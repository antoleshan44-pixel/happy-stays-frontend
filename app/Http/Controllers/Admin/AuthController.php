<?php
// File: app/Http/Controllers/Admin/AuthController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SpringBootApiService;
use App\Services\Admin\AdminAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    protected $apiService;
    protected $adminAuthService;

    public function __construct(
        SpringBootApiService $apiService,
        AdminAuthService $adminAuthService
    ) {
        $this->apiService = $apiService;
        $this->adminAuthService = $adminAuthService;
    }

    /**
     * Show admin login form
     */
    public function showLogin()
    {
        // If already logged in as admin, redirect to dashboard
        if (session()->has('admin_logged_in')) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    /**
     * Process admin login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'captcha' => 'required|captcha',
        ]);

        $ipAddress = $request->ip();
        $userAgent = $request->userAgent();

        // Check rate limiting
        $attemptKey = "admin_login_attempts:{$ipAddress}";
        $attempts = Cache::get($attemptKey, 0);

        if ($attempts >= 5) {
            $lockedUntil = Cache::get("admin_login_locked:{$ipAddress}");
            if ($lockedUntil && now()->lessThan($lockedUntil)) {
                Log::warning('Admin login rate limit exceeded', [
                    'ip' => $ipAddress,
                    'attempts' => $attempts
                ]);
                return back()->withErrors([
                    'email' => 'Too many login attempts. Please try again after 15 minutes.'
                ]);
            }
        }

        // Call Spring Boot API for admin authentication
        $result = $this->apiService->adminLogin($request->email, $request->password);

        if (!$result['success']) {
            // FIXED: Use Cache::put() instead of increment() + expire()
            Cache::put($attemptKey, $attempts + 1, 900); // 15 minutes TTL

            Log::warning('Admin login failed', [
                'email' => $request->email,
                'ip' => $ipAddress,
                'reason' => $result['message'] ?? 'Invalid credentials'
            ]);

            return back()->withErrors([
                'email' => $result['message'] ?? 'Invalid credentials'
            ])->withInput($request->only('email'));
        }

        $admin = $result['admin'];

        // Check admin status
        if ($admin['status'] === 'suspended') {
            Log::warning('Suspended admin attempted login', [
                'admin_id' => $admin['id'],
                'email' => $admin['email'],
                'ip' => $ipAddress
            ]);
            return back()->withErrors([
                'email' => 'Your account has been suspended. Please contact system administrator.'
            ]);
        }

        if ($admin['status'] === 'locked') {
            Log::warning('Locked admin attempted login', [
                'admin_id' => $admin['id'],
                'email' => $admin['email'],
                'ip' => $ipAddress
            ]);
            return back()->withErrors([
                'email' => 'Account locked due to security concerns. Please contact system administrator.'
            ]);
        }

        // Check if 2FA is enabled
        if ($admin['two_factor_enabled']) {
            session(['admin_2fa_pending' => true, 'admin_id' => $admin['id']]);
            return redirect()->route('admin.2fa.verify');
        }

        // Complete login process
        return $this->completeLogin($admin, $ipAddress, $userAgent);
    }

    /**
     * Show 2FA verification form
     */
    public function show2faVerification()
    {
        if (!session('admin_2fa_pending')) {
            return redirect()->route('admin.login');
        }

        return view('admin.auth.2fa-verify');
    }

    /**
     * Verify 2FA code
     */
    public function verify2fa(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6'
        ]);

        $adminId = session('admin_id');
        if (!$adminId) {
            return redirect()->route('admin.login');
        }

        $admin = $this->apiService->getAdminById($adminId);

        if (!$this->adminAuthService->verifyTwoFactorCode($admin, $request->code)) {
            Log::warning('Admin 2FA verification failed', [
                'admin_id' => $adminId,
                'ip' => $request->ip()
            ]);
            return back()->withErrors(['code' => 'Invalid verification code']);
        }

        // Clear 2FA session data
        session()->forget(['admin_2fa_pending', 'admin_id']);

        return $this->completeLogin($admin, $request->ip(), $request->userAgent());
    }

    /**
     * Complete the login process after successful authentication
     */
    protected function completeLogin($admin, $ipAddress, $userAgent)
    {
        // Generate device fingerprint
        $deviceFingerprint = $this->adminAuthService->generateDeviceFingerprint(
            $ipAddress,
            $userAgent,
            $admin['email']
        );

        // Check for suspicious login
        $isSuspicious = $this->adminAuthService->detectSuspiciousLogin(
            $admin['id'],
            $ipAddress,
            $deviceFingerprint
        );

        // Get admin permissions
        $permissions = $this->adminAuthService->getAdminPermissions($admin['role']);

        // Create admin session
        session([
            'admin_logged_in' => true,
            'admin_id' => $admin['id'],
            'admin_name' => $admin['name'],
            'admin_email' => $admin['email'],
            'admin_role' => $admin['role'],
            'admin_permissions' => $permissions,
            'admin_device_fingerprint' => $deviceFingerprint,
            'admin_login_time' => now()->toDateTimeString(),
        ]);

        // Log successful login
        $this->adminAuthService->logLogin(
            $admin['id'],
            $ipAddress,
            $userAgent,
            $deviceFingerprint,
            !$isSuspicious
        );

        // Generate JWT token for API access
        $jwtToken = $this->apiService->generateAdminToken($admin);
        session(['admin_api_token' => $jwtToken]);

        // Clear failed attempts cache
        $attemptKey = "admin_login_attempts:{$ipAddress}";
        Cache::forget($attemptKey);

        Log::info('Admin logged in successfully', [
            'admin_id' => $admin['id'],
            'admin_role' => $admin['role'],
            'ip' => $ipAddress,
            'suspicious' => $isSuspicious,
        ]);

        // If suspicious login detected, show warning
        if ($isSuspicious) {
            session(['admin_suspicious_login_warning' => true]);
            $this->adminAuthService->sendSuspiciousLoginAlert($admin, $ipAddress);
        }

        return redirect()->route('admin.dashboard');
    }

    /**
     * Show forgot password form
     */
    public function showForgotPassword()
    {
        return view('admin.auth.forgot-password');
    }

    /**
     * Send password reset link
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $result = $this->apiService->sendAdminPasswordResetLink($request->email);

        if ($result['success']) {
            return back()->with('success', 'Password reset link sent to your email address.');
        }

        return back()->withErrors(['email' => $result['message'] ?? 'Unable to send reset link']);
    }

    /**
     * Show password reset form
     */
    public function showResetForm($token)
    {
        return view('admin.auth.reset-password', ['token' => $token]);
    }

    /**
     * Reset password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $result = $this->apiService->resetAdminPassword([
            'token' => $request->token,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        if ($result['success']) {
            return redirect()->route('admin.login')->with('success', 'Password reset successful. Please login with your new password.');
        }

        return back()->withErrors(['email' => $result['message'] ?? 'Password reset failed']);
    }

    /**
     * Logout admin
     */
    public function logout(Request $request)
    {
        $adminId = session('admin_id');
        $adminName = session('admin_name');

        Log::info('Admin logged out', [
            'admin_id' => $adminId,
            'admin_name' => $adminName,
            'ip' => $request->ip(),
        ]);

        // Log logout to history
        $this->adminAuthService->logLogout($adminId);

        // Invalidate API token
        if ($token = session('admin_api_token')) {
            $this->apiService->invalidateAdminToken($token);
        }

        // Clear all admin session data
        session()->forget([
            'admin_logged_in',
            'admin_id',
            'admin_name',
            'admin_email',
            'admin_role',
            'admin_permissions',
            'admin_device_fingerprint',
            'admin_login_time',
            'admin_api_token',
            'admin_suspicious_login_warning',
        ]);

        // Regenerate session ID for security
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Logged out successfully');
    }
}

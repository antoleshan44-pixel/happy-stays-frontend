<?php
// File: app/Services/Admin/AdminAuthService.php
// LOCATION: C:\xampp\htdocs\eserian-homes\app\Services\Admin\AdminAuthService.php
// UPDATED - Added error handling for missing tables

namespace App\Services\Admin;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\AdminLoginHistory;
use App\Models\BlacklistedIp;
use App\Models\Notification;
use Exception;

class AdminAuthService
{
    /**
     * Generate device fingerprint from IP, user agent, and email
     */
    public function generateDeviceFingerprint($ipAddress, $userAgent, $email): string
    {
        $data = $ipAddress . $userAgent . $email;
        return hash('sha256', $data);
    }

    /**
     * Detect suspicious login attempts
     */
    public function detectSuspiciousLogin($adminId, $ipAddress, $deviceFingerprint): bool
    {
        $isSuspicious = false;

        // Check 1: New IP address for this admin
        try {
            $previousLogins = AdminLoginHistory::where('admin_id', $adminId)
                ->where('status', 'success')
                ->where('ip_address', '!=', $ipAddress)
                ->count();

            if ($previousLogins === 0) {
                $isSuspicious = true;
                Log::info('Admin login from new IP address', [
                    'admin_id' => $adminId,
                    'ip' => $ipAddress
                ]);
            }
        } catch (Exception $e) {
            Log::warning('Could not check login history (table may not exist): ' . $e->getMessage());
        }

        // Check 2: IP blacklist
        try {
            $blacklistedIp = BlacklistedIp::where('ip_address', $ipAddress)
                ->where(function($q) {
                    $q->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
                })
                ->exists();

            if ($blacklistedIp) {
                $isSuspicious = true;
                Log::warning('Admin login from blacklisted IP', [
                    'admin_id' => $adminId,
                    'ip' => $ipAddress
                ]);
            }
        } catch (Exception $e) {
            Log::warning('Could not check IP blacklist: ' . $e->getMessage());
        }

        // Check 3: Multiple admins from same device
        try {
            $adminsFromDevice = AdminLoginHistory::where('device_fingerprint', $deviceFingerprint)
                ->where('admin_id', '!=', $adminId)
                ->where('created_at', '>=', now()->subHours(24))
                ->count();

            if ($adminsFromDevice >= 2) {
                $isSuspicious = true;
                Log::warning('Multiple admins from same device', [
                    'admin_id' => $adminId,
                    'device_fingerprint' => $deviceFingerprint,
                    'count' => $adminsFromDevice
                ]);
            }
        } catch (Exception $e) {
            Log::warning('Could not check multiple admins: ' . $e->getMessage());
        }

        return $isSuspicious;
    }

    /**
     * Get admin permissions based on role
     */
    public function getAdminPermissions($role): array
    {
        $permissions = [
            'super_admin' => ['*'],
            'property_moderator' => [
                'properties.view', 'properties.approve', 'properties.reject',
                'properties.suspend', 'properties.archive', 'properties.edit',
                'photos.moderate', 'videos.moderate', 'reviews.moderate',
            ],
            'finance_admin' => [
                'payments.view', 'payments.process_refunds', 'payouts.approve',
                'payouts.process', 'commissions.configure', 'reports.financial',
                'transactions.view',
            ],
            'support_admin' => [
                'users.view', 'users.suspend', 'disputes.manage', 'tickets.manage',
                'bookings.view', 'refunds.process', 'messages.view',
            ],
            'fraud_analyst' => [
                'fraud.view', 'fraud.investigate', 'fraud.flag', 'fraud.blacklist',
                'users.view', 'transactions.view', 'reports.fraud',
            ],
            'content_moderator' => [
                'reviews.moderate', 'photos.moderate', 'videos.moderate',
                'messages.moderate', 'cms.edit', 'announcements.create',
            ],
            'marketing_admin' => [
                'promotions.manage', 'campaigns.create', 'newsletters.send',
                'analytics.view', 'featured.listings', 'discounts.create',
            ],
        ];

        return $permissions[$role] ?? [];
    }

    /**
     * Verify two-factor authentication code
     */
    public function verifyTwoFactorCode($admin, $code): bool
    {
        // This would verify against TOTP (Google Authenticator)
        if (isset($admin['two_factor_secret'])) {
            $expectedCode = $this->generateTOTP($admin['two_factor_secret']);
            return $code === $expectedCode;
        }
        return false;
    }

    /**
     * Generate TOTP code (simplified for demo)
     */
    protected function generateTOTP($secret): string
    {
        $time = floor(time() / 30);
        return substr(hash_hmac('sha1', (string)$time, $secret), 0, 6);
    }

    /**
     * Log admin login
     */
    public function logLogin($adminId, $ipAddress, $userAgent, $deviceFingerprint, $isSuccess): void
    {
        try {
            AdminLoginHistory::create([
                'admin_id' => $adminId,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'device_fingerprint' => $deviceFingerprint,
                'status' => $isSuccess ? 'success' : 'failed',
                'login_method' => 'password',
                'created_at' => now(),
            ]);
        } catch (Exception $e) {
            Log::warning('Could not log admin login (table may not exist): ' . $e->getMessage());
        }
    }

    /**
     * Log admin logout
     */
    public function logLogout($adminId): void
    {
        Log::info('Admin logout logged', ['admin_id' => $adminId]);
    }

    /**
     * Send suspicious login alert
     */
    public function sendSuspiciousLoginAlert($admin, $ipAddress): void
    {
        // Send email notification
        try {
            Mail::send('emails.admin.suspicious-login', [
                'admin_name' => $admin['name'],
                'admin_email' => $admin['email'],
                'ip_address' => $ipAddress,
                'time' => now()->toDateTimeString(),
            ], function ($message) use ($admin) {
                $message->to($admin['email'])
                        ->subject('Suspicious Login Detected - Eserian Admin Panel');
            });
        } catch (Exception $e) {
            Log::warning('Could not send suspicious login email: ' . $e->getMessage());
        }

        // Create internal notification
        try {
            Notification::create([
                'user_id' => $admin['id'],
                'type' => 'security_alert',
                'title' => 'Suspicious Login Detected',
                'message' => "We detected a login to your admin account from a new IP address: {$ipAddress}",
                'is_admin_notification' => true,
                'created_at' => now(),
            ]);
        } catch (Exception $e) {
            Log::warning('Could not create notification (table may not exist): ' . $e->getMessage());
        }
    }
}

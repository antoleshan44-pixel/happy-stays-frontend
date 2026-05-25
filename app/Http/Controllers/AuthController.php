<?php

namespace App\Http\Controllers;

use App\Services\SpringBootApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    protected $api;

    public function __construct(SpringBootApiService $api)
    {
        $this->api = $api;
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        Log::info('Login attempt', ['email' => $credentials['email']]);

        // Call Spring Boot API for login
        $result = $this->api->login($credentials['email'], $credentials['password']);

        if (isset($result['token'])) {
            // Login successful
            $user = $result['user'];

            // Store user data in session
            session(['user' => $user]);
            session(['api_token' => $result['token']]);

            // Verify session was set
            Log::info('Session after login', [
                'session_user' => session('user'),
                'session_id' => session()->getId()
            ]);

            Log::info('Login successful', ['email' => $user['email'], 'role' => $user['role']]);

            // ALL USERS redirect to home page (navbar will handle role-based menu)
            return redirect()->route('home.authenticated');
        }

        // Login failed
        Log::warning('Login failed', ['email' => $credentials['email'], 'error' => $result['message'] ?? 'Invalid credentials']);

        return back()->withErrors([
            'email' => $result['message'] ?? 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function register(Request $request)
    {
        Log::info('Registration attempt', $request->all());

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
            'phone' => 'required|string|min:10|max:13',
            'role' => 'required|in:customer,owner'
        ]);

        try {
            // Call Spring Boot API for registration
            $result = $this->api->register([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => $validated['password'],
                'confirmPassword' => $validated['password'],
                'phone' => $validated['phone'],
                'role' => strtoupper($validated['role'])
            ]);

            if (isset($result['token'])) {
                $user = $result['user'];

                Log::info('User created successfully via Spring Boot', [
                    'user_id' => $user['id'],
                    'email' => $user['email'],
                    'role' => $user['role']
                ]);

                // Store user in session
                session(['user' => $user]);
                session(['api_token' => $result['token']]);

                // ALL USERS redirect to home page (navbar will handle role-based menu)
                return redirect()->route('home.authenticated')->with('success', 'Welcome! Your account has been created.');
            }

            Log::error('Registration failed via Spring Boot', ['error' => $result['message'] ?? 'Unknown error']);
            return back()->with('error', $result['message'] ?? 'Registration failed. Please try again.')->withInput();

        } catch (\Exception $e) {
            Log::error('Registration exception', ['error' => $e->getMessage()]);
            return back()->with('error', 'Registration failed: ' . $e->getMessage())->withInput();
        }
    }

    public function logout(Request $request)
    {
        // Call Spring Boot API logout (clears session)
        $this->api->logout();

        // Clear Laravel session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}

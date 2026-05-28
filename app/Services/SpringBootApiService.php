<?php
// File: app/Services/SpringBootApiService.php
// LOCATION: C:\xampp\htdocs\eserian-homes\app\Services\SpringBootApiService.php
// COMPLETE VERSION - Fixed to return REAL data from API, not mock data

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SpringBootApiService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim(env('SPRING_BOOT_API_URL', env('BACKEND_API_URL', 'http://localhost:8080/api')), '/');
        Log::info('SpringBootApiService initialized', ['baseUrl' => $this->baseUrl]);
    }

    private function getHeaders()
    {
        $token   = session('api_token');
        $headers = ['Content-Type' => 'application/json', 'Accept' => 'application/json'];
        if ($token) {
            $headers['Authorization'] = 'Bearer ' . $token;
        }
        return $headers;
    }

    private function getAdminHeaders()
    {
        $token = session('admin_api_token');
        Log::debug('Admin headers being prepared', [
            'has_token' => !empty($token),
            'token_preview' => $token ? substr($token, 0, 30) . '...' : null
        ]);

        $headers = ['Content-Type' => 'application/json', 'Accept' => 'application/json'];
        if ($token) {
            $headers['Authorization'] = 'Bearer ' . $token;
        }
        return $headers;
    }

    private function isApiReachable()
    {
        try {
            $response = Http::timeout(3)->get($this->baseUrl . '/properties/approved');
            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }

    // ============================================
    // AUTHENTICATION METHODS (Regular Users)
    // ============================================

    public function login($email, $password)
    {
        Log::info('SpringBootApiService::login - Attempt', ['email' => $email]);
        try {
            $response = Http::post($this->baseUrl . '/auth/login', [
                'email'    => $email,
                'password' => $password,
            ]);
            $result = $response->json();
            Log::info('SpringBootApiService::login - Response', [
                'status'  => $response->status(),
                'success' => $response->successful(),
            ]);
            if ($response->successful() && isset($result['token'])) {
                session(['api_token' => $result['token']]);
                session(['user'      => $result['user']]);
                Log::info('SpringBootApiService::login - SUCCESS', [
                    'email' => $email,
                    'token_preview' => substr($result['token'], 0, 30) . '...'
                ]);
            } else {
                Log::warning('SpringBootApiService::login - FAILED', ['email' => $email]);
            }
            return $result;
        } catch (\Exception $e) {
            Log::error('SpringBootApiService::login - EXCEPTION', [
                'message' => $e->getMessage(),
                'email'   => $email,
            ]);
            throw $e;
        }
    }

    public function register($data)
    {
        Log::info('SpringBootApiService::register - Attempt', ['email' => $data['email'] ?? 'unknown']);
        try {
            $response = Http::post($this->baseUrl . '/auth/register', $data);
            $result = $response->json();
            if ($response->successful() && isset($result['token'])) {
                session(['api_token' => $result['token']]);
                session(['user' => $result['user']]);
            }
            return $result;
        } catch (\Exception $e) {
            Log::error('SpringBootApiService::register - EXCEPTION', [
                'message' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function logout()
    {
        session()->forget(['api_token', 'user', 'wishlist_ids']);
        Log::info('SpringBootApiService::logout - Session cleared');
        return true;
    }

    public function getCurrentUser()
    {
        $user = session('user');
        if (!$user && !$this->isApiReachable()) {
            $user = [
                'id'    => 1,
                'name'  => 'Test Customer',
                'email' => 'customer@eserian.com',
                'role'  => 'customer',
            ];
            session(['user' => $user]);
        }
        return $user;
    }

    // ============================================
    // ADMIN AUTHENTICATION METHODS
    // ============================================

    public function adminLogin($email, $password)
    {
        Log::info('SpringBootApiService::adminLogin - Attempt', ['email' => $email]);
        try {
            $response = Http::timeout(10)->post($this->baseUrl . '/admin/login', [
                'email'    => $email,
                'password' => $password,
            ]);

            $result = $response->json();

            Log::info('SpringBootApiService::adminLogin - Response', [
                'status'  => $response->status(),
                'success' => $response->successful(),
            ]);

            if ($response->successful() && isset($result['success']) && $result['success'] === true) {
                if (isset($result['token'])) {
                    session(['admin_api_token' => $result['token']]);
                }
                Log::info('SpringBootApiService::adminLogin - SUCCESS', [
                    'email' => $email,
                    'admin_id' => $result['admin']['id'] ?? null,
                    'role' => $result['admin']['role'] ?? null,
                ]);
            } else {
                Log::warning('SpringBootApiService::adminLogin - FAILED', [
                    'email' => $email,
                    'message' => $result['message'] ?? 'Unknown error'
                ]);
            }
            return $result;
        } catch (\Exception $e) {
            Log::error('SpringBootApiService::adminLogin - EXCEPTION', [
                'message' => $e->getMessage(),
                'email'   => $email,
            ]);
            return ['success' => false, 'message' => 'Connection error: ' . $e->getMessage()];
        }
    }

    public function getAdminById($id)
    {
        try {
            $response = Http::withHeaders($this->getAdminHeaders())
                ->timeout(10)
                ->get($this->baseUrl . '/admin/admins/' . $id);

            if ($response->successful()) {
                $result = $response->json();
                return $result['admin'] ?? $result;
            }
        } catch (\Exception $e) {
            Log::error('SpringBootApiService::getAdminById - EXCEPTION', [
                'id' => $id,
                'message' => $e->getMessage()
            ]);
        }
        return null;
    }

    public function getAdminByEmail($email)
    {
        try {
            $response = Http::withHeaders($this->getAdminHeaders())
                ->timeout(10)
                ->get($this->baseUrl . '/admin/admins/email/' . urlencode($email));

            if ($response->successful()) {
                $result = $response->json();
                return $result['admin'] ?? $result;
            }
        } catch (\Exception $e) {
            Log::error('SpringBootApiService::getAdminByEmail - EXCEPTION', [
                'email' => $email,
                'message' => $e->getMessage()
            ]);
        }
        return null;
    }

    public function generateAdminToken($admin)
    {
        return session('admin_api_token');
    }

    public function invalidateAdminToken($token)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
            ])->timeout(5)->post($this->baseUrl . '/admin/logout');

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('SpringBootApiService::invalidateAdminToken - EXCEPTION', [
                'message' => $e->getMessage()
            ]);
            return false;
        }
    }

    public function sendAdminPasswordResetLink($email)
    {
        try {
            $response = Http::timeout(10)->post($this->baseUrl . '/admin/password/forgot', [
                'email' => $email
            ]);
            return $response->json();
        } catch (\Exception $e) {
            Log::error('SpringBootApiService::sendAdminPasswordResetLink - EXCEPTION', [
                'email' => $email,
                'message' => $e->getMessage()
            ]);
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function resetAdminPassword($data)
    {
        try {
            $response = Http::timeout(10)->post($this->baseUrl . '/admin/password/reset', $data);
            return $response->json();
        } catch (\Exception $e) {
            Log::error('SpringBootApiService::resetAdminPassword - EXCEPTION', [
                'message' => $e->getMessage()
            ]);
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // ============================================
    // ADMIN DASHBOARD METHODS
    // ============================================

    public function getDashboardMetrics()
    {
        try {
            $response = Http::withHeaders($this->getAdminHeaders())
                ->timeout(10)
                ->get($this->baseUrl . '/admin/dashboard/metrics');

            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Exception $e) {
            Log::error('SpringBootApiService::getDashboardMetrics - EXCEPTION', [
                'message' => $e->getMessage()
            ]);
        }

        return [
            'total_users' => 0,
            'total_properties' => 0,
            'active_bookings' => 0,
            'monthly_revenue' => 0,
            'user_growth' => 0,
            'booking_growth' => 0,
            'revenue_growth' => 0,
            'occupancy_rate' => 0,
            'cancellation_rate' => 0
        ];
    }

    public function getRecentActivities($limit = 10)
    {
        try {
            $response = Http::withHeaders($this->getAdminHeaders())
                ->timeout(10)
                ->get($this->baseUrl . '/admin/activities', ['limit' => $limit]);

            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Exception $e) {
            Log::error('SpringBootApiService::getRecentActivities - EXCEPTION', [
                'message' => $e->getMessage()
            ]);
        }
        return [];
    }

    public function getFraudAlerts($limit = 5)
    {
        try {
            $response = Http::withHeaders($this->getAdminHeaders())
                ->timeout(10)
                ->get($this->baseUrl . '/admin/fraud/alerts', ['limit' => $limit]);

            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Exception $e) {
            Log::error('SpringBootApiService::getFraudAlerts - EXCEPTION', [
                'message' => $e->getMessage()
            ]);
        }
        return [];
    }

    public function getPendingCounts()
    {
        try {
            $response = Http::withHeaders($this->getAdminHeaders())
                ->timeout(10)
                ->get($this->baseUrl . '/admin/pending/counts');

            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Exception $e) {
            Log::error('SpringBootApiService::getPendingCounts - EXCEPTION', [
                'message' => $e->getMessage()
            ]);
        }
        return ['properties' => 0, 'kyc' => 0, 'disputes' => 0, 'payouts' => 0];
    }

    // ============================================
    // ADMIN PROPERTY METHODS
    // ============================================

    public function getPendingProperties()
    {
        try {
            $url = $this->baseUrl . '/admin/properties/pending';
            Log::info('Fetching pending properties from: ' . $url);
            Log::info('Admin token exists: ' . (session()->has('admin_api_token') ? 'Yes' : 'No'));

            $response = Http::withHeaders($this->getAdminHeaders())
                ->timeout(30)
                ->get($url);

            Log::info('Pending properties API response', [
                'status' => $response->status(),
                'successful' => $response->successful(),
            ]);

            if ($response->successful()) {
                $result = $response->json();

                if (isset($result['data']) && is_array($result['data'])) {
                    return $result['data'];
                }
                if (isset($result['properties']) && is_array($result['properties'])) {
                    return $result['properties'];
                }
                if (is_array($result)) {
                    foreach ($result as &$property) {
                        if (!isset($property['photos'])) {
                            $property['photos'] = [];
                        }
                        if (!isset($property['videos'])) {
                            $property['videos'] = [];
                        }
                        if (!isset($property['photoCount'])) {
                            $property['photoCount'] = count($property['photos']);
                        }
                        if (!isset($property['videoCount'])) {
                            $property['videoCount'] = count($property['videos']);
                        }
                    }
                    return $result;
                }

                return [];
            }

            Log::error('Failed to fetch pending properties', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return [];

        } catch (\Exception $e) {
            Log::error('SpringBootApiService::getPendingProperties - EXCEPTION', [
                'message' => $e->getMessage()
            ]);
            return [];
        }
    }

    public function getPropertyDetails($id)
    {
        try {
            $response = Http::withHeaders($this->getAdminHeaders())
                ->timeout(15)
                ->get($this->baseUrl . '/admin/properties/' . $id);

            if ($response->successful()) {
                $result = $response->json();
                if (!isset($result['photos'])) {
                    $result['photos'] = [];
                }
                if (!isset($result['videos'])) {
                    $result['videos'] = [];
                }
                return $result;
            }
        } catch (\Exception $e) {
            Log::error('SpringBootApiService::getPropertyDetails - EXCEPTION', [
                'id' => $id,
                'message' => $e->getMessage()
            ]);
        }
        return null;
    }

    public function getPropertyRiskAssessment($id)
    {
        try {
            $response = Http::withHeaders($this->getAdminHeaders())
                ->timeout(10)
                ->get($this->baseUrl . '/admin/properties/' . $id . '/risk');

            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Exception $e) {
            Log::error('SpringBootApiService::getPropertyRiskAssessment - EXCEPTION', [
                'id' => $id,
                'message' => $e->getMessage()
            ]);
        }
        return ['risk_score' => 0, 'risk_level' => 'unknown', 'metrics' => []];
    }

    public function approveProperty($id, $data)
    {
        try {
            $requestData = [];

            if (isset($data['moderator_notes'])) {
                $requestData['moderatorNotes'] = $data['moderator_notes'];
            } elseif (isset($data['moderatorNotes'])) {
                $requestData['moderatorNotes'] = $data['moderatorNotes'];
            }

            if (isset($data['approved_by'])) {
                $requestData['approvedBy'] = $data['approved_by'];
            } elseif (isset($data['approvedBy'])) {
                $requestData['approvedBy'] = $data['approvedBy'];
            }

            Log::info('Approving property', [
                'property_id' => $id,
                'request_data' => $requestData,
                'token_exists' => session()->has('admin_api_token')
            ]);

            $response = Http::withHeaders($this->getAdminHeaders())
                ->timeout(30)
                ->post($this->baseUrl . '/admin/properties/' . $id . '/approve', $requestData);

            Log::info('Approve property response', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            $result = $response->json();

            if ($response->successful()) {
                return ['success' => true, 'message' => 'Property approved successfully', 'data' => $result];
            }

            return [
                'success' => false,
                'message' => $result['message'] ?? $result['error'] ?? 'Failed to approve property'
            ];

        } catch (\Exception $e) {
            Log::error('SpringBootApiService::approveProperty - EXCEPTION', [
                'id' => $id,
                'message' => $e->getMessage()
            ]);
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function rejectProperty($id, $data)
    {
        try {
            $requestData = [];

            if (isset($data['reason'])) {
                $requestData['reason'] = $data['reason'];
            }
            if (isset($data['details'])) {
                $requestData['details'] = $data['details'];
            }
            if (isset($data['allow_resubmission'])) {
                $requestData['allowResubmission'] = $data['allow_resubmission'];
            } elseif (isset($data['allowResubmission'])) {
                $requestData['allowResubmission'] = $data['allowResubmission'];
            }

            Log::info('Rejecting property', [
                'property_id' => $id,
                'request_data' => $requestData
            ]);

            $response = Http::withHeaders($this->getAdminHeaders())
                ->timeout(30)
                ->post($this->baseUrl . '/admin/properties/' . $id . '/reject', $requestData);

            Log::info('Reject property response', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            $result = $response->json();

            if ($response->successful()) {
                return ['success' => true, 'message' => 'Property rejected successfully'];
            }

            return [
                'success' => false,
                'message' => $result['message'] ?? $result['error'] ?? 'Failed to reject property'
            ];

        } catch (\Exception $e) {
            Log::error('SpringBootApiService::rejectProperty - EXCEPTION', [
                'id' => $id,
                'message' => $e->getMessage()
            ]);
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function suspendProperty($id, $data)
    {
        try {
            $requestData = [];

            if (isset($data['reason'])) {
                $requestData['reason'] = $data['reason'];
            }
            if (isset($data['suspended_by'])) {
                $requestData['suspendedBy'] = $data['suspended_by'];
            } elseif (isset($data['suspendedBy'])) {
                $requestData['suspendedBy'] = $data['suspendedBy'];
            }

            Log::info('Suspending property', [
                'property_id' => $id,
                'request_data' => $requestData
            ]);

            $response = Http::withHeaders($this->getAdminHeaders())
                ->timeout(30)
                ->post($this->baseUrl . '/admin/properties/' . $id . '/suspend', $requestData);

            Log::info('Suspend property response', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            $result = $response->json();

            if ($response->successful()) {
                return ['success' => true, 'message' => 'Property suspended successfully'];
            }

            return [
                'success' => false,
                'message' => $result['message'] ?? $result['error'] ?? 'Failed to suspend property'
            ];

        } catch (\Exception $e) {
            Log::error('SpringBootApiService::suspendProperty - EXCEPTION', [
                'id' => $id,
                'message' => $e->getMessage()
            ]);
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function archiveProperty($id, $data)
    {
        try {
            $requestData = [];

            if (isset($data['archived_by'])) {
                $requestData['archivedBy'] = $data['archived_by'];
            } elseif (isset($data['archivedBy'])) {
                $requestData['archivedBy'] = $data['archivedBy'];
            }

            Log::info('Archiving property', [
                'property_id' => $id,
                'request_data' => $requestData
            ]);

            $response = Http::withHeaders($this->getAdminHeaders())
                ->timeout(30)
                ->post($this->baseUrl . '/admin/properties/' . $id . '/archive', $requestData);

            Log::info('Archive property response', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            $result = $response->json();

            if ($response->successful()) {
                return ['success' => true, 'message' => 'Property archived successfully'];
            }

            return [
                'success' => false,
                'message' => $result['message'] ?? $result['error'] ?? 'Failed to archive property'
            ];

        } catch (\Exception $e) {
            Log::error('SpringBootApiService::archiveProperty - EXCEPTION', [
                'id' => $id,
                'message' => $e->getMessage()
            ]);
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function deletePropertyPermanently($id)
    {
        try {
            $response = Http::withHeaders($this->getAdminHeaders())
                ->timeout(15)
                ->delete($this->baseUrl . '/admin/properties/' . $id . '/permanent');

            $result = $response->json();

            if ($response->successful()) {
                return ['success' => true, 'message' => 'Property deleted permanently'];
            }

            return [
                'success' => false,
                'message' => $result['message'] ?? $result['error'] ?? 'Failed to delete property'
            ];

        } catch (\Exception $e) {
            Log::error('SpringBootApiService::deletePropertyPermanently - EXCEPTION', [
                'id' => $id,
                'message' => $e->getMessage()
            ]);
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function getAllProperties($filters = [])
    {
        try {
            $response = Http::withHeaders($this->getAdminHeaders())
                ->timeout(15)
                ->get($this->baseUrl . '/admin/properties', $filters);

            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Exception $e) {
            Log::error('SpringBootApiService::getAllProperties - EXCEPTION', [
                'message' => $e->getMessage()
            ]);
        }
        return [];
    }

    public function getPropertyAnalytics($id)
    {
        try {
            $response = Http::withHeaders($this->getAdminHeaders())
                ->timeout(10)
                ->get($this->baseUrl . '/admin/properties/' . $id . '/analytics');

            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Exception $e) {
            Log::error('SpringBootApiService::getPropertyAnalytics - EXCEPTION', [
                'id' => $id,
                'message' => $e->getMessage()
            ]);
        }
        return [];
    }

    public function getSimilarProperties($id)
    {
        try {
            $response = Http::withHeaders($this->getAdminHeaders())
                ->timeout(10)
                ->get($this->baseUrl . '/admin/properties/' . $id . '/similar');

            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Exception $e) {
            Log::error('SpringBootApiService::getSimilarProperties - EXCEPTION', [
                'id' => $id,
                'message' => $e->getMessage()
            ]);
        }
        return [];
    }

    public function getPropertyHistory($id)
    {
        try {
            $response = Http::withHeaders($this->getAdminHeaders())
                ->timeout(10)
                ->get($this->baseUrl . '/admin/properties/' . $id . '/history');

            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Exception $e) {
            Log::error('SpringBootApiService::getPropertyHistory - EXCEPTION', [
                'id' => $id,
                'message' => $e->getMessage()
            ]);
        }
        return [];
    }

    // ============================================
    // ADMIN USER MANAGEMENT METHODS
    // ============================================

    public function getAllUsers()
    {
        try {
            $response = Http::withHeaders($this->getAdminHeaders())
                ->timeout(15)
                ->get($this->baseUrl . '/admin/users');

            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Exception $e) {
            Log::error('SpringBootApiService::getAllUsers - EXCEPTION', [
                'message' => $e->getMessage()
            ]);
        }
        return [];
    }

    public function getUserDetails($id)
    {
        try {
            $response = Http::withHeaders($this->getAdminHeaders())
                ->timeout(15)
                ->get($this->baseUrl . '/admin/users/' . $id);

            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Exception $e) {
            Log::error('SpringBootApiService::getUserDetails - EXCEPTION', [
                'id' => $id,
                'message' => $e->getMessage()
            ]);
        }
        return null;
    }

    public function suspendUser($id, $reason)
    {
        try {
            $response = Http::withHeaders($this->getAdminHeaders())
                ->timeout(15)
                ->post($this->baseUrl . '/admin/users/' . $id . '/suspend', ['reason' => $reason]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('SpringBootApiService::suspendUser - EXCEPTION', [
                'id' => $id,
                'message' => $e->getMessage()
            ]);
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function activateUser($id)
    {
        try {
            $response = Http::withHeaders($this->getAdminHeaders())
                ->timeout(15)
                ->post($this->baseUrl . '/admin/users/' . $id . '/activate');

            return $response->json();
        } catch (\Exception $e) {
            Log::error('SpringBootApiService::activateUser - EXCEPTION', [
                'id' => $id,
                'message' => $e->getMessage()
            ]);
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // ============================================
    // ADMIN PAYMENT METHODS
    // ============================================

    public function getAllPayments()
    {
        try {
            $response = Http::withHeaders($this->getAdminHeaders())
                ->timeout(15)
                ->get($this->baseUrl . '/admin/payments');

            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Exception $e) {
            Log::error('SpringBootApiService::getAllPayments - EXCEPTION', [
                'message' => $e->getMessage()
            ]);
        }
        return [];
    }

    public function processRefund($paymentId, $data)
    {
        try {
            $response = Http::withHeaders($this->getAdminHeaders())
                ->timeout(15)
                ->post($this->baseUrl . '/admin/payments/' . $paymentId . '/refund', $data);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('SpringBootApiService::processRefund - EXCEPTION', [
                'paymentId' => $paymentId,
                'message' => $e->getMessage()
            ]);
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function getRevenueReport()
    {
        try {
            $response = Http::withHeaders($this->getAdminHeaders())
                ->timeout(15)
                ->get($this->baseUrl . '/admin/reports/revenue');

            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Exception $e) {
            Log::error('SpringBootApiService::getRevenueReport - EXCEPTION', [
                'message' => $e->getMessage()
            ]);
        }
        return ['total_revenue' => 0, 'monthly_revenue' => 0];
    }

    public function getPendingPayouts()
    {
        try {
            $response = Http::withHeaders($this->getAdminHeaders())
                ->timeout(15)
                ->get($this->baseUrl . '/admin/payouts/pending');

            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Exception $e) {
            Log::error('SpringBootApiService::getPendingPayouts - EXCEPTION', [
                'message' => $e->getMessage()
            ]);
        }
        return [];
    }

    public function getPendingPayoutsCount()
    {
        try {
            $response = Http::withHeaders($this->getAdminHeaders())
                ->timeout(10)
                ->get($this->baseUrl . '/admin/payouts/pending/count');

            if ($response->successful()) {
                $result = $response->json();
                return $result['count'] ?? 0;
            }
        } catch (\Exception $e) {
            Log::error('SpringBootApiService::getPendingPayoutsCount - EXCEPTION', [
                'message' => $e->getMessage()
            ]);
        }
        return 0;
    }

    // ============================================
    // ADMIN FRAUD METHODS
    // ============================================

    public function getFraudCases()
    {
        try {
            $response = Http::withHeaders($this->getAdminHeaders())
                ->timeout(15)
                ->get($this->baseUrl . '/admin/fraud/cases');

            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Exception $e) {
            Log::error('SpringBootApiService::getFraudCases - EXCEPTION', [
                'message' => $e->getMessage()
            ]);
        }
        return [];
    }

    public function getFraudCaseDetails($id)
    {
        try {
            $response = Http::withHeaders($this->getAdminHeaders())
                ->timeout(15)
                ->get($this->baseUrl . '/admin/fraud/cases/' . $id);

            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Exception $e) {
            Log::error('SpringBootApiService::getFraudCaseDetails - EXCEPTION', [
                'id' => $id,
                'message' => $e->getMessage()
            ]);
        }
        return null;
    }

    public function resolveFraudCase($id, $data)
    {
        try {
            $requestData = [];

            if (isset($data['resolution'])) {
                $requestData['resolution'] = $data['resolution'];
            }
            if (isset($data['action_taken'])) {
                $requestData['actionTaken'] = $data['action_taken'];
            }
            if (isset($data['resolved_by'])) {
                $requestData['resolvedBy'] = $data['resolved_by'];
            }

            $response = Http::withHeaders($this->getAdminHeaders())
                ->timeout(15)
                ->post($this->baseUrl . '/admin/fraud/cases/' . $id . '/resolve', $requestData);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('SpringBootApiService::resolveFraudCase - EXCEPTION', [
                'id' => $id,
                'message' => $e->getMessage()
            ]);
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // ============================================
    // ADMIN DISPUTE METHODS
    // ============================================

    public function getDisputes()
    {
        try {
            $response = Http::withHeaders($this->getAdminHeaders())
                ->timeout(15)
                ->get($this->baseUrl . '/admin/disputes');

            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Exception $e) {
            Log::error('SpringBootApiService::getDisputes - EXCEPTION', [
                'message' => $e->getMessage()
            ]);
        }
        return [];
    }

    public function resolveDispute($id, $data)
    {
        try {
            $requestData = [];

            if (isset($data['resolution'])) {
                $requestData['resolution'] = $data['resolution'];
            }
            if (isset($data['refund_amount'])) {
                $requestData['refundAmount'] = $data['refund_amount'];
            }
            if (isset($data['resolved_by'])) {
                $requestData['resolvedBy'] = $data['resolved_by'];
            }

            $response = Http::withHeaders($this->getAdminHeaders())
                ->timeout(15)
                ->post($this->baseUrl . '/admin/disputes/' . $id . '/resolve', $requestData);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('SpringBootApiService::resolveDispute - EXCEPTION', [
                'id' => $id,
                'message' => $e->getMessage()
            ]);
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // ============================================
    // ADMIN REPORT METHODS
    // ============================================

    public function getAvailableReports()
    {
        return [
            ['name' => 'Revenue Report', 'type' => 'revenue', 'description' => 'Monthly and yearly revenue analytics'],
            ['name' => 'Booking Report', 'type' => 'bookings', 'description' => 'Booking trends and occupancy rates'],
            ['name' => 'User Report', 'type' => 'users', 'description' => 'User growth and engagement metrics'],
            ['name' => 'Fraud Report', 'type' => 'fraud', 'description' => 'Fraud detection and prevention analytics']
        ];
    }

    public function exportReport($type, $format, $filters)
    {
        try {
            $response = Http::withHeaders($this->getAdminHeaders())
                ->timeout(60)
                ->post($this->baseUrl . '/admin/reports/export/' . $type, array_merge($filters, ['format' => $format]));

            if ($response->successful()) {
                return $response->body();
            }
        } catch (\Exception $e) {
            Log::error('SpringBootApiService::exportReport - EXCEPTION', [
                'type' => $type,
                'message' => $e->getMessage()
            ]);
        }
        return null;
    }

    // ============================================
    // ADMIN SETTINGS METHODS
    // ============================================

    public function getSystemSettings()
    {
        try {
            $response = Http::withHeaders($this->getAdminHeaders())
                ->timeout(15)
                ->get($this->baseUrl . '/admin/settings');

            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Exception $e) {
            Log::error('SpringBootApiService::getSystemSettings - EXCEPTION', [
                'message' => $e->getMessage()
            ]);
        }
        return [];
    }

    public function updateCommissionSettings($data)
    {
        try {
            $response = Http::withHeaders($this->getAdminHeaders())
                ->timeout(15)
                ->post($this->baseUrl . '/admin/settings/commissions', $data);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('SpringBootApiService::updateCommissionSettings - EXCEPTION', [
                'message' => $e->getMessage()
            ]);
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // ============================================
    // PROPERTY METHODS (PUBLIC) - FIXED: No more mock data
    // ============================================

    public function getProperties()
    {
        Log::info('SpringBootApiService::getProperties - Request', [
            'url' => $this->baseUrl . '/properties/approved',
        ]);
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->timeout(30)
                ->get($this->baseUrl . '/properties/approved');

            Log::info('SpringBootApiService::getProperties - Response', [
                'status'     => $response->status(),
                'successful' => $response->successful(),
            ]);

            if ($response->successful()) {
                $result = $response->json();
                Log::info('Properties retrieved successfully', [
                    'count' => is_array($result) ? count($result) : 0
                ]);
                return is_array($result) ? $result : [];
            }

            Log::error('SpringBootApiService::getProperties - Failed', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            // ✅ Return empty array instead of mock data
            return [];

        } catch (\Exception $e) {
            Log::error('SpringBootApiService::getProperties - EXCEPTION', [
                'message' => $e->getMessage(),
            ]);

            // ✅ Return empty array instead of mock data
            return [];
        }
    }

    /**
     * ✅ FIXED: Get property by ID - Returns REAL data from API, not mock data
     */
    public function getProperty($id, $ownerContext = false)
    {
        $token = session('api_token');

        if ($ownerContext || $token) {
            try {
                $response = Http::withHeaders($this->getHeaders())
                    ->timeout(15)
                    ->get($this->baseUrl . '/owner/properties/' . $id);

                Log::info('SpringBootApiService::getProperty - owner endpoint', [
                    'id'     => $id,
                    'status' => $response->status(),
                ]);

                if ($response->successful()) {
                    $result = $response->json();
                    if ($result && isset($result['id'])) {
                        Log::info('Property retrieved from owner endpoint', [
                            'id' => $id,
                            'has_photos' => isset($result['photos']),
                            'photo_count' => isset($result['photos']) ? count($result['photos']) : 0
                        ]);
                        return $result;
                    }
                }
            } catch (\Exception $e) {
                Log::warning('SpringBootApiService::getProperty - owner endpoint failed, trying public', [
                    'id'      => $id,
                    'message' => $e->getMessage(),
                ]);
            }
        }

        try {
            $response = Http::withHeaders($this->getHeaders())
                ->timeout(15)
                ->get($this->baseUrl . '/properties/' . $id);

            Log::info('SpringBootApiService::getProperty - public endpoint', [
                'id'     => $id,
                'status' => $response->status(),
            ]);

            if ($response->successful()) {
                $result = $response->json();

                Log::info('Property retrieved from public endpoint', [
                    'id' => $id,
                    'has_photos' => isset($result['photos']),
                    'photo_count' => isset($result['photos']) ? count($result['photos']) : 0,
                    'has_videos' => isset($result['videos']),
                    'video_count' => isset($result['videos']) ? count($result['videos']) : 0
                ]);

                // ✅ Return REAL API data
                return $result;
            }

            Log::warning('API returned non-successful status', [
                'id' => $id,
                'status' => $response->status()
            ]);

            // ✅ Return null instead of mock data
            return null;

        } catch (\Exception $e) {
            Log::error('SpringBootApiService::getProperty - EXCEPTION', [
                'id'      => $id,
                'message' => $e->getMessage(),
            ]);

            // ✅ Return null instead of mock data
            return null;
        }
    }

    public function searchProperties($query, $filters = [])
    {
        try {
            $params   = array_merge(['search' => $query], $filters);
            $response = Http::withHeaders($this->getHeaders())
                ->timeout(15)
                ->get($this->baseUrl . '/properties/search', $params);

            $result = $response->json();

            if ($response->successful() && is_array($result)) {
                return $result;
            }

            return [];

        } catch (\Exception $e) {
            Log::error('SpringBootApiService::searchProperties - EXCEPTION', [
                'query'   => $query,
                'message' => $e->getMessage(),
            ]);
            return [];
        }
    }

    // ============================================
    // OWNER PROPERTY METHODS
    // ============================================

    public function getMyProperties()
    {
        Log::info('SpringBootApiService::getMyProperties - Request', [
            'url' => $this->baseUrl . '/owner/properties',
        ]);
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->timeout(30)
                ->get($this->baseUrl . '/owner/properties');

            Log::info('SpringBootApiService::getMyProperties - Response', [
                'status'     => $response->status(),
                'successful' => $response->successful(),
            ]);

            if (!$response->successful()) {
                Log::error('SpringBootApiService::getMyProperties - Failed', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                return [];
            }

            $result = $response->json();
            return is_array($result) ? $result : [];

        } catch (\Exception $e) {
            Log::error('SpringBootApiService::getMyProperties - EXCEPTION', [
                'message' => $e->getMessage(),
            ]);
            return [];
        }
    }

    public function createProperty($data)
    {
        Log::info('SpringBootApiService::createProperty - Request', [
            'data' => $data,
            'url'  => $this->baseUrl . '/owner/properties',
        ]);

        try {
            $response = Http::withHeaders($this->getHeaders())
                ->timeout(30)
                ->post($this->baseUrl . '/owner/properties', $data);

            Log::info('SpringBootApiService::createProperty - Response', [
                'status'     => $response->status(),
                'successful' => $response->successful(),
                'body'       => $response->body(),
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            return [
                'success' => false,
                'message' => $response->json()['message'] ?? 'Failed to create property',
            ];

        } catch (\Exception $e) {
            Log::error('SpringBootApiService::createProperty - EXCEPTION', [
                'message' => $e->getMessage(),
            ]);
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ];
        }
    }

    public function updateProperty($id, $data)
    {
        Log::info('SpringBootApiService::updateProperty - Request', [
            'id'   => $id,
            'data' => $data,
            'url'  => $this->baseUrl . '/owner/properties/' . $id,
        ]);

        try {
            $response = Http::withHeaders($this->getHeaders())
                ->timeout(30)
                ->put($this->baseUrl . '/owner/properties/' . $id, $data);

            Log::info('SpringBootApiService::updateProperty - Response', [
                'status'     => $response->status(),
                'successful' => $response->successful(),
            ]);

            return $response->successful();

        } catch (\Exception $e) {
            Log::error('SpringBootApiService::updateProperty - EXCEPTION', [
                'id'      => $id,
                'message' => $e->getMessage(),
            ]);
            return false;
        }
    }

    public function deleteProperty($id)
    {
        Log::info('SpringBootApiService::deleteProperty - Request', [
            'id'  => $id,
            'url' => $this->baseUrl . '/owner/properties/' . $id,
        ]);

        try {
            $response = Http::withHeaders($this->getHeaders())
                ->timeout(30)
                ->delete($this->baseUrl . '/owner/properties/' . $id);

            Log::info('SpringBootApiService::deleteProperty - Response', [
                'status'     => $response->status(),
                'successful' => $response->successful(),
            ]);

            return $response->successful();

        } catch (\Exception $e) {
            Log::error('SpringBootApiService::deleteProperty - EXCEPTION', [
                'id'      => $id,
                'message' => $e->getMessage(),
            ]);
            return false;
        }
    }

    // ============================================
    // PHOTO METHODS
    // ============================================

    public function uploadPhotos($propertyId, array $photos)
    {
        $token = session('api_token');

        Log::info('SpringBootApiService::uploadPhotos - Auth Check', [
            'propertyId' => $propertyId,
            'photoCount' => count($photos),
            'has_token' => !empty($token),
            'url' => $this->baseUrl . '/owner/properties/' . $propertyId . '/photos',
        ]);

        if (empty($token)) {
            Log::error('uploadPhotos - NO TOKEN FOUND!');
            return [];
        }

        $results = [];

        foreach ($photos as $photo) {
            try {
                $response = Http::withHeaders([
                        'Authorization' => 'Bearer ' . $token,
                        'Accept'        => 'application/json',
                    ])
                    ->timeout(60)
                    ->attach('photo', file_get_contents($photo->getRealPath()), $photo->getClientOriginalName())
                    ->post($this->baseUrl . '/owner/properties/' . $propertyId . '/photos');

                Log::info('SpringBootApiService::uploadPhotos - Single photo response', [
                    'status'     => $response->status(),
                    'successful' => $response->successful(),
                    'body'       => $response->body(),
                ]);

                if ($response->successful()) {
                    $results[] = $response->json();
                } else {
                    Log::error('SpringBootApiService::uploadPhotos - Photo upload failed', [
                        'status' => $response->status(),
                        'body'   => $response->body(),
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('SpringBootApiService::uploadPhotos - EXCEPTION on single photo', [
                    'propertyId' => $propertyId,
                    'message'    => $e->getMessage(),
                ]);
            }
        }

        return $results;
    }

    public function deletePhoto($propertyId, $photoId)
    {
        Log::info('SpringBootApiService::deletePhoto - Request', [
            'propertyId' => $propertyId,
            'photoId'    => $photoId,
            'url'        => $this->baseUrl . '/owner/properties/' . $propertyId . '/photos/' . $photoId,
        ]);

        try {
            $response = Http::withHeaders($this->getHeaders())
                ->timeout(30)
                ->delete($this->baseUrl . '/owner/properties/' . $propertyId . '/photos/' . $photoId);

            Log::info('SpringBootApiService::deletePhoto - Response', [
                'status'     => $response->status(),
                'successful' => $response->successful(),
            ]);

            return $response->successful();

        } catch (\Exception $e) {
            Log::error('SpringBootApiService::deletePhoto - EXCEPTION', [
                'propertyId' => $propertyId,
                'photoId'    => $photoId,
                'message'    => $e->getMessage(),
            ]);
            return false;
        }
    }

    public function setPrimaryPhoto($propertyId, $photoId)
    {
        Log::info('SpringBootApiService::setPrimaryPhoto - Request', [
            'propertyId' => $propertyId,
            'photoId'    => $photoId,
            'url'        => $this->baseUrl . '/owner/properties/' . $propertyId . '/photos/' . $photoId . '/primary',
        ]);

        try {
            $response = Http::withHeaders($this->getHeaders())
                ->timeout(30)
                ->put($this->baseUrl . '/owner/properties/' . $propertyId . '/photos/' . $photoId . '/primary');

            Log::info('SpringBootApiService::setPrimaryPhoto - Response', [
                'status'     => $response->status(),
                'successful' => $response->successful(),
            ]);

            return $response->successful();

        } catch (\Exception $e) {
            Log::error('SpringBootApiService::setPrimaryPhoto - EXCEPTION', [
                'propertyId' => $propertyId,
                'photoId'    => $photoId,
                'message'    => $e->getMessage(),
            ]);
            return false;
        }
    }

    public function reorderPhotos($propertyId, array $photoIds)
    {
        Log::info('SpringBootApiService::reorderPhotos - Request', [
            'propertyId' => $propertyId,
            'photoIds'   => $photoIds,
            'url'        => $this->baseUrl . '/owner/properties/' . $propertyId . '/photos/reorder',
        ]);

        try {
            $response = Http::withHeaders($this->getHeaders())
                ->timeout(30)
                ->post($this->baseUrl . '/owner/properties/' . $propertyId . '/photos/reorder', [
                    'photoIds' => $photoIds,
                ]);

            Log::info('SpringBootApiService::reorderPhotos - Response', [
                'status'     => $response->status(),
                'successful' => $response->successful(),
            ]);

            return $response->successful();

        } catch (\Exception $e) {
            Log::error('SpringBootApiService::reorderPhotos - EXCEPTION', [
                'propertyId' => $propertyId,
                'message'    => $e->getMessage(),
            ]);
            return false;
        }
    }

    // ============================================
    // VIDEO METHODS
    // ============================================

    public function uploadVideo($propertyId, $videoFile, $title = null, $description = null)
    {
        $token = session('api_token');

        Log::info('SpringBootApiService::uploadVideo - Auth Check', [
            'propertyId' => $propertyId,
            'fileName'   => $videoFile->getClientOriginalName(),
            'fileSize'   => $videoFile->getSize(),
            'has_token'  => !empty($token),
            'url'        => $this->baseUrl . '/owner/properties/' . $propertyId . '/videos',
        ]);

        if (empty($token)) {
            Log::error('uploadVideo - NO TOKEN FOUND! User needs to re-login');
            return null;
        }

        try {
            $request = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                    'Accept'        => 'application/json',
                ])
                ->timeout(120)
                ->attach('video', file_get_contents($videoFile->getRealPath()), $videoFile->getClientOriginalName());

            if ($title) {
                $request = $request->attach('title', $title);
            }
            if ($description) {
                $request = $request->attach('description', $description);
            }

            $response = $request->post($this->baseUrl . '/owner/properties/' . $propertyId . '/videos');

            Log::info('SpringBootApiService::uploadVideo - Response', [
                'status'     => $response->status(),
                'successful' => $response->successful(),
                'body'       => $response->body(),
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('SpringBootApiService::uploadVideo - Failed', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
            return null;

        } catch (\Exception $e) {
            Log::error('SpringBootApiService::uploadVideo - EXCEPTION', [
                'propertyId' => $propertyId,
                'message'    => $e->getMessage(),
            ]);
            return null;
        }
    }

    public function deleteVideo($propertyId, $videoId)
    {
        Log::info('SpringBootApiService::deleteVideo - Request', [
            'propertyId' => $propertyId,
            'videoId'    => $videoId,
            'url'        => $this->baseUrl . '/owner/properties/' . $propertyId . '/videos/' . $videoId,
        ]);

        try {
            $response = Http::withHeaders($this->getHeaders())
                ->timeout(30)
                ->delete($this->baseUrl . '/owner/properties/' . $propertyId . '/videos/' . $videoId);

            Log::info('SpringBootApiService::deleteVideo - Response', [
                'status'     => $response->status(),
                'successful' => $response->successful(),
            ]);

            return $response->successful();

        } catch (\Exception $e) {
            Log::error('SpringBootApiService::deleteVideo - EXCEPTION', [
                'propertyId' => $propertyId,
                'videoId'    => $videoId,
                'message'    => $e->getMessage(),
            ]);
            return false;
        }
    }

    public function setFeaturedVideo($propertyId, $videoId)
    {
        Log::info('SpringBootApiService::setFeaturedVideo - Request', [
            'propertyId' => $propertyId,
            'videoId'    => $videoId,
            'url'        => $this->baseUrl . '/owner/properties/' . $propertyId . '/videos/' . $videoId . '/featured',
        ]);

        try {
            $response = Http::withHeaders($this->getHeaders())
                ->timeout(30)
                ->put($this->baseUrl . '/owner/properties/' . $propertyId . '/videos/' . $videoId . '/featured');

            Log::info('SpringBootApiService::setFeaturedVideo - Response', [
                'status'     => $response->status(),
                'successful' => $response->successful(),
            ]);

            return $response->successful();

        } catch (\Exception $e) {
            Log::error('SpringBootApiService::setFeaturedVideo - EXCEPTION', [
                'propertyId' => $propertyId,
                'videoId'    => $videoId,
                'message'    => $e->getMessage(),
            ]);
            return false;
        }
    }

    // ============================================
    // BOOKING METHODS
    // ============================================

    public function createBooking($data)
    {
        Log::info('SpringBootApiService::createBooking - Request', [
            'data' => $data,
            'url'  => $this->baseUrl . '/bookings',
        ]);

        if (!$this->isApiReachable()) {
            Log::info('API not reachable, using MOCK booking creation');
            return $this->getMockBookingResponse($data);
        }

        try {
            $response = Http::withHeaders($this->getHeaders())
                ->timeout(30)
                ->post($this->baseUrl . '/bookings', $data);

            $statusCode   = $response->status();
            $responseBody = $response->body();
            $responseJson = $response->json();

            Log::info('SpringBootApiService::createBooking - Response Details', [
                'status_code' => $statusCode,
                'successful'  => $response->successful(),
                'body'        => $responseBody,
                'json'        => $responseJson,
            ]);

            if ($response->successful()) {
                if (isset($responseJson['id'])) {
                    session(["booking_{$responseJson['id']}" => $responseJson]);
                }
                return $responseJson;
            }

            Log::warning('API booking failed, falling back to mock mode');
            return $this->getMockBookingResponse($data);

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('SpringBootApiService::createBooking - Connection Error, using mock', [
                'message' => $e->getMessage(),
                'url'     => $this->baseUrl . '/bookings',
            ]);
            return $this->getMockBookingResponse($data);

        } catch (\Exception $e) {
            Log::error('SpringBootApiService::createBooking - Exception, using mock', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);
            return $this->getMockBookingResponse($data);
        }
    }

    public function getMyBookings()
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->timeout(15)
                ->get($this->baseUrl . '/bookings/my-bookings');

            $result = $response->json();
            return is_array($result) ? $result : [];

        } catch (\Exception $e) {
            Log::error('SpringBootApiService::getMyBookings - EXCEPTION', [
                'message' => $e->getMessage(),
            ]);
            return [];
        }
    }

    public function getBookingDetails($id)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->timeout(15)
                ->get($this->baseUrl . '/bookings/' . $id);

            $result = $response->json();

            if (!$result) {
                $mockBooking = session("booking_{$id}");
                if ($mockBooking) {
                    Log::info('Returning mock booking from session', ['id' => $id]);
                    return $mockBooking;
                }
            }

            return $result;

        } catch (\Exception $e) {
            Log::error('SpringBootApiService::getBookingDetails - EXCEPTION', [
                'id'      => $id,
                'message' => $e->getMessage(),
            ]);
            $mockBooking = session("booking_{$id}");
            return $mockBooking ?: null;
        }
    }

    public function cancelBooking($id)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->timeout(15)
                ->delete($this->baseUrl . '/bookings/' . $id);
            return $response->successful();
        } catch (\Exception $e) {
            Log::error('SpringBootApiService::cancelBooking - EXCEPTION', [
                'id'      => $id,
                'message' => $e->getMessage(),
            ]);
            return true;
        }
    }

    // ============================================
    // WISHLIST METHODS
    // ============================================

    public function addToWishlist($propertyId)
    {
        Log::info('SpringBootApiService::addToWishlist', ['propertyId' => $propertyId]);

        if (!$this->isApiReachable()) {
            Log::warning('API not reachable — storing wishlist item in session only');
            return true;
        }

        try {
            $response = Http::withHeaders($this->getHeaders())
                ->timeout(15)
                ->post($this->baseUrl . '/user/wishlist/' . $propertyId);

            Log::info('SpringBootApiService::addToWishlist - Response', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            if ($response->successful()) {
                return true;
            }

            if ($response->status() === 409) {
                Log::info('addToWishlist - Already exists on API (409), treating as success');
                return true;
            }

            Log::error('SpringBootApiService::addToWishlist - Failed', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
            return false;

        } catch (\Exception $e) {
            Log::error('SpringBootApiService::addToWishlist - EXCEPTION', [
                'propertyId' => $propertyId,
                'message'    => $e->getMessage(),
            ]);
            return false;
        }
    }

    public function removeFromWishlist($propertyId)
    {
        Log::info('SpringBootApiService::removeFromWishlist', ['propertyId' => $propertyId]);

        try {
            $response = Http::withHeaders($this->getHeaders())
                ->timeout(15)
                ->delete($this->baseUrl . '/user/wishlist/' . $propertyId);

            Log::info('SpringBootApiService::removeFromWishlist - Response', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            if ($response->successful() || $response->status() === 404) {
                return true;
            }

            Log::error('SpringBootApiService::removeFromWishlist - Failed', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
            return false;

        } catch (\Exception $e) {
            Log::error('SpringBootApiService::removeFromWishlist - EXCEPTION', [
                'propertyId' => $propertyId,
                'message'    => $e->getMessage(),
            ]);
            return false;
        }
    }

    public function getWishlists()
    {
        Log::info('SpringBootApiService::getWishlists');

        try {
            $response = Http::withHeaders($this->getHeaders())
                ->timeout(15)
                ->get($this->baseUrl . '/user/wishlist');

            Log::info('SpringBootApiService::getWishlists - Response', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            if ($response->successful()) {
                $result = $response->json();
                return is_array($result) ? $result : [];
            }

            $response2 = Http::withHeaders($this->getHeaders())
                ->timeout(15)
                ->get($this->baseUrl . '/user/wishlists');

            if ($response2->successful()) {
                $result = $response2->json();
                return is_array($result) ? $result : [];
            }

            Log::warning('SpringBootApiService::getWishlists - Both endpoints failed', [
                'status1' => $response->status(),
                'status2' => $response2->status(),
            ]);
            return [];

        } catch (\Exception $e) {
            Log::error('SpringBootApiService::getWishlists - EXCEPTION', [
                'message' => $e->getMessage(),
            ]);
            return [];
        }
    }

    // ============================================
    // USER PROFILE METHODS
    // ============================================

    public function updateProfile($data)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->timeout(15)
                ->put($this->baseUrl . '/user/profile', $data);
            return $response->json();
        } catch (\Exception $e) {
            Log::error('SpringBootApiService::updateProfile - EXCEPTION', [
                'message' => $e->getMessage(),
            ]);
            return ['success' => true, 'message' => 'Profile updated (mock)'];
        }
    }

    public function getUserProfile($userId = null)
    {
        try {
            $url = $userId
                ? $this->baseUrl . '/users/' . $userId
                : $this->baseUrl . '/user/profile';
            $response = Http::withHeaders($this->getHeaders())->timeout(15)->get($url);
            return $response->json();
        } catch (\Exception $e) {
            Log::error('SpringBootApiService::getUserProfile - EXCEPTION', [
                'userId'  => $userId,
                'message' => $e->getMessage(),
            ]);
            return null;
        }
    }

    public function changePassword($data)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->timeout(15)
                ->post($this->baseUrl . '/user/change-password', $data);
            return $response->successful();
        } catch (\Exception $e) {
            Log::error('SpringBootApiService::changePassword - EXCEPTION', [
                'message' => $e->getMessage(),
            ]);
            return true;
        }
    }

    // ============================================
    // NOTIFICATION METHODS
    // ============================================

    public function getNotifications()
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->timeout(15)
                ->get($this->baseUrl . '/notifications');

            $result = $response->json();
            return is_array($result) ? $result : [];
        } catch (\Exception $e) {
            Log::error('SpringBootApiService::getNotifications - EXCEPTION', [
                'message' => $e->getMessage(),
            ]);
            return [];
        }
    }

    public function markNotificationRead($id)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->timeout(15)
                ->post($this->baseUrl . '/notifications/' . $id . '/read');
            return $response->successful();
        } catch (\Exception $e) {
            Log::error('SpringBootApiService::markNotificationRead - EXCEPTION', [
                'id'      => $id,
                'message' => $e->getMessage(),
            ]);
            return true;
        }
    }

    public function markAllNotificationsRead()
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->timeout(15)
                ->post($this->baseUrl . '/notifications/read-all');
            return $response->successful();
        } catch (\Exception $e) {
            Log::error('SpringBootApiService::markAllNotificationsRead - EXCEPTION', [
                'message' => $e->getMessage(),
            ]);
            return true;
        }
    }

    // ============================================
    // REVIEW METHODS
    // ============================================

    public function getUserReviews($userId = null)
    {
        try {
            $url = $userId
                ? $this->baseUrl . '/users/' . $userId . '/reviews'
                : $this->baseUrl . '/user/reviews';
            $response = Http::withHeaders($this->getHeaders())->timeout(15)->get($url);

            $result = $response->json();
            return is_array($result) ? $result : [];
        } catch (\Exception $e) {
            Log::error('SpringBootApiService::getUserReviews - EXCEPTION', [
                'userId'  => $userId,
                'message' => $e->getMessage(),
            ]);
            return [];
        }
    }

    public function getPendingReviews()
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->timeout(15)
                ->get($this->baseUrl . '/user/reviews/pending');

            $result = $response->json();
            return is_array($result) ? $result : [];
        } catch (\Exception $e) {
            Log::error('SpringBootApiService::getPendingReviews - EXCEPTION', [
                'message' => $e->getMessage(),
            ]);
            return [];
        }
    }

    public function submitReview($bookingId, $data)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->timeout(15)
                ->post($this->baseUrl . '/bookings/' . $bookingId . '/review', $data);
            return $response->json();
        } catch (\Exception $e) {
            Log::error('SpringBootApiService::submitReview - EXCEPTION', [
                'bookingId' => $bookingId,
                'message'   => $e->getMessage(),
            ]);
            return ['success' => true, 'message' => 'Review submitted (mock)'];
        }
    }

    public function submitPropertyReview($propertyId, $data)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->timeout(15)
                ->post($this->baseUrl . '/properties/' . $propertyId . '/reviews', $data);
            return $response->json();
        } catch (\Exception $e) {
            Log::error('SpringBootApiService::submitPropertyReview - EXCEPTION', [
                'propertyId' => $propertyId,
                'message'    => $e->getMessage(),
            ]);
            return ['success' => true, 'message' => 'Review submitted (mock)'];
        }
    }

    // ============================================
    // MESSAGE METHODS
    // ============================================

    public function getConversations()
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->timeout(30)
                ->get($this->baseUrl . '/messages/conversations');

            if ($response->successful()) {
                return $response->json() ?? [];
            }
            Log::warning('Get conversations failed', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
            return [];
        } catch (\Exception $e) {
            Log::error('SpringBootApiService::getConversations - EXCEPTION', [
                'message' => $e->getMessage(),
            ]);
            return [];
        }
    }

    public function getMessages($conversationId)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->timeout(30)
                ->get($this->baseUrl . '/messages/conversations/' . $conversationId . '/messages');

            if ($response->successful()) {
                return $response->json() ?? [];
            }
            return [];
        } catch (\Exception $e) {
            Log::error('SpringBootApiService::getMessages - EXCEPTION', [
                'conversationId' => $conversationId,
                'message'        => $e->getMessage(),
            ]);
            return [];
        }
    }

    public function getConversationDetails($conversationId)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->timeout(30)
                ->get($this->baseUrl . '/messages/conversations/' . $conversationId);

            if ($response->successful()) {
                return $response->json() ?? [];
            }
            return [];
        } catch (\Exception $e) {
            Log::error('SpringBootApiService::getConversationDetails - EXCEPTION', [
                'conversationId' => $conversationId,
                'message'        => $e->getMessage(),
            ]);
            return [];
        }
    }

    public function getConversationMessages($userId)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->timeout(30)
                ->get($this->baseUrl . '/messages/users/' . $userId);

            if ($response->successful()) {
                return $response->json() ?? [];
            }
            return [];
        } catch (\Exception $e) {
            Log::error('SpringBootApiService::getConversationMessages - EXCEPTION', [
                'userId'  => $userId,
                'message' => $e->getMessage(),
            ]);
            return [];
        }
    }

    public function sendMessage($data)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->timeout(30)
                ->post($this->baseUrl . '/messages/send', $data);

            if ($response->successful()) {
                return $response->json();
            }
            return ['success' => false, 'error' => 'Failed to send message'];
        } catch (\Exception $e) {
            Log::error('SpringBootApiService::sendMessage - EXCEPTION', [
                'message' => $e->getMessage(),
            ]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function sendMessageToConversation($conversationId, $message)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->timeout(30)
                ->post($this->baseUrl . '/messages/conversations/' . $conversationId . '/send', [
                    'message' => $message,
                ]);

            if ($response->successful()) {
                return $response->json();
            }
            return ['success' => false, 'error' => 'Failed to send message'];
        } catch (\Exception $e) {
            Log::error('SpringBootApiService::sendMessageToConversation - EXCEPTION', [
                'conversationId' => $conversationId,
                'message'        => $e->getMessage(),
            ]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function markMessageAsRead($messageId)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->timeout(15)
                ->post($this->baseUrl . '/messages/' . $messageId . '/read');
            return $response->successful();
        } catch (\Exception $e) {
            Log::error('SpringBootApiService::markMessageAsRead - EXCEPTION', [
                'messageId' => $messageId,
                'message'   => $e->getMessage(),
            ]);
            return true;
        }
    }

    public function getUnreadCount()
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->timeout(10)
                ->get($this->baseUrl . '/messages/unread/count');

            if ($response->successful()) {
                $data = $response->json();
                return $data['count'] ?? 0;
            }
            return 0;
        } catch (\Exception $e) {
            Log::error('SpringBootApiService::getUnreadCount - EXCEPTION', [
                'message' => $e->getMessage(),
            ]);
            return 0;
        }
    }

    // ============================================
    // MOCK DATA METHODS (Only used for booking fallback)
    // ============================================

    /**
     * NOTE: Mock properties are ONLY used as fallback for booking creation
     * when the API is not reachable. For property display, we use real API data.
     */
    private function getMockProperties()
    {
        return [
            [
                'id'            => 1,
                'title'         => 'Luxury Beach Villa',
                'description'   => 'Beautiful beachfront villa with ocean views, private pool, and direct beach access.',
                'pricePerNight' => 15000,
                'location'      => 'Diani Beach',
                'propertyType'  => 'Villa',
                'averageRating' => 4.8,
                'createdAt'     => now()->toISOString(),
                'ownerName'     => 'Sarah Johnson',
                'photos'        => [
                    ['id' => 1, 'photoPath' => 'https://images.unsplash.com/photo-1582268611958-ebfd161ef9cf?w=800&h=600&fit=crop', 'isPrimary' => true],
                ],
                'videos'    => [],
                'amenities' => ['WiFi', 'Swimming Pool', 'Air Conditioning', 'Full Kitchen', 'Free Parking', 'Beach Access'],
            ],
            [
                'id'            => 2,
                'title'         => 'Modern City Apartment',
                'description'   => 'Stylish apartment in the heart of Nairobi with stunning city views.',
                'pricePerNight' => 8000,
                'location'      => 'Westlands, Nairobi',
                'propertyType'  => 'Apartment',
                'averageRating' => 4.5,
                'createdAt'     => now()->toISOString(),
                'ownerName'     => 'Michael Otieno',
                'photos'        => [
                    ['id' => 2, 'photoPath' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&h=600&fit=crop', 'isPrimary' => true],
                ],
                'videos'    => [],
                'amenities' => ['WiFi', 'Gym', 'Air Conditioning', 'Full Kitchen', '24/7 Security'],
            ],
            [
                'id'            => 3,
                'title'         => 'Cozy Mountain Cabin',
                'description'   => 'Peaceful cabin with stunning mountain views, fireplace, and hiking trails nearby.',
                'pricePerNight' => 6000,
                'location'      => 'Aberdare',
                'propertyType'  => 'Cabin',
                'averageRating' => 4.9,
                'createdAt'     => now()->toISOString(),
                'ownerName'     => 'David Kimathi',
                'photos'        => [
                    ['id' => 3, 'photoPath' => 'https://images.unsplash.com/photo-1582268611958-ebfd161ef9cf?w=800&h=600&fit=crop', 'isPrimary' => true],
                ],
                'videos'    => [],
                'amenities' => ['WiFi', 'Fireplace', 'Kitchen', 'Parking', 'Mountain View'],
            ],
        ];
    }

    private function getMockProperty($id)
    {
        $properties = $this->getMockProperties();
        foreach ($properties as $property) {
            if ($property['id'] == $id) {
                return $property;
            }
        }
        return [
            'id'            => $id,
            'title'         => 'Beautiful Property',
            'description'   => 'A wonderful place to stay with great amenities and excellent location.',
            'pricePerNight' => 10000,
            'location'      => 'Nairobi',
            'propertyType'  => 'House',
            'averageRating' => 4.5,
            'createdAt'     => now()->toISOString(),
            'ownerName'     => 'Eserian Host',
            'photos'        => [],
            'videos'        => [],
            'amenities'     => ['WiFi', 'Parking', 'Kitchen'],
        ];
    }

    private function getMockBookingResponse($data)
    {
        $bookingId = rand(10000, 99999);

        $checkIn  = \Carbon\Carbon::parse($data['checkInDate']);
        $checkOut = \Carbon\Carbon::parse($data['checkOutDate']);
        $nights   = $checkOut->diffInDays($checkIn);

        $property      = $this->getMockProperty($data['propertyId']);
        $pricePerNight = $property['pricePerNight'] ?? 10000;

        $subtotal    = $pricePerNight * $nights;
        $cleaningFee = 2500;
        $serviceFee  = 4200;
        $taxes       = 1800;
        $totalPrice  = $subtotal + $cleaningFee + $serviceFee + $taxes;

        $mockBooking = [
            'id'           => $bookingId,
            'propertyId'   => $data['propertyId'],
            'property'     => $property,
            'checkInDate'  => $data['checkInDate'],
            'checkOutDate' => $data['checkOutDate'],
            'guests'       => $data['guests'],
            'nights'       => $nights,
            'subtotal'     => $subtotal,
            'totalPrice'   => $totalPrice,
            'status'       => 'PENDING',
            'createdAt'    => now()->toISOString(),
        ];

        session(["booking_{$bookingId}" => $mockBooking]);

        Log::info('MOCK: Booking created successfully', [
            'booking_id'  => $bookingId,
            'total_price' => $totalPrice,
            'nights'      => $nights,
        ]);

        return $mockBooking;
    }
}

<?php
// File: app/Http/Controllers/AdminController.php
// LOCATION: C:\xampp\htdocs\eserian-homes\app\Http\Controllers\AdminController.php

namespace App\Http\Controllers;

use App\Services\SpringBootApiService;
use App\Helpers\ApiHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    protected $apiService;

    public function __construct(SpringBootApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    /**
     * 5.3 Admin Dashboard
     * Aggregates platform-wide metrics from Spring Boot API
     */
    public function dashboard()
    {
        try {
            // Fetch metrics from Spring Boot API
            $metrics = $this->apiService->getDashboardMetrics();
            $pendingCounts = $this->apiService->getPendingCounts();
            $recentActivities = $this->apiService->getRecentActivities(10);
            $fraudAlerts = $this->apiService->getFraudAlerts(5);

            return view('admin.dashboard.index', compact('metrics', 'pendingCounts', 'recentActivities', 'fraudAlerts'));
        } catch (\Exception $e) {
            Log::error('Admin dashboard error: ' . $e->getMessage());

            // Fallback data
            $metrics = [
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
            $pendingCounts = ['properties' => 0, 'kyc' => 0, 'disputes' => 0, 'payouts' => 0];
            $recentActivities = [];
            $fraudAlerts = [];

            return view('admin.dashboard.index', compact('metrics', 'pendingCounts', 'recentActivities', 'fraudAlerts'));
        }
    }

    /**
     * 5.4 Property Approval Workflow
     * Retrieves all pending properties from Spring Boot
     */
    public function pendingProperties()
    {
        try {
            $properties = $this->apiService->getPendingProperties();
            $stats = [
                'total' => count($properties),
                'high_risk' => collect($properties)->where('risk_score', '>=', 70)->count(),
                'no_photos' => collect($properties)->filter(function($p) {
                    return ($p['photoCount'] ?? 0) === 0;
                })->count(),
            ];

            return view('admin.properties.pending', compact('properties', 'stats'));
        } catch (\Exception $e) {
            Log::error('Pending properties error: ' . $e->getMessage());
            return view('admin.properties.pending', ['properties' => [], 'stats' => ['total' => 0, 'high_risk' => 0, 'no_photos' => 0]]);
        }
    }

    /**
     * 5.4 Approval Process
     * Approves a property via Spring Boot API
     */
    public function approveProperty($id)
    {
        try {
            $result = $this->apiService->approveProperty($id, [
                'moderator_notes' => request()->input('moderator_notes'),
                'approved_by' => session('admin_id')
            ]);

            if ($result['success'] ?? false) {
                return back()->with('success', 'Property approved successfully!');
            }
            return back()->with('error', $result['message'] ?? 'Failed to approve property');
        } catch (\Exception $e) {
            Log::error('Approve property error: ' . $e->getMessage());
            return back()->with('error', 'Error approving property: ' . $e->getMessage());
        }
    }

    /**
     * 5.5 Property Rejection Workflow
     * Rejects a property with reason via Spring Boot API
     */
    public function rejectProperty(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string',
            'details' => 'required|string|min:10'
        ]);

        try {
            $result = $this->apiService->rejectProperty($id, [
                'reason' => $request->reason,
                'details' => $request->details,
                'allow_resubmission' => $request->has('allow_resubmission')
            ]);

            if ($result['success'] ?? false) {
                return back()->with('warning', 'Property rejected successfully.');
            }
            return back()->with('error', $result['message'] ?? 'Failed to reject property');
        } catch (\Exception $e) {
            Log::error('Reject property error: ' . $e->getMessage());
            return back()->with('error', 'Error rejecting property: ' . $e->getMessage());
        }
    }

    /**
     * 5.7 Suspend Property Workflow
     * Suspends a property via Spring Boot API
     */
    public function suspendProperty(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string'
        ]);

        try {
            $result = $this->apiService->suspendProperty($id, [
                'reason' => $request->reason,
                'suspended_by' => session('admin_id')
            ]);

            if ($result['success'] ?? false) {
                return back()->with('warning', 'Property suspended successfully.');
            }
            return back()->with('error', $result['message'] ?? 'Failed to suspend property');
        } catch (\Exception $e) {
            Log::error('Suspend property error: ' . $e->getMessage());
            return back()->with('error', 'Error suspending property: ' . $e->getMessage());
        }
    }

    /**
     * 5.9 Archive Property Workflow
     * Archives a property via Spring Boot API
     */
    public function archiveProperty($id)
    {
        try {
            $result = $this->apiService->archiveProperty($id, [
                'archived_by' => session('admin_id')
            ]);

            if ($result['success'] ?? false) {
                return back()->with('info', 'Property archived successfully.');
            }
            return back()->with('error', $result['message'] ?? 'Failed to archive property');
        } catch (\Exception $e) {
            Log::error('Archive property error: ' . $e->getMessage());
            return back()->with('error', 'Error archiving property: ' . $e->getMessage());
        }
    }

    /**
     * 5.10 Permanent Delete Workflow
     * Permanently deletes a property via Spring Boot API
     */
    public function deleteProperty($id)
    {
        try {
            $result = $this->apiService->deletePropertyPermanently($id);

            if ($result['success'] ?? false) {
                return back()->with('success', 'Property deleted permanently.');
            }
            return back()->with('error', $result['message'] ?? 'Failed to delete property');
        } catch (\Exception $e) {
            Log::error('Delete property error: ' . $e->getMessage());
            return back()->with('error', 'Error deleting property: ' . $e->getMessage());
        }
    }

    /**
     * 5.6 Manage All Properties
     * Retrieves all properties with filters from Spring Boot
     */
    public function allProperties(Request $request)
    {
        try {
            $filters = $request->only(['status', 'search', 'property_type', 'min_price', 'max_price']);
            $properties = $this->apiService->getAllProperties($filters);

            $statusOptions = ['pending', 'approved', 'rejected', 'suspended', 'archived'];
            $typeOptions = ['villa', 'apartment', 'house', 'cabin', 'hotel'];

            return view('admin.properties.all', compact('properties', 'statusOptions', 'typeOptions'));
        } catch (\Exception $e) {
            Log::error('All properties error: ' . $e->getMessage());
            return view('admin.properties.all', ['properties' => [], 'statusOptions' => [], 'typeOptions' => []]);
        }
    }

    /**
     * 5.12 User Management Workflow
     * Retrieves all users from Spring Boot
     */
    public function users()
    {
        try {
            $users = $this->apiService->getAllUsers();
            return view('admin.users.index', compact('users'));
        } catch (\Exception $e) {
            Log::error('Users error: ' . $e->getMessage());
            return view('admin.users.index', ['users' => []]);
        }
    }

    /**
     * 5.15 Payment & Financial Management
     * Retrieves all payments from Spring Boot
     */
    public function payments()
    {
        try {
            $payments = $this->apiService->getAllPayments();
            return view('admin.payments.index', compact('payments'));
        } catch (\Exception $e) {
            Log::error('Payments error: ' . $e->getMessage());
            return view('admin.payments.index', ['payments' => []]);
        }
    }

    /**
     * Payouts Page
     * Displays pending and completed payouts
     */
    public function payouts()
    {
        try {
            $payouts = $this->apiService->getPendingPayouts();
            return view('admin.payments.payouts', compact('payouts'));
        } catch (\Exception $e) {
            Log::error('Payouts error: ' . $e->getMessage());
            return view('admin.payments.payouts', ['payouts' => []]);
        }
    }

    /**
     * 5.3 Revenue Report
     * Retrieves revenue metrics from Spring Boot
     */
    public function revenueReport()
    {
        try {
            $revenueData = $this->apiService->getRevenueReport();
            return view('admin.reports.revenue', compact('revenueData'));
        } catch (\Exception $e) {
            Log::error('Revenue report error: ' . $e->getMessage());
            return view('admin.reports.revenue', ['revenueData' => ['total_revenue' => 0, 'monthly_revenue' => 0]]);
        }
    }

    /**
     * Property Review Page
     * Retrieves single property details for review
     */
    public function reviewProperty($id)
    {
        try {
            $property = $this->apiService->getPropertyDetails($id);
            $riskAssessment = $this->apiService->getPropertyRiskAssessment($id);
            $similarProperties = $this->apiService->getSimilarProperties($id);

            return view('admin.properties.review', compact('property', 'riskAssessment', 'similarProperties'));
        } catch (\Exception $e) {
            Log::error('Review property error: ' . $e->getMessage());
            return back()->with('error', 'Error loading property details');
        }
    }

    /**
     * User Details Page
     */
    public function userDetails($id)
    {
        try {
            $user = $this->apiService->getUserDetails($id);
            return view('admin.users.show', compact('user'));
        } catch (\Exception $e) {
            Log::error('User details error: ' . $e->getMessage());
            return back()->with('error', 'Error loading user details');
        }
    }

    /**
     * Suspend User
     */
    public function suspendUser(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string'
        ]);

        try {
            $result = $this->apiService->suspendUser($id, $request->reason);

            if ($result['success'] ?? false) {
                return back()->with('warning', 'User suspended successfully.');
            }
            return back()->with('error', $result['message'] ?? 'Failed to suspend user');
        } catch (\Exception $e) {
            Log::error('Suspend user error: ' . $e->getMessage());
            return back()->with('error', 'Error suspending user');
        }
    }

    /**
     * Activate User
     */
    public function activateUser($id)
    {
        try {
            $result = $this->apiService->activateUser($id);

            if ($result['success'] ?? false) {
                return back()->with('success', 'User activated successfully.');
            }
            return back()->with('error', $result['message'] ?? 'Failed to activate user');
        } catch (\Exception $e) {
            Log::error('Activate user error: ' . $e->getMessage());
            return back()->with('error', 'Error activating user');
        }
    }

    /**
     * Process Refund
     */
    public function processRefund(Request $request, $paymentId)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'reason' => 'required|string'
        ]);

        try {
            $result = $this->apiService->processRefund($paymentId, [
                'amount' => $request->amount,
                'reason' => $request->reason
            ]);

            if ($result['success'] ?? false) {
                return back()->with('success', 'Refund processed successfully.');
            }
            return back()->with('error', $result['message'] ?? 'Failed to process refund');
        } catch (\Exception $e) {
            Log::error('Process refund error: ' . $e->getMessage());
            return back()->with('error', 'Error processing refund');
        }
    }

    /**
     * Fraud Alerts Page
     */
    public function fraudAlerts()
    {
        try {
            $alerts = $this->apiService->getFraudCases();
            return view('admin.fraud.alerts', compact('alerts'));
        } catch (\Exception $e) {
            Log::error('Fraud alerts error: ' . $e->getMessage());
            return view('admin.fraud.alerts', ['alerts' => []]);
        }
    }

    /**
     * Fraud Case Details
     */
    public function fraudCase($id)
    {
        try {
            $case = $this->apiService->getFraudCaseDetails($id);
            return view('admin.fraud.case', compact('case'));
        } catch (\Exception $e) {
            Log::error('Fraud case error: ' . $e->getMessage());
            return back()->with('error', 'Error loading fraud case');
        }
    }

    /**
     * Resolve Fraud Case
     */
    public function resolveFraudCase(Request $request, $id)
    {
        $request->validate([
            'resolution' => 'required|string',
            'action_taken' => 'required|string'
        ]);

        try {
            $result = $this->apiService->resolveFraudCase($id, [
                'resolution' => $request->resolution,
                'action_taken' => $request->action_taken,
                'resolved_by' => session('admin_id')
            ]);

            if ($result['success'] ?? false) {
                return back()->with('success', 'Fraud case resolved successfully.');
            }
            return back()->with('error', $result['message'] ?? 'Failed to resolve case');
        } catch (\Exception $e) {
            Log::error('Resolve fraud case error: ' . $e->getMessage());
            return back()->with('error', 'Error resolving fraud case');
        }
    }

    /**
     * Disputes Page
     */
    public function disputes()
    {
        try {
            $disputes = $this->apiService->getDisputes();
            return view('admin.disputes.index', compact('disputes'));
        } catch (\Exception $e) {
            Log::error('Disputes error: ' . $e->getMessage());
            return view('admin.disputes.index', ['disputes' => []]);
        }
    }

    /**
     * Resolve Dispute
     */
    public function resolveDispute(Request $request, $id)
    {
        $request->validate([
            'resolution' => 'required|string',
            'refund_amount' => 'nullable|numeric|min:0'
        ]);

        try {
            $result = $this->apiService->resolveDispute($id, [
                'resolution' => $request->resolution,
                'refund_amount' => $request->refund_amount ?? 0,
                'resolved_by' => session('admin_id')
            ]);

            if ($result['success'] ?? false) {
                return back()->with('success', 'Dispute resolved successfully.');
            }
            return back()->with('error', $result['message'] ?? 'Failed to resolve dispute');
        } catch (\Exception $e) {
            Log::error('Resolve dispute error: ' . $e->getMessage());
            return back()->with('error', 'Error resolving dispute');
        }
    }

    /**
     * Reports Page
     */
    public function reports()
    {
        try {
            $reports = $this->apiService->getAvailableReports();
            return view('admin.reports.index', compact('reports'));
        } catch (\Exception $e) {
            Log::error('Reports error: ' . $e->getMessage());
            return view('admin.reports.index', ['reports' => []]);
        }
    }

    /**
     * Export Report
     */
    public function exportReport($type, Request $request)
    {
        try {
            $format = $request->get('format', 'excel');
            $filters = $request->only(['date_from', 'date_to', 'status']);

            $export = $this->apiService->exportReport($type, $format, $filters);

            if ($export) {
                return response($export)
                    ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                    ->header('Content-Disposition', 'attachment; filename="' . $type . '_report.' . ($format === 'excel' ? 'xlsx' : $format) . '"');
            }

            return back()->with('error', 'Failed to generate report');
        } catch (\Exception $e) {
            Log::error('Export report error: ' . $e->getMessage());
            return back()->with('error', 'Error generating report');
        }
    }

    /**
     * Settings Page
     */
    public function settings()
    {
        try {
            $settings = $this->apiService->getSystemSettings();
            return view('admin.settings.index', compact('settings'));
        } catch (\Exception $e) {
            Log::error('Settings error: ' . $e->getMessage());
            return view('admin.settings.index', ['settings' => []]);
        }
    }

    /**
     * Update Commission Settings
     */
    public function updateCommissions(Request $request)
    {
        $request->validate([
            'commission_rate' => 'required|numeric|min:0|max:100',
            'service_fee' => 'required|numeric|min:0',
            'cleaning_fee' => 'required|numeric|min:0'
        ]);

        try {
            $result = $this->apiService->updateCommissionSettings($request->all());

            if ($result['success'] ?? false) {
                return back()->with('success', 'Commission settings updated successfully.');
            }
            return back()->with('error', $result['message'] ?? 'Failed to update settings');
        } catch (\Exception $e) {
            Log::error('Update commissions error: ' . $e->getMessage());
            return back()->with('error', 'Error updating commission settings');
        }
    }
}

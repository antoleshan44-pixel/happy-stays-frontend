<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('subtitle', 'Platform Overview & Key Metrics'); ?>

<?php $__env->startSection('content'); ?>
<?php use App\Helpers\CurrencyHelper; ?>
<div class="space-y-6">
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Users -->
        <div class="bg-white rounded-xl shadow-sm p-6 hover-lift">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Users</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1"><?php echo e(number_format($metrics['total_users'] ?? 0)); ?></p>
                    <p class="text-green-600 text-xs mt-2">
                        <i class="fas fa-arrow-up"></i> <?php echo e($metrics['user_growth'] ?? 0); ?>% increase
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Properties -->
        <div class="bg-white rounded-xl shadow-sm p-6 hover-lift">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Properties</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1"><?php echo e(number_format($metrics['total_properties'] ?? 0)); ?></p>
                    <p class="text-yellow-600 text-xs mt-2">
                        <i class="fas fa-clock"></i> <?php echo e($pendingCounts['properties'] ?? 0); ?> pending
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-building text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Active Bookings -->
        <div class="bg-white rounded-xl shadow-sm p-6 hover-lift">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Active Bookings</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1"><?php echo e(number_format($metrics['active_bookings'] ?? 0)); ?></p>
                    <p class="text-green-600 text-xs mt-2">
                        <i class="fas fa-arrow-up"></i> <?php echo e($metrics['booking_growth'] ?? 0); ?>% increase
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-calendar-check text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Monthly Revenue in KES -->
        <div class="bg-white rounded-xl shadow-sm p-6 hover-lift">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Monthly Revenue</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1"><?php echo e(CurrencyHelper::formatKES($metrics['monthly_revenue'] ?? 0, true)); ?></p>
                    <p class="text-green-600 text-xs mt-2">
                        <i class="fas fa-arrow-up"></i> <?php echo e($metrics['revenue_growth'] ?? 0); ?>% growth
                    </p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-line text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Revenue Chart -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-800">Revenue Overview (KES)</h3>
                <select class="text-sm border rounded-lg px-3 py-1" id="revenuePeriod">
                    <option value="12">Last 12 Months</option>
                    <option value="6">Last 6 Months</option>
                    <option value="3">Last 3 Months</option>
                </select>
            </div>
            <canvas id="revenueChart" height="250"></canvas>
        </div>

        <!-- Booking Status -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Booking Status</h3>
            <canvas id="bookingChart" height="250"></canvas>
            <div class="mt-4 space-y-2">
                <div class="flex justify-between text-sm">
                    <span><span class="inline-block w-3 h-3 bg-green-500 rounded-full mr-2"></span>Confirmed</span>
                    <span class="font-medium" id="confirmedCount">0</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span><span class="inline-block w-3 h-3 bg-yellow-500 rounded-full mr-2"></span>Pending</span>
                    <span class="font-medium" id="pendingCount">0</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span><span class="inline-block w-3 h-3 bg-blue-500 rounded-full mr-2"></span>Completed</span>
                    <span class="font-medium" id="completedCount">0</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span><span class="inline-block w-3 h-3 bg-red-500 rounded-full mr-2"></span>Cancelled</span>
                    <span class="font-medium" id="cancelledCount">0</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities & Pending Approvals -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Activities -->
        <div class="bg-white rounded-xl shadow-sm">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">Recent Activities</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4" id="recentActivitiesList">
                    <?php $__empty_1 = true; $__currentLoopData = $recentActivities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-<?php echo e($activity['icon'] ?? 'check'); ?> text-green-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-800"><?php echo e($activity['description'] ?? 'Activity recorded'); ?></p>
                            <p class="text-xs text-gray-400 mt-1"><?php echo e(\Carbon\Carbon::parse($activity['created_at'])->diffForHumans()); ?></p>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-gray-500 text-center">No recent activities</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Pending Approvals -->
        <div class="bg-white rounded-xl shadow-sm">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">Pending Approvals</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-800">Properties</p>
                            <p class="text-xs text-gray-500">Awaiting review</p>
                        </div>
                        <span class="text-2xl font-bold text-yellow-600"><?php echo e($pendingCounts['properties'] ?? 0); ?></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-800">KYC Verifications</p>
                            <p class="text-xs text-gray-500">Identity documents</p>
                        </div>
                        <span class="text-2xl font-bold text-blue-600"><?php echo e($pendingCounts['kyc'] ?? 0); ?></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-800">Payout Requests</p>
                            <p class="text-xs text-gray-500">Pending approval</p>
                        </div>
                        <span class="text-2xl font-bold text-green-600"><?php echo e(CurrencyHelper::formatKES($pendingCounts['payouts_amount'] ?? 0, true)); ?></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-800">Disputes</p>
                            <p class="text-xs text-gray-500">Open cases</p>
                        </div>
                        <span class="text-2xl font-bold text-red-600"><?php echo e($pendingCounts['disputes'] ?? 0); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Revenue Chart Data from Controller
    <?php
        $monthlyRevenueData = json_encode($metrics['monthly_revenue_data'] ?? array_fill(0, 12, 0));
        $bookingStatusCounts = json_encode($metrics['booking_status_counts'] ?? [0, 0, 0, 0]);
    ?>

    var revenueData = <?php echo $monthlyRevenueData; ?>;
    var bookingStatusData = <?php echo $bookingStatusCounts; ?>;

    document.getElementById('confirmedCount').innerText = bookingStatusData[0] || 0;
    document.getElementById('pendingCount').innerText = bookingStatusData[1] || 0;
    document.getElementById('completedCount').innerText = bookingStatusData[2] || 0;
    document.getElementById('cancelledCount').innerText = bookingStatusData[3] || 0;

    // Revenue Chart
    var ctx1 = document.getElementById('revenueChart').getContext('2d');
    var revenueChart = new Chart(ctx1, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Revenue (KES)',
                data: revenueData,
                borderColor: '#00288e',
                backgroundColor: 'rgba(0, 40, 142, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { position: 'bottom' },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'KES ' + context.raw.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Booking Chart
    var ctx2 = document.getElementById('bookingChart').getContext('2d');
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['Confirmed', 'Pending', 'Completed', 'Cancelled'],
            datasets: [{
                data: bookingStatusData,
                backgroundColor: ['#10b981', '#f59e0b', '#3b82f6', '#ef4444']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: { legend: { position: 'bottom' } }
        }
    });

    // Period selector
    var periodSelector = document.getElementById('revenuePeriod');
    if (periodSelector) {
        periodSelector.addEventListener('change', function() {
            fetch('/admin/api/revenue/' + this.value)
                .then(function(response) { return response.json(); })
                .then(function(data) {
                    revenueChart.data.datasets[0].data = data;
                    revenueChart.update();
                })
                .catch(function(error) {
                    console.error('Error fetching revenue data:', error);
                });
        });
    }
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\eserian-homes\resources\views/admin/dashboard/index.blade.php ENDPATH**/ ?>
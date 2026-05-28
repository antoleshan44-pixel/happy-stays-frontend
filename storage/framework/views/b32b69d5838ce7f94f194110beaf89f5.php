
<footer style="
    background: #0f1117;
    color: #e5e7eb;
    font-family: 'Inter', sans-serif;
    margin-top: 3rem;
    border-top: 1px solid rgba(255,255,255,0.06);
">

    
    <div class="container-custom" style="padding-top: 2.5rem; padding-bottom: 2rem;">
        <div style="display: grid; grid-template-columns: 1fr; gap: 2rem;">

            
            <div style="display: grid; grid-template-columns: 1fr; gap: 2rem;"
                 class="admin-footer-grid">

                <div>
                    
                    <div style="margin-bottom: 0.75rem;">
                        <span style="
                            font-family: 'Manrope', 'Inter', sans-serif;
                            font-size: 1.5rem;
                            font-weight: 700;
                            color: #ffffff;
                            letter-spacing: -0.01em;
                        ">Eserian <span style="color: #667eea;">Admin</span></span>
                    </div>
                    <p style="font-size: 0.8rem; color: #9ca3af; line-height: 1.6; max-width: 260px; font-weight: 400;">
                        Enterprise administration portal for Eserian Homes property management platform.
                    </p>
                </div>

                
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem;" class="admin-footer-links">

                    
                    <div>
                        <p style="
                            font-size: 0.65rem; font-weight: 600;
                            letter-spacing: 0.1em; text-transform: uppercase;
                            color: #6b7280; margin-bottom: 0.75rem;
                        ">Platform</p>
                        <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.5rem;">
                            <li><a href="<?php echo e(route('admin.dashboard')); ?>" class="admin-footer-link">Dashboard</a></li>
                            <li><a href="<?php echo e(route('admin.properties')); ?>" class="admin-footer-link">Properties</a></li>
                            <li><a href="<?php echo e(route('admin.users')); ?>" class="admin-footer-link">Users</a></li>
                            <li><a href="<?php echo e(route('admin.payments')); ?>" class="admin-footer-link">Payments</a></li>
                        </ul>
                    </div>

                    
                    <div>
                        <p style="
                            font-size: 0.65rem; font-weight: 600;
                            letter-spacing: 0.1em; text-transform: uppercase;
                            color: #6b7280; margin-bottom: 0.75rem;
                        ">Management</p>
                        <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.5rem;">
                            <li><a href="<?php echo e(route('admin.pending')); ?>" class="admin-footer-link">Pending Approvals</a></li>
                            <li><a href="<?php echo e(route('admin.fraud.alerts')); ?>" class="admin-footer-link">Fraud Alerts</a></li>
                            <li><a href="<?php echo e(route('admin.disputes')); ?>" class="admin-footer-link">Disputes</a></li>
                            <li><a href="<?php echo e(route('admin.payouts')); ?>" class="admin-footer-link">Payouts</a></li>
                        </ul>
                    </div>

                    
                    <div>
                        <p style="
                            font-size: 0.65rem; font-weight: 600;
                            letter-spacing: 0.1em; text-transform: uppercase;
                            color: #6b7280; margin-bottom: 0.75rem;
                        ">Reports</p>
                        <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.5rem;">
                            <li><a href="<?php echo e(route('admin.reports')); ?>" class="admin-footer-link">Analytics</a></li>
                            <li><a href="<?php echo e(route('admin.revenue')); ?>" class="admin-footer-link">Revenue Report</a></li>
                            <li><a href="#" class="admin-footer-link">Booking Trends</a></li>
                            <li><a href="#" class="admin-footer-link">User Statistics</a></li>
                        </ul>
                    </div>

                    
                    <div>
                        <p style="
                            font-size: 0.65rem; font-weight: 600;
                            letter-spacing: 0.1em; text-transform: uppercase;
                            color: #6b7280; margin-bottom: 0.75rem;
                        ">System</p>
                        <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.5rem;">
                            <li><a href="<?php echo e(route('admin.settings')); ?>" class="admin-footer-link">Settings</a></li>
                            <li><a href="#" class="admin-footer-link">Audit Logs</a></li>
                            <li><a href="#" class="admin-footer-link">API Status</a></li>
                            <li><a href="#" class="admin-footer-link">Documentation</a></li>
                        </ul>
                    </div>

                </div>
            </div>

            
            <div style="
                border-top: 1px solid rgba(255,255,255,0.05);
                padding-top: 1.5rem;
            ">
                <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1rem;">
                    <p style="font-size: 0.7rem; color: #4b5563; margin: 0;">
                        <i class="fas fa-database"></i> System Status: Operational
                    </p>
                    <div style="display: flex; flex-wrap: wrap; gap: 1.5rem;">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <span class="status-dot green"></span>
                            <span style="font-size: 0.7rem; color: #6b7280;">API: Connected</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <span class="status-dot green"></span>
                            <span style="font-size: 0.7rem; color: #6b7280;">Database: Active</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <span class="status-dot yellow"></span>
                            <span style="font-size: 0.7rem; color: #6b7280;">Queue: Processing</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <span class="status-dot green"></span>
                            <span style="font-size: 0.7rem; color: #6b7280;">Cache: Ready</span>
                        </div>
                    </div>
                </div>
            </div>

            
            <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1rem;">
                <div style="display: flex; flex-wrap: wrap; gap: 1rem;">
                    <a href="#" class="admin-footer-link-sm">Support Center</a>
                    <span style="color: #2d3748;">|</span>
                    <a href="#" class="admin-footer-link-sm">Security</a>
                    <span style="color: #2d3748;">|</span>
                    <a href="#" class="admin-footer-link-sm">Privacy Policy</a>
                    <span style="color: #2d3748;">|</span>
                    <a href="#" class="admin-footer-link-sm">Terms of Service</a>
                </div>
                <div>
                    <span style="font-size: 0.7rem; color: #4b5563;">
                        <i class="far fa-clock"></i> Server Time: <?php echo e(now()->format('Y-m-d H:i:s')); ?>

                    </span>
                </div>
            </div>

        </div>
    </div>

    
    <div style="border-top: 1px solid rgba(255,255,255,0.04); background: rgba(0,0,0,0.2);">
        <div class="container-custom" style="
            padding-top: 1rem; padding-bottom: 1rem;
            display: flex; flex-wrap: wrap;
            align-items: center; justify-content: space-between;
            gap: 0.75rem;
        ">
            <span style="font-size: 0.72rem; color: #4b5563;">
                <i class="far fa-copyright"></i> <?php echo e(date('Y')); ?> Eserian Homes Ltd. All rights reserved.
            </span>
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <span style="font-size: 0.7rem; color: #4b5563;">
                    <i class="fas fa-shield-alt"></i> Secured by Eserian Security
                </span>
                <span style="display: inline-block; width: 4px; height: 4px; border-radius: 50%; background: #2d3748;"></span>
                <span style="font-size: 0.7rem; color: #4b5563;">
                    <i class="fas fa-code-branch"></i> v2.0.0
                </span>
            </div>
        </div>
    </div>

</footer>

<style>
    .admin-footer-link {
        font-size: 0.78rem;
        color: #9ca3af;
        text-decoration: none;
        font-weight: 400;
        transition: color 0.2s;
        display: inline-block;
    }
    .admin-footer-link:hover {
        color: #667eea;
    }

    .admin-footer-link-sm {
        font-size: 0.7rem;
        color: #6b7280;
        text-decoration: none;
        transition: color 0.2s;
    }
    .admin-footer-link-sm:hover {
        color: #667eea;
    }

    .status-dot {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }
    .status-dot.green { background: #10b981; box-shadow: 0 0 4px #10b981; }
    .status-dot.yellow { background: #f59e0b; box-shadow: 0 0 4px #f59e0b; }
    .status-dot.red { background: #ef4444; box-shadow: 0 0 4px #ef4444; }

    @media (min-width: 768px) {
        .admin-footer-grid {
            grid-template-columns: 240px 1fr !important;
        }
    }
    @media (max-width: 640px) {
        .admin-footer-links {
            grid-template-columns: repeat(2, 1fr) !important;
        }
    }

    .container-custom {
        max-width: 1280px;
        margin-left: auto;
        margin-right: auto;
        padding-left: 1.5rem;
        padding-right: 1.5rem;
    }
    @media (min-width: 768px) {
        .container-custom {
            padding-left: 2rem;
            padding-right: 2rem;
        }
    }
</style>
<?php /**PATH C:\xampp\htdocs\eserian-homes\resources\views/admin/partials/footer.blade.php ENDPATH**/ ?>
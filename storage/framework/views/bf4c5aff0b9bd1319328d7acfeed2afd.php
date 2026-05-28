<?php $__env->startSection('title', 'User Management'); ?>
<?php $__env->startSection('subtitle', 'Manage platform users and their roles'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-xl shadow-sm">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
        <h3 class="font-semibold text-gray-800">All Users</h3>
        <div class="flex space-x-2">
            <select class="px-3 py-1 border rounded-lg text-sm">
                <option>All Roles</option>
                <option>Customers</option>
                <option>Owners</option>
                <option>Admins</option>
            </select>
            <select class="px-3 py-1 border rounded-lg text-sm">
                <option>All Status</option>
                <option>Active</option>
                <option>Suspended</option>
            </select>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Joined</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-900">1</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="text-blue-600 text-sm font-medium">SA</span>
                            </div>
                            <span class="text-sm font-medium text-gray-900">Super Admin</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">admin@eserian.com</td>
                    <td class="px-6 py-4"><span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-700">Admin</span></td>
                    <td class="px-6 py-4"><span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">Active</span></td>
                    <td class="px-6 py-4 text-sm text-gray-500">Jan 1, 2024</td>
                    <td class="px-6 py-4">
                        <div class="flex space-x-2">
                            <button class="text-blue-600 hover:text-blue-800"><i class="fas fa-eye"></i></button>
                            <button class="text-red-600 hover:text-red-800"><i class="fas fa-ban"></i></button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\eserian-homes\resources\views/admin/users/index.blade.php ENDPATH**/ ?>
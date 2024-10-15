<?php 
use System\Libraries\Session;?>


<div class="container mx-auto">
    <div class="flex items-center justify-between">
        <a href="<?= admin_url('users/add') ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Add User</a>
    </div>
    <div class="flex flex-wrap -mx-3">
        <div class="w-full md:w-7/10 px-3 mb-6">
            <div class="bg-white shadow-md rounded p-4">
            <table class="min-w-full table-auto">
                <thead>
                    <tr class="bg-gray-200">
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td class="px-4 py-2"><?php echo $user['id']; ?></td>
                            <td class="px-4 py-2"><?php echo $user['username']; ?></td>
                            <td class="px-4 py-2"><?php echo $user['email']; ?></td>
                            <td class="px-4 py-2"><?php echo $user['role']; ?></td>
                            <td class="px-4 py-2"><?php echo $user['status']; ?></td>
                            <td class="px-4 py-2">
                                <a href="/users/edit/<?php echo $user['id']; ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Edit</a> 
                                <a href="/users/delete/<?php echo $user['id']; ?>" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Are you sure you want to delete?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>
<?php
use System\Libraries\Session;
?>
<div class="container mx-auto p-4">
    <?php if (Session::has_flash('success')): ?>
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
            <?= Session::flash('success'); ?>
        </div>
    <?php endif; ?>
    
    <!-- Form thêm ngôn ngữ mới -->
    <div class="mb-6">
        <h2 class="text-xl font-semibold mb-2">Thêm ngôn ngữ mới</h2>
        <form action="<?= admin_url('languages/add') ?>" method="POST" class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Tên ngôn ngữ</label>
                <input type="text" name="name" id="name" required class="mt-1 p-2 border border-gray-300 rounded w-full">
            </div>
            <div>
                <label for="code" class="block text-sm font-medium text-gray-700">Mã ngôn ngữ</label>
                <input type="text" name="code" id="code" maxlength="2" required class="mt-1 p-2 border border-gray-300 rounded w-full">
            </div>
            <div>
                <label for="flat" class="block text-sm font-medium text-gray-700">Flat</label>
                <input type="text" name="flat" id="flat" required class="mt-1 p-2 border border-gray-300 rounded w-full">
            </div>
            <div>
                <label for="is_default" class="block text-sm font-medium text-gray-700">Mặc định</label>
                <select name="is_default" id="is_default" class="mt-1 p-2 border border-gray-300 rounded w-full">
                    <option value="0">Không</option>
                    <option value="1">Có</option>
                </select>
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Trạng thái</label>
                <select name="status" id="status" class="mt-1 p-2 border border-gray-300 rounded w-full">
                    <option value="active">Hoạt động</option>
                    <option value="inactive">Tạm tắt</option>
                </select>
            </div>
            <div>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Thêm ngôn ngữ</button>
            </div>
        </form>
    </div>

    <div class="overflow-x-auto">        
        <table class="min-w-full bg-white rounded-lg shadow-md">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 text-left">ID</th>
                    <th class="px-4 py-2 text-left">Tên</th>
                    <th class="px-4 py-2 text-left">Mã</th>
                    <th class="px-4 py-2 text-left">Trạng thái</th>
                    <th class="px-4 py-2 text-left">Mặc định</th>
                    <th class="px-4 py-2 text-left">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($languages as $language): ?>
                    <tr class="border-b">
                        <td class="px-4 py-2"><?= $language['id']; ?></td>
                        <td class="px-4 py-2"><?= $language['name']; ?></td>
                        <td class="px-4 py-2"><?= $language['code']; ?></td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 rounded <?= $language['status'] == 'active' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800'; ?>">
                                <?= $language['status'] == 'active' ? 'Hoạt động' : 'Tạm tắt'; ?>
                            </span>
                        </td>
                        <td class="px-4 py-2"><?= $language['is_default'] == 1 ? 'Có' : 'Không'; ?></td>
                        <td class="px-4 py-2">
                            <a href="<?= admin_url('languages/edit/' . $language['id']); ?>" class="text-blue-600 hover:underline">Chỉnh sửa</a> |
                            <a href="<?= admin_url('languages/delete/' . $language['id']); ?>" class="text-red-600 hover:underline" onclick="return confirm('Bạn có chắc muốn xóa?');">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php
use System\Libraries\Session;
?><div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Chỉnh sửa ngôn ngữ</h1>
    <?php if (Session::has_flash('error')): ?>
        <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">
            <?= Session::flash('error'); ?>
        </div>
    <?php endif; ?>
    <?php if (Session::has_flash('success')): ?>
        <div class="bg-red-100 text-green-800 px-4 py-2 rounded mb-4">
            <?= Session::flash('error'); ?>
        </div>
    <?php endif; ?>
    <form method="post" class="space-y-4">
    <input type="hidden" name="csrf_token" value="<?= $csrf_token; ?>">  
    <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Tên ngôn ngữ:</label>
            <input type="text" name="name" id="name" value="<?= $language['name']; ?>" required class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
        </div>
        <div class="mb-4">
            <label for="code" class="block text-sm font-medium text-gray-700">Mã ngôn ngữ:</label>
            <input type="text" name="code" id="code" value="<?= $language['code']; ?>" required class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
        </div>
        <div class="mb-4">
            <label for="status" class="block text-sm font-medium text-gray-700">Trạng thái:</label>
            <select name="status" id="status" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                <option value="active" <?= $language['status'] == 'active' ? 'selected' : ''; ?>>Hoạt động</option>
                <option value="inactive" <?= $language['status'] == 'inactive' ? 'selected' : ''; ?>>Tạm tắt</option>
            </select>
        </div>
        <div class="mb-4">
            <label for="is_default" class="block text-sm font-medium text-gray-700">Mặc định</label>
            <select name="is_default" id="is_default" class="mt-1 p-2 border border-gray-300 rounded w-full">
                <!-- check seledcted -->
                    <?php if ($language['is_default'] == 1): ?>
                    <option value="1" selected>Có</option>
                    <option value="0">Không</option>
                <?php else: ?>
                    <option value="0" selected>Không</option>
                    <option value="1">Có</option>
                <?php endif; ?>
            </select>
        </div>
        <div>
            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Cập nhật
            </button>
            <!-- button deleta url_admin(languages/delete/id) -->
            <a href="<?= admin_url('languages/delete/' . $language['id']); ?>" class="text-red-600 hover:underline pl-1" onclick="return confirm('Bạn có chắc muốn xóa?');">Xóa</a>
        </div>
    </form>
</div>

<?php
use System\Libraries\Session;
?><div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Thêm ngôn ngữ mới</h1>
    <?php if (Session::has_flash('error')): ?>
        <div class="bg-red-200 text-red-800 px-4 py-2 rounded mb-4">
            <?= Session::flash('error'); ?>
        </div>
    <?php endif; ?>
    <form method="post" action="<?= admin_url('languages/add')?>"class="space-y-4">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Tên ngôn ngữ:</label>
            <input type="text" name="name" id="name" required class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
        </div>
        <div class="mb-4">
            <label for="code" class="block text-sm font-medium text-gray-700">Mã ngôn ngữ:</label>
            <input type="text" name="code" id="code" required class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
        </div>
        <div class="mb-4">
            <label for="status" class="block text-sm font-medium text-gray-700">Trạng thái:</label>
            <select name="status" id="status" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                <option value="active">Hoạt động</option>
                <option value="inactive">Tạm tắt</option>
            </select>
        </div>
        <div class="mb-4">
            <div class="flex items-center">
                <input type="checkbox" name="is_default" id="is_default" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <label for="is_default" class="ml-2 block text-sm text-gray-900">Đặt làm ngôn ngữ mặc định</label>
            </div>
        </div>
        <div>
            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Thêm ngôn ngữ
            </button>
        </div>
    </form>
</div>

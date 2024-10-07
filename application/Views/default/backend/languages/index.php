<?php
use System\Libraries\Session;
?><div class="container mx-auto p-4">
<h1 class="text-2xl font-bold mb-4">Quản lý ngôn ngữ</h1>
<?php if (Session::has_flash('success')): ?>
    <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
        <?= Session::flash('success'); ?>
    </div>
<?php endif; ?>
<div class="overflow-x-auto">
    <a href="<?= admin_url('languages/add/') ?>"><button class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Thêm Ngôn Ngữ</button></a>
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

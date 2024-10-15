<?php
use System\Libraries\Session;
use App\Libraries\Fastlang as Flang;
?>
<div class="container mx-auto p-6">
<?php if (Session::has_flash('success')): ?>
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
            <?= Session::flash('success'); ?>
        </div>
    <?php endif; ?>
    <?php if (Session::has_flash('error')): ?>
        <div class="bg-red-100 text-red-800 p-4 mb-4 rounded">
        <?= Session::flash('error'); ?>
        </div>
    <?php endif; ?>
    <h2 class="text-2xl font-bold mb-6">Danh Sách Post Types</h2>
    <a href="<?= admin_url('posttype/add'); ?>" class="bg-blue-600 text-white px-4 py-2 mb-4 rounded hover:bg-blue-700">Tạo Post Type Mới</a>
    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">Tên</th>
                <th class="py-2 px-4 border-b">Slug</th>
                <th class="py-2 px-4 border-b">Hành Động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($postTypes as $postType): ?>
                <tr>
                    <td class="py-2 px-4 border-b"><?= $postType['name']; ?></td>
                    <td class="py-2 px-4 border-b"><?= $postType['slug']; ?></td>
                    <td class="py-2 px-4 border-b">
                        <a href="<?= admin_url('posttype/edit/' . $postType['id']); ?>" class="text-blue-500">Chỉnh sửa</a> |
                        <a href="<?= admin_url('posttype/delete/' . $postType['id']); ?>" class="text-red-500">Xóa</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

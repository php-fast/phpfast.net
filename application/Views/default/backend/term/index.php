<?php 
use System\Libraries\Session;?>
<?php
function buildOptions($tree, $level = 0, $current_id = null)
{
    $output = '';

    foreach ($tree as $node) {
        // Tạo dấu gạch dựa theo cấp độ
        $prefix = str_repeat('-', $level);
        // this current node not show in parent
        if ($current_id != null && $node['id'] == $current_id) {
            continue;
        }
        // Tạo thẻ option với id làm giá trị và tên là text
        $output .= '<option value="' . $node['id'] . '">' . $prefix . ' ' . $node['name'] . '</option>';

        // Nếu có children, đệ quy để xây dựng tiếp các options
        if (!empty($node['children'])) {
            $output .= buildOptions($node['children'], $level + 1);
        }
    }

    return $output;
}
function renderTermRows($nodes, $level = 0)
{
    foreach ($nodes as $node) {
        if(!$node) {
          continue;
        }
        ?>
        <tr>
            <td class="px-4 py-2"><?= str_repeat('&mdash; ', $level) . $node['name']; ?></td>
            <td class="px-4 py-2"><?= $node['slug']; ?></td>
            <td class="px-4 py-2"><?= $node['posttype']; ?></td>
            <td class="px-4 py-2"><?= $node['type']; ?></td>
            <td class="px-4 py-2"><?= $node['language_id']; ?></td>
            <td class="px-4 py-2"><?= $node['parent_id']; ?></td>
            <td class="px-4 py-2">
                <a href="/admin/term/<?= $node['posttype'] . '/' . $node['type'] ?>/edit/<?= $node['id']; ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Edit</a>
                <a href="/admin/term/<?= $node['posttype'] . '/' . $node['type'] ?>/delete/<?= $node['id'] . '?type=' . $node['type']; ?>" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Are you sure you want to delete?')">Delete</a>
            </td>
        </tr>
        <?php
        // Nếu có children, tiếp tục gọi đệ quy để render các children
        if (!empty($node['children'])) {
            renderTermRows($node['children'], $level + 1);
        }
    }
}
?>
<div class="container mx-auto">
    <h1 class="text-3xl font-bold mb-6"><?= $title ?></h1>
    <div class="flex flex-wrap -mx-3">
        <!-- Form Section (3/10) -->
        <div class="w-full md:w-3/10 px-3 mb-6">
            <div class="bg-white shadow-md rounded p-6">
                <form action="/admin/term/create" method="POST">
                    <input type="hidden" name="type" value="<?php echo htmlspecialchars($type); ?>">
                    <input type="hidden" name="posttype" value="<?php echo htmlspecialchars($posttype); ?>">
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 font-bold mb-2">Name <span class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <div class="mb-4">
                        <label for="slug" class="block text-gray-700 font-bold mb-2">Slug</label>
                        <input type="text" id="slug" name="slug" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div class="mb-4">
                        <label for="description" class="block text-gray-700 font-bold mb-2">Description</label>
                        <textarea id="description" name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                    </div>
                    <!-- option -->
                    <div class="mb-4">
                        <label for="parent_id" class="block text-gray-700 font-bold mb-2">Parent</label>
                        <select id="parent_id" name="parent_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">Select Parent</option>
                            <?= buildOptions($tree); ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="language_id" class="block text-gray-700 font-bold mb-2">Languge</label>
                        <select id="language_id" name="language_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">Select Languge</option>
                            <option value="1">Vietnamese</option>
                            <option value="2">English</option>
                            
                        </select>
                    </div>

                    <div class="flex items-center justify-between">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Add Term</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- Table Section (7/10) -->
        <div class="w-full md:w-7/10 px-3 mb-6">
            <div class="bg-white shadow-md rounded p-4">
            <table class="min-w-full table-auto">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Slug</th>
                        <th class="px-4 py-2">Post Type</th>
                        <th class="px-4 py-2">Type</th>
                        <th class="px-4 py-2">Language ID</th>
                        <th class="px-4 py-2">Parent ID</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php renderTermRows($tree); ?>
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>
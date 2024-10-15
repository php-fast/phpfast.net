<?php 
function buildOptions($tree, $level = 0, $current_id = null, $parent = null)
{
  $output = '';
  
    foreach ($tree as $node) {  
      // Tạo dấu gạch dựa theo cấp độ
      $prefix = str_repeat('-', $level);
      // die;
      // Không hiển thị chính node hiện tại trong danh sách cha
      if ($node['id'] == $current_id) {
        continue;
      }
      
      // Thiết lập `selected` nếu node hiện tại là `parent_id`
      $selected = ($node['id'] == $parent) ? ' selected' : '';
    // Xây dựng option
    $output .= '<option value="' . $node['id'] . '"' . $selected . '>' . $prefix . ' ' . $node['name'] . '</option>';
    
    // Nếu có children, đệ quy để xây dựng tiếp các options
    if (!empty($node['children'])) {
      $output .= buildOptions($node['children'], $level + 1, $current_id, $parent);
    }
  }

  return $output;
}

?>

<div class="max-w-4xl mx-auto p-6 bg-white shadow-md rounded-lg mt-10">
  <h2 class="text-2xl font-bold mb-6">Edit Term Details</h2>
  <form action="<?= admin_url('terms/update/' . $data['posttype'] . '/' . $data['type'] . '/' . $data['id']) ?>" method="POST">
    <?php 
    // Assuming $data contains term information 
    ?>
    <input type="hidden" name="type" value="<?= $data['type']; ?>">
    <input type="hidden" name="posttype" value="<?= $data['posttype']; ?>">

    <!-- Name -->
    <div class="mb-4">
      <label for="name" class="block text-gray-700 font-bold mb-2">Name:</label>
      <input type="text" id="name" name="name" value="<?= $data['name']; ?>" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring focus:ring-indigo-100">
    </div>

    <!-- Slug -->
    <div class="mb-4">
      <label for="slug" class="block text-gray-700 font-bold mb-2">Slug:</label>
      <input type="text" id="slug" name="slug" value="<?= $data['slug']; ?>" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring focus:ring-indigo-100">
  </div>

    <!-- Description -->
    <div class="mb-4">
      <label for="description" class="block text-gray-700 font-bold mb-2">Description:</label>
      <textarea id="description" name="description" rows="3" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring focus:ring-indigo-100"><?= $data['description'] ?></textarea>
    </div>
    <div class="mb-4">
      <label for="parent" class="block text-gray-700 font-bold mb-2">Parent</label>
      <select id="parent" name="parent" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
          <?= buildOptions($tree, 0, $data['id'], $data['parent']) ?>
      </select>
    </div>

    <!-- Buttons -->
    <div class="flex space-x-4">
    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Update</button>
    <a href="<?= admin_url('terms/delete/' . $data['posttype'] . '/' . $data['type'] . '/' . $data['id']); ?>" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600">Delete</a>
    </div>
  </form>
</div>

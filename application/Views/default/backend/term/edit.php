<?php 
function buildOptions($tree, $level = 0, $current_id = null)
{
    $output = '';

    foreach ($tree as $node) {
        // Tạo dấu gạch dựa theo cấp độ
        $prefix = str_repeat('-', $level);
        // this current node not show in parent
        if ($node['id'] == $current_id) {
          continue;
        }
        $output .= '<option value="' . $node['id'] . '">' . $prefix . ' ' . $node['name'] . '</option>';
        
        // Nếu có children, đệ quy để xây dựng tiếp các options
        if (!empty($node['children'])) {
            $output .= buildOptions($node['children'], $level + 1);
        }
    }

    return $output;
}
?>

<div class="max-w-4xl mx-auto p-6 bg-white shadow-md rounded-lg mt-10">
  <h2 class="text-2xl font-bold mb-6">Edit Term Details</h2>
  <form action="/admin/term/<?= $data['posttype'] . '/' . $data['type'] ?>/update/<?= $data['id'] ?>" method="POST">
    <?php 
    // Assuming $data contains term information 
    ?>
    <input type="hidden" name="type" value="<?php echo htmlspecialchars($data['type']); ?>">
    <input type="hidden" name="posttype" value="<?php echo htmlspecialchars($data['posttype']); ?>">
    <!-- ID -->
    <div class="mb-4">
      <label for="id" class="block text-gray-700 font-bold mb-2">ID:</label>
      <input type="text" id="id" name="id" value="<?php echo htmlspecialchars($data['id']); ?>" disabled class="w-full px-3 py-2 border rounded-md text-gray-500 cursor-not-allowed">
    </div>


    <!-- Name -->
    <div class="mb-4">
      <label for="name" class="block text-gray-700 font-bold mb-2">Name:</label>
      <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($data['name']); ?>" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring focus:ring-indigo-100">
    </div>

    <!-- Slug -->
    <div class="mb-4">
      <label for="slug" class="block text-gray-700 font-bold mb-2">Slug:</label>
      <input type="text" id="slug" name="slug" value="<?php echo htmlspecialchars($data['slug']); ?>" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring focus:ring-indigo-100">
    </div>

    <!-- Description -->
    <div class="mb-4">
      <label for="description" class="block text-gray-700 font-bold mb-2">Description:</label>
      <textarea id="description" name="description" rows="3" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring focus:ring-indigo-100"><?php echo htmlspecialchars($data['description']); ?></textarea>
    </div>
    <div class="mb-4">
      <label for="parent_id" class="block text-gray-700 font-bold mb-2">Parent</label>
      <select id="parent_id" name="parent_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
          <option value="">Select Parent</option>
          <?= buildOptions($tree, 0, $data['id']); ?>
      </select>
    </div>  
    <!-- Created At -->
    <div class="mb-4">
      <label for="created_at" class="block text-gray-700 font-bold mb-2">Created At:</label>
      <input type="text" id="created_at" name="created_at" value="<?php echo htmlspecialchars($data['created_at']); ?>" disabled class="w-full px-3 py-2 border rounded-md text-gray-500 cursor-not-allowed">
    </div>

    <!-- Updated At -->
    <div class="mb-4">
      <label for="updated_at" class="block text-gray-700 font-bold mb-2">Updated At:</label>
      <input type="text" id="updated_at" name="updated_at" value="<?php echo htmlspecialchars($data['updated_at'] ?? 'N/A'); ?>" disabled class="w-full px-3 py-2 border rounded-md text-gray-500 cursor-not-allowed">
    </div>

    <!-- Buttons -->
    <div class="flex space-x-4">
    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Update</button>
    <a href="/admin/term/<?= $data['posttype'] . '/' . $data['type'] ?>/delete/<?php echo htmlspecialchars($data['id']); ?>" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600">Delete</a>
    </div>
  </form>
</div>
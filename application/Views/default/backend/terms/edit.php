<?php
use System\Libraries\Session;
use App\Libraries\Fastlang as Flang;
?>

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

<div class="page-wrapper">
  <div class="flex flex-wrap">
        <!-- Sidebar left -->
  <?php echo $sidebar; ?>
        <!-- Content right -->
    <div class="content-wrapper">
      <div class="min-h-screen flex flex-col">
        <div class="page-main flex flex-wrap flex-1 py-5 px-4 md:px-8">
          <div class="flex flex-wrap flex-col w-full">
            <h1 class="text-3xl font-bold mb-6"><?= $title ?></h1>
            <?php if (!empty($success)): ?>
              <div class="bg-green-100 text-green-800 p-4 mb-4 rounded">
                  <?= $success; ?>
              </div>
            <?php endif; ?>
            <form action="<?= admin_url('terms/edit/' . $data['posttype'] . '/' . $data['type'] . '/' . $data['id']) ?>" method="POST">
              <input type="hidden" name="csrf_token" value="<?= $csrf_token; ?>">
              <input type="hidden" name="type" value="<?= $type ?? 'default' ?>">
              <input type="hidden" name="posttype" value="<?= $posttype ?? 'default' ?>">
              <!-- Form Fields Container -->
              <div class="flex flex-wrap -mx-2">
                <!-- name -->
                <div class="w-full md:w-1/2 px-2 mb-4">
                <label for="name" class="block text-gray-700 font-bold mb-2"><?= Flang::_e('lable_name') ?></label>
                <input type="text" value="<?= $data['name']; ?>" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    <!-- Display errors if any -->
                    <?php if (!empty($errors['name'])): ?>
                        <div class="text-red-800 mt-2 text-sm">
                            <?php foreach ($errors['name'] as $error): ?>
                                <p><?= $error; ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <!-- slug -->
                <div class="w-full md:w-1/2 px-2 mb-4">
                  <label for="slug" class="block text-gray-700 font-bold mb-2"><?= Flang::_e('lable_slug') ?></label>
                  <input type="text" value="<?= $data['slug']; ?>" id="slug" name="slug" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    <!-- Display errors if any -->
                    <?php if (!empty($errors['slug'])): ?>
                        <div class="text-red-800 mt-2 text-sm">
                            <?php foreach ($errors['slug'] as $error): ?>
                                <p><?= $error; ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                  <!-- lang -->
                <div class="w-full md:w-1/2 px-2 mb-4">
                  <label for="lang" class="block text-gray-700 font-bold mb-2"><?= Flang::_e('lable_lang') ?></label>
                  <select id="lang" name="lang" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                      <!-- <option value=""><?= Flang::_e('select_lang') ?></option> -->
                      <?php foreach($lang as $item) { ?>
                          <option value="<?= $item['id'] ?>" <?= $data['lang'] == $item['id'] ? 'selected' : '' ?>><?= $item['name'] ?></option>
                      <?php } ?>
                  </select>
                  <!-- Display errors if any -->
                  <?php if (!empty($errors['lang'])): ?>
                      <div class="text-red-800 mt-2 text-sm">
                          <?php foreach ($errors['lang'] as $error): ?>
                              <p><?= $error; ?></p>
                          <?php endforeach; ?>
                      </div>
                  <?php endif; ?>
                </div>
                <!-- parent -->
                <div class="w-full md:w-1/2 px-2 mb-4">
                  <label for="parent" class="block text-gray-700 font-bold mb-2"><?= Flang::_e('lable_parent') ?></label>
                  <select id="parent" name="parent" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <?= buildOptions($tree, 0, $data['id'], $data['parent']) ?>
                  </select>
                  <!-- Display errors if any -->
                  <?php if (!empty($errors['parent'])): ?>
                      <div class="text-red-800 mt-2 text-sm">
                          <?php foreach ($errors['parent'] as $error): ?>
                              <p><?= $error; ?></p>
                          <?php endforeach; ?>
                      </div>
                  <?php endif; ?>
                </div>
            <!-- Submit Button -->
              <div class="flex items-center justify-between">
              <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 me-2"><?= Flang::_e('btn_update') ?></button>
              <a href="<?= admin_url('terms/delete/' . $data['posttype'] . '/' . $data['type'] . '/' . $data['id']); ?>" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600"><?= Flang::_e('btn_del') ?></a>
              </div>
            </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php 
use System\Libraries\Session;
use App\Libraries\Fastlang as Flang;

if (Session::has_flash('success')){
  $success = Session::flash('success');
}
?>
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
            <td class="p-4 text-sm text-gray-900"><?= str_repeat('&mdash; ', $level) . $node['name']; ?></td>
            <td class="p-4 text-sm text-gray-900"><?= $node['slug']; ?></td>
            <td class="p-4 text-sm text-gray-900"><?= $node['posttype']; ?></td>
            <td class="p-4 text-sm text-gray-900"><?= $node['type']; ?></td>
            <td class="p-4 text-sm text-gray-900"><?= $node['lang_name']; ?></td>
            <td class="p-4 text-sm text-gray-900"><?= $node['parent_name'] ?? 'No'; ?></td>
            <td class="p-4 text-sm text-gray-900">
                <a href="<?= admin_url('terms/edit/' . $node['posttype'] . '/' . $node['type'] . '/' . $node['id']); ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"><?= Flang::_e('btn_edit') ?></a>
                <a href="<?= admin_url('terms/delete/' . $node['posttype'] . '/' . $node['type'] . '/' . $node['id']); ?>" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm(<?= Flang::_e('confirm_delete') ?>)"><?= Flang::_e('btn_del') ?></a>
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

<div class="page-wrapper">
      <div class="flex flex-wrap">
        <!-- Sidebar left -->
        <?php echo $sidebar; ?>
        <!-- Content right -->
        <div class="content-wrapper">
          <div class="min-h-screen flex flex-col">
            <div class="flex flex-wrap flex-1 py-5 px-4 md:px-8">
              <div class="flex flex-wrap flex-col w-full">
                <h1 class="text-3xl font-bold mb-6"><?= $title ?></h1>
                <?php if (!empty($success)): ?>
                  <div class="bg-green-100 text-green-800 p-4 mb-4 rounded">
                      <?= $success; ?>
                  </div>
                <?php endif; ?>
                <form action="<?= admin_url('terms/add') ?>" method="POST">
                  <input type="hidden" name="csrf_token" value="<?= $csrf_token; ?>">
                  <input type="hidden" name="type" value="<?= $type ?? 'default' ?>">
                  <input type="hidden" name="posttype" value="<?= $posttype ?? 'default' ?>">
                  <!-- Form Fields Container -->
                  <div class="flex flex-wrap -mx-2">
                    <!-- name -->
                    <div class="w-full md:w-1/2 px-2 mb-4">
                    <label for="name" class="block text-gray-700 font-bold mb-2"><?= Flang::_e('lable_name') ?><span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
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
                      <input type="text" id="slug" name="slug" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
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
                          <option value=""><?= Flang::_e('select_lang') ?></option>
                          <?php foreach($langActive as $lang) { ?>
                              <option value="<?= $lang['id'] ?>"><?= $lang['name'] ?></option>
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
                        <option value=""><?= Flang::_e('select_parent') ?></option>    
                        <?= buildOptions($tree); ?>
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
                <!-- description -->
                    <div class="w-full px-2 mb-4">
                      <label for="description" class="block text-gray-700 font-bold mb-2"><?= Flang::_e('lable_description') ?></label>
                      <textarea id="description" name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                          <!-- Display errors if any -->
                          <?php if (!empty($errors['description'])): ?>
                              <div class="text-red-800 mt-2 text-sm">
                                  <?php foreach ($errors['description'] as $error): ?>
                                      <p><?= $error; ?></p>
                                  <?php endforeach; ?>
                              </div>
                          <?php endif; ?>
                    </div>
                  </div> 
                <!-- Submit Button -->
                  <div class="flex items-center justify-between">
                      <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"><?= Flang::_e('Add') ?></button>
                  </div>
                </form>

                <!-- table -->
                <div class="flex flex-col mt-6 w-full">
                  <div class="overflow-x-auto rounded-lg border border-solid border-gray-200">
                    <div class="inline-block min-w-full align-middle">
                      <div class="overflow-hidden shadow sm:rounded-lg">
                        <table id="user-table"
                          class="min-w-full divide-y divide-gray-200 "
                        >
                          <thead class="bg-gray-50 ">
                            <tr>
                              <th scope="col" class="text-left text-gray-700 text-xs font-semibold leading-4 p-4 text-nowrap">
                              <?= Flang::_e('table_name') ?>
                              </th>
                              <th scope="col" class="text-left text-gray-700 text-xs font-semibold leading-4 p-4 text-nowrap">
                              <?= Flang::_e('table_slug') ?>
                            </th>
                            <th scope="col" class="text-left text-gray-700 text-xs font-semibold leading-4 p-4 text-nowrap">
                              <?= Flang::_e('table_post_type') ?>
                            </th>
                            <th scope="col" class="text-left text-gray-700 text-xs font-semibold leading-4 p-4 text-nowrap">
                              <?= Flang::_e('table_type') ?>
                            </th>
                            <th scope="col" class="text-left text-gray-700 text-xs font-semibold leading-4 p-4 text-nowrap">
                              <?= Flang::_e('table_lang') ?>
                            </th>
                            <th scope="col" class="text-left text-gray-700 text-xs font-semibold leading-4 p-4 text-nowrap">
                              <?= Flang::_e('table_parent') ?>
                            </th>
                            <th scope="col" class="text-left text-gray-700 text-xs font-semibold leading-4 p-4 text-nowrap">
                              <?= Flang::_e('table_action') ?>
                              </th>
                            </tr>
                          </thead>
                          <tbody class="bg-white">
                            <?php renderTermRows($tree); ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
         </div>
        </div>
     </div>
    </div>
</div>                

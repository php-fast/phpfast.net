<?php
use System\Libraries\Render;
use System\Libraries\Session;
use App\Libraries\Fastlang as Flang;
if (Session::has_flash('success')){
    $success = Session::flash('success');
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
                <!-- Table -->
                <?php if (!empty($success)): ?>
                  <div class="bg-green-100 text-green-800 p-4 mb-4 rounded">
                      <?= $success; ?>
                  </div>
                <?php endif; ?>

                <div class="page-title relative w-full mb-8">
               
                  <a href="<?= admin_url('users/add') ?>" class="btn btn-primary w-auto !h-10 mt-3 static md:absolute md:mt-0 right-0 top-0 !py-0 !px-5 gap-2">
                    <span class="icon">
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        class="size-5 stroke-white"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M12 4.5v15m7.5-7.5h-15"
                        />
                      </svg>
                    </span>
                    <?= Flang::_e('title_add_member') ?></a
                  >
                </div>
                <div class="flex flex-col mt-6 w-full">
                  <div
                    class="overflow-x-auto rounded-lg border border-solid border-gray-200"
                  >
                    <div class="inline-block min-w-full align-middle">
                      <div class="overflow-hidden shadow sm:rounded-lg">
                        <table id="user-table"
                          class="min-w-full divide-y divide-gray-200 "
                        >
                          <thead class="bg-gray-50 ">
                            <tr>
                              <th scope="col" class="text-left text-gray-700 text-xs font-semibold leading-4 p-4 text-nowrap">
                              <?= Flang::_e('username') ?>
                              </th>
                              <th scope="col" class="text-left text-gray-700 text-xs font-semibold leading-4 p-4 text-nowrap">
                              <?= Flang::_e('fullname') ?>
                            </th>
                            <th scope="col" class="text-left text-gray-700 text-xs font-semibold leading-4 p-4 text-nowrap">
                              <?= Flang::_e('phone') ?>
                            </th>
                            <th scope="col" class="text-left text-gray-700 text-xs font-semibold leading-4 p-4 text-nowrap">
                              <?= Flang::_e('email') ?>
                            </th>
                            <th scope="col" class="text-left text-gray-700 text-xs font-semibold leading-4 p-4 text-nowrap">
                              <?= Flang::_e('role') ?>
                            </th>
                            <th scope="col" class="text-left text-gray-700 text-xs font-semibold leading-4 p-4 text-nowrap">
                              <?= Flang::_e('status') ?>
                            </th>
                            <th scope="col" class="text-left text-gray-700 text-xs font-semibold leading-4 p-4 text-nowrap">
                              <?= Flang::_e('action') ?>
                              </th>
                            </tr>
                          </thead>
                          <tbody id="user-table-body" class="bg-white">
                          
                          <?php 
                          if (!empty($users) && !empty($users['data'])) :
                            foreach ($users['data'] as $user) : ?>
                              <tr id="user-row-<?= $user['id']; ?>">
                                  <td class="p-4 text-sm text-gray-900"><?= htmlspecialchars($user['username']); ?></td>
                                  <td class="p-4 text-sm text-gray-900"><?= htmlspecialchars($user['fullname']); ?></td>
                                  <td class="p-4 text-sm text-gray-900"><?= htmlspecialchars($user['phone']); ?></td>
                                  <td class="p-4 text-sm text-gray-900"><?= htmlspecialchars($user['email']); ?></td>
                                  <td class="p-4 text-sm text-gray-900"><?= htmlspecialchars($user['role']); ?></td>
                                  <td class="p-4 text-sm">
                                      <label class="inline-flex items-center cursor-pointer">
                                          <input type="checkbox" <?= $user['status'] === 'active' ? 'checked' : ''; ?> data-user-id="<?= $user['id']; ?>" class="status-checkbox sr-only peer">
                                          <div class="mr-3 relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                          <span class="status complete rounded-full py-1 px-2 <?= $user['status'] === 'active' ? 'bg-success-light text-green-800' : 'bg-red-500 text-white'; ?> text-center text-xs font-medium leading-4">
                                              <?= htmlspecialchars($user['status']); ?>
                                          </span>
                                      </label>
                                  </td>
                                  <td class="p-4 text-sm text-gray-900">
                                      <a href="/admin/users/edit/<?= $user['id']; ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Edit</a>
                                  </td>
                              </tr>
                        <?php 
                          endforeach;
                        endif;  
                        ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <?php
              $is_next = !empty($users['is_next']) && $users['is_next'] ? true:false;
              $query_params = [];
              if ($limit != 10){
                $query_params['limit'] = $limit;
              }
              if (HAS_GET('sortby') && S_GET('sortby') != 'id'){
                $query_params['sortby'] = S_GET('sortby');
              }
              if (HAS_GET('sortsc') && strtolower(S_GET('sortsc')) != 'desc'){
                $query_params['sortsc'] = 'asc';
              }
              // Gọi hàm pagination với các tùy chỉnh
              echo Render::pagination2(admin_url('users/index/'), $page, $is_next, $query_params);
              ?>
            </div>
         </div>
        </div>
     </div>
    </div>
</div>                

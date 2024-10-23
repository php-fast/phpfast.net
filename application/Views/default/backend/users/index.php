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
     
            <div class="page-main flex flex-wrap py-5 px-4 md:px-8">
              <?php if (!empty($success)): ?>
                  <div class="bg-green-100 text-green-800 p-4 mb-4 rounded">
                      <?= htmlspecialchars($success); ?>
                  </div>
              <?php endif; ?>

              <?php if (!empty($error)): ?>
                <div class="bg-red-200 text-red-800 p-4 mb-4 rounded">
                        <?= $error; ?>
                    </div>
                  <!-- <div class="bg-red-200 text-red-800 p-4 mb-4 rounded">
                      <?= htmlspecialchars($error); ?>
                  </div> -->
              <?php endif; ?>
              <div class="flex justify-between w-full items-center">
                <div class="flex items-center relative">
                  <form method="GET" action="/admin/users/index">
                    <div class="flex w-full flex-wrap">
                        <div class="fieldset w-full">
                            <div class="field h-full">
                                <select name="sortby" class="select-country w-full h-full rounded-lg border border-gray-300 p-2" onchange="this.form.submit()">
                                    <option value=""><?= Flang::_e('select_fillter')?></option>
                                    <option value="email" <?= isset($_GET['sortby']) && $_GET['sortby'] == 'email' ? 'selected' : ''; ?>>Email</option>
                                    <option value="username" <?= isset($_GET['sortby']) && $_GET['sortby'] == 'username' ? 'selected' : ''; ?>>Username</option>
                                    <option value="phone" <?= isset($_GET['sortby']) && $_GET['sortby'] == 'phone' ? 'selected' : ''; ?>>Phone</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
                <form class="flex" action="<?= admin_url('users/index') ?>" method="post">
                  <div class="relative flex w-full md:max-w-sm ml-3">
                    <input
                        type="text"
                        name="search_user"
                        placeholder="Search..."
                        style="border-top-right-radius: 0px; border-bottom-right-radius: 0px;"
                        class="w-full pr-4 py-2 border border-gray-300 shadow-sm outline-none"
                      />
                    
                      <button type="submit" class="text-white rounded-r bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                      <div class="inset-y-0 left-0 flex items-center pointer-events-none">
                        <svg
                          width="16"
                          height="16"
                          viewBox="0 0 16 16"
                          fill="#ffffffff"
                          xmlns="http://www.w3.org/2000/svg">
                          <path
                            fill-rule="evenodd"
                            clip-rule="evenodd"
                            d="M7 1.5C3.96243 1.5 1.5 3.96243 1.5 7C1.5 10.0376 3.96243 12.5 7 12.5C8.51899 12.5 9.89296 11.8852 10.8891 10.8891C11.8852 9.89296 12.5 8.51899 12.5 7C12.5 3.96243 10.0376 1.5 7 1.5ZM0 7C0 3.13401 3.13401 0 7 0C10.866 0 14 3.13401 14 7C14 8.66252 13.4197 10.1906 12.4517 11.3911L15.7803 14.7197C16.0732 15.0126 16.0732 15.4874 15.7803 15.7803C15.4874 16.0732 15.0126 16.0732 14.7197 15.7803L11.3911 12.4517C10.1906 13.4197 8.66252 14 7 14C3.13401 14 0 10.866 0 7Z"
                            fill="#ffffff"
                          />
                        </svg>
                      </div>
                      </button>
                    </div>
                  </div>
                </form> 
                  <a href="<?= admin_url('users/add') ?>" class="btn btn-primary w-auto !h-10 !py-0 !px-5 gap-2">
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
                    <?= Flang::_e('title_add_member') ?></a>
                </div>

                <div class="flex flex-col mt-5 w-full">
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
                                <?= Flang::_e('status') ?>
                              </th>
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
                              <?= Flang::_e('action') ?>
                              </th>
                            </tr>
                          </thead>
                          <tbody class="bg-white">
                           <?php 
                            if (!empty($users)) :
                              $usersList = isset($users['data']) ? $users['data'] : $users;
                              foreach ($usersList as $user) : ?>
                                  <tr id="user-row-<?= $user['id']; ?>">
                                      <td class="p-4 text-sm">
                                          <label class="inline-flex items-center cursor-pointer">
                                              <input type="checkbox" <?= $user['status'] === 'active' ? 'checked' : ''; ?> data-user-id="<?= $user['id']; ?>" class="status-checkbox sr-only peer">
                                              <div class="mr-3 relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                              <span class="status complete rounded-full py-1 px-2 <?= $user['status'] === 'active' ? 'bg-success-light text-green-800' : 'bg-red-500 text-white'; ?> text-center text-xs font-medium leading-4">
                                                  <?= htmlspecialchars($user['status']); ?>
                                              </span>
                                          </label>
                                      </td>
                                      <td class="p-4 text-sm text-gray-900"><?= htmlspecialchars($user['username']); ?></td>
                                      <td class="p-4 text-sm text-gray-900"><?= htmlspecialchars($user['fullname']); ?></td>
                                      <td class="p-4 text-sm text-gray-900"><?= htmlspecialchars($user['phone']); ?></td>
                                      <td class="p-4 text-sm text-gray-900"><?= htmlspecialchars($user['email']); ?></td>
                                      <td class="p-4 text-sm text-gray-900"><?= htmlspecialchars($user['role']); ?></td>
                                      <td class="p-4 text-sm text-gray-900">
                                          <a href="/admin/users/edit/<?= $user['id']; ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Edit</a>
                                      </td>
                                  </tr>
                              <?php 
                              endforeach;
                            endif; ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- // pagination -->
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
              echo Render::pagination(admin_url('users/index/'), $page, $is_next, $query_params);
              ?>
            </div>
         </div>
        </div>
     </div>
    </div>
</div>                

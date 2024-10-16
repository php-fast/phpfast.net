<?php
use System\Libraries\Session;
use App\Libraries\Fastlang as Flang;
if (Session::has_flash('success')){
    $success = Session::flash('success');
}
if (Session::has_flash('error')){
    $error = Session::flash('error');
}
?>

<link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.0.0/dist/tailwind.min.css" rel="stylesheet">

<div class="container mx-auto">
    <h1 class="text-3xl font-bold mb-6"><?= $title ?></h1>
    <div class="flex flex-wrap -mx-3">
        <?php if (!empty($errors)): ?>
            <div class="bg-green-100 text-green-800 p-4 mb-4 rounded">
                <?= $errors; ?>
            </div>
        <?php endif; ?>

        <!-- Form Section (3/10) -->
        <div class="w-full md:w-3/10 px-3 mb-6">
            <div class="bg-white shadow-md rounded p-6">
                <form action="<?= admin_url('users/create') ?>" method="POST">
               
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token; ?>">  
                    
                    <div class="mb-4">
                        <label for="username" class="block text-gray-700 font-bold mb-2"><?= Flang::_e('username') ?><span class="text-red-500">*</span></label>
                        <input type="text" id="username" placeholder="<?= Flang::_e('placeholder_username') ?>" name="username" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <?php if (!empty($errors['username'])): ?>
                        <div class="text-red-500 mt-2 text-sm">
                            <?php foreach ($errors['username'] as $error): ?>
                                <p><?= $error; ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <div class="mb-4">
                        <label for="fullname" class="block text-gray-700 font-bold mb-2"><?= Flang::_e('fullname') ?><span class="text-red-500">*</span></label>
                        <input type="text" id="fullname" placeholder="<?= Flang::_e('placeholder_fullname') ?>" name="fullname" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <?php if (!empty($errors['fullname'])): ?>
                        <div class="text-red-500 mt-2 text-sm">
                            <?php foreach ($errors['fullname'] as $error): ?>
                                <p><?= $error; ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 font-bold mb-2"><?= Flang::_e('email') ?><span class="text-red-500">*</span></label>
                        <input type="email" id="email" placeholder="<?= Flang::_e('placeholder_email') ?>" name="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <?php if (!empty($errors['email'])): ?>
                        <div class="text-red-500 mt-2 text-sm">
                            <?php foreach ($errors['email'] as $error): ?>
                                <p><?= $error; ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <div class="mb-4">
                        <label for="phone" class="block text-gray-700 font-bold mb-2"><?= Flang::_e('phone') ?><span class="text-red-500">*</span></label>
                        <input type="text" id="phone" placeholder="<?= Flang::_e('placeholder_phone') ?>" name="phone" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <?php if (!empty($errors['phone'])): ?>
                        <div class="text-red-500 mt-2 text-sm">
                            <?php foreach ($errors['phone'] as $error): ?>
                                <p><?= $error; ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <div class="mb-4">
                        <label for="password" class="block text-gray-700 font-bold mb-2"><?= Flang::_e('password') ?><span class="text-red-500">*</span></label>
                        <input type="password" id="password" placeholder="<?= Flang::_e('placeholder_password') ?>" name="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <?php if (!empty($errors['password'])): ?>
                        <div class="text-red-500 mt-2 text-sm">
                            <?php foreach ($errors['password'] as $error): ?>
                                <p><?= $error; ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <div class="mb-4">
                        <label for="password_repeat" class="block text-gray-700 font-bold mb-2"><?= Flang::_e('password_repeat') ?><span class="text-red-500">*</span></label>
                        <input type="password" id="password_repeat" placeholder="<?= Flang::_e('placeholder_password_repeat') ?>" name="password_repeat" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <?php if (!empty($errors['password_repeat'])): ?>
                        <div class="text-red-500 mt-2 text-sm">
                            <?php foreach ($errors['password_repeat'] as $error): ?>
                                <p><?= $error; ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <!-- role -->

                    <div class="mb-4">
                        <label for="role" class="block text-gray-700 font-bold mb-2"><?= Flang::_e('role') ?></label>
                        <select id="role" name="role" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value=""><?= Flang::_e('select_role') ?></option>
                            <?php foreach ($roles as $role => $permissions):?>
                                <option value="<?= htmlspecialchars($role) ?>"><?= htmlspecialchars($role) ?></option>
                                <?php endforeach; ?>
                        </select>
                    </div>
                    <?php if (!empty($errors['role_option'])): ?>
                        <div class="text-red-500 mt-2 text-sm">
                            <?php foreach ($errors['role_option'] as $error): ?>
                                <p><?= $error; ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <!-- permissions -->
                    <div id="permissions-container" class="w-full p-6 bg-white border border-gray-200 rounded-lg shadow-lg hidden">
                        <h2 id="role-title" class="text-lg font-semibold mb-4 text-gray-700"></h2>
                        <div id="permissions-list" class="grid grid-cols-4 gap-4"></div>
                    </div>

                    <div class="flex items-center justify-between">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"><?= Flang::_e('submit_add') ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const roles = <?php echo json_encode($roles); ?>;

    // Lấy các phần tử cần thiết từ DOM
    const roleSelect = document.getElementById('role');
    const permissionsContainer = document.getElementById('permissions-container');
    const permissionsList = document.getElementById('permissions-list');
    const roleTitle = document.getElementById('role-title');
    const permissionsJsonOutput = document.getElementById('permissions-json-output');

    // Lắng nghe sự kiện thay đổi của select
    roleSelect.addEventListener('change', function() {
        const selectedRole = roleSelect.value;

        // Xóa các quyền hiện có trong danh sách
        permissionsList.innerHTML = '';

        if (selectedRole && roles[selectedRole]) {
            // Hiển thị container danh sách quyền
            permissionsContainer.classList.remove('hidden');
            roleTitle.textContent = `Role: ${selectedRole} - Permissions`;

            // Lặp qua danh sách quyền và thêm checkbox
            for (const [resource, permissions] of Object.entries(roles[selectedRole])) {
                // Tạo phần chứa cho từng nhóm quyền
                const resourceContainer = document.createElement('div');
                resourceContainer.className = 'mb-4 p-4 border border-gray-300 rounded-md';

                const resourceTitle = document.createElement('h3');
                resourceTitle.className = 'font-medium text-gray-600 mb-2';
                resourceTitle.textContent = resource;

                resourceContainer.appendChild(resourceTitle);

                // Thêm các quyền cho từng resource trong lưới 3 cột
                const permissionsGrid = document.createElement('div');
                permissionsGrid.className = 'grid grid-cols-3 gap-4';

                permissions.forEach(permission => {
                    const div = document.createElement('div');

                    const label = document.createElement('label');
                    label.className = 'inline-flex items-center';

                    const checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.name = `permissions[${resource}][]`; // Đặt tên checkbox theo dạng permission[resource][]
                    checkbox.className = 'form-checkbox text-indigo-600';
                    checkbox.value = permission;
                    checkbox.checked = true; // Theo mặc định, tất cả các quyền đều được chọn

                    // Lắng nghe sự kiện thay đổi của checkbox
                    checkbox.addEventListener('change', updatePermissionsJson);

                    const span = document.createElement('span');
                    span.className = 'ml-2 text-gray-700';
                    span.textContent = permission;

                    label.appendChild(checkbox);
                    label.appendChild(span);
                    div.appendChild(label);
                    permissionsGrid.appendChild(div);
                });

                // Thêm lưới vào container quyền
                resourceContainer.appendChild(permissionsGrid);
                permissionsList.appendChild(resourceContainer);
            }

            // Cập nhật JSON ban đầu sau khi render
            updatePermissionsJson();
        } else {
            // Ẩn container nếu không có vai trò nào được chọn
            permissionsContainer.classList.add('hidden');
            permissionsJsonOutput.textContent = '';
        }
    });

    // Hàm để cập nhật và render JSON dựa trên các quyền đã chọn
    function updatePermissionsJson() {
        const selectedPermissions = {};

        // Lặp qua tất cả các checkbox và thêm quyền đã chọn vào JSON
        const checkboxes = document.querySelectorAll('#permissions-list input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            if (checkbox.checked) {
                const resource = checkbox.name.match(/permissions\[([^\]]+)]\[]/)[1];
                if (!selectedPermissions[resource]) {
                    selectedPermissions[resource] = [];
                }
                selectedPermissions[resource].push(checkbox.value);
            }
        });

        // Chuyển đổi đối tượng thành JSON
        const permissionsJson = JSON.stringify(selectedPermissions, null, 2);

        // Hiển thị JSON kết quả
        permissionsJsonOutput.textContent = permissionsJson;
    }
</script>

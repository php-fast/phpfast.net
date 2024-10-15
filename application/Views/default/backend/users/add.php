<link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.0.0/dist/tailwind.min.css" rel="stylesheet">

<div class="container mx-auto">
    <h1 class="text-3xl font-bold mb-6"><?= $title ?></h1>
    <div class="flex flex-wrap -mx-3">
        <!-- Form Section (3/10) -->
        <div class="w-full md:w-3/10 px-3 mb-6">
            <div class="bg-white shadow-md rounded p-6">
                <form action="<?= admin_url('users/create') ?>" method="POST">
               
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token; ?>">  
                    
                    <div class="mb-4">
                        <label for="username" class="block text-gray-700 font-bold mb-2">User name <span class="text-red-500">*</span></label>
                        <input type="text" id="username" name="username" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <div class="mb-4">
                        <label for="fullname" class="block text-gray-700 font-bold mb-2">Full name <span class="text-red-500">*</span></label>
                        <input type="text" id="fullname" name="fullname" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 font-bold mb-2">Email<span class="text-red-500">*</span></label>
                        <input type="email" id="email" name="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div class="mb-4">
                        <label for="phone" class="block text-gray-700 font-bold mb-2">Phone<span class="text-red-500">*</span></label>
                        <input type="phone" id="phone" name="phone" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div class="mb-4">
                        <label for="password_repeat" class="block text-gray-700 font-bold mb-2">password_repeat<span class="text-red-500">*</span></label>
                        <input type="text" id="password_repeat" name="password_repeat" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <!-- role -->

                    <div class="mb-4">
                        <label for="role" class="block text-gray-700 font-bold mb-2">Role</label>
                        <select id="role" name="role" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">Select roles</option>
                            <?php foreach ($roles as $role => $permissions):?>
                                <option value="<?= htmlspecialchars($role) ?>"><?= htmlspecialchars($role) ?></option>
                                <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- permissions -->
                    <div id="permissions-container" class="w-full p-6 bg-white border border-gray-200 rounded-lg shadow-lg hidden">
                        <h2 id="role-title" class="text-lg font-semibold mb-4 text-gray-700"></h2>
                        <div id="permissions-list" class="grid grid-cols-4 gap-4"></div>
                    </div>

                    <!-- status -->
                    <div class="mb-4">
                        <label for="status" class="block text-gray-700 font-bold mb-2">Status</label>
                        <select id="status" name="status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">Select Status</option>
                            <?php foreach ($status as $value): ?>
                                <option value="<?= htmlspecialchars($value) ?>"><?= htmlspecialchars($value) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="flex items-center justify-between">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Add Term</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- encode -->
<script>
        const roles = <?php echo json_encode($roles); ?>;

        // Lấy các phần tử cần thiết từ DOM
        const roleSelect = document.getElementById('role');
        const permissionsContainer = document.getElementById('permissions-container');
        const permissionsList = document.getElementById('permissions-list');
        const roleTitle = document.getElementById('role-title');

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
                    permissionsGrid.className = 'grid grid-cols-2 gap-4';

                    permissions.forEach(permission => {
                        const div = document.createElement('div');

                        const label = document.createElement('label');
                        label.className = 'inline-flex items-center';

                        const checkbox = document.createElement('input');
                        checkbox.type = 'checkbox';
                        checkbox.name = 'permission[]';
                        checkbox.className = 'form-checkbox text-indigo-600';
                        checkbox.value = permission;
                        checkbox.checked = true;

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
            } else {
                // Ẩn container nếu không có vai trò nào được chọn
                permissionsContainer.classList.add('hidden');
            }
        });
    </script>
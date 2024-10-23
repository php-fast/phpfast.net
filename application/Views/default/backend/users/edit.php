<?php
use System\Libraries\Session;
use App\Libraries\Fastlang as Flang;
if (Session::has_flash('success')) {
    $success = Session::flash('success');
}
if (Session::has_flash('error')) {
    $error = Session::flash('error');
}

$admin_permissions = $roles['admin'];

// Get the user's permissions from the database
$user_permissions = json_decode($user['permissions'], true);
?>
<div id="app">
    <div class="page-wrapper">
        <div class="flex flex-wrap">
            <!-- Sidebar left -->
            <?php echo $sidebar; ?>
            <!-- Content right -->
            <div class="content-wrapper">
                <div class="min-h-screen flex flex-col">
                    <div class="flex flex-wrap flex-1 py-5 px-4 md:px-8">
                        <div class="flex flex-wrap flex-col w-full">
                            <!-- Page Title -->
                            <div class="page-title relative w-full mb-8">
                                <h1 class="font-bold text-3xl leading-10 text-gray-900">
                                    <?= $title ?>
                                </h1>
                            </div>
                            <!-- Form -->
                            <?php if (!empty($success)): ?>
                                <div class="bg-green-100 text-green-800 p-4 mb-4 rounded">
                                    <?= $success; ?>
                                </div>
                            <?php elseif (!empty($error)): ?>
                                <div class="bg-red-200 text-red-800 p-4 mb-4 rounded">
                                    <?= $error; ?>
                                </div>
                            <?php endif; ?>

                            <form action="<?= admin_url('users/edit/' . $user['id']) ?>" method="POST">
                                <input type="hidden" name="csrf_token" value="<?= $csrf_token; ?>">

                                <!-- Form Fields Container -->
                                <div class="flex flex-wrap -mx-2">
                                    <!-- Username -->
                                    <div class="w-full md:w-1/2 px-2 mb-4">
                                        <label for="username" class="block text-gray-700 font-bold mb-2"><?= Flang::_e('username') ?><span class="text-red-500">*</span></label>
                                        <input v-model="formData.username" type="text" id="username" placeholder="<?= Flang::_e('placeholder_username') ?>" name="username" value="<?= htmlspecialchars($user['username'], ENT_QUOTES) ?>" class="" required>
                                        <!-- Display errors if any -->
                                        <?php if (!empty($errors['username'])): ?>
                                            <div class="text-red-500 mt-2 text-sm">
                                                <?php foreach ($errors['username'] as $error): ?>
                                                    <p><?= $error; ?></p>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Fullname -->
                                    <div class="w-full md:w-1/2 px-2 mb-4">
                                        <label for="fullname" class="block text-gray-700 font-bold mb-2"><?= Flang::_e('fullname') ?><span class="text-red-500">*</span></label>
                                        <input v-model="formData.fullname" type="text" id="fullname" placeholder="<?= Flang::_e('placeholder_fullname') ?>" name="fullname" value="<?= htmlspecialchars($user['fullname'], ENT_QUOTES) ?>" class="" required>
                                        <!-- Display errors if any -->
                                        <?php if (!empty($errors['fullname'])): ?>
                                            <div class="text-red-500 mt-2 text-sm">
                                                <?php foreach ($errors['fullname'] as $error): ?>
                                                    <p><?= $error; ?></p>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Email -->
                                    <div class="w-full md:w-1/2 px-2 mb-4">
                                        <label for="email" class="block text-gray-700 font-bold mb-2"><?= Flang::_e('email') ?><span class="text-red-500">*</span></label>
                                        <input v-model="formData.email" type="email" id="email" placeholder="<?= Flang::_e('placeholder_email') ?>" name="email" value="<?= htmlspecialchars($user['email'], ENT_QUOTES) ?>" class="" style="padding-left: 0.5rem;" required>
                                        <!-- Display errors if any -->
                                        <?php if (!empty($errors['email'])): ?>
                                            <div class="text-red-500 mt-2 text-sm">
                                                <?php foreach ($errors['email'] as $error): ?>
                                                    <p><?= $error; ?></p>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Phone -->
                                    <div class="w-full md:w-1/2 px-2 mb-4">
                                        <label for="phone" class="block text-gray-700 font-bold mb-2"><?= Flang::_e('phone') ?><span class="text-red-500">*</span></label>
                                        <input v-model="formData.phone" type="text" id="phone" placeholder="<?= Flang::_e('placeholder_phone') ?>" name="phone" value="<?= htmlspecialchars($user['phone'], ENT_QUOTES) ?>" class="" required>
                                        <!-- Display errors if any -->
                                        <?php if (!empty($errors['phone'])): ?>
                                            <div class="text-red-500 mt-2 text-sm">
                                                <?php foreach ($errors['phone'] as $error): ?>
                                                    <p><?= $error; ?></p>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Status -->
                                    <div class="w-full md:w-1/2 px-2 mb-4">
                                        <label for="status" class="block text-gray-700 font-bold mb-2"><?= Flang::_e('status') ?></label>
                                        <select v-model="formData.status" id="status" name="status" class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                            <?php foreach ($status as $status_option): ?>
                                                <option value="<?= $status_option ?>" <?= $user['status'] == $status_option ? 'selected' : '' ?>><?= $status_option ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <!-- Display errors if any -->
                                        <?php if (!empty($errors['status'])): ?>
                                            <div class="text-red-500 mt-2 text-sm">
                                                <?php foreach ($errors['status'] as $error): ?>
                                                    <p><?= $error; ?></p>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Role -->
                                    <div class="w-full md:w-1/2 px-2 mb-4">
                                        <label for="role" class="block text-gray-700 font-bold mb-2"><?= Flang::_e('role') ?></label>
                                        <select v-model="selectedRole" id="role" name="role" class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                            <option value=""><?= Flang::_e('select_role') ?></option>
                                            <?php foreach ($roles as $role => $permissions): ?>
                                                <option value="<?= $role ?>" <?= $user['role'] == $role ? 'selected' : '' ?>><?= $role ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <!-- Display errors if any -->
                                        <?php if (!empty($errors['role'])): ?>
                                            <div class="text-red-500 mt-2 text-sm">
                                                <?php foreach ($errors['role'] as $error): ?>
                                                    <p><?= $error; ?></p>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Permissions -->
                                <div v-if="adminPermissions" class="mb-4 w-full p-6 bg-white border border-gray-200 rounded-lg">
                                    <h2 class="text-lg font-semibold mb-4 text-gray-700">Permissions</h2>
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                        <div v-for="(permissions, resource) in adminPermissions" :key="resource" class="mb-4 p-4 border border-gray-300 rounded-md">
                                            <h3 class="font-medium text-gray-600 mb-2">{{ resource }}</h3>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                                <div v-for="permission in permissions" :key="permission">
                                                    <label class="inline-flex items-center">
                                                        <input type="checkbox" :name="`permissions[${resource}][]`" :value="permission" v-model="selectedPermissions[resource]" class="form-checkbox text-indigo-600">
                                                        <span class="ml-2 text-gray-700">{{ permission }}</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="flex items-center justify-between">
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"><?= Flang::_e('submit_update') ?></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>                
    </div>
</div>

<!-- Include Vue.js -->
<script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
<script>
    var app = new Vue({
        el: '#app',
        data: {
            roles: <?php echo json_encode($roles); ?>,
            adminPermissions: <?php echo json_encode($admin_permissions); ?>,
            selectedRole: '<?php echo $user['role']; ?>',
            selectedPermissions: <?php echo json_encode($user_permissions); ?>,
            formData: {
                username: '<?php echo htmlspecialchars($user['username'], ENT_QUOTES); ?>',
                fullname: '<?php echo htmlspecialchars($user['fullname'], ENT_QUOTES); ?>',
                email: '<?php echo htmlspecialchars($user['email'], ENT_QUOTES); ?>',
                phone: '<?php echo htmlspecialchars($user['phone'], ENT_QUOTES); ?>',
                password: '',
                password_repeat: '',
                status: '<?php echo $user['status']; ?>'
            }
        },
        watch: {
            selectedRole(newRole) {
                this.updatePermissions(newRole);
            }
        },
        methods: {
            updatePermissions(role) {
                // Always display admin permissions
                this.adminPermissions = <?php echo json_encode($admin_permissions); ?>;

                // Initialize permissions arrays if not set
                for (let resource in this.adminPermissions) {
                    if (this.adminPermissions.hasOwnProperty(resource)) {
                        if (!this.selectedPermissions[resource]) {
                            this.$set(this.selectedPermissions, resource, []);
                        }
                    }
                }

                // If a role is selected and exists in roles
                if (role && this.roles[role]) {
                    const rolePermissions = this.roles[role];

                    // Pre-select permissions based on the selected role
                    for (let resource in rolePermissions) {
                        if (rolePermissions.hasOwnProperty(resource)) {
                            this.selectedPermissions[resource] = rolePermissions[resource];
                        }
                    }
                }
            }
        },
        created() {
            // Initialize permissions
            this.updatePermissions(this.selectedRole);
        }
    });
</script>

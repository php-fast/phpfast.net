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
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md p-8 bg-white rounded-lg shadow-md">
        <h2 class="mb-6 text-2xl font-bold text-center"><?= Flang::_e('register_welcome') ?></h2>
        
        <?php if (!empty($success)): ?>
            <div class="p-4 mb-4 text-green-800 bg-green-100 rounded">
                <?= $success; ?>
            </div>
        <?php elseif (!empty($error)): ?>
            <div class="p-4 mb-4 text-red-800 bg-red-100 rounded">
                <?= $error; ?>
            </div>
        <?php endif; ?>
        
        <form method="post" action="<?= admin_url('auth/register') ?>">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token; ?>">

            <!-- Username Field -->
            <div class="mb-4">
                <label for="username" class="block text-gray-700"><?= Flang::_e('username') ?></label>
                <input type="text" name="username" class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300" value="<?= S_POST('username'); ?>">
                <?php if (!empty($errors['username'])): ?>
                    <div class="mt-2 text-sm text-red-500">
                        <?php foreach ($errors['username'] as $error): ?>
                            <p><?= $error; ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Email Field -->
            <div class="mb-4">
                <label for="email" class="block text-gray-700"><?= Flang::_e('email') ?></label>
                <input type="email" name="email" class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300" value="<?= S_POST('email'); ?>">
                <?php if (!empty($errors['email'])): ?>
                    <div class="mt-2 text-sm text-red-500">
                        <?php foreach ($errors['email'] as $error): ?>
                            <p><?= $error; ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Password Field -->
            <div class="mb-4">
                <label for="password" class="block text-gray-700"><?= Flang::_e('password') ?></label>
                <input type="password" name="password" class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300">
                <?php if (!empty($errors['password'])): ?>
                    <div class="mt-2 text-sm text-red-500">
                        <?php foreach ($errors['password'] as $error): ?>
                            <p><?= $error; ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Password Verify Field -->
            <div class="mb-4">
                <label for="    " class="block text-gray-700"><?= Flang::_e('password_verify') ?></label>
                <input type="password" name="password_verify" class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300">
                <?php if (!empty($errors['password_verify'])): ?>
                    <div class="mt-2 text-sm text-red-500">
                        <?php foreach ($errors['password_verify'] as $error): ?>
                            <p><?= $error; ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Fullname Field -->
            <div class="mb-4">
                <label for="fullname" class="block text-gray-700"><?= Flang::_e('fullname') ?></label>
                <input type="text" name="fullname" class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300" value="<?= S_POST('fullname'); ?>">
                <?php if (!empty($errors['fullname'])): ?>
                    <div class="mt-2 text-sm text-red-500">
                        <?php foreach ($errors['fullname'] as $error): ?>
                            <p><?= $error; ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <button type="submit" class="w-full py-2 text-white transition duration-300 bg-blue-600 rounded hover:bg-blue-700"><?= Flang::_e('register') ?></button>
        </form>

        <p class="mt-4 text-sm text-center">
            Already have an account? <a href="<?= admin_url('auth/login') ?>" class="text-blue-600 hover:underline"><?= Flang::_e('login') ?></a>.
        </p>
    </div>
</div>

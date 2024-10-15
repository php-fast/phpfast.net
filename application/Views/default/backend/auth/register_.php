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
<div class="min-h-screen bg-gray-100 flex items-center justify-center">
    <div class="w-full max-w-md bg-white rounded-lg shadow-md p-8">
        <h2 class="text-2xl font-bold text-center mb-6"><?= Flang::_e('register_welcome') ?></h2>
        
        <?php if (!empty($success)): ?>
            <div class="bg-green-100 text-green-800 p-4 mb-4 rounded">
                <?= $success; ?>
            </div>
        <?php elseif (!empty($error)): ?>
            <div class="bg-red-100 text-red-800 p-4 mb-4 rounded">
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
                    <div class="text-red-500 mt-2 text-sm">
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
                    <div class="text-red-500 mt-2 text-sm">
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
                    <div class="text-red-500 mt-2 text-sm">
                        <?php foreach ($errors['password'] as $error): ?>
                            <p><?= $error; ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Password Verify Field -->
            <div class="mb-4">
                <label for="password_verify" class="block text-gray-700"><?= Flang::_e('password_verify') ?></label>
                <input type="password" name="password_verify" class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300">
                <?php if (!empty($errors['password_verify'])): ?>
                    <div class="text-red-500 mt-2 text-sm">
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
                    <div class="text-red-500 mt-2 text-sm">
                        <?php foreach ($errors['fullname'] as $error): ?>
                            <p><?= $error; ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition duration-300"><?= Flang::_e('register') ?></button>
        </form>

        <p class="text-center mt-4 text-sm">
            Already have an account? <a href="<?= admin_url('auth/login') ?>" class="text-blue-600 hover:underline"><?= Flang::_e('login') ?></a>.
        </p>
    </div>
</div>

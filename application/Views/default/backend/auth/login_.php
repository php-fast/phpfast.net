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
        <h2 class="text-2xl font-bold text-center mb-6"><?= Flang::_e('login_welcome') ?></h2>

        <?php if (!empty($success)): ?>
            <div class="bg-green-100 text-green-800 p-4 mb-4 rounded">
                <?= $success; ?>
            </div>
        <?php elseif (!empty($error)): ?>
            <div class="bg-red-200 text-red-800 p-4 mb-4 rounded">
                <?= $error; ?>
            </div>
        <?php endif; ?>
            
        <form action="<?= auth_url('login') ?>" method="post">
            <!-- CSRF Token -->
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

            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition duration-300"><?= Flang::_e('login') ?></button>
        </form>

        <p class="text-center mt-4 text-sm">
            Don't have an account? <a href="<?= auth_url('register') ?>" class="text-blue-600 hover:underline"><?= Flang::_e('register') ?></a>.
        </p>

        <p class="text-center mt-2 text-sm">
            <a href="<?= auth_url('forgot_password') ?>" class="text-blue-600 hover:underline"><?= Flang::_e('forgot_pass') ?></a>
        </p>
    </div>
</div>

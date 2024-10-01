<?php
use System\Libraries\Session;
use App\Libraries\Fastlang as FL;
$error = Session::flash('error');
?>
    <h1><?= FL::_e('login_welcome') ?></h1>
    <?php if (isset($error)): ?>
        <div style="color: red;"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="<?= admin_url('auth/login') ?>" method="post">
        <input type="hidden" id="csrf_token" name="csrf_token" value="<?= $csrf_token??''; ?>" />
        <label for="username"><?= FL::_e('username') ?>:</label>
        <input type="text" id="username" name="username" required><br>
        <?php if (!empty($errors['username'])): ?>
            <div style="color: red;"><?= $errors['username'][0] ?></div>
        <?php endif; ?>
        <br>

        <label for="password"><?= FL::_e('password') ?>:</label>
        <input type="password" id="password" name="password" required><br>
        <?php if (!empty($errors['password'])): ?>
            <div style="color: red;"><?= $errors['password'][0] ?></div>
        <?php endif; ?>
        <br>

        <a href="<?= admin_url('auth/register') ?>"><?= FL::_e('register') ?></a> | <a href="<?= admin_url('auth/forgot') ?>"><?= FL::_e('forgot_pass') ?></a><br><br>

        <button type="submit" style="width: 200px;"><?= FL::_e('login') ?></button>
    </form>

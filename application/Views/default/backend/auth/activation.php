<?php
use System\Libraries\Session;
use App\Libraries\Fastlang as Flang;

if (Session::has_flash('error')){
    $error = Session::flash('error') ?? '';
}
if (Session::has_flash('success')){
    $success = Session::flash('success') ?? '';
}
?>
<div class="flex min-h-screen bg-gray-100 justify-center items-center">
    <div class="max-w-md w-full bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4 text-center"><?php Flang::_('active_welcome') ?></h2>

        <?php if (!empty($error)): ?>
            <div class="bg-red-100 text-red-700 px-4 py-2 mb-4 rounded">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="bg-green-100 text-green-700 px-4 py-2 mb-4 rounded">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

<<<<<<< HEAD
        <form action="<?php echo admin_url('auth/activation/' . $user_id); ?>" method="POST" class="space-y-4">
=======
        <form action="<?php echo auth_url('activation/' . $user_id); ?>" method="POST" class="space-y-4">
            thiếu csrf
>>>>>>> d56d56bc250df9011e4c0789f16dacc6aedb2327
            <div>
                <label for="activation_no" class="block text-sm font-medium text-gray-700"><?php Flang::_('active_number') ?>:</label>
                <input type="text" name="activation_no" id="activation_no" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>

            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <?php Flang::_('active') ?>
            </button>
        </form>

<<<<<<< HEAD
        <form action="<?php echo admin_url('auth/activation/' . $user_id); ?>" method="POST" class="mt-4">
=======
        <form action="<?php echo auth_url('activation/' . $user_id); ?>" method="POST" class="mt-4">
>>>>>>> d56d56bc250df9011e4c0789f16dacc6aedb2327
            <button type="submit" name="activation_resend" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                <?php Flang::_('resend_activation') ?>
            </button>
        </form>
    </div>
</div>

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


<div class="page-wrapper">
      <div class="flex flex-wrap">
        
        <?php echo $sidebar; ?>
      

        <div class="w-full p-3 md:w-2/3 md:p-12">
          <div class="authorize-form w-full h-full bg-white flex items-center justify-center">
            <div class="w-full max-w-[470px] p-6 space-y-4 md:space-y-6 sm:p-8">
              <h1 class="font-semibold text-2xl leading-8 text-gray-900">
                <?= Flang::_e('forgot_password_title') ?>
              </h1>

              <?php if (!empty($success)): ?>
                <div class="bg-green-100 text-green-800 p-4 mb-4 rounded">
                    <?= $success; ?>
                </div>
                <?php elseif (!empty($error)): ?>
                    <div class="bg-red-100 text-red-800 p-4 mb-4 rounded">
                        <?= $error; ?>
                    </div>
              <?php endif; ?>

              <form
                name="forgotPasssForm"
                method="post"
                class="space-y-4 md:space-y-6"
                action="<?= auth_url('forgot_password') ?>"
              >
              <input type="hidden" name="csrf_token" value="<?= $csrf_token; ?>">  

                <div class="fieldset">
                  <label
                    for="email"
                    class="block mb-2 font-medium text-sm leading-5 text-gray-900"
                    ><?= Flang::_e('email') ?></label
                  >
                  <div class="field email">
                    <input
                      type="email"
                      name="email"
                      id="email"
                      class=""
                      placeholder="<?= Flang::_e('placeholder_email') ?>"
                      required=""
                    />
                  </div>

                  <?php if (!empty($errors['email'])): ?>
                    <div class="text-red-500 mt-2 text-sm">
                        <?php foreach ($errors['email'] as $error): ?>
                            <p><?= $error; ?></p>
                        <?php endforeach; ?>
                     </div>
                  <?php endif; ?>

                </div>
                <!-- check validate email exsit reset success redirect qua page reset passsword <?= auth_url('reset_password') ?> -->
                <button type="submit" class="btn btn-primary w-full" disabled>
                  <?= Flang::_e('submit_link') ?>
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
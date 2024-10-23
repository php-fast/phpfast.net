
<?php
use System\Libraries\Session;
use App\Libraries\Fastlang as Flang;
if (Session::has_flash('success')){
    $success = Session::flash('success');
}
if (Session::has_flash('error')){
    $error = Session::flash('error');
}

$email = Session::get('email') ?? null;
$fullname = Session::get('fullname') ?? null;

?>

<div class="page-wrapper">
      <div class="flex flex-wrap">
        <?php echo $sidebar; ?>  

        <div class="w-full p-3 md:w-2/3 md:p-12">
          <div class="authorize-form w-full h-full bg-white flex items-center justify-center">
            <div class="w-full max-w-[470px] p-6 space-y-4 md:space-y-6 sm:p-8">
              <h1 class="font-semibold text-2xl leading-8 text-gray-900">
                <?= Flang::_e('register_welcome') ?>
              </h1>

            <?php if (!empty($success)): ?>
            <div class="bg-green-100 text-green-800 p-4 mb-4 rounded">
                <?= $success; ?>
            </div>
            <?php elseif (!empty($error)): ?>
                <div class="bg-red-200 text-red-800 p-4 mb-4 rounded">
                    <?= $error; ?>
                </div>
            <?php endif; ?>

              <form name="sigupForm" class="space-y-4 md:space-y-6" method="post" action="<?= auth_url('register') ?>">

                <input type="hidden" name="csrf_token" value="<?= $csrf_token; ?>">

                <div class="fieldset">
                  <label
                    for="username"
                    class="block mb-2 text-sm font-medium leading-5 text-gray-900"
                    ><?= Flang::_e('username') ?></label
                  >
                  <div class="field username">
                    <input
                      type="text"
                      name="username"
                      id="username"
                      class=""
                      placeholder="<?= Flang::_e('placeholder_username') ?>"
                      required=""
                    />
                  </div>
                  <?php if (!empty($errors['username'])): ?>
                    <div class="text-red-500 mt-2 text-sm">
                        <?php foreach ($errors['username'] as $error): ?>
                            <p><?= $error; ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                </div>
                <div class="fieldset">
                  <label
                    for="fullname"
                    class="block mb-2 text-sm font-medium leading-5 text-gray-900"
                    ><?= Flang::_e('fullname') ?></label
                  >
                  <div class="field fullname">
                    <input
                      type="text"
                      name="fullname"
                      id="fullname"
                      value="<?php echo $fullname ?>"
                      class=""
                      placeholder="<?= Flang::_e('placeholder_fullname') ?>"
                      required=""
                    />
                  </div>
                  <?php if (!empty($errors['fullname'])): ?>
                    <div class="text-red-500 mt-2 text-sm">
                        <?php foreach ($errors['fullname'] as $error): ?>
                            <p><?= $error; ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                </div>
                <div class="fieldset">
                  <label
                    for="email"
                    class="block mb-2 text-sm font-medium leading-5 text-gray-900"
                    ><?= Flang::_e('email') ?></label
                  >
                  <div class="field email">
                    <input
                      type="email"
                      name="email"
                      value="<?php echo $email ?>"
                      id="email"
                      class=""
                      placeholder="<?= Flang::_e('placeholder_email') ?>"
                      required=""
                      <?php echo empty($email) ? '' : 'readonly'; ?>
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
                <div class="fieldset">
                  <label
                    for="phone"
                    class="block mb-2 text-sm font-medium leading-5 text-gray-900"
                    ><?= Flang::_e('phone') ?></label
                  >
                  <div class="field phone">
                    <input
                      type="text"
                      name="phone"
                      id="phone"
                      class=""
                      placeholder="<?= Flang::_e('placeholder_phone') ?>"
                      required=""
                    />
                  </div>

                  <?php if (!empty($errors['phone'])): ?>
                    <div class="text-red-500 mt-2 text-sm">
                        <?php foreach ($errors['phone'] as $error): ?>
                            <p><?= $error; ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                </div>
                <div class="fieldset">
                  <label
                    for="password"
                    class="block mb-2 text-sm font-medium text-gray-900 "
                    ><?= Flang::_e('password') ?></label
                  >
                  <div class="field password">
                    <input
                      type="password"
                      name="password"
                      id="password"
                      placeholder="<?= Flang::_e('placeholder_password') ?>"
                      class=""
                      required=""
                    />
                    <span
                      id="togglePassword"
                      class="absolute inset-y-0 right-0 px-3 py-2 flex items-center text-gray-600 focus:outline-none"
                    >
                      <svg
                        id="eyeIcon"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="currentColor"
                        class="size-5"
                      >
                        <path
                          d="M3.53 2.47a.75.75 0 0 0-1.06 1.06l18 18a.75.75 0 1 0 1.06-1.06l-18-18ZM22.676 12.553a11.249 11.249 0 0 1-2.631 4.31l-3.099-3.099a5.25 5.25 0 0 0-6.71-6.71L7.759 4.577a11.217 11.217 0 0 1 4.242-.827c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113Z"
                        />
                        <path
                          d="M15.75 12c0 .18-.013.357-.037.53l-4.244-4.243A3.75 3.75 0 0 1 15.75 12ZM12.53 15.713l-4.243-4.244a3.75 3.75 0 0 0 4.244 4.243Z"
                        />
                        <path
                          d="M6.75 12c0-.619.107-1.213.304-1.764l-3.1-3.1a11.25 11.25 0 0 0-2.63 4.31c-.12.362-.12.752 0 1.114 1.489 4.467 5.704 7.69 10.675 7.69 1.5 0 2.933-.294 4.242-.827l-2.477-2.477A5.25 5.25 0 0 1 6.75 12Z"
                        />
                      </svg>
                    </span>
                  </div>
                  <?php if (!empty($errors['password'])): ?>
                    <div class="text-red-500 mt-2 text-sm">
                        <?php foreach ($errors['password'] as $error): ?>
                            <p><?= $error; ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                </div>
                <div class="fieldset">
                  <label
                    for="password_repeat"
                    class="block mb-2 text-sm font-medium text-gray-900"
                    ><?= Flang::_e('password_repeat') ?></label
                  >
                  <div class="field password">
                    <input
                      type="password" 
                      name="password_repeat"
                      id="password_repeat"
                      placeholder="<?= Flang::_e('placeholder_password_repeat') ?>"
                      class="password_repeat"
                      required=""
                    />
                    <span
                      id="togglePasswordrepeat"
                      class="absolute inset-y-0 right-0 px-3 py-2 flex items-center text-gray-600 focus:outline-none"
                    >
                      <svg
                        id="eyeIconrepeat"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="currentColor"
                        class="size-5"
                      >
                        <path
                          d="M3.53 2.47a.75.75 0 0 0-1.06 1.06l18 18a.75.75 0 1 0 1.06-1.06l-18-18ZM22.676 12.553a11.249 11.249 0 0 1-2.631 4.31l-3.099-3.099a5.25 5.25 0 0 0-6.71-6.71L7.759 4.577a11.217 11.217 0 0 1 4.242-.827c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113Z"
                        />
                        <path
                          d="M15.75 12c0 .18-.013.357-.037.53l-4.244-4.243A3.75 3.75 0 0 1 15.75 12ZM12.53 15.713l-4.243-4.244a3.75 3.75 0 0 0 4.244 4.243Z"
                        />
                        <path
                          d="M6.75 12c0-.619.107-1.213.304-1.764l-3.1-3.1a11.25 11.25 0 0 0-2.63 4.31c-.12.362-.12.752 0 1.114 1.489 4.467 5.704 7.69 10.675 7.69 1.5 0 2.933-.294 4.242-.827l-2.477-2.477A5.25 5.25 0 0 1 6.75 12Z"
                        />
                      </svg>
                    </span>
                  </div>
                  <?php if (!empty($errors['password_repeat'])): ?>
                    <div class="text-red-500 mt-2 text-sm">
                        <?php foreach ($errors['password_repeat'] as $error): ?>
                            <p><?= $error; ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                </div>
                <button type="submit" class="btn btn-primary w-full" disabled>
                  <?= Flang::_e('sign_up') ?>
                </button>
                <p class="text-sm font-light text-gray-500 ">
                  <?= Flang::_e('have_account') ?>
                  <a
                    href="<?= auth_url('login') ?>" 
                    class="font-medium text-primary-600 hover:underline "
                    ><?= Flang::_e('login') ?></a
                  >
                </p>
                <div class="my-4 flex items-center gap-4">
                  <hr class="w-full border-gray-300" />
                  <p class="text-sm text-gray-800 text-center">or</p>
                  <hr class="w-full border-gray-300" />
                </div>
                <a
                   href="<?= auth_url('login_google') ?>"
                  class="w-full flex items-center justify-center gap-4 py-3 px-6 text-sm tracking-wide text-gray-800 border border-gray-300 rounded-md bg-gray-50 hover:bg-gray-100 focus:outline-none"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="20px"
                    class="inline"
                    viewBox="0 0 512 512"
                  >
                    <path
                      fill="#fbbd00"
                      d="M120 256c0-25.367 6.989-49.13 19.131-69.477v-86.308H52.823C18.568 144.703 0 198.922 0 256s18.568 111.297 52.823 155.785h86.308v-86.308C126.989 305.13 120 281.367 120 256z"
                      data-original="#fbbd00"
                    />
                    <path
                      fill="#0f9d58"
                      d="m256 392-60 60 60 60c57.079 0 111.297-18.568 155.785-52.823v-86.216h-86.216C305.044 385.147 281.181 392 256 392z"
                      data-original="#0f9d58"
                    />
                    <path
                      fill="#31aa52"
                      d="m139.131 325.477-86.308 86.308a260.085 260.085 0 0 0 22.158 25.235C123.333 485.371 187.62 512 256 512V392c-49.624 0-93.117-26.72-116.869-66.523z"
                      data-original="#31aa52"
                    />
                    <path
                      fill="#3c79e6"
                      d="M512 256a258.24 258.24 0 0 0-4.192-46.377l-2.251-12.299H256v120h121.452a135.385 135.385 0 0 1-51.884 55.638l86.216 86.216a260.085 260.085 0 0 0 25.235-22.158C485.371 388.667 512 324.38 512 256z"
                      data-original="#3c79e6"
                    />
                    <path
                      fill="#cf2d48"
                      d="m352.167 159.833 10.606 10.606 84.853-84.852-10.606-10.606C388.668 26.629 324.381 0 256 0l-60 60 60 60c36.326 0 70.479 14.146 96.167 39.833z"
                      data-original="#cf2d48"
                    />
                    <path
                      fill="#eb4132"
                      d="M256 120V0C187.62 0 123.333 26.629 74.98 74.98a259.849 259.849 0 0 0-22.158 25.235l86.308 86.308C162.883 146.72 206.376 120 256 120z"
                      data-original="#eb4132"
                    />
                  </svg>
                    <?= Flang::_e('login_google') ?>
                  </a>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

<?php
use System\Libraries\Session;
use App\Libraries\Fastlang as Flang;
if (Session::has_flash('success')){
    $success = Session::flash('success');
}
if (Session::has_flash('error')){
    $error = Session::flash('error');
}

$email = Session::has_flash('email')? Session::has_flash('email') : null;
$fullname = Session::has_flash('fullname') ? Session::has_flash('fullname') : null;

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
                <div class="bg-red-100 text-red-800 p-4 mb-4 rounded">
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
                    for="password_verify"
                    class="block mb-2 text-sm font-medium text-gray-900"
                    ><?= Flang::_e('password') ?></label
                  >
                  <div class="field password">
                    <input
                      type="password" 
                      name="password_verify"
                      id="password_verify"
                      placeholder="<?= Flang::_e('placeholder_password_repeat') ?>"
                      class="password_verify"
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
                  <?php if (!empty($errors['password_verify'])): ?>
                    <div class="text-red-500 mt-2 text-sm">
                        <?php foreach ($errors['password_verify'] as $error): ?>
                            <p><?= $error; ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                </div>
                <!-- <div>
                  <div
                    class="block mb-2 text-sm font-medium text-gray-900 "
                  >
                    <?= Flang::_e('social_media') ?>
                  </div>
                  <div class="fieldset space-y-4 md:space-y-6">
                    <div class="field relative">
                      <input
                        type="text"
                        name="telegram"
                        id="telegram"
                        placeholder="<?= Flang::_e('placeholder_telegram') ?>"
                        class="!pl-10"
                      />
                      <svg
                        class="absolute top-[10px] left-[10px]"
                        width="21"
                        height="20"
                        viewBox="0 0 21 20"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                      >
                        <g clip-path="url(#clip0_355_17038)">
                          <path
                            d="M10.5 20C16.0228 20 20.5 15.5228 20.5 10C20.5 4.47715 16.0228 0 10.5 0C4.97715 0 0.5 4.47715 0.5 10C0.5 15.5228 4.97715 20 10.5 20Z"
                            fill="url(#paint0_linear_355_17038)"
                          />
                          <path
                            fill-rule="evenodd"
                            clip-rule="evenodd"
                            d="M5.02635 9.89446C7.94156 8.62435 9.88548 7.78702 10.8581 7.38246C13.6352 6.22737 14.2123 6.02672 14.5884 6.02009C14.6711 6.01863 14.8561 6.03913 14.9759 6.13635C15.0771 6.21844 15.1049 6.32934 15.1182 6.40717C15.1316 6.485 15.1481 6.6623 15.135 6.80083C14.9845 8.38207 14.3333 12.2193 14.002 13.9903C13.8618 14.7397 13.5858 14.991 13.3186 15.0155C12.7379 15.069 12.2969 14.6318 11.7345 14.2631C10.8543 13.6861 10.3571 13.327 9.50279 12.764C8.51547 12.1134 9.15551 11.7558 9.71818 11.1714C9.86543 11.0184 12.4241 8.69115 12.4736 8.48002C12.4798 8.45362 12.4856 8.3552 12.4271 8.30322C12.3686 8.25125 12.2823 8.26903 12.22 8.28316C12.1318 8.30319 10.7257 9.23252 8.00198 11.0711C7.60288 11.3452 7.2414 11.4787 6.91752 11.4717C6.56046 11.464 5.87364 11.2698 5.36306 11.1039C4.73681 10.9003 4.23907 10.7927 4.28242 10.4469C4.30499 10.2669 4.55297 10.0827 5.02635 9.89446Z"
                            fill="white"
                          />
                        </g>
                        <defs>
                          <linearGradient
                            id="paint0_linear_355_17038"
                            x1="10.5"
                            y1="0"
                            x2="10.5"
                            y2="19.8517"
                            gradientUnits="userSpaceOnUse"
                          >
                            <stop stop-color="#2AABEE" />
                            <stop offset="1" stop-color="#229ED9" />
                          </linearGradient>
                          <clipPath id="clip0_355_17038">
                            <rect
                              width="20"
                              height="20"
                              fill="white"
                              transform="translate(0.5)"
                            />
                          </clipPath>
                        </defs>
                      </svg>
                      <?php if (!empty($errors['telegram'])): ?>
                    <div class="text-red-500 mt-2 text-sm">
                        <?php foreach ($errors['telegram'] as $error): ?>
                            <p><?= $error; ?></p>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                    </div>
                    <div class="field relative">
                      <input
                        type="text"
                        name="skype"
                        id="skype"
                        placeholder="<?= Flang::_e('placeholder_skype') ?>"
                        class="!pl-10"
                      />
                      <svg
                        class="absolute top-[10px] left-[10px]"
                        width="21"
                        height="20"
                        viewBox="0 0 21 20"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                      >
                        <g clip-path="url(#clip0_355_17043)">
                          <path
                            d="M20.5 10C20.5 11.9778 19.9135 13.9112 18.8147 15.5557C17.7159 17.2002 16.1541 18.4819 14.3268 19.2388C12.4996 19.9957 10.4889 20.1937 8.5491 19.8079C6.60929 19.422 4.82746 18.4696 3.42894 17.0711C2.03041 15.6725 1.078 13.8907 0.692152 11.9509C0.306299 10.0111 0.504333 8.00043 1.26121 6.17317C2.01809 4.3459 3.29981 2.78412 4.9443 1.6853C6.58879 0.58649 8.52219 0 10.5 0C13.1506 0.00510569 15.6912 1.06031 17.5654 2.93457C19.4397 4.80883 20.4949 7.3494 20.5 10Z"
                            fill="#00A9F0"
                          />
                          <path
                            d="M16.2464 10.9534C16.0514 10.6763 15.9491 10.3445 15.9544 10.0057C15.9556 9.12721 15.7447 8.26141 15.3395 7.48195C14.9343 6.7025 14.3468 6.03244 13.627 5.52879C12.9073 5.02514 12.0765 4.70279 11.2054 4.58916C10.3343 4.47553 9.44859 4.57398 8.6237 4.87615C8.30753 4.99469 7.96213 5.01098 7.6362 4.92274C7.34081 4.83956 7.03319 4.80841 6.72711 4.83069C6.25356 4.86385 5.79747 5.02296 5.40605 5.29155C5.01463 5.56014 4.70211 5.92846 4.50082 6.35839C4.29953 6.78831 4.2168 7.26422 4.26118 7.73685C4.30556 8.20948 4.47544 8.66167 4.75325 9.0466C4.94902 9.3255 5.05128 9.65931 5.04529 10C5.0447 10.8783 5.25618 11.7437 5.66176 12.5226C6.06734 13.3016 6.65502 13.9712 7.37484 14.4743C8.09466 14.9775 8.92533 15.2994 9.79624 15.4127C10.6671 15.526 11.5525 15.4273 12.3771 15.125C12.6932 15.0061 13.0386 14.9894 13.3646 15.0773C13.6599 15.1612 13.9675 15.1931 14.2737 15.1716C14.7473 15.138 15.2033 14.9785 15.5946 14.7097C15.986 14.4408 16.2984 14.0723 16.4996 13.6423C16.7008 13.2123 16.7836 12.7363 16.7393 12.2636C16.695 11.7909 16.5252 11.3386 16.2476 10.9534H16.2464ZM10.4998 8.97728C10.5635 8.99319 10.6294 9.01024 10.6998 9.02615C11.7998 9.28751 13.4623 9.68297 13.4623 11.5148C13.4623 12.4171 13.018 13.1057 12.2123 13.4466C11.6635 13.683 11.0442 13.725 10.501 13.7273H10.4237C8.6987 13.7273 7.60893 12.3852 7.56348 12.3284C7.49435 12.2444 7.44263 12.1475 7.41132 12.0433C7.38001 11.939 7.36974 11.8297 7.38111 11.7214C7.39248 11.6132 7.42526 11.5084 7.47754 11.4129C7.52983 11.3175 7.60057 11.2334 7.68565 11.1656C7.77073 11.0978 7.86845 11.0476 7.97313 11.0179C8.07781 10.9882 8.18734 10.9796 8.29537 10.9926C8.40339 11.0057 8.50774 11.0401 8.60235 11.0938C8.69695 11.1475 8.77992 11.2196 8.84643 11.3057C8.84643 11.3057 9.50098 12.0568 10.4237 12.0864H10.4998C11.5453 12.1046 11.7885 11.7818 11.8214 11.5068C11.8589 11.1796 11.5851 10.9386 10.4998 10.6602C10.4419 10.6443 10.3862 10.6296 10.3203 10.6148C9.2737 10.3705 7.69188 10 7.69188 8.30797C7.69188 7.79206 7.89757 6.13638 10.4998 6.07047H10.6544C12.3896 6.07047 13.4135 7.4341 13.4567 7.49547C13.5863 7.67027 13.6411 7.88939 13.6091 8.10463C13.5772 8.31987 13.461 8.5136 13.2862 8.64319C13.1114 8.77279 12.8923 8.82763 12.677 8.79567C12.4618 8.7637 12.2681 8.64754 12.1385 8.47274C12.1192 8.44774 11.5317 7.79092 10.6544 7.71251C10.6021 7.71251 10.551 7.70456 10.4998 7.70342C9.89189 7.68751 9.33279 7.91706 9.33279 8.24319C9.33279 8.59319 9.49984 8.72728 10.4998 8.97728Z"
                            fill="white"
                          />
                        </g>
                        <defs>
                          <clipPath id="clip0_355_17043">
                            <rect
                              width="20"
                              height="20"
                              fill="white"
                              transform="translate(0.5)"
                            />
                          </clipPath>
                        </defs>
                      </svg>
                      <?php if (!empty($errors['skype'])): ?>
                    <div class="text-red-500 mt-2 text-sm">
                        <?php foreach ($errors['skype'] as $error): ?>
                            <p><?= $error; ?></p>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                    </div>
                    <div class="field relative">
                      <input
                        type="text"
                        name="whatsapp"
                        id="whatsapp"
                        placeholder="<?= Flang::_e('placeholder_whatsapp') ?>"
                        class="!pl-10"
                      />
                      <svg
                        class="absolute top-[10px] left-[10px]"
                        width="21"
                        height="20"
                        viewBox="0 0 21 20"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink"
                      >
                        <rect
                          x="0.5"
                          width="20"
                          height="20"
                          fill="url(#pattern0_355_17048)"
                        />
                        <defs>
                          <pattern
                            id="pattern0_355_17048"
                            patternContentUnits="objectBoundingBox"
                            width="1"
                            height="1"
                          >
                            <use
                              xlink:href="#image0_355_17048"
                              transform="scale(0.00195312)"
                            />
                          </pattern>
                          <image
                            id="image0_355_17048"
                            width="512"
                            height="512"
                            xlink:href="data:image/png;base64,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"
                          />
                        </defs>
                      </svg>
                    </div>
                  </div>
                </div> -->
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
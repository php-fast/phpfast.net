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
                <?= FLang::_e('reset_password_title') ?>
              </h1>
              <form
                name="resetPassForm"
                class="space-y-4 md:space-y-6"
                action=""
                method="post"
              > 
                <div class="fieldset">
                  <label
                    for="password"
                    class="block mb-2 text-sm font-medium text-gray-900 "
                    ><?= Flang::_e('new password') ?></label
                  >
                  <div class="field password relative">
                    <input
                      type="password"
                      name="password"
                      id="password"
                      placeholder="<?= Flang::_e('new_password') ?>"
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
                </div>
                <button type="submit" class="btn btn-primary w-full" disabled>
                <?= Flang::_e('change_password') ?>
                </button>
                <div
                  class="bg-success-light border border-success-dark font-normal text-sm leading-5 text-success py-5 rounded relative pl-12 pr-4"
                  role="alert"
                >
                  <span class="icon absolute inset-y-0 left-5 flex items-center"
                    ><svg
                      width="21"
                      height="20"
                      viewBox="0 0 21 20"
                      fill="none"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path
                        fill-rule="evenodd"
                        clip-rule="evenodd"
                        d="M17.2074 5.29303C17.3949 5.48056 17.5002 5.73487 17.5002 6.00003C17.5002 6.26519 17.3949 6.5195 17.2074 6.70703L9.20741 14.707C9.01988 14.8945 8.76557 14.9998 8.50041 14.9998C8.23524 14.9998 7.98094 14.8945 7.79341 14.707L3.79341 10.707C3.61125 10.5184 3.51045 10.2658 3.51273 10.0036C3.51501 9.74143 3.62018 9.49062 3.80559 9.30521C3.991 9.1198 4.24181 9.01464 4.50401 9.01236C4.7662 9.01008 5.0188 9.11087 5.20741 9.29303L8.50041 12.586L15.7934 5.29303C15.9809 5.10556 16.2352 5.00024 16.5004 5.00024C16.7656 5.00024 17.0199 5.10556 17.2074 5.29303Z"
                        fill="#0E9F6E"
                      />
                    </svg>
                  </span>
                  <span class="block sm:inline"><?= Flang::_e('reset_password_success') ?></span>
                  <a href="<?= auth_url('login') ?>" class="md:ml-3"
                    ><strong class="font-semibold"><?= Flang::_e('login_now') ?></strong></a
                  >
                  <span
                    class="close absolute inset-y-0 right-5 flex items-center cursor-pointer"
                  >
                    <svg
                      class="fill-success-dark"
                      width="19"
                      height="18"
                      viewBox="0 0 19 18"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path
                        fill-rule="evenodd"
                        clip-rule="evenodd"
                        d="M4.36409 3.86375C4.53287 3.69502 4.76175 3.60024 5.00039 3.60024C5.23904 3.60024 5.46792 3.69502 5.63669 3.86375L9.50039 7.72745L13.3641 3.86375C13.4471 3.77779 13.5464 3.70923 13.6562 3.66206C13.766 3.61489 13.8841 3.59006 14.0036 3.58902C14.1231 3.58798 14.2416 3.61076 14.3523 3.65601C14.4629 3.70126 14.5633 3.76809 14.6478 3.85259C14.7324 3.9371 14.7992 4.03758 14.8444 4.14819C14.8897 4.2588 14.9125 4.37731 14.9114 4.49681C14.9104 4.61631 14.8856 4.73441 14.8384 4.84421C14.7912 4.95402 14.7227 5.05333 14.6367 5.13635L10.773 9.00005L14.6367 12.8637C14.8006 13.0335 14.8914 13.2608 14.8893 13.4968C14.8872 13.7328 14.7926 13.9585 14.6257 14.1254C14.4589 14.2923 14.2331 14.3869 13.9972 14.389C13.7612 14.391 13.5338 14.3003 13.3641 14.1363L9.50039 10.2726L5.63669 14.1363C5.46695 14.3003 5.23961 14.391 5.00363 14.389C4.76766 14.3869 4.54192 14.2923 4.37506 14.1254C4.20819 13.9585 4.11354 13.7328 4.11149 13.4968C4.10944 13.2608 4.20015 13.0335 4.36409 12.8637L8.22779 9.00005L4.36409 5.13635C4.19537 4.96757 4.10059 4.7387 4.10059 4.50005C4.10059 4.2614 4.19537 4.03252 4.36409 3.86375Z"
                      />
                    </svg>
                  </span>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
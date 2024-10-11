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
        <div class="w-full md:w-1/3">
          <div
            class="content bg-blue-800 h-auto md:h-full md:min-h-screen pt-12 px-5"
          >
            <div class="logo mb-3">
              <svg
                width="214"
                height="48"
                viewBox="0 0 214 48"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
              >
                <g clip-path="url(#clip0_100_3429)">
                  <path
                    d="M21.9321 48.2627C21.9321 48.2627 38.3004 44.0842 35.3177 34.1238C32.728 25.4752 22.0796 25.9672 16.0151 22.0049C11.6272 19.1382 8.53297 16.6171 9.36056 13.4081C10.6419 8.43574 19.7308 3.41276 28.2252 1.96362C28.2252 1.96362 20.1463 6.52158 27.3965 11.0784C34.6466 15.6352 49.9757 24.3357 43.9686 36.1438C37.9604 47.9519 21.9321 48.2627 21.9321 48.2627Z"
                    fill="url(#paint0_linear_100_3429)"
                  />
                  <path
                    d="M31.3779 36.4243C31.6954 38.6143 30.7271 40.8696 29.1496 42.4235C27.5732 43.9773 25.4518 44.8871 23.2866 45.3432C16.8775 46.6932 9.79288 43.9863 5.91726 38.7055C2.04165 33.4247 1.5845 25.8547 4.79579 20.1449C4.30599 23.4541 4.37693 26.9649 5.83394 29.9758C7.29208 32.9867 10.3739 35.3704 13.7169 35.251C18.1623 35.0922 21.9039 30.7944 26.311 31.3979C28.841 31.7436 31.0119 33.8976 31.3779 36.4243Z"
                    fill="url(#paint1_linear_100_3429)"
                  />
                  <path
                    d="M30.8061 6.03299C30.3546 4.79779 30.6395 3.35879 31.6495 2.51543C31.7362 2.44337 31.8274 2.37581 31.9242 2.31276C33.7821 1.11697 38.0349 1.93781 40.0369 0C40.0369 0 37.9651 7.04299 34.1334 7.76812C32.0931 8.15433 31.1979 7.10154 30.8061 6.03299Z"
                    fill="#0E5ED9"
                  />
                  <path
                    d="M3.00781 28.9443C3.00781 28.9443 7.24487 39.3022 14.0807 40.3381C20.9165 41.374 15.2539 44.94 15.2539 44.94C15.2539 44.94 4.57067 41.7884 3.00781 28.9443Z"
                    fill="url(#paint2_linear_100_3429)"
                  />
                  <path
                    d="M28.1464 14.8584C28.1464 14.8584 19.3424 14.8584 11.885 14.8584C11.885 14.8584 15.4251 21.6154 25.7649 23.1445C40.5456 25.33 38.0899 39.1987 38.0899 39.1987C38.0899 39.1987 45.3333 27.4941 35.3177 21.2798C26.5824 15.8605 28.1464 14.8584 28.1464 14.8584Z"
                    fill="url(#paint3_linear_100_3429)"
                  />
                  <path
                    d="M31.4376 5.0173C31.4376 5.0173 31.1505 2.84191 33.6366 2.9455C36.1228 3.04909 37.3659 1.96252 37.3659 1.96252C37.3659 1.96252 31.8283 8.22859 31.4376 5.0173Z"
                    fill="url(#paint4_linear_100_3429)"
                  />
                </g>
                <path
                  d="M73.7322 18.9567C73.647 18.0973 73.2813 17.4297 72.6349 16.9538C71.9886 16.478 71.1115 16.2401 70.0036 16.2401C69.2507 16.2401 68.6151 16.3466 68.0966 16.5597C67.5781 16.7656 67.1804 17.0533 66.9034 17.4226C66.6335 17.7919 66.4986 18.2109 66.4986 18.6797C66.4844 19.0703 66.5661 19.4112 66.7436 19.7024C66.9283 19.9936 67.1804 20.2457 67.5 20.4588C67.8196 20.6648 68.1889 20.8459 68.608 21.0021C69.027 21.1513 69.4744 21.2791 69.9503 21.3857L71.9105 21.8544C72.8622 22.0675 73.7358 22.3516 74.5312 22.7067C75.3267 23.0618 76.0156 23.4986 76.598 24.017C77.1804 24.5355 77.6314 25.1463 77.951 25.8494C78.2777 26.5526 78.4446 27.3587 78.4517 28.2678C78.4446 29.603 78.1037 30.7607 77.429 31.7408C76.7614 32.7138 75.7955 33.4702 74.5312 34.0099C73.2741 34.5426 71.7578 34.8089 69.9822 34.8089C68.2209 34.8089 66.6868 34.5391 65.38 33.9993C64.0803 33.4595 63.0646 32.6605 62.3331 31.6023C61.6087 30.5369 61.2287 29.2195 61.1932 27.6499H65.657C65.7067 28.3814 65.9162 28.9922 66.2855 29.4822C66.6619 29.9652 67.1626 30.331 67.7876 30.5795C68.4197 30.821 69.1335 30.9418 69.929 30.9418C70.7102 30.9418 71.3885 30.8281 71.9638 30.6009C72.5462 30.3736 72.9972 30.0575 73.3168 29.6527C73.6364 29.2479 73.7962 28.7827 73.7962 28.2571C73.7962 27.767 73.6506 27.3551 73.3594 27.0213C73.0753 26.6875 72.6563 26.4034 72.1023 26.169C71.5554 25.9347 70.8842 25.7216 70.0888 25.5298L67.7131 24.9332C65.8736 24.4858 64.4212 23.7862 63.3558 22.8345C62.2905 21.8828 61.7614 20.6009 61.7685 18.9886C61.7614 17.6676 62.1129 16.5135 62.8232 15.5263C63.5405 14.5391 64.5241 13.7685 65.7741 13.2145C67.0241 12.6605 68.4446 12.3835 70.0355 12.3835C71.6548 12.3835 73.0682 12.6605 74.2756 13.2145C75.4901 13.7685 76.4347 14.5391 77.1094 15.5263C77.7841 16.5135 78.1321 17.657 78.1534 18.9567H73.7322ZM81.4693 34.5V18.1364H85.7946V21.0234H85.9863C86.3272 20.0646 86.8954 19.3082 87.6909 18.7543C88.4863 18.2003 89.438 17.9233 90.546 17.9233C91.6681 17.9233 92.6234 18.2038 93.4118 18.7649C94.2001 19.3189 94.7257 20.0717 94.9885 21.0234H95.1589C95.4927 20.0859 96.0964 19.3366 96.97 18.7756C97.8507 18.2074 98.8912 17.9233 100.091 17.9233C101.618 17.9233 102.858 18.4098 103.809 19.3828C104.768 20.3487 105.248 21.7195 105.248 23.495V34.5H100.72V24.3899C100.72 23.4808 100.479 22.799 99.9956 22.3445C99.5126 21.8899 98.9089 21.6626 98.1845 21.6626C97.3606 21.6626 96.7179 21.9254 96.2562 22.451C95.7946 22.9695 95.5637 23.6548 95.5637 24.5071V34.5H91.1639V24.294C91.1639 23.4915 90.9331 22.8523 90.4714 22.3764C90.0169 21.9006 89.4167 21.6626 88.671 21.6626C88.1667 21.6626 87.7122 21.7905 87.3074 22.0462C86.9096 22.2947 86.5936 22.6463 86.3592 23.1009C86.1248 23.5483 86.0076 24.0739 86.0076 24.6776V34.5H81.4693ZM113.499 34.8089C112.455 34.8089 111.524 34.6278 110.708 34.2656C109.891 33.8963 109.244 33.353 108.769 32.6357C108.3 31.9112 108.066 31.0092 108.066 29.9297C108.066 29.0206 108.232 28.2571 108.566 27.6392C108.9 27.0213 109.355 26.5241 109.93 26.1477C110.505 25.7713 111.159 25.4872 111.89 25.2955C112.629 25.1037 113.403 24.9688 114.213 24.8906C115.164 24.7912 115.931 24.6989 116.514 24.6136C117.096 24.5213 117.519 24.3864 117.781 24.2088C118.044 24.0312 118.176 23.7685 118.176 23.4205V23.3565C118.176 22.6818 117.963 22.1598 117.536 21.7905C117.117 21.4212 116.521 21.2365 115.747 21.2365C114.93 21.2365 114.28 21.4176 113.797 21.7798C113.314 22.1349 112.994 22.5824 112.838 23.1222L108.641 22.7812C108.854 21.7869 109.273 20.9276 109.898 20.2031C110.523 19.4716 111.329 18.9105 112.316 18.5199C113.311 18.1222 114.461 17.9233 115.768 17.9233C116.677 17.9233 117.547 18.0298 118.378 18.2429C119.216 18.456 119.958 18.7862 120.605 19.2337C121.258 19.6811 121.773 20.2564 122.149 20.9595C122.526 21.6555 122.714 22.4901 122.714 23.4631V34.5H118.41V32.2308H118.282C118.019 32.7422 117.668 33.1932 117.227 33.5838C116.787 33.9673 116.258 34.2692 115.64 34.4893C115.022 34.7024 114.308 34.8089 113.499 34.8089ZM114.798 31.6768C115.466 31.6768 116.056 31.5455 116.567 31.2827C117.078 31.0128 117.48 30.6506 117.771 30.196C118.062 29.7415 118.208 29.2266 118.208 28.6513V26.9148C118.066 27.0071 117.87 27.0923 117.622 27.1705C117.38 27.2415 117.107 27.3089 116.801 27.3729C116.496 27.4297 116.191 27.483 115.885 27.5327C115.58 27.5753 115.303 27.6143 115.054 27.6499C114.521 27.728 114.056 27.8523 113.659 28.0227C113.261 28.1932 112.952 28.424 112.732 28.7152C112.512 28.9993 112.401 29.3544 112.401 29.7805C112.401 30.3984 112.625 30.8707 113.073 31.1974C113.527 31.517 114.102 31.6768 114.798 31.6768ZM126.235 34.5V18.1364H130.635V20.9915H130.805C131.104 19.9759 131.604 19.2088 132.307 18.6903C133.01 18.1648 133.82 17.902 134.736 17.902C134.964 17.902 135.209 17.9162 135.471 17.9446C135.734 17.973 135.965 18.0121 136.164 18.0618V22.0888C135.951 22.0249 135.656 21.968 135.28 21.9183C134.903 21.8686 134.559 21.8438 134.246 21.8438C133.579 21.8438 132.982 21.9893 132.456 22.2805C131.938 22.5646 131.526 22.9624 131.221 23.4737C130.922 23.9851 130.773 24.5746 130.773 25.2422V34.5H126.235ZM148.034 18.1364V21.5455H138.18V18.1364H148.034ZM140.417 14.2159H144.956V29.4716C144.956 29.8906 145.02 30.2173 145.147 30.4517C145.275 30.679 145.453 30.8388 145.68 30.9311C145.914 31.0234 146.184 31.0696 146.49 31.0696C146.703 31.0696 146.916 31.0518 147.129 31.0163C147.342 30.9737 147.505 30.9418 147.619 30.9205L148.333 34.2976C148.105 34.3686 147.786 34.4503 147.374 34.5426C146.962 34.642 146.461 34.7024 145.872 34.7237C144.778 34.7663 143.819 34.6207 142.995 34.2869C142.179 33.9531 141.543 33.4347 141.088 32.7315C140.634 32.0284 140.41 31.1406 140.417 30.0682V14.2159ZM161.78 34.5H156.837L164.369 12.6818H170.313L177.835 34.5H172.892L167.426 17.6676H167.256L161.78 34.5ZM161.471 25.924H173.147V29.5249H161.471V25.924ZM185.644 34.7663C184.401 34.7663 183.275 34.4467 182.267 33.8075C181.265 33.1612 180.47 32.2131 179.88 30.9631C179.298 29.706 179.007 28.1648 179.007 26.3395C179.007 24.4645 179.308 22.9055 179.912 21.6626C180.516 20.4126 181.318 19.4787 182.32 18.8608C183.328 18.2358 184.433 17.9233 185.633 17.9233C186.549 17.9233 187.313 18.0795 187.923 18.392C188.541 18.6974 189.039 19.081 189.415 19.5426C189.798 19.9972 190.09 20.4446 190.289 20.8849H190.427V12.6818H194.955V34.5H190.48V31.8793H190.289C190.075 32.3338 189.774 32.7848 189.383 33.2322C188.999 33.6726 188.499 34.0384 187.881 34.3295C187.27 34.6207 186.524 34.7663 185.644 34.7663ZM187.082 31.1548C187.813 31.1548 188.431 30.956 188.936 30.5582C189.447 30.1534 189.838 29.5888 190.107 28.8643C190.384 28.1399 190.523 27.2912 190.523 26.3182C190.523 25.3452 190.388 24.5 190.118 23.7827C189.848 23.0653 189.458 22.5114 188.946 22.1207C188.435 21.7301 187.813 21.5348 187.082 21.5348C186.336 21.5348 185.708 21.7372 185.196 22.142C184.685 22.5469 184.298 23.108 184.035 23.8253C183.772 24.5426 183.641 25.3736 183.641 26.3182C183.641 27.2699 183.772 28.1115 184.035 28.843C184.305 29.5675 184.692 30.1357 185.196 30.5476C185.708 30.9524 186.336 31.1548 187.082 31.1548ZM212.301 22.8026L208.146 23.0582C208.075 22.7031 207.923 22.3835 207.688 22.0994C207.454 21.8082 207.145 21.5774 206.761 21.407C206.385 21.2294 205.934 21.1406 205.408 21.1406C204.705 21.1406 204.112 21.2898 203.629 21.5881C203.146 21.8793 202.905 22.2699 202.905 22.7599C202.905 23.1506 203.061 23.4808 203.374 23.7507C203.686 24.0206 204.222 24.2372 204.982 24.4006L207.944 24.9972C209.535 25.3239 210.721 25.8494 211.502 26.5739C212.283 27.2983 212.674 28.25 212.674 29.429C212.674 30.5014 212.358 31.4425 211.726 32.2521C211.101 33.0618 210.241 33.6939 209.148 34.1484C208.061 34.5959 206.808 34.8196 205.387 34.8196C203.221 34.8196 201.495 34.3686 200.21 33.4666C198.931 32.5575 198.182 31.3217 197.962 29.7592L202.425 29.5249C202.56 30.1854 202.887 30.6896 203.406 31.0376C203.924 31.3786 204.588 31.549 205.398 31.549C206.193 31.549 206.832 31.3963 207.315 31.0909C207.805 30.7784 208.054 30.3771 208.061 29.8871C208.054 29.4751 207.88 29.1378 207.539 28.875C207.198 28.6051 206.673 28.3991 205.962 28.2571L203.129 27.6925C201.531 27.3729 200.341 26.8189 199.56 26.0305C198.786 25.2422 198.398 24.2372 198.398 23.0156C198.398 21.9645 198.683 21.0589 199.251 20.299C199.826 19.5391 200.632 18.9531 201.669 18.5412C202.713 18.1293 203.935 17.9233 205.334 17.9233C207.401 17.9233 209.027 18.3601 210.213 19.2337C211.406 20.1072 212.102 21.2969 212.301 22.8026Z"
                  fill="white"
                />
                <defs>
                  <linearGradient
                    id="paint0_linear_100_3429"
                    x1="36.9465"
                    y1="43.7536"
                    x2="15.5413"
                    y2="6.67874"
                    gradientUnits="userSpaceOnUse"
                  >
                    <stop stop-color="#144CA2" />
                    <stop offset="1" stop-color="#0E5ED9" />
                  </linearGradient>
                  <linearGradient
                    id="paint1_linear_100_3429"
                    x1="2.66657"
                    y1="32.9162"
                    x2="31.4374"
                    y2="32.9162"
                    gradientUnits="userSpaceOnUse"
                  >
                    <stop stop-color="#FFCB05" />
                    <stop offset="1" stop-color="#F37021" />
                  </linearGradient>
                  <linearGradient
                    id="paint2_linear_100_3429"
                    x1="3.00735"
                    y1="36.9424"
                    x2="17.456"
                    y2="36.9424"
                    gradientUnits="userSpaceOnUse"
                  >
                    <stop stop-color="#FFD400" />
                    <stop offset="1" stop-color="#FFD400" stop-opacity="0" />
                  </linearGradient>
                  <linearGradient
                    id="paint3_linear_100_3429"
                    x1="26.2533"
                    y1="39.1988"
                    x2="26.2533"
                    y2="14.8578"
                    gradientUnits="userSpaceOnUse"
                  >
                    <stop stop-color="#5284CF" />
                    <stop offset="1" stop-color="#0F8FCF" stop-opacity="0" />
                  </linearGradient>
                  <linearGradient
                    id="paint4_linear_100_3429"
                    x1="24.75"
                    y1="9.75"
                    x2="36.75"
                    y2="2.25"
                    gradientUnits="userSpaceOnUse"
                  >
                    <stop stop-color="white" />
                    <stop offset="1" stop-color="#1A56DB" />
                  </linearGradient>
                  <clipPath id="clip0_100_3429">
                    <rect width="48" height="48" fill="white" />
                  </clipPath>
                </defs>
              </svg>
            </div>
            <p
              class="welcome text-white font-semibold text-lg leading-[26px] mb-3"
            >
              Welcome Back!
            </p>
            <div class="font-semibold text-2xl leading-8 text-white mb-3">
              Ready to Boost Your Campaigns?
            </div>
            <div class="greating leading-6 text-white mb-10">
              Log in to access your dashboard, track performance, and optimize
              your ads for maximum impact.
            </div>
            <div class="feature">
              <ul class="flex flex-col gap-4">
                <li class="flex items-center gap-3">
                  <span class="icon">
                    <svg
                      width="32"
                      height="32"
                      viewBox="0 0 32 32"
                      fill="none"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path
                        d="M16 0C15.4823 0 15.0625 0.41975 15.0625 0.9375V4.6875C15.0625 5.20525 15.4823 5.625 16 5.625C21.7207 5.625 26.375 10.2792 26.375 16C26.375 21.7208 21.7207 26.375 16 26.375C10.2793 26.375 5.625 21.7208 5.625 16C5.625 15.4823 5.20525 15.0625 4.6875 15.0625H0.9375C0.41975 15.0625 0 15.4823 0 16C0 24.8094 7.19006 32 16 32C24.8094 32 32 24.81 32 16C32 7.19056 24.8099 0 16 0Z"
                        fill="url(#paint0_linear_57_1228)"
                      />
                      <path
                        d="M16 7.5C15.4823 7.5 15.0625 7.91975 15.0625 8.4375V12.1875C15.0625 12.7052 15.4823 13.125 16 13.125C17.5584 13.125 18.875 14.4416 18.875 16C18.875 17.5584 17.5584 18.875 16 18.875C14.4416 18.875 13.125 17.5584 13.125 16C13.125 15.4823 12.7052 15.0625 12.1875 15.0625H8.4375C7.91975 15.0625 7.5 15.4823 7.5 16C7.5 20.6664 11.3516 24.5 16 24.5C20.6664 24.5 24.5 20.6484 24.5 16C24.5 11.3336 20.6484 7.5 16 7.5ZM4.6875 5.625H12.25C12.7677 5.625 13.1875 5.20525 13.1875 4.6875C13.1875 4.16975 12.7677 3.75 12.25 3.75H4.6875C4.16975 3.75 3.75 4.16975 3.75 4.6875C3.75 5.20525 4.16975 5.625 4.6875 5.625ZM0.9375 5.625C1.45525 5.625 1.875 5.20525 1.875 4.6875C1.875 4.16975 1.45525 3.75 0.9375 3.75C0.41975 3.75 0 4.16975 0 4.6875C0 5.20525 0.41975 5.625 0.9375 5.625ZM13.1875 8.4375C13.1875 7.91975 12.7677 7.5 12.25 7.5H0.9375C0.41975 7.5 0 7.91975 0 8.4375C0 8.95525 0.41975 9.375 0.9375 9.375H12.25C12.7677 9.375 13.1875 8.95525 13.1875 8.4375Z"
                        fill="url(#paint1_linear_57_1228)"
                      />
                      <defs>
                        <linearGradient
                          id="paint0_linear_57_1228"
                          x1="16"
                          y1="32"
                          x2="16"
                          y2="0"
                          gradientUnits="userSpaceOnUse"
                        >
                          <stop stop-color="#5558FF" />
                          <stop offset="1" stop-color="#00C0FF" />
                        </linearGradient>
                        <linearGradient
                          id="paint1_linear_57_1228"
                          x1="12.25"
                          y1="24.5"
                          x2="12.25"
                          y2="3.75"
                          gradientUnits="userSpaceOnUse"
                        >
                          <stop stop-color="#ADDCFF" />
                          <stop offset="0.5028" stop-color="#EAF6FF" />
                          <stop offset="1" stop-color="#EAF6FF" />
                        </linearGradient>
                      </defs>
                    </svg>
                  </span>
                  <span class="text font-semibold text-lg leading-6 text-white"
                    >Advanced Analytics</span
                  >
                </li>
                <li class="flex items-center gap-3">
                  <span class="icon">
                    <svg
                      width="32"
                      height="32"
                      viewBox="0 0 32 32"
                      fill="none"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path
                        d="M16 0C15.4823 0 15.0625 0.41975 15.0625 0.9375V4.6875C15.0625 5.20525 15.4823 5.625 16 5.625C21.7207 5.625 26.375 10.2792 26.375 16C26.375 21.7208 21.7207 26.375 16 26.375C10.2793 26.375 5.625 21.7208 5.625 16C5.625 15.4823 5.20525 15.0625 4.6875 15.0625H0.9375C0.41975 15.0625 0 15.4823 0 16C0 24.8094 7.19006 32 16 32C24.8094 32 32 24.81 32 16C32 7.19056 24.8099 0 16 0Z"
                        fill="url(#paint0_linear_57_1228)"
                      />
                      <path
                        d="M16 7.5C15.4823 7.5 15.0625 7.91975 15.0625 8.4375V12.1875C15.0625 12.7052 15.4823 13.125 16 13.125C17.5584 13.125 18.875 14.4416 18.875 16C18.875 17.5584 17.5584 18.875 16 18.875C14.4416 18.875 13.125 17.5584 13.125 16C13.125 15.4823 12.7052 15.0625 12.1875 15.0625H8.4375C7.91975 15.0625 7.5 15.4823 7.5 16C7.5 20.6664 11.3516 24.5 16 24.5C20.6664 24.5 24.5 20.6484 24.5 16C24.5 11.3336 20.6484 7.5 16 7.5ZM4.6875 5.625H12.25C12.7677 5.625 13.1875 5.20525 13.1875 4.6875C13.1875 4.16975 12.7677 3.75 12.25 3.75H4.6875C4.16975 3.75 3.75 4.16975 3.75 4.6875C3.75 5.20525 4.16975 5.625 4.6875 5.625ZM0.9375 5.625C1.45525 5.625 1.875 5.20525 1.875 4.6875C1.875 4.16975 1.45525 3.75 0.9375 3.75C0.41975 3.75 0 4.16975 0 4.6875C0 5.20525 0.41975 5.625 0.9375 5.625ZM13.1875 8.4375C13.1875 7.91975 12.7677 7.5 12.25 7.5H0.9375C0.41975 7.5 0 7.91975 0 8.4375C0 8.95525 0.41975 9.375 0.9375 9.375H12.25C12.7677 9.375 13.1875 8.95525 13.1875 8.4375Z"
                        fill="url(#paint1_linear_57_1228)"
                      />
                      <defs>
                        <linearGradient
                          id="paint0_linear_57_1228"
                          x1="16"
                          y1="32"
                          x2="16"
                          y2="0"
                          gradientUnits="userSpaceOnUse"
                        >
                          <stop stop-color="#5558FF" />
                          <stop offset="1" stop-color="#00C0FF" />
                        </linearGradient>
                        <linearGradient
                          id="paint1_linear_57_1228"
                          x1="12.25"
                          y1="24.5"
                          x2="12.25"
                          y2="3.75"
                          gradientUnits="userSpaceOnUse"
                        >
                          <stop stop-color="#ADDCFF" />
                          <stop offset="0.5028" stop-color="#EAF6FF" />
                          <stop offset="1" stop-color="#EAF6FF" />
                        </linearGradient>
                      </defs>
                    </svg>
                  </span>
                  <span class="text font-semibold text-lg leading-6 text-white"
                    >Optimize Your Campaign</span
                  >
                </li>
                <li class="flex items-center gap-3">
                  <span class="icon">
                    <svg
                      width="32"
                      height="32"
                      viewBox="0 0 32 32"
                      fill="none"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path
                        d="M16 0C15.4823 0 15.0625 0.41975 15.0625 0.9375V4.6875C15.0625 5.20525 15.4823 5.625 16 5.625C21.7207 5.625 26.375 10.2792 26.375 16C26.375 21.7208 21.7207 26.375 16 26.375C10.2793 26.375 5.625 21.7208 5.625 16C5.625 15.4823 5.20525 15.0625 4.6875 15.0625H0.9375C0.41975 15.0625 0 15.4823 0 16C0 24.8094 7.19006 32 16 32C24.8094 32 32 24.81 32 16C32 7.19056 24.8099 0 16 0Z"
                        fill="url(#paint0_linear_57_1228)"
                      />
                      <path
                        d="M16 7.5C15.4823 7.5 15.0625 7.91975 15.0625 8.4375V12.1875C15.0625 12.7052 15.4823 13.125 16 13.125C17.5584 13.125 18.875 14.4416 18.875 16C18.875 17.5584 17.5584 18.875 16 18.875C14.4416 18.875 13.125 17.5584 13.125 16C13.125 15.4823 12.7052 15.0625 12.1875 15.0625H8.4375C7.91975 15.0625 7.5 15.4823 7.5 16C7.5 20.6664 11.3516 24.5 16 24.5C20.6664 24.5 24.5 20.6484 24.5 16C24.5 11.3336 20.6484 7.5 16 7.5ZM4.6875 5.625H12.25C12.7677 5.625 13.1875 5.20525 13.1875 4.6875C13.1875 4.16975 12.7677 3.75 12.25 3.75H4.6875C4.16975 3.75 3.75 4.16975 3.75 4.6875C3.75 5.20525 4.16975 5.625 4.6875 5.625ZM0.9375 5.625C1.45525 5.625 1.875 5.20525 1.875 4.6875C1.875 4.16975 1.45525 3.75 0.9375 3.75C0.41975 3.75 0 4.16975 0 4.6875C0 5.20525 0.41975 5.625 0.9375 5.625ZM13.1875 8.4375C13.1875 7.91975 12.7677 7.5 12.25 7.5H0.9375C0.41975 7.5 0 7.91975 0 8.4375C0 8.95525 0.41975 9.375 0.9375 9.375H12.25C12.7677 9.375 13.1875 8.95525 13.1875 8.4375Z"
                        fill="url(#paint1_linear_57_1228)"
                      />
                      <defs>
                        <linearGradient
                          id="paint0_linear_57_1228"
                          x1="16"
                          y1="32"
                          x2="16"
                          y2="0"
                          gradientUnits="userSpaceOnUse"
                        >
                          <stop stop-color="#5558FF" />
                          <stop offset="1" stop-color="#00C0FF" />
                        </linearGradient>
                        <linearGradient
                          id="paint1_linear_57_1228"
                          x1="12.25"
                          y1="24.5"
                          x2="12.25"
                          y2="3.75"
                          gradientUnits="userSpaceOnUse"
                        >
                          <stop stop-color="#ADDCFF" />
                          <stop offset="0.5028" stop-color="#EAF6FF" />
                          <stop offset="1" stop-color="#EAF6FF" />
                        </linearGradient>
                      </defs>
                    </svg>
                  </span>
                  <span class="text font-semibold text-lg leading-6 text-white"
                    >Refine Your Targeting Strategy</span
                  >
                </li>
              </ul>
            </div>
            <div class="image-intro">
              <img class="w-full" src="./image/bg-intro.png" alt="" />
            </div>
        </div>
        </div>
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
                action="<?= admin_url('auth/forgot_password') ?>"
              >

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
                <!-- check validate email exsit reset success redirect qua page reset passsword <?= admin_url('auth/reset_password') ?> -->
                <button type="submit" class="btn btn-primary w-full" disabled>
                  <?= Flang::_e('submit_link') ?>
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
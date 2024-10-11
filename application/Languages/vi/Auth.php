<?php
return [
    //common
    'csrf_failed'   =>  'Yêu cầu CSRF đã hết hạn hoặc không hợp lệ do yêu cầu URL kép!',
    
    //auth
    'login'    =>  'Đăng nhập',
    'register' =>  'Đăng ký',
    'forgot_pass'   =>  'Quên mật khẩu',
    'sign_up' => 'Đăng ký',
    'username'  =>  'Tên đăng nhập',
    'fullname'  =>  'Họ và tên',
    'password'  =>  'Mật khẩu',
    'email'  =>  'Email',
    'phone'  =>  'Số điện thoại',
    'active_send_email' => 'Mã kích hoạt mới đã được gửi tới email của bạn.'
    'active_email_success' => 'Tài khoản của bạn đã được kích hoạt thành công.'
    'placeholder_password' => 'Nhập mật khẩu của bạn',
    'login_google' => 'Đăng nhập bằng Google',
    'acccount_does_exist' => 'Tài khoản không tồn tại.',
    'acccount_active' => 'Tài khoản đã được kích hoạt.',
    'token_out_time' => 'Mã kích hoạt đã hết hạn.',
    'token_invalid' => 'Mã kích hoạt không hợp lệ',
    'placeholder_username' => 'Nhập tên đăng nhập của bạn',
    'placeholder_fullname' => 'Nhập họ và tên của bạn',
    'placeholder_email' => 'Nhập email của bạn',
    'placeholder_phone' => 'Nhập số điện thoại của bạn',
    
    //login
    'login_welcome' =>  'Chào mừng đến trang đăng nhập',
    'login_failed'  =>  '%1%: Tên đăng nhập hoặc mật khẩu không đúng!',
    'login_title' => 'Đăng nhập bằng tên người dùng hoặc email',
    'placeholder_login' => 'Nhập tên đăng nhập hoặc email của bạn',
    'remember_me' => 'Ghi nhớ đăng nhập',
    'forgot_password' => 'Quên mật khẩu?',
    'dont_have_account' => 'Chưa có tài khoản?',
    
    //register translate
    'register_welcome'  =>  'Chào mừng đến trang đăng ký',
    'register_success' => 'Đăng ký thành công. Vui lòng kiểm tra email để kích hoạt tài khoản của bạn.',
    'register_error' => 'Đăng ký thất bại. Vui lòng thử lại.',
    'placeholder_password_repeat' => 'Nhập lại mật khẩu của bạn',
    'social_media' => 'Liên hệ mạng xã hội của bạn để hỗ trợ',
    'placeholder_telegram' => 'Nhập Telegram của bạn',
    'placeholder_skype' => 'Nhập Skype của bạn',
    'placeholder_whatsapp' => 'Nhập WhatsApp của bạn',
    'have_account' => 'Bạn đã có tài khoản?',
    
    //forgot password
    'forgotpassw_welcome' => 'Chào mừng đến trang quên mật khẩu',
    'token_fotgot_invalid' => 'Mã kích hoạt không hợp lệ, vui lòng nhập lại email để đặt lại mật khẩu',
    'token_fotgot_out_time' => 'Liên kết đã hết hạn, vui lòng nhập lại email để đặt lại mật khẩu',
    'forgot_password_title' => 'Quên mật khẩu?',
    'submit_link' => 'Gửi liên kết khôi phục',
    'link_reset_password' => 'Liên kết đặt lại mật khẩu đã được gửi tới email của bạn',
    'update_password_welcome' => 'Chào mừng đến trang cập nhật mật khẩu',
    
    // update password
    'reset_password_title' => 'Đặt lại mật khẩu',
    'new_password' => 'Mật khẩu mới',
    'change_password' => 'Thay đổi mật khẩu',
    'reset_password_success' => 'Đổi mật khẩu thành công',
    'login_now' => 'Đăng nhập ngay',
    
    //users translate
    'users_noactive'  =>  '%1%: Tài khoản của bạn chưa được kích hoạt! Vui lòng kiểm tra Hộp thư đến hoặc Spam để kích hoạt tài khoản này!',
    
    //validate
    'username_double'  =>  '%1%: Tên đăng nhập đã tồn tại trong hệ thống.',
    'username_invalid'  =>  'Tên đăng nhập phải là chữ cái, số, không bao gồm ký tự đặc biệt.',
    'username_length'   =>  'Độ dài tên đăng nhập yêu cầu từ %1% đến %2% ký tự.',
    'fullname_invalid'  =>  'Tên đăng nhập phải là chữ cái, số, không bao gồm ký tự đặc biệt.',
    'fullname_length'   =>  'Độ dài tên đăng nhập yêu cầu từ %1% đến %2% ký tự.',
    'email_double'  =>  '%1%: Email đã tồn tại trong hệ thống.',
    'email_exist'   => 'Email %1% không tồn tại trong hệ thống',
    'email_invalid'  =>  'Email phải đúng định dạng email@domain.etc',
    'email_length'    =>  'Độ dài email yêu cầu từ %1% đến %2% ký tự.',
    'password_length'   =>  'Độ dài mật khẩu yêu cầu từ %1% đến %2% ký tự.',
    'password_verify_invalid'   =>  'Xác nhận mật khẩu %1% không khớp với mật khẩu.',
    'fullname_length'   =>  'Độ dài họ và tên yêu cầu từ %1% đến %2% ký tự.',
    'phone_invalid'  =>  'Số điện thoại phải có định dạng 0123456789',
    'phone_length'  =>  'Độ dài số điện thoại yêu cầu là %1% ký tự.',
    'telegram_length'  =>  'Độ dài Telegram yêu cầu từ %1% đến %2% ký tự.',
    'skype_length'  =>  'Độ dài Skype yêu cầu từ %1% đến %2% ký tự.',
    'whatsapp_length'  =>  'Độ dài WhatsApp yêu cầu là %1% ký tự.',
];
∆
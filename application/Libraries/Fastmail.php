<?php

namespace App\Libraries;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use System\Libraries\Render;

class Fastmail
{
    protected $mailer;
    protected $config;
    protected $theme;

    public function __construct($config = [])
    {
        // Lấy cấu hình email từ file config nếu không có cấu hình tùy chỉnh
        $this->config = !empty($config) ? $config : config('email');
        $this->theme = config('theme'); // Lấy theme hiện tại từ cấu hình
        $this->mailer = new PHPMailer(true); // Tạo một instance của PHPMailer
        $this->_setup();
    }

    /**
     * Thiết lập cấu hình cho PHPMailer
     */
    protected function _setup()
    {
        try {
            // Thiết lập cấu hình server SMTP
            $this->mailer->isSMTP();
            $this->mailer->Host = $this->config['mail_host'] ?? '';
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = $this->config['mail_username'] ?? '';
            $this->mailer->Password = $this->config['mail_password'] ?? '';
            $this->mailer->SMTPSecure = $this->config['mail_encryption'] ?? 'tls';
            $this->mailer->Port = $this->config['mail_port'] ?? 587;
            $this->mailer->CharSet  = $this->config['mail_charset'] ?? 'UTF-8';

            // Cấu hình địa chỉ gửi mặc định
            if (!empty($this->config['mail_from_address'])) {
                $this->mailer->setFrom($this->config['mail_from_address'], $this->config['mail_from_name'] ?? '');
            }
        } catch (Exception $e) {
            throw new \System\Core\AppException('Fastmail Setup Error: ' . $e->getMessage());
        }
    }

    /**
     * Gửi email sử dụng template HTML từ views
     *
     * @param string $to Địa chỉ email người nhận
     * @param string $subject Chủ đề email
     * @param string $template Tên file template (không cần phần mở rộng .php)
     * @param array $data Mảng dữ liệu truyền vào template
     * @param array $options Các tùy chọn bổ sung (cc, bcc, attachments, isHtml)
     * @return bool Trả về true nếu gửi thành công, false nếu thất bại
     */
    public function send($to, $subject, $template, $data = [], $options = [])
    {
        try {
            $this->mailer->clearAllRecipients(); // Xóa tất cả người nhận trước khi thêm mới
            $this->mailer->addAddress($to);

            // Thêm CC nếu có
            if (isset($options['cc']) && is_array($options['cc'])) {
                foreach ($options['cc'] as $cc) {
                    $this->mailer->addCC($cc);
                }
            }

            // Thêm BCC nếu có
            if (isset($options['bcc']) && is_array($options['bcc'])) {
                foreach ($options['bcc'] as $bcc) {
                    $this->mailer->addBCC($bcc);
                }
            }

            // Thêm file đính kèm nếu có
            if (isset($options['attachments']) && is_array($options['attachments'])) {
                foreach ($options['attachments'] as $file) {
                    $this->mailer->addAttachment($file);
                }
            }

            // Lấy nội dung HTML từ template
            $body = $this->render($template, $data);

            // Thiết lập chủ đề và nội dung
            $this->mailer->isHTML($options['isHtml'] ?? true);
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $body;

            // Gửi email
            return $this->mailer->send();
        } catch (Exception $e) {
            // Ghi log lỗi nếu cần
            \System\Libraries\Logger::error($e->getMessage(), $e->getFile(), $e->getLine());
            return false;
        }
    }

    /**
     * Render template HTML cho email
     *
     * @param string $template Tên file template (không cần phần mở rộng .php)
     * @param array $data Mảng dữ liệu truyền vào template
     * @return string Nội dung HTML đã render
     */
    protected function render($template, $data = [])
    {
        // Sử dụng Render để lấy nội dung từ template
        return Render::component('common/email/'.$template, $data);
    }
}

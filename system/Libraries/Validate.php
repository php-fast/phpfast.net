<?php
namespace System\Libraries;

use Respect\Validation\Validator as RespectValidator;
use Respect\Validation\Exceptions\ValidationException;
use App\Libraries\Fastlang;

class Validate
{
    protected $errors = [];

    // Các phương thức Validate (hàm ví dụ)
    //alpha: Kiểm tra chuỗi chỉ chứa ký tự chữ cái.
    public static function alpha()
    {
        return RespectValidator::alpha();
    }
    //digit: Kiểm tra chuỗi chỉ chứa các ký tự số.
    public static function digit()
    {
        return RespectValidator::digit();
    }
    //lowercase: Kiểm tra chuỗi chỉ chứa ký tự chữ cái viết thường.
    public static function lowercase()
    {
        return RespectValidator::lowercase();
    }
    //uppercase: Kiểm tra chuỗi chỉ chứa ký tự chữ cái viết hoa.
    public static function uppercase()
    {
        return RespectValidator::uppercase();
    }
    //contains: Kiểm tra xem chuỗi có chứa một giá trị cụ thể hay không.
    public static function contains($value)
    {
        return RespectValidator::contains($value);
    }
    // Alnum validator với các ký tự bổ sung
    public static function alnum(...$additionalChars)
<<<<<<< HEAD
    {
=======
    {   
>>>>>>> d56d56bc250df9011e4c0789f16dacc6aedb2327
        return RespectValidator::alnum(...$additionalChars);
    }
    // Length validator với độ dài tối thiểu và tối đa
    public static function length($min = null, $max = null)
    {
        return RespectValidator::length($min, $max);
    }
    // Email validator
    public static function email()
    {
        return RespectValidator::email();
    }
    // URL validator
    public static function url()
    {
        return RespectValidator::url();
    }
    // Number validator
    public static function numeric()
    {
        return RespectValidator::numeric();
    }
    // Regex validator
    public static function regex($pattern)
    {
        return RespectValidator::regex($pattern);
    }
    // Date validator
    public static function date($format = 'Y-m-d')
    {
        return RespectValidator::date($format);
    }
    // NotEmpty validator
    public static function notEmpty()
    {
        return RespectValidator::notEmpty();
    }
    // Equals validator
    public static function equals($compareTo)
    {
        return RespectValidator::equals($compareTo);
    }
    // Phone number validator
    public static function phone()
    {
        return RespectValidator::phone();
    }
    // IP address validator
    public static function ip()
    {
        return RespectValidator::ip();
    }
    // In array validator
    public static function in($haystack)
    {
        return RespectValidator::in($haystack);
    }
    // Range validator
    public static function between($min, $max)
    {
        return RespectValidator::between($min, $max);
    }
    //startsWith: Kiểm tra xem chuỗi có bắt đầu bằng một giá trị cụ thể hay không.
    public static function startsWith($value)
    {
        return RespectValidator::startsWith($value);
    }
    //endsWith: Kiểm tra xem chuỗi có kết thúc bằng một giá trị cụ thể hay không.
    public static function endsWith($value)
    {
        return RespectValidator::endsWith($value);
    }
    //inArray: Kiểm tra xem giá trị có nằm trong một mảng hay không.
    public static function inArray(array $haystack, $strict = false)
    {
        return RespectValidator::in($haystack, $strict);
    }
    //uuid: Kiểm tra xem chuỗi có phải là UUID hợp lệ hay không.
    public static function uuid()
    {
        return RespectValidator::uuid();
    }
    //creditCard: Kiểm tra xem chuỗi có phải là số thẻ tín dụng hợp lệ không.
    public static function creditCard()
    {
        return RespectValidator::creditCard();
    }
    //not: Đảo ngược kết quả của một validator (sử dụng ! để phủ định).
    public static function not($rule)
    {
        return RespectValidator::not($rule);
    }
    //optional: Cho phép một validator là tùy chọn (sẽ bỏ qua nếu giá trị là null).
    public static function optional($rule)
    {
        return RespectValidator::optional($rule);
    }
    //lengthBetween: Kiểm tra độ dài của chuỗi nằm trong khoảng nhất định.
    public static function lengthBetween($min, $max)
    {
        return RespectValidator::length($min, $max);
    }
    //image: Kiểm tra xem file có phải là ảnh hợp lệ không.
    public static function image()
    {
        return RespectValidator::image();
    }
    //json: Kiểm tra xem chuỗi có phải là JSON hợp lệ không.
    public static function json()
    {
        return RespectValidator::json();
    }
    //domain: Kiểm tra xem chuỗi có phải là tên miền hợp lệ hay không.
    public static function domain()
    {
        return RespectValidator::domain();
    }
    //macAddress: Kiểm tra xem chuỗi có phải là địa chỉ MAC hợp lệ không.
    public static function macAddress()
    {
        return RespectValidator::macAddress();
    }
    //subset: Kiểm tra xem một mảng là tập con của một mảng khác.
    public static function subset(array $superset)
    {
        return RespectValidator::subset($superset);
    }
    //hexRgbColor: Kiểm tra xem chuỗi có phải là mã màu RGB dạng hex không.
    public static function hexRgbColor()
    {
        return RespectValidator::hexRgbColor();
    }

    // Còn nhiều phương thức khác nữa, bạn có thể bổ sung tương tự...

    /**
     * Validate một mảng dữ liệu theo các điều kiện đã khai báo
     *
     * @param array $data Mảng dữ liệu cần validate (['field_name' => 'value'])
     * @param array $rules Mảng các điều kiện validate và thông báo lỗi
     * [
     *      'username' => [
     *          'rules' => [Validate::alnum(), Validate::length(3, 20)],
     *          'messages' => ['Username phải là chuỗi không chứa ký tự đặc biệt.', 'Độ dài phải từ 3 đến 20 ký tự.']
     *      ]
     * ]
     * @return bool True nếu dữ liệu hợp lệ, false nếu có lỗi
     */
    public function check($data, $rules)
    {
        $this->errors = []; // Reset lỗi

        foreach ($rules as $field => $conditions) {
            $value = $data[$field] ?? null;

            if (isset($conditions['rules']) && is_array($conditions['rules'])) {
                foreach ($conditions['rules'] as $index => $rule) {
                    try {
                        // Thực hiện validate
                        $rule->assert($value);
                    } catch (ValidationException $e) {
                        // Ghi lại lỗi nếu có, lưu trữ nhiều lỗi cho một field
                        $this->errors[$field][] = $conditions['messages'][$index] ?? Fastlang::_e('error_default');
                    }
                }
            }
        }

        return empty($this->errors);
    }

    /**
     * Lấy các lỗi sau khi validate
     *
     * @return array Mảng lỗi ['field_name' => ['error_message1', 'error_message2']]
     */
    public function getErrors()
    {
        return $this->errors;
    }
}

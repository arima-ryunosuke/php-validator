<?php
namespace ryunosuke\chmonos\Condition;

use function ryunosuke\chmonos\si_prefix;

/**
 * ファイルサイズバリデータ
 *
 * - maxsize: int
 *   - 許容する最大ファイルサイズ
 */
class FileSize extends AbstractCondition implements Interfaces\InferableType
{
    public const INVALID      = 'FileSizeInvalid';
    public const INVALID_OVER = 'FileSizeInvalidOver';

    protected static $messageTemplates = [
        self::INVALID      => '入力ファイルが不正です',
        self::INVALID_OVER => 'ファイルサイズが大きすぎます。%message%以下のファイルを選択してください',
    ];

    protected $_maxsize;
    protected $_message;

    public function __construct($maxsize)
    {
        $this->_maxsize = $maxsize;
        $this->_message = si_prefix($this->_maxsize, 1024, function ($var, $unit) {
            return number_format($var) . strtoupper($unit) . 'B';
        });

        parent::__construct();
    }

    public static function validate($value, $fields, $params, $consts, $error, $context)
    {
        $size = filesize($value);

        if (!$size) {
            $error($consts['INVALID']);
        }

        if ($size > $params['maxsize']) {
            $error($consts['INVALID_OVER']);
        }
    }

    public function getType()
    {
        return 'file';
    }

    public function getFixture($value, $fields)
    {
        $value = tempnam(sys_get_temp_dir(), 'filesize');
        file_put_contents($value, 'X');
        return $value;
    }
}

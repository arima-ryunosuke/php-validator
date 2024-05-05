<?php
namespace ryunosuke\chmonos\Condition;

/**
 * ファイルサイズバリデータ
 *
 * - maxsize: int|string
 *   - 許容する最大ファイルサイズ
 *   - 2M のような表記も使える
 */
class FileSize extends AbstractCondition implements Interfaces\InferableType
{
    public const INVALID      = 'FileSizeInvalid';
    public const INVALID_OVER = 'FileSizeInvalidOver';

    protected static $messageTemplates = [
        self::INVALID      => '入力ファイルが不正です',
        self::INVALID_OVER => '${_maxsize}B以下のファイルを選択してください',
    ];

    protected $_maxsize;

    public function __construct($maxsize)
    {
        $this->_maxsize = $maxsize;

        parent::__construct();
    }

    public static function validate($value, $fields, $params, $consts, $error, $context)
    {
        $size = filesize($value);

        if (!$size) {
            $error($consts['INVALID'], []);
        }

        if ($size > ini_parse_quantity($params['maxsize'])) {
            $error($consts['INVALID_OVER'], []);
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

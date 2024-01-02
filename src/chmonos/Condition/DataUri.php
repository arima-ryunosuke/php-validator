<?php
namespace ryunosuke\chmonos\Condition;

use ryunosuke\chmonos\Condition\Traits\File;
use function ryunosuke\chmonos\dataurl_encode;
use function ryunosuke\chmonos\si_prefix;

/**
 * DataUri バリデータ
 *
 * multipart ではないファイルのアップロードでの使用を想定。
 * デフォルトでは値として得られるのはデコード後の値なので注意。
 *
 * - size: int
 *   - 許容する文字長（デコード後）
 * - type: array
 *   - 許容する拡張子（minetype 逆引き）
 * - minetype: array
 *   - mimetype と拡張子の対応表
 */
class DataUri extends AbstractCondition implements Interfaces\ConvertibleValue
{
    use File;

    public const INVALID      = 'dataUriInvalid';
    public const INVALID_SIZE = 'dataUriInvalidSize';
    public const INVALID_TYPE = 'dataUriInvalidType';

    protected static $messageTemplates = [
        self::INVALID      => 'Invalid value given',
        self::INVALID_SIZE => '%size_message%以下で入力してください',
        self::INVALID_TYPE => '%type_message%形式で入力してください',
    ];

    private $convertible;

    protected $_size;
    protected $_size_message;

    protected $_type;
    protected $_type_message;

    protected $_allowTypes;

    public function __construct(array $rule = ['size' => null, 'type' => [], 'mimetype' => []], $convertible = true)
    {
        $this->convertible = $convertible;

        $this->_size = $rule['size'] ?? null;
        $this->_size_message = si_prefix($this->_size, 1024, fn($var, $unit) => number_format($var) . strtoupper($unit) . 'B');

        $this->_type = $rule['type'] ?? [];
        $this->_type_message = implode(', ', $this->_type);

        $this->_allowTypes = $this->getMimeTypes($this->_type, $rule['mimetype'] ?? []);

        parent::__construct();
    }

    public static function validate($value, $fields, $params, $consts, $error, $context)
    {
        $matches = [];

        if (!preg_match('#^data:(.+?/.+?)?(;charset=.+?)?(;base64)?,#iu', $value, $matches)) {
            return $error($consts['INVALID']);
        }

        $decoded = base64_decode(substr($value, strlen($matches[0])), true);

        if ($decoded === false) {
            return $error($consts['INVALID']);
        }

        if ($params['size'] && $params['size'] < strlen($decoded)) {
            $error($consts['INVALID_SIZE']);
        }

        if ($params['type'] && !in_array($matches[1], $params['allowTypes'], true)) {
            $error($consts['INVALID_TYPE']);
        }
    }

    public function getValue($value)
    {
        if ($this->convertible) {
            return base64_decode(preg_replace('#^data:(.+?/.+?)?(;charset=.+?)?(;base64)?,#iu', '', $value), true);
        }
        return $value;
    }

    public function getFixture($value, $fields)
    {
        $data = str_pad($value ?? '', $this->_size, 'X', STR_PAD_RIGHT);
        $type = $this->fixtureArray($this->_allowTypes);
        return dataurl_encode($data, ['mimetype' => $type]);
    }
}

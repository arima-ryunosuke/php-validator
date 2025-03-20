<?php
namespace ryunosuke\chmonos\Condition;

use ryunosuke\chmonos\Condition\Traits\File;
use function ryunosuke\chmonos\array_flatten;
use function ryunosuke\chmonos\dataurl_encode;

/**
 * DataUri バリデータ
 *
 * multipart ではないファイルのアップロードでの使用を想定。
 * デフォルトでは値として得られるのはデコード後の値なので注意。
 *
 * - size: int|string
 *   - 許容する文字長（デコード後）
 *   - 2M のような表記も使える
 * - type: array
 *   - 許容する拡張子（minetype 逆引き）
 * - minetype: array
 *   - mimetype と拡張子の対応表
 *
 * 上記は過去の仕様で、現在は名前付き引数で下記（上記も受け入れられる）。
 *
 * - size: int|string
 *   - 上記と同じ
 * - type: array
 *   - [拡張子 => mimetype] の配列
 */
class DataUri extends AbstractCondition implements Interfaces\ConvertibleValue
{
    use File;

    public const INVALID      = 'dataUriInvalid';
    public const INVALID_SIZE = 'dataUriInvalidSize';
    public const INVALID_TYPE = 'dataUriInvalidType';

    protected static $messageTemplates = [
        self::INVALID      => 'Invalid value given',
        self::INVALID_SIZE => '${_size}B以下で入力してください',
        self::INVALID_TYPE => '${implode(",", _type)}形式で入力してください',
    ];

    private $convertible;

    protected $_size;

    protected $_type;

    protected $_allowTypes;

    //public function __construct(?string $size = null, array $type = [], bool $convertible = true)
    public function __construct(array $rule = ['size' => null, 'type' => [], 'mimetype' => []], $convertible = true, ...$namedArguments)
    {
        $this->convertible = $convertible;

        if ($namedArguments) {
            $namedArguments += [
                'size' => null,
                'type' => [],
            ];
            $namedArguments['type'] = array_map(fn($type) => (array) $type, $namedArguments['type']);

            $this->_size = $namedArguments['size'];
            $this->_type = array_keys($namedArguments['type']);

            $standard = $this->getMimeTypes($this->_type);
            $userland = array_values(array_flatten($namedArguments['type']));
            $this->_allowTypes = array_values(array_unique(array_merge($standard, $userland)));
        }
        // for compatible
        else {
            $this->_size = $rule['size'] ?? null;
            $this->_type = $rule['type'] ?? [];
            $this->_allowTypes = $this->getMimeTypes($this->_type, $rule['mimetype'] ?? []);
        }

        parent::__construct();
    }

    public static function validate($value, $fields, $params, $consts, $error, $context)
    {
        $matches = [];

        if (!preg_match('#^data:(.+?/.+?)?(;charset=.+?)?(;base64)?,#iu', $value, $matches)) {
            return $error($consts['INVALID'], []);
        }

        $decoded = base64_decode(substr($value, strlen($matches[0])), true);

        if ($decoded === false) {
            return $error($consts['INVALID'], []);
        }

        if ($params['size'] && ini_parse_quantity($params['size']) < strlen($decoded)) {
            $error($consts['INVALID_SIZE'], []);
        }

        if ($params['type'] && !count(array_filter($params['allowTypes'], fn($type) => fnmatch($type, $matches[1])))) {
            $error($consts['INVALID_TYPE'], []);
        }
    }

    public function getValue($value)
    {
        if ($this->convertible) {
            if (!preg_match('#^data:(.+?/.+?)?(;charset=.+?)?(;base64)?,#iu', $value ?? '')) {
                return dataurl_encode($value ?? '');
            }
        }
        return $value;
    }

    public function getAccepts()
    {
        $extensions = [];
        foreach ($this->_allowTypes as $type) {
            $exts = array_map(fn($ext) => ".$ext", self::$mimeTypes[$type] ?? []);
            array_push($extensions, ...$exts);
        }

        return array_values(array_unique(array_merge($extensions, $this->_allowTypes)));
    }

    public function getFixture($value, $fields)
    {
        $size = ini_parse_quantity($this->_size);
        $data = str_pad($value ?? '', $size, 'X', STR_PAD_RIGHT);
        $type = $this->fixtureArray(array_filter($this->_allowTypes, fn($type) => !str_contains($type, '*')));
        return dataurl_encode($data, ['mimetype' => $type]);
    }
}

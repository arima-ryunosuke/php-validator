<?php
namespace ryunosuke\chmonos\Condition;

/**
 * ファイル名・パスバリデータ
 *
 * 原則として半角専用。
 *
 * - extensions: string|array
 *   - 許可する拡張子（null で全許可）
 * - symbols: string
 *   - 許可する記号（null でスタンダードな記号群）
 *   - extensions が指定されている場合、 '.' は必ず許可される
 * - windows: bool
 *   - Windows 特有の使用できないファイル名をエラーにするか（null で自動判断）
 */
class FileName extends AbstractCondition implements Interfaces\ImeMode
{
    public const INVALID                   = 'InvalidFileName';
    public const INVALID_FILENAME_STR      = 'InvalidFileNameStr';
    public const INVALID_FILENAME_EXT      = 'InvalidFileNameExt';
    public const INVALID_FILENAME_RESERVED = 'InvalidFileNameReserved';

    protected static $messageTemplates = [
        self::INVALID                   => 'Invalid value given',
        self::INVALID_FILENAME_STR      => '有効なファイル名を入力してください',
        self::INVALID_FILENAME_EXT      => '%extensions%ファイル名を入力してください',
        self::INVALID_FILENAME_RESERVED => '使用できないファイル名です',
    ];

    // @formatter:off
    private static $reserved = [
        'PRN', 'AUX', 'NUL', 'CON',
        'COM0', 'COM1', 'COM2', 'COM3', 'COM4', 'COM5', 'COM6', 'COM7', 'COM8', 'COM9',
        'LPT0', 'LPT1', 'LPT2', 'LPT3', 'LPT4', 'LPT5', 'LPT6', 'LPT7', 'LPT8', 'LPT9',
    ];
    // @formatter:on

    protected $_regex;
    protected $_reserved;
    protected $_extensions;

    public function __construct($extensions = null, $symbols = null, $windows = null)
    {
        $extensions ??= [];
        $symbols ??= '/!-_.\'()&$@=;+,:\\';
        $windows ??= DIRECTORY_SEPARATOR === '\\';

        $this->_extensions = (array) $extensions;
        $this->_regex = '/^[a-z0-9' . preg_quote($symbols . ($this->_extensions ? '.' : ''), '/') . ']+$/i';
        $this->_reserved = $windows ? self::$reserved : [];

        parent::__construct();
    }

    public static function validate($value, $fields, $params, $consts, $error, $context)
    {
        if (!preg_match($params['regex'], $value)) {
            $error($consts['INVALID_FILENAME_STR']);
            return;
        }

        $pathinfo = pathinfo($value);
        $pathinfo['extension'] = isset($pathinfo['extension']) ? $pathinfo['extension'] : '';
        $pathinfo['filename'] = isset($pathinfo['filename']) ? $pathinfo['filename'] : '';

        if (count($params['extensions']) && !in_array($pathinfo['extension'], $params['extensions'])) {
            $error($consts['INVALID_FILENAME_EXT']);
            return;
        }

        if (count($params['reserved']) && in_array(strtoupper($pathinfo['filename']), $params['reserved'])) {
            $error($consts['INVALID_FILENAME_RESERVED']);
            return;
        }
    }

    public function getImeMode()
    {
        return Interfaces\ImeMode::DISABLED;
    }

    public function getFixture($value, $fields)
    {
        $value = tempnam(sys_get_temp_dir(), 'filename');
        if ($this->_extensions) {
            $value .= '.' . $this->fixtureArray($this->_extensions);
        }
        return $value;
    }
}

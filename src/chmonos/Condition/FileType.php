<?php
namespace ryunosuke\chmonos\Condition;

use ryunosuke\chmonos\Condition\Traits\File;

/**
 * ファイルタイプバリデータ
 *
 * - filetype: array
 *   - 許容するファイル拡張子
 *   - ['表示名' => [拡張子1, 拡張子2]] のような形式で指定する
 *   - 例えば PNG と JPG を許可するなら下記のように指定する
 *     - ['PNG' => 'png', 'JPG' => ['jpg', 'jpeg']]
 *     - ['画像' => ['jpg', 'jpeg', 'png']]
 * - mimetype: array
 *   - 許容する mimetype
 */
class FileType extends AbstractCondition implements Interfaces\InferableType
{
    use File;

    public const INVALID      = 'FileTypeInvalid';
    public const INVALID_TYPE = 'FileTypeInvalidType';

    protected static $messageTemplates = [
        self::INVALID      => '入力ファイルが不正です',
        self::INVALID_TYPE => '${implode(",", array_keys(_allowTypes))}形式のファイルを選択して下さい',
    ];

    protected $_allowTypes;
    protected $_mimeTypes;
    protected $_type;

    public function __construct($filetype, $mimetype = [])
    {
        // 配列に正規化+小文字化
        foreach ($filetype as $name => $option) {
            if (!is_array($option)) {
                $filetype[$name] = [$option];
            }
            foreach ($filetype[$name] as &$ext) {
                $ext = strtolower($ext);
            }
        }

        $this->_allowTypes = $filetype;
        $this->_mimeTypes = $mimetype;

        parent::__construct();
    }

    public static function validate($value, $fields, $params, $consts, $error, $context)
    {
        if ($context['lang'] === 'php') {
            $value = strval($value);
        }

        $mimetype = mime_content_type($value);

        if (!$mimetype && !in_array('*', $params['mimetype'])) {
            $error($consts['INVALID'], []);
        }

        if (!in_array($mimetype, $params['mimetype'])) {
            $error($consts['INVALID_TYPE'], []);
        }
    }

    public function getValidationParam()
    {
        $exts = [...array_merge(...array_values($this->_allowTypes))];
        $mimetype = $this->getMimeTypes($exts, $this->_mimeTypes);

        return ['allowTypes' => $this->_allowTypes, 'mimetype' => $mimetype];
    }

    public function getAccepts()
    {
        $exts = [...array_merge(...array_values($this->_allowTypes))];
        return array_merge(array_map(fn($ext) => ".$ext", $exts), $this->getMimeTypes($exts, $this->_mimeTypes));
    }

    public function getType()
    {
        return 'file';
    }

    public function getFixture($value, $fields)
    {
        // minetype が一致するようなファイルを生成するのは困難（todo /etc/magic あたりで逆引きする？）
        return $value;
    }
}

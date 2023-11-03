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
 *   - '*' は「mime type 不明」を表し、「すべての拡張子」ではない
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
        self::INVALID_TYPE => '%type%形式のファイルを選択して下さい',
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
        $this->_type = implode(', ', array_keys($filetype));

        parent::__construct();
    }

    public static function validate($value, $fields, $params, $consts, $error, $context)
    {
        $mimetype = mime_content_type($value);

        if (!$mimetype && !in_array('*', $params['mimetype'])) {
            $error($consts['INVALID']);
        }

        if (!in_array($mimetype, $params['mimetype'])) {
            $error($consts['INVALID_TYPE']);
        }
    }

    public function getValidationParam()
    {
        $exts = [...array_merge(...array_values($this->_allowTypes))];
        $mimetype = $this->getMimeTypes($exts, $this->_mimeTypes);

        // for compatible
        if (in_array('*', $exts, true)) {
            $mimetype = array_merge($mimetype, ['*', 'application/octet-stream']);
        }
        return ['mimetype' => $mimetype, 'type' => $this->_type];
    }

    public function getAccepts()
    {
        $exts = [...array_merge(...array_values($this->_allowTypes))];
        // for compatible
        $exts = array_filter($exts, fn($ext) => $ext !== '*');
        return array_merge(array_map(fn($ext) => ".$ext", $exts), $this->getMimeTypes($exts, $this->_mimeTypes));
    }

    public function getType()
    {
        return 'file';
    }
}

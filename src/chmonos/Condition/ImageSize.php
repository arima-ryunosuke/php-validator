<?php
namespace ryunosuke\chmonos\Condition;

/**
 * 画像サイズバリデータ
 *
 * - width: int|null
 *   - 横サイズ
 * - height: int|null
 *   - 縦サイズ
 */
class ImageSize extends AbstractCondition implements Interfaces\InferableType
{
    const INVALID        = 'ImageFileInvalid';
    const INVALID_WIDTH  = 'ImageFileInvalidWidth';
    const INVALID_HEIGHT = 'ImageFileInvalidHeight';

    protected static $messageTemplates = [
        self::INVALID        => '画像ファイルを入力してください',
        self::INVALID_WIDTH  => '横サイズは${_width}ピクセル以下で選択してください',
        self::INVALID_HEIGHT => '縦サイズは${_height}ピクセル以下で選択してください',
    ];

    protected $_width;
    protected $_height;

    public function __construct($width = null, $height = null)
    {
        $this->_width = $width;
        $this->_height = $height;

        parent::__construct();
    }

    public static function validate($value, $fields, $params, $consts, $error, $context)
    {
        $size = yield getimagesize($value);

        if ($size === false) {
            $error($consts['INVALID'], []);
            return;
        }

        if (!is_null($params['width']) && $params['width'] < $size[0]) {
            $error($consts['INVALID_WIDTH'], []);
        }

        if (!is_null($params['height']) && $params['height'] < $size[1]) {
            $error($consts['INVALID_HEIGHT'], []);
        }
    }

    public function getType()
    {
        return 'file';
    }

    public function getFixture($value, $fields)
    {
        $value = tempnam(sys_get_temp_dir(), 'imagesize');
        $image = imagecreatetruecolor($this->fixtureInt(1, $this->_width ?? 256), $this->fixtureInt(1, $this->_height ?? 256));
        imagepng($image, $value);
        return $value;
    }
}

<?php
namespace ryunosuke\chmonos\Condition;

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
    public const INVALID      = 'dataUriInvalid';
    public const INVALID_SIZE = 'dataUriInvalidSize';
    public const INVALID_TYPE = 'dataUriInvalidType';

    protected static $messageTemplates = [
        self::INVALID      => 'Invalid value given',
        self::INVALID_SIZE => '%size_message%以下で入力してください',
        self::INVALID_TYPE => '%type_message%形式で入力してください',
    ];

    protected static $mimeTypes = [
        'application/csv'         => ['csv'],
        'application/dicom'       => ['dcm'],
        'application/dvcs'        => ['dvc'],
        'application/fastinfoset' => ['finf'],
        'application/hyperstudio' => ['stk'],
        'application/ipfix'       => ['ipfix'],
        'application/json'        => ['json'],
        'application/marc'        => ['mrc'],
        'application/mathematica' => ['nb', 'ma', 'mb'],
        'application/mbox'        => ['mbox'],
        'application/mp21'        => ['m21', 'mp21'],
        'application/msword'      => ['doc'],
        'application/mxf'         => ['mxf'],
        'application/oda'         => ['oda'],
        'application/ogg'         => ['ogx'],
        'application/pdf'         => ['pdf'],
        'application/pkcs10'      => ['p10'],
        'application/postscript'  => ['ai', 'eps', 'ps'],
        'application/rtf'         => ['rtf'],
        'application/sdp'         => ['sdp'],
        'application/sieve'       => ['siv', 'sieve'],
        'application/smil'        => ['smil', 'smi', 'sml'],
        'application/srgs'        => ['gram'],
        'application/zip'         => ['zip'],
        'audio/32kadpcm'          => ['726'],
        'audio/AMR'               => ['amr'],
        'audio/ATRAC3'            => ['at3', 'aa3', 'omg'],
        'audio/EVRC'              => ['evc'],
        'audio/EVRCB'             => ['evb'],
        'audio/EVRCWB'            => ['evw'],
        'audio/L16'               => ['l16'],
        'audio/SMV'               => ['smv'],
        'audio/ac3'               => ['ac3'],
        'audio/basic'             => ['au', 'snd'],
        'audio/dls'               => ['dls'],
        'audio/iLBC'              => ['lbc'],
        'audio/midi'              => ['mid', 'midi', 'kar'],
        'audio/mpeg'              => ['mpga', 'mp1', 'mp2', 'mp3'],
        'audio/ogg'               => ['oga', 'ogg', 'spx'],
        'audio/qcelp'             => ['qcp'],
        'image/bmp'               => ['bmp'],
        'image/fits'              => ['fits', 'fit', 'fts'],
        'image/gif'               => ['gif'],
        'image/ief'               => ['ief'],
        'image/jp2'               => ['jp2', 'jpg2'],
        'image/jpeg'              => ['jpeg', 'jpg', 'jpe', 'jfif'],
        'image/jpm'               => ['jpm', 'jpgm'],
        'image/jpx'               => ['jpx', 'jpf'],
        'image/png'               => ['png'],
        'image/t38'               => ['t38'],
        'image/tiff'              => ['tiff', 'tif'],
        'message/global'          => ['u8msg'],
        'message/rfc822'          => ['eml', 'mail', 'art'],
        'model/iges'              => ['igs', 'iges'],
        'model/mesh'              => ['msh', 'mesh', 'silo'],
        'model/vrml'              => ['wrl', 'vrml'],
        'text/calendar'           => ['ics', 'ifb'],
        'text/css'                => ['css'],
        'text/csv'                => ['csv'],
        'text/dns'                => ['soa', 'zone'],
        'text/html'               => ['html', 'htm'],
        'text/javascript'         => ['js'],
        'text/plain'              => ['asc', 'txt', 'text', 'pm', 'el', 'c', 'h', 'cc', 'hh', 'cxx', 'hxx', 'f90'],
        'text/richtext'           => ['rtx'],
        'text/sgml'               => ['sgml', 'sgm'],
        'text/xml'                => ['xml'],
        'video/3gpp'              => ['3gp', '3gpp'],
        'video/3gpp2'             => ['3g2', '3gpp2'],
        'video/mj2'               => ['mj2', 'mjp2'],
        'video/mp4'               => ['mp4', 'mpg4'],
        'video/mpeg'              => ['mpeg', 'mpg', 'mpe'],
        'video/ogg'               => ['ogv'],
        'video/quicktime'         => ['qt', 'mov'],
        'video/webm'              => ['webm']
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

        $this->_allowTypes = [];
        foreach (self::$mimeTypes + ($rule['mimetype'] ?? []) as $mimetype => $extensions) {
            foreach ($this->_type as $type) {
                if (in_array(strtolower($type), $extensions, true)) {
                    $this->_allowTypes[] = $mimetype;
                }
            }
        }

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
}

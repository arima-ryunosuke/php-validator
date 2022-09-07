<?php
namespace ryunosuke\chmonos\Condition;

use ryunosuke\chmonos\Condition\Interfaces;

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
    public const INVALID      = 'FileTypeInvalid';
    public const INVALID_TYPE = 'FileTypeInvalidType';

    protected static $messageTemplates = [
        self::INVALID      => '入力ファイルが不正です',
        self::INVALID_TYPE => '%type%形式のファイルを選択して下さい',
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
        $this->_mimeTypes = $mimetype + self::$mimeTypes;
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
        $mimetype = [];
        foreach ($this->_allowTypes as $targets) {
            foreach ($targets as $target) {
                if ($target === '*') {
                    $mimetype[] = '*';
                    $mimetype[] = 'application/octet-stream';
                    continue;
                }

                foreach ($this->_mimeTypes as $mime => $exts) {
                    if (in_array($target, $exts)) {
                        $mimetype[] = $mime;
                    }
                }
            }
        }
        return ['mimetype' => $mimetype, 'type' => $this->_type];
    }

    public function getAccepts()
    {
        $accepts = [
            'ext'  => [],
            'mime' => [],
        ];
        foreach ($this->_allowTypes as $exts) {
            foreach ($exts as $ext) {
                if ($ext === '*') {
                    continue;
                }
                $accepts['ext'][] = '.' . $ext;

                foreach ($this->_mimeTypes as $mime => $mimeexts) {
                    if (in_array($ext, $mimeexts)) {
                        $accepts['mime'][] = $mime;
                    }
                }
            }
        }
        return array_unique(array_merge($accepts['ext'], $accepts['mime']));
    }

    public function getType()
    {
        return 'file';
    }
}

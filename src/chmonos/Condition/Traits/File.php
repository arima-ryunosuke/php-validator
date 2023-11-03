<?php
namespace ryunosuke\chmonos\Condition\Traits;

/**
 * ファイル関係のトレイト
 */
trait File
{
    // to const in future scope
    protected static $mimeTypes = [
        'application/csv'                                                         => ['csv'],
        'application/dicom'                                                       => ['dcm'],
        'application/dvcs'                                                        => ['dvc'],
        'application/fastinfoset'                                                 => ['finf'],
        'application/hyperstudio'                                                 => ['stk'],
        'application/ipfix'                                                       => ['ipfix'],
        'application/json'                                                        => ['json'],
        'application/marc'                                                        => ['mrc'],
        'application/mathematica'                                                 => ['nb', 'ma', 'mb'],
        'application/mbox'                                                        => ['mbox'],
        'application/mp21'                                                        => ['m21', 'mp21'],
        'application/msexcel'                                                     => ['xls'],
        'application/msword'                                                      => ['doc'],
        'application/mxf'                                                         => ['mxf'],
        'application/oda'                                                         => ['oda'],
        'application/ogg'                                                         => ['ogx'],
        'application/pdf'                                                         => ['pdf'],
        'application/pkcs10'                                                      => ['p10'],
        'application/postscript'                                                  => ['ai', 'eps', 'ps'],
        'application/rtf'                                                         => ['rtf'],
        'application/sdp'                                                         => ['sdp'],
        'application/sieve'                                                       => ['siv', 'sieve'],
        'application/smil'                                                        => ['smil', 'smi', 'sml'],
        'application/srgs'                                                        => ['gram'],
        'application/xml'                                                         => ['xml'],
        'application/zip'                                                         => ['zip'],
        'application/x-zip-compressed'                                            => ['zip'],
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'       => ['xlsx'],
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => ['docx'],
        'application/vnd.ms-excel'                                                => ['xls'],
        'application/vnd.ms-word'                                                 => ['doc'],
        'audio/32kadpcm'                                                          => ['726'],
        'audio/AMR'                                                               => ['amr'],
        'audio/ATRAC3'                                                            => ['at3', 'aa3', 'omg'],
        'audio/EVRC'                                                              => ['evc'],
        'audio/EVRCB'                                                             => ['evb'],
        'audio/EVRCWB'                                                            => ['evw'],
        'audio/L16'                                                               => ['l16'],
        'audio/SMV'                                                               => ['smv'],
        'audio/ac3'                                                               => ['ac3'],
        'audio/basic'                                                             => ['au', 'snd'],
        'audio/dls'                                                               => ['dls'],
        'audio/iLBC'                                                              => ['lbc'],
        'audio/midi'                                                              => ['mid', 'midi', 'kar'],
        'audio/mpeg'                                                              => ['mpga', 'mp1', 'mp2', 'mp3'],
        'audio/ogg'                                                               => ['oga', 'ogg', 'spx'],
        'audio/qcelp'                                                             => ['qcp'],
        'image/bmp'                                                               => ['bmp'],
        'image/fits'                                                              => ['fits', 'fit', 'fts'],
        'image/gif'                                                               => ['gif'],
        'image/ief'                                                               => ['ief'],
        'image/jp2'                                                               => ['jp2', 'jpg2'],
        'image/jpeg'                                                              => ['jpeg', 'jpg', 'jpe', 'jfif'],
        'image/jpm'                                                               => ['jpm', 'jpgm'],
        'image/jpx'                                                               => ['jpx', 'jpf'],
        'image/svg+xml'                                                           => ['svg'],
        'image/png'                                                               => ['png'],
        'image/t38'                                                               => ['t38'],
        'image/tiff'                                                              => ['tiff', 'tif'],
        'message/global'                                                          => ['u8msg'],
        'message/rfc822'                                                          => ['eml', 'mail', 'art'],
        'model/iges'                                                              => ['igs', 'iges'],
        'model/mesh'                                                              => ['msh', 'mesh', 'silo'],
        'model/vrml'                                                              => ['wrl', 'vrml'],
        'text/calendar'                                                           => ['ics', 'ifb'],
        'text/css'                                                                => ['css'],
        'text/csv'                                                                => ['csv'],
        'text/dns'                                                                => ['soa', 'zone'],
        'text/html'                                                               => ['html', 'htm'],
        'text/javascript'                                                         => ['js'],
        'text/plain'                                                              => ['asc', 'txt', 'text', 'pm', 'el', 'c', 'h', 'cc', 'hh', 'cxx', 'hxx', 'f90'],
        'text/richtext'                                                           => ['rtx'],
        'text/sgml'                                                               => ['sgml', 'sgm'],
        'text/xml'                                                                => ['xml'],
        'video/3gpp'                                                              => ['3gp', '3gpp'],
        'video/3gpp2'                                                             => ['3g2', '3gpp2'],
        'video/mj2'                                                               => ['mj2', 'mjp2'],
        'video/mp4'                                                               => ['mp4', 'mpg4'],
        'video/mpeg'                                                              => ['mpeg', 'mpg', 'mpe'],
        'video/ogg'                                                               => ['ogv'],
        'video/quicktime'                                                         => ['qt', 'mov'],
        'video/webm'                                                              => ['webm']
    ];

    /**
     * 拡張子を与えると対応する mimetype を返す
     *
     * オプションで追加 mimetype を与えられる。
     *
     * @param string[] $exts 拡張子
     * @param array $extendMimeTypes [mimetype => [ext]] な配列
     * @return array
     */
    public function getMimeTypes(array $exts, array $extendMimeTypes = []): array
    {
        $result = [];

        foreach ($extendMimeTypes + self::$mimeTypes as $mimetype => $extensions) {
            foreach ($exts as $ext) {
                if (in_array(strtolower($ext), $extensions, true)) {
                    $result[] = $mimetype;
                }
            }
        }

        return $result;
    }
}

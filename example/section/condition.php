<?php

use ryunosuke\chmonos\Condition\AbstractCondition;
use ryunosuke\chmonos\Condition\Ajax;
use ryunosuke\chmonos\Condition\Range;
use ryunosuke\chmonos\Condition\Requires;

$condition_form = new \ryunosuke\chmonos\Form([
    'ajax1'                 => [
        'condition' => [
            'Digits' => null
        ],
        'default'   => rand(1, 99),
    ],
    'ajax2'                 => [
        'condition' => [
            'Digits' => null
        ],
        'default'   => rand(1, 99),
    ],
    'ajax_sum'              => [
        'title'     => 'サーバーサイドで足し算',
        'condition' => [
            'Digits' => null,
            $ajax
        ],
        'dependent' => ['ajax1', 'ajax2'],
    ],
    'ajax_bool'             => [
        'condition' => [
            (new Ajax('ajax-bool.php', ['ajax1', 'ajax2']))->setMessage('値が一致しません'),
        ],
    ],
    'array_length_checkbox' => [
        'title'     => '3件以下チェックボックス',
        'condition' => [
            'ArrayLength' => [null, 3]
        ],
        'options'   => [
            1 => '選択肢1',
            2 => '選択肢2',
            3 => '選択肢3',
            4 => '選択肢4',
            5 => '選択肢5'
        ],
        'default'   => [],
    ],
    'array_length_select'   => [
        'title'     => '3件以下セレクトボックス',
        'condition' => [
            'ArrayLength' => [null, 3]
        ],
        'options'   => [
            1 => '選択肢1',
            2 => '選択肢2',
            3 => '選択肢3',
            4 => '選択肢4',
            5 => '選択肢5'
        ],
        'default'   => [],
    ],
    'array_length_text'     => [
        'title'     => '3件以下テキストボックス',
        'condition' => [
            'ArrayLength' => [null, 3]
        ],
        'flags'     => [
            'multiple' => true,
        ],
        'default'   => ['a', 'b', 'c', 'x'],
    ],
    'array_length_file'     => [
        'title'     => '3件以下ファイル',
        'condition' => [
            'ArrayLength' => [null, 3],
            'FileSize'    => 1024,
        ],
        'flags'     => [
            'multiple' => true,
        ],
        'default'   => [],
    ],
    'array_exclusion_checkbox'    => [
        'title'     => '排他チェックボックス',
        'condition' => [
            'ArrayExclusion' => [
                [
                    1 => '選択肢1',
                    2 => '選択肢2',
                ],
            ]
        ],
        'options'   => [
            1 => '選択肢1',
            2 => '選択肢2',
            3 => '選択肢3',
            4 => '選択肢4',
        ],
        'default'   => [],
    ],
    'array_exclusion_select'    => [
        'title'     => '排他セレクトボックス',
        'condition' => [
            'ArrayExclusion' => [
                [
                    1 => '選択肢1',
                    2 => '選択肢2',
                ],
            ]
        ],
        'options'   => [
            1 => '選択肢1',
            2 => '選択肢2',
            3 => '選択肢3',
            4 => '選択肢4',
        ],
        'default'   => [],
    ],
    'aruiha_range'          => [
        'condition' => [
            'Aruiha' => [
                [
                    'min' => new Range(-3, -1),
                    'max' => new Range(1, 3),
                ]
            ]
        ],
        'message'   => [
            'AruihaInvalid' => '-3 ～ -1 あるいは 1 ～ 3'
        ],
    ],
    'callback1'             => [
        'title'     => 'コールバック1',
        'condition' => [
            'Callback' => function ($value, $error) {
                if ($value != 'a') {
                    $error('aじゃないとダメ');
                }
            },
        ]
    ],
    'callback2'             => [
        'title'     => 'コールバック2',
        'condition' => [
            'Callback' => [
                function ($value, $error, $depends, $userdata, $context) {
                    if ($value != 'b') {
                        $error('bじゃないとダメ');
                        $error($context['str_concat']('callback1:', $depends['callback1'], ', userdata:', $userdata));
                    }
                },
                'callback1',
                'hoge'
            ],
        ]
    ],
    'compare'               => [
        'title'     => '比較元',
        'condition' => []
    ],
    'compare_confirm'       => [
        'title'     => '同じ値でなければならない',
        'condition' => [
            'Compare' => ['==', 'compare']
        ]
    ],
    'compare_contain'       => [
        'title'     => '比較元を含んではならない',
        'condition' => [
            'Compare' => ['!contain', 'compare']
        ]
    ],
    'compare_direct'        => [
        'title'     => '今日より2日先でなければならない',
        'condition' => [
            'Date'    => 'Y/m/d',
            'Compare' => ['>=', date('Y/m/d'), 'strtotime', -3600 * 24 * 2, true]
        ]
    ],
    'greater'               => [
        'condition' => [
            'Date' => 'Y/m/d H:i:s'
        ]
    ],
    'greater_confirm'       => [
        'title'     => '右の方が大きい日時でなければならない',
        'condition' => [
            'Date'    => 'Y/m/d H:i:s',
            'Compare' => ['>', 'greater', 'strtotime']
        ]
    ],
    'dateYmd'               => [
        'title'     => '日時（Y-m-d）',
        'condition' => [
            'Date' => 'Y-m-d'
        ]
    ],
    'dateYmdHis'            => [
        'title'     => '日時（Y-m-dTH:i:s）',
        'condition' => [
            'Date' => 'Y-m-d\TH:i:s'
        ]
    ],
    'decimal'               => [
        'title'     => '小数',
        'condition' => [
            'Decimal' => [3, 2]
        ]
    ],
    'digits'                => [
        'title'     => '整数',
        'condition' => [
            'Digits' => ['+-', 4]
        ]
    ],
    'digits5'               => [
        'title'     => '整数',
        'condition' => [
            'Digits' => ['+-', 5, false]
        ]
    ],
    'number'                => [
        'title'     => '数値',
        'condition' => [
            'Number' => [-9.9, 999.999],
        ]
    ],
    'alphadigit'            => [
        'title'     => '識別子',
        'condition' => [
            'AlphaDigit' => [false, '_-'],
        ]
    ],
    'email'                 => [
        'title'     => 'メールアドレス',
        'condition' => [
            'EmailAddress' => null
        ],
        'event'     => ['input'],
    ],
    'email_multiple'        => [
        'title'     => 'メールアドレス（カンマで複数値）',
        'condition' => [
            'EmailAddress' => [null, '#(,|\r?\n)#'],
            'Distinct'     => null,
        ],
        'event'     => ['input'],
    ],
    'email_noerror'         => [
        'title'     => 'メールアドレス（change エラー）',
        'condition' => [
            'EmailAddress' => null
        ],
        'event'     => ['input.noerror', 'change'],
    ],
    'image_file_require'    => [
        'default' => '',
        'options' => [
            1 => '必須'
        ]
    ],
    'image_type'            => [
        'options' => [
            'image/png'  => 'image/png',
            'image/jpeg' => 'image/jpeg',
            'image/gif'  => 'image/gif',
        ]
    ],
    'image_file'            => [
        'title'     => '画像ファイル',
        'condition' => [
            'Requires'   => 'image_file_require',
            'FileType'   => [
                [
                    'GIF' => 'gif',
                    'PNG' => 'png',
                    'JPG' => ['jpg', 'jpeg',]
                ]
            ],
            'FileSize'   => ['200K'],
            'ImageSize'  => [100, 120],
            'DependFile' => ['image_type'],
        ],
    ],
    'hostname'              => [
        'title'     => 'ホスト名',
        'condition' => [
            'Hostname' => ''
        ]
    ],
    'hostname_multiple'     => [
        'title'     => 'ホスト名（カンマで複数値）',
        'condition' => [
            'Hostname' => ['', null, '#,|\s#']
        ]
    ],
    'hostname_v4'           => [
        'title'     => 'ホスト名（IPv4、CIDR）',
        'condition' => [
            'Hostname' => [['', 4, 'cidr']]
        ]
    ],
    'hostname_port'         => [
        'title'     => 'ホスト名（IPv4+port）',
        'condition' => [
            'Hostname' => [['', 4], true]
        ]
    ],
    'inarray'               => [
        'title'     => 'InArray(1, 2, 3)',
        'condition' => [
            'InArray' => [[1, 2, 3]]
        ]
    ],
    'notinarray'            => [
        'title'     => 'NotInArray(1, 2, 3)',
        'condition' => [
            'NotInArray' => [[1, 2, 3]]
        ]
    ],
    'inarray_strict'        => [
        'condition' => [
            'InArray' => [["1", 2, 3], true,]
        ]
    ],
    'inarray_invalid'      => [
        'options'    => [
            'x' => 'hoge',
            'y' => "\x18warning",
            'z' => 'piyo',
        ],
        'autocond'   => [
            'NotInArray' => fn(AbstractCondition $cond) => $cond->setValidationLevel('warning')->setMessageTemplate('選択できません', 'valueInArray'),
        ],
    ],
    'inarray_invalid2'      => [
        'options'    => [
            'x' => 'hoge',
            'y' => "warning",
            'z' => 'piyo',
        ],
        'invalids' => [
            'y' => "warning(無効状態)",
        ],
    ],
    'json'                  => [
        'title'     => 'JSON文字列',
        'condition' => [
            'Json' => []
        ]
    ],
    'password'              => [
        'title'     => 'パスワード',
        'condition' => [
            'Password' => null
        ]
    ],
    'filename'              => [
        'title'     => 'ファイル名',
        'condition' => [
            'FileName' => [['json', 'yaml'], null, true]
        ]
    ],
    'step'                  => [
        'title'     => '倍数',
        'condition' => [
            'Decimal' => [3, 1],
            'Range'   => [0, 99.9],
            'Step'    => 0.5,
        ]
    ],
    'step15minute'                  => [
        'title'     => '15分単位',
        'condition' => [
            'Step'    => [60 * 15, 'ja-jp'],
        ]
    ],
    'range'                 => [
        'title'     => '数値範囲',
        'condition' => [
            'Digits' => null,
            'Range'  => [-100, 100]
        ]
    ],
    'regex'                 => [
        'title'     => '正規表現（肯定）',
        'condition' => [
            'Regex' => '/^[a-z]*$/'
        ]
    ],
    'notregex'              => [
        'title'     => '正規表現（否定）',
        'condition' => [
            'Regex' => ['/^[a-z]*$/', true]
        ]
    ],
    'regexall'              => [
        'title'     => '正規表現（全行）',
        'condition' => [
            'RegexAll' => ['/^(\\.[a-z0-9]+(\\s+.+)?)?$/i', "#\n#", 2],
        ],
        'default' => ".txt   テキスト\n.csv   CSV\r\n.json  JSON\r"
    ],
    'mixed'                 => [
        'title'     => '複合条件',
        'condition' => [
            'Requires' => null,
            'Hostname' => '',
            'FileName' => [['json', 'yaml'], null, true]
        ]
    ],
    'require_checkbox'      => [
        'title'     => '必須チェックボックス',
        'default'   => [],
        'condition' => [
            'Requires' => null
        ],
        'options'   => [
            1 => '選択肢1',
            2 => '選択肢2',
            3 => '選択肢3',
            4 => '選択肢4',
            5 => '選択肢5'
        ]
    ],
    'require_select'        => [
        'title'     => '必須セレクトボックス',
        'default'   => [],
        'condition' => [
            'Requires' => null
        ],
        'options'   => [
            1 => '選択肢1',
            2 => '選択肢2',
            3 => '選択肢3',
            4 => '選択肢4',
            5 => '選択肢5'
        ],
    ],
    'require_simple'        => [
        'title'     => '単純必須',
        'condition' => [
            'Requires' => null
        ]
    ],
    'require_depend'        => [
        'title'     => '依存必須1',
        'condition' => [
            'Requires' => 'require_simple'
        ]
    ],
    'require_great'         => [
        'title'     => '依存必須2',
        'condition' => [
            'Requires' => [
                [
                    'require_simple' => ['>=', 5],
                ]
            ]
        ]
    ],
    'require_andor1'        => [],
    'require_andor2'        => [],
    'require_eqand'         => [
        'title'     => '上がaかつ下がb',
        'condition' => [
            'Requires' => [
                [
                    'require_andor1' => ['===', 'a'],
                    'require_andor2' => ['===', 'b'],
                ]
            ]
        ]
    ],
    'require_eqor'          => [
        'title'     => '上がaあるいは下がb',
        'condition' => [
            'Requires' => [
                ['require_andor1' => ['===', 'a']],
                ['require_andor2' => ['===', 'b']],
            ]
        ]
    ],
    'require_should'        => [
        'options' => [
            1 => '受け取る',
        ],
        'default' => '',
    ],
    'require_option'        => [
        'options' => [
            1 => 'メール',
            2 => '電話',
            3 => 'FAX',
        ],
    ],
    'require_always'        => [
        'options' => [
            1 => '無関係に必須',
        ],
        'default' => '',
    ],
    'require_break'         => [
        'title'     => '複合条件',
        'condition' => [
            'Requires' => [
                [
                    'require_should' => ['==', '1'],
                    'require_option' => ['any', ['2', '3']],
                ],
                [
                    'require_always' => ['==', '1']
                ],
            ]
        ]
    ],
    'require_preceding'        => [
        'options' => [
            1 => '後方は伝播しない',
        ],
        'propagate' => [
            'require_domorder' => 'preceding',
        ],
        'default' => '',
    ],
    'require_following'        => [
        'options' => [
            1 => '無条件で伝播',
        ],
        'default' => '',
    ],
    'require_domorder'         => [
        'title'     => 'DOM条件',
        'condition' => [
            'Requires' => [
                [
                    'require_preceding' => ['==', '1'],
                ],
                [
                    'require_following' => ['==', '1'],
                ],
            ]
        ]
    ],
    'require_array'         => [
        'options' => [
            1 => '1.これを選ぶと必須になる',
            2 => '2.これは必須にならない',
            3 => '3.これを選ぶと必須になる',
            4 => '4.これを選ぶと必須になる/選ばないと非活性になる',
        ],
        'default' => [],
    ],
    'require_any'           => [
        'title'     => '依存チェックボックス(1 || 3)',
        'condition' => [
            new Requires(['require_array' => ['any', [1, '3']],]),
        ]
    ],
    'require_in'            => [
        'title'     => '依存チェックボックス(1 && 3)',
        'condition' => [
            (new Requires(['require_array' => ['all', [1, '3']],]))->setValidationLevel('warning'),
        ]
    ],
    'require_needless'      => [
        'title'     => '依存チェックボックス(4)',
        'condition' => [
            new Requires(['require_array' => ['==', 4],]),
        ],
        'needless'  => 'disabled',
    ],
    'stringlength'          => [
        'title'     => '文字長',
        'condition' => [
            'StringLength' => [2, 6]
        ]
    ],
    'stringwidth'           => [
        'title'     => '文字幅',
        'condition' => [
            'StringWidth' => [2, 6]
        ]
    ],
    'telephone'             => [
        'title'     => '電話番号（ハイフン不問）',
        'condition' => [
            'Telephone' => null
        ]
    ],
    'telephone-multiple'             => [
        'title'     => '電話番号（カンマで複数値）',
        'condition' => [
            'Telephone' => [true, '#,|\s#']
        ]
    ],
    'url'                   => [
        'title'     => 'URL（スキーム不問）',
        'condition' => [
            'Uri' => null
        ]
    ],
    'url-http'              => [
        'title'     => 'URL（http(s)のみ）',
        'condition' => [
            'Uri' => [['http', 'https']]
        ]
    ],
    'url-multiple'                   => [
        'title'     => 'URL（改行・カンマで複数値）',
        'condition' => [
            'Uri' => [['http', 'https'], "#\\n|,#u"]
        ]
    ],
    'data-uri'                => [
        'title'     => 'DataURI',
        'condition' => [
            'DataUri' => ['size' => '256K', 'type' => ['txt' => ['text/txt'], 'csv' => ['text/csv']]]
        ],
        'default' => "a,b,c\na1,b1,c1\na2,b2,c2\n",
    ]
]);
resetForm($condition_form, 'condition_form');
?>

<?= $condition_form->form(['method' => 'post', 'data-max-toast-count' => 5]) ?>
<input type="hidden" name="formid" value="condition_form">
<table class="table">
    <tr>
        <th>サーバーサイドで足し算する</th>
        <td><?= $condition_form->input('ajax1') ?> + <?= $condition_form->input('ajax2') ?></td>
        <td>
            <?= $condition_form->input('ajax_sum') ?>
            <?= $condition_form->input('ajax_bool') ?>
        </td>
    </tr>
    <tr>
        <th>3件以下でなければならない：checkbox | select | text | file</th>
        <td>
            <?= $condition_form->input('array_length_checkbox', ['type' => 'checkbox']) ?><br>
            <?= $condition_form->input('array_length_select', ['type' => 'select']) ?><br>
        </td>
        <td>
            <?= $condition_form->input('array_length_text', ['type' => 'text']) ?>
            <div class="vfile-dropzone">
                ドロップエリア
                <?= $condition_form->input('array_length_file', ['type' => 'file']) ?>
            </div>
        </td>
    </tr>
    <tr>
        <th>1+2 の排他制御</th>
        <td>
            <?= $condition_form->input('array_exclusion_checkbox', ['type' => 'checkbox']) ?>
        </td>
        <td>
            <?= $condition_form->input('array_exclusion_select', ['type' => 'select']) ?>
        </td>
    </tr>
    <tr>
        <th>-3 ～ -1 あるいは 1 ～ 3 でなければならない</th>
        <td><?= $condition_form->input('aruiha_range', ['type' => 'number']) ?></td>
        <td></td>
    </tr>
    <tr>
        <th>コールバック</th>
        <td><?= $condition_form->input('callback1') ?></td>
        <td><?= $condition_form->input('callback2') ?></td>
    </tr>
    <tr>
        <th>単純比較/包含</th>
        <td>比較元<?= $condition_form->input('compare') ?></td>
        <td>
            同じ値でなければならない<?= $condition_form->input('compare_confirm') ?><br>
            比較元を含んではならない<?= $condition_form->input('compare_contain') ?>
        </td>
    </tr>
    <tr>
        <th>今日より2日先でなければならない</th>
        <td><?= $condition_form->input('compare_direct') ?></td>
        <td></td>
    </tr>
    <tr>
        <th>右の方が大きい日時でなければならない</th>
        <td><?= $condition_form->input('greater') ?></td>
        <td><?= $condition_form->input('greater_confirm') ?></td>
    </tr>
    <tr>
        <th>日時：Y-m-d | Y-m-d H:i:s</th>
        <td><?= $condition_form->input('dateYmd') ?></td>
        <td><?= $condition_form->input('dateYmdHis') ?></td>
    </tr>
    <tr>
        <th>小数：abc.yz</th>
        <td><?= $condition_form->input('decimal') ?></td>
        <td></td>
    </tr>
    <tr>
        <th>整数（4桁固定|5桁まで）</th>
        <td><?= $condition_form->input('digits') ?></td>
        <td><?= $condition_form->input('digits5') ?></td>
    </tr>
    <tr>
        <th>数値</th>
        <td><?= $condition_form->input('number') ?></td>
        <td></td>
    </tr>
    <tr>
        <th>識別子（先頭数字NGで半角英数字_-）</th>
        <td><?= $condition_form->input('alphadigit') ?></td>
        <td></td>
    </tr>
    <tr>
        <th>メールアドレス（input でエラー | 複数値）</th>
        <td><?= $condition_form->input('email') ?></td>
        <td><?= $condition_form->input('email_multiple', ['type' => 'textarea']) ?></td>
    </tr>
    <tr>
        <th>メールアドレス（input でエラーなし、chage でエラー）</th>
        <td><?= $condition_form->input('email_noerror') ?></td>
        <td></td>
    </tr>
    <tr>
        <th>画像ファイル(ファイルタイプ/ファイルサイズ/画像タイプ)</th>
        <td>
            <?= $condition_form->input('image_file_require') ?>
            <div class="vfile-dropzone" for="image_file">
                ドロップエリアです
            </div>
        </td>
        <td><?= $condition_form->input('image_type') ?><?= $condition_form->input('image_file', ['id' => 'image_file', 'type' => 'file']) ?></td>
    </tr>
    <tr>
        <th>ホスト名：ホスト名のみ | ホスト名、IPv4、cidrのみ | ホスト名、ポート必須 | 複数値</th>
        <td>
            <?= $condition_form->input('hostname') ?>
            <?= $condition_form->input('hostname_v4') ?>
            <?= $condition_form->input('hostname_port') ?>
        </td>
        <td>
            <?= $condition_form->input('hostname_multiple') ?>
        </td>
    </tr>
    <tr>
        <th>Array：InArray(1, 2, 3) | NotInArray(1, 2, 3)</th>
        <td><?= $condition_form->input('inarray') ?></td>
        <td><?= $condition_form->input('notinarray') ?></td>
    </tr>
    <tr>
        <th>Array：InArrayStrict("1", 2, 3)</th>
        <td><?= $condition_form->input('inarray_strict') ?></td>
        <td></td>
    </tr>
    <tr>
        <th>Array：InArrayInvalid("x", "z", current "y")</th>
        <td><?= $condition_form->input('inarray_invalid', ['value' => 'y']) ?></td>
        <td><?= $condition_form->input('inarray_invalid2', ['type' => 'checkbox', 'value' => 'y']) ?></td>
        <td></td>
    </tr>
    <tr>
        <th>JSON 文字列</th>
        <td><?= $condition_form->input('json') ?></td>
        <td></td>
    </tr>
    <tr>
        <th>Password(a～z,A～Z,0～9,$%+)</th>
        <td><?= $condition_form->input('password') ?></td>
        <td></td>
    </tr>
    <tr>
        <th>ファイル名(json, yaml)</th>
        <td><?= $condition_form->input('filename') ?></td>
        <td></td>
    </tr>
    <tr>
        <th>倍数(0.5)</th>
        <td><?= $condition_form->input('step') ?></td>
        <td></td>
    </tr>
    <tr>
        <th>倍数(15分)</th>
        <td><?= $condition_form->input('step15minute', ['type' => 'time']) ?></td>
        <td></td>
    </tr>
    <tr>
        <th>数値範囲(-100 ～ 100)</th>
        <td><?= $condition_form->input('range') ?></td>
        <td></td>
    </tr>
    <tr>
        <th>正規表現：肯定(a-z]*) | 否定(a-z]*)</th>
        <td><?= $condition_form->input('regex') ?></td>
        <td><?= $condition_form->input('notregex') ?></td>
    </tr>
    <tr>
        <th>正規表現：全行（.ext   コメント）</th>
        <td><?= $condition_form->input('regexall') ?></td>
        <td></td>
    </tr>
    <tr>
        <th>複合条件（ファイル名・ホスト名）</th>
        <td><?= $condition_form->input('mixed') ?></td>
        <td></td>
    </tr>
    <tr>
        <th>必須：checkbox | select</th>
        <td><?= $condition_form->input('require_checkbox', ['type' => 'checkbox']) ?></td>
        <td><?= $condition_form->input('require_select', ['type' => 'select']) ?></td>
    </tr>
    <tr>
        <th>必須：単純必須 | 左が入力されたら必須 | 左が5以上なら必須</th>
        <td><?= $condition_form->input('require_simple') ?></td>
        <td>
            <?= $condition_form->input('require_depend') ?>
            <?= $condition_form->input('require_great') ?>
        </td>
    </tr>
    <tr>
        <th>必須：上がaかつ下がbなら必須 | 上がaあるいは下がbなら必須</th>
        <td><?= $condition_form->input('require_andor1') ?><br><?= $condition_form->input('require_andor2') ?></td>
        <td>
            <?= $condition_form->input('require_eqand') ?>
            <?= $condition_form->input('require_eqor') ?>
        </td>
    </tr>
    <tr>
        <th>必須：受け取る場合のみ電話 or FAX必須だが、下にチェックを入れると無条件必須</th>
        <td><?= $condition_form->input('require_should') ?><br><?= $condition_form->input('require_option', ['type' => 'radio', 'separator' => '<br>']) ?><br><?= $condition_form->input('require_always') ?></td>
        <td>
            <?= $condition_form->input('require_break') ?>
        </td>
    </tr>
    <tr>
        <th>必須：DOM 順で後方に居るなら伝播しない</th>
        <td colspan="2">
            <?= $condition_form->input('require_preceding') ?>
            <?= $condition_form->input('require_domorder') ?>
            <?= $condition_form->input('require_following') ?>
        </td>
    </tr>
    <tr>
        <th>必須：配列チェックボックス</th>
        <td><?= $condition_form->input('require_array', ['type' => 'checkbox', 'separator' => '<br>']) ?></td>
        <td>
            <?= $condition_form->label('require_any') ?>
            <?= $condition_form->input('require_any') ?>
            <?= $condition_form->label('require_in') ?>
            <?= $condition_form->input('require_in') ?>
            <?= $condition_form->label('require_needless') ?>
            <?= $condition_form->input('require_needless') ?>
        </td>
    </tr>
    <tr>
        <th>文字長</th>
        <td><?= $condition_form->input('stringlength') ?></td>
        <td></td>
    </tr>
    <tr>
        <th>文字幅</th>
        <td><?= $condition_form->input('stringwidth') ?></td>
        <td></td>
    </tr>
    <tr>
        <th>電話番号： ハイフン不問 | ハイフン必須な複数値</th>
        <td><?= $condition_form->input('telephone') ?></td>
        <td><?= $condition_form->input('telephone-multiple') ?></td>
    </tr>
    <tr>
        <th>URL：スキーム不問 | http(s)のみ | 複数値</th>
        <td>
            <?= $condition_form->input('url') ?>
            <?= $condition_form->input('url-http') ?>
        </td>
        <td>
            <?= $condition_form->input('url-multiple', ['type' => 'textarea']) ?>
        </td>
    </tr>
    <tr>
        <th>DataURI</th>
        <td colspan="2">
            <?= $condition_form->input('data-uri', ['type' => 'textarea', 'style' => 'width:100%']) ?>
            <div id="data-uri-filedrop"  class="vfile-dropzone">
                ドロップエリアです
            </div>
            <script type="module">
                $$('#data-uri-filedrop').addEventListener('filedrop', async function (e) {
                    const textarea = $$('[data-vinput-id=data-uri]');
                    textarea.value = await e.detail.files[0].toDataURL();
                    textarea.dispatchEvent(new Event('change', {bubbles: true}));
                });
            </script>
        </td>
    </tr>
</table>
<input type="button" class="btn btn-info object-button" value="object">
<input type="submit" id="condition_form_submit" class="btn btn-primary" value="post">
<label class='btn btn-warning'>
    <input type='checkbox' class="js-enable-switcher" checked>
    js チェック有効
</label>
<?= $condition_form->form() ?>

<?php
namespace ryunosuke\Test\UnitTest\chmonos;

use ryunosuke\chmonos\Condition\Decimal;
use ryunosuke\chmonos\Context;
use ryunosuke\chmonos\Input;
use ryunosuke\Test\CustomInput;

class ContextTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    protected function _getRules()
    {
        $rules = [
            'parent'   => [
                'title'     => '親項目',
                'condition' => [
                    'StringLength' => array_values([
                        2,
                        6
                    ])
                ],
                'default'   => 'P',
            ],
            'children' => [
                'title'     => '子項目配列',
                'condition' => [],
                'inputs'    => [
                    'child1' => [
                        'title'     => '子項目1',
                        'condition' => [
                            'EmailAddress' => null
                        ],
                        'default'   => 'C1',
                    ],
                    'child2' => [
                        'title'     => '子項目2',
                        'condition' => [
                            'Decimal' => array_values([
                                3,
                                3
                            ])
                        ],
                        'default'   => 'C2',
                    ]
                ]
            ]
        ];

        return $rules;
    }

    protected function _getValues($context)
    {
        if ($context == 'valid') {
            return [
                'parent'   => '1234',
                'children' => [
                    [
                        'child1' => 'test@example.com',
                        'child2' => '123.456'
                    ]
                ]
            ];
        }
        if ($context == 'invalid_parent') {
            return [
                'parent'   => '1234567',
                'children' => [
                    [
                        'child1' => 'test@example.com',
                        'child2' => '123.456'
                    ]
                ]
            ];
        }
        if ($context == 'invalid_child') {
            return [
                'parent'   => '1234',
                'children' => [
                    [
                        'child1' => 'invalidaddress',
                        'child2' => '123.456'
                    ],
                    [
                        'child1' => 'test@example.com',
                        'child2' => '3.14159'
                    ]
                ]
            ];
        }
    }

    function test___construct()
    {
        $context = new Context([
            'null'    => null,
            'parent'  => [
                'inputs' => [
                    'child' => [],
                ],
            ],
            'prefix_' => function () {
                return [
                    'inner1' => [],
                    'inner2' => [],
                ];
            },
            function () {
                yield 'yield_inner1' => [];
                yield 'yield_inner2' => [];
            },
        ], null, CustomInput::class);

        that(isset($context->null))->isFalse();
        that(isset($context->parent))->isTrue();

        that($context)->parent->isInstanceOf(CustomInput::class);
        that($context)->parent->context->child->isInstanceOf(CustomInput::class);

        that($context)->prefix_inner1->isInstanceOf(CustomInput::class);
        that($context)->prefix_inner2->isInstanceOf(CustomInput::class);

        that($context)->yield_inner1->isInstanceOf(CustomInput::class);
        that($context)->yield_inner2->isInstanceOf(CustomInput::class);
    }

    function test___isset()
    {
        $context = new Context($this->_getRules());
        that(isset($context->parent))->isTrue();
        that(isset($context->children))->isTrue();
        that(isset($context->undefined))->isFalse();
        that(isset($context->{"children/child1"}))->isTrue();
        that(isset($context->{"children/child2"}))->isTrue();
        that(isset($context->{"children/child3"}))->isFalse();
    }

    function test___get()
    {
        $context = new Context($this->_getRules());
        that($context)->parent->isInstanceOf(Input::class);
        that($context)->children->isInstanceOf(Input::class);
        that($context)->{"children/child1"}->isInstanceOf(Input::class);
        that($context)->{"children/child2"}->isInstanceOf(Input::class);
        that($context)->undefinedProperty->isThrowable(\InvalidArgumentException::class);
    }

    function test_initialize()
    {
        $context = new Context([
            'source' => [],
            'target' => [
                'condition' => [
                    'Requires' => 'source'
                ],
            ],
            'flag'   => [
                'propagate' => 'values/elem3',
            ],
            'values' => [
                'inputs' => [
                    'elem1' => [
                        'condition' => [
                            'Requires' => '/flag'
                        ],
                    ],
                    'elem2' => [
                        'condition' => [
                            'Requires' => 'elem1'
                        ],
                    ],
                    'elem3' => [
                        'propagate' => 'elem2',
                    ],
                ],
            ],
        ]);
        $context->initialize();

        // source は target に伝播する
        that($context)->source->propagate->is(['target']);

        // target は誰にも伝播しない
        that($context)->target->propagate->is([]);

        // flag は values/elem3 values/elem1 に伝播する
        that($context)->flag->propagate->is(['values/elem3', 'values/elem1']);

        // values/elem1 は兄弟の elem2 に伝播する
        that($context)->{"values/elem1"}->propagate->is(['elem2']);

        // values/elem2 は誰にも伝播しない
        that($context)->{"values/elem2"}->propagate->is([]);

        // values/elem3 は elem2 に伝播する
        that($context)->{"values/elem3"}->propagate->is(['elem2']);
    }

    function test_normalize()
    {
        $rules = [
            'dummy_file' => [
                'condition' => [
                    'FileSize' => 1024
                ],
                'default'   => 'path/to/dummy'
            ],
            'phantom-1'  => [],
            'phantom-2'  => [],
            'phantom-3'  => [],
            'phantomX'   => [
                'phantom' => [
                    '%s-%s-%s',
                    'phantom-1',
                    'phantom-2',
                    'phantom-3',
                ]
            ],
            'phantomY'   => [
                'phantom' => [
                    '%s-%s-%s',
                    'phantom-1',
                    'phantom-2',
                    'phantom-9',
                ]
            ],
            'children'   => [
                'inputs' => [
                    'child1' => [],
                    'child2' => [
                        'default' => 'cvalue2',
                    ]
                ]
            ]
        ];
        $context = new Context($rules);
        $values = $context->normalize([
            'phantom-1' => 'hoge',
            'phantom-2' => 'fuga',
            'phantom-3' => 'piyo',
            'children'  => [
                [
                    'child1' => 'test@example.com',
                ]
            ],
            'undef'     => "未定義"
        ]);

        // default
        that($values)['dummy_file']->is('path/to/dummy');

        // phantomX: vsprintf になっているはず
        that($values)['phantomX']->is("hoge-fuga-piyo");
        // phantomY: 一つでも空なら全体として空になるはず
        that($values)['phantomY']->isNull();

        // children
        that($values)['children']->is([
            [
                "child1" => "test@example.com",
                "child2" => "cvalue2",
            ],
        ]);

        // filter
        that($values)->notHasKey('undef');
    }

    function test_error()
    {
        $context = new Context($this->_getRules());

        $values = $this->_getValues('valid');
        $context->validate($values);

        // この時点ではエラーはないはず
        $messages = $context->getMessages();
        that($messages)->notHasKey('parent');

        // 親項目にユーザエラーを追加するとエラーになるはず
        $context->error('parent', 'ユーザエラー');
        $messages = $context->getMessages();
        that($messages)->hasKey('parent');
    }

    function test_getDefaults()
    {
        $context = new Context($this->_getRules());

        that($context->getDefaults())->is([
            "parent"   => 'P',
            "children" => [
                "child1" => "C1",
                "child2" => "C2",
            ],
        ]);
    }

    function test_getValues()
    {
        $context = new Context($this->_getRules());

        that($context->getValues())->is([
            "parent"   => "P",
            "children" => [],
        ]);

        $context->normalize([
            "parent"   => __FILE__ . 'P',
            "children" => [
                ['child1' => __FILE__ . 'C1'],
                ['child1' => __FILE__ . 'C2'],
            ],
        ]);

        that($context->getValues())->is([
            "parent"   => __FILE__ . 'P',
            "children" => [
                ["child1" => __FILE__ . 'C1', "child2" => 'C2'],
                ["child1" => __FILE__ . 'C2', "child2" => 'C2'],
            ],
        ]);
    }

    function test_getMessages()
    {
        $context = new Context($this->_getRules());

        $values = $this->_getValues('invalid_parent');
        $context->validate($values);

        // 文字長エラーになっているはず
        $messages = $context->getMessages();
        that(reset($messages['parent']))->contains('2文字～6文字で入力して下さい');

        //-----------------------------------------------

        $values = $this->_getValues('invalid_child');
        $context->validate($values);

        // それぞれエラーになっているはず
        $messages = $context->getMessages();
        foreach ($messages as $array) {
            that(reset($array[0]['child1']))->contains('メールアドレスを正しく入力してください');
            that(reset($array[1]['child2']))->contains('小数部分を3桁以下で入力してください');
        }
    }

    function test_clear()
    {
        $context = new Context($this->_getRules());

        $values = $this->_getValues('invalid_parent');
        $context->validate($values);

        that($context)->getMessages()->count(1);
        $context->clear();
        that($context)->getMessages()->count(0);

        //-----------------------------------------------

        $values = $this->_getValues('invalid_child');
        $context->validate($values);

        that($context)->getMessages()->count(1);
        $context->clear();
        that($context)->getMessages()->count(0);
    }

    function test_getFlatMessages()
    {
        $context = new Context($this->_getRules());

        $values = $this->_getValues('invalid_parent');
        $context->validate($values);

        // フォーマット未指定
        $messages = $context->getFlatMessages();
        that($messages)[0]->contains('[親項目] 2文字～6文字で入力して下さい');

        // フォーマット指定
        $messages = $context->getFlatMessages('【%s】 %s');
        that($messages)[0]->contains('【親項目】 2文字～6文字で入力して下さい');

        //-----------------------------------------------


        $values = $this->_getValues('invalid_child');
        $context->validate($values);

        // フォーマット未指定
        $messages = $context->getFlatMessages();
        that($messages)[0]->contains('[子項目配列 1行目 - 子項目1] メールアドレスを正しく入力してください');
        that($messages)[1]->contains('[子項目配列 2行目 - 子項目2] 小数部分を3桁以下で入力してください');

        // フォーマット指定
        $messages = $context->getFlatMessages('【%s】 %s', '%sの%d個目：%s');
        that($messages)[0]->contains('【子項目配列の1個目：子項目1】 メールアドレスを正しく入力してください');
        that($messages)[1]->contains('【子項目配列の2個目：子項目2】 小数部分を3桁以下で入力してください');
    }

    function test_getFlatMessages_withArrays()
    {
        $form = new Context([
            'arrays' => [
                'title'     => '配列要素',
                'condition' => [
                    'ArrayLength' => [2, null],
                ],
                'inputs'    => [
                    'hoge' => [
                        'title'     => '配下要素A',
                        'condition' => [
                            'Requires' => null,
                        ],
                    ],
                    'fuga' => [
                        'title'     => '配下要素B',
                        'condition' => [
                            'Requires' => null,
                        ],
                    ],
                ],
            ]
        ]);

        $values = [
            'arrays' => [
                ['hoge' => '', 'fuga' => '']
            ],
        ];
        $form->validate($values);
        that($form)->getFlatMessages()->is([
            "[配列要素] 2件以上は入力してください",
            "[配列要素 1行目 - 配下要素A] 入力必須です",
            "[配列要素 1行目 - 配下要素B] 入力必須です",
        ]);
    }

    function test_getRules()
    {
        $context = new Context([
            'parent' => [
                'inputs' => [
                    'child' => [
                        'condition' => [
                            // キー指定
                            'Decimal'              => [1, 1],
                            // エイリアス指定
                            'num'                  => 'Decimal(int:2,dec:2)',
                            // エイリアス指定
                            'Decimal(int:3,dec:3)' => [
                                Decimal::INVALID => 'foo'
                            ],
                            // インスタンス指定
                            new Decimal(4, 4),
                        ],
                        'message'   => [
                            'Decimal'              => [
                                Decimal::INVALID => 'hoge'
                            ],
                            'num'                  => [
                                Decimal::INVALID => 'fuga'
                            ],
                            'Decimal(int:3,dec:3)' => [
                                Decimal::INVALID_INT => 'bar'
                            ],
                            '0'                    => [
                                Decimal::INVALID => 'piyo'
                            ],
                        ]
                    ]
                ]
            ]
        ]);

        $rule = $context->getRules();

        that($rule)['parent/child']['condition']['Decimal']['cname']->is("Decimal");
        that($rule)['parent/child']['condition']['num']['cname']->is("Decimal");
        that($rule)['parent/child']['condition']['Decimal(int:3,dec:3)']['cname']->is("Decimal");
        that($rule)['parent/child']['condition']['0']['cname']->is("Decimal");

        that($rule)['parent/child']['condition']['Decimal']['param']['int']->is(1);
        that($rule)['parent/child']['condition']['num']['param']['int']->is(2);
        that($rule)['parent/child']['condition']['Decimal(int:3,dec:3)']['param']['int']->is(3);
        that($rule)['parent/child']['condition']['0']['param']['int']->is(4);

        that($rule)['parent/child']['condition']['Decimal']['message'][Decimal::INVALID]->is("hoge");
        that($rule)['parent/child']['condition']['num']['message'][Decimal::INVALID]->is("fuga");
        that($rule)['parent/child']['condition']['Decimal(int:3,dec:3)']['message'][Decimal::INVALID]->is("foo");
        that($rule)['parent/child']['condition']['Decimal(int:3,dec:3)']['message'][Decimal::INVALID_INT]->is("bar");
        that($rule)['parent/child']['condition']['0']['message'][Decimal::INVALID]->is("piyo");
    }

    function test_hasInputFile()
    {
        // 持っていない
        $context = new Context($this->_getRules());
        that($context)->hasInputFile()->isFalse();

        // 持っている
        $rules = [
            'dummy' => [
                'condition' => [
                    'FileSize' => 1024
                ]
            ]
        ];
        $context = new Context($rules);
        that($context)->hasInputFile()->isTrue();

        // 子が持っている
        $rules = [
            'children' => [
                'inputs' => [
                    'child' => [
                        'condition' => [
                            'FileSize' => 1024
                        ]
                    ]
                ]
            ]
        ];
        $context = new Context($rules);
        that($context)->hasInputFile()->isTrue();
    }

    function test_getIterator()
    {
        $context = new Context(['inputA' => [], 'inputB' => []]);
        that(array_keys(iterator_to_array($context)))->is(['inputA', 'inputB']);
    }

    function test_noform()
    {
        $tmpfile = sys_get_temp_dir() . '/plain.txt';
        file_put_contents($tmpfile, 'test');

        $context = new Context([
            'plain'  => [
                'condition' => [
                    'StringLength' => [3, 5],
                ],
            ],
            'file'   => [
                'condition' => [
                    'FileType' => [['txt' => ['txt']]],
                ],
            ],
            'inputs' => [
                'condition' => [
                    'ArrayLength' => [3, 5],
                ],
                'inputs'    => [
                    'plain' => [
                        'condition' => [
                            'StringLength' => [3, 5],
                        ],
                    ],
                    'file'  => [
                        'condition' => [
                            'FileType' => [['txt' => ['txt']]],
                        ],
                    ],
                ],
            ],
        ]);
        that($context)->validate([
            'plain'  => 'x',
            'file'   => __FILE__,
            'inputs' => [
                [
                    'plain' => 'x',
                    'file'  => __FILE__,
                ],
                [
                    'plain' => 'x',
                    'file'  => __FILE__,
                ],
            ],
        ])->isFalse();
        that($context)->getMessages()->is([
            "plain"  => [
                "StringLength" => [
                    "StringLengthInvalidMinMax" => "3文字～5文字で入力して下さい",
                ],
            ],
            "file"   => [
                "FileType" => [
                    "FileTypeInvalidType" => "txt形式のファイルを選択して下さい",
                ],
            ],
            "inputs" => [
                "ArrayLength" => [
                    "ArrayLengthInvalidMinMax" => "3件～5件を入力して下さい",
                ],
                0             => [
                    "plain" => [
                        "StringLength" => [
                            "StringLengthInvalidMinMax" => "3文字～5文字で入力して下さい",
                        ],
                    ],
                    "file"  => [
                        "FileType" => [
                            "FileTypeInvalidType" => "txt形式のファイルを選択して下さい",
                        ],
                    ],
                ],
                1             => [
                    "plain" => [
                        "StringLength" => [
                            "StringLengthInvalidMinMax" => "3文字～5文字で入力して下さい",
                        ],
                    ],
                    "file"  => [
                        "FileType" => [
                            "FileTypeInvalidType" => "txt形式のファイルを選択して下さい",
                        ],
                    ],
                ],
            ],
        ]);

        that($context)->validate([
            'plain'  => 'xyz',
            'file'   => $tmpfile,
            'inputs' => [
                [
                    'plain' => 'xyz',
                    'file'  => $tmpfile,
                ],
                [
                    'plain' => 'xyz',
                    'file'  => $tmpfile,
                ],
                [
                    'plain' => 'xyz',
                    'file'  => $tmpfile,
                ],
            ],
        ])->isTrue();
        that($context)->getMessages()->isEmpty();
    }
}

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
                ]
            ],
            'children' => [
                'title'     => '子項目配列',
                'condition' => [],
                'inputs'    => [
                    'child1' => [
                        'title'     => '子項目1',
                        'condition' => [
                            'EmailAddress' => null
                        ]
                    ],
                    'child2' => [
                        'title'     => '子項目2',
                        'condition' => [
                            'Decimal' => array_values([
                                3,
                                3
                            ])
                        ]
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
            'parent' => [
                'inputs' => [
                    'child' => [],
                ],
            ],
        ], null, CustomInput::class);

        $this->assertInstanceOf(CustomInput::class, $context->parent);
        $this->assertInstanceOf(CustomInput::class, $context->parent->context->child);
    }

    function test___isset()
    {
        $context = new Context($this->_getRules());
        $this->assertTrue(isset($context->parent));
        $this->assertTrue(isset($context->children));
        $this->assertFalse(isset($context->undefined));
        $this->assertTrue(isset($context->{"children/child1"}));
        $this->assertTrue(isset($context->{"children/child2"}));
        $this->assertFalse(isset($context->{"children/child3"}));
    }

    function test___get()
    {
        $context = new Context($this->_getRules());
        $this->assertInstanceOf(Input::class, $context->parent);
        $this->assertInstanceOf(Input::class, $context->children);
        $this->assertInstanceOf(Input::class, $context->{"children/child1"});
        $this->assertInstanceOf(Input::class, $context->{"children/child2"});

        $this->expectException(\InvalidArgumentException::class);
        /** @noinspection PhpExpressionResultUnusedInspection */
        $context->undefinedProperty;
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
        $this->assertEquals(['target'], $context->source->propagate);

        // target は誰にも伝播しない
        $this->assertEquals([], $context->target->propagate);

        // flag は values/elem3 values/elem1 に伝播する
        $this->assertEquals(['values/elem3', 'values/elem1'], $context->flag->propagate);

        // values/elem1 は兄弟の elem2 に伝播する
        $this->assertEquals(['elem2'], $context->{"values/elem1"}->propagate);

        // values/elem2 は誰にも伝播しない
        $this->assertEquals([], $context->{"values/elem2"}->propagate);

        // values/elem3 は誰にも伝播しない
        $this->assertEquals(['elem2'], $context->{"values/elem3"}->propagate);
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
        $this->assertEquals('path/to/dummy', $values['dummy_file']);

        // phantomX: vsprintf になっているはず
        $this->assertEquals('hoge-fuga-piyo', $values['phantomX']);
        // phantomY: 一つでも空なら全体として空になるはず
        $this->assertEquals('', $values['phantomY']);

        // children
        $this->assertEquals([
            [
                'child1' => 'test@example.com',
                'child2' => 'cvalue2',
            ]
        ], $values['children']);

        // filter
        $this->assertArrayNotHasKey('undef', $values);
    }

    function test_error()
    {
        $context = new Context($this->_getRules());

        $values = $this->_getValues('valid');
        $context->validate($values);

        // この時点ではエラーはないはず
        $messages = $context->getMessages();
        $this->assertArrayNotHasKey('parent', $messages);

        // 親項目にユーザエラーを追加するとエラーになるはず
        $context->error('parent', 'ユーザエラー');
        $messages = $context->getMessages();
        $this->assertArrayHasKey('parent', $messages);
    }

    function test_getMessages()
    {
        $context = new Context($this->_getRules());

        $values = $this->_getValues('invalid_parent');
        $context->validate($values);

        // 文字長エラーになっているはず
        $messages = $context->getMessages();
        $this->assertContains('2文字～6文字で入力して下さい', reset($messages['parent']));

        //-----------------------------------------------

        $values = $this->_getValues('invalid_child');
        $context->validate($values);

        // それぞれエラーになっているはず
        $messages = $context->getMessages();
        foreach ($messages as $array) {
            $this->assertContains('メールアドレスを正しく入力してください', reset($array[0]['child1']));
            $this->assertContains('小数部分を3桁以下で入力してください', reset($array[1]['child2']));
        }
    }

    function test_getFlatMessages()
    {
        $context = new Context($this->_getRules());

        $values = $this->_getValues('invalid_parent');
        $context->validate($values);

        // フォーマット未指定
        $messages = $context->getFlatMessages();
        $this->assertContains('[親項目] 2文字～6文字で入力して下さい', $messages[0]);

        // フォーマット指定
        $messages = $context->getFlatMessages('【%s】 %s');
        $this->assertContains('【親項目】 2文字～6文字で入力して下さい', $messages[0]);

        //-----------------------------------------------


        $values = $this->_getValues('invalid_child');
        $context->validate($values);

        // フォーマット未指定
        $messages = $context->getFlatMessages();
        $this->assertContains('[子項目配列 1行目 - 子項目1] メールアドレスを正しく入力してください', $messages[0]);
        $this->assertContains('[子項目配列 2行目 - 子項目2] 小数部分を3桁以下で入力してください', $messages[1]);

        // フォーマット指定
        $messages = $context->getFlatMessages('【%s】 %s', '%sの%d個目：%s');
        $this->assertContains('【子項目配列の1個目：子項目1】 メールアドレスを正しく入力してください', $messages[0]);
        $this->assertContains('【子項目配列の2個目：子項目2】 小数部分を3桁以下で入力してください', $messages[1]);
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
        $this->assertEquals([
            "[配列要素] 2件以上は入力してください",
            "[配列要素 1行目 - 配下要素A] 入力必須です",
            "[配列要素 1行目 - 配下要素B] 入力必須です",
        ], $form->getFlatMessages());
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

        $this->assertEquals('Decimal', $rule['parent/child']['condition']['Decimal']['cname']);
        $this->assertEquals('Decimal', $rule['parent/child']['condition']['num']['cname']);
        $this->assertEquals('Decimal', $rule['parent/child']['condition']['Decimal(int:3,dec:3)']['cname']);
        $this->assertEquals('Decimal', $rule['parent/child']['condition']['0']['cname']);

        $this->assertEquals('1', $rule['parent/child']['condition']['Decimal']['param']['int']);
        $this->assertEquals('2', $rule['parent/child']['condition']['num']['param']['int']);
        $this->assertEquals('3', $rule['parent/child']['condition']['Decimal(int:3,dec:3)']['param']['int']);
        $this->assertEquals('4', $rule['parent/child']['condition']['0']['param']['int']);

        $this->assertEquals('hoge', $rule['parent/child']['condition']['Decimal']['message'][Decimal::INVALID]);
        $this->assertEquals('fuga', $rule['parent/child']['condition']['num']['message'][Decimal::INVALID]);
        $this->assertEquals('foo', $rule['parent/child']['condition']['Decimal(int:3,dec:3)']['message'][Decimal::INVALID]);
        $this->assertEquals('bar', $rule['parent/child']['condition']['Decimal(int:3,dec:3)']['message'][Decimal::INVALID_INT]);
        $this->assertEquals('piyo', $rule['parent/child']['condition']['0']['message'][Decimal::INVALID]);
    }

    function test_hasInputFile()
    {
        // 持っていない
        $context = new Context($this->_getRules());
        $this->assertFalse($context->hasInputFile());

        // 持っている
        $rules = [
            'dummy' => [
                'condition' => [
                    'FileSize' => 1024
                ]
            ]
        ];
        $context = new Context($rules);
        $this->assertTrue($context->hasInputFile());

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
        $this->assertTrue($context->hasInputFile());
    }

    function test_getIterator()
    {
        $context = new Context(['inputA' => [], 'inputB' => []]);
        $this->assertEquals(['inputA', 'inputB'], array_keys(iterator_to_array($context)));
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
        $this->assertFalse($context->validate([
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
        ]));
        $this->assertEquals([
            'plain'  => [
                'StringLength' => [
                    'StringLengthInvalidMinMax' => '3文字～5文字で入力して下さい',
                ],
            ],
            'file'   => [
                'FileType' => [
                    'FileTypeInvalidType' => 'txt形式のファイルを選択して下さい',
                ],
            ],
            'inputs' => [
                'ArrayLength' => [
                    'ArrayLengthInvalidMinMax' => '3件～5件を入力して下さい',
                ],
                0             => [
                    'plain' => [
                        'StringLength' => [
                            'StringLengthInvalidMinMax' => '3文字～5文字で入力して下さい',
                        ],
                    ],
                    'file'  => [
                        'FileType' => [
                            'FileTypeInvalidType' => 'txt形式のファイルを選択して下さい',
                        ],
                    ],
                ],
                1             => [
                    'plain' => [
                        'StringLength' => [
                            'StringLengthInvalidMinMax' => '3文字～5文字で入力して下さい',
                        ],
                    ],
                    'file'  => [
                        'FileType' => [
                            'FileTypeInvalidType' => 'txt形式のファイルを選択して下さい',
                        ],
                    ],
                ],
            ],
        ], $context->getMessages());

        $this->assertTrue($context->validate([
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
        ]));
        $this->assertEmpty($context->getMessages());
    }
}

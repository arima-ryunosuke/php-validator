<?php
/** @noinspection CssUnknownProperty */
namespace ryunosuke\Test\UnitTest\chmonos;

use ryunosuke\chmonos\Condition\Decimal;
use ryunosuke\chmonos\Condition\InArray;
use ryunosuke\chmonos\Condition\Requires;
use ryunosuke\chmonos\Condition\StringLength;
use ryunosuke\chmonos\Context;
use ryunosuke\chmonos\Exception\ValidationException;
use ryunosuke\chmonos\Input;

class InputTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test___construct()
    {
        $rule = [
            'condition' => new Requires(),
            'propagate' => 'other'
        ];
        $input = new Input($rule);
        $this->assertTrue(is_array($input->condition));
        $this->assertTrue(is_array($input->propagate));

        $rule = [
            'options' => [
                'group' => [
                    '1' => 'groupoption.1'
                ],
                '2'     => 'option.2',
            ]
        ];
        $input = new Input($rule);
        $this->assertEquals(1, $input->default);
    }

    function test___construct_js()
    {
        $input = new Input(['javascript' => true]);
        $this->assertEquals(['server' => true, 'client' => true], $input->checkmode);

        $input = new Input(['javascript' => false]);
        $this->assertEquals(['server' => true, 'client' => false], $input->checkmode);
    }

    function test___isset()
    {
        $input = new Input([]);
        $this->assertFalse(isset($input->context));
        $this->assertTrue(isset($input->condition));
        $this->assertFalse(isset($input->hogera));

        $input = new Input([
            'inputs' => [
                'child' => [],
            ]
        ]);
        $this->assertTrue(isset($input->context));
    }

    function test___get()
    {
        $input = new Input([]);
        $this->assertTrue(is_array($input->propagate));

        $this->assertException(new \InvalidArgumentException('undefined property'), function () use ($input) {
            return $input->hogera;
        });
    }

    function test_initialize()
    {
        $context = new Context([
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
                            'Requires' => 'elem1',
                            'Unique'   => [],
                        ],
                    ],
                ],
            ],
        ]);
        $context->initialize();

        $this->assertEquals([
            'root'   => 'values',
            'name'   => 'elem2',
            'strict' => true,
        ], $context->values->context->elem2->condition['Unique']->getValidationParam());
    }

    function test_normalize()
    {
        $input = new Input([
            'name'     => 'hoge',
            'default'  => 'DDD',
            'pseudo'   => true,
            'multiple' => true,
            'trimming' => true,
        ]);

        // 無いならデフォルト
        $this->assertEquals('DDD', $input->normalize([]));
        // あるならその値
        $this->assertEquals('hogera', $input->normalize(['hoge' => 'hogera']));
        // trim される
        $this->assertEquals('hogera', $input->normalize(['hoge' => ' hogera ']));
        // pseudo:true で multiple で空文字なら配列になる
        $this->assertEquals([], $input->normalize(['hoge' => '']));

        $input = new Input([
            'name'     => 'hoge',
            'default'  => 'DDD',
            'pseudo'   => 'hoge',
            'multiple' => false,
            'trimming' => false,
        ]);

        // trim されない
        $this->assertEquals(' hogera ', $input->normalize(['hoge' => ' hogera ']));
        // pseudo:値指定で multiple で空文字ならその値になる
        $this->assertEquals('hoge', $input->normalize(['hoge' => '']));

        $input = new Input([
            'name'    => 'hoge',
            'phantom' => ['%s-%s', 'fuga', 'piyo'],
        ]);

        // phantom 値が入る
        $this->assertEquals('FUGA-PIYO', $input->normalize(['fuga' => 'FUGA', 'piyo' => 'PIYO']));
        // 一つでも空なら入らない
        $this->assertEquals('', $input->normalize(['fuga' => 'FUGA']));
    }

    function test_value_array()
    {
        $input = new Input([
            'inputs' => [
                'hoge' => [
                    'default' => 'def',
                ],
                'fuga' => [
                    'default' => 'def',
                ],
            ],
        ]);

        $value = $input->setValue([
            ['hoge' => 'hoge1'],
            ['fuga' => 'fuga2'],
        ]);

        $this->assertEquals([
            ['hoge' => 'hoge1', 'fuga' => 'def'],
            ['hoge' => 'def', 'fuga' => 'fuga2'],
        ], $value);

        $this->assertEquals([
            ['hoge' => 'hoge1', 'fuga' => 'def'],
            ['hoge' => 'def', 'fuga' => 'fuga2'],
        ], $input->getValue());

        $this->assertEquals('def', $input->context->hoge->getValue());
        $this->assertEquals('hoge1', $input->context->hoge->getValue(0));
        $this->assertEquals('def', $input->context->hoge->getValue(1));
        $this->assertEquals('def', $input->context->fuga->getValue());
        $this->assertEquals('def', $input->context->fuga->getValue(0));
        $this->assertEquals('fuga2', $input->context->fuga->getValue(1));
    }

    function test_message()
    {
        $rule = [
            'name'      => 'input',
            'title'     => '項目名',
            'condition' => [
                'Requires'     => null,
                'StringLength' => [2, 6]
            ],
            'message'   => [
                Requires::INVALID_TEXT  => 'm_INVALID_TEXT',
                StringLength::SHORTLONG => 'm_SHORTLONG'
            ]
        ];
        $input = new Input($rule);

        $values = [
            'input' => ''
        ];

        $input->validate($values, $values);
        $messages = $input->getMessages();
        $this->assertContains('m_INVALID_TEXT', $messages['Requires']);
    }

    function test_autocond()
    {
        $input = new Input([
            'condition' => [
                'EmailAddress' => null,
            ]
        ]);
        $this->assertContains('StringLength', array_keys($input->condition));

        $input = new Input([
            'condition' => [
                'EmailAddress' => null,
            ],
            'autocond'  => false,
        ]);
        $this->assertNotContains('StringLength', array_keys($input->condition));

        $input = new Input([
            'condition' => [
                'EmailAddress' => null,
            ],
            'options'   => [
                1 => 'on',
            ],
            'autocond'  => [
                'InArray'      => true,
                'StringLength' => false,
            ],
        ]);
        $this->assertContains('InArray', array_keys($input->condition));
        $this->assertNotContains('StringLength', array_keys($input->condition));

        $input = new class([
            'autocond' => true,
        ]) extends Input {
            public function _setAutoHoge()
            {
                $this->rule['condition']['hoge'] = 'called';
            }
        };
        $this->assertContains('hoge', array_keys($input->condition));
    }

    function test_multiple()
    {
        $input = new Input([
            'name'      => 'hoge',
            'condition' => [
                'Range' => [1, 5],
            ],
            'multiple'  => true,
        ]);

        // 普通にやれば普通に true のはず
        $values = ['hoge' => [1, 5]];
        $this->assertTrue($input->validate($values, $values));

        // 1つでもダメなのがあれば false のはず
        $values = ['hoge' => [1, 6]];
        $this->assertFalse($input->validate($values, $values));

        // 配列じゃないと例外が飛ぶはず
        $this->expectException(ValidationException::class);
        $values = ['hoge' => 3];
        $this->assertEquals(false, $input->validate($values, $values));
    }

    function test_detectType()
    {
        $rule = [
            'options' => ['' => '', 1 => '1']
        ];
        $input = new Input($rule);

        // 空キーを含む options 指定がされれば select になるはず
        $detectType = self::publishMethod($input, '_detectType');
        $this->assertEquals('select', $detectType());

        $rule = [
            'options' => ['' => '', 'group' => [1 => '1']]
        ];
        $input = new Input($rule);

        // 階層を含む options 指定がされれば select になるはず
        $detectType = self::publishMethod($input, '_detectType');
        $this->assertEquals('select', $detectType());

        $rule = [
            'options' => [1 => '1', 2 => '2'],
            'default' => [1, 2],
        ];
        $input = new Input($rule);

        // default が配列なら checkbox になるはず
        $detectType = self::publishMethod($input, '_detectType');
        $this->assertEquals('checkbox', $detectType());
    }

    function test_setAutoInArray()
    {
        $rule = [
            'condition' => [],
            'options'   => [
                1 => 'option.1'
            ],
            'default'   => [2, 3],
        ];
        $input = new Input($rule);
        $setAutoInArray = self::publishMethod($input, '_setAutoInArray');
        $setAutoInArray();

        // InArray が追加されているはず
        $this->assertInstanceOf(InArray::class, $input->condition['InArray']);
        // default がマージされているはず
        $this->assertEquals([1 => 0, 2 => 1, 3 => 2], $input->condition['InArray']->getValidationParam()['haystack']);

        $in_array = new InArray([1, 2, 3]);
        $rule = [
            'condition' => [
                $in_array
            ],
            'options'   => [
                1 => 'option.1'
            ]
        ];
        $input = new Input($rule);
        $setAutoInArray = self::publishMethod($input, '_setAutoInArray');
        $setAutoInArray();

        // 同じインスタンスのはず
        $this->assertEquals(spl_object_hash($in_array), spl_object_hash($input->condition[0]));
    }

    function test_getRange()
    {
        $rule = [
            'condition' => [
                'Range'   => [0, 999],
                'Decimal' => [2, 3],
                'Step'    => [0.5],
            ]
        ];
        $input = new Input($rule);

        $getRange = self::publishMethod($input, '_getRange');
        $range = $getRange();
        $this->assertEquals("0", $range['min']); // min は Range の 0
        $this->assertEquals("99.999", $range['max']); // max は Decimal の 99.999
        $this->assertEquals("0.5", $range['step']); // step は Step の 0.5
    }

    function test_getMaxlength()
    {
        $rule = [
            'condition' => [
                'EmailAddress' => null,
                'StringLength' => [
                    null,
                    20
                ]
            ]
        ];
        $input = new Input($rule);

        // EmailAddress(256) に負けずに 20 になるはず
        $getMaxlength = self::publishMethod($input, '_getMaxlength');
        $this->assertEquals(20, $getMaxlength());

        $rule = [
            'condition' => [
                'StringLength' => [
                    null,
                    20
                ],
                'EmailAddress' => null
            ]
        ];
        $input = new Input($rule);

        // 指定順は影響しないはず
        $getMaxlength = self::publishMethod($input, '_getMaxlength');
        $this->assertEquals(20, $getMaxlength());
    }

    function test_getImeMode()
    {
        // ime-mode 自体が無効は null
        $input = new Input([
            'ime-mode' => false
        ]);
        $getImeMode = self::publishMethod($input, '_getImeMode');
        $this->assertNull($getImeMode());

        // Regex は ImeMode を実装しないので null
        $input = new Input([
            'condition' => ['Regex' => '']
        ]);
        $getImeMode = self::publishMethod($input, '_getImeMode');
        $this->assertNull($getImeMode());

        // EmailAddress は disabled
        $input = new Input([
            'condition' => ['EmailAddress' => null]
        ]);
        $getImeMode = self::publishMethod($input, '_getImeMode');
        $this->assertEquals('disabled', $getImeMode());
    }

    function test_getDependent()
    {
        $rule = [
            'condition' => [
                'Requires' => 'depend1'
            ],
            'phantom'   => ['hoge%sfuga', 'depend2', 'depend3'],
            'dependent' => ['hoge', true],
        ];
        $input = new Input($rule);

        // Requires は依存性を持つし、phantom で指定したものも依存に追加される
        $this->assertEquals(['hoge', 'depend1', 'depend2', 'depend3'], $input->getDependent());

        $rule = [
            'condition' => [
                'Requires' => 'depend1'
            ],
            'phantom'   => ['hoge%sfuga', 'depend2', 'depend3'],
            'dependent' => [],
        ];
        $input = new Input($rule);

        // dependent を [] にすると自動蒐集は行われない
        $this->assertEquals([], $input->getDependent());
    }

    function test_validate()
    {
        $rule = [
            'name'      => 'input',
            'condition' => [
                'Requires'     => null,
                'StringLength' => [2, 6],
            ]
        ];
        $input = new Input($rule);

        $values = [
            'input' => ''
        ];

        $this->assertFalse($input->validate($values, $values));
        $this->assertCount(1, $input->getMessages());

        //-----------------------------------------------

        $values = [
            'input' => 'longlonglong'
        ];

        $this->assertFalse($input->validate($values, $values));
        $this->assertCount(1, $input->getMessages());

        //-----------------------------------------------

        $values = [
            'input' => 'short'
        ];

        $this->assertTrue($input->validate($values, $values));
        $this->assertCount(0, $input->getMessages());
    }

    function test_validate_array()
    {
        $rule = [
            'name'   => 'input',
            'inputs' => [
                'e1' => [
                    'condition' => [
                        'StringLength' => [2, 6],
                    ]
                ],
                'e2' => [
                    'condition' => [
                        'StringLength' => [1, 7],
                    ]
                ],
            ],
        ];
        $input = new Input($rule);

        $values = [
            'input' => [
                ['e1' => 'longlong', 'e2' => 'short'],
                ['e1' => 'short', 'e2' => 'longlong'],
            ]
        ];

        $this->assertFalse($input->validate($values, $values));
        $this->assertEquals([
            [
                'e1' => [
                    'StringLength' => [
                        'StringLengthInvalidMinMax' => '2文字～6文字で入力して下さい',
                    ]
                ]
            ],
            [
                'e2' => [
                    'StringLength' => [
                        'StringLengthInvalidMinMax' => '1文字～7文字で入力して下さい',
                    ]
                ]
            ],
        ], $input->getMessages());
    }

    function test_getValidationParams_class()
    {
        $input = new Input([
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
        ]);

        $rule = $input->getValidationRule();

        $this->assertEquals('Decimal', $rule['condition']['Decimal']['cname']);
        $this->assertEquals('Decimal', $rule['condition']['num']['cname']);
        $this->assertEquals('Decimal', $rule['condition']['Decimal(int:3,dec:3)']['cname']);
        $this->assertEquals('Decimal', $rule['condition']['0']['cname']);

        $this->assertEquals('1', $rule['condition']['Decimal']['param']['int']);
        $this->assertEquals('2', $rule['condition']['num']['param']['int']);
        $this->assertEquals('3', $rule['condition']['Decimal(int:3,dec:3)']['param']['int']);
        $this->assertEquals('4', $rule['condition']['0']['param']['int']);

        $this->assertEquals('hoge', $rule['condition']['Decimal']['message'][Decimal::INVALID]);
        $this->assertEquals('fuga', $rule['condition']['num']['message'][Decimal::INVALID]);
        $this->assertEquals('foo', $rule['condition']['Decimal(int:3,dec:3)']['message'][Decimal::INVALID]);
        $this->assertEquals('bar', $rule['condition']['Decimal(int:3,dec:3)']['message'][Decimal::INVALID_INT]);
        $this->assertEquals('piyo', $rule['condition']['0']['message'][Decimal::INVALID]);
    }

    function test_label()
    {
        $input = new Input([
            'name'    => 'input',
            'title'   => 'hogera',
            'options' => [
                1 => 'OK'
            ]
        ]);

        $this->assertAttribute([
            'label' => [
                [
                    'data-vlabel-id'    => 'input',
                    'data-vlabel-class' => 'input',
                    'data-vlabel-index' => '',
                    'for'               => 'input',
                    'class'             => 'validatable_label',
                ]
            ],
        ], $input->label());

        $this->assertStringContainsString('for="input-id"', $input->label(['for' => 'input-id']));
    }

    function test_label_context()
    {
        $input = new Input([
            'name'   => 'inputs',
            'title'  => 'parent',
            'inputs' => [
                'child' => [
                    'title' => 'child',
                ]
            ]
        ]);

        $prefix = spl_object_id($input->context);
        $this->assertAttribute([
            'label' => [
                [
                    'for'               => "cx{$prefix}_inputs-__index-child",
                    'data-vlabel-id'    => 'inputs/__index/child',
                    'data-vlabel-class' => 'inputs/child',
                    'data-vlabel-index' => '__index',
                    'class'             => 'validatable_label',
                ]
            ]
        ], $input->context->child->label());

        $this->assertStringContainsString('for="input-id"', $input->label(['for' => 'input-id']));
    }

    function test_input()
    {
        $input = new Input([
            'name'  => 'hoge',
            'title' => 'HOGE',
        ]);

        $this->assertAttribute([
            'input' => [
                [
                    'value'                 => 'new value',
                    'class'                 => 'klass1 klass2 validatable',
                    'style'                 => 'color:red',
                    'data-validation-title' => 'HOGE',
                    'data-vinput-id'        => 'hoge',
                    'data-vinput-class'     => 'hoge',
                    'data-vinput-index'     => '',
                    'name'                  => 'hoge',
                    'id'                    => 'hoge',
                    'type'                  => 'text',
                ]
            ]
        ], $input->input(['value' => 'new value', 'class' => 'klass1 klass2', 'style' => 'color:red']));
    }

    function test_input_attribute()
    {
        $input = new Input([
            'name'      => 'hoge',
            'attribute' => [
                'scalar' => 123,
                'string' => '"</script>',
                'array'  => [1, 2, 3],
                'hash'   => ['a' => 'A', 'b' => 'B', 'c' => 'C'],
            ]
        ]);

        $this->assertAttribute([
            'input' => [
                [
                    'scalar'                => '123',
                    'string'                => '"</script>',
                    'array'                 => '[1,2,3]',
                    'hash'                  => '{"a":"A","b":"B","c":"C"}',
                    'data-validation-title' => '',
                    'data-vinput-id'        => 'hoge',
                    'data-vinput-class'     => 'hoge',
                    'data-vinput-index'     => '',
                    'name'                  => 'hoge',
                    'id'                    => 'hoge',
                    'type'                  => 'text',
                    'class'                 => 'validatable',
                    'value'                 => '',
                ]
            ]
        ], $input->input([]));

        $this->assertAttribute([
            'input' => [
                [
                    'scalar'                => '',
                    'string'                => '',
                    'array'                 => '',
                    'hash'                  => '',
                    'data-validation-title' => '',
                    'data-vinput-id'        => 'hoge',
                    'data-vinput-class'     => 'hoge',
                    'data-vinput-index'     => '',
                    'name'                  => 'hoge',
                    'id'                    => 'hoge',
                    'type'                  => 'text',
                    'class'                 => 'validatable',
                    'value'                 => '',
                ]
            ]
        ], $input->input([
            'scalar' => null,
            'string' => null,
            'array'  => null,
            'hash'   => null,
        ]));

        $this->assertException(new \InvalidArgumentException('attribute requires hash array'), function () {
            new Input([
                'attribute' => ''
            ]);
        });
    }

    function test_input_multiple()
    {
        $input = new Input([
            'name'     => 'hoge',
            'multiple' => true,
        ]);
        $value = [1, 2, 3];
        $input->setValue($value);

        $this->assertStringContainsString('name="hoge[]" id="hoge_0" value="1"', $input->input());
        $this->assertStringContainsString('name="hoge[]" id="hoge_1" value="2"', $input->input());
        $this->assertStringContainsString('name="hoge[]" id="hoge_2" value="3"', $input->input());
    }

    function test_input_wrapper()
    {
        $input = new Input([
            'name'    => 'hoge',
            'wrapper' => 'input-class',
        ]);

        $this->assertStringContainsString('<span class="input-class input-text">', $input->input());
        $this->assertStringContainsString('<span class="hogera input-text">', $input->input(['wrapper' => 'hogera']));
    }

    function test_inputArrays()
    {
        $input = new Input([
            'name'   => 'inputs',
            'inputs' => [
                'child' => []
            ]
        ]);

        $this->assertAttribute([
            'input' => [
                [
                    'data-validation-title' => '',
                    'data-vinput-id'        => 'inputs',
                    'data-vinput-class'     => 'inputs',
                    'data-vinput-index'     => '',
                    'name'                  => '__inputs',
                    'id'                    => 'inputs',
                    'type'                  => 'dummy',
                    'value'                 => 'dummy',
                    'class'                 => 'validatable',
                    'style'                 => 'border:0px;width:1px;height:1px;visibility:hidden',
                ]
            ],
        ], $input->input());

        $this->assertAttribute([
            'input' => [
                [
                    'index'                 => '1',
                    'data-validation-title' => '',
                    'data-vinput-id'        => 'inputs',
                    'data-vinput-class'     => 'inputs',
                    'data-vinput-index'     => '',
                    'name'                  => '__inputs',
                    'id'                    => 'inputs',
                    'type'                  => 'dummy',
                    'value'                 => 'dummy',
                    'class'                 => 'validatable',
                    'style'                 => 'border:0px;width:1px;height:1px;visibility:hidden',
                ]
            ],
        ], $input->input([
            'index' => 1
        ]));

        $cx = spl_object_id($input->context);
        $this->assertAttribute([
            'input' => [
                [
                    'value'                 => '',
                    'id'                    => "cx{$cx}_inputs-__index-child",
                    'data-validation-title' => '',
                    'data-vinput-id'        => 'inputs/__index/child',
                    'data-vinput-class'     => 'inputs/child',
                    'data-vinput-index'     => '__index',
                    'disabled'              => 'disabled',
                    'name'                  => 'inputs[__index][child]',
                    'type'                  => 'text',
                    'class'                 => 'validatable',
                ]
            ],
        ], $input->context->child->input());
        $this->assertAttribute([
            'input' => [
                [
                    'id'                    => "cx{$cx}_inputs-1-child",
                    'value'                 => '',
                    'data-validation-title' => '',
                    'data-vinput-id'        => 'inputs/1/child',
                    'data-vinput-class'     => 'inputs/child',
                    'data-vinput-index'     => '1',
                    'name'                  => 'inputs[1][child]',
                    'type'                  => 'text',
                    'class'                 => 'validatable',
                ]
            ],
        ], $input->context->child->input([
            'index' => 1
        ]));
    }

    function test_inputCheckbox()
    {
        $input = new Input([
            'name'    => 'name',
            'options' => [
                1 => 'option.1'
            ],
            'pseudo'  => false
        ]);

        $this->assertAttribute([
            'input' => [
                [
                    'type'                  => 'checkbox',
                    'data-validation-title' => '',
                    'data-vinput-id'        => 'name',
                    'data-vinput-class'     => 'name',
                    'data-vinput-index'     => '',
                    'name'                  => 'name',
                    'class'                 => 'validatable',
                    'value'                 => '1',
                    'checked'               => 'checked',
                    'id'                    => 'name-1',
                ]
            ],
            'label' => [
                [
                    'for' => 'name-1',
                ]
            ]
        ], $input->input([
            'type' => 'checkbox'
        ]));

        $this->assertAttribute([
            'input' => [
                [
                    'type'                  => 'checkbox',
                    'data-validation-title' => '',
                    'data-vinput-id'        => 'name',
                    'data-vinput-class'     => 'name',
                    'data-vinput-index'     => '',
                    'name'                  => 'name',
                    'class'                 => 'validatable',
                    'value'                 => '99',
                    'id'                    => 'name-99',
                ]
            ],
            'label' => [
                [
                    'for' => 'name-99',
                ]
            ]
        ], $input->input([
            'type'    => 'checkbox',
            'options' => [
                '99' => 'hoge'
            ]
        ]));

        $this->assertAttribute([
            'input' => [
                [
                    'type'                  => 'checkbox',
                    'data-array'            => '',
                    'data-string'           => 'string',
                    'data-validation-title' => '',
                    'data-vinput-id'        => 'name',
                    'data-vinput-class'     => 'name',
                    'data-vinput-index'     => '',
                    'name'                  => 'name[]',
                    'id'                    => 'name-1',
                    'class'                 => 'validatable',
                    'value'                 => '1',
                    'checked'               => 'checked',
                ],
                [
                    'type'                  => 'checkbox',
                    'data-array'            => 'fuga-data',
                    'data-string'           => 'string',
                    'data-validation-title' => '',
                    'data-vinput-id'        => 'name',
                    'data-vinput-class'     => 'name',
                    'data-vinput-index'     => '',
                    'name'                  => 'name[]',
                    'class'                 => 'validatable',
                    'value'                 => '2',
                    'id'                    => 'name-2',
                ]
            ],
            'label' => [
                [
                    'for' => 'name-1',
                ],
                [
                    'for' => 'name-2',
                ]
            ]
        ], $input->input([
            'type'        => 'checkbox',
            'options'     => [
                '1' => 'hoge',
                '2' => 'fuga',
            ],
            'data-array'  => [
                '2' => 'fuga-data',
            ],
            'data-string' => 'string',
        ]));
    }

    function test_inputCheckbox_pseudo()
    {
        $input = new Input([
            'name'    => 'name',
            'options' => [
                0  => 'option.1',
                '' => 'option.2'
            ],
            'pseudo'  => true
        ]);

        $this->assertAttribute([
            'input' => [
                [
                    'type'  => 'hidden',
                    'name'  => 'name',
                    'value' => '',
                ],
                [
                    'type'                  => 'checkbox',
                    'data-validation-title' => '',
                    'data-vinput-id'        => 'name',
                    'data-vinput-class'     => 'name',
                    'data-vinput-index'     => '',
                    'name'                  => 'name[]',
                    'id'                    => 'name-0',
                    'class'                 => 'validatable',
                    'value'                 => '0',
                    'checked'               => 'checked',
                ],
                [
                    'type'                  => 'checkbox',
                    'data-validation-title' => '',
                    'data-vinput-id'        => 'name',
                    'data-vinput-class'     => 'name',
                    'data-vinput-index'     => '',
                    'name'                  => 'name[]',
                    'id'                    => 'name-',
                    'class'                 => 'validatable',
                    'value'                 => '',
                ]
            ],
            'label' => [
                [
                    'for' => 'name-0',
                ],
                [
                    'for' => 'name-',
                ]
            ]
        ], $input->input([
            'type' => 'checkbox',
        ]));
    }

    function test_inputCheckbox_strict()
    {
        $input = new Input([
            'name'    => 'name',
            'options' => [
                0  => 'option.1',
                '' => 'option.2'
            ],
            'pseudo'  => false
        ]);

        $this->assertAttribute([
            'input' => [
                [
                    'type'                  => 'checkbox',
                    'data-validation-title' => '',
                    'data-vinput-id'        => 'name',
                    'data-vinput-class'     => 'name',
                    'data-vinput-index'     => '',
                    'name'                  => 'name[]',
                    'id'                    => 'name-0',
                    'class'                 => 'validatable',
                    'value'                 => '0',
                    'checked'               => 'checked',
                ],
                [
                    'type'                  => 'checkbox',
                    'data-validation-title' => '',
                    'data-vinput-id'        => 'name',
                    'data-vinput-class'     => 'name',
                    'data-vinput-index'     => '',
                    'name'                  => 'name[]',
                    'id'                    => 'name-',
                    'class'                 => 'validatable',
                    'value'                 => '',
                ]
            ],
            'label' => [
                [
                    'for' => 'name-0',
                ],
                [
                    'for' => 'name-',
                ]
            ]
        ], $input->input([
            'type'  => 'checkbox',
            'value' => 0,
        ]));

        $this->assertAttribute([
            'input' => [
                [
                    'type'                  => 'checkbox',
                    'data-validation-title' => '',
                    'data-vinput-id'        => 'name',
                    'data-vinput-class'     => 'name',
                    'data-vinput-index'     => '',
                    'name'                  => 'name[]',
                    'id'                    => 'name-0',
                    'class'                 => 'validatable',
                    'value'                 => '0',
                ],
                [
                    'type'                  => 'checkbox',
                    'data-validation-title' => '',
                    'data-vinput-id'        => 'name',
                    'data-vinput-class'     => 'name',
                    'data-vinput-index'     => '',
                    'name'                  => 'name[]',
                    'id'                    => 'name-',
                    'class'                 => 'validatable',
                    'value'                 => '',
                    'checked'               => 'checked',
                ]
            ],
            'label' => [
                [
                    'for' => 'name-0',
                ],
                [
                    'for' => 'name-',
                ]
            ]
        ], $input->input([
            'type'  => 'checkbox',
            'value' => '',
        ]));
    }

    function test_inputCheckbox_multiple()
    {
        $input = new Input([
            'name'     => 'name',
            'options'  => [
                1 => 'option.1',
                2 => 'option.2'
            ],
            'multiple' => true,
            'pseudo'   => false
        ]);

        $this->assertAttribute([
            'input' => [
                [
                    'type'                  => 'checkbox',
                    'data-validation-title' => '',
                    'data-vinput-id'        => 'name',
                    'data-vinput-class'     => 'name',
                    'data-vinput-index'     => '',
                    'name'                  => 'name[]',
                    'id'                    => 'name-1',
                    'class'                 => 'validatable',
                    'value'                 => '1',
                ],
                [
                    'type'                  => 'checkbox',
                    'data-validation-title' => '',
                    'data-vinput-id'        => 'name',
                    'data-vinput-class'     => 'name',
                    'data-vinput-index'     => '',
                    'name'                  => 'name[]',
                    'id'                    => 'name-2',
                    'class'                 => 'validatable',
                    'value'                 => '2',
                    'checked'               => 'checked',
                ]
            ],
            'label' => [
                [
                    'for' => 'name-1',
                ],
                [
                    'for' => 'name-2',
                ]
            ]
        ], $input->input([
            'type'  => 'checkbox',
            'value' => '2'
        ]));

        $this->assertAttribute([
            'input' => [
                [
                    'type'                  => 'checkbox',
                    'data-validation-title' => '',
                    'data-vinput-id'        => 'name',
                    'data-vinput-class'     => 'name',
                    'data-vinput-index'     => '',
                    'name'                  => 'name[]',
                    'id'                    => 'name-98',
                    'class'                 => 'validatable',
                    'value'                 => '98',
                ],
                [
                    'type'                  => 'checkbox',
                    'data-validation-title' => '',
                    'data-vinput-id'        => 'name',
                    'data-vinput-class'     => 'name',
                    'data-vinput-index'     => '',
                    'name'                  => 'name[]',
                    'id'                    => 'name-99',
                    'class'                 => 'validatable',
                    'value'                 => '99',
                ]
            ],
            'label' => [
                [
                    'for' => 'name-98',
                ],
                [
                    'for' => 'name-99',
                ]
            ]
        ], $input->input([
            'type'    => 'checkbox',
            'options' => [
                '98' => 'hoge',
                '99' => 'fuga',
            ]
        ]));
    }

    function test_inputFile()
    {
        $input = new Input([
            'name' => 'name',
        ]);

        $this->assertAttribute([
            'input' => [
                [
                    'type'                  => 'file',
                    'data-validation-title' => '',
                    'data-vinput-id'        => 'name',
                    'data-vinput-class'     => 'name',
                    'data-vinput-index'     => '',
                    'name'                  => 'name',
                    'id'                    => 'name',
                    'class'                 => 'validatable',
                ],
            ],
        ], $input->input([
            'type' => 'file'
        ]));

        $input = new Input([
            'name'      => 'name',
            'condition' => [
                'FileType' => [['HTML' => ['html']]]
            ]
        ]);

        $this->assertAttribute([
            'input' => [
                [
                    'type'                  => 'file',
                    'data-validation-title' => '',
                    'data-vinput-id'        => 'name',
                    'data-vinput-class'     => 'name',
                    'data-vinput-index'     => '',
                    'name'                  => 'name',
                    'id'                    => 'name',
                    'class'                 => 'validatable',
                    'accept'                => '.html,text/html',
                ],
            ],
        ], $input->input([
            'type' => 'file'
        ]));
    }

    function test_inputFile_multiple()
    {
        $input = new Input([
            'name'     => 'name',
            'multiple' => true,
        ]);

        $this->assertAttribute([
            'input' => [
                [
                    'type'                  => 'file',
                    'data-validation-title' => '',
                    'data-vinput-id'        => 'name',
                    'data-vinput-class'     => 'name',
                    'data-vinput-index'     => '',
                    'name'                  => 'name[]',
                    'id'                    => 'name',
                    'multiple'              => 'multiple',
                    'class'                 => 'validatable',
                ],
            ],
        ], $input->input([
            'type' => 'file'
        ]));
    }

    function test_inputRadio()
    {
        $input = new Input([
            'name'    => 'name',
            'options' => [
                1 => 'option.1'
            ]
        ]);

        $this->assertAttribute([
            'input' => [
                [
                    'type'                  => 'radio',
                    'data-validation-title' => '',
                    'data-vinput-id'        => 'name',
                    'data-vinput-class'     => 'name',
                    'data-vinput-index'     => '',
                    'name'                  => 'name',
                    'class'                 => 'validatable',
                    'value'                 => '1',
                    'checked'               => 'checked',
                    'id'                    => 'name-1',
                ],
            ],
            'label' => [
                [
                    'for' => 'name-1',
                ]
            ],
        ], $input->input([
            'type' => 'radio'
        ]));

        $this->assertAttribute([
            'input' => [
                [
                    'type'                  => 'radio',
                    'data-validation-title' => '',
                    'data-vinput-id'        => 'name',
                    'data-vinput-class'     => 'name',
                    'data-vinput-index'     => '',
                    'name'                  => 'name',
                    'class'                 => 'validatable',
                    'value'                 => '99',
                    'id'                    => 'name-99',
                ],
            ],
            'label' => [
                [
                    'for' => 'name-99',
                ]
            ],
        ], $input->input([
            'type'    => 'radio',
            'options' => [
                '99' => 'hoge'
            ]
        ]));
    }

    function test_inputRadio_format()
    {
        $input = new Input([
            'name'    => 'name',
            'options' => [
                1 => 'option.1'
            ]
        ]);

        $this->assertAttribute([
            'input' => [
                [
                    'type'                  => 'radio',
                    'data-validation-title' => '',
                    'data-vinput-id'        => 'name',
                    'data-vinput-class'     => 'name',
                    'data-vinput-index'     => '',
                    'name'                  => 'name',
                    'class'                 => 'validatable',
                    'value'                 => '1',
                    'checked'               => 'checked',
                    'id'                    => 'name-1',
                ],
            ],
            'label' => [
                [
                    'for' => 'name-1',
                ]
            ],
        ], $input->input([
            'type'   => 'radio',
            'format' => 'hoge%sfuga'
        ]));
    }

    function test_inputSelect()
    {
        $input = new Input([
            'name'    => 'name',
            'options' => [
                1       => 'option.1',
                'group' => [
                    2 => 'group.1'
                ]
            ]
        ]);

        $this->assertAttribute([
            'select'   => [
                [
                    'data-validation-title' => '',
                    'data-vinput-id'        => 'name',
                    'data-vinput-class'     => 'name',
                    'data-vinput-index'     => '',
                    'name'                  => 'name',
                    'id'                    => 'name',
                    'class'                 => 'validatable',
                ],
            ],
            'option'   => [
                [
                    'selected' => 'selected',
                    'value'    => '1',
                ],
                [
                    'value' => '2',
                ],
            ],
            'optgroup' => [
                [
                    'label' => 'group',
                ]
            ],
        ], $input->input([
            'type' => 'select'
        ]));

        $this->assertAttribute([
            'select' => [
                [
                    'data-validation-title' => '',
                    'data-vinput-id'        => 'name',
                    'data-vinput-class'     => 'name',
                    'data-vinput-index'     => '',
                    'name'                  => 'name',
                    'id'                    => 'name',
                    'class'                 => 'validatable',
                ],
            ],
            'option' => [
                [
                    'value' => '99',
                ],
            ],
        ], $input->input([
            'type'    => 'select',
            'options' => [
                '99' => 'hoge'
            ]
        ]));
    }

    function test_inputSelect_pseudo()
    {
        $input = new Input([
            'name'     => 'name',
            'options'  => [
                1 => 'option.1',
                2 => 'option.2',
            ],
            'multiple' => true,
        ]);

        $this->assertAttribute([
            'input'  => [
                [
                    'type'  => 'hidden',
                    'name'  => 'name',
                    'value' => '',
                ]
            ],
            'select' => [
                [
                    'multiple'              => 'multiple',
                    'data-validation-title' => '',
                    'data-vinput-id'        => 'name',
                    'data-vinput-class'     => 'name',
                    'data-vinput-index'     => '',
                    'name'                  => 'name[]',
                    'id'                    => 'name',
                    'class'                 => 'validatable',
                ],
            ],
            'option' => [
                [
                    'value'    => '1',
                    'selected' => 'selected',
                ],
                [
                    'value' => '2',
                ],
            ],
        ], $input->input([
            'type' => 'select',
        ]));
    }

    function test_inputSelect_multiple()
    {
        $input = new Input([
            'name'     => 'name',
            'options'  => [
                1       => 'option.1',
                'group' => [
                    2 => 'group.1'
                ]
            ],
            'multiple' => true,
        ]);

        $this->assertAttribute([
            'select'   => [
                [
                    'multiple'              => 'multiple',
                    'data-validation-title' => '',
                    'data-vinput-id'        => 'name',
                    'data-vinput-class'     => 'name',
                    'data-vinput-index'     => '',
                    'name'                  => 'name[]',
                    'id'                    => 'name',
                    'class'                 => 'validatable',
                ],
            ],
            'option'   => [
                [
                    'value' => '1',
                ],
                [
                    'selected' => 'selected',
                    'value'    => '2',
                ],
            ],
            'optgroup' => [
                [
                    'label' => 'group',
                ]
            ],
        ], $input->input([
            'type'  => 'select',
            'value' => 2
        ]));
    }

    function test_inputSelect_strict()
    {
        $input = new Input([
            'name'    => 'name',
            'options' => [
                0       => 'option.1',
                'group' => [
                    '' => 'group.1'
                ]
            ]
        ]);

        $this->assertAttribute([
            'select'   => [
                [
                    'data-validation-title' => '',
                    'data-vinput-id'        => 'name',
                    'data-vinput-class'     => 'name',
                    'data-vinput-index'     => '',
                    'name'                  => 'name',
                    'id'                    => 'name',
                    'class'                 => 'validatable',
                ],
            ],
            'option'   => [
                [
                    'selected' => 'selected',
                    'value'    => '0',
                ],
                [
                    'value' => '',
                ],
            ],
            'optgroup' => [
                [
                    'label' => 'group',
                ]
            ],
        ], $input->input([
            'type'  => 'select',
            'value' => 0,
        ]));

        $this->assertAttribute([
            'select'   => [
                [
                    'data-validation-title' => '',
                    'data-vinput-id'        => 'name',
                    'data-vinput-class'     => 'name',
                    'data-vinput-index'     => '',
                    'name'                  => 'name',
                    'id'                    => 'name',
                    'class'                 => 'validatable',
                ],
            ],
            'option'   => [
                [
                    'value' => '0',
                ],
                [
                    'selected' => 'selected',
                    'value'    => '',
                ],
            ],
            'optgroup' => [
                [
                    'label' => 'group',
                ]
            ],
        ], $input->input([
            'type'  => 'select',
            'value' => '',
        ]));
    }

    function test_inputText()
    {
        $input = new Input([
            'name'      => 'name',
            'condition' => [
                'Date' => 'Y/m/d'
            ]
        ]);

        $this->assertAttribute([
            'input' => [
                [
                    'data-validation-title' => '',
                    'data-vinput-id'        => 'name',
                    'data-vinput-class'     => 'name',
                    'data-vinput-index'     => '',
                    'name'                  => 'name',
                    'id'                    => 'name',
                    'type'                  => 'text',
                    'class'                 => 'validatable',
                    'value'                 => '',
                    'maxlength'             => '10',
                    'style'                 => 'ime-mode:disabled;',
                ],
            ],
        ], $input->input());

        $input = new Input([
            'name'      => 'name',
            'condition' => [
                'Digits' => null,
                'Range'  => [
                    10,
                    20
                ]
            ]
        ]);

        $this->assertAttribute([
            'input' => [
                [
                    'data-validation-title' => '',
                    'data-vinput-id'        => 'name',
                    'data-vinput-class'     => 'name',
                    'data-vinput-index'     => '',
                    'name'                  => 'name',
                    'id'                    => 'name',
                    'type'                  => 'number',
                    'class'                 => 'validatable',
                    'value'                 => '',
                    'min'                   => '10',
                    'max'                   => '20',
                    'style'                 => 'ime-mode:disabled;',
                ],
            ],
        ], $input->input());

        $input = new Input([
            'name'      => 'name',
            'condition' => [
                'Decimal' => [
                    2,
                    4
                ]
            ]
        ]);

        $this->assertAttribute([
            'input' => [
                [
                    'data-validation-title' => '',
                    'data-vinput-id'        => 'name',
                    'data-vinput-class'     => 'name',
                    'data-vinput-index'     => '',
                    'name'                  => 'name',
                    'id'                    => 'name',
                    'type'                  => 'number',
                    'class'                 => 'validatable',
                    'value'                 => '',
                    'min'                   => '-99.9999',
                    'max'                   => '99.9999',
                    'step'                  => '0.0001',
                    'maxlength'             => '8',
                    'style'                 => 'ime-mode:disabled;',
                ],
            ],
        ], $input->input());

        // render 時の直接指定が勝つ
        $this->assertAttribute([
            'input' => [
                [
                    'min'                   => '-99',
                    'max'                   => '99',
                    'step'                  => '3',
                    'data-validation-title' => '',
                    'data-vinput-id'        => 'name',
                    'data-vinput-class'     => 'name',
                    'data-vinput-index'     => '',
                    'name'                  => 'name',
                    'id'                    => 'name',
                    'type'                  => 'number',
                    'class'                 => 'validatable',
                    'value'                 => '',
                    'maxlength'             => '8',
                    'style'                 => 'ime-mode:disabled;',
                ],
            ],
        ], $input->input([
            'min'  => '-99',
            'max'  => '99',
            'step' => '3',
        ]));
    }

    function test_inputTextarea()
    {
        $input = new Input([
            'name'      => 'name',
            'condition' => [
                'StringLength' => [
                    null,
                    1000
                ]
            ]
        ]);

        $this->assertAttribute([
            'textarea' => [
                [
                    'data-validation-title' => '',
                    'data-vinput-id'        => 'name',
                    'data-vinput-class'     => 'name',
                    'data-vinput-index'     => '',
                    'name'                  => 'name',
                    'id'                    => 'name',
                    'class'                 => 'validatable',
                    'maxlength'             => '1000',
                ],
            ],
        ], $input->input([
            'type' => 'textarea'
        ]));
    }
}

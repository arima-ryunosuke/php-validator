<?php
/** @noinspection CssUnknownProperty */
namespace ryunosuke\Test\UnitTest\chmonos;

use ryunosuke\chmonos\Condition\AbstractCondition;
use ryunosuke\chmonos\Condition\Decimal;
use ryunosuke\chmonos\Condition\InArray;
use ryunosuke\chmonos\Condition\NotInArray;
use ryunosuke\chmonos\Condition\Requires;
use ryunosuke\chmonos\Condition\StringLength;
use ryunosuke\chmonos\Context;
use ryunosuke\chmonos\Exception\ValidationException;
use ryunosuke\chmonos\Input;

class InputTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_setDefaultRule()
    {
        $default = Input::setDefaultRule([
            'wrapper'     => 'hoge',
            'subposition' => 'append',
        ]);
        $input = new Input([]);
        that($input)->wrapper->is('hoge');
        that($input)->subposition->is('append');

        Input::setDefaultRule($default);
    }

    function test___construct()
    {
        $rule = [
            'condition' => new Requires(),
            'propagate' => 'other'
        ];
        $input = new Input($rule);
        that($input)->condition->isArray();
        that($input)->propagate->isArray();

        $rule = [
            'condition' => [
                null,
            ],
            'options'   => [
                'group' => [
                    '1' => 'groupoption.1'
                ],
                '2'     => 'option.2',
            ]
        ];
        $input = new Input($rule);
        that($input)->condition->count(1);
        that($input)->default->is(1);
    }

    function test___construct_checkmode()
    {
        $input = new Input([
            'condition' => [
                $c1 = (new Decimal(3, 6))->setCheckMode(['server' => true, 'client' => false]),
                $c2 = new StringLength(3, 6),
            ],
            'checkmode' => [],
        ]);
        that($input)->checkmode->is([]);
        that($c1)->checkmode->is(['server' => true, 'client' => false]);
        that($c2)->checkmode->is(['server' => true, 'client' => true]);
    }

    function test___isset()
    {
        $input = new Input([]);
        that(isset($input->context))->isFalse();
        that(isset($input->condition))->isTrue();
        that(isset($input->hogera))->isFalse();

        $input = new Input([
            'inputs' => [
                'child' => [],
            ]
        ]);
        that(isset($input->context))->isTrue();
    }

    function test___get()
    {
        $input = new Input([]);
        that($input)->propagate->isArray();

        that($input)->hogera->isThrowable(new \InvalidArgumentException('undefined property'));
    }

    function test___set()
    {
        $input = new Input([
            'condition' => [
                'StringLength' => [2, 6]
            ],
            'inputs'    => [
                'child' => [
                    'title' => 'child',
                ]
            ],
        ]);

        that($input)->context->isNotNull();
        that($input)->condition->isNotEmpty();

        $input->context = null;
        $input->condition = [];
        $input->attribute = ['data-a' => 'A'];
        $input->event = ['mouseup'];

        that($input)->context->isNull();
        that($input)->condition->isEmpty();

        that($input)->attribute->is(['data-a' => 'A']);
        that($input)->event->is(['mouseup']);

        that($input)->try('__set', 'hogera', null)->wasThrown(new \InvalidArgumentException('undefined property'));
    }

    function test_resolve()
    {
        $context = new Context([
            'parent'   => [
                'title'   => '親項目',
                'options' => [
                    'a' => 'A',
                ],
            ],
            'children' => [
                'title'  => '子項目配列',
                'inputs' => [
                    'child1' => [
                        'title'   => '子項目1',
                        'options' => [
                            'c1' => 'C1',
                        ],
                    ],
                    'child2' => [
                        'title'   => '子項目2',
                        'options' => [
                            'c2' => 'C2',
                        ],
                    ]
                ]
            ]
        ]);
        $context->initialize();

        that($context)->parent->resolveTitle('parent')->is('親項目');
        that($context)->parent->resolveTitle('children/child1')->is('子項目1');
        that($context)->parent->resolveLabel('a')->is('A');

        that($context)->children->context->child1->resolveTitle('child2')->is('子項目2');
        that($context)->children->context->child1->resolveLabel('c1')->is('C1');
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

        that($context)->values->context->elem2->condition['Unique']->getValidationParam()->is([
            "root"   => "values",
            "name"   => "elem2",
            "strict" => true,
        ]);
    }

    function test_normalize()
    {
        $input = new Input([
            'name'     => 'hoge',
            'default'  => 'DDD',
            'pseudo'   => true,
            'multiple' => true,
            'trimming' => true,
            'nullable' => false,
        ]);

        // 無いならデフォルト
        that($input)->normalize([])->is('DDD');
        // null でもデフォルト
        that($input)->normalize(['hoge' => null])->is('DDD');
        // あるならその値
        that($input)->normalize(['hoge' => 'hogera'])->is('hogera');
        // trim される
        that($input)->normalize(['hoge' => ' hogera '])->is('hogera');
        // pseudo:true で multiple で空文字なら配列になる
        that($input)->normalize(['hoge' => ''])->is([]);

        $input = new Input([
            'name'     => 'hoge',
            'default'  => 'DDD',
            'pseudo'   => 'hoge',
            'multiple' => false,
            'trimming' => false,
            'nullable' => true,
        ]);

        // 無いならデフォルト
        that($input)->normalize([])->is('DDD');
        // null でも null
        that($input)->normalize(['hoge' => null])->is(null);
        // trim されない
        that($input)->normalize(['hoge' => ' hogera '])->is(' hogera ');
        // pseudo:値指定で multiple で空文字ならその値になる
        that($input)->normalize(['hoge' => ''])->is('hoge');

        $input = new Input([
            'name'    => 'hoge',
            'phantom' => ['%s-%s', 'fuga', 'piyo'],
        ]);

        // phantom 値が入る
        that($input)->normalize(['fuga' => 'FUGA', 'piyo' => 'PIYO'])->is('FUGA-PIYO');
        // 一つでも空なら入らない
        that($input)->normalize(['fuga' => 'FUGA'])->is('');
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

        that($value)->is([
            [
                "hoge" => "hoge1",
                "fuga" => "def",
            ],
            [
                "fuga" => "fuga2",
                "hoge" => "def",
            ],
        ]);

        that($input)->getValue()->is([
            [
                "hoge" => "hoge1",
                "fuga" => "def",
            ],
            [
                "fuga" => "fuga2",
                "hoge" => "def",
            ],
        ]);

        that($input)->context->hoge->getValue()->is('def');
        that($input)->context->hoge->getValue(0)->is('hoge1');
        that($input)->context->hoge->getValue(1)->is('def');
        that($input)->context->fuga->getValue()->is('def');
        that($input)->context->fuga->getValue(0)->is('def');
        that($input)->context->fuga->getValue(1)->is('fuga2');
    }

    function test_message()
    {
        $rule = [
            'name'      => 'input',
            'title'     => '項目名',
            'condition' => [
                'StringLength' => [2, 6]
            ],
            'options'   => [
                1 => 'hoge',
                2 => 'fuga',
            ],
            'message'   => [
                StringLength::SHORTLONG => 'm_SHORTLONG',
                InArray::NOT_IN_ARRAY   => 'm_NOT_IN_ARRAY'
            ]
        ];
        $input = new Input($rule);

        $values = [
            'input' => 'x'
        ];

        $input->validate($values, $values);
        $messages = $input->getMessages();
        that($messages)['StringLength']->contains('m_SHORTLONG');
        that($messages)['InArray']->contains('m_NOT_IN_ARRAY');
    }

    function test_autocond()
    {
        $input = new Input([
            'condition' => [
                'EmailAddress' => null,
            ]
        ]);
        that($input)->condition->hasKey('StringLength');

        $input = new Input([
            'condition' => [
                'EmailAddress' => null,
            ],
            'autocond'  => false,
        ]);
        that($input)->condition->notHasKey('StringLength');

        $input = new Input([
            'condition'             => [
                'EmailAddress' => null,
            ],
            'invalid-option-prefix' => "xxx-",
            'options'               => [
                1          => 'on',
                'optgroup' => [
                    0 => "xxx-off",
                ],
            ],
            'autocond'              => [
                'InArray'      => true,
                // 'NotInArray'   => true,// 未指定は true 扱い
                'StringLength' => false,
            ],
        ]);
        that($input)->condition->hasKey('InArray');
        that($input)->condition->hasKey('NotInArray');
        that($input)->condition->notHasKey('StringLength');
        that($input)->options->is([
            1          => 'on',
            'optgroup' => [
                0 => "off",
            ],
        ]);

        $input = new Input([
            'condition' => [
                'EmailAddress' => null,
            ],
            'options'   => [1 => 'on'],
            'autocond'  => fn(AbstractCondition $cond) => $cond->setValidationLevel('warning'),
        ]);
        that($input->condition['InArray'])->level->is('warning');
        that($input->condition['StringLength'])->level->is('warning');

        $input = new Input([
            'condition' => [
                'EmailAddress' => null,
            ],
            'options'   => [1 => 'on'],
            'autocond'  => [
                'InArray'      => fn(AbstractCondition $cond) => $cond->setValidationLevel('warning'),
                'StringLength' => true,
            ],
        ]);
        that($input->condition['InArray'])->level->is('warning');
        that($input->condition['StringLength'])->level->is('error');

        $input = new Input([
            'condition' => [
                'sl'  => $sl = new StringLength(),
                'ia'  => $ia = new InArray([]),
                'nia' => $nia = new NotInArray([]),
            ],
            'options'   => [
                1 => 'on',
                0 => "xxx-off",
            ],
        ]);
        that($input)->condition['sl']->isSame($sl);
        that($input)->condition['ia']->isSame($ia);
        that($input)->condition['nia']->isSame($nia);
        that($input)->options->is([
            1 => 'on',
            0 => "xxx-off",
        ]);

        $input = new class([
            'autocond' => true,
        ]) extends Input {
            public function _setAutoHoge()
            {
                $this->rule['condition']['hoge'] = 'called';
            }
        };
        that($input)->condition->hasKey('hoge');
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
        that($input)->validate($values, $values)->isTrue();

        // 1つでもダメなのがあれば false のはず
        $values = ['hoge' => [1, 6]];
        that($input)->validate($values, $values)->isFalse();

        // 配列じゃないと例外が飛ぶはず
        $values = ['hoge' => 3];
        that($input)->validate($values, $values)->wasThrown(ValidationException::class);
    }

    function test_detectType()
    {
        $rule = [
            'options' => ['' => '', 1 => '1']
        ];
        $input = new Input($rule);

        // 空キーを含む options 指定がされれば select になるはず
        that($input)->_detectType()->is('select');

        $rule = [
            'options' => ['' => '', 'group' => [1 => '1']]
        ];
        $input = new Input($rule);

        // 階層を含む options 指定がされれば select になるはず
        that($input)->_detectType()->is('select');

        $rule = [
            'options' => [1 => '1', 2 => '2'],
            'default' => [1, 2],
        ];
        $input = new Input($rule);

        // default が配列なら checkbox になるはず
        that($input)->_detectType()->is('checkbox');

        $rule = [
            'type'    => 'unknown',
            'options' => [1 => '1', 2 => '2'],
            'default' => [1, 2],
        ];
        $input = new Input($rule);

        // 明確に指定していればそれになるはず
        that($input)->_detectType()->is('unknown');
    }

    function test_setAutoStringLength()
    {
        $rule = [
            'condition' => [
                'EmailAddress' => null,
            ],
        ];
        $input = new Input($rule);
        that($input)->_setAutoStringLength()->isNull();

        // StringLength が追加されているはず
        that($input)->condition['StringLength']->isInstanceOf(StringLength::class);

        $string_length = new StringLength(null, 10);
        $rule = [
            'condition' => [
                'EmailAddress' => null,
                $string_length
            ],
        ];
        $input = new Input($rule);
        that($input)->_setAutoStringLength()->isNull();

        // 同じインスタンスのはず
        that(spl_object_hash($input->condition[0]))->is(spl_object_hash($string_length));
    }

    function test_setAutoInArray()
    {
        $rule = [
            'condition' => [],
            'options'   => [
                1 => 'option.1'
            ],
            'default'   => 2,
            'pseudo'    => 3,
        ];
        $input = new Input($rule);
        that($input)->_setAutoInArray()->isNull();

        // InArray が追加されているはず
        that($input)->condition['InArray']->isInstanceOf(InArray::class);
        // default がマージされているはず
        that($input)->condition['InArray']->getValidationParam()['haystack']->is([
            1 => 0,
            2 => 1,
            3 => 2,
        ]);

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
        that($input)->_setAutoInArray()->isNull();

        // 同じインスタンスのはず
        that(spl_object_hash($input->condition[0]))->is(spl_object_hash($in_array));
    }

    function test_setAutoDistinctDelimiter()
    {
        $rule = [
            'condition' => [
                'Hostname' => ['', false, '#,#'],
                'Distinct' => [],
            ],
        ];
        $input = new Input($rule);
        that($input)->_setAutoDistinctDelimiter()->isNull();

        // delimiter が設定されているはず
        that($input)->condition['Distinct']->getDelimiter()->is('#,#');

        $rule = [
            'condition' => [
                'Hostname' => ['', false, '#,#'],
                'Distinct' => ['/\n/'],
            ],
        ];
        $input = new Input($rule);
        that($input)->_setAutoDistinctDelimiter()->isNull();

        // 変わらないはず
        that($input)->condition['Distinct']->getDelimiter()->is('/\n/');

        $rule = [
            'condition' => [
                'Hostname' => [],
                'Distinct' => [],
            ],
        ];
        that(Input::class)->new($rule)->wasThrown('notfound delimiter');
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

        that($input)->_getRange()->is([
            'min'  => "0",      // min は Range の 0
            'max'  => "99.999", // max は Decimal の 99.999
            'step' => "0.5",    // step は Step の 0.5
        ]);
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
        that($input)->getDependent()->is(["hoge", "depend1", "depend2", "depend3"]);

        $rule = [
            'condition' => [
                'Requires' => 'depend1'
            ],
            'phantom'   => ['hoge%sfuga', 'depend2', 'depend3'],
            'dependent' => [],
        ];
        $input = new Input($rule);

        // dependent を [] にすると自動蒐集は行われない
        that($input)->getDependent()->is([]);
    }

    function test_getAjaxResponse()
    {
        $rule = [
            'condition' => [
                'Ajax' => ['url', [], function () { return 'hoge'; }],
            ],
        ];
        $input = new Input($rule);
        that($input)->getAjaxResponse()->is([
            "AjaxInvalid" => "hoge",
        ]);

        $rule = [
            'condition' => [
                'Ajax' => ['url', [], function () { return; }],
            ],
        ];
        $input = new Input($rule);
        that($input)->getAjaxResponse()->is(null);

        that(Input::class)->new(['condition' => []])->getAjaxResponse()->wasThrown('AjaxCondition is not found');
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

        that($input)->validate($values, $values)->isFalse();
        that($input)->getMessages()->count(1);

        //-----------------------------------------------

        $values = [
            'input' => 'longlonglong'
        ];

        that($input)->validate($values, $values)->isFalse();
        that($input)->getMessages()->count(1);

        //-----------------------------------------------

        $values = [
            'input' => 'short'
        ];

        that($input)->validate($values, $values)->isTrue();
        that($input)->getMessages()->count(0);
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

        that($input)->validate($values, $values)->isFalse();
        that($input)->getMessages()->is([
            [
                "e1" => [
                    "StringLength" => [
                        "StringLengthInvalidMinMax" => "2文字～6文字で入力して下さい",
                    ],
                ],
            ],
            [
                "e2" => [
                    "StringLength" => [
                        "StringLengthInvalidMinMax" => "1文字～7文字で入力して下さい",
                    ],
                ],
            ],
        ]);
    }

    function test_clear()
    {
        $rule = [
            'name'      => 'input',
            'condition' => [
                'Requires' => null,
            ],
        ];
        $input = new Input($rule);

        $values = [
            'input' => '',
        ];

        that($input)->validate($values, $values)->isFalse();
        that($input)->getMessages()->count(1);
        $input->clear();
        that($input)->getMessages()->count(0);
    }

    function test_getValidationParams_class()
    {
        $input = new Input([
            'condition' => [
                // 名前付き引数
                'Decimal' => ['dec' => 1, 'int' => 1],
                // インスタンス指定
                'num'     => (new Decimal(2, 2))->setMessageTemplates([Decimal::INVALID => 'foo']),
            ],
            'message'   => [
                'Decimal' => [
                    Decimal::INVALID => 'hoge'
                ],
                'num'     => [
                    Decimal::INVALID_INT => 'bar'
                ],
            ]
        ]);

        $rule = $input->getValidationRule();

        that($rule)['condition']['Decimal']['cname']->is('Decimal');
        that($rule)['condition']['num']['cname']->is('Decimal');

        that($rule)['condition']['Decimal']['param']['int']->is('1');
        that($rule)['condition']['num']['param']['int']->is('2');

        that($rule)['condition']['Decimal']['message'][Decimal::INVALID]->is('hoge');
        that($rule)['condition']['num']['message'][Decimal::INVALID]->is('foo');
        that($rule)['condition']['num']['message'][Decimal::INVALID_INT]->is('bar');
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

        that($input)->label()->htmlMatchesArray([
            'label' => [
                'data-vlabel-id'    => 'input',
                'data-vlabel-class' => 'input',
                'data-vlabel-index' => '',
                'for'               => 'input',
                'class'             => 'validatable_label',
            ],
        ]);

        that($input)->label(['for' => 'input-id'])->contains('for="input-id"');
        that($input)->label(['label' => 'specified-label'])->contains('specified-label');
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
        that($input)->context->child->label()->htmlMatchesArray([
            'label' => [
                'for'               => "cx{$prefix}_inputs-__index-child",
                'data-vlabel-id'    => 'inputs/__index/child',
                'data-vlabel-class' => 'inputs/child',
                'data-vlabel-index' => '__index',
                'class'             => 'validatable_label',
            ],
        ]);

        that($input)->label(['for' => 'input-id'])->contains('for="input-id"');
    }

    function test_input()
    {
        $input = new Input([
            'name'  => 'hoge',
            'title' => 'HOGE',
        ]);

        that($input)->input(['value' => 'new value', 'class' => 'klass1 klass2', 'style' => 'color:red'])->htmlMatchesArray([
            'input' => [
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
            ],
        ]);
    }

    function test_input_attribute()
    {
        $input = new Input([
            'name'      => 'hoge',
            'attribute' => [
                'scalar' => 123,
                'string' => '"</script>',
                'array'  => [1, 2, 3],
                'hash'   => (object) ['a' => 'A', 'b' => 'B', 'c' => 'C'],
            ],
            'needless'  => 'readonly',
        ]);

        that($input)->input([])->htmlMatchesArray([
            'input' => [
                'scalar'                => '123',
                'string'                => '"</script>',
                'array'                 => '1 2 3',
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
            ],
        ]);

        that($input)->input([
            'scalar' => null,
            'string' => null,
            'array'  => null,
            'hash'   => null,
        ])->htmlMatchesArray([
            'input' => [
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
            ],
        ]);

        that($input)->getValidationRule()['needless']->is([
            'readonly' => 'readonly',
        ]);

        that(Input::class)->new(['attribute' => ''])->wasThrown(new \InvalidArgumentException('attribute requires hash array'));
    }

    function test_input_multiple()
    {
        $input = new Input([
            'name'     => 'hoge',
            'multiple' => true,
        ]);
        $value = [1, 2, 3];
        $input->setValue($value);

        that($input)->input()->contains('name="hoge[]" id="hoge_0" value="1"');
        that($input)->input()->contains('name="hoge[]" id="hoge_1" value="2"');
        that($input)->input()->contains('name="hoge[]" id="hoge_2" value="3"');
    }

    function test_input_delimiter()
    {
        $input = new Input([
            'name'      => 'hoge',
            'delimiter' => ',',
        ]);

        $input->setValue('');
        that($input)->getValue()->is([]);

        $input->setValue('1,2,3');
        that($input)->getValue()->is(['1', '2', '3']);
    }

    function test_input_wrapper()
    {
        $input = new Input([
            'name'    => 'hoge',
            'wrapper' => 'input-class',
            'options' => [
                1 => 'foo',
            ],
        ]);

        that($input)->input(['type' => 'text'])->htmlMatchesArray([
            "span[1]" => [
                "data-vinput-wrapper" => "hoge",
                "data-value"          => "",
                "class"               => ["input-class", "input-text"],
                "input[1]"            => [
                    "type"                  => "text",
                    "data-validation-title" => "",
                    "data-vinput-id"        => "hoge",
                    "data-vinput-class"     => "hoge",
                    "data-vinput-index"     => "",
                    "name"                  => "hoge",
                    "id"                    => "hoge",
                    "class"                 => ["validatable"],
                    "value"                 => "1",
                ],
            ],
        ]);
        that($input)->input(['wrapper' => 'hogera'])->htmlMatchesArray([
            "body[1]" => [
                "input[1]" => [
                    "type"               => "hidden",
                    "name"               => "hoge",
                    "value"              => "",
                    "data-vinput-pseudo" => "true",
                ],
                "span[1]"  => [
                    "data-vinput-wrapper" => "hoge",
                    "data-value"          => "1",
                    "class"               => ["hogera", "input-checkbox"],
                    "input[1]"            => [
                        "data-validation-title" => "",
                        "data-vinput-id"        => "hoge",
                        "data-vinput-class"     => "hoge",
                        "data-vinput-index"     => "",
                        "name"                  => "hoge",
                        "id"                    => "hoge-1",
                        "class"                 => ["validatable"],
                        "type"                  => "checkbox",
                        "value"                 => "1",
                        "checked"               => "checked",
                    ],
                    "label[1]"            => [
                        "for" => "hoge-1",
                        0     => "foo",
                    ],
                ],
            ],
        ]);
        that($input)->input(['type' => 'radio'])->htmlMatchesArray([
            "span[1]" => [
                "data-vinput-wrapper" => "hoge",
                "data-value"          => "1",
                "class"               => ["input-class", "input-radio"],
                "input[1]"            => [
                    "type"                  => "radio",
                    "data-validation-title" => "",
                    "data-vinput-id"        => "hoge",
                    "data-vinput-class"     => "hoge",
                    "data-vinput-index"     => "",
                    "name"                  => "hoge",
                    "id"                    => "hoge-1",
                    "class"                 => ["validatable"],
                    "value"                 => "1",
                    "checked"               => "checked",
                ],
                "label[1]"            => [
                    "for" => "hoge-1",
                    0     => "foo",
                ],
            ],
        ]);
    }

    function test_input_grouper()
    {
        $input = new Input([
            'name'     => 'hoge',
            'grouper'  => 'input-group',
            'multiple' => true,
            'options'  => [
                1 => 'foo',
                2 => 'bar',
            ],
        ]);

        that($input)->input(['type' => 'text'])->htmlMatchesArray([
            "span[1]" => [
                "data-vinput-group" => "hoge[]",
                "class"             => ["input-group", "input-text"],
                "input[1]"          => [
                    "type"                  => "text",
                    "data-validation-title" => "",
                    "data-vinput-id"        => "hoge",
                    "data-vinput-class"     => "hoge",
                    "data-vinput-index"     => "",
                    "name"                  => "hoge[]",
                    "id"                    => "hoge_0",
                    "value"                 => "1",
                    "class"                 => ["validatable"],
                ],
            ],
        ]);
        that($input)->input(['type' => 'checkbox'])->htmlMatchesArray([
            "span[1]" => [
                "data-vinput-group" => "hoge[]",
                "class"             => ["input-group", "input-checkbox"],
                "input[1]"          => [
                    "type"               => "hidden",
                    "name"               => "hoge",
                    "value"              => "",
                    "data-vinput-pseudo" => "true",
                ],
                "input[2]"          => [
                    "type"                  => "checkbox",
                    "data-validation-title" => "",
                    "data-vinput-id"        => "hoge",
                    "data-vinput-class"     => "hoge",
                    "data-vinput-index"     => "",
                    "name"                  => "hoge[]",
                    "id"                    => "hoge-1",
                    "class"                 => ["validatable"],
                    "value"                 => "1",
                    "checked"               => "checked",
                ],
                "label[1]"          => [
                    "for" => "hoge-1",
                    0     => "foo",
                ],
                "input[3]"          => [
                    "type"                  => "checkbox",
                    "data-validation-title" => "",
                    "data-vinput-id"        => "hoge",
                    "data-vinput-class"     => "hoge",
                    "data-vinput-index"     => "",
                    "name"                  => "hoge[]",
                    "id"                    => "hoge-2",
                    "class"                 => ["validatable"],
                    "value"                 => "2",
                ],
                "label[2]"          => [
                    "for" => "hoge-2",
                    0     => "bar",
                ],
            ],
        ]);
        that($input)->input(['type' => 'radio'])->htmlMatchesArray([
            "span[1]" => [
                "data-vinput-group" => "hoge",
                "class"             => ["input-group", "input-radio"],
                "input[1]"          => [
                    "type"                  => "radio",
                    "data-validation-title" => "",
                    "data-vinput-id"        => "hoge",
                    "data-vinput-class"     => "hoge",
                    "data-vinput-index"     => "",
                    "name"                  => "hoge",
                    "id"                    => "hoge-1",
                    "class"                 => ["validatable"],
                    "value"                 => "1",
                    "checked"               => "checked",
                ],
                "label[1]"          => [
                    "for" => "hoge-1",
                    0     => "foo",
                ],
                "input[2]"          => [
                    "type"                  => "radio",
                    "data-validation-title" => "",
                    "data-vinput-id"        => "hoge",
                    "data-vinput-class"     => "hoge",
                    "data-vinput-index"     => "",
                    "name"                  => "hoge",
                    "id"                    => "hoge-2",
                    "class"                 => ["validatable"],
                    "value"                 => "2",
                ],
                "label[2]"          => [
                    "for" => "hoge-2",
                    0     => "bar",
                ],
            ],
        ]);
    }

    function test_input_datalist()
    {
        $input = new Input([
            'name'     => 'name',
            'datalist' => [
                '2014-12-23' => 'yesterday',
                '2014-12-24' => 'today',
                '2014-12-25' => 'tomorrow',
            ]
        ]);

        that($input)->input([
            'type' => 'date'
        ])->htmlMatchesArray([
            'input'    => [
                'data-validation-title' => '',
                'data-vinput-id'        => 'name',
                'data-vinput-class'     => 'name',
                'data-vinput-index'     => '',
                'name'                  => 'name',
                'id'                    => 'name',
                'class'                 => 'validatable',
                'type'                  => 'date',
                'list'                  => 'name-datalist',
                'value'                 => '',
            ],
            'datalist' => [
                "id"        => "name-datalist",
                "option[1]" => [
                    "value" => "2014-12-23",
                    "yesterday",
                ],
                "option[2]" => [
                    "value" => "2014-12-24",
                    "today",
                ],
                "option[3]" => [
                    "value" => "2014-12-25",
                    "tomorrow",
                ],
            ]
        ]);

        // InArray 系は設定されない
        that($input)->condition->isEmpty();
    }

    function test_inputArrays()
    {
        $input = new Input([
            'name'   => 'inputs',
            'inputs' => [
                'child' => []
            ]
        ]);

        that($input)->input()->htmlMatchesArray([
            'input' => [
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
            ],
        ]);

        that($input)->input(['index' => 1])->htmlMatchesArray([
            'input' => [
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
            ],
        ]);

        $cx = spl_object_id($input->context);
        that($input)->context->child->input()->htmlMatchesArray([
            'input' => [
                'value'                 => '',
                'id'                    => "cx{$cx}_inputs-__index-child",
                'data-validation-title' => '',
                'data-vinput-id'        => 'inputs/__index/child',
                'data-vinput-class'     => 'inputs/child',
                'data-vinput-index'     => '__index',
                'name'                  => 'inputs[__index][child]',
                'type'                  => 'text',
                'class'                 => 'validatable',
            ],
        ]);
        that($input)->context->child->input(['index' => 1])->htmlMatchesArray([
            'input' => [
                'id'                    => "cx{$cx}_inputs-1-child",
                'value'                 => '',
                'data-validation-title' => '',
                'data-vinput-id'        => 'inputs/1/child',
                'data-vinput-class'     => 'inputs/child',
                'data-vinput-index'     => '1',
                'name'                  => 'inputs[1][child]',
                'type'                  => 'text',
                'class'                 => 'validatable',
            ],
        ]);
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

        that($input)->input([
            'type'    => 'checkbox',
            'labeled' => 'left',
        ])->htmlMatchesArray([
            'label' => [
                'for' => 'name-1',
            ],
            'input' => [
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
            ],
        ]);

        that($input)->input([
            'type'    => 'checkbox',
            'labeled' => 'outer',
        ])->htmlMatchesArray([
            'label' => [
                'for'   => 'name-1',
                'input' => [
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
                ],
            ]
        ]);

        that($input)->input([
            'type'    => 'checkbox',
            'options' => [
                '99' => 'hoge'
            ]
        ])->htmlMatchesArray([
            'input' => [
                'type'                  => 'checkbox',
                'data-validation-title' => '',
                'data-vinput-id'        => 'name',
                'data-vinput-class'     => 'name',
                'data-vinput-index'     => '',
                'name'                  => 'name',
                'class'                 => 'validatable',
                'value'                 => '99',
                'id'                    => 'name-99',
            ],
            'label' => [
                'for' => 'name-99',
            ]
        ]);

        that($input)->input([
            'type'        => 'checkbox',
            'options'     => [
                '1' => 'hoge',
                '2' => 'fuga',
            ],
            'data-array'  => [
                '2' => 'fuga-data',
            ],
            'data-string' => 'string',
        ])->htmlMatchesArray([
            'input[1]' => [
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
            'label[1]' => [
                "for" => "name-1",
            ],
            'input[2]' => [
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
            ],
            'label[2]' => [
                "for" => "name-2",
            ],
        ]);
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

        that($input)->input([
            'type' => 'checkbox',
        ])->htmlMatchesArray([
            'input[1]' => [
                'type'               => 'hidden',
                'name'               => 'name',
                'value'              => '',
                'data-vinput-pseudo' => 'true',
            ],
            'input[2]' => [
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
            'label[1]' => [
                'for' => 'name-0',
            ],
            'input[3]' => [
                'type'                  => 'checkbox',
                'data-validation-title' => '',
                'data-vinput-id'        => 'name',
                'data-vinput-class'     => 'name',
                'data-vinput-index'     => '',
                'name'                  => 'name[]',
                'id'                    => 'name-',
                'class'                 => 'validatable',
                'value'                 => '',
            ],
            'label[2]' => [
                'for' => 'name-',
            ],
        ]);
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

        that($input)->input([
            'type'  => 'checkbox',
            'value' => 0,
        ])->htmlMatchesArray([
            'input[1]' => [
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
            'label[1]' => [
                'for' => 'name-0',
            ],
            'input[2]' => [
                'type'                  => 'checkbox',
                'data-validation-title' => '',
                'data-vinput-id'        => 'name',
                'data-vinput-class'     => 'name',
                'data-vinput-index'     => '',
                'name'                  => 'name[]',
                'id'                    => 'name-',
                'class'                 => 'validatable',
                'value'                 => '',
            ],
            'label[2]' => [
                'for' => 'name-',
            ],
        ]);

        that($input)->input([
            'type'  => 'checkbox',
            'value' => false,
        ])->htmlMatchesArray([
            'input[1]' => [
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
            'label[1]' => [
                'for' => 'name-0',
            ],
            'input[2]' => [
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
            ],
            'label[2]' => [
                'for' => 'name-',
            ],
        ]);
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

        that($input)->input([
            'type'  => 'checkbox',
            'value' => '2'
        ])->htmlMatchesArray([
            'input[1]' => [
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
            'label[1]' => [
                'for' => 'name-1',
            ],
            'input[2]' => [
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
            ],
            'label[2]' => [
                'for' => 'name-2',
            ],
        ]);

        that($input)->input([
            'type'    => 'checkbox',
            'options' => [
                '98' => 'hoge',
                '99' => 'fuga',
            ]
        ])->htmlMatchesArray([
            'input[1]' => [
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
            'label[1]' => [
                'for' => 'name-98',
            ],
            'input[2]' => [
                'type'                  => 'checkbox',
                'data-validation-title' => '',
                'data-vinput-id'        => 'name',
                'data-vinput-class'     => 'name',
                'data-vinput-index'     => '',
                'name'                  => 'name[]',
                'id'                    => 'name-99',
                'class'                 => 'validatable',
                'value'                 => '99',
            ],
            'label[2]' => [
                'for' => 'name-99',
            ],
        ]);
    }

    function test_inputFile()
    {
        $input = new Input([
            'name' => 'name',
        ]);

        that($input)->input([
            'type' => 'file'
        ])->htmlMatchesArray([
            'input' => [
                'type'                  => 'file',
                'data-validation-title' => '',
                'data-vinput-id'        => 'name',
                'data-vinput-class'     => 'name',
                'data-vinput-index'     => '',
                'name'                  => 'name',
                'id'                    => 'name',
                'class'                 => 'validatable',
            ],
        ]);

        $input = new Input([
            'name'      => 'name',
            'condition' => [
                'FileType' => [['HTML' => ['html']]]
            ]
        ]);

        that($input)->input([
            'type' => 'file'
        ])->htmlMatchesArray([
            'input' => [
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
        ]);
    }

    function test_inputFile_multiple()
    {
        $input = new Input([
            'name'     => 'name',
            'multiple' => true,
        ]);

        that($input)->input([
            'type' => 'file'
        ])->htmlMatchesArray([
            'input' => [
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
        ]);
    }

    function test_inputRadio()
    {
        $input = new Input([
            'name'    => 'name',
            'options' => [
                1 => 'option.1'
            ]
        ]);

        that($input)->input([
            'type' => 'radio'
        ])->htmlMatchesArray([
            'input' => [
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
            'label' => [
                'for' => 'name-1',
            ],
        ]);

        that($input)->input([
            'type'    => 'radio',
            'options' => [
                '99' => 'hoge'
            ]
        ])->htmlMatchesArray([
            'input' => [
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
            'label' => [
                'for' => 'name-99',
            ],
        ]);
    }

    function test_inputRadio_invalid()
    {
        $input = new Input([
            'name'     => 'name',
            'options'  => [
                1 => (object) [
                    'label'   => 'object.1',
                    'invalid' => false,
                ],
                2 => (object) [
                    'label'   => 'object.2',
                    'invalid' => true,
                ],
                3 => (object) [
                    'label' => 'object.3',
                ],
            ],
            'invalids' => [
                3 => 'invalid-option(invalid)',
            ],
        ]);

        that($input)->input([
            'type' => 'radio'
        ])->htmlMatchesArray([
            'input[1]' => [
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
            'label[1]' => [
                'for' => 'name-1',
            ],
            'input[2]' => [
                'type'                  => 'radio',
                'data-validation-title' => '',
                'data-vinput-id'        => 'name',
                'data-vinput-class'     => 'name',
                'data-vinput-index'     => '',
                'name'                  => 'name',
                'class'                 => 'validatable',
                'value'                 => '2',
                'id'                    => 'name-2',
            ],
            'label[2]' => [
                'for' => 'name-2',
            ],
            'input[3]' => [
                'type'                  => 'radio',
                'data-validation-title' => '',
                'data-vinput-id'        => 'name',
                'data-vinput-class'     => 'name',
                'data-vinput-index'     => '',
                'name'                  => 'name',
                'class'                 => ['validatable', 'validation_invalid'],
                'value'                 => '3',
                'id'                    => 'name-3',
            ],
            'label[3]' => [
                'for' => 'name-3',
                'invalid-option(invalid)',
            ],
        ]);

        that($input)->condition['NotInArray']->getValidationParam()['haystack']->is([
            2 => 0,
            3 => 1,
        ]);
    }

    function test_inputRadio_format()
    {
        $input = new Input([
            'name'    => 'name',
            'options' => [
                1 => 'option.1'
            ]
        ]);

        that($input)->input([
            'type'   => 'radio',
            'format' => 'hoge%sfuga'
        ])->htmlMatchesArray([
            'input' => [
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
            'label' => [
                'for' => 'name-1',
            ],
        ]);
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

        that($input)->input([
            'type' => 'select'
        ])->htmlMatchesArray([
            'select' => [
                'data-validation-title' => '',
                'data-vinput-id'        => 'name',
                'data-vinput-class'     => 'name',
                'data-vinput-index'     => '',
                'name'                  => 'name',
                'id'                    => 'name',
                'class'                 => 'validatable',

                'option' => [
                    'selected' => 'selected',
                    'value'    => '1',
                ],

                'optgroup' => [
                    'label'  => 'group',
                    'option' => [
                        'value' => '2',
                    ],
                ],
            ],
        ]);

        that($input)->input([
            'type'    => 'select',
            'options' => [
                '99' => 'hoge'
            ]
        ])->htmlMatchesArray([
            'select' => [
                'data-validation-title' => '',
                'data-vinput-id'        => 'name',
                'data-vinput-class'     => 'name',
                'data-vinput-index'     => '',
                'name'                  => 'name',
                'id'                    => 'name',
                'class'                 => 'validatable',

                'option' => [
                    'value' => '99',
                ],
            ],
        ]);
    }

    function test_inputSelect_invalid()
    {
        $input = new Input([
            'name'     => 'name',
            'options'  => [
                1       => (object) [
                    'label'   => 'object.1',
                    'invalid' => false,
                ],
                2       => (object) [
                    'label'   => 'object.2',
                    'invalid' => true,
                ],
                3       => (object) [
                    'label' => 'object.3',
                ],
                'group' => [
                    4 => (object) [
                        'label'   => 'object.4',
                        'invalid' => false,
                    ],
                ]
            ],
            'invalids' => [
                3 => 'invalid-option(invalid)',
            ],
        ]);

        that($input)->input([
            'type' => 'select'
        ])->htmlMatchesArray([
            'select' => [
                'data-validation-title' => '',
                'data-vinput-id'        => 'name',
                'data-vinput-class'     => 'name',
                'data-vinput-index'     => '',
                'name'                  => 'name',
                'id'                    => 'name',
                'class'                 => 'validatable',

                'option[1]' => [
                    'value' => '1',
                    'object.1',
                ],

                'option[2]' => [
                    'value' => '2',
                    'object.2',
                ],

                'option[3]' => [
                    'value' => '3',
                    'invalid-option(invalid)',
                ],

                'optgroup' => [
                    'label'  => 'group',
                    'option' => [
                        'value' => '4',
                        'object.4',
                    ],
                ],
            ],
        ]);

        that($input)->condition['NotInArray']->getValidationParam()['haystack']->is([
            2 => 0,
            3 => 1,
        ]);
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

        that($input)->input([
            'type' => 'select',
        ])->htmlMatchesArray([
            'input'  => [
                'type'               => 'hidden',
                'name'               => 'name',
                'value'              => '',
                'data-vinput-pseudo' => 'true',
            ],
            'select' => [
                'multiple'              => 'multiple',
                'data-validation-title' => '',
                'data-vinput-id'        => 'name',
                'data-vinput-class'     => 'name',
                'data-vinput-index'     => '',
                'name'                  => 'name[]',
                'id'                    => 'name',
                'class'                 => 'validatable',

                'option[1]' => [
                    'value'    => '1',
                    'selected' => 'selected',
                ],
                'option[2]' => [
                    'value' => '2',
                ],
            ],
        ]);
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

        that($input)->input([
            'type'  => 'select',
            'value' => 2
        ])->htmlMatchesArray([
            'select' => [
                'multiple'              => 'multiple',
                'data-validation-title' => '',
                'data-vinput-id'        => 'name',
                'data-vinput-class'     => 'name',
                'data-vinput-index'     => '',
                'name'                  => 'name[]',
                'id'                    => 'name',
                'class'                 => 'validatable',

                'option'   => [
                    'value' => '1',
                ],
                'optgroup' => [
                    'label'  => 'group',
                    'option' => [
                        'selected' => 'selected',
                        'value'    => '2',
                    ],
                ],
            ],
        ]);
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

        that($input)->input([
            'type'  => 'select',
            'value' => 0,
        ])->htmlMatchesArray([
            'select' => [
                'data-validation-title' => '',
                'data-vinput-id'        => 'name',
                'data-vinput-class'     => 'name',
                'data-vinput-index'     => '',
                'name'                  => 'name',
                'id'                    => 'name',
                'class'                 => 'validatable',

                'option'   => [
                    'selected' => 'selected',
                    'value'    => '0',
                ],
                'optgroup' => [
                    'label'  => 'group',
                    'option' => [
                        'value' => '',
                    ],
                ],
            ],
        ]);

        that($input)->input([
            'type'  => 'select',
            'value' => '',
        ])->htmlMatchesArray([
            'select' => [
                'data-validation-title' => '',
                'data-vinput-id'        => 'name',
                'data-vinput-class'     => 'name',
                'data-vinput-index'     => '',
                'name'                  => 'name',
                'id'                    => 'name',
                'class'                 => 'validatable',

                'option'   => [
                    'value' => '0',
                ],
                'optgroup' => [
                    'label'  => 'group',
                    'option' => [
                        'selected' => 'selected',
                        'value'    => '',
                    ],
                ],
            ],
        ]);
    }

    function test_inputText()
    {
        $input = new Input([
            'name'      => 'name',
            'condition' => [
                'Date' => 'Y/m/d'
            ]
        ]);

        that($input)->input()->htmlMatchesArray([
            'input' => [
                'data-validation-title' => '',
                'data-vinput-id'        => 'name',
                'data-vinput-class'     => 'name',
                'data-vinput-index'     => '',
                'name'                  => 'name',
                'id'                    => 'name',
                'type'                  => 'text',
                'class'                 => 'validatable',
                'value'                 => '',
            ],
        ]);

        $input = new Input([
            'name'      => 'name',
            'condition' => [
                'Date' => 'Y-m-d\TH:i:s'
            ]
        ]);

        that($input)->input()->htmlMatchesArray([
            'input' => [
                'data-validation-title' => '',
                'data-vinput-id'        => 'name',
                'data-vinput-class'     => 'name',
                'data-vinput-index'     => '',
                'name'                  => 'name',
                'id'                    => 'name',
                'type'                  => 'datetime-local',
                'class'                 => 'validatable',
                'value'                 => '',
                'min'                   => '1000-01-01T00:00:00',
                'max'                   => '9999-12-31T23:59:59',
                'step'                  => '1',
            ],
        ]);

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

        that($input)->input()->htmlMatchesArray([
            'input' => [
                'data-validation-title' => '',
                'data-vinput-id'        => 'name',
                'data-vinput-class'     => 'name',
                'data-vinput-index'     => '',
                'name'                  => 'name',
                'id'                    => 'name',
                'type'                  => 'text',
                'class'                 => 'validatable',
                'value'                 => '',
                'min'                   => '10',
                'max'                   => '20',
            ],
        ]);

        $input = new Input([
            'name'      => 'name',
            'condition' => [
                'Decimal' => [
                    2,
                    4
                ]
            ]
        ]);

        that($input)->input()->htmlMatchesArray([
            'input' => [
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
            ],
        ]);

        // render 時の直接指定が勝つ
        that($input)->input([
            'min'  => '-99',
            'max'  => '99',
            'step' => '3',
        ])->htmlMatchesArray([
            'input' => [
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
            ],
        ]);
    }

    function test_inputTextarea()
    {
        $input = new Input([
            'name'      => 'name',
            'condition' => [
                'StringLength' => [
                    null,
                    1000,
                    false,
                ]
            ]
        ]);

        that($input)->input([
            'type' => 'textarea'
        ])->htmlMatchesArray([
            'textarea' => [
                'data-validation-title' => '',
                'data-vinput-id'        => 'name',
                'data-vinput-class'     => 'name',
                'data-vinput-index'     => '',
                'name'                  => 'name',
                'id'                    => 'name',
                'class'                 => 'validatable',
            ],
        ]);
    }
}

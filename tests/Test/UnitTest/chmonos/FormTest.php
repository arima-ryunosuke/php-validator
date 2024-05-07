<?php
namespace ryunosuke\Test\UnitTest\chmonos;

use ryunosuke\chmonos\Condition;
use ryunosuke\chmonos\Context;
use ryunosuke\chmonos\Exception\ValidationException;
use ryunosuke\chmonos\Form;
use ryunosuke\chmonos\Input;
use ryunosuke\Test\CustomInput;

class FormTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test___construct()
    {
        $form = new Form([
            'hoge_input' => []
        ], [
            'inputClass' => CustomInput::class
        ]);

        that($form)->hoge_input->isInstanceOf(Input::class);
        that($form)->context->isInstanceOf(Context::class);
    }

    function test___isset()
    {
        $form = new Form([
            'hoge_input' => []
        ]);

        that(isset($form->hoge_input))->isTrue();
        that(isset($form->context))->isFalse();
        that(isset($form->undefined))->isFalse();
    }

    function test___get()
    {
        $form = new Form([
            'hoge_input' => []
        ]);

        that($form)->hoge_input->isInstanceOf(Input::class);
        that($form)->context->isInstanceOf(Context::class);
    }

    function test_setValues_getValues()
    {
        $form = new Form([
            'req' => [
                'condition' => [
                    'Requires' => null
                ]
            ],
            'dt'  => [
                'condition' => [
                    'Date' => 'Y-m-d\TH:i'
                ]
            ],
        ]);

        $values = $form->setValues([
            'req' => '  hoge  ',
            'dt'  => '2009-02-14 08:31:30',
            'foo' => 'bar',
        ]);

        that($values)->is($form->getValues());

        that($values)->isArray()->hasKey('req')->hasKey('dt')->notHasKey('foo');
        that($values)['req']->is('hoge');
        that($values)['dt']->is('2009-02-14T08:31');
    }

    function test_setValues_getValues_file()
    {
        $default = [
            'name'     => '',
            'type'     => '',
            'tmp_name' => __FILE__,
            'error'    => UPLOAD_ERR_NO_FILE,
            'size'     => 0,
        ];
        $form = new Form([
            'file' => [
                'condition' => [
                    'FileSize' => 9999,
                ],
                'default'   => 'nofile',
            ]
        ]);

        $_FILES = [];
        $values = $form->setValues(['file' => 'specified']);
        that($values)->is($form->getValues());
        that($values)['file']->is("nofile");

        $_FILES = ['file' => ['error' => UPLOAD_ERR_OK] + $default];
        $values = $form->setValues(['file' => 'specified']);
        that($form->getValues(false))->is(['file' => null]);
        that($form->getValues(true))->is($values);
        that($values)['file']->is(__FILE__);

        $_FILES = ['file' => ['error' => UPLOAD_ERR_OK] + $default];
        $values = $form->setValues([]);
        that($form->getValues(false))->is(['file' => null]);
        that($form->getValues(true))->is($values);
        that($values)['file']->is(__FILE__);

        $_FILES = ['file' => ['error' => UPLOAD_ERR_NO_FILE] + $default];
        $values = $form->setValues([]);
        that($form->getValues(false))->is(['file' => "nofile"]);
        that($form->getValues(true))->is($values);
        that($values)['file']->is("nofile");

        $_FILES = ['file' => ['error' => UPLOAD_ERR_INI_SIZE] + $default];
        that($form)->setValues([])->wasThrown(new \UnexpectedValueException('file size too large', UPLOAD_ERR_INI_SIZE));

        $_FILES = ['file' => ['error' => 999] + $default];
        that($form)->setValues([])->wasThrown(new \UnexpectedValueException('upload error', 999));

        $_FILES = ['file' => ['error' => UPLOAD_ERR_OK, 'tmp_name' => 'not_uploaded_file'] + $default];
        that($form)->setValues([])->wasThrown(new \UnexpectedValueException('file is not uploaded'));
    }

    function test_withFile()
    {
        $form = new Form([
            'file' => [
                'condition' => [
                    'FileSize' => 1234
                ]
            ]
        ]);

        $form_tag = $form->form([]) . 'dummy' . $form->form();

        // multipart/form-data な post になるはず
        that($form_tag)->contains('method="post"');
        that($form_tag)->contains('enctype="multipart/form-data"');
    }

    function test_withDelimiter()
    {
        $form = new Form([
            'text' => [
                'delimiter' => ',',
            ]
        ]);

        $form_tag = $form->form([]) . 'dummy' . $form->form();

        // array=delimitable な application/x-www-form-urlencodeds になるはず
        that($form_tag)->contains('enctype="application/x-www-form-urlencodeds;array=delimitable"');
    }

    function test_validate()
    {
        $form = new Form([
            'req' => [
                'condition' => [
                    'Requires' => null
                ]
            ]
        ]);

        $values = [
            'req' => '  hoge  ',
            'foo' => 'bar',
        ];
        $form->validate($values);

        that($values)->isArray()->hasKey('req')->notHasKey('foo');
        that($values)['req']->is("hoge");
    }

    function test_validate_warning()
    {
        $form = new Form([
            'hoge' => [
                'condition' => [
                    (new Condition\Requires())->setValidationLevel('warning'),
                ]
            ],
            'fuga' => [
                'condition' => [
                    (new Condition\Requires())->setValidationLevel('error'),
                ]
            ]
        ]);

        $values = [
            'hoge' => '',
            'fuga' => '',
        ];
        $form->validate($values);

        $messages = $form->getMessages();
        that($messages)->count(1);
        that($messages)->notHasKey('hoge');
        that($messages)->hasKey('fuga');
    }

    function test_validate_ignore()
    {
        $form = new Form([
            'parent'         => [],
            '@parent_ignore' => [],
            'children'       => [
                'inputs' => [
                    'child'         => [],
                    '@child_ignore' => [],
                ],
            ]
        ]);

        $values = [
            'parent'        => 'hoge',
            'parent_ignore' => 'hoge',
            'children'      => [
                [
                    'child'        => 'fuga1',
                    'child_ignore' => 'fuga1',
                ],
                [
                    'child'        => 'fuga2',
                    'child_ignore' => 'fuga2',
                ],
            ],
        ];
        $form->validate($values);
        that($values)->is([
            "parent"   => "hoge",
            "children" => [
                [
                    "child" => "fuga1",
                ],
                [
                    "child" => "fuga2",
                ],
            ],
        ]);
    }

    function test_validate_files()
    {
        file_put_contents($parent_file = tempnam(sys_get_temp_dir(), 'tmp'), '');
        file_put_contents($multiple1_file = tempnam(sys_get_temp_dir(), 'tmp'), '');
        file_put_contents($multiple2_file = tempnam(sys_get_temp_dir(), 'tmp'), '');
        file_put_contents($child_file0 = tempnam(sys_get_temp_dir(), 'tmp'), '');
        file_put_contents($child_file1 = tempnam(sys_get_temp_dir(), 'tmp'), '');
        require_once $parent_file;
        require_once $multiple1_file;
        require_once $multiple2_file;
        require_once $child_file0;
        require_once $child_file1;

        $_FILES = [
            'parent_file'   => [
                'name'     => 'local/parent_file',
                'type'     => 'text/plain',
                'error'    => UPLOAD_ERR_OK,
                'size'     => 999,
                'tmp_name' => $parent_file,
            ],
            'multiple_file' => [
                'name'     => ['local/multiple1_file', 'local/multiple2_file'],
                'type'     => ['text/plain', 'text/plain'],
                'error'    => [UPLOAD_ERR_OK, UPLOAD_ERR_OK],
                'size'     => [999, 999],
                'tmp_name' => [$multiple1_file, $multiple2_file],
            ],
            'children'      => [
                'name'     => [
                    ['child_file' => 'local/child_file0'],
                    ['child_file' => 'local/child_file1'],
                ],
                'type'     => [
                    ['child_file' => 'text/plain'],
                    ['child_file' => 'text/plain'],
                ],
                'error'    => [
                    ['child_file' => UPLOAD_ERR_OK],
                    ['child_file' => UPLOAD_ERR_OK],
                ],
                'size'     => [
                    ['child_file' => 999],
                    ['child_file' => 999],
                ],
                'tmp_name' => [
                    ['child_file' => $child_file0],
                    ['child_file' => $child_file1],
                ],
            ],
        ];

        $form = new Form([
            'parent_text'   => [],
            'parent_file'   => [
                'condition' => [
                    'FileSize' => 9999,
                ],
            ],
            'multiple_file' => [
                'condition' => [
                    'FileSize' => 9999,
                ],
                'multiple'  => true,
            ],
            'children'      => [
                'inputs' => [
                    'child_text' => [],
                    'child_file' => [
                        'condition' => [
                            'FileSize' => 9999,
                        ],
                    ],
                ],
            ]
        ]);
        $posts = [
            'parent_text' => 'parent_text',
            'children'    => [
                [
                    'child_text' => 'child_text0'
                ],
                [
                    'child_text' => 'child_text1'
                ],
            ]
        ];
        @$form->validate($posts);

        that($posts)['parent_text']->is("parent_text");
        that($posts)['parent_file']->is($parent_file);

        that($posts)['multiple_file'][0]->is($multiple1_file);
        that($posts)['multiple_file'][1]->is($multiple2_file);

        that($posts)['children'][0]['child_text']->is("child_text0");
        that($posts)['children'][0]['child_file']->is($child_file0);
        that($posts)['children'][1]['child_text']->is("child_text1");
        that($posts)['children'][1]['child_file']->is($child_file1);
    }

    function test_validate_files_missing()
    {
        $form = new Form([
            'text' => [],
            'file' => [
                'condition' => [
                    'FileSize' => 9999,
                ],
            ],
        ]);
        $posts = ['text' => 'text',];
        $form->validate($posts);

        that($posts)['file']->is("");
    }

    function test_validateOrFilter()
    {
        $form = new Form([
            'parent'    => [
                'condition' => [
                    'Requires' => null
                ]
            ],
            'children1' => [
                'condition' => [
                    'Requires' => null
                ],
                'inputs'    => [
                    'child1' => [
                        'condition' => [
                            'Requires' => null
                        ]
                    ],
                    'child2' => [
                        'condition' => [
                            'Requires' => null
                        ]
                    ]
                ]
            ],
            'children2' => [
                'inputs' => [
                    'child1' => [
                        'condition' => [
                            'Requires' => null
                        ]
                    ],
                    'child2' => [
                        'condition' => [
                            'Requires' => null
                        ]
                    ]
                ]
            ]
        ]);

        that($form)->filter([
            'parent'    => '',
            'children1' => [],
            'children2' => [
                [
                    'child1' => '',
                    'child2' => '',
                ]
            ]
        ])->is([
            "children2" => [],
        ]);
        that($form)->getMessages()->isEmpty();

        that($form)->filter([
            'parent'    => 'val',
            'children1' => [
                [
                    'child1' => 'val',
                    'child2' => '',
                ]
            ],
            'children2' => [
                [
                    'child1' => 'val',
                    'child2' => '',
                ]
            ]
        ])->is([
            "parent"    => "val",
            "children1" => [
                [
                    "child1" => "val",
                ],
            ],
            "children2" => [
                [
                    "child1" => "val",
                ],
            ],
        ]);
        that($form)->getMessages()->isEmpty();
    }

    function test_validateOrThrow_ng()
    {
        $form = new Form([
            'req' => [
                'condition' => [
                    'Requires' => null
                ]
            ]
        ]);

        try {
            $form->validateOrThrow([]);
        }
        catch (ValidationException $ex) {
            that($ex)->getForm()->isSame($form);
            that($ex)->getForm()->getFlatMessages('%s%s', '')->contains('入力必須です');
        }
    }

    function test_validateOrThrow_ok()
    {
        $form = new Form([
            'req' => [
                'condition' => [
                    'Requires' => null
                ]
            ],
            'dmy' => [
                'default' => 'dmy'
            ]
        ]);

        $values = $form->validateOrThrow([
            'req' => '  hoge  ',
            'foo' => 'bar',
        ]);

        // req はトリミングされているはずだし
        that($values)['req']->is("hoge");
        // dmy はデフォルト値が設定されているはずだし
        that($values)['dmy']->is("dmy");
        // foo はフィルタリングされて存在しないはず
        that($values)->notHasKey("foo");
    }

    function test_form()
    {
        $form = new Form([
            'parent'   => [
                'title' => 'parent-title'
            ],
            'children' => [
                'default' => [],
                'inputs'  => [
                    'child1' => [
                        'default' => 'def1'
                    ],
                    'child2' => [
                        'default'  => 'def2',
                        'multiple' => true,
                    ]
                ]
            ]
        ], [
            'tokenName' => 'hoge',
            'nonce'     => 'fuga',
        ]);

        $form->setValues([
            'parent'   => 'hoge',
            'children' => [
                [
                    'child1' => 'child1_hoge',
                    'child2' => 'child2_hoge',
                ],
                [
                    'child1' => 'child1_fuga',
                ]
            ]
        ]);

        // 開始タグ
        {
            $content = $form->open(['id' => 'hoge']);

            // form 開始タグから始まる
            that($content)->stringStartsWith('<form');
            // id が設定されている
            that($content)->stringContains('id="hoge"');
            // nonce がある
            that($content)->stringContains('nonce="fuga"');
        }

        $prefix = spl_object_id($form->context);

        // 通常 input
        {
            // label が描画できる
            that($form)->label('parent')->htmlMatchesArray([
                'label' => [
                    'for'               => "cx{$prefix}_parent",
                    'data-vlabel-id'    => 'parent',
                    'data-vlabel-class' => 'parent',
                    'data-vlabel-index' => '',
                    'class'             => 'validatable_label',
                ],
            ]);

            // input が描画できる
            that($form)->input('parent')->htmlMatchesArray([
                'input' => [
                    'id'                    => "cx{$prefix}_parent",
                    'data-validation-title' => 'parent-title',
                    'data-vinput-id'        => 'parent',
                    'data-vinput-class'     => 'parent',
                    'data-vinput-index'     => '',
                    'name'                  => 'parent',
                    'type'                  => 'text',
                    'class'                 => 'validatable',
                    'value'                 => 'hoge',
                ],
            ]);

            // label が描画できる
            that($form)->label('parent', ['vuejs' => true])->htmlMatchesArray([
                'label' => [
                    ':for'               => "'cx{$prefix}_parent'",
                    ':data-vlabel-id'    => "'parent'",
                    ':data-vlabel-class' => "'parent'",
                    ':data-vlabel-index' => "''",
                    'class'              => 'validatable_label',
                ],
            ]);

            // input が描画できる
            that($form)->input('parent', ['type' => 'number', 'vuejs' => true])->htmlMatchesArray([
                'input' => [
                    ':id'                   => "'cx{$prefix}_'+'parent'",
                    ':data-vinput-id'       => "'parent'",
                    ':data-vinput-class'    => "'parent'",
                    ':data-vinput-index'    => "''",
                    ':name'                 => "'parent'",
                    'v-model.number'        => 'parent',
                    'data-validation-title' => 'parent-title',
                    'type'                  => 'number',
                    'class'                 => 'validatable',
                ],
            ]);

            // vue に任せるものは伏せられる
            that($form)->input('parent', ['type' => 'text', 'value' => 'hoge', 'vuejs' => false])->htmlMatchesArray([
                'input' => [
                    "value" => "hoge",
                ],
            ]);
            that($form)->input('parent', ['type' => 'text', 'value' => 'hoge', 'vuejs' => true])->htmlMatchesArray([
                'input' => [
                    "value" => "",
                ],
            ]);
            that($form)->input('parent', ['type' => 'checkbox', 'value' => 'hoge', 'options' => ['hoge' => 'HOGE'], 'vuejs' => false])->htmlMatchesArray([
                'input[2]' => [
                    "value"   => "hoge",
                    "checked" => "checked",
                ],
            ]);
            that($form)->input('parent', ['type' => 'checkbox', 'value' => 'hoge', 'options' => ['hoge' => 'HOGE'], 'vuejs' => true])->htmlMatchesArray([
                'input[2]' => [
                    "value"   => "hoge",
                    "checked" => null,
                ],
            ]);
            that($form)->input('parent', ['type' => 'select', 'value' => 'hoge', 'options' => ['hoge' => 'HOGE'], 'vuejs' => false])->htmlMatchesArray([
                'select' => [
                    'option' => [
                        "selected" => "selected",
                        "value"    => "hoge",
                    ],
                ],
            ]);
            that($form)->input('parent', ['type' => 'select', 'value' => 'hoge', 'options' => ['hoge' => 'HOGE'], 'vuejs' => true])->htmlMatchesArray([
                'select' => [
                    'option' => [
                        "selected" => null,
                        "value"    => "hoge",
                    ],
                ],
            ]);
            that($form)->input('parent', ['type' => 'textarea', 'value' => 'hoge', 'vuejs' => false])->htmlMatchesArray([
                'textarea' => [
                    "hoge"
                ],
            ]);
            that($form)->input('parent', ['type' => 'textarea', 'value' => 'hoge', 'vuejs' => true])->htmlMatchesArray([
                'textarea' => [
                    "",
                ],
            ]);
        }

        $prefix = spl_object_id($form->children->context);

        // context
        {
            // context は何もしないので空
            that($form)->context('children', 1)->isEmpty();

            // label が描画できる
            that($form)->label('child1')->htmlMatchesArray([
                'label' => [
                    'for'               => "cx{$prefix}_children-1-child1",
                    'data-vlabel-id'    => 'children/1/child1',
                    'data-vlabel-class' => 'children/child1',
                    'data-vlabel-index' => '1',
                    'class'             => 'validatable_label',
                ],
            ]);
            that($form)->label('child2')->htmlMatchesArray([
                'label' => [
                    'for'               => "cx{$prefix}_children-1-child2",
                    'data-vlabel-id'    => 'children/1/child2',
                    'data-vlabel-class' => 'children/child2',
                    'data-vlabel-index' => '1',
                    'class'             => 'validatable_label',
                ],
            ]);

            // input が含まれる
            that($form)->input('child1')->htmlMatchesArray([
                'input' => [
                    'id'                    => "cx{$prefix}_children-1-child1",
                    'value'                 => 'child1_fuga',
                    'data-validation-title' => '',
                    'data-vinput-id'        => 'children/1/child1',
                    'data-vinput-class'     => 'children/child1',
                    'data-vinput-index'     => '1',
                    'name'                  => 'children[1][child1]',
                    'type'                  => 'text',
                    'class'                 => 'validatable',
                ],
            ]);
            that($form)->input('child2')->htmlMatchesArray([
                'input' => [
                    'id'                    => "cx{$prefix}_children[1][child2]_0",
                    'value'                 => 'def2',
                    'data-validation-title' => '',
                    'data-vinput-id'        => 'children/1/child2',
                    'data-vinput-class'     => 'children/child2',
                    'data-vinput-index'     => '1',
                    'name'                  => 'children[1][child2][]',
                    'type'                  => 'text',
                    'class'                 => 'validatable',
                ],
            ]);

            // context は何もしないので空
            that($form)->context()->isEmpty();
        }

        // template
        {
            $content = $form->template('children');

            // <script から始まる
            that($content)->stringStartsWith('<script');
            // type=text/x-template
            that($content)->stringContains('type="text/x-template"');
            // nonce がある
            that($content)->stringContains('nonce="fuga"');

            // label が描画できる
            that($form)->label('child1')->htmlMatchesArray([
                'label' => [
                    'for'               => "cx{$prefix}_children-__index-child1",
                    'data-vlabel-id'    => 'children/__index/child1',
                    'data-vlabel-class' => 'children/child1',
                    'data-vlabel-index' => '__index',
                    'class'             => 'validatable_label',
                ],
            ]);
            that($form)->label('child2')->htmlMatchesArray([
                'label' => [
                    'for'               => "cx{$prefix}_children-__index-child2",
                    'data-vlabel-id'    => 'children/__index/child2',
                    'data-vlabel-class' => 'children/child2',
                    'data-vlabel-index' => '__index',
                    'class'             => 'validatable_label',
                ],
            ]);

            // input が含まれる
            that($form)->input('child1')->htmlMatchesArray([
                'input' => [
                    'id'                    => "cx{$prefix}_children-__index-child1",
                    'value'                 => 'def1',
                    'data-validation-title' => '',
                    'data-vinput-id'        => 'children/__index/child1',
                    'data-vinput-class'     => 'children/child1',
                    'data-vinput-index'     => '__index',
                    'disabled'              => 'disabled',
                    'name'                  => 'children[__index][child1]',
                    'type'                  => 'text',
                    'class'                 => 'validatable',
                ],
            ]);
            that($form)->input('child2')->htmlMatchesArray([
                'input' => [
                    'id'                    => "cx{$prefix}_children[__index][child2]_0",
                    'value'                 => 'def2',
                    'data-validation-title' => '',
                    'data-vinput-id'        => 'children/__index/child2',
                    'data-vinput-class'     => 'children/child2',
                    'data-vinput-index'     => '__index',
                    'disabled'              => 'disabled',
                    'name'                  => 'children[__index][child2][]',
                    'type'                  => 'text',
                    'class'                 => 'validatable',
                ],
            ]);

            $content = $form->template();

            // </script から始まる
            that($content)->stringStartsWith("</script>");
        }

        // vuefor
        {
            /** @noinspection PhpUnusedLocalVariableInspection */
            $options = $this->rewriteProperty($form, 'options', function ($options) {
                $options['vuejs'] = true;
                return $options;
            });

            // vuefor は何もしないので空
            that($form)->vuefor('children', 'item', 'i')->isEmpty();

            // label が描画できる
            that($form)->label('child1')->htmlMatchesArray([
                'label' => [
                    ':for'               => "'cx{$prefix}_children-'+i+'-child1'",
                    ':data-vlabel-id'    => "'children'+'/'+i+'/'+'child1'",
                    ':data-vlabel-class' => "'children'+'/'+'child1'",
                    ':data-vlabel-index' => "i",
                    'class'              => 'validatable_label',
                ],
            ]);
            that($form)->label('child2')->htmlMatchesArray([
                'label' => [
                    ':for'               => "'cx{$prefix}_children-'+i+'-child2'",
                    ':data-vlabel-id'    => "'children'+'/'+i+'/'+'child2'",
                    ':data-vlabel-class' => "'children'+'/'+'child2'",
                    ':data-vlabel-index' => "i",
                    'class'              => 'validatable_label',
                ],
            ]);

            // input が含まれる
            that($form)->input('child1')->htmlMatchesArray([
                'input' => [
                    ':id'                   => "'cx{$prefix}_children-'+i+'-child1'",
                    'data-validation-title' => '',
                    ':data-vinput-id'       => "'children'+'/'+i+'/'+'child1'",
                    ':data-vinput-class'    => "'children'+'/'+'child1'",
                    ':data-vinput-index'    => "i",
                    ':name'                 => "'children['+i+'][child1]'",
                    'type'                  => 'text',
                    'class'                 => 'validatable',
                    'v-model'               => 'item.child1',
                ],
            ]);
            that($form)->input('child2')->htmlMatchesArray([
                'input' => [
                    ':id'                   => "'cx{$prefix}_'+'children['+i+'][child2]'+'_0'",
                    'data-validation-title' => '',
                    ':data-vinput-id'       => "'children'+'/'+i+'/'+'child2'",
                    ':data-vinput-class'    => "'children'+'/'+'child2'",
                    ':data-vinput-index'    => "i",
                    ':name'                 => "'children['+i+'][child2]'+'[]'",
                    'type'                  => 'text',
                    'class'                 => 'validatable',
                    'v-model'               => 'item.child2',
                ],
            ]);

            // vuefor は何もしないので空
            that($form)->vuefor()->isEmpty();
        }

        // 終了タグ
        {
            $content = $form->close();

            // <script> タグから始まる
            that($content)->stringStartsWith('<script nonce="fuga">');
            // </form> で終わる
            that($content)->stringEndsWith('</form>');
            // 初期化がある
            that($content)->stringContainsAny(['chmonos.initialize', 'chmonos.data']);
        }
    }

    function test_form_exception()
    {
        $form = new Form([]);

        that($form)->vuefor('children', 'child', 'i')->wasThrown('vuejs flag is false');
    }
}

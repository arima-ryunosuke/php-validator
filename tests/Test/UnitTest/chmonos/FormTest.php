<?php
namespace ryunosuke\Test\UnitTest\chmonos;

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

        $this->assertInstanceOf(Input::class, $form->hoge_input);
        $this->assertInstanceOf(Context::class, $form->context);
    }

    function test___get()
    {
        $form = new Form([
            'hoge_input' => []
        ]);

        $this->assertInstanceOf(Input::class, $form->hoge_input);
        $this->assertInstanceOf(Context::class, $form->context);
    }

    function test_setValues()
    {
        $form = new Form([
            'req' => [
                'condition' => [
                    'Requires' => null
                ]
            ]
        ]);

        $values = $form->setValues([
            'req' => '  hoge  ',
            'foo' => 'bar',
        ]);

        $this->assertIsArray($values);
        $this->assertArrayHasKey('req', $values);
        $this->assertEquals('hoge', $values['req']);
        $this->assertArrayNotHasKey('foo', $values);
    }

    function test_setValues_file()
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
        $this->assertEquals('nofile', $form->setValues(['file' => 'specified'])['file']);

        $_FILES = ['file' => ['error' => UPLOAD_ERR_OK] + $default];
        $this->assertEquals(__FILE__, $form->setValues(['file' => 'specified'])['file']);

        $_FILES = ['file' => ['error' => UPLOAD_ERR_OK] + $default];
        $this->assertEquals(__FILE__, $form->setValues([])['file']);

        $_FILES = ['file' => ['error' => UPLOAD_ERR_NO_FILE] + $default];
        $this->assertEquals('nofile', $form->setValues([])['file']);

        $ex = new \UnexpectedValueException('file size too large', UPLOAD_ERR_INI_SIZE);
        $this->assertException($ex, function () use ($form, $default) {
            $_FILES = ['file' => ['error' => UPLOAD_ERR_INI_SIZE] + $default];
            $this->assertEquals('nofile', $form->setValues([])['file']);
        });

        $ex = new \UnexpectedValueException('upload error', 999);
        $this->assertException($ex, function () use ($form, $default) {
            $_FILES = ['file' => ['error' => 999] + $default];
            $this->assertEquals('nofile', $form->setValues([])['file']);
        });
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
        $this->assertContains('method="post"', $form_tag);
        $this->assertContains('enctype="multipart/form-data"', $form_tag);
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

        $this->assertIsArray($values);
        $this->assertArrayHasKey('req', $values);
        $this->assertEquals('hoge', $values['req']);
        $this->assertArrayNotHasKey('foo', $values);
    }

    function test_validate_csrf()
    {
        $form = new Form([], [
            'tokenName' => 'hoge',
        ]);

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['hoge'] = 'invalid';

        $this->assertException('token is invalid', function () use ($form) {
            $values = [];
            $form->validate($values);
        });
    }

    function test_validate_files()
    {
        $_FILES = [
            'parent_file'   => [
                'name'     => 'local/parent_file',
                'type'     => 'text/plain',
                'error'    => UPLOAD_ERR_OK,
                'size'     => 999,
                'tmp_name' => 'remote/parent_file',
            ],
            'multiple_file' => [
                'name'     => ['local/multiple1_file', 'local/multiple2_file'],
                'type'     => ['text/plain', 'text/plain'],
                'error'    => [UPLOAD_ERR_OK, UPLOAD_ERR_OK],
                'size'     => [999, 999],
                'tmp_name' => ['remote/multiple1_file', 'remote/multiple2_file'],
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
                    ['child_file' => 'remote/child_file0'],
                    ['child_file' => 'remote/child_file1'],
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

        $this->assertEquals('parent_text', $posts['parent_text']);
        $this->assertEquals('remote/parent_file', $posts['parent_file']);

        $this->assertEquals('remote/multiple1_file', $posts['multiple_file'][0]);
        $this->assertEquals('remote/multiple2_file', $posts['multiple_file'][1]);

        $this->assertEquals('child_text0', $posts['children'][0]['child_text']);
        $this->assertEquals('remote/child_file0', $posts['children'][0]['child_file']);
        $this->assertEquals('child_text1', $posts['children'][1]['child_text']);
        $this->assertEquals('remote/child_file1', $posts['children'][1]['child_file']);
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

        $this->assertEquals('', $posts['file']);
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
            $this->assertSame($ex->getForm(), $form);
            $this->assertContains('入力必須です', $ex->getForm()->getFlatMessages('%s%s', ''));
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
        $this->assertEquals('hoge', $values['req']);
        // dmy はデフォルト値が設定されているはずだし
        $this->assertEquals('dmy', $values['dmy']);
        // foo はフィルタリングされて存在しないはず
        $this->assertArrayNotHasKey('foo', $values);
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
                        'default' => 'def2'
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
        $prefix = spl_object_id($form->children->context);

        // 開始タグ
        {
            $content = $form->form(['id' => 'hoge']);

            // form 開始タグから始まる
            $this->assertStringStartsWith('<form', $content);
            // id が設定されている
            $this->assertContains('id="hoge"', $content);
            // nonce がある
            $this->assertContains('nonce="fuga"', $content);
        }

        // context
        {
            // コンテキストは何もしないので空
            $this->assertEmpty($form->context('children', 1));

            // label が描画できる
            $this->assertAttribute([
                'label' => [
                    [
                        'for'               => "cx{$prefix}_children-1-child1",
                        'data-vlabel-id'    => 'children/1/child1',
                        'data-vlabel-class' => 'children/child1',
                        'data-vlabel-index' => '1',
                        'class'             => 'validatable_label',
                    ]
                ],
            ], $form->label('child1'));
            $this->assertAttribute([
                'label' => [
                    [
                        'for'               => "cx{$prefix}_children-1-child2",
                        'data-vlabel-id'    => 'children/1/child2',
                        'data-vlabel-class' => 'children/child2',
                        'data-vlabel-index' => '1',
                        'class'             => 'validatable_label',
                    ]
                ],
            ], $form->label('child2'));

            // input が含まれる
            $this->assertAttribute([
                'input' => [
                    [
                        'id'                    => "cx{$prefix}_children-1-child1",
                        'value'                 => 'child1_fuga',
                        'data-validation-title' => '',
                        'data-vinput-id'        => 'children/1/child1',
                        'data-vinput-class'     => 'children/child1',
                        'data-vinput-index'     => '1',
                        'name'                  => 'children[1][child1]',
                        'type'                  => 'text',
                        'class'                 => 'validatable',
                    ]
                ],
            ], $form->input('child1'));
            $this->assertAttribute([
                'input' => [
                    [
                        'id'                    => "cx{$prefix}_children-1-child2",
                        'value'                 => 'def2',
                        'data-validation-title' => '',
                        'data-vinput-id'        => 'children/1/child2',
                        'data-vinput-class'     => 'children/child2',
                        'data-vinput-index'     => '1',
                        'name'                  => 'children[1][child2]',
                        'type'                  => 'text',
                        'class'                 => 'validatable',
                    ]
                ],
            ], $form->input('child2'));

            // コンテキストは何もしないので空
            $this->assertEmpty($form->context());
        }

        // template
        {
            $content = $form->template('children');

            // <script から始まる
            $this->assertStringStartsWith('<script', $content);
            // type=text/x-template
            $this->assertContains('type="text/x-template"', $content);
            // nonce がある
            $this->assertContains('nonce="fuga"', $content);

            // label が描画できる
            $this->assertAttribute([
                'label' => [
                    [
                        'for'               => "cx{$prefix}_children-__index-child1",
                        'data-vlabel-id'    => 'children/__index/child1',
                        'data-vlabel-class' => 'children/child1',
                        'data-vlabel-index' => '__index',
                        'class'             => 'validatable_label',
                    ]
                ],
            ], $form->label('child1'));
            $this->assertAttribute([
                'label' => [
                    [
                        'for'               => "cx{$prefix}_children-__index-child2",
                        'data-vlabel-id'    => 'children/__index/child2',
                        'data-vlabel-class' => 'children/child2',
                        'data-vlabel-index' => '__index',
                        'class'             => 'validatable_label',
                    ]
                ],
            ], $form->label('child2'));

            // input が含まれる
            $this->assertAttribute([
                'input' => [
                    [
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
                    ]
                ],
            ], $form->input('child1'));
            $this->assertAttribute([
                'input' => [
                    [
                        'id'                    => "cx{$prefix}_children-__index-child2",
                        'value'                 => 'def2',
                        'data-validation-title' => '',
                        'data-vinput-id'        => 'children/__index/child2',
                        'data-vinput-class'     => 'children/child2',
                        'data-vinput-index'     => '__index',
                        'disabled'              => 'disabled',
                        'name'                  => 'children[__index][child2]',
                        'type'                  => 'text',
                        'class'                 => 'validatable',
                    ]
                ],
            ], $form->input('child2'));

            $content = $form->template();

            // </script から始まる
            $this->assertStringStartsWith("</script>", $content);
        }

        // 終了タグ
        {
            $content = $form->form();

            // <script> タグから始まる
            $this->assertStringStartsWith('<script nonce="fuga">', $content);
            // </form> で終わる
            $this->assertStringEndsWith('</form>', $content);
            // initialize の呼び出しがある
            $this->assertContains('chmonos.initialize', $content);
        }
    }

    function test_form_csrf()
    {
        $form = new Form([], [
            'tokenName' => 'hoge',
        ]);
        // method=post 時のみ含まれる
        $this->assertNotContains("<input type='hidden' name='hoge'", $form->form(['method' => 'get']));
        $this->assertContains("<input type='hidden' name='hoge'", $form->form(['method' => 'post']));

    }
}

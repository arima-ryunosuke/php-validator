<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\AbstractCondition;
use ryunosuke\chmonos\Condition\Callback;
use ryunosuke\chmonos\Condition\Decimal;
use ryunosuke\chmonos\Condition\Range;
use function ryunosuke\chmonos\rm_rf;

class AbstractConditionTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_setNamespace()
    {
        $file = __DIR__ . '/_files/Empty/Date.php';
        require_once $file;

        $current = AbstractCondition::setNamespace(['hogera' => dirname($file)]);
        $this->assertInstanceOf(\hogera\Date::class, AbstractCondition::create('Date'));
        $this->assertInstanceOf(Callback::class, AbstractCondition::create('Callback', [function () { }]));

        AbstractCondition::setNamespace(['hogera' => dirname($file)], false);
        $this->assertInstanceOf(\hogera\Date::class, AbstractCondition::create('Date'));
        $this->assertException('is not found', function () {
            AbstractCondition::create('Callback', [function () { }]);
        });

        AbstractCondition::setNamespace($current);
    }

    function test_setMessages()
    {
        // バックアップしておかないと後段のテストに影響が出る
        $messageTemplates = self::publishField(Decimal::class, 'messageTemplates');

        AbstractCondition::setMessages([
            // クラス => メッセージ指定
            Decimal::class       => [
                Decimal::INVALID => 'custom1',
            ],
            // キーの直指定
            Decimal::INVALID_INT => 'custom2',
        ]);
        // 個別指定
        Decimal::setMessages([
            Decimal::INVALID_DEC => 'custom3',
        ]);

        $condition = new Decimal(1, 2);

        $condition->isValid('hoge');
        $this->assertEquals([
            Decimal::INVALID => 'custom1',
        ], $condition->getMessages());

        $condition->isValid(999.9);
        $this->assertEquals([
            Decimal::INVALID_INT => 'custom2',
        ], $condition->getMessages());

        $condition->isValid(9.999);
        $this->assertEquals([
            Decimal::INVALID_DEC => 'custom3',
        ], $condition->getMessages());

        self::publishField(Decimal::class, 'messageTemplates', $messageTemplates);
    }

    function test_create_direct()
    {
        $this->assertInstanceOf(AbstractCondition::class, AbstractCondition::create(\custom\Condition\CustomCondition::class));

        $this->assertException("is not found", function () {
            AbstractCondition::create(\custom\Condition\CustomCondition::class . 'Dummy');
        });
    }

    function test_create_paml()
    {
        $expected = ['min' => 1, 'max' => 3];
        $this->assertEquals($expected, AbstractCondition::create(null, 'Range(1, 3)')->getValidationParam());
        $this->assertEquals($expected, AbstractCondition::create(null, 'Range(max:3, min:1)')->getValidationParam());
        $this->assertEquals($expected, AbstractCondition::create(null, 'Range(max:3, min:1)')->getValidationParam());

        $this->assertEquals(['min' => 1, 'max' => null], AbstractCondition::create(null, 'Range(min:1)')->getValidationParam());
        $this->assertEquals(['min' => null, 'max' => 3], AbstractCondition::create(null, 'Range(max:3)')->getValidationParam());
    }

    function test_create_paml_key()
    {
        $condition = AbstractCondition::create('Range(min:1,max:3)', [
            Range::INVALID => 'hoge',
        ]);
        $this->assertEquals(['min' => 1, 'max' => 3], $condition->getValidationParam());
        $this->assertEquals([
            Range::INVALID => 'hoge',
        ], $condition->getMessageTemplates());
    }

    function test_create_arg()
    {
        $expected = ['min' => 1, 'max' => 3];
        $this->assertEquals($expected, AbstractCondition::create('Range', [1, 3])->getValidationParam());
        $this->assertEquals($expected, AbstractCondition::create('Range', ['max' => 3, 1])->getValidationParam());
        $this->assertEquals($expected, AbstractCondition::create('Range', [1, 'max' => 3])->getValidationParam());
        $this->assertEquals($expected, AbstractCondition::create('Range', ['min' => 1, 'max' => 3])->getValidationParam());
        $this->assertEquals($expected, AbstractCondition::create('Range', ['max' => 3, 'min' => 1])->getValidationParam());

        $this->assertEquals(['min' => 1, 'max' => null], AbstractCondition::create('Range', ['min' => 1])->getValidationParam());
        $this->assertEquals(['min' => null, 'max' => 3], AbstractCondition::create('Range', ['max' => 3])->getValidationParam());

        $this->assertEquals([
            'statements' => [
                ['hoge' => ['!=', '']],
                ['fuga' => ['==', 'X']],
                [
                    'piyo1' => ['>=', 'Y'],
                    'piyo2' => ['<=', 'Z'],
                ],
            ]
        ], AbstractCondition::create('Requires', [
            'hoge',
            ['fuga' => ['==', 'X']],
            [
                'piyo1' => ['>=', 'Y'],
                'piyo2' => ['<=', 'Z'],
            ],
        ])->getValidationParam());

        $this->assertException("is required parameter", function () {
            AbstractCondition::create('Regex');
        });
    }

    function test_outputJavascript()
    {
        $tmp_dir = sys_get_temp_dir() . '/validator-test';
        @mkdir($tmp_dir);
        rm_rf($tmp_dir, false);
        $this->assertTrue(AbstractCondition::outputJavascript($tmp_dir, true));

        $this->assertContains('core_validate', file_get_contents("$tmp_dir/validator.js"));
        $this->assertContains('"CustomCondition":', file_get_contents("$tmp_dir/validator.js"));

        // 出したばかりなので false になるはず
        $this->assertFalse(AbstractCondition::outputJavascript($tmp_dir));

        $this->assertException("is not writable", function () {
            AbstractCondition::outputJavascript('notfound dir');
        });
    }

    function test_context_syntax()
    {
        $callback = new Callback(function ($value, $error, $depends, $userdata, $context) {
            // function(引数と use が渡ってくる function を生成)
            $f = $context['function'](function ($arg1, $arg2, $use) {
                return $arg1 . $arg2 . $use;
            }, 'hoge');
            $this->assertEquals('arg1arg2hoge', $f('arg1', 'arg2'));

            // foreach(配列の key,value と use を渡してループ処理を回す)
            $all = '';
            $t = $context['foreach']($userdata, function ($k, $v, $use) use (&$all) {
                $all .= $k . $v . $use;
            }, 'hoge');
            $this->assertTrue($t);
            $this->assertEquals('0ahoge1bhoge2choge', $all);

            // ループが完遂できなかったら false になる
            $t = $context['foreach']([1, 2, 3], function ($k, $v) {
                return false;
            });
            $this->assertFalse($t);

            // cast(指定の型で指定の値をキャスト)
            $v = $context['cast']('array', 'hoge');
            $this->assertEquals(['hoge'], $v);

            // いまのところ array しか対応していない
            $this->assertException(new \InvalidArgumentException('invalid cast type'), function () use ($context) {
                $context['cast']('stdclass', 'hoge');
            });

            // str_concat(文字列結合)
            $v = $context['str_concat'](1, 's');
            $this->assertEquals('1s', $v);

        }, [], ['a', 'b', 'c']);
        $callback->isValid(null);
    }

    function test_isValid()
    {
        $condition = new Callback(function ($value, $error) { $error('this is error'); }, [], 'userdata');
        $this->assertEquals(false, $condition->isValid(null));
        $this->assertEquals([Callback::INVALID => 'this is error'], $condition->getMessages());

        $condition->setCheckMode(false);
        $this->assertEquals(true, $condition->isValid(null));
        $this->assertEquals([], $condition->getMessages());
    }

    function test_isArrayableValidation()
    {
        $validate = new Decimal(1, 10);

        $this->assertEquals($validate->isArrayableValidation(), false);
    }

    function test_getValidationParam()
    {
        $condition = new Decimal(1, 2);
        $this->assertEquals(['int' => 1, 'dec' => 2], $condition->getValidationParam());
    }

    function test_getFields()
    {
        $condition = new Decimal(1, 2);
        $this->assertEquals([], $condition->getFields());
    }

    function test_getMessageTemplates()
    {
        $condition = new Callback(function () { });
        $this->assertEquals([], $condition->getMessageTemplates());

        $condition->setMessageTemplate('test', 'undefined');
        $this->assertEquals([], $condition->getMessageTemplates());

        $condition->setMessageTemplate('test', Callback::INVALID);
        $this->assertEquals(['CallbackInvalid' => 'test'], $condition->getMessageTemplates());
    }

    function test_getRule()
    {
        $condition = new Callback(function () { });
        $this->assertEquals(['cname', 'param', 'arrayable', 'message', 'fields'], array_keys($condition->getRule()));

        $condition->setCheckMode(false);
        $this->assertNull($condition->getRule());
    }

    function test_setCheckMode()
    {
        $condition = new Callback(function () { });
        $checkmode = \Closure::bind(function () {
            /** @noinspection PhpUndefinedFieldInspection */
            return $this->checkmode;
        }, $condition, AbstractCondition::class);

        $condition->setCheckMode(false);
        $this->assertEquals(['server' => false, 'client' => false], $checkmode());
        $condition->setCheckMode(true);
        $this->assertEquals(['server' => true, 'client' => true], $checkmode());
        $condition->setCheckMode(['server' => false, 'hogera' => 123]);
        $this->assertEquals(['server' => false, 'client' => true], $checkmode());
    }

    function test_addMessage()
    {
        $condition = new Callback(function () { }, [], 'userdata');
        $condition->setMessageTemplate('this is %userdata% message', Callback::INVALID);

        $condition->addMessage(Callback::INVALID, 'hogera');
        $this->assertEquals([Callback::INVALID => 'hogera'], $condition->getMessages());

        $condition->addMessage(Callback::INVALID, '%notfund%');
        $this->assertEquals([Callback::INVALID => '%notfund%'], $condition->getMessages());

        $condition->addMessage(Callback::INVALID);
        $this->assertEquals([Callback::INVALID => 'this is userdata message'], $condition->getMessages());
    }
}

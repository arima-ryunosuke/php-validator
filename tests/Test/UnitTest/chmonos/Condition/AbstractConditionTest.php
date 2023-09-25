<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\AbstractCondition;
use ryunosuke\chmonos\Condition\Callback;
use ryunosuke\chmonos\Condition\Decimal;
use ryunosuke\chmonos\Condition\Range;

class AbstractConditionTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_setNamespace()
    {
        $file = __DIR__ . '/_files/Empty/Date.php';
        require_once $file;

        $current = AbstractCondition::setNamespace(['hogera' => dirname($file)]);
        that(AbstractCondition::class)::create('Date')->isInstanceOf(\hogera\Date::class);
        that(AbstractCondition::class)::create('Callback', [function () { }])->isInstanceOf(Callback::class);

        AbstractCondition::setNamespace(['hogera' => dirname($file)], false);
        that(AbstractCondition::class)::create('Date')->isInstanceOf(\hogera\Date::class);
        that(AbstractCondition::class)::create('Callback', [function () { }])->wasThrown('is not found');

        AbstractCondition::setNamespace($current);
    }

    function test_setMessages()
    {
        /** @noinspection PhpUnusedLocalVariableInspection */
        $messageTemplates = $this->rewriteProperty(Decimal::class, 'messageTemplates');

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
        that($condition)->getMessages()->is([
            Decimal::INVALID => 'custom1',
        ]);

        $condition->isValid(999.9);
        that($condition)->getMessages()->is([
            Decimal::INVALID_INT => 'custom2',
        ]);

        $condition->isValid(9.999);
        that($condition)->getMessages()->is([
            Decimal::INVALID_DEC => 'custom3',
        ]);
    }

    function test_create_direct()
    {
        that(AbstractCondition::class)::create(\custom\Condition\CustomCondition::class)->isInstanceOf(AbstractCondition::class);

        that(AbstractCondition::class)::create(\custom\Condition\CustomCondition::class . 'Dummy')->wasThrown('is not found');
    }

    function test_create_paml()
    {
        $expected = ['min' => 1, 'max' => 3];
        that(AbstractCondition::class)::create(null, 'Range(1, 3)')->getValidationParam()->is($expected);
        that(AbstractCondition::class)::create(null, 'Range(max:3, min:1)')->getValidationParam()->is($expected);
        that(AbstractCondition::class)::create(null, 'Range(max:3, min:1)')->getValidationParam()->is($expected);

        that(AbstractCondition::class)::create(null, 'Range(min:1)')->getValidationParam()->is([
            "min" => 1,
            "max" => null,
        ]);
        that(AbstractCondition::class)::create(null, 'Range(max:3)')->getValidationParam()->is([
            "min" => null,
            "max" => 3,
        ]);
    }

    function test_create_paml_key()
    {
        $condition = AbstractCondition::create('Range(min:1,max:3)', [
            Range::INVALID => 'hoge',
        ]);
        that($condition)->getValidationParam()->is([
            "min" => 1,
            "max" => 3,
        ]);
        that($condition)->getMessageTemplates()->is([
            Range::INVALID => 'hoge',
        ]);
    }

    function test_create_arg()
    {
        $expected = ['min' => 1, 'max' => 3];
        that(AbstractCondition::class)::create('Range', [1, 3])->getValidationParam()->is($expected);
        that(AbstractCondition::class)::create('Range', ['max' => 3, 1])->getValidationParam()->is($expected);
        that(AbstractCondition::class)::create('Range', [1, 'max' => 3])->getValidationParam()->is($expected);
        that(AbstractCondition::class)::create('Range', ['min' => 1, 'max' => 3])->getValidationParam()->is($expected);
        that(AbstractCondition::class)::create('Range', ['max' => 3, 'min' => 1])->getValidationParam()->is($expected);

        that(AbstractCondition::class)::create('Range', ['min' => 1])->getValidationParam()->is(['min' => 1, 'max' => null]);
        that(AbstractCondition::class)::create('Range', ['max' => 3])->getValidationParam()->is(['min' => null, 'max' => 3]);

        that(AbstractCondition::class)::create('Requires', [
            'hoge',
            ['fuga' => ['==', 'X']],
            [
                'piyo1' => ['>=', 'Y'],
                'piyo2' => ['<=', 'Z'],
            ],
        ])->getValidationParam()->is([
            'statements' => [
                ['hoge' => ['!=', '']],
                ['fuga' => ['==', 'X']],
                [
                    'piyo1' => ['>=', 'Y'],
                    'piyo2' => ['<=', 'Z'],
                ],
            ]
        ]);

        that(AbstractCondition::class)::create('Regex')->wasThrown('is required parameter');
    }

    function test_outputJavascript()
    {
        $out_dir = $this->emptyDirectory();
        mkdir("$out_dir/phpjs");

        that(AbstractCondition::class)::outputJavascript($out_dir, true)->isTrue();

        that("$out_dir/validator.js")->fileContains('core_validate');
        that("$out_dir/validator.js")->fileContains('"CustomCondition":');

        // 出したばかりなので false になるはず
        that(AbstractCondition::class)::outputJavascript($out_dir)->isFalse();

        // ファイルが増えれば再び生成されるはず
        touch("$out_dir/phpjs/dummy.js", time() + 1);
        that(AbstractCondition::class)::outputJavascript($out_dir)->isTrue();

        that(AbstractCondition::class)::outputJavascript('notfound dir')->wasThrown('is not writable');
    }

    function test_context_syntax()
    {
        $callback = new Callback(function ($value, $error, $depends, $userdata, $context) {
            // function(引数と use が渡ってくる function を生成)
            $f = $context['function'](function ($arg1, $arg2, $use) {
                return $arg1 . $arg2 . $use;
            }, 'hoge');
            that($f('arg1', 'arg2'))->is('arg1arg2hoge');

            // foreach(配列の key,value と use を渡してループ処理を回す)
            $all = '';
            $t = $context['foreach']($userdata, function ($k, $v, $use) use (&$all) {
                $all .= $k . $v . $use;
            }, 'hoge');
            that($t)->isTrue();
            that($all)->is('0ahoge1bhoge2choge');

            // ループが完遂できなかったら false になる
            $t = $context['foreach']([1, 2, 3], function ($k, $v) {
                return false;
            });
            that($t)->isFalse();

            // cast(指定の型で指定の値をキャスト)
            $v = $context['cast']('array', 'hoge');
            that($v)->is(['hoge']);

            // いまのところ array しか対応していない
            that($context['cast'])('stdclass', 'hoge')->wasThrown('invalid cast type');

            // str_concat(文字列結合)
            $v = $context['str_concat'](1, 's');
            that($v)->is('1s');

        }, [], ['a', 'b', 'c']);
        $callback->isValid(null);
    }

    function test_isValid()
    {
        $condition = new Callback(function ($value, $error) { $error('this is error'); }, [], 'userdata');
        that($condition)->isValid(null)->isFalse();
        that($condition)->getMessages()->is([Callback::INVALID => 'this is error']);

        $condition->setCheckMode(false);
        that($condition)->isValid(null)->isTrue();
        that($condition)->getMessages()->is([]);
    }

    function test_isArrayableValidation()
    {
        $condition = new Decimal(1, 10);
        that($condition)->isArrayableValidation()->isFalse();
    }

    function test_getValidationParam()
    {
        $condition = new Decimal(1, 2);
        that($condition)->getValidationParam()->is(['int' => 1, 'dec' => 2]);
    }

    function test_getFields()
    {
        $condition = new Decimal(1, 2);
        that($condition)->getFields()->is([]);
    }

    function test_getMessageTemplates()
    {
        $condition = new Callback(function () { });
        that($condition)->getMessageTemplates()->is([]);

        $condition->setMessageTemplate('test', 'undefined');
        that($condition)->getMessageTemplates()->is([]);

        $condition->setMessageTemplate('test', Callback::INVALID);
        that($condition)->getMessageTemplates()->is(['CallbackInvalid' => 'test']);
    }

    function test_getRule()
    {
        $condition = new Callback(function () { });
        that($condition)->getRule()->hasKeyAll(['cname', 'param', 'arrayable', 'message', 'fields']);

        $condition->setCheckMode(false);
        that($condition)->getRule()->isNull();
    }

    function test_setCheckMode()
    {
        $condition = new Callback(function () { });
        $checkmode = \Closure::bind(function () {
            return $this->checkmode;
        }, $condition, AbstractCondition::class);

        $condition->setCheckMode(false);
        that($checkmode)()->is([
            "server" => false,
            "client" => false,
        ]);
        $condition->setCheckMode(true);
        that($checkmode)()->is([
            "server" => true,
            "client" => true,
        ]);
        $condition->setCheckMode(['server' => false, 'hogera' => 123]);
        that($checkmode)()->is([
            "server" => false,
            "client" => true,
        ]);
    }

    function test_setMessage()
    {
        $condition = new Decimal(1, 1);
        $condition->setMessage('this is all message');

        $condition->addMessage(Decimal::INVALID, 'custom');
        that($condition)->getMessages()->is([Decimal::INVALID => 'custom']);

        $condition->isValid("31.4");
        that($condition)->getMessages()->is([Decimal::INVALID_INT => 'this is all message']);

        $condition->isValid("3.14");
        that($condition)->getMessages()->is([Decimal::INVALID_DEC => 'this is all message']);
    }

    function test_addMessage()
    {
        $condition = new Callback(function () { }, [], 'userdata');
        $condition->setMessageTemplate('this is %userdata% message', Callback::INVALID);

        $condition->addMessage(Callback::INVALID, 'hogera');
        that($condition)->getMessages()->is([Callback::INVALID => 'hogera']);

        $condition->addMessage(Callback::INVALID, '%notfund%');
        that($condition)->getMessages()->is([Callback::INVALID => '%notfund%']);

        $condition->addMessage(Callback::INVALID);
        that($condition)->getMessages()->is([Callback::INVALID => 'this is userdata message']);
    }
}

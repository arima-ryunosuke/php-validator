<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\Callback;

class CallbackTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_valid()
    {
        $validate = new Callback(function ($value, $error, $depends, $userdata, $context) {
            $error($context['str_concat']('$value is ', $value, ', $another is ', $depends['another'], ', $userdata is ', $userdata));
        }, ['another'], 'userdata');

        that($validate)->isValid('hoge', [
            'another' => 'fuga',
        ])->isFalse();
        that($validate)->getMessages()->is([
            "CallbackInvalid" => '$value is hoge, $another is fuga, $userdata is userdata',
        ]);
    }

    function test_callback()
    {
        function callback($input, $value, $fields, $params, $consts, $error, $context, $e)
        {
            if ($value !== 'valid') {
                return "another:{$fields['another']},userdata:{$params['userdata']}";
            }
        }

        $validate = new Callback(__NAMESPACE__ . "\\callback", ['another'], 'userdata');

        that($validate)->isValid('invalid', [
            'another' => 'fuga',
        ])->isFalse();
        that($validate)->getMessages()->is([
            "CallbackInvalid" => 'another:fuga,userdata:userdata',
        ]);

        that($validate)->isValid('valid', [
            'another' => 'fuga',
        ])->isTrue();
        that($validate)->getMessages()->is([]);
    }

    function test_getField()
    {
        $validate = new Callback(function () { }, ['another']);
        that($validate)->getFields()->is(["another"]);
    }

    function test_getPropagation()
    {
        $validate = new Callback(function () { }, ['another']);
        that($validate)->getPropagation()->is(["another"]);
    }

    function test_getFixture()
    {
        $validate = new Callback(function () { });
        that($validate)->getFixture(null, [])->isSame(null);
    }
}

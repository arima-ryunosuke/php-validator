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

        $this->assertEquals(false, $validate->isValid('hoge', [
            'another' => 'fuga'
        ]));
        $this->assertEquals([
            'CallbackInvalid' => '$value is hoge, $another is fuga, $userdata is userdata'
        ], $validate->getMessages());
    }

    function test_getField()
    {
        $validate = new Callback(function () { }, ['another']);
        $this->assertEquals(['another'], $validate->getFields());
    }

    function test_getPropagation()
    {
        $validate = new Callback(function () { }, ['another']);
        $this->assertEquals(['another'], $validate->getPropagation());
    }
}

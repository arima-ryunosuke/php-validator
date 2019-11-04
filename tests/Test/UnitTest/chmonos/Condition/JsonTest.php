<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\Json;

class JsonTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_isValid()
    {
        $validate = new Json();
        $this->assertEquals($validate->isValid('null'), true);
        $this->assertEquals($validate->isValid('false'), true);
        $this->assertEquals($validate->isValid('true'), true);
        $this->assertEquals($validate->isValid('123'), true);
        $this->assertEquals($validate->isValid('"hoge"'), true);
        $this->assertEquals($validate->isValid('[1,2,3]'), true);
        $this->assertEquals($validate->isValid('{"a": "A"}'), true);
        $this->assertEquals($validate->isValid('{"a": "A", "x": [{},{},{}]}'), true);

        $this->assertEquals($validate->isValid('hoge'), false);
        $this->assertEquals($validate->isValid('1.2.3'), false);
        $this->assertEquals($validate->isValid('[1,2,3'), false);
        $this->assertEquals($validate->isValid('{"a": "A"'), false);
    }
}

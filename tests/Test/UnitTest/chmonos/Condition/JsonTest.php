<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\Json;

class JsonTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_isValid()
    {
        $validate = new Json();
        $this->assertEquals(true, $validate->isValid('null'));
        $this->assertEquals(true, $validate->isValid('false'));
        $this->assertEquals(true, $validate->isValid('true'));
        $this->assertEquals(true, $validate->isValid('123'));
        $this->assertEquals(true, $validate->isValid('"hoge"'));
        $this->assertEquals(true, $validate->isValid('[1,2,3]'));
        $this->assertEquals(true, $validate->isValid('{"a": "A"}'));
        $this->assertEquals(true, $validate->isValid('{"a": "A", "x": [{},{},{}]}'));

        $this->assertEquals(false, $validate->isValid('hoge'));
        $this->assertEquals(false, $validate->isValid('1.2.3'));
        $this->assertEquals(false, $validate->isValid('[1,2,3'));
        $this->assertEquals(false, $validate->isValid('{"a": "A"'));
    }
}

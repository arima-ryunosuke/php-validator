<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\Json;

class JsonTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_isValid()
    {
        $validate = new Json();
        that($validate)->isValid('null')->isTrue();
        that($validate)->isValid('false')->isTrue();
        that($validate)->isValid('true')->isTrue();
        that($validate)->isValid('123')->isTrue();
        that($validate)->isValid('"hoge"')->isTrue();
        that($validate)->isValid('[1,2,3]')->isTrue();
        that($validate)->isValid('{"a": "A"}')->isTrue();
        that($validate)->isValid('{"a": "A", "x": [{},{},{}]}')->isTrue();

        that($validate)->isValid('hoge')->isFalse();
        that($validate)->isValid('1.2.3')->isFalse();
        that($validate)->isValid('[1,2,3')->isFalse();
        that($validate)->isValid('{"a": "A"')->isFalse();
    }

    function test_getFixture()
    {
        $validate = new Json();
        that($validate)->getFixture(null, [])->is(json_encode(null));
        that($validate)->getFixture([1, 2, 3], [])->is(json_encode([1, 2, 3]));
    }
}

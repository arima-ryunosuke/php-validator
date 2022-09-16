<?php
namespace ryunosuke\Test\UnitTest\chmonos\Mixin;

use ryunosuke\chmonos\Mixin\Jsonable;

class JsonableTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    use Jsonable;

    function test_encodeJson()
    {
        $json = $this->encodeJson([
            'string'   => 'string',
            'int'      => 123,
            'bool'     => true,
            'null'     => null,
            'array'    => [1, 2, 3],
            'assoc'    => ['a' => 'A', 'b' => 'B'],
            'object'   => (object) [1, 2, 3],
            'iterable' => new \ArrayObject([1 => 1, 2, 3]),
            'resource' => $this->literalJson('function(){return "hogehoge"}'),
        ]);
        that($json)->stringContainsAll([
            '"string":"string"',
            '"int":123',
            '"bool":true',
            '"null":null',
            '"array":[1,2,3]',
            '"assoc":{"a":"A","b":"B"}',
            '"object":{"0":1,"1":2,"2":3}',
            '"iterable":{"1":1,"2":2,"3":3}',
            '"resource":function(){return "hogehoge"}',
        ]);
    }

    function test_literalJson()
    {
        that($this)->literalJson('function(){return "hogehoge"}')->isResource();
    }
}

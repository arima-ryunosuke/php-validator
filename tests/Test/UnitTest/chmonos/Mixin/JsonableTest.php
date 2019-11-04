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
        $this->assertContains('"string":"string"', $json);
        $this->assertContains('"int":123', $json);
        $this->assertContains('"bool":true', $json);
        $this->assertContains('"null":null', $json);
        $this->assertContains('"array":[1,2,3]', $json);
        $this->assertContains('"assoc":{"a":"A","b":"B"}', $json);
        $this->assertContains('"object":{"0":1,"1":2,"2":3}', $json);
        $this->assertContains('"iterable":{"1":1,"2":2,"3":3}', $json);
        $this->assertContains('"resource":function(){return "hogehoge"}', $json);
    }

    function test_literalJson()
    {
        $this->assertIsResource($this->literalJson('function(){return "hogehoge"}'));
    }
}

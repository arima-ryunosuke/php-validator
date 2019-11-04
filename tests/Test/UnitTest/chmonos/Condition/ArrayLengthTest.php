<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\ArrayLength;

class ArrayLengthTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    private function array_create($count)
    {
        if ($count == 0) {
            return [];
        }
        return array_fill(0, $count, 'x');
    }

    function test_isArrayableValidation()
    {
        $validate = new ArrayLength(1, 10);

        $this->assertEquals($validate->isArrayableValidation(), true);
    }

    function test_min()
    {
        $validate = new ArrayLength(1, null);

        $this->assertEquals($validate->isValid($this->array_create(0)), false);
        $this->assertEquals($validate->isValid($this->array_create(1)), true);
        $this->assertEquals($validate->isValid($this->array_create(65535)), true);
    }

    function test_max()
    {
        $validate = new ArrayLength(null, 10);

        $this->assertEquals($validate->isValid($this->array_create(0)), true);
        $this->assertEquals($validate->isValid($this->array_create(1)), true);
        $this->assertEquals($validate->isValid($this->array_create(10)), true);
        $this->assertEquals($validate->isValid($this->array_create(11)), false);
    }

    function test_minmax()
    {
        $validate = new ArrayLength(1, 10);

        $this->assertEquals($validate->isValid($this->array_create(0)), false);
        $this->assertEquals($validate->isValid($this->array_create(1)), true);
        $this->assertEquals($validate->isValid($this->array_create(10)), true);
        $this->assertEquals($validate->isValid($this->array_create(11)), false);
    }
}

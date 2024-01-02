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

        that($validate)->isArrayableValidation()->isTrue();
    }

    function test_min()
    {
        $validate = new ArrayLength(1, null);

        that($validate)->isValid($this->array_create(0))->isFalse();
        that($validate)->isValid($this->array_create(1))->isTrue();
        that($validate)->isValid($this->array_create(65535))->isTrue();
    }

    function test_max()
    {
        $validate = new ArrayLength(null, 10);

        that($validate)->isValid($this->array_create(0))->isTrue();
        that($validate)->isValid($this->array_create(1))->isTrue();
        that($validate)->isValid($this->array_create(10))->isTrue();
        that($validate)->isValid($this->array_create(11))->isFalse();
    }

    function test_minmax()
    {
        $validate = new ArrayLength(1, 10);

        that($validate)->isValid($this->array_create(0))->isFalse();
        that($validate)->isValid($this->array_create(1))->isTrue();
        that($validate)->isValid($this->array_create(10))->isTrue();
        that($validate)->isValid($this->array_create(11))->isFalse();
    }

    function test_getFixture()
    {
        $validate = new ArrayLength(2, 5);
        that($validate)->getFixture([1, 2, 3], [])->isSame([1, 2, 3]);
        that($validate)->getFixture([1], [])->isSame([1, 1]);
        that($validate)->getFixture([1, 2, 3, 4, 5, 6], [])->isSame([1, 2, 3, 4, 5]);
    }
}

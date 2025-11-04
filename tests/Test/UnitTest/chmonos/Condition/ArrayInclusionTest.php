<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\ArrayInclusion;

class ArrayInclusionTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_isArrayableValidation()
    {
        $validate = new ArrayInclusion(['a' => 'A', 1 => 'ONE']);

        that($validate)->isArrayableValidation()->isTrue();
    }

    function test_valid()
    {
        $validate = new ArrayInclusion(['a' => 'A', 1 => 'ONE']);

        that($validate)->isValid(['a'])->isFalse();
        that($validate)->isValid(['a', 2])->isFalse();
        that($validate)->isValid([1])->isFalse();
        that($validate)->isValid(['b', 1])->isFalse();
        that($validate)->isValid(['a', 1])->isTrue();

        that($validate)->isValid(['b', 1])->isFalse();
        that($validate)->getMessages()->is([
            "notArrayInclusion" => 'Aを含めてください',
        ]);
        that($validate)->isValid(['x'])->isFalse();
        that($validate)->getMessages()->is([
            "notArrayInclusion" => 'A, ONEを含めてください',
        ]);
    }

    function test_getFixture()
    {
        $validate = new ArrayInclusion(['a' => 'A', 1 => 'ONE']);
        that($validate)->getFixture(['x', 'a'], [])->is(["x", "a", 1]);
    }
}

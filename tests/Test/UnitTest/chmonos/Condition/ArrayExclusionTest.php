<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\ArrayExclusion;

class ArrayExclusionTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_isArrayableValidation()
    {
        $validate = new ArrayExclusion([1 => 'a', 2 => 'b']);

        that($validate)->isArrayableValidation()->isTrue();
    }

    function test_valid()
    {
        $validate = new ArrayExclusion([1 => 'a', 2 => 'b']);

        that($validate)->isValid([])->isTrue();
        that($validate)->isValid([1])->isTrue();
        that($validate)->isValid([2])->isTrue();
        that($validate)->isValid([1, 2])->isFalse();
        that($validate)->isValid(["2", "1"])->isFalse();
        that($validate)->getMessages()->is([
            "ArrayExclusionInclusion" => 'a,bは同時選択できません',
        ]);
    }

    function test_getFixture()
    {
        $validate = new ArrayExclusion([1 => 'a', 2 => 'b']);
        that($validate)->getFixture([1, 2, 3], [])->isSame([2 => 3]);
        that($validate)->getFixture([3, 4, 5], [])->isSame([3, 4, 5]);
    }
}

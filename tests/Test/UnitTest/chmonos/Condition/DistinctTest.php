<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\Distinct;

class DistinctTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_valid()
    {
        $validate = new Distinct('#\R|\s#');
        that($validate)->isValid("a\nb\nc")->isTrue();
        that($validate)->isValid("a\nb\nc\na")->isFalse();
        that($validate)->isValid("a \n b \n c \n a")->isFalse();
    }

    function test_getFixture()
    {
        $validate = new Distinct('#,#');
        that($validate)->getFixture(null, [])->isSame(null);
    }
}

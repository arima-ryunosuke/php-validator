<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\StringLength;

class StringLengthTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_min()
    {
        $validate = new StringLength(1, null);

        that($validate)->isValid(str_repeat('x', 0))->isFalse();
        that($validate)->isValid(str_repeat('x', 1))->isTrue();
        that($validate)->isValid(str_repeat('x', 65535))->isTrue();
    }

    function test_max()
    {
        $validate = new StringLength(null, 10);

        that($validate)->isValid(str_repeat('x', 0))->isTrue();
        that($validate)->isValid(str_repeat('x', 1))->isTrue();
        that($validate)->isValid(str_repeat('x', 10))->isTrue();
        that($validate)->isValid(str_repeat('x', 11))->isFalse();
    }

    function test_minmax()
    {
        $validate = new StringLength(1, 10);

        that($validate)->isValid(str_repeat('x', 0))->isFalse();
        that($validate)->isValid(str_repeat('x', 1))->isTrue();
        that($validate)->isValid(str_repeat('x', 10))->isTrue();
        that($validate)->isValid(str_repeat('x', 11))->isFalse();
    }

    function test_different()
    {
        $validate = new StringLength(3, 3);

        $validate->isValid(str_repeat('x', 0));
        $messages = $validate->getMessages();
        that($messages)[StringLength::DIFFERENT]->contains('3文字で');
    }
}

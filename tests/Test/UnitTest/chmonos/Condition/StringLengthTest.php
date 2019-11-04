<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\StringLength;

class StringLengthTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_min()
    {
        $validate = new StringLength(1, null);

        $this->assertEquals($validate->isValid(str_repeat('x', 0)), false);
        $this->assertEquals($validate->isValid(str_repeat('x', 1)), true);
        $this->assertEquals($validate->isValid(str_repeat('x', 65535)), true);
    }

    function test_max()
    {
        $validate = new StringLength(null, 10);

        $this->assertEquals($validate->isValid(str_repeat('x', 0)), true);
        $this->assertEquals($validate->isValid(str_repeat('x', 1)), true);
        $this->assertEquals($validate->isValid(str_repeat('x', 10)), true);
        $this->assertEquals($validate->isValid(str_repeat('x', 11)), false);
    }

    function test_minmax()
    {
        $validate = new StringLength(1, 10);

        $this->assertEquals($validate->isValid(str_repeat('x', 0)), false);
        $this->assertEquals($validate->isValid(str_repeat('x', 1)), true);
        $this->assertEquals($validate->isValid(str_repeat('x', 10)), true);
        $this->assertEquals($validate->isValid(str_repeat('x', 11)), false);
    }

    function test_different()
    {
        $validate = new StringLength(3, 3);

        $validate->isValid(str_repeat('x', 0));
        $messages = $validate->getMessages();
        $this->assertContains('3文字で', $messages[StringLength::DIFFERENT]);
    }
}

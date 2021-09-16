<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\StringLength;

class StringLengthTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_min()
    {
        $validate = new StringLength(1, null);

        $this->assertEquals(false, $validate->isValid(str_repeat('x', 0)));
        $this->assertEquals(true, $validate->isValid(str_repeat('x', 1)));
        $this->assertEquals(true, $validate->isValid(str_repeat('x', 65535)));
    }

    function test_max()
    {
        $validate = new StringLength(null, 10);

        $this->assertEquals(true, $validate->isValid(str_repeat('x', 0)));
        $this->assertEquals(true, $validate->isValid(str_repeat('x', 1)));
        $this->assertEquals(true, $validate->isValid(str_repeat('x', 10)));
        $this->assertEquals(false, $validate->isValid(str_repeat('x', 11)));
    }

    function test_minmax()
    {
        $validate = new StringLength(1, 10);

        $this->assertEquals(false, $validate->isValid(str_repeat('x', 0)));
        $this->assertEquals(true, $validate->isValid(str_repeat('x', 1)));
        $this->assertEquals(true, $validate->isValid(str_repeat('x', 10)));
        $this->assertEquals(false, $validate->isValid(str_repeat('x', 11)));
    }

    function test_different()
    {
        $validate = new StringLength(3, 3);

        $validate->isValid(str_repeat('x', 0));
        $messages = $validate->getMessages();
        $this->assertStringContainsString('3文字で', $messages[StringLength::DIFFERENT]);
    }
}

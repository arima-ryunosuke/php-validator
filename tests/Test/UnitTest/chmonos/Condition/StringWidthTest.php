<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\StringWidth;

class StringWidthTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_min()
    {
        $validate = new StringWidth(2, null);

        $this->assertEquals(false, $validate->isValid(''));
        $this->assertEquals(false, $validate->isValid('x'));
        $this->assertEquals(true, $validate->isValid('xx'));
        $this->assertEquals(true, $validate->isValid('ｘ'));
    }

    function test_max()
    {
        $validate = new StringWidth(null, 5);

        $this->assertEquals(true, $validate->isValid('xxxxx'));
        $this->assertEquals(true, $validate->isValid('xxxｘ'));
        $this->assertEquals(false, $validate->isValid('xxxxxx'));
        $this->assertEquals(false, $validate->isValid('xxxxｘ'));
    }

    function test_minmax()
    {
        $validate = new StringWidth(2, 5);

        $this->assertEquals(false, $validate->isValid("x"));
        $this->assertEquals(true, $validate->isValid("ｘ"));
        $this->assertEquals(true, $validate->isValid("xｘｘ"));
        $this->assertEquals(false, $validate->isValid("xxｘｘ"));
    }

    function test_different()
    {
        $validate = new StringWidth(3, 3);

        $validate->isValid(str_repeat('ｘｘ', 0));
        $messages = $validate->getMessages();
        $this->assertStringContainsString('3文字で', $messages[StringWidth::DIFFERENT]);
    }

    function test_getMaxLength()
    {
        $validate = new StringWidth(3, 4);
        $this->assertEquals(4, $validate->getMaxLength());
        $validate = new StringWidth(3);
        $this->assertEquals(null, $validate->getMaxLength());
    }
}

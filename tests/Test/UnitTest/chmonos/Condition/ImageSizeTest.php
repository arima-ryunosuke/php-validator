<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\ImageSize;

class ImageSizeTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_valid()
    {
        $dir = __DIR__ . '/_files/';

        $validate = new ImageSize(200, 200);
        $this->assertEquals(true, $validate->isValid($dir . 'jpg.jpg'));

        $validate = new ImageSize(1, 999);
        $this->assertEquals(false, $validate->isValid($dir . 'gif.gif'));
        $this->assertStringContainsString('横サイズ', $validate->getMessages()[ImageSize::INVALID_WIDTH]);

        $validate = new ImageSize(999, 1);
        $this->assertEquals(false, $validate->isValid($dir . 'gif.gif'));
        $this->assertStringContainsString('縦サイズ', $validate->getMessages()[ImageSize::INVALID_HEIGHT]);

        $validate = new ImageSize(360, 270);
        $this->assertEquals(true, $validate->isValid($dir . 'gif.gif'));
        $validate = new ImageSize(359, 270);
        $this->assertEquals(false, $validate->isValid($dir . 'gif.gif'));
        $validate = new ImageSize(360, 269);
        $this->assertEquals(false, $validate->isValid($dir . 'gif.gif'));

        $this->assertEquals(false, $validate->isValid($dir . 'csv.txt'));
        $this->assertEquals(false, @$validate->isValid($dir . 'notfound'));
    }

    function test_getType()
    {
        $validate = new ImageSize(200, 200);
        $this->assertEquals('file', $validate->getType());
    }
}

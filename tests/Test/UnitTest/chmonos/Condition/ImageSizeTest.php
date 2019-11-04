<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\ImageSize;

class ImageSizeTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_valid()
    {
        $dir = __DIR__ . '/_files/';

        $validate = new ImageSize(200, 200);
        $this->assertEquals($validate->isValid($dir . 'jpg.jpg'), true);

        $validate = new ImageSize(1, 999);
        $this->assertEquals($validate->isValid($dir . 'gif.gif'), false);
        $this->assertContains('横サイズ', $validate->getMessages()[ImageSize::INVALID_WIDTH]);

        $validate = new ImageSize(999, 1);
        $this->assertEquals($validate->isValid($dir . 'gif.gif'), false);
        $this->assertContains('縦サイズ', $validate->getMessages()[ImageSize::INVALID_HEIGHT]);

        $validate = new ImageSize(360, 270);
        $this->assertEquals($validate->isValid($dir . 'gif.gif'), true);
        $validate = new ImageSize(359, 270);
        $this->assertEquals($validate->isValid($dir . 'gif.gif'), false);
        $validate = new ImageSize(360, 269);
        $this->assertEquals($validate->isValid($dir . 'gif.gif'), false);

        $this->assertEquals($validate->isValid($dir . 'csv.txt'), false);
        $this->assertEquals(@$validate->isValid($dir . 'notfound'), false);
    }

    function test_getType()
    {
        $validate = new ImageSize(200, 200);
        $this->assertEquals('file', $validate->getType());
    }
}

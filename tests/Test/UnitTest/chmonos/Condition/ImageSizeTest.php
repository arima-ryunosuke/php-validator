<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\ImageSize;

class ImageSizeTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_valid()
    {
        $dir = __DIR__ . '/_files/';

        $validate = new ImageSize(200, 200);
        that($validate)->isValid($dir . 'jpg.jpg')->isTrue();

        $validate = new ImageSize(1, 999);
        that($validate)->isValid($dir . 'gif.gif')->isFalse();
        that($validate)->getMessages()[ImageSize::INVALID_WIDTH]->contains('横サイズ');

        $validate = new ImageSize(999, 1);
        that($validate)->isValid($dir . 'gif.gif')->isFalse();
        that($validate)->getMessages()[ImageSize::INVALID_HEIGHT]->contains('縦サイズ');

        $validate = new ImageSize(360, 270);
        that($validate)->isValid($dir . 'gif.gif')->isTrue();
        $validate = new ImageSize(359, 270);
        that($validate)->isValid($dir . 'gif.gif')->isFalse();
        $validate = new ImageSize(360, 269);
        that($validate)->isValid($dir . 'gif.gif')->isFalse();

        that($validate)->isValid($dir . 'csv.txt')->isFalse();
        @that($validate)->isValid($dir . 'notfound')->isFalse();
    }

    function test_getType()
    {
        $validate = new ImageSize(200, 200);
        that($validate)->getType()->is("file");
    }
}

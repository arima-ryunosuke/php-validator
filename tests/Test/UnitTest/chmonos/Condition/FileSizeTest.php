<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\FileSize;

class FileSizeTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_valid()
    {
        $dir = __DIR__ . '/_files/';

        // 10240バイト以下
        $validate = new FileSize(10240);
        $this->assertEquals(true, $validate->isValid($dir . '10239.dat'));
        $this->assertEquals(true, $validate->isValid($dir . '10240.dat'));
        $this->assertEquals(false, $validate->isValid($dir . '10241.dat'));

        // 存在しないファイル
        $validate = new FileSize(10);
        $this->assertEquals(false, @$validate->isValid($dir . 'notfound'));
    }

    function test_getType()
    {
        $validate = new FileSize(10240);
        $this->assertEquals('file', $validate->getType());
    }
}

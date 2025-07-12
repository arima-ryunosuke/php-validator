<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\FileSize;
use ryunosuke\chmonos\UploadedFile;

class FileSizeTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_valid()
    {
        $dir = __DIR__ . '/_files/';

        // 10240バイト以下
        $validate = new FileSize(10240);
        that($validate)->isValid($dir . '10239.dat')->isTrue();
        that($validate)->isValid($dir . '10240.dat')->isTrue();
        that($validate)->isValid($dir . '10241.dat')->isFalse();

        // 存在しないファイル
        $validate = new FileSize(10);
        @that($validate)->isValid($dir . 'notfound')->isFalse();
    }

    function test_uploaded_file()
    {
        $dir = __DIR__ . '/_files/';

        $validate = new FileSize(10240);
        that($validate)->isValid(new UploadedFile([
            'full_path' => '',
            'name'      => '',
            'type'      => '',
            'tmp_name'  => "$dir/10240.dat",
            'size'      => 0,
        ]))->isTrue();
        that($validate)->isValid(new UploadedFile([
            'full_path' => '',
            'name'      => '',
            'type'      => '',
            'tmp_name'  => "$dir/10241.dat",
            'size'      => 0,
        ]))->isFalse();
    }

    function test_getType()
    {
        $validate = new FileSize(10240);
        that($validate)->getType()->is("file");
    }

    function test_getFixture()
    {
        $validate = new FileSize(100);
        that($validate)->getFixture(null, [])->fileSizeIs(1);
    }
}

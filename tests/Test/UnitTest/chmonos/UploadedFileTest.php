<?php
namespace ryunosuke\Test\UnitTest\chmonos;

use ryunosuke\chmonos\UploadedFile;

class UploadedFileTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_all()
    {
        $file = new UploadedFile([
            'full_path' => 'directory/local.txt',
            'name'      => 'local.txt',
            'type'      => 'text/plain',
            'tmp_name'  => __FILE__,
            'size'      => 123,
        ]);

        that($file)->getRealPath()->is(__FILE__);
        that($file)->getFullpath()->is('directory/local.txt');
        that($file)->getName()->is('local.txt');
        that($file)->getType()->is('text/plain');
        that($file)->getRealType()->is('text/x-php');
        that($file)->getSize()->is(123);

        that($file)->isUploaded()->is(false);
        that((string) $file)->is(__FILE__);
    }
}

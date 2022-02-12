<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\Interfaces;
use ryunosuke\chmonos\Condition\FileName;

class FileNameTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_valid()
    {
        $validate = new FileName('json', '!', true);
        $this->assertEquals(true, $validate->isValid('a.json'));
        $this->assertEquals(true, $validate->isValid('!.json'));
        $this->assertEquals(false, $validate->isValid('a.jsonl'));
        $this->assertEquals(false, $validate->isValid('$.json'));
        $this->assertEquals(false, $validate->isValid('prn.json'));

        $validate = new FileName(null, '!', false);
        $this->assertEquals(true, $validate->isValid('ajson'));
        $this->assertEquals(true, $validate->isValid('!json'));
        $this->assertEquals(false, $validate->isValid('a.json'));
        $this->assertEquals(false, $validate->isValid('$.json'));
        $this->assertEquals(true, $validate->isValid('prn'));
    }

    function test_getImeMode()
    {
        $validate = new FileName();
        $this->assertEquals(Interfaces\ImeMode::DISABLED, $validate->getImeMode());
    }
}

<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\FileName;

class FileNameTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_valid()
    {
        $validate = new FileName('json');
        that($validate)->isValid('/full/path/name.json')->isTrue();
        that($validate)->isValid('C:\\path\\name.json')->isTrue();

        $validate = new FileName('json', '!', true);
        that($validate)->isValid('a.json')->isTrue();
        that($validate)->isValid('!.json')->isTrue();
        that($validate)->isValid('a.jsonl')->isFalse();
        that($validate)->isValid('$.json')->isFalse();
        that($validate)->isValid('prn.json')->isFalse();

        $validate = new FileName(null, '!', false);
        that($validate)->isValid('ajson')->isTrue();
        that($validate)->isValid('!json')->isTrue();
        that($validate)->isValid('a.json')->isFalse();
        that($validate)->isValid('$.json')->isFalse();
        that($validate)->isValid('prn')->isTrue();
    }

    function test_getFixture()
    {
        $validate = new FileName('txt');
        that($validate)->getFixture(null, [])->stringEndsWith('.txt');
    }
}

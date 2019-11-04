<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\EmailAddress;

class EmailAddressTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_valid()
    {
        $validate = new EmailAddress();
        $this->assertEquals($validate->isValid('test@test.com'), true);
        $this->assertEquals($validate->isValid('test@test'), true);
        $this->assertEquals($validate->isValid('test@test,com'), false);
        $this->assertEquals($validate->isValid('test.@test.com'), true);
        $this->assertEquals($validate->isValid('test@.test.com'), false);
        $this->assertEquals($validate->isValid('test.@.test.com'), false);

        $this->assertEquals($validate->isValid('test@test.ne.jp'), true);
        $this->assertEquals($validate->isValid('test@test,ne.jp'), false);
        $this->assertEquals($validate->isValid('test.@test.ne.jp'), true);
        $this->assertEquals($validate->isValid('test@.test.ne.jp'), false);
        $this->assertEquals($validate->isValid('test.@.test.ne.jp'), false);

        $this->assertEquals($validate->isValid('<test>@test.ne.jp'), false);

        $validate = new EmailAddress('#^aaa@bbb$#ui');
        $this->assertEquals($validate->isValid('aaa@bbb'), true);
        $this->assertEquals($validate->isValid('AAA@BBB'), true);
        $this->assertEquals($validate->isValid('Xaaa@bbbX'), false);
    }

    function test_getImeMode()
    {
        $validate = new EmailAddress();
        $this->assertEquals(EmailAddress::DISABLED, $validate->getImeMode());
    }

    function test_getType()
    {
        $validate = new EmailAddress();
        $this->assertEquals('text', $validate->getType());
    }
}

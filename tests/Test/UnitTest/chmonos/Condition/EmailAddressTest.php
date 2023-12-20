<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\EmailAddress;

class EmailAddressTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_valid()
    {
        $validate = new EmailAddress();
        that($validate)->isValid('test@test.com')->isTrue();
        that($validate)->isValid('test@test')->isTrue();
        that($validate)->isValid('test@test,com')->isFalse();
        that($validate)->isValid('test.@test.com')->isTrue();
        that($validate)->isValid('test@.test.com')->isFalse();
        that($validate)->isValid('test.@.test.com')->isFalse();

        that($validate)->isValid('test@test.ne.jp')->isTrue();
        that($validate)->isValid('test@test,ne.jp')->isFalse();
        that($validate)->isValid('test.@test.ne.jp')->isTrue();
        that($validate)->isValid('test@.test.ne.jp')->isFalse();
        that($validate)->isValid('test.@.test.ne.jp')->isFalse();

        that($validate)->isValid('<test>@test.ne.jp')->isFalse();

        $validate = new EmailAddress('#^aaa@bbb$#ui');
        that($validate)->isValid('aaa@bbb')->isTrue();
        that($validate)->isValid('AAA@BBB')->isTrue();
        that($validate)->isValid('Xaaa@bbbX')->isFalse();
    }

    function test_multiple()
    {
        $validate = new EmailAddress(null, '#\\n|,#');
        that($validate)->isValid('')->isTrue();
        that($validate)->isValid("test@test.com, test@test.com,\ntest@test.com")->isTrue();
        that($validate)->isValid("test@test.com, test@test.com,\naaa")->isFalse();
    }

    function test_getImeMode()
    {
        $validate = new EmailAddress();
        that($validate)->getImeMode()->is(EmailAddress::DISABLED);
    }

    function test_getType()
    {
        $validate = new EmailAddress();
        that($validate)->getType()->is("text");
    }

    function test_getMaxLength()
    {
        $validate = new EmailAddress(null);
        that($validate)->getMaxLength()->is(256);

        $validate = new EmailAddress(null, ',');
        that($validate)->getMaxLength()->is(null);
    }
}

<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\Password;

class PasswordTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_valid()
    {
        $validate = new Password('alpha');
        that($validate)->isValid('a')->isFalse();
        that($validate)->isValid('aa')->isFalse();
        that($validate)->isValid('abc')->isFalse();
        that($validate)->isValid('A')->isFalse();
        that($validate)->isValid('Aa')->isFalse();
        that($validate)->isValid('Abc')->isFalse();
        that($validate)->isValid('aBcc')->isFalse();
        that($validate)->isValid('aBcD')->isTrue();

        $validate = new Password('numeric');
        that($validate)->isValid('0')->isFalse();
        that($validate)->isValid('00')->isFalse();
        that($validate)->isValid('000')->isFalse();
        that($validate)->isValid('01')->isTrue();

        $validate = new Password('lower_numeric', 1);
        that($validate)->isValid('aaa123')->isTrue();
        that($validate)->isValid('aaa')->isFalse();
        that($validate)->isValid('123')->isFalse();
        that($validate)->isValid('AAA123')->isFalse();

        $validate = new Password('symbol');
        that($validate)->isValid('!')->isFalse();
        that($validate)->isValid('!!')->isFalse();
        that($validate)->isValid('!!!')->isFalse();
        that($validate)->isValid('!$')->isTrue();

        $validate = new Password('alpha_numeric_symbol', 1);
        that($validate)->isValid('aZ0+')->isTrue();

        $validate = new Password('alpha_numeric_symbol', 2);
        that($validate)->isValid('aZ0+')->isFalse();
        that($validate)->isValid('aZ0+aZ0+')->isFalse();
        that($validate)->isValid('aZ0+bY9!')->isTrue();
        that($validate)->isValid('abcdefghij0Z+')->isTrue();
    }

    function test_getImeMode()
    {
        $validate = new Password();
        that($validate)->getImeMode()->is(Password::DISABLED);
    }

    function test_getType()
    {
        $validate = new Password();
        that($validate)->getType()->is("password");
    }

    function test_getFixture()
    {
        $validate = new Password(['az', '19', '+-'], 2);
        that($validate)->getFixture(null, [])->containsAny(['a', 'z']);
        that($validate)->getFixture(null, [])->containsAny(['1', '9']);
        that($validate)->getFixture(null, [])->containsAny(['+', '-']);
    }
}

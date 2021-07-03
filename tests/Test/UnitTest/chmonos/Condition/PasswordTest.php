<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\Interfaces;
use ryunosuke\chmonos\Condition\Password;

class PasswordTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_valid()
    {
        $validate = new Password('alpha');
        $this->assertEquals(false, $validate->isValid('a'));
        $this->assertEquals(false, $validate->isValid('aa'));
        $this->assertEquals(false, $validate->isValid('abc'));
        $this->assertEquals(false, $validate->isValid('A'));
        $this->assertEquals(false, $validate->isValid('Aa'));
        $this->assertEquals(false, $validate->isValid('Abc'));
        $this->assertEquals(false, $validate->isValid('aBcc'));
        $this->assertEquals(true, $validate->isValid('aBcD'));

        $validate = new Password('numeric');
        $this->assertEquals(false, $validate->isValid('0'));
        $this->assertEquals(false, $validate->isValid('00'));
        $this->assertEquals(false, $validate->isValid('000'));
        $this->assertEquals(true, $validate->isValid('01'));

        $validate = new Password('lower_numeric', 1);
        $this->assertEquals(true, $validate->isValid('aaa123'));
        $this->assertEquals(false, $validate->isValid('aaa'));
        $this->assertEquals(false, $validate->isValid('123'));
        $this->assertEquals(false, $validate->isValid('AAA123'));

        $validate = new Password('symbol');
        $this->assertEquals(false, $validate->isValid('!'));
        $this->assertEquals(false, $validate->isValid('!!'));
        $this->assertEquals(false, $validate->isValid('!!!'));
        $this->assertEquals(true, $validate->isValid('!$'));

        $validate = new Password('alpha_numeric_symbol', 1);
        $this->assertEquals(true, $validate->isValid('aZ0+'));

        $validate = new Password('alpha_numeric_symbol', 2);
        $this->assertEquals(false, $validate->isValid('aZ0+'));
        $this->assertEquals(false, $validate->isValid('aZ0+aZ0+'));
        $this->assertEquals(true, $validate->isValid('aZ0+bY9!'));
        $this->assertEquals(true, $validate->isValid('abcdefghij0Z+'));
    }

    function test_getImeMode()
    {
        $validate = new Password();
        $this->assertEquals(Interfaces\ImeMode::DISABLED, $validate->getImeMode());
    }

    function test_getType()
    {
        $validate = new Password();
        $this->assertEquals('password', $validate->getType());
    }
}

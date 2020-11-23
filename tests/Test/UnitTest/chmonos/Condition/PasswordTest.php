<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\Interfaces;
use ryunosuke\chmonos\Condition\Password;

class PasswordTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_valid()
    {
        $validate = new Password('alpha');
        $this->assertEquals($validate->isValid('a'), false);
        $this->assertEquals($validate->isValid('aa'), false);
        $this->assertEquals($validate->isValid('abc'), false);
        $this->assertEquals($validate->isValid('A'), false);
        $this->assertEquals($validate->isValid('Aa'), false);
        $this->assertEquals($validate->isValid('Abc'), false);
        $this->assertEquals($validate->isValid('aBcc'), false);
        $this->assertEquals($validate->isValid('aBcD'), true);

        $validate = new Password('numeric');
        $this->assertEquals($validate->isValid('0'), false);
        $this->assertEquals($validate->isValid('00'), false);
        $this->assertEquals($validate->isValid('000'), false);
        $this->assertEquals($validate->isValid('01'), true);

        $validate = new Password('lower_numeric', 1);
        $this->assertEquals($validate->isValid('aaa123'), true);
        $this->assertEquals($validate->isValid('aaa'), false);
        $this->assertEquals($validate->isValid('123'), false);
        $this->assertEquals($validate->isValid('AAA123'), false);

        $validate = new Password('symbol');
        $this->assertEquals($validate->isValid('!'), false);
        $this->assertEquals($validate->isValid('!!'), false);
        $this->assertEquals($validate->isValid('!!!'), false);
        $this->assertEquals($validate->isValid('!$'), true);

        $validate = new Password('alpha_numeric_symbol', 1);
        $this->assertEquals($validate->isValid('aZ0+'), true);

        $validate = new Password('alpha_numeric_symbol', 2);
        $this->assertEquals($validate->isValid('aZ0+'), false);
        $this->assertEquals($validate->isValid('aZ0+aZ0+'), false);
        $this->assertEquals($validate->isValid('aZ0+bY9!'), true);
        $this->assertEquals($validate->isValid('abcdefghij0Z+'), true);
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

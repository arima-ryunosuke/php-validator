<?php
namespace ryunosuke\Test\UnitTest\chmonos;

use ryunosuke\chmonos\Token;

class TokenTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_getToken()
    {
        $token = new Token('hoge', 'fuga');
        $this->assertEquals('fuga', $token->getToken());
    }

    function test_validate()
    {
        $token = new Token('hoge', 'fuga');
        $_POST['hoge'] = 'fuga';

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $this->assertTrue($token->validate());

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $this->assertTrue($token->validate());

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'none';
        $this->assertTrue($token->validate());

        $_POST['hoge'] = 'invalid';
        $this->assertFalse($token->validate());
    }

    function test_render()
    {
        $token = new Token('hoge', 'fuga');
        $this->assertEquals("<input type='hidden' name='hoge' value='fuga'>", $token->render());
    }
}

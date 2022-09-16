<?php
namespace ryunosuke\Test\UnitTest\chmonos;

use ryunosuke\chmonos\Token;

class TokenTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_getToken()
    {
        $token = new Token('hoge', 'fuga');
        that($token)->getToken()->is('fuga');
    }

    function test_validate()
    {
        $token = new Token('hoge', 'fuga');
        $_POST['hoge'] = 'fuga';

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        that($token)->validate()->isTrue();

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        that($token)->validate()->isTrue();

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'none';
        that($token)->validate()->isTrue();

        $_POST['hoge'] = 'invalid';
        that($token)->validate()->isFalse();
    }

    function test_render()
    {
        $token = new Token('hoge', 'fuga');
        that($token)->render()->htmlMatchesArray([
            "input" => [
                "type"  => "hidden",
                "name"  => "hoge",
                "value" => "fuga",
            ],
        ]);
    }
}

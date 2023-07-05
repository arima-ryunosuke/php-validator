<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\DataUri;

class DataUriTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_valid()
    {
        $validate = new DataUri([]);
        that($validate)->isValid("hogera")->isFalse();
        that($validate)->isValid("data:image/png;base64,invalid;base64")->isFalse();

        $validate = new DataUri(["size" => 64]);
        that($validate)->isValid("data:image/png;base64," . base64_encode(str_repeat("x", 64)))->isTrue();
        that($validate)->isValid("data:image/png;base64," . base64_encode(str_repeat("x", 65)))->isFalse();

        $validate = new DataUri(["type" => ["png", "jpg"]]);
        that($validate)->isValid("data:image/png;charset=8bit;base64," . "eA")->isTrue();
        that($validate)->isValid("data:image/png;base64," . "eA")->isTrue();
        that($validate)->isValid("data:image/jpeg;base64," . "eA")->isTrue();
        that($validate)->isValid("data:image/hoge;base64," . "eA")->isFalse();
        that($validate)->isValid("data:image/gif;base64," . "eA")->isFalse();

        $validate = new DataUri(["type" => ["png", "jpg"], "mimetype" => ["image/hoge" => ["png"]]]);
        that($validate)->isValid("data:image/png;base64," . "eA")->isTrue();
        that($validate)->isValid("data:image/jpeg;base64," . "eA")->isTrue();
        that($validate)->isValid("data:image/hoge;base64," . "eA")->isTrue();
        that($validate)->isValid("data:image/gif;base64," . "eA")->isFalse();
    }

    function test_getValue()
    {
        $base64_hello_world = base64_encode("Hello, world");

        $validate = new DataUri([], true);
        that($validate)->getValue("data:text/plain,$base64_hello_world")->is("Hello, world");
        that($validate)->getValue("data:text/plain;charset=utf-8,$base64_hello_world")->is("Hello, world");
        that($validate)->getValue("data:text/plain;charset=utf-8;base64,$base64_hello_world")->is("Hello, world");

        $validate = new DataUri([], false);
        that($validate)->getValue("data:text/plain,$base64_hello_world")->is("data:text/plain,$base64_hello_world");
        that($validate)->getValue("data:text/plain;charset=utf-8,$base64_hello_world")->is("data:text/plain;charset=utf-8,$base64_hello_world");
        that($validate)->getValue("data:text/plain;charset=utf-8;base64,$base64_hello_world")->is("data:text/plain;charset=utf-8;base64,$base64_hello_world");
    }
}

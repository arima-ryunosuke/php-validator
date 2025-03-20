<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\DataUri;
use function ryunosuke\chmonos\dataurl_decode;

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

        $validate = new DataUri(type: ['画像' => ['image/*'], 'CSV' => 'text/csv']);
        that($validate)->isValid("data:image/png;base64," . "eA")->isTrue();
        that($validate)->isValid("data:image/jpeg;base64," . "eA")->isTrue();
        that($validate)->isValid("data:image/hoge;base64," . "eA")->isTrue();
        that($validate)->isValid("data:image/gif;base64," . "eA")->isTrue();
        that($validate)->isValid("data:audio/wav;base64," . "eA")->isFalse();
        that($validate)->isValid("data:text/csv;base64," . "eA")->isTrue();
        that($validate)->isValid("data:text/plain;base64," . "eA")->isFalse();
    }

    function test_getValue()
    {
        $base64_hello_world = base64_encode("Hello, world");

        $validate = new DataUri([], true);
        that($validate)->getValue("Hello, world")->is("data:text/plain;charset=US-ASCII;base64,$base64_hello_world");
        that($validate)->getValue("data:text/plain;charset=US-ASCII;base64,$base64_hello_world")->is("data:text/plain;charset=US-ASCII;base64,$base64_hello_world");

        $validate = new DataUri([], false);
        that($validate)->getValue("data:text/plain,$base64_hello_world")->is("data:text/plain,$base64_hello_world");
    }

    function test_getAccepts()
    {
        $validate = new DataUri(type: ['画像' => ['image/*'], 'CSV' => 'text/csv']);
        that($validate)->getAccepts()->is([".csv", "application/csv", "text/csv", "image/*"]);
    }

    function test_getFixture()
    {
        $validate = new DataUri(['size' => 128, 'type' => ['png', 'jpg']]);
        $datauri = $validate->getFixture(null, []);
        $raw = dataurl_decode($datauri, $metadata);
        that(strlen($raw))->is(128);
        that($metadata['mimetype'])->isAny(['image/png', 'image/jpeg']);
    }
}

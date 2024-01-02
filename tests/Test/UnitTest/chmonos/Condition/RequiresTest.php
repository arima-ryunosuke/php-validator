<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\Requires;

class RequiresTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test___construct()
    {
        that(Requires::class)->new(['hoge' => ['==', ['array']]])->wasThrown(new \InvalidArgumentException('must be scalar'));
        that(Requires::class)->new(['hoge' => ['in', 'scalar']])->wasThrown(new \InvalidArgumentException('must be array'));
    }

    function test_isArrayableValidation()
    {
        $validate = new Requires();

        that($validate)->isArrayableValidation()->isTrue();
    }

    function test_getFields_getPropagation()
    {
        $validate = new Requires('aaa', ['bbb' => ['==', '123']]);
        that($validate)->getFields()->is(["aaa", "bbb"]);
        that($validate)->getPropagation()->is(["aaa", "bbb"]);
    }

    function test_valid()
    {
        $validate = new Requires();

        that($validate)->isValid('-1')->isTrue();
        that($validate)->isValid('0')->isTrue();
        that($validate)->isValid('1')->isTrue();
        that($validate)->isValid(' ')->isTrue();
        that($validate)->isValid([1])->isTrue();
        that($validate)->isValid('')->isFalse();
        that($validate)->isValid(null)->isFalse();
        that($validate)->isValid([])->isFalse();
        that($validate)->isValid([null])->isTrue();
    }

    function test_valid_equal()
    {
        $validate = new Requires(['dependent' => ['==', '123']]);
        that($validate)->isValid('', ['dependent' => '123'])->isFalse();
        that($validate)->isValid('', ['dependent' => 123])->isFalse();
        that($validate)->isValid('', ['dependent' => '456'])->isTrue();
        that($validate)->isValid('ok', ['dependent' => ''])->isTrue();

        $validate = new Requires(['dependent' => ['===', '123']]);
        that($validate)->isValid('', ['dependent' => '123'])->isFalse();
        that($validate)->isValid('', ['dependent' => 123])->isTrue();
        that($validate)->isValid('', ['dependent' => '456'])->isTrue();
        that($validate)->isValid('ok', ['dependent' => ''])->isTrue();
    }

    function test_valid_notequal()
    {
        $validate = new Requires(['dependent' => ['!=', '123']]);
        that($validate)->isValid('', ['dependent' => '123'])->isTrue();
        that($validate)->isValid('', ['dependent' => 123])->isTrue();
        that($validate)->isValid('', ['dependent' => '456'])->isFalse();
        that($validate)->isValid('ok', ['dependent' => ''])->isTrue();

        $validate = new Requires(['dependent' => ['!==', '123']]);
        that($validate)->isValid('', ['dependent' => '123'])->isTrue();
        that($validate)->isValid('', ['dependent' => 123])->isFalse();
        that($validate)->isValid('', ['dependent' => '456'])->isFalse();
        that($validate)->isValid('ok', ['dependent' => ''])->isTrue();
    }

    function test_valid_lt()
    {
        $validate = new Requires(['dependent' => ['<', '123']]);
        that($validate)->isValid('', ['dependent' => '122'])->isFalse();
        that($validate)->isValid('', ['dependent' => '123'])->isTrue();
        that($validate)->isValid('', ['dependent' => '124'])->isTrue();
        that($validate)->isValid('ok', ['dependent' => ''])->isTrue();

        $validate = new Requires(['dependent' => ['<=', '123']]);
        that($validate)->isValid('', ['dependent' => '122'])->isFalse();
        that($validate)->isValid('', ['dependent' => '123'])->isFalse();
        that($validate)->isValid('', ['dependent' => '124'])->isTrue();
        that($validate)->isValid('ok', ['dependent' => ''])->isTrue();
    }

    function test_valid_gt()
    {
        $validate = new Requires(['dependent' => ['>', '123']]);
        that($validate)->isValid('', ['dependent' => '122'])->isTrue();
        that($validate)->isValid('', ['dependent' => '123'])->isTrue();
        that($validate)->isValid('', ['dependent' => '124'])->isFalse();
        that($validate)->isValid('ok', ['dependent' => ''])->isTrue();

        $validate = new Requires(['dependent' => ['>=', '123']]);
        that($validate)->isValid('', ['dependent' => '122'])->isTrue();
        that($validate)->isValid('', ['dependent' => '123'])->isFalse();
        that($validate)->isValid('', ['dependent' => '124'])->isFalse();
        that($validate)->isValid('ok', ['dependent' => ''])->isTrue();
    }

    function test_valid_any()
    {
        $validate = new Requires(['dependent' => ['any', ['y', 'z']]]);
        that($validate)->isValid('', ['dependent' => 'x'])->isTrue();
        that($validate)->isValid('', ['dependent' => 'y'])->isFalse();
        that($validate)->isValid('', ['dependent' => 'z'])->isFalse();
        that($validate)->isValid('ok', ['dependent' => ''])->isTrue();

        $validate = new Requires(['dependent' => ['notany', ['y', 'z']]]);
        that($validate)->isValid('', ['dependent' => 'x'])->isFalse();
        that($validate)->isValid('', ['dependent' => 'y'])->isTrue();
        that($validate)->isValid('', ['dependent' => 'z'])->isTrue();
        that($validate)->isValid('ok', ['dependent' => ''])->isTrue();
    }

    function test_valid_in()
    {
        $validate = new Requires(['dependent' => ['in', ['y', 'z']]]);
        that($validate)->isValid('', ['dependent' => ['x', 'y', 'z']])->isFalse();
        that($validate)->isValid('', ['dependent' => ['y', 'z']])->isFalse();
        that($validate)->isValid('', ['dependent' => ['y']])->isTrue();
        that($validate)->isValid('', ['dependent' => ['z']])->isTrue();
        that($validate)->isValid('ok', ['dependent' => []])->isTrue();

        $validate = new Requires(['dependent' => ['notin', ['y', 'z']]]);
        that($validate)->isValid('', ['dependent' => ['x', 'y', 'z']])->isTrue();
        that($validate)->isValid('', ['dependent' => ['y', 'z']])->isTrue();
        that($validate)->isValid('', ['dependent' => ['y']])->isFalse();
        that($validate)->isValid('', ['dependent' => ['z']])->isFalse();
        that($validate)->isValid('ok', ['dependent' => []])->isTrue();
    }

    function test_valid_and()
    {
        $validate = new Requires([
            'dependent1' => ['==', '123'],
            'dependent2' => ['==', '456'],
        ]);
        that($validate)->isValid('', [
            'dependent1' => '123',
            'dependent2' => '456',
        ])->isFalse();
        that($validate)->isValid('', [
            'dependent1' => '123',
            'dependent2' => 'xxx',
        ])->isTrue();
        that($validate)->isValid('', [
            'dependent1' => 'xxx',
            'dependent2' => '456',
        ])->isTrue();
        that($validate)->isValid('ok', [
            'dependent1' => '123',
            'dependent2' => '456',
        ])->isTrue();
    }

    function test_valid_or()
    {
        $validate = new Requires(...[
            ['dependent1' => ['==', '123']],
            ['dependent2' => ['==', '456']],
        ]);
        that($validate)->isValid('', [
            'dependent1' => '123',
            'dependent2' => '456',
        ])->isFalse();
        that($validate)->isValid('', [
            'dependent1' => '123',
            'dependent2' => 'xxx',
        ])->isFalse();
        that($validate)->isValid('', [
            'dependent1' => 'xxx',
            'dependent2' => '456',
        ])->isFalse();
        that($validate)->isValid('ok', [
            'dependent1' => '123',
            'dependent2' => '456',
        ])->isTrue();
    }

    function test_getFixture()
    {
        $validate = new Requires();
        that($validate)->getFixture(null, [])->isSame(null);
    }
}

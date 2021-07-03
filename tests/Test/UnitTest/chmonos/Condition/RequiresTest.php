<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\Requires;

class RequiresTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test___construct()
    {
        $this->assertException(new \InvalidArgumentException("must be scalar"), function () {
            new Requires([
                'hoge' => ['==', ['array']],
            ]);
        });

        $this->assertException(new \InvalidArgumentException("must be array"), function () {
            new Requires([
                'hoge' => ['in', 'scalar'],
            ]);
        });
    }

    function test_isArrayableValidation()
    {
        $validate = new Requires();

        $this->assertEquals(true, $validate->isArrayableValidation());
    }

    function test_getFields_getPropagation()
    {
        $condition = new Requires('aaa', ['bbb' => ['==', '123']]);
        $this->assertEquals(['aaa', 'bbb'], $condition->getFields());
        $this->assertEquals(['aaa', 'bbb'], $condition->getPropagation());
    }

    function test_valid()
    {
        $validate = new Requires();

        $this->assertEquals(true, $validate->isValid('-1'));
        $this->assertEquals(true, $validate->isValid('0'));
        $this->assertEquals(true, $validate->isValid('1'));
        $this->assertEquals(true, $validate->isValid(' '));
        $this->assertEquals(true, $validate->isValid([1]));
        $this->assertEquals(false, $validate->isValid(''));
        $this->assertEquals(false, $validate->isValid(null));
        $this->assertEquals(false, $validate->isValid([]));
        $this->assertEquals(true, $validate->isValid([null]));
    }

    function test_valid_equal()
    {
        $validate = new Requires(['dependent' => ['==', '123']]);
        $this->assertEquals(false, $validate->isValid('', ['dependent' => '123']));
        $this->assertEquals(false, $validate->isValid('', ['dependent' => 123]));
        $this->assertEquals(true, $validate->isValid('', ['dependent' => '456']));
        $this->assertEquals(true, $validate->isValid('ok', ['dependent' => '']));

        $validate = new Requires(['dependent' => ['===', '123']]);
        $this->assertEquals(false, $validate->isValid('', ['dependent' => '123']));
        $this->assertEquals(true, $validate->isValid('', ['dependent' => 123]));
        $this->assertEquals(true, $validate->isValid('', ['dependent' => '456']));
        $this->assertEquals(true, $validate->isValid('ok', ['dependent' => '']));
    }

    function test_valid_notequal()
    {
        $validate = new Requires(['dependent' => ['!=', '123']]);
        $this->assertEquals(true, $validate->isValid('', ['dependent' => '123']));
        $this->assertEquals(true, $validate->isValid('', ['dependent' => 123]));
        $this->assertEquals(false, $validate->isValid('', ['dependent' => '456']));
        $this->assertEquals(true, $validate->isValid('ok', ['dependent' => '']));

        $validate = new Requires(['dependent' => ['!==', '123']]);
        $this->assertEquals(true, $validate->isValid('', ['dependent' => '123']));
        $this->assertEquals(false, $validate->isValid('', ['dependent' => 123]));
        $this->assertEquals(false, $validate->isValid('', ['dependent' => '456']));
        $this->assertEquals(true, $validate->isValid('ok', ['dependent' => '']));
    }

    function test_valid_lt()
    {
        $validate = new Requires(['dependent' => ['<', '123']]);
        $this->assertEquals(false, $validate->isValid('', ['dependent' => '122']));
        $this->assertEquals(true, $validate->isValid('', ['dependent' => '123']));
        $this->assertEquals(true, $validate->isValid('', ['dependent' => '124']));
        $this->assertEquals(true, $validate->isValid('ok', ['dependent' => '']));

        $validate = new Requires(['dependent' => ['<=', '123']]);
        $this->assertEquals(false, $validate->isValid('', ['dependent' => '122']));
        $this->assertEquals(false, $validate->isValid('', ['dependent' => '123']));
        $this->assertEquals(true, $validate->isValid('', ['dependent' => '124']));
        $this->assertEquals(true, $validate->isValid('ok', ['dependent' => '']));
    }

    function test_valid_gt()
    {
        $validate = new Requires(['dependent' => ['>', '123']]);
        $this->assertEquals(true, $validate->isValid('', ['dependent' => '122']));
        $this->assertEquals(true, $validate->isValid('', ['dependent' => '123']));
        $this->assertEquals(false, $validate->isValid('', ['dependent' => '124']));
        $this->assertEquals(true, $validate->isValid('ok', ['dependent' => '']));

        $validate = new Requires(['dependent' => ['>=', '123']]);
        $this->assertEquals(true, $validate->isValid('', ['dependent' => '122']));
        $this->assertEquals(false, $validate->isValid('', ['dependent' => '123']));
        $this->assertEquals(false, $validate->isValid('', ['dependent' => '124']));
        $this->assertEquals(true, $validate->isValid('ok', ['dependent' => '']));
    }

    function test_valid_any()
    {
        $validate = new Requires(['dependent' => ['any', ['y', 'z']]]);
        $this->assertEquals(true, $validate->isValid('', ['dependent' => 'x']));
        $this->assertEquals(false, $validate->isValid('', ['dependent' => 'y']));
        $this->assertEquals(false, $validate->isValid('', ['dependent' => 'z']));
        $this->assertEquals(true, $validate->isValid('ok', ['dependent' => '']));

        $validate = new Requires(['dependent' => ['notany', ['y', 'z']]]);
        $this->assertEquals(false, $validate->isValid('', ['dependent' => 'x']));
        $this->assertEquals(true, $validate->isValid('', ['dependent' => 'y']));
        $this->assertEquals(true, $validate->isValid('', ['dependent' => 'z']));
        $this->assertEquals(true, $validate->isValid('ok', ['dependent' => '']));
    }

    function test_valid_in()
    {
        $validate = new Requires(['dependent' => ['in', ['y', 'z']]]);
        $this->assertEquals(false, $validate->isValid('', ['dependent' => ['x', 'y', 'z']]));
        $this->assertEquals(false, $validate->isValid('', ['dependent' => ['y', 'z']]));
        $this->assertEquals(true, $validate->isValid('', ['dependent' => ['y']]));
        $this->assertEquals(true, $validate->isValid('', ['dependent' => ['z']]));
        $this->assertEquals(true, $validate->isValid('ok', ['dependent' => []]));

        $validate = new Requires(['dependent' => ['notin', ['y', 'z']]]);
        $this->assertEquals(true, $validate->isValid('', ['dependent' => ['x', 'y', 'z']]));
        $this->assertEquals(true, $validate->isValid('', ['dependent' => ['y', 'z']]));
        $this->assertEquals(false, $validate->isValid('', ['dependent' => ['y']]));
        $this->assertEquals(false, $validate->isValid('', ['dependent' => ['z']]));
        $this->assertEquals(true, $validate->isValid('ok', ['dependent' => []]));
    }

    function test_valid_and()
    {
        $validate = new Requires([
            'dependent1' => ['==', '123'],
            'dependent2' => ['==', '456'],
        ]);
        $this->assertEquals(false, $validate->isValid('', [
            'dependent1' => '123',
            'dependent2' => '456',
        ]));
        $this->assertEquals(true, $validate->isValid('', [
            'dependent1' => '123',
            'dependent2' => 'xxx',
        ]));
        $this->assertEquals(true, $validate->isValid('', [
            'dependent1' => 'xxx',
            'dependent2' => '456',
        ]));
        $this->assertEquals(true, $validate->isValid('ok', [
            'dependent1' => '123',
            'dependent2' => '456',
        ]));
    }

    function test_valid_or()
    {
        $validate = new Requires(...[
            ['dependent1' => ['==', '123']],
            ['dependent2' => ['==', '456']],
        ]);
        $this->assertEquals(false, $validate->isValid('', [
            'dependent1' => '123',
            'dependent2' => '456',
        ]));
        $this->assertEquals(false, $validate->isValid('', [
            'dependent1' => '123',
            'dependent2' => 'xxx',
        ]));
        $this->assertEquals(false, $validate->isValid('', [
            'dependent1' => 'xxx',
            'dependent2' => '456',
        ]));
        $this->assertEquals(true, $validate->isValid('ok', [
            'dependent1' => '123',
            'dependent2' => '456',
        ]));
    }
}

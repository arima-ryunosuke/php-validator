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

        $this->assertEquals($validate->isArrayableValidation(), true);
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

        $this->assertEquals($validate->isValid('-1'), true);
        $this->assertEquals($validate->isValid('0'), true);
        $this->assertEquals($validate->isValid('1'), true);
        $this->assertEquals($validate->isValid(' '), true);
        $this->assertEquals($validate->isValid([1]), true);
        $this->assertEquals($validate->isValid(''), false);
        $this->assertEquals($validate->isValid(null), false);
        $this->assertEquals($validate->isValid([]), false);
        $this->assertEquals($validate->isValid([null]), true);
    }

    function test_valid_equal()
    {
        $validate = new Requires(['dependent' => ['==', '123']]);
        $this->assertEquals($validate->isValid('', ['dependent' => '123']), false);
        $this->assertEquals($validate->isValid('', ['dependent' => 123]), false);
        $this->assertEquals($validate->isValid('', ['dependent' => '456']), true);
        $this->assertEquals($validate->isValid('ok', ['dependent' => '']), true);

        $validate = new Requires(['dependent' => ['===', '123']]);
        $this->assertEquals($validate->isValid('', ['dependent' => '123']), false);
        $this->assertEquals($validate->isValid('', ['dependent' => 123]), true);
        $this->assertEquals($validate->isValid('', ['dependent' => '456']), true);
        $this->assertEquals($validate->isValid('ok', ['dependent' => '']), true);
    }

    function test_valid_notequal()
    {
        $validate = new Requires(['dependent' => ['!=', '123']]);
        $this->assertEquals($validate->isValid('', ['dependent' => '123']), true);
        $this->assertEquals($validate->isValid('', ['dependent' => 123]), true);
        $this->assertEquals($validate->isValid('', ['dependent' => '456']), false);
        $this->assertEquals($validate->isValid('ok', ['dependent' => '']), true);

        $validate = new Requires(['dependent' => ['!==', '123']]);
        $this->assertEquals($validate->isValid('', ['dependent' => '123']), true);
        $this->assertEquals($validate->isValid('', ['dependent' => 123]), false);
        $this->assertEquals($validate->isValid('', ['dependent' => '456']), false);
        $this->assertEquals($validate->isValid('ok', ['dependent' => '']), true);
    }

    function test_valid_lt()
    {
        $validate = new Requires(['dependent' => ['<', '123']]);
        $this->assertEquals($validate->isValid('', ['dependent' => '122']), false);
        $this->assertEquals($validate->isValid('', ['dependent' => '123']), true);
        $this->assertEquals($validate->isValid('', ['dependent' => '124']), true);
        $this->assertEquals($validate->isValid('ok', ['dependent' => '']), true);

        $validate = new Requires(['dependent' => ['<=', '123']]);
        $this->assertEquals($validate->isValid('', ['dependent' => '122']), false);
        $this->assertEquals($validate->isValid('', ['dependent' => '123']), false);
        $this->assertEquals($validate->isValid('', ['dependent' => '124']), true);
        $this->assertEquals($validate->isValid('ok', ['dependent' => '']), true);
    }

    function test_valid_gt()
    {
        $validate = new Requires(['dependent' => ['>', '123']]);
        $this->assertEquals($validate->isValid('', ['dependent' => '122']), true);
        $this->assertEquals($validate->isValid('', ['dependent' => '123']), true);
        $this->assertEquals($validate->isValid('', ['dependent' => '124']), false);
        $this->assertEquals($validate->isValid('ok', ['dependent' => '']), true);

        $validate = new Requires(['dependent' => ['>=', '123']]);
        $this->assertEquals($validate->isValid('', ['dependent' => '122']), true);
        $this->assertEquals($validate->isValid('', ['dependent' => '123']), false);
        $this->assertEquals($validate->isValid('', ['dependent' => '124']), false);
        $this->assertEquals($validate->isValid('ok', ['dependent' => '']), true);
    }

    function test_valid_any()
    {
        $validate = new Requires(['dependent' => ['any', ['y', 'z']]]);
        $this->assertEquals($validate->isValid('', ['dependent' => 'x']), true);
        $this->assertEquals($validate->isValid('', ['dependent' => 'y']), false);
        $this->assertEquals($validate->isValid('', ['dependent' => 'z']), false);
        $this->assertEquals($validate->isValid('ok', ['dependent' => '']), true);

        $validate = new Requires(['dependent' => ['notany', ['y', 'z']]]);
        $this->assertEquals($validate->isValid('', ['dependent' => 'x']), false);
        $this->assertEquals($validate->isValid('', ['dependent' => 'y']), true);
        $this->assertEquals($validate->isValid('', ['dependent' => 'z']), true);
        $this->assertEquals($validate->isValid('ok', ['dependent' => '']), true);
    }

    function test_valid_in()
    {
        $validate = new Requires(['dependent' => ['in', ['y', 'z']]]);
        $this->assertEquals($validate->isValid('', ['dependent' => ['x', 'y', 'z']]), false);
        $this->assertEquals($validate->isValid('', ['dependent' => ['y', 'z']]), false);
        $this->assertEquals($validate->isValid('', ['dependent' => ['y']]), true);
        $this->assertEquals($validate->isValid('', ['dependent' => ['z']]), true);
        $this->assertEquals($validate->isValid('ok', ['dependent' => []]), true);

        $validate = new Requires(['dependent' => ['notin', ['y', 'z']]]);
        $this->assertEquals($validate->isValid('', ['dependent' => ['x', 'y', 'z']]), true);
        $this->assertEquals($validate->isValid('', ['dependent' => ['y', 'z']]), true);
        $this->assertEquals($validate->isValid('', ['dependent' => ['y']]), false);
        $this->assertEquals($validate->isValid('', ['dependent' => ['z']]), false);
        $this->assertEquals($validate->isValid('ok', ['dependent' => []]), true);
    }

    function test_valid_and()
    {
        $validate = new Requires([
            'dependent1' => ['==', '123'],
            'dependent2' => ['==', '456'],
        ]);
        $this->assertEquals($validate->isValid('', [
            'dependent1' => '123',
            'dependent2' => '456',
        ]), false);
        $this->assertEquals($validate->isValid('', [
            'dependent1' => '123',
            'dependent2' => 'xxx',
        ]), true);
        $this->assertEquals($validate->isValid('', [
            'dependent1' => 'xxx',
            'dependent2' => '456',
        ]), true);
        $this->assertEquals($validate->isValid('ok', [
            'dependent1' => '123',
            'dependent2' => '456',
        ]), true);
    }

    function test_valid_or()
    {
        $validate = new Requires(...[
            ['dependent1' => ['==', '123']],
            ['dependent2' => ['==', '456']],
        ]);
        $this->assertEquals($validate->isValid('', [
            'dependent1' => '123',
            'dependent2' => '456',
        ]), false);
        $this->assertEquals($validate->isValid('', [
            'dependent1' => '123',
            'dependent2' => 'xxx',
        ]), false);
        $this->assertEquals($validate->isValid('', [
            'dependent1' => 'xxx',
            'dependent2' => '456',
        ]), false);
        $this->assertEquals($validate->isValid('ok', [
            'dependent1' => '123',
            'dependent2' => '456',
        ]), true);
    }
}

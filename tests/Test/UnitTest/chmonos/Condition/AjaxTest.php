<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\Ajax;

class AjaxTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_valid()
    {
        // クロージャ形式
        $method = function ($value) {
            if ($value === 'trust') {
                return null;
            }
            return 'false';
        };
        $validate = new Ajax('hoge', [], $method);
        $this->assertEquals(true, $validate->isValid('trust'));
        $this->assertEquals(false, $validate->isValid('false'));

        // callable形式
        $validate = new Ajax('hoge', [], [$this, '_method']);
        $this->assertEquals(true, $validate->isValid('trust'));
        $this->assertEquals(false, $validate->isValid('false'));

        // 複数フィールド
        $method = function ($value, $params) {
            if ($value === $params[0]) {
                return null;
            }
            return 'false';
        };
        $validate = new Ajax('hoge', ['hoge'], $method);
        $this->assertEquals(true, $validate->isValid('hoge', ['hoge']));
        $this->assertEquals(false, $validate->isValid('hoge', ['fuga']));
    }

    function test_nomethod()
    {
        $validate = new Ajax('hoge');

        $this->assertEquals(true, $validate->isValid(''));
        $this->assertEquals(null, $validate->response());
    }

    function test_response()
    {
        $validate = new Ajax('hoge', [], [$this, '_method']);

        $_POST = ['key' => 'trust'];
        $this->assertEquals(null, $validate->response());

        $_POST = ['key' => 'trust', 'other' => 'hoge'];
        $this->assertEquals(null, $validate->response());

        $_POST = ['key' => 'dummy'];
        $this->assertEquals(['AjaxInvalid' => 'false'], $validate->response());
    }

    function test_response_method()
    {
        $validate = new Ajax([
            'url'    => 'hoge',
            'method' => 'get',
        ], [], [$this, '_method']);

        $_GET = ['key' => 'trust'];
        $_POST = [];
        $this->assertEquals(null, $validate->response());

        $_GET = [];
        $_POST = ['key' => 'trust'];
        $this->assertEquals(['AjaxInvalid' => 'false'], $validate->response());
    }

    function test_response_data()
    {
        $validate = new Ajax('hoge', [], [$this, '_method']);

        $this->assertEquals(null, $validate->response(['key' => 'trust']));

        $this->assertEquals(null, $validate->response(['key' => 'trust', 'other' => 'hoge']));

        $this->assertEquals(['AjaxInvalid' => 'false'], $validate->response(['key' => 'dummy']));
    }

    function test_response_file()
    {
        $validate = new Ajax('hoge', [], [$this, '_method']);

        $this->assertEquals(null, $validate->response([
            'f' => [
                'name'     => 'dummy',
                'type'     => 'dummy',
                'tmp_name' => 'trust',
                'error'    => 'dummy',
                'size'     => 'dummy',
            ],
        ]));

        $this->assertEquals(['AjaxInvalid' => 'false'], $validate->response([
            'f' => [
                'name'     => 'dummy',
                'type'     => 'dummy',
                'tmp_name' => 'dummy',
                'error'    => 'dummy',
                'size'     => 'dummy',
            ],
        ]));
    }

    function test_getField()
    {
        $validate = new Ajax('hoge', ['another'], [$this, '_method']);
        $this->assertEquals(['another'], $validate->getFields());
    }

    public function _method($value)
    {
        if ($value === 'trust') {
            return null;
        }
        return 'false';
    }
}

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
        $this->assertEquals($validate->isValid('trust'), true);
        $this->assertEquals($validate->isValid('false'), false);

        // callable形式
        $validate = new Ajax('hoge', [], [$this, '_method']);
        $this->assertEquals($validate->isValid('trust'), true);
        $this->assertEquals($validate->isValid('false'), false);

        // 複数フィールド
        $method = function ($value, $params) {
            if ($value === $params[0]) {
                return null;
            }
            return 'false';
        };
        $validate = new Ajax('hoge', ['hoge'], $method);
        $this->assertEquals($validate->isValid('hoge', ['hoge']), true);
        $this->assertEquals($validate->isValid('hoge', ['fuga']), false);
    }

    function test_nomethod()
    {
        $validate = new Ajax('hoge');

        $this->assertEquals($validate->isValid(''), true);
        $this->assertEquals($validate->response(), null);
    }

    function test_response()
    {
        $validate = new Ajax('hoge', [], [$this, '_method']);

        $_POST = ['key' => 'trust'];
        $this->assertEquals($validate->response(), null);

        $_POST = ['key' => 'trust', 'other' => 'hoge'];
        $this->assertEquals($validate->response(), null);

        $_POST = ['key' => 'dummy'];
        $this->assertEquals($validate->response(), [
            'AjaxInvalid' => 'false'
        ]);
    }

    function test_response_method()
    {
        $validate = new Ajax([
            'url'    => 'hoge',
            'method' => 'get',
        ], [], [$this, '_method']);

        $_GET = ['key' => 'trust'];
        $_POST = [];
        $this->assertEquals($validate->response(), null);

        $_GET = [];
        $_POST = ['key' => 'trust'];
        $this->assertEquals($validate->response(), ['AjaxInvalid' => 'false']);
    }

    function test_response_data()
    {
        $validate = new Ajax('hoge', [], [$this, '_method']);

        $this->assertEquals($validate->response(['key' => 'trust']), null);

        $this->assertEquals($validate->response(['key' => 'trust', 'other' => 'hoge']), null);

        $this->assertEquals($validate->response(['key' => 'dummy']), [
            'AjaxInvalid' => 'false'
        ]);
    }

    function test_response_file()
    {
        $validate = new Ajax('hoge', [], [$this, '_method']);

        $this->assertEquals($validate->response([
            'f' => [
                'name'     => 'dummy',
                'type'     => 'dummy',
                'tmp_name' => 'trust',
                'error'    => 'dummy',
                'size'     => 'dummy',
            ],
        ]), null);

        $this->assertEquals($validate->response([
            'f' => [
                'name'     => 'dummy',
                'type'     => 'dummy',
                'tmp_name' => 'dummy',
                'error'    => 'dummy',
                'size'     => 'dummy',
            ],
        ]), [
            'AjaxInvalid' => 'false'
        ]);
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

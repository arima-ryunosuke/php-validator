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
        that($validate)->isValid('trust')->isTrue();
        that($validate)->isValid('false')->isFalse();

        // callable形式
        $validate = new Ajax('hoge', [], [$this, '_method']);
        that($validate)->isValid('trust')->isTrue();
        that($validate)->isValid('false')->isFalse();

        // 複数フィールド
        $method = function ($value, $params) {
            if ($value === $params[0]) {
                return null;
            }
            return 'false';
        };
        $validate = new Ajax('hoge', ['hoge'], $method);
        that($validate)->isValid('hoge', ['hoge'])->isTrue();
        that($validate)->isValid('hoge', ['fuga'])->isFalse();
    }

    function test_nomethod()
    {
        $validate = new Ajax('hoge');

        that($validate)->isValid('')->isTrue();
        that($validate)->response()->isNull();
    }

    function test_response()
    {
        $validate = new Ajax('hoge?id=123&seq=456', [], [$this, '_method']);

        $_GET = ['id' => 123, 'seq' => 456, 'key' => 'trust'];
        that($validate)->response()->isNull();

        $_GET = ['id' => 123, 'seq' => 456, 'key' => 'trust', 'other' => 'hoge'];
        that($validate)->response()->isNull();

        $_GET = ['id' => 123, 'seq' => 456, 'key' => 'dummy'];
        that($validate)->response()->is([
            "AjaxInvalid" => "false",
        ]);
    }

    function test_response_method()
    {
        $validate = new Ajax([
            'url'    => 'hoge',
            'method' => 'post',
        ], [], [$this, '_method']);

        $_GET = [];
        $_POST = ['key' => 'trust'];
        that($validate)->response()->isNull();

        $_GET = ['key' => 'trust'];
        $_POST = [];
        that($validate)->response()->is([
            "AjaxInvalid" => "false",
        ]);
    }

    function test_response_data()
    {
        $validate = new Ajax('hoge', [], [$this, '_method']);

        that($validate)->response(['key' => 'trust'])->isNull();

        that($validate)->response(['key' => 'trust', 'other' => 'hoge'])->isNull();

        that($validate)->response(['key' => 'dummy'])->is([
            "AjaxInvalid" => "false",
        ]);
    }

    function test_response_file()
    {
        $validate = new Ajax('hoge', [], [$this, '_method']);

        that($validate)->response([
            'f' => [
                'name'     => 'dummy',
                'type'     => 'dummy',
                'tmp_name' => 'trust',
                'error'    => 'dummy',
                'size'     => 'dummy',
            ],
        ])->isNull();

        that($validate)->response([
            'f' => [
                'name'     => 'dummy',
                'type'     => 'dummy',
                'tmp_name' => 'dummy',
                'error'    => 'dummy',
                'size'     => 'dummy',
            ],
        ])->is([
            "AjaxInvalid" => "false",
        ]);
    }

    function test_getField()
    {
        $validate = new Ajax('hoge', ['another'], [$this, '_method']);
        that($validate)->getFields()->is(["another"]);
    }

    function test_getFixture()
    {
        $validate = new Ajax('hoge', ['another'], [$this, '_method']);
        that($validate)->getFixture(null, [])->isSame(null);
    }

    public function _method($value)
    {
        if ($value === 'trust') {
            return null;
        }
        return 'false';
    }
}

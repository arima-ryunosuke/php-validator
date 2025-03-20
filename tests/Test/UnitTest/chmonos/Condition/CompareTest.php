<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\Compare;

class CompareTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_valid_equal()
    {
        $values = [
            'depend' => '12345',
        ];

        // equal/string
        $validate = new Compare('===', 'depend');
        that($validate)->isValid('12345', $values)->isTrue();
        that($validate)->isValid(12345, $values)->isFalse();
        that($validate)->isValid('54321', $values)->isFalse();

        // equal/number
        $validate = new Compare('==', 'depend');
        that($validate)->isValid('12345', $values)->isTrue();
        that($validate)->isValid(12345, $values)->isTrue();
        that($validate)->isValid(54321, $values)->isFalse();
    }

    function test_valid_equal_filter()
    {
        $values = [
            'depend' => 'abcde',
        ];

        // equal/string
        $validate = new Compare('==', 'depend', 'strtoupper');
        that($validate)->isValid('ABCDE', $values)->isTrue();
    }

    function test_valid_notequal()
    {
        $values = [
            'depend' => '12345',
        ];

        // notequal/string
        $validate = new Compare('!==', 'depend');
        that($validate)->isValid('12345', $values)->isFalse();
        that($validate)->isValid(12345, $values)->isTrue();
        that($validate)->isValid('54321', $values)->isTrue();

        // notequal/number
        $validate = new Compare('!=', 'depend');
        that($validate)->isValid('12345', $values)->isFalse();
        that($validate)->isValid(12345, $values)->isFalse();
        that($validate)->isValid(54321, $values)->isTrue();
    }

    function test_valid_less()
    {
        $values = [
            'depend' => '12345',
        ];

        // less/number
        $validate = new Compare('<=', 'depend');
        that($validate)->isValid('12344', $values)->isTrue();
        that($validate)->isValid('12345', $values)->isTrue();
        that($validate)->isValid('12346', $values)->isFalse();

        $values = [
            'depend' => '2011/11/11 11:11:11',
        ];

        // less/date
        $validate = new Compare('<', 'depend', 'strtotime');
        that($validate)->isValid('2011/11/11 11:11:10', $values)->isTrue();
        that($validate)->isValid('2011/11/11 11:11:11', $values)->isFalse();
        that($validate)->isValid('2011/11/11 11:11:12', $values)->isFalse();
    }

    function test_valid_great()
    {
        $values = [
            'depend' => '12345',
        ];

        // great/number
        $validate = new Compare('>=', 'depend');
        that($validate)->isValid('12344', $values)->isFalse();
        that($validate)->isValid('12345', $values)->isTrue();
        that($validate)->isValid('12346', $values)->isTrue();

        $values = [
            'depend' => '2011/11/11 11:11:11',
        ];

        // great/date
        $validate = new Compare('>', 'depend', 'strtotime');
        that($validate)->isValid('2011/11/11 11:11:10', $values)->isFalse();
        that($validate)->isValid('2011/11/11 11:11:11', $values)->isFalse();
        that($validate)->isValid('2011/11/11 11:11:12', $values)->isTrue();
    }

    function test_valid_contain()
    {
        $values = [
            'depend' => 'mail@address',
        ];

        // contain
        $validate = new Compare('contain', 'depend');
        that($validate)->isValid('password', $values)->isFalse();
        that($validate)->isValid('prefix-mail@address-suffix', $values)->isTrue();

        // not contain
        $validate = new Compare('!contain', 'depend');
        that($validate)->isValid('password', $values)->isTrue();
        that($validate)->isValid('prefix-mail@address-suffix', $values)->isFalse();
    }

    function test_valid_offset()
    {
        $values = [
            'depend' => '12345',
        ];

        // less/number
        $validate = new Compare('<=', 'depend', '', 100);
        that($validate)->isValid('12244', $values)->isTrue();
        that($validate)->isValid('12245', $values)->isTrue();
        that($validate)->isValid('12246', $values)->isFalse();

        $values = [
            'depend' => '2011/11/11 11:11:11',
        ];

        // great/date
        $validate = new Compare('>=', 'depend', 'strtotime', -60);
        that($validate)->isValid('2011/11/11 11:12:10', $values)->isFalse();
        that($validate)->isValid('2011/11/11 11:12:11', $values)->isTrue();
        that($validate)->isValid('2011/11/11 11:12:12', $values)->isTrue();
    }

    function test_valid_direct()
    {
        $validate = new Compare('>=', '2018/12/12 12:34:56', 'strtotime', 0, true);
        that($validate)->isValid('2018/12/12 12:34:55')->isFalse();
        that($validate)->isValid('2018/12/12 12:34:57')->isTrue();
    }

    function test_valid_empty()
    {
        $values = [
            'depend' => '',
        ];

        $validate = new Compare('==', 'depend');
        that($validate)->isValid(+100, $values)->isTrue();
        that($validate)->isValid(-100, $values)->isTrue();
    }

    function test_getPropagation()
    {
        $validate = new Compare('==', 'depend');
        that($validate)->getPropagation()->is(["depend"]);
    }

    function test_getFixture()
    {
        $validate = new Compare('==', 'depend');
        that($validate)->getFixture(null, [])->isSame(null);
        that($validate)->getFixture(null, ['depend' => 'DEPEND'])->is('DEPEND');

        $validate = new Compare('>', 'depend');
        that($validate)->getFixture(null, [])->isSame(null);
        that($validate)->getFixture(null, ['depend' => '2014'])->gt('2014');
        that($validate)->getFixture(null, ['depend' => '2014-12'])->gt('2014-12');
        that($validate)->getFixture(null, ['depend' => '2014-12-31'])->gt('2014-12-31');
        that($validate)->getFixture(null, ['depend' => '2014-12-31T23:59'])->gt('2014-12-31T23:59');
        that($validate)->getFixture(null, ['depend' => '2014-12-31T23:59:59'])->gt('2014-12-31T23:59:59');
        that($validate)->getFixture(null, ['depend' => 'hoge'])->is(null);

        $validate = new Compare('<', 'depend');
        that($validate)->getFixture(null, [])->isSame(null);
        that($validate)->getFixture(null, ['depend' => '2014'])->lt('2014');
        that($validate)->getFixture(null, ['depend' => '2014-01'])->lt('2014-01');
        that($validate)->getFixture(null, ['depend' => '2014-01-01'])->lt('2014-01-01');
        that($validate)->getFixture(null, ['depend' => '2014-01-01T00:00'])->lt('2014-01-01T00:00');
        that($validate)->getFixture(null, ['depend' => '2014-01-01T00:00:00'])->lt('2014-01-01T00:00:00');
        that($validate)->getFixture(null, ['depend' => 'hoge'])->is(null);

        $validate = new Compare('!=', 'depend');
        that($validate)->getFixture(null, [])->isSame(null);
        that($validate)->getFixture(null, ['depend' => 'DEPEND'])->is(null);
        that($validate)->getFixture(null, ['depend' => '123'])->is(null);
        that($validate)->getFixture(null, ['depend' => '2014-12-24'])->is(null);
        that($validate)->getFixture(null, ['depend' => '2014-12-24T12:34'])->is(null);
        that($validate)->getFixture(null, ['depend' => 'hoge'])->is(null);
    }
}

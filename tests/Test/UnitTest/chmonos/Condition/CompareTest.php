<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\Compare;

class CompareTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_valid_equal()
    {
        $values = [
            'depend' => '12345'
        ];

        // equal/string
        $validate = new Compare('===', 'depend');
        $this->assertEquals($validate->isValid('12345', $values), true);
        $this->assertEquals($validate->isValid(12345, $values), false);
        $this->assertEquals($validate->isValid('54321', $values), false);

        // equal/number
        $validate = new Compare('==', 'depend');
        $this->assertEquals($validate->isValid('12345', $values), true);
        $this->assertEquals($validate->isValid(12345, $values), true);
        $this->assertEquals($validate->isValid(54321, $values), false);
    }

    function test_valid_equal_filter()
    {
        $values = [
            'depend' => 'abcde'
        ];

        // equal/string
        $validate = new Compare('==', 'depend', 'strtoupper');
        $this->assertEquals($validate->isValid('ABCDE', $values), true);
    }

    function test_valid_notequal()
    {
        $values = [
            'depend' => '12345'
        ];

        // notequal/string
        $validate = new Compare('!==', 'depend');
        $this->assertEquals($validate->isValid('12345', $values), false);
        $this->assertEquals($validate->isValid(12345, $values), true);
        $this->assertEquals($validate->isValid('54321', $values), true);

        // notequal/number
        $validate = new Compare('!=', 'depend');
        $this->assertEquals($validate->isValid('12345', $values), false);
        $this->assertEquals($validate->isValid(12345, $values), false);
        $this->assertEquals($validate->isValid(54321, $values), true);
    }

    function test_valid_less()
    {
        $values = [
            'depend' => '12345'
        ];

        // less/number
        $validate = new Compare('<=', 'depend');
        $this->assertEquals($validate->isValid('12344', $values), true);
        $this->assertEquals($validate->isValid('12345', $values), true);
        $this->assertEquals($validate->isValid('12346', $values), false);

        $values = [
            'depend' => '2011/11/11 11:11:11'
        ];

        // less/date
        $validate = new Compare('<', 'depend', 'strtotime');
        $this->assertEquals($validate->isValid('2011/11/11 11:11:10', $values), true);
        $this->assertEquals($validate->isValid('2011/11/11 11:11:11', $values), false);
        $this->assertEquals($validate->isValid('2011/11/11 11:11:12', $values), false);
    }

    function test_valid_great()
    {
        $values = [
            'depend' => '12345'
        ];

        // great/number
        $validate = new Compare('>=', 'depend');
        $this->assertEquals($validate->isValid('12344', $values), false);
        $this->assertEquals($validate->isValid('12345', $values), true);
        $this->assertEquals($validate->isValid('12346', $values), true);

        $values = [
            'depend' => '2011/11/11 11:11:11'
        ];

        // great/date
        $validate = new Compare('>', 'depend', 'strtotime');
        $this->assertEquals($validate->isValid('2011/11/11 11:11:10', $values), false);
        $this->assertEquals($validate->isValid('2011/11/11 11:11:11', $values), false);
        $this->assertEquals($validate->isValid('2011/11/11 11:11:12', $values), true);
    }

    function test_valid_offset()
    {
        $values = [
            'depend' => '12345'
        ];

        // less/number
        $validate = new Compare('<=', 'depend', '', 100);
        $this->assertEquals($validate->isValid('12244', $values), true);
        $this->assertEquals($validate->isValid('12245', $values), true);
        $this->assertEquals($validate->isValid('12246', $values), false);

        $values = [
            'depend' => '2011/11/11 11:11:11'
        ];

        // great/date
        $validate = new Compare('>=', 'depend', 'strtotime', -60);
        $this->assertEquals($validate->isValid('2011/11/11 11:12:10', $values), false);
        $this->assertEquals($validate->isValid('2011/11/11 11:12:11', $values), true);
        $this->assertEquals($validate->isValid('2011/11/11 11:12:12', $values), true);
    }

    function test_valid_direct()
    {
        $validate = new Compare('>=', '2018/12/12 12:34:56', 'strtotime', 0, true);
        $this->assertEquals($validate->isValid('2018/12/12 12:34:55'), false);
        $this->assertEquals($validate->isValid('2018/12/12 12:34:57'), true);
    }

    function test_valid_empty()
    {
        $values = [
            'depend' => ''
        ];

        $validate = new Compare('==', 'depend');
        $this->assertEquals($validate->isValid(+100, $values), true);
        $this->assertEquals($validate->isValid(-100, $values), true);
    }

    function test_getPropagation()
    {
        $validate = new Compare('==', 'depend');
        $this->assertEquals(['depend'], $validate->getPropagation());
    }
}

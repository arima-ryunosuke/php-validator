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
        $this->assertEquals(true, $validate->isValid('12345', $values));
        $this->assertEquals(false, $validate->isValid(12345, $values));
        $this->assertEquals(false, $validate->isValid('54321', $values));

        // equal/number
        $validate = new Compare('==', 'depend');
        $this->assertEquals(true, $validate->isValid('12345', $values));
        $this->assertEquals(true, $validate->isValid(12345, $values));
        $this->assertEquals(false, $validate->isValid(54321, $values));
    }

    function test_valid_equal_filter()
    {
        $values = [
            'depend' => 'abcde'
        ];

        // equal/string
        $validate = new Compare('==', 'depend', 'strtoupper');
        $this->assertEquals(true, $validate->isValid('ABCDE', $values));
    }

    function test_valid_notequal()
    {
        $values = [
            'depend' => '12345'
        ];

        // notequal/string
        $validate = new Compare('!==', 'depend');
        $this->assertEquals(false, $validate->isValid('12345', $values));
        $this->assertEquals(true, $validate->isValid(12345, $values));
        $this->assertEquals(true, $validate->isValid('54321', $values));

        // notequal/number
        $validate = new Compare('!=', 'depend');
        $this->assertEquals(false, $validate->isValid('12345', $values));
        $this->assertEquals(false, $validate->isValid(12345, $values));
        $this->assertEquals(true, $validate->isValid(54321, $values));
    }

    function test_valid_less()
    {
        $values = [
            'depend' => '12345'
        ];

        // less/number
        $validate = new Compare('<=', 'depend');
        $this->assertEquals(true, $validate->isValid('12344', $values));
        $this->assertEquals(true, $validate->isValid('12345', $values));
        $this->assertEquals(false, $validate->isValid('12346', $values));

        $values = [
            'depend' => '2011/11/11 11:11:11'
        ];

        // less/date
        $validate = new Compare('<', 'depend', 'strtotime');
        $this->assertEquals(true, $validate->isValid('2011/11/11 11:11:10', $values));
        $this->assertEquals(false, $validate->isValid('2011/11/11 11:11:11', $values));
        $this->assertEquals(false, $validate->isValid('2011/11/11 11:11:12', $values));
    }

    function test_valid_great()
    {
        $values = [
            'depend' => '12345'
        ];

        // great/number
        $validate = new Compare('>=', 'depend');
        $this->assertEquals(false, $validate->isValid('12344', $values));
        $this->assertEquals(true, $validate->isValid('12345', $values));
        $this->assertEquals(true, $validate->isValid('12346', $values));

        $values = [
            'depend' => '2011/11/11 11:11:11'
        ];

        // great/date
        $validate = new Compare('>', 'depend', 'strtotime');
        $this->assertEquals(false, $validate->isValid('2011/11/11 11:11:10', $values));
        $this->assertEquals(false, $validate->isValid('2011/11/11 11:11:11', $values));
        $this->assertEquals(true, $validate->isValid('2011/11/11 11:11:12', $values));
    }

    function test_valid_offset()
    {
        $values = [
            'depend' => '12345'
        ];

        // less/number
        $validate = new Compare('<=', 'depend', '', 100);
        $this->assertEquals(true, $validate->isValid('12244', $values));
        $this->assertEquals(true, $validate->isValid('12245', $values));
        $this->assertEquals(false, $validate->isValid('12246', $values));

        $values = [
            'depend' => '2011/11/11 11:11:11'
        ];

        // great/date
        $validate = new Compare('>=', 'depend', 'strtotime', -60);
        $this->assertEquals(false, $validate->isValid('2011/11/11 11:12:10', $values));
        $this->assertEquals(true, $validate->isValid('2011/11/11 11:12:11', $values));
        $this->assertEquals(true, $validate->isValid('2011/11/11 11:12:12', $values));
    }

    function test_valid_direct()
    {
        $validate = new Compare('>=', '2018/12/12 12:34:56', 'strtotime', 0, true);
        $this->assertEquals(false, $validate->isValid('2018/12/12 12:34:55'));
        $this->assertEquals(true, $validate->isValid('2018/12/12 12:34:57'));
    }

    function test_valid_empty()
    {
        $values = [
            'depend' => ''
        ];

        $validate = new Compare('==', 'depend');
        $this->assertEquals(true, $validate->isValid(+100, $values));
        $this->assertEquals(true, $validate->isValid(-100, $values));
    }

    function test_getPropagation()
    {
        $validate = new Compare('==', 'depend');
        $this->assertEquals(['depend'], $validate->getPropagation());
    }
}

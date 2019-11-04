<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\Aruiha;
use ryunosuke\chmonos\Condition\Compare;
use ryunosuke\chmonos\Condition\EmailAddress;
use ryunosuke\chmonos\Condition\Range;

class AruihaTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_compare()
    {
        $values = [
            'dependL' => -1,
            'dependG' => 1,
        ];

        $validate = new Aruiha([
            new Compare('<=', 'dependL'),
            new Compare('>=', 'dependG'),
        ]);

        $this->assertEquals($validate->isValid(-2, $values), true);
        $this->assertEmpty($validate->getMessages());
        $this->assertEquals($validate->isValid(-1, $values), true);
        $this->assertEmpty($validate->getMessages());
        $this->assertEquals($validate->isValid(0, $values), false);
        $this->assertNotEmpty($validate->getMessages());
        $this->assertEquals($validate->isValid(1, $values), true);
        $this->assertEmpty($validate->getMessages());
        $this->assertEquals($validate->isValid(2, $values), true);
        $this->assertEmpty($validate->getMessages());

        $this->assertEquals($validate->getFields(), ['dependL', 'dependG']);
    }

    function test_range()
    {
        $validate = new Aruiha([
            new Range(-3, -1),
            'Range' => [1, 3],
        ]);

        $this->assertEquals($validate->isValid(-4), false);
        $this->assertNotEmpty($validate->getMessages());
        $this->assertEquals($validate->isValid(-3), true);
        $this->assertEmpty($validate->getMessages());
        $this->assertEquals($validate->isValid(-2), true);
        $this->assertEmpty($validate->getMessages());
        $this->assertEquals($validate->isValid(-1), true);
        $this->assertEmpty($validate->getMessages());
        $this->assertEquals($validate->isValid(0), false);
        $this->assertNotEmpty($validate->getMessages());
        $this->assertEquals($validate->isValid(1), true);
        $this->assertEmpty($validate->getMessages());
        $this->assertEquals($validate->isValid(2), true);
        $this->assertEmpty($validate->getMessages());
        $this->assertEquals($validate->isValid(3), true);
        $this->assertEmpty($validate->getMessages());
        $this->assertEquals($validate->isValid(4), false);
        $this->assertNotEmpty($validate->getMessages());
    }

    function test_misc()
    {
        $validate = new Aruiha([
            new Range(-3, -1),
        ]);
        $this->assertEquals($validate->getMaxLength(), null);

        $validate = new Aruiha([
            'StringLength' => [0, 10],
            new EmailAddress('#this_is_regex#'),
        ]);

        $this->assertEquals($validate->getMaxLength(), 256);
        $this->assertEquals($validate->getValidationParam(), [
            'condition' => [
                [
                    'class' => 'StringLength',
                    'param' => [
                        'min' => 0,
                        'max' => 10,
                    ],
                ],
                [
                    'class' => 'EmailAddress',
                    'param' => [
                        'regex' => '#this_is_regex#',
                    ],
                ],
            ],
        ]);
    }
}

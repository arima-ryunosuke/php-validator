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

        $this->assertEquals(true, $validate->isValid(-2, $values));
        $this->assertEmpty($validate->getMessages());
        $this->assertEquals(true, $validate->isValid(-1, $values));
        $this->assertEmpty($validate->getMessages());
        $this->assertEquals(false, $validate->isValid(0, $values));
        $this->assertNotEmpty($validate->getMessages());
        $this->assertEquals(true, $validate->isValid(1, $values));
        $this->assertEmpty($validate->getMessages());
        $this->assertEquals(true, $validate->isValid(2, $values));
        $this->assertEmpty($validate->getMessages());

        $this->assertEquals(['dependL', 'dependG'], $validate->getFields());
    }

    function test_range()
    {
        $validate = new Aruiha([
            new Range(-3, -1),
            'Range' => [1, 3],
        ]);

        $this->assertEquals(false, $validate->isValid(-4));
        $this->assertNotEmpty($validate->getMessages());
        $this->assertEquals(true, $validate->isValid(-3));
        $this->assertEmpty($validate->getMessages());
        $this->assertEquals(true, $validate->isValid(-2));
        $this->assertEmpty($validate->getMessages());
        $this->assertEquals(true, $validate->isValid(-1));
        $this->assertEmpty($validate->getMessages());
        $this->assertEquals(false, $validate->isValid(0));
        $this->assertNotEmpty($validate->getMessages());
        $this->assertEquals(true, $validate->isValid(1));
        $this->assertEmpty($validate->getMessages());
        $this->assertEquals(true, $validate->isValid(2));
        $this->assertEmpty($validate->getMessages());
        $this->assertEquals(true, $validate->isValid(3));
        $this->assertEmpty($validate->getMessages());
        $this->assertEquals(false, $validate->isValid(4));
        $this->assertNotEmpty($validate->getMessages());
    }

    function test_misc()
    {
        $validate = new Aruiha([
            new Range(-3, -1),
        ]);
        $this->assertEquals(null, $validate->getMaxLength());

        $validate = new Aruiha([
            'StringLength' => [0, 10],
            new EmailAddress('#this_is_regex#'),
        ]);

        $this->assertEquals(256, $validate->getMaxLength());
        $this->assertEquals([
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
        ], $validate->getValidationParam());
    }
}

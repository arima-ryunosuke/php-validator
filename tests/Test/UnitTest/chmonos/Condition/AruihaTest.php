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

        that($validate)->isValid(-2, $values)->isTrue();
        that($validate)->getMessages()->is([]);
        that($validate)->isValid(-1, $values)->isTrue();
        that($validate)->getMessages()->is([]);
        that($validate)->isValid(0, $values)->isFalse();
        that($validate)->getMessages()->is(["AruihaInvalid" => "必ず呼び出し元で再宣言する"]);
        that($validate)->isValid(1, $values)->isTrue();
        that($validate)->getMessages()->is([]);
        that($validate)->isValid(2, $values)->isTrue();
        that($validate)->getMessages()->is([]);

        that($validate)->getFields()->is(["dependL", "dependG"]);
    }

    function test_range()
    {
        $validate = new Aruiha([
            new Range(-3, -1),
            'Range' => [1, 3],
        ]);

        that($validate)->isValid(-4)->isFalse();
        that($validate)->getMessages()->is(["AruihaInvalid" => "必ず呼び出し元で再宣言する"]);
        that($validate)->isValid(-3)->isTrue();
        that($validate)->getMessages()->is([]);
        that($validate)->isValid(-2)->isTrue();
        that($validate)->getMessages()->is([]);
        that($validate)->isValid(-1)->isTrue();
        that($validate)->getMessages()->is([]);
        that($validate)->isValid(0)->isFalse();
        that($validate)->getMessages()->is(["AruihaInvalid" => "必ず呼び出し元で再宣言する"]);
        that($validate)->isValid(1)->isTrue();
        that($validate)->getMessages()->is([]);
        that($validate)->isValid(2)->isTrue();
        that($validate)->getMessages()->is([]);
        that($validate)->isValid(3)->isTrue();
        that($validate)->getMessages()->is([]);
        that($validate)->isValid(4)->isFalse();
        that($validate)->getMessages()->is(["AruihaInvalid" => "必ず呼び出し元で再宣言する"]);
    }

    function test_misc()
    {
        $validate = new Aruiha([
            new Range(-3, -1),
        ]);
        that($validate)->getMaxLength()->isNull();

        $validate = new Aruiha([
            'StringLength' => [0, 10],
            new EmailAddress('#this_is_regex#'),
        ]);

        that($validate)->getMaxLength()->is(256);
        that($validate)->getValidationParam()->is([
            "condition" => [
                [
                    "class" => "StringLength",
                    "param" => [
                        "min"      => 0,
                        "max"      => 10,
                        'grapheme' => false,
                    ],
                ],
                [
                    "class" => "EmailAddress",
                    "param" => [
                        "regex" => "#this_is_regex#",
                    ],
                ],
            ],
        ]);
    }
}

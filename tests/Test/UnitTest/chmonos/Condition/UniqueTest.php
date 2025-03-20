<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\Unique;

class UniqueTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_valid()
    {
        $base_values = [
            '/values' => [
                [
                    'unique' => 'val1',
                ],
                [
                    'unique' => 'val2',
                ],
                [
                    'unique' => 'val',
                ],
            ],
        ];

        $validate = new Unique(true);
        $validate->initialize(null, null, 'values', 'unique');

        // 重複なし
        $values = $base_values;
        that($validate)->isValid('val', $values)->isTrue();

        // 重複あり
        $values = $base_values;
        $values['/values'][] = [
            'unique' => 'val',
        ];
        that($validate)->isValid('val', $values)->isFalse();

        $validate = new Unique(false);
        $validate->initialize(null, null, 'values', 'unique');

        // ケース無視重複なし
        $values = $base_values;
        that($validate)->isValid('val', $values)->isTrue();

        // ケース無視重複あり
        $values = $base_values;
        $values['/values'][] = [
            'unique' => 'VAL',
        ];
        that($validate)->isValid('val', $values)->isFalse();
    }

    function test_valid_single()
    {
        $values = [
            '/values' => [
                [
                    'unique' => 'val',
                ],
            ],
        ];

        // 重複なし
        $validate = new Unique(true);
        $validate->initialize(null, null, 'values', 'unique');
        that($validate)->isValid('val', $values)->isTrue();

        // ケース無視重複なし
        $validate = new Unique(false);
        $validate->initialize(null, null, 'values', 'unique');
        that($validate)->isValid('val', $values)->isTrue();
    }

    function test_setRootName()
    {
        $validate = new Unique();
        $validate->initialize(null, null, 'values', 'unique');
        that($validate)->getPropagation()->is(["/values/unique"]);
        that($validate)->getFields()->is(["/values"]);
    }
}

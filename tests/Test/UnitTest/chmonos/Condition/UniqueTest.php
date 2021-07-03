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
                    'unique' => 'val1'
                ],
                [
                    'unique' => 'val2'
                ],
                [
                    'unique' => 'val'
                ]
            ]
        ];

        $validate = new Unique(true);
        $validate->initialize(null, null, 'values', 'unique');

        // 重複なし
        $values = $base_values;
        $this->assertEquals(true, $validate->isValid('val', $values));

        // 重複あり
        $values = $base_values;
        $values['/values'][] = [
            'unique' => 'val'
        ];
        $this->assertEquals(false, $validate->isValid('val', $values));

        $validate = new Unique(false);
        $validate->initialize(null, null, 'values', 'unique');

        // ケース無視重複なし
        $values = $base_values;
        $this->assertEquals(true, $validate->isValid('val', $values));

        // ケース無視重複あり
        $values = $base_values;
        $values['/values'][] = [
            'unique' => 'VAL'
        ];
        $this->assertEquals(false, $validate->isValid('val', $values));
    }

    function test_valid_single()
    {
        $values = [
            '/values' => [
                [
                    'unique' => 'val'
                ]
            ]
        ];

        // 重複なし
        $validate = new Unique(true);
        $validate->initialize(null, null, 'values', 'unique');
        $this->assertEquals(true, $validate->isValid('val', $values));

        // ケース無視重複なし
        $validate = new Unique(false);
        $validate->initialize(null, null, 'values', 'unique');
        $this->assertEquals(true, $validate->isValid('val', $values));
    }

    function test_setRootName()
    {
        $validate = new Unique();
        $validate->initialize(null, null, 'values', 'unique');
        $this->assertEquals(['/values/unique'], $validate->getPropagation());
        $this->assertEquals(['/values'], $validate->getFields());
    }
}

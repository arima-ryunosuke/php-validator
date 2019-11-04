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
        $this->assertEquals($validate->isValid('val', $values), true);

        // 重複あり
        $values = $base_values;
        $values['/values'][] = [
            'unique' => 'val'
        ];
        $this->assertEquals($validate->isValid('val', $values), false);

        $validate = new Unique(false);
        $validate->initialize(null, null, 'values', 'unique');

        // ケース無視重複なし
        $values = $base_values;
        $this->assertEquals($validate->isValid('val', $values), true);

        // ケース無視重複あり
        $values = $base_values;
        $values['/values'][] = [
            'unique' => 'VAL'
        ];
        $this->assertEquals($validate->isValid('val', $values), false);
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
        $this->assertEquals($validate->isValid('val', $values), true);

        // ケース無視重複なし
        $validate = new Unique(false);
        $validate->initialize(null, null, 'values', 'unique');
        $this->assertEquals($validate->isValid('val', $values), true);
    }

    function test_setRootName()
    {
        $validate = new Unique();
        $validate->initialize(null, null, 'values', 'unique');
        $this->assertEquals(['/values/unique'], $validate->getPropagation());
        $this->assertEquals(['/values'], $validate->getFields());
    }
}

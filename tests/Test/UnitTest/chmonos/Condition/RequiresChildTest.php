<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\RequiresChild;

class RequiresChildTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_valid()
    {
        $base_values = [
            'values' => [
                [
                    'k1' => 'val1',
                    'k2' => 'val2',
                ],
                [
                    'k1' => 'val3',
                    'k2' => 'val4',
                ],
                [
                    'k1' => 'val5',
                    'k2' => 'val6',
                ],
            ]
        ];

        $validate = new RequiresChild([
            'k1' => ['all', ['val1', 'val3', 'val7']],
            'k2' => ['any', ['val8']],
        ]);
        $validate->initialize(null, null, null, 'values');

        that($validate)->getPropagation()->is(['/values/k1', '/values/k2']);
        that($validate)->isArrayableValidation()->is(true);

        $values = $base_values;
        that($validate)->isValid($values['values'])->isFalse();

        $values = $base_values;
        $values['values'][] = [
            'k1' => 'val7',
            'k2' => '',
        ];
        that($validate)->isValid($values['values'])->isFalse();

        $values = $base_values;
        $values['values'][] = [
            'k1' => '',
            'k2' => 'val8',
        ];
        that($validate)->isValid($values['values'])->isFalse();

        $values = $base_values;
        $values['values'][] = [
            'k1' => 'val7',
            'k2' => 'val8',
        ];
        that($validate)->isValid($values['values'])->isTrue();

        $values = $base_values;
        $values['values'][] = [
            'k1' => ['val7'],
            'k2' => ['val8'],
        ];
        that($validate)->isValid($values['values'])->isTrue();
    }
}

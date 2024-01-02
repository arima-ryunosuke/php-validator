<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\UniqueChild;

class UniqueChildTest extends \ryunosuke\Test\AbstractUnitTestCase
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
                    'k1' => 'val1',
                    'k2' => 'val2',
                ],
            ]
        ];

        $validate = new UniqueChild(['k1', 'k2']);
        $validate->initialize(null, null, null, 'values');

        that($validate)->getPropagation()->is(['/values/k1', '/values/k2']);
        that($validate)->isArrayableValidation()->is(true);

        $values = $base_values;
        that($validate)->isValid($values['values'])->isFalse();

        $values = $base_values;
        $values['values'][0]['k1'] = 'val9';
        that($validate)->isValid($values['values'])->isTrue();

        $values = $base_values;
        $values['values'][1]['k2'] = 'val9';
        that($validate)->isValid($values['values'])->isTrue();

        $values = $base_values;
        $values['values'][] = [
            'k1' => ['val1'],
            'k2' => ['val2'],
        ];
        $values['values'][] = [
            'k1' => ['val1'],
            'k2' => ['val2'],
        ];
        that($validate)->isValid($values['values'])->isFalse();

        $empty_values = [
            'values' => [
                [
                    'k1' => '',
                    'k2' => '',
                ],
                [
                    'k1' => '',
                    'k2' => '',
                ],
            ]
        ];

        $validate1 = new UniqueChild(['k1', 'k2'], true);
        $validate2 = new UniqueChild(['k1', 'k2'], false);
        $values = $empty_values;
        that($validate1)->isValid($values['values'])->isTrue();
        that($validate2)->isValid($values['values'])->isFalse();
        $values = $empty_values;
        $values['values'][0]['k1'] = 'valE';
        $values['values'][1]['k1'] = 'valE';
        that($validate1)->isValid($values['values'])->isFalse();
        that($validate2)->isValid($values['values'])->isFalse();
    }

    function test_getFixture()
    {
        $validate = new UniqueChild(['k1', 'k2']);
        that($validate)->getFixture([['k1' => 'a', 'k2' => 'b']], [])->is([['k1' => 'a', 'k2' => 'b']]);
    }
}

<?php
namespace ryunosuke\chmonos\Condition;

use function ryunosuke\chmonos\array_kmap;

/**
 * 子供必須バリデータ
 *
 * ある項目内での要/不要を検出する。
 *
 * type=arrays 要素に設定される前提。なぜならフラットならば Requires/Regex 等で事足りるから。
 * つまり列の串刺し配列に対して InArray を適用できるイメージ。
 *
 * ```php
 * [
 *   // 「名前1」の value が「値1, 値2, ...」のいずれかを含むとき必須になる
 *   '名前1' => ['any', [値1, 値2, ...]],
 *   // 「名前2」の value が「値1, 値2, ...」のすべてを含むとき必須になる
 *   '名前2' => ['all', [値1, 値2, ...]],
 * ],
 * ```
 */
class RequiresChild extends AbstractParentCondition
{
    public const INVALID     = 'RequiresChildInvalid';
    public const NOT_CONTAIN = 'RequiresChildNotContain';

    protected static $messageTemplates = [
        self::INVALID     => 'Invalid value given',
        self::NOT_CONTAIN => '必須項目を含んでいません',
    ];

    protected $_inputs;

    public function __construct(array $inputs)
    {
        $this->_inputs = $inputs;

        parent::__construct(array_keys($this->_inputs));
    }

    public static function validate($value, $fields, $params, $consts, $error, $context)
    {
        $cols = array_kmap(array_flip($params['children']), $context['function'](function ($_, $k, $n, $value, $context) {
            $col = array_column($value, $k);
            $col = array_reduce($col, $context['function'](function ($carry, $c, $context) {
                return array_merge($carry, $context['cast']('array', $c));
            }, $context), []);
            return $col;
        }, $value, $context));

        $context['foreach']($cols, function ($name, $values, $inputs, $consts, $error, $context) {
            $operator = $inputs[$name][0];
            $operands = $inputs[$name][1];

            $intersect = array_intersect_key(
                array_flip($context['cast']('array', $values)),
                array_flip($context['cast']('array', $operands)),
            );

            if ($operator === 'any' && !count($intersect)) {
                $error($consts['NOT_CONTAIN']);
            }
            if ($operator === 'all' && count($intersect) !== count($operands)) {
                $error($consts['NOT_CONTAIN']);
            }
        }, $params['inputs'], $consts, $error, $context);
    }

    public function getFixture($value, $fields)
    {
        foreach ($this->_inputs as $name => $input) {
            $operator = $input[0];
            $operands = $input[1];
            if ($operator === 'any') {
                $value[array_rand($value)][$name] = $this->fixtureArray($operands);
            }
            if ($operator === 'all') {
                foreach ($value as $i => $v) {
                    $value[$i][$name] = $this->fixtureArray($operands);
                }
            }
        }
        return $value;
    }
}

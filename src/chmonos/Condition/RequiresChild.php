<?php
namespace ryunosuke\chmonos\Condition;

use ryunosuke\chmonos\Context;

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
class RequiresChild extends AbstractCondition implements Interfaces\Propagation, Interfaces\Initialize
{
    public const INVALID     = 'RequiresChildInvalid';
    public const NOT_CONTAIN = 'RequiresChildNotContain';

    protected static $messageTemplates = [
        self::INVALID     => 'Invalid value given',
        self::NOT_CONTAIN => '必須項目を含んでいません',
    ];

    protected $_name;
    protected $_inputs;

    public function __construct(array $inputs)
    {
        $this->_inputs = $inputs;

        parent::__construct();
    }

    public function initialize(?Context $root, ?Context $context, $parent, $name)
    {
        $this->_name = $name;
    }

    public function getFields()
    {
        return ["/$this->_name"];
    }

    public function getPropagation()
    {
        return array_map(fn($v) => "/$this->_name/$v", array_keys($this->_inputs));
    }

    public static function getJavascriptCode()
    {
        return <<<'JS'
            (function() {
                $context['values'] = {};
                for (var row of chmonos.value($params.name)) {
                    for (var name of Object.keys($params.inputs)) {
                        $context['values'][name] = ($context['values'][name] ?? []).concat($context['cast']('array', row[name]));
                    }
                }
                // @validationcode:inject
            })();
JS;
    }

    public static function prevalidate($value, $fields, $params)
    {
        $context = ['values' => []];
        foreach ($fields["/{$params['name']}"] as $row) {
            foreach ($params['inputs'] as $name => $rule) {
                $context['values'][$name] = array_merge($context['values'][$name] ?? [], (array) $row[$name]);
            }
        }
        return $context;
    }

    public static function validate($value, $fields, $params, $consts, $error, $context)
    {
        $context['foreach']($context['values'], function ($name, $values, $params, $consts, $error, $context) {
            $operator = $params['inputs'][$name][0];
            $operands = $params['inputs'][$name][1];

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
        }, $params, $consts, $error, $context);
    }
}

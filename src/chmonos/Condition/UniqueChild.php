<?php
namespace ryunosuke\chmonos\Condition;

use ryunosuke\chmonos\Context;
use function ryunosuke\chmonos\arrayize;

/**
 * 子供重複バリデータ
 *
 * ある項目内での重複を検出する。
 *
 * type=arrays 要素に設定される前提。なぜならフラットならば Compare/Unique 等で事足りるから。
 * つまり列の串刺し配列に対して Compare/Unique を適用できるイメージ。
 *
 * ```php
 * // elem1 と elem2 の結合結果が他と重複するときにエラーになる
 * ['elem1', 'elem2'],
 * ```
 */
class UniqueChild extends AbstractCondition implements Interfaces\Propagation, Interfaces\Initialize
{
    public const INVALID   = 'UniqueChildInvalid';
    public const NO_UNIQUE = 'UniqueChildNoUnique';

    protected static $messageTemplates = [
        self::INVALID   => 'Invalid value given',
        self::NO_UNIQUE => '値が重複しています',
    ];

    protected $_name;
    protected $_inputs;

    public function __construct($inputs)
    {
        $this->_inputs = arrayize($inputs);

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
        return array_map(fn($v) => "/$this->_name/$v", $this->_inputs);
    }

    public static function getJavascriptCode()
    {
        return <<<'JS'
            (function() {
                $context['values'] = [];
                for (var row of chmonos.value($params.name)) {
                    var values = [];
                    for (var name of $params.inputs) {
                        values = values.concat($context['cast']('array', row[name]));
                    }
                    $context['values'].push(values.join('\x1f'));
                }
                // @validationcode:inject
            })();
JS;
    }

    public static function prevalidate($value, $fields, $params)
    {
        $context = ['values' => []];
        foreach ($fields["/{$params['name']}"] as $row) {
            $values = [];
            foreach ($params['inputs'] as $name) {
                $values = array_merge($values, (array) $row[$name]);
            }
            $context['values'][] = implode("\x1f", $values);
        }
        return $context;
    }

    public static function validate($value, $fields, $params, $consts, $error, $context)
    {
        if (count($context['values']) !== count(array_unique($context['values']))) {
            $error($consts['NO_UNIQUE']);
        }
    }
}

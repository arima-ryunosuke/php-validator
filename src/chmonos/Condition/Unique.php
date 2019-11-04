<?php
namespace ryunosuke\chmonos\Condition;

use ryunosuke\chmonos\Condition\Interfaces;
use ryunosuke\chmonos\Context;

/**
 * 重複チェックバリデータ
 *
 * ある項目内での重複を検出する。
 *
 * type=arrays 配下の要素に設定される前提。なぜならフラットならば Compare で事足りるから。
 * このクラスは従兄弟との重複を検出したい場合に使う。
 *
 * - strict: bool
 *   - 大文字小文字比較フラグ
 */
class Unique extends AbstractCondition implements Interfaces\Propagation, Interfaces\Initialize
{
    public const INVALID   = 'UniqueInvalid';
    public const NO_UNIQUE = 'UniqueNoUnique';

    protected static $messageTemplates = [
        self::INVALID   => 'Invalid value given',
        self::NO_UNIQUE => '値が重複しています',
    ];

    protected $_root;
    protected $_name;
    protected $_strict;

    public function __construct($strict = true)
    {
        $this->_strict = $strict;

        parent::__construct();
    }

    public function initialize(?Context $root, ?Context $context, $parent, $name)
    {
        $this->_root = $parent;
        $this->_name = $name;
    }

    public function getFields()
    {
        return ["/$this->_root"];
    }

    public function getPropagation()
    {
        return ["/$this->_root/$this->_name"];
    }

    public static function getJavascriptCode()
    {
        return <<<'JS'
            (function() {
                $context['values'] = {};
                var regexp = new RegExp($params.root + '/(-?\\d+)/' + $params.name);
                var values = chmonos.values();
                var keys = Object.keys(values);
                for(var i = 0; i < keys.length; i++){
                    var name = keys[i];
                    var m = regexp.exec(name);
                    if (m) {
                        $context['values'][m[1]] = $params.strict ? values[name] : (values[name] + '').toLowerCase();
                    }
                }
                // @validationcode:inject
            })();
JS;
    }

    public static function prevalidate($value, $fields, $params)
    {
        return [
            'values' => array_map(function ($v) use ($params) {
                return $params['strict'] ? $v[$params['name']] : strtolower($v[$params['name']]);
            }, $fields["/{$params['root']}"])
        ];
    }


    public static function validate($value, $fields, $params, $consts, $error, $context)
    {
        $acv = array_count_values($context['values']);
        if ($acv[$params['strict'] ? $value : strtolower($value)] > 1) {
            $error($consts['NO_UNIQUE']);
            return false;
        }
    }
}

<?php
namespace ryunosuke\chmonos\Condition;

use ryunosuke\chmonos\Condition\Interfaces;
use function ryunosuke\chmonos\callable_code;

/**
 * Callback バリデータ
 *
 * クロージャを渡すと php/js 共に検証がされる。
 * 実体はクロージャのコードをパースして呼び出しているだけなので、クロージャは php/js 共通のシンタックスである必要がある。
 *
 * - closure: Closure
 *   - 実際に処理されるクロージャ。 php/js 共通のシンタックスである必要がある
 * - fields: array
 *   - 依存フィールド
 * - userdata: mixed
 *   - クロージャに渡されるユーザデータ（use のようなもの）
 */
class Callback extends AbstractCondition implements Interfaces\Propagation
{
    public const INVALID = 'CallbackInvalid';

    protected static $messageTemplates = [
        self::INVALID => 'クロージャの戻り値で上書きされる',
    ];

    private $closure;

    protected $_fields;
    protected $_userdata;

    public function __construct(\Closure $closure, $fields = [], $userdata = null)
    {
        $this->closure = $closure;
        $this->_fields = (array) $fields;
        $this->_userdata = $userdata;

        parent::__construct();
    }

    public function getFields()
    {
        return $this->_fields;
    }

    public function getPropagation()
    {
        return $this->getFields();
    }

    public static function validate($value, $fields, $params, $consts, $error, $context)
    {
        $callee = $context['lang'] === 'php' ? $params['closure'] : $params['function'];
        $callee($value, $error, $fields, $params['userdata'], $context);
    }

    public function getValidationParam()
    {
        $block = callable_code($this->closure)[1];
        $block = preg_replace('#(^\s*{)|}\s*$#u', '', $block);

        $result = [];
        $result['function'] = $this->literalJson('function($value, $error, $depends, $userdata, $context) {' . $block . '}');
        $result['closure'] = $this->closure;
        $result['userdata'] = $this->_userdata;
        return $result;
    }
}

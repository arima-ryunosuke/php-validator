<?php
namespace ryunosuke\chmonos\Condition;

use function ryunosuke\chmonos\callable_code;

/**
 * Callback バリデータ
 *
 * クロージャを渡すと php/js 共に検証がされる。
 * 実体はクロージャのコードをパースして呼び出しているだけなので、クロージャは php/js 共通のシンタックスである必要がある。
 * ただし、文字列を渡すと関数としてコールされる（歴史的な経緯で引数が異なるので注意）。
 * 基本的に js をメインに据えているが php の関数をも呼べる（が、あまり使うことはないだろう）。
 *
 * - closure: string|Closure
 *   - 実際に処理される callable
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

    public function __construct(string|\Closure $closure, $fields = [], $userdata = null)
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
        if (is_string($params['closure'])) {
            // 引数にないのでエラーが出る（将来的には js と合わせる意味でも引数に加えてしまいたい）
            $input ??= null;
            $e ??= null;

            $callee = $context['lang'] === 'php' ? $params['closure'] : $params['function'];
            return $error($callee($input, $value, $fields, $params, $consts, $error, $context, $e));
        }

        $callee = $context['lang'] === 'php' ? $params['closure'] : $params['function'];
        $callee($value, $error, $fields, $params['userdata'], $context);
    }

    public function getValidationParam()
    {
        if (is_string($this->closure)) {
            $result = [];
            $result['function'] = $this->literalJson('function($input, $value, $fields, $params, $consts, $error, $context, e) {
                return (' . strtr($this->closure, ['\\' => '.']) . ')($input, $value, $fields, $params, $consts, $error, $context, e);
            }');
            $result['closure'] = strtr($this->closure, ['.' => '\\']);
            $result['userdata'] = $this->_userdata;
            return $result;
        }

        $block = callable_code($this->closure)[1];
        $block = preg_replace('#(^\s*{)|}\s*$#u', '', $block);

        $result = [];
        $result['function'] = $this->literalJson('function($value, $error, $depends, $userdata, $context) {' . $block . '}');
        $result['closure'] = $this->closure;
        $result['userdata'] = $this->_userdata;
        return $result;
    }
}

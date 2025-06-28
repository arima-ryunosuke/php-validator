<?php
namespace ryunosuke\chmonos\Condition;

use ryunosuke\chmonos\Input;
use function ryunosuke\chmonos\class_shorten;

/**
 * OR バリデータ
 *
 * 通常 Condition は AND 的動作になるが、このクラスを使うと OR 検証が可能になる。
 * 実際は Condition をコンポジットしているだけのかなり局所的な実装。
 *
 * - conditions: array
 *   - Condition インスタンス・生成文字列の配列
 */
class Aruiha extends AbstractCondition implements Interfaces\MaxLength
{
    public const INVALID_ARUIHA = 'AruihaInvalid';

    protected static $messageTemplates = [
        self::INVALID_ARUIHA => '必ず呼び出し元で再宣言する',
    ];

    /** @var AbstractCondition[] */
    private $conditions = [];

    public function __construct($conditions)
    {
        foreach ($conditions as $name => $condition) {
            if (!($condition instanceof AbstractCondition)) {
                $condition = AbstractCondition::create($name, $condition);
            }
            $this->conditions[] = $condition;
        }

        parent::__construct();
    }

    public function getValidationParam()
    {
        $result = ['condition' => []];
        foreach ($this->conditions as $condition) {
            $result['condition'][] = [
                'class' => class_shorten($condition),
                'param' => $condition->getValidationParam(),
            ];
        }
        return $result;
    }

    public static function getJavascriptCode()
    {
        /** @noinspection JSUnresolvedFunction */
        return <<<'JS'
            (function() {
                var keys = Object.keys($params['condition']);
                for (var i = 0; i < keys.length; i++) {
                    var condition = $params['condition'][keys[i]];
                    var ok = true;
                    chmonos.condition[condition.class]($input, $value, $fields, condition.param, $consts, function() { ok = false }, $context, $e);
                    if (ok) {
                        return;
                    }
                }
                $error($consts['INVALID_ARUIHA'], []);
            })();
JS;
    }

    public function getFields()
    {
        $result = [];
        foreach ($this->conditions as $condition) {
            $result = array_merge($result, $condition->getFields());
        }
        return array_unique($result);
    }

    public function isValid($value, $fields = [], ?Input $input = null)
    {
        foreach ($this->conditions as $condition) {
            $this->messages = [];
            if ($condition->isValid($value, $fields, $input)) {
                return true;
            }
            $this->addMessage(self::INVALID_ARUIHA, null, []);
        }
        return !count($this->messages);
    }

    public function clearMessage($messageKey = null)
    {
        foreach ($this->conditions as $condition) {
            $condition->clearMessage($messageKey);
        }
        return parent::clearMessage($messageKey);
    }

    public function getMaxLength()
    {
        $lengths = [];
        foreach ($this->conditions as $condition) {
            if ($condition instanceof Interfaces\MaxLength) {
                $lengths[] = $condition->getMaxLength();
            }
        }

        if (count($lengths) === 0) {
            return null;
        }
        return max($lengths);
    }

    public function getFixture($value, $fields)
    {
        return $this->fixtureArray($this->conditions)->getFixture($value, $fields);
    }
}

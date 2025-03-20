<?php
namespace ryunosuke\chmonos\Condition;

use function ryunosuke\chmonos\is_primitive;

/**
 * 必須項目バリデータ
 *
 * - statements: ...array
 *   - 必須となる条件を可変引数で指定する
 *   - 少しややこしいので詳細は後述する
 *
 * 同引数間は AND で処理（すべて満たすと必須）、可変引数間は OR で処理（どれかを満たすと必須）される。
 *
 * ```php
 * // 下記の3条件の OR で必須が決まる
 * [
 *   //「名前1の value が == 値1」 AND 「名前2の value が <= 値2」のとき必須になる
 *   '名前1' => ['==', 値1],
 *   '名前2' => ['<=', 値2],
 * ],
 * [
 *   // 「名前3」の value が「値1, 値2, ...」のいずれかを含むとき必須になる
 *   '名前3' => ['any', [値1, 値2, ...]],
 * ],
 * [
 *   // 「名前4」の value が「値1, 値2, ...」のすべてを含むとき必須になる
 *   '名前4' => ['all', [値1, 値2, ...]],
 * ],
 * ```
 *
 * それぞれ複数指定可能。その「すべてを満たした時」必須であるとみなされる。
 * `!` で否定になる（等価は `!=`, any,all は `!any`, `!all`）。
 *
 * 対象が単一要素であれば all と any に本質的な違いはない。
 * 対象の値が配列（複数チェックボックスとか）の場合に違いが生じる。
 * （any は指定値のいずれかがチェックされていれば、all は指定値のすべてがチェックされていれば、となる）。
 * 歴史的な経緯で `all` は `in` でも指定可能（旧バージョンで in だったが意味合いが逆に感じたためリネームした）。
 *
 * 単純に要素名を指定すれば「その要素が入力されていれば必須」となる。
 */
class Requires extends AbstractCondition implements Interfaces\Propagation
{
    public const INVALID          = 'RequireInvalid';
    public const INVALID_TEXT     = 'RequireInvalidText';
    public const INVALID_MULTIPLE = 'RequireInvalidSelectSingle';

    protected static $messageTemplates = [
        self::INVALID          => 'Invalid value given',
        self::INVALID_TEXT     => '入力必須です',
        self::INVALID_MULTIPLE => '選択してください',
    ];

    protected $_statements;

    public function __construct(...$statements)
    {
        $this->_statements = [];
        foreach ($statements as $statement) {
            // 文字列指定は単純なフィールド指定とみなし、「それが入力されていたら必須」とする
            if (is_string($statement)) {
                $statement = [$statement => ['!=', '']];
            }

            foreach ($statement as $n => $rule) {
                // for compatible
                $rule[0] = match ($rule[0]) {
                    'in'     => 'all',
                    'notin'  => '!all',
                    'notall' => '!all',
                    'notany' => '!any',
                    default  => $rule[0],
                };
                $statement[$n] = $rule;

                if (in_array($rule[0], ['all', '!all', 'any', '!any'])) {
                    if (!is_array($rule[1])) {
                        throw new \InvalidArgumentException("$rule[0] methos's value must be array.");
                    }
                }
                else {
                    if (!is_primitive($rule[1])) {
                        throw new \InvalidArgumentException("$rule[0] methos's value must be scalar.");
                    }
                }
            }
            $this->_statements[] = $statement;
        }

        parent::__construct();
    }

    public function isArrayableValidation()
    {
        return true;
    }

    public function getFields()
    {
        $fields = [];
        foreach ($this->_statements as $statement) {
            $fields = array_merge($fields, array_keys($statement));
        }
        return $fields;
    }

    public function getPropagation()
    {
        return $this->getFields();
    }

    public static function validate($value, $fields, $params, $consts, $error, $context)
    {
        $nofify = function ($value, $error, $consts) {
            if (!is_array($value) && strval($value) === '') {
                $error($consts['INVALID_TEXT'], []);
            }
            elseif (is_array($value) && count($value) === 0) {
                $error($consts['INVALID_MULTIPLE'], []);
            }
        };

        if (count($params['statements']) === 0) {
            return $nofify($value, $error, $consts);
        }

        $getDepend = $context['function'](function ($name, $fields) { return $fields[$name]; }, $fields);

        if (array_reduce($params['statements'], $context['function'](function ($carry, $statement, $getDepend, $context) {
            if ($carry === true) {
                return true;
            }
            return array_reduce(array_keys($statement), $context['function'](function ($carry, $field, $statement, $getDepend, $context) {
                if ($carry === false) {
                    return false;
                }
                $operator = $statement[$field][0];
                $operand = $statement[$field][1];
                $dvalue = $getDepend($field);

                // for scalar
                if ($operator === '==') {
                    return $dvalue == $operand;
                }
                if ($operator === '===') {
                    return $dvalue === $operand;
                }
                if ($operator === '!=') {
                    return $dvalue != $operand;
                }
                if ($operator === '!==') {
                    return $dvalue !== $operand;
                }
                if ($operator === '<') {
                    return $dvalue < $operand;
                }
                if ($operator === '<=') {
                    return $dvalue <= $operand;
                }
                if ($operator === '>') {
                    return $dvalue > $operand;
                }
                if ($operator === '>=') {
                    return $dvalue >= $operand;
                }

                // for array
                $intersect = array_intersect_key(
                    array_flip($context['cast']('array', $dvalue)),
                    array_flip($operand)
                );
                if ($operator === 'any') {
                    return !!count($intersect);
                }
                if ($operator === '!any') {
                    return !count($intersect);
                }
                if ($operator === 'all') {
                    return count($intersect) === count($operand);
                }
                if ($operator === '!all') {
                    return count($intersect) !== count($operand);
                }
            }, $statement, $getDepend, $context), true);
        }, $getDepend, $context), false)) {
            $nofify($value, $error, $consts);
        }
    }

    public function getFixture($value, $fields)
    {
        // 必須エラーだからと言って代替値を用意するのは不可能
        // ただし値を入れないと必須エラーになるのは間違いないので Input の方で最後に代入している
        return $value;
    }
}

<?php
namespace ryunosuke\chmonos;

use ryunosuke\chmonos\Condition\AbstractCondition;
use ryunosuke\chmonos\Condition\Interfaces;
use ryunosuke\chmonos\Exception\ValidationException;

/**
 * UI 要素のクラス
 *
 * コンストラクタで特性を指定して input で描画して validate で検証する。
 *
 * @property \ryunosuke\chmonos\Context $context
 * @property \ryunosuke\chmonos\Condition\AbstractCondition[] $condition
 */
class Input
{
    use Mixin\Htmlable {
        createHtmlAttr as _createHtmlAttr;
    }
    use Mixin\Jsonable;

    /** @var array 生成時のデフォルト値 */
    protected static $defaultRule = [
        'title'                 => '',
        'condition'             => [],
        'options'               => [],
        'invalids'              => [],
        'invalid-option-prefix' => "\x18",
        'datalist'              => [],
        'event'                 => ['change'],
        'propagate'             => [],
        'dependent'             => true,
        'message'               => [],
        'phantom'               => [],
        'attribute'             => [],
        'inputs'                => [],
        'checkmode'             => ['server' => true, 'client' => true],
        'wrapper'               => null,
        'grouper'               => null,
        'invisible'             => false,
        'ignore'                => false,
        'trimming'              => true,
        'needless'              => [],
        'autocond'              => true,
        'multiple'              => null,
        'pseudo'                => true,
        'nullable'              => true,
        // 'default'               => null, // あるかないかでdefault値を決めるのでコメントアウト
        // 'fixture'               => null, // あるかないかでfixture値を決めるのでコメントアウト
    ];

    /** @var Input */
    protected $parent;

    /** @var Context */
    protected $context;

    /** @var Context */
    protected $parentContext;

    /** @var string */
    protected $id;

    /** @var string */
    protected $name;

    /** @var string */
    protected $type;

    /** @var array|string */
    protected $value;

    /** @var array */
    protected $rule = [];

    /** @var array */
    protected $messages = [];

    /** @var bool */
    protected $vuemode = false;

    public static function setDefaultRule($rule)
    {
        $return = static::$defaultRule;
        static::$defaultRule = array_replace(static::$defaultRule, $rule);
        return $return;
    }

    /**
     * コンストラクタ
     *
     * @param array $rule ルール配列
     * @param Input|null $parent 親要素
     */
    public function __construct($rule, $parent = null)
    {
        $rule += static::$defaultRule;
        $rule['condition'] = arrayize($rule['condition']);
        $rule['propagate'] = arrayize($rule['propagate']);
        $rule['dependent'] = arrayize($rule['dependent']);
        $rule['attribute'] = arrayize($rule['attribute']);
        $rule['needless'] = arrayize($rule['needless']);
        $rule['event'] = arrayize($rule['event']);

        // 文字列指定の Condition をオブジェクト化する
        foreach ($rule['condition'] as $name => $condition) {
            if (is_int($name) && $condition === null) {
                unset($rule['condition'][$name]);
                continue;
            }
            if (!($condition instanceof AbstractCondition)) {
                $rule['condition'][$name] = AbstractCondition::create($name, $condition);
            }
            if ($rule['checkmode'] !== null && $rule['checkmode'] !== []) {
                $rule['condition'][$name]->setCheckMode($rule['checkmode']);
            }
        }

        // 属性の正規化
        foreach ($rule['attribute'] as $name => $attribute) {
            if (is_int($name)) {
                throw new \InvalidArgumentException("attribute requires hash array");
            }
        }
        foreach ($rule['needless'] as $name => $attribute) {
            if (is_int($name)) {
                unset($rule['needless'][$name]);
                $rule['needless'][$attribute] = $attribute;
            }
        }

        // デフォルト値
        if (!array_key_exists('default', $rule)) {
            // inputs を持ってるなら間違いなく空配列
            if ($rule['inputs']) {
                $rule['default'] = [];
            }
            // options を持っているならその最初のキー
            elseif ($rule['options']) {
                // optgroup は配列を持ちうるので foreach でそれを考慮
                foreach ($rule['options'] as $key => $val) {
                    if (is_array($val)) {
                        reset($val);
                        $rule['default'] = key($val);
                    }
                    else {
                        $rule['default'] = $key;
                    }
                    break;
                }
            }
            // multiple なら配列
            elseif ($rule['multiple']) {
                $rule['default'] = [];
            }
            // 上記以外は null
            else {
                $rule['default'] = null;
            }
        }

        // multiple の自動設定
        if ($rule['multiple'] === null) {
            $rule['multiple'] = $rule['inputs'] || is_array($rule['default']);
        }

        // invalids の自動設定
        if (strlen($rule["invalid-option-prefix"])) {
            array_walk_recursive($rule['options'], function ($v, $k) use (&$rule) {
                if (is_string($v)) {
                    [, $value] = explode($rule["invalid-option-prefix"], $v, 2) + [1 => null];
                    if ($value !== null) {
                        $rule['invalids'][$k] = $value;
                    }
                }
            });
        }

        $this->rule = $rule;
        $this->context = $this->inputs ? new Context($this->inputs, $this) : null;
        $this->id = $rule['id'] ?? '';
        $this->name = $rule['name'] ?? '';
        $this->type = $this->_detectType();
        $this->parent = $parent;

        // see https://qiita.com/ArimaRyunosuke/items/bd474ece6f2a5a79c5a9
        static $automethods = [];
        if (!isset($automethods[static::class])) {
            $automethods[static::class] = [];
            foreach (get_class_methods($this) as $method) {
                if (preg_match('#^_setAuto(.+)#i', $method, $m)) {
                    $automethods[static::class][$m[1]] = $method;
                }
            }
        }
        foreach ($automethods[static::class] as $name => $method) {
            if ($rule['autocond'] === true || $rule['autocond'] instanceof \Closure || $rule['autocond'] instanceof \Closure || (is_array($rule['autocond']) && ($rule['autocond'][$name] ?? true))) {
                /** @var AbstractCondition $cond */
                $cond = $this->$method();
                if ($cond !== null) {
                    if ($rule['autocond'] instanceof \Closure) {
                        $rule['autocond']($cond);
                    }
                    if ((is_array($rule['autocond']) && ($rule['autocond'][$name] ?? null) instanceof \Closure)) {
                        $rule['autocond'][$name]($cond);
                    }
                }
            }
        }

        // 検証メッセージ設定
        /** @var AbstractCondition $condition */
        foreach ($rule['message'] as $key => $message) {
            // 配列ならその要素のみの設定となる
            if (is_array($message)) {
                foreach ($message as $name => $msg) {
                    $condition = $this->rule['condition'][$key];
                    $condition->setMessageTemplate("$msg", $name);
                }
            }
            // 文字列なら共通で設定
            elseif (is_string($message)) {
                foreach ($this->rule['condition'] as $condition) {
                    $condition->setMessageTemplate("$message", $key);
                }
            }
        }

        $this->setValue($this->default);
    }

    /**
     * rule プロパティへのアクセス
     *
     * @param string $name
     * @return bool
     */
    public function __isset($name)
    {
        if ($name === 'context') {
            return isset($this->context);
        }

        return isset($this->rule[$name]);
    }

    /**
     * rule プロパティへのアクセス
     *
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        if ($name === 'context') {
            return $this->context;
        }

        if (!array_key_exists($name, $this->rule)) {
            throw new \InvalidArgumentException("undefined property '{$name}'");
        }
        return $this->rule[$name];
    }

    /**
     * rule プロパティへのアクセス
     *
     * いかなる検証も型チェックも行われない直接代入なので注意して使わなければならない。
     *
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        if ($name === 'context') {
            $this->context = $value;
            return;
        }

        if (!array_key_exists($name, $this->rule)) {
            throw new \InvalidArgumentException("undefined property '{$name}'");
        }
        $this->rule[$name] = $value;
    }

    public function resolveTitle($member)
    {
        return $this->parentContext->$member?->title;
    }

    public function resolveLabel($value)
    {
        $options = [];
        array_walk_recursive($this->rule['options'], function ($v, $k) use (&$options) {
            $options[$k] = $v instanceof \stdClass ? $v->label : $v;
        });
        return $this->options[$value] ?? $value;
    }

    public function initialize(Context $root, Context $context)
    {
        $this->parentContext = $context;

        foreach ($this->condition as $condition) {
            if ($condition instanceof Condition\Interfaces\Initialize) {
                $condition->initialize($root, $context, $this->parent->name ?? null, $this->name);
            }
        }

        foreach ($this->getDependent() as $dependent) {
            if ($dependent[0] === '/') {
                $target = $root->{substr($dependent, 1)};
                $target->rule['propagate'] = array_merge($target->propagate, [($this->parent->name ?? '') . '/' . $this->name]);
            }
            else {
                $target = $context->$dependent;
                $target->rule['propagate'] = array_merge($target->propagate, [$this->name]);
            }
        }

        if ($this->getType() === 'arrays') {
            $this->context->initialize($root);
        }
    }

    public function normalize($values)
    {
        $exists = function ($value, $values) {
            if ($this->nullable) {
                return array_key_exists($value, $values);
            }
            else {
                return isset($values[$value]);
            }
        };

        if ($phantom = $this->phantom) {
            $flag = true;
            $palues = [];
            for ($i = 1; $i < count($phantom); $i++) {
                $palue = array_get($values, $phantom[$i], '');
                if (strlen($palue) === 0) {
                    $flag = false;
                    break;
                }
                $palues[] = $palue;
            }

            $value = $flag ? vsprintf($phantom[0], $palues) : $this->default;
        }
        elseif ($exists($this->name, $values)) {
            $value = $values[$this->name];

            if ($this->pseudo !== false && $value === '') {
                if ($this->multiple) {
                    $value = $this->pseudo === true ? [] : (array) $this->pseudo;
                }
                else {
                    $value = $this->pseudo === true ? '' : (string) $this->pseudo;
                }
            }
        }
        else {
            $value = $this->default;
        }

        // trim するなら trim （ただし必要があるのは string のみ）
        if ($this->trimming && is_string($value)) {
            $value = mb_trim($value);
        }

        return $value;
    }

    /**
     * value セッター
     *
     * @param mixed $value この UI の値
     * @return mixed 正規化された $value
     */
    public function setValue($value)
    {
        if ($this->getType() === 'arrays') {
            // context のルールで引数を正規化するために呼ぶ（値の設定が目的ではない）
            foreach ($value as $n => $v) {
                $value[$n] = $this->context->normalize($v);
            }
            // ↑で設定されてしまっているので戻す
            $this->context->normalize([]);
        }

        // Condition から値を変換できるならそれを(最後を優先するので reverse してる)
        foreach (array_reverse($this->condition) as $condition) {
            if ($condition instanceof Condition\Interfaces\ConvertibleValue) {
                $value = $condition->getValue($value);
            }
        }

        return $this->value = $value;
    }

    /**
     * value ゲッター
     *
     * @param int|null $index 欲しい連番
     * @return mixed この UI の値
     */
    public function getValue($index = null)
    {
        if ($this->parent && $index !== null) {
            return array_dive($this->parent->value, [$index, $this->name], $this->default);
        }
        return $this->value;
    }

    /**
     * タイプを取得
     *
     * @return string タイプ
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * rule 配列から type を推測する
     */
    protected function _detectType()
    {
        if (isset($this->rule['type'])) {
            return $this->rule['type'];
        }

        // Context を持ってるなら間違いなく arrays
        if (isset($this->context)) {
            return 'arrays';
        }

        // options を持っているなら [checkbox, radio, select] のいずれか
        if ($this->options) {
            // 階層を持つなら optgroup なので select
            if (array_depth($this->options, 2) > 1) {
                return 'select';
            }
            // options に空文字を含む場合は select の場合が*多い*（未選択選択肢のため）
            if (array_key_exists('', $this->options)) {
                return 'select';
            }
            // options が 1つなら単 checkbox
            if (count($this->options) === 1) {
                return 'checkbox';
            }
            // default が配列の場合は multiple checkbox の場合が*多い*
            if (is_array($this->default)) {
                return 'checkbox';
            }
            // それ以外はとりあえず radio で
            return 'radio';
        }

        // Condition から推測できるならそれを(最後を優先するので reverse してる)
        foreach (array_reverse($this->condition) as $condition) {
            if ($condition instanceof Condition\Interfaces\InferableType) {
                return $condition->getType();
            }
        }

        // 上記以外は text
        return 'text';
    }

    /**
     * MaxLength を実装しているものは StringLength を自動設定する
     *
     * ただし、すでに StringLength 条件が登録されていたら何もしない。
     */
    protected function _setAutoStringLength()
    {
        $lengths = [];
        foreach ($this->condition as $condition) {
            if ($condition instanceof Condition\StringLength) {
                return;
            }
            if ($condition instanceof Interfaces\MaxLength) {
                if (($max_length = $condition->getMaxLength()) !== null) {
                    $lengths[] = $max_length;
                }
            }
        }

        // 一つもないなら属性自体を付加しないため null を返す
        if (count($lengths) === 0) {
            return null;
        }

        // 指定されているもののうち最も小さい値
        $max = min($lengths);

        $stringlength = new Condition\StringLength(null, $max);
        $this->rule['condition'][class_shorten($stringlength)] = $stringlength;
        return $stringlength;
    }

    /**
     * options を必要とする要素（select とか radio とか）に InArray 条件を自動で付加する
     *
     * ただし、すでに InArray 条件が登録されていたら何もしない。
     */
    protected function _setAutoInArray()
    {
        foreach ($this->condition as $condition) {
            if ($condition instanceof Condition\InArray) {
                return;
            }
        }

        if (!$this->options) {
            return;
        }

        // optgroup 用の配列が来ることがあるので flat にする
        $options = [];
        $tmp = $this->options;
        array_walk_recursive($tmp, function ($v, $k) use (&$options) {
            $options[$k] = $v;
        });

        // デフォルト値を許容する
        foreach (arrayize($this->default) as $defval) {
            $options[$defval] = '';
        }

        // pseudo 値を許容する
        if ($this->pseudo !== false && count($this->options) === 1) {
            $options[$this->pseudo === true ? '' : $this->pseudo] = true;
        }

        $inarray = new Condition\InArray(array_keys($options));
        $this->rule['condition'][class_shorten($inarray)] = $inarray;
        return $inarray;
    }

    /**
     * options を必要とする要素（select とか radio とか）に特殊プレフィックスで NotInArray 条件を自動で付加する
     *
     * ただし、すでに NotInArray 条件が登録されていたら何もしない。
     */
    protected function _setAutoNotInArray()
    {
        foreach ($this->condition as $condition) {
            if ($condition instanceof Condition\NotInArray) {
                return;
            }
        }

        if (!$this->options) {
            return;
        }

        $notoptions = [];
        array_walk_recursive($this->rule['options'], function (&$v, $k) use (&$notoptions) {
            if (array_key_exists($k, $this->invalids)) {
                if ($v instanceof \stdClass) {
                    $v->label = $this->invalids[$k] ?? $v->label;
                    $v->invalid = false;
                }
                else {
                    $v = $this->invalids[$k] ?? $v;
                }
                $notoptions[] = $k;
            }
            elseif ($v instanceof \stdClass && ($v->invalid ?? false)) {
                $notoptions[] = $k;
            }
        });

        if ($notoptions) {
            $notinarray = new Condition\NotInArray($notoptions);
            $this->rule['condition'][class_shorten($notinarray)] = $notinarray;
            return $notinarray;
        }
    }

    /**
     * Distinct の delimiter を自動設定する
     *
     * ただし、すでに delimiter が設定されていたら何もしない。
     */
    protected function _setAutoDistinctDelimiter()
    {
        $distinct = null;
        foreach ($this->condition as $condition) {
            if ($condition instanceof Condition\Distinct) {
                $distinct = $condition;
                break;
            }
        }

        if ($distinct === null || $distinct->getDelimiter() !== null) {
            return;
        }

        $delimiters = [];
        foreach ($this->condition as $condition) {
            if ($condition instanceof Condition\Interfaces\MultipleValue) {
                $delimiters[] = $condition->getDelimiter();
            }
        }

        $delimiters = array_filter($delimiters, fn($v) => $v !== null);
        if (count($delimiters) !== 1) {
            throw new \UnexpectedValueException('AutoDistinctDelimiter failed. notfound delimiter');
        }
        $distinct->setDelimiter(reset($delimiters));
    }

    /**
     * number 用の min/max/step を算出する
     *
     * @return array ['min'=>$min, 'max'=>$max, 'step'=>$step]
     */
    protected function _getRange()
    {
        $range = [
            'min'  => null,
            'max'  => null,
            'step' => null,
        ];

        foreach ($this->condition as $condition) {
            if ($condition instanceof Interfaces\Range) {
                $min = $condition->getMin();
                $max = $condition->getMax();
                $step = $condition->getStep();
                // 最もキツイ制限を返す
                if ($range['min'] === null || ($min !== null && $min > $range['min'])) {
                    $range['min'] = $min;
                }
                if ($range['max'] === null || ($max !== null && $max < $range['max'])) {
                    $range['max'] = $max;
                }
                if ($range['step'] === null || ($step !== null && $step > $range['step'])) {
                    $range['step'] = $step;
                }
            }
        }

        return $range;
    }

    /**
     * 自身のバリデータチェインの中から検証用のパラメータを集めて返す
     *
     * @return array
     */
    public function getValidationRule()
    {
        return [
            'condition' => array_map_filter($this->condition, function ($condition) { return $condition->getRule(); }),
            'event'     => (array) $this->event,
            'propagate' => (array) $this->propagate,
            'phantom'   => (array) $this->phantom,
            'invisible' => (bool) $this->invisible,
            'trimming'  => (bool) $this->trimming,
            'needless'  => (array) $this->needless,
        ];
    }

    /**
     * 伝播元をまとめて取得して配列で返す
     */
    public function getDependent()
    {
        if (!$this->dependent) {
            return [];
        }

        $propagation = [];

        foreach ($this->dependent as $dependent) {
            if ($dependent === true) {
                // Interfaces\Propagation を実装してるやつから伝播元を取得
                foreach ($this->condition as $condition) {
                    if ($condition instanceof Interfaces\Propagation) {
                        $propagation = array_merge($propagation, $condition->getPropagation());
                    }
                }

                // ファントム値から伝播元を取得
                if ($this->phantom) {
                    $phantom = array_slice($this->phantom, 1);
                    $propagation = array_merge($propagation, $phantom);
                }
            }
            else {
                $propagation[] = $dependent;
            }
        }

        return array_unique($propagation);
    }

    /**
     * ajax Condition の結果を返す
     *
     * 複数の Ajax があることはまずないだろうので最初の要素のみ。
     * Ajax が無い場合は例外を投げる。
     *
     * @param array|null $fields 依存データ。未指定時はよしなに
     * @return ?array メッセージ配列
     */
    public function getAjaxResponse($fields = null)
    {
        foreach ($this->condition as $condition) {
            if ($condition instanceof Condition\Ajax) {
                return $condition->response($fields);
            }
        }
        throw new \UnexpectedValueException('AjaxCondition is not found.');
    }

    /**
     * 登録されている Condition 全てを回して検証
     *
     * @param array $values 値
     * @param array $original 入力元オリジナル配列
     * @return bool エラーがないならtrue
     */
    public function validate($values, $original)
    {
        $value = $values[$this->name];

        // 配列の許可/未許可。通常フローではほぼありえないので例外で良い(=親切じゃなくて良い)
        if (!$this->getType() === 'arrays' && (!$this->multiple && is_array($value)) || ($this->multiple && !is_array($value))) {
            throw new ValidationException(null, sprintf("'%s' invalid type (%s).", $this->name, gettype($value)));
        }

        $fields = $values + $original;
        $this->messages = [];
        $isvalid = true;
        foreach ($this->condition as $cname => $condition) {
            // null, 空文字, あるいは空配列の時に「空」と定義し、「空」の場合は Requires 以後の検証をしない
            if (!$condition instanceof Condition\Requires && ($value === null || $value === '' || $value === [])) {
                break;
            }
            $vs = $condition->isArrayableValidation() ? [$value] : arrayize($value);
            $flag = array_all($vs, function ($v) use ($condition, $fields) { return $condition->isValid($v, $fields, $this); });
            $mess = $condition->getMessages();

            $isvalid = $flag && $isvalid;
            if (count($mess) > 0) {
                $this->messages[$cname] = $mess;
            }
        }

        // arrays タイプは子も検証する
        if ($this->getType() === 'arrays') {
            foreach ($value as $i => $v) {
                $isvalid = $this->context->validate($v, $original) && $isvalid;
                $messages = $this->context->getMessages();
                if (count($messages) > 0) {
                    $this->messages[$i] = $messages;
                }
            }
        }

        return $isvalid;
    }

    /**
     * バリデーションメッセージを返す
     *
     * @return array バリデーションメッセージ
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * バリデーション結果をクリアする
     */
    public function clear()
    {
        $this->messages = [];
    }

    /**
     * UI ラベル描画
     *
     * @param array $attrs 属性連想配列
     * @return string label 文字列
     */
    public function label($attrs = [])
    {
        $this->vuemode = array_unset($attrs, 'vuejs') ?? false;

        $name = $this->name;
        $index = '';

        if ($this->parent) {
            $mainname = $this->parent->name;
            $subname = $this->name;

            $index = array_unset($attrs, 'index') ?? '__index';
            $name = $this->_concatString("{$mainname}[", [$index], "]{$subname}");
            $attrs['for'] = $attrs['for'] ?? $this->_concatString("{$this->id}{$mainname}-", [$index], "-{$subname}");
        }

        foreach ($this->_createDataAttrs($index) as $key => $val) {
            $attrs["data-vlabel-$key"] = $val;
        }

        array_unset($attrs, 'child');
        $label = array_unset($attrs, 'label', $this->title);
        $attrs['for'] = $attrs['for'] ?? $this->_concatString("{$this->id}{$name}");
        $attrs['class'] = concat($attrs['class'] ?? '', ' ') . 'validatable_label';
        $attr = $this->createHtmlAttr($attrs, null, 'label');
        return "<label $attr>{$this->escapeHtml($label)}</label>";
    }

    /**
     * UI インプット描画
     *
     * @param array $attrs 属性連想配列
     * @return string 自身の type に基づく inputXXX の結果文字列
     */
    public function input($attrs = [])
    {
        $this->vuemode = array_unset($attrs, 'vuejs') ?? false;

        $name = $this->_concatString($this->name);
        $index = '';
        $attrs += $this->attribute;

        if ($this->parent) {
            $parent_name = $this->parent->name;
            $subname = $this->name;

            if (!isset($attrs['index'])) {
                $attrs['disabled'] = 'disabled';
                $attrs['value'] = $this->default;
            }

            $index = array_unset($attrs, 'index') ?? '__index';
            $name = $this->_concatString("{$parent_name}[", [$index], "][{$subname}]");
            $attrs['id'] = $attrs['id'] ?? $this->_concatString("{$this->id}{$parent_name}-", [$index], "-{$subname}");
            $attrs['value'] = $attrs['value'] ?? $this->getValue($index);
        }

        $type = $attrs['type'] ?? $this->getType();

        // 存在しない type は text にフォールバック
        $method = '_input' . ucfirst(strtolower($type));
        if (!method_exists($this, $method)) {
            $method = '_inputText';
        }

        // select, textarea は専用タグなので type 不要
        if ($type === 'select' || $type === 'textarea') {
            unset($attrs['type']);
        }

        $attrs['data-validation-title'] = $this->title;
        foreach ($this->_createDataAttrs($index) as $key => $val) {
            $attrs["data-vinput-$key"] = $val;
        }

        $attrs['wrapper'] = $attrs['wrapper'] ?? $this->wrapper;
        $attrs['grouper'] = $attrs['grouper'] ?? $this->grouper;

        $child = array_unset($attrs, 'child');
        if ($this->vuemode && $type !== 'file') {
            $modifier = (array) array_unset($attrs, 'v-model.modifier', []);
            if ($type === 'number') {
                $modifier[] = 'number';
            }
            $modifier = array_map(fn($v) => ".$v", array_unique($modifier));
            $attrs['v-model' . implode('', $modifier)] = $attrs['v-model'] ?? concat($child, '.') . $this->name;
        }

        // multiple 属性はその数生成する
        if ($this->multiple) {
            // ただしいくつかの input は html レベルで複数入力に対応しているのでそっちを使う
            // radio だけは特別扱いで除外している（複数入力可能な radio はもはや radio ではない）
            if (!in_array($type, ['arrays', 'file', 'checkbox', 'select', 'radio'])) {
                $attrs['name'] = $this->_concatString([$name], '[]');
                $vs = strpos($name, '[__index]') === false ? $this->getValue() : $this->default;
                $format = array_unset($attrs, 'format', '%s');
                $result = [];
                foreach ((array) $vs as $n => $v) {
                    $attrs2 = $attrs;
                    $attrs2['id'] = $this->_concatString($this->id, [$name], "_$n");
                    $attrs2['value'] = $v;
                    $result[] = sprintf($format, $this->$method($attrs2));
                }
                $grouper = array_unset($attrs, 'grouper');
                return $this->_wrapInput('group', $grouper, $type, $attrs['name'], '', implode('', $result));
            }
        }

        $attrs['name'] = $name;
        $attrs['id'] = $attrs['id'] ?? $this->_concatString($this->id, [$name]);
        return $this->$method($attrs);
    }

    public function fixture($default, $fields = [])
    {
        if (array_key_exists('fixture', $this->rule)) {
            $value = $this->rule['fixture'];
            if ($value instanceof \Closure) {
                $value = $value($this);
            }
            return $value;
        }

        $value = $default;
        $fields += array_fill_keys($this->getDependent(), null);

        $fixture_condition = function (AbstractCondition $condition, $value) use ($fields) {
            // null, 空文字, あるいは空配列の時に「空」と定義し、「空」の場合は検証をしない
            if (!($value === null || $value === '' || $value === []) && $condition->isValidInternal($value, $fields)) {
                return $value;
            }
            return $condition->getFixture($value, $fields);
        };

        if ($this->getType() === 'arrays') {
            // 歴史的な経緯により ArrayLength は親・子の両方に対応していて fixture がスカラー値を返すので前もって埋める必要がある
            $param = [];
            foreach ($this->condition as $condition) {
                if ($condition instanceof Condition\ArrayLength) {
                    $param = $condition->getValidationParam();
                    break;
                }
            }

            $values = array_pad((array) $value, rand($param['min'] ?? 1, $param['max'] ?? 5), []);
            foreach ($values as $i => $value) {
                $values[$i] = $this->context->getFixture($value);
            }

            foreach ($this->condition as $condition) {
                if ($condition instanceof Condition\AbstractParentCondition) {
                    $values = $fixture_condition($condition, $values);
                }
            }
            return $values;
        }

        // 実値が配列/非配列、条件が受け付ける値が配列/非配列、変換を伴うかどうか、などの負債が凄く、統一できないため明確に分ける
        // リファクタ対象。値はすべて配列として扱って意識しないようにすればいい
        if ($this->multiple || is_array($value)) {
            $value = (array) $value;
            foreach ($this->condition as $condition) {
                $value = $condition->isArrayableValidation() ? [$value] : (arrayize($value) ?: [null]);
                $values = [];
                foreach ($value as $v) {
                    $values = array_merge($values, arrayize($fixture_condition($condition, $v)));
                }
                $value = $values;
            }
        }
        else {
            foreach ($this->condition as $condition) {
                // Requires が ArrayableValidation なのは歴史的経緯の設計ミスなので除外
                $value = !$condition instanceof Condition\Requires && $condition->isArrayableValidation() ? (array) $value : $value;
                $value = $fixture_condition($condition, $value);
            }
        }

        if ($value === null || $value === '' || $value === []) {
            $value = $this->default;
            if ($value === null || $value === '' || $value === []) {
                foreach ($this->condition as $condition) {
                    if ($condition instanceof Condition\Requires) {
                        $value = 'required';
                        break;
                    }
                }
            }
        }
        return $value;
    }

    protected function _inputArrays($attrs)
    {
        $attrs['name'] = $this->_concatString('__', [$attrs['name']]);
        $attrs['type'] = 'dummy';
        $attrs['value'] = 'dummy';
        $attrs['style'] = concat($attrs['style'] ?? '', ';') . $this->createStyleAttr([
                'border'     => '0px',
                'width'      => '1px',
                'height'     => '1px',
                'visibility' => 'hidden',
            ]);
        return $this->_inputText($attrs);
    }

    protected function _inputCheckbox($attrs)
    {
        $hidden = '';
        if ($this->pseudo !== false) {
            $hidden = $this->_pseudoHidden($attrs['name']);
        }

        $options = array_get($attrs, 'options', $this->options);
        $multiple_mode = $this->multiple || count($options) > 1;

        $attrs['name'] = $multiple_mode ? $this->_concatString([$attrs['name']], '[]') : $attrs['name'];
        $attrs['class'] = concat($attrs['class'] ?? '', ' ') . 'validatable';
        $attrs['type'] = 'checkbox';

        $grouper = array_unset($attrs, 'grouper');
        return $this->_wrapInput('group', $grouper, $attrs['type'], $attrs['name'], '', $hidden . $this->_inputChoice($attrs));
    }

    protected function _inputFile($attrs)
    {
        $attrs['multiple'] = $this->multiple;
        $attrs['name'] = $attrs['multiple'] ? $this->_concatString([$attrs['name']], '[]') : $attrs['name'];
        $attrs['class'] = concat($attrs['class'] ?? '', ' ') . 'validatable';
        $attrs['type'] = 'file';

        // FileType を持っているなら accept 属性
        if (!isset($attrs['accept'])) {
            foreach ($this->condition as $condition) {
                if ($condition instanceof Condition\FileType) {
                    $attrs['accept'] = implode(',', $condition->getAccepts());
                }
            }
        }

        $wrapper = array_unset($attrs, 'wrapper');
        array_unset($attrs, 'grouper');
        $attr = $this->createHtmlAttr($attrs, null, $attrs['type']);
        return $this->_wrapInput('wrapper', $wrapper, $attrs['type'], $attrs['name'], '', "<input $attr>");
    }

    protected function _inputRadio($attrs)
    {
        $attrs['class'] = concat($attrs['class'] ?? '', ' ') . 'validatable';
        $attrs['type'] = 'radio';

        $grouper = array_unset($attrs, 'grouper');
        return $this->_wrapInput('group', $grouper, $attrs['type'], $attrs['name'], '', $this->_inputChoice($attrs));
    }

    protected function _inputSelect($attrs)
    {
        $hidden = '';
        if ($this->pseudo !== false && $this->multiple) {
            $hidden = $this->_pseudoHidden($attrs['name']);
        }

        $attrs['multiple'] = $this->multiple;
        $attrs['name'] = $attrs['multiple'] ? $this->_concatString([$attrs['name']], '[]') : $attrs['name'];
        $attrs['class'] = concat($attrs['class'] ?? '', ' ') . 'validatable';

        $value = (array) array_unset($attrs, 'value', $this->getValue());
        $flipped_value = array_flip(array_map('strval', $value));

        $option_attrs = (array) array_unset($attrs, 'option_attrs', []);
        $options = (array) array_unset($attrs, 'options', $this->options);

        $result = [];
        foreach ($options as $key => $text) {
            // option
            if (!is_array($text)) {
                $result[] = $this->_inputOption($flipped_value, $key, $text, $option_attrs);
            }
            // optgroup
            else {
                $optgroup = '';
                foreach ($text as $key2 => $text2) {
                    $optgroup .= $this->_inputOption($flipped_value, $key2, $text2, $option_attrs);
                }
                $result[] = '<optgroup label="' . $key . '">' . $optgroup . '</optgroup>';
            }
        }

        $wrapper = array_unset($attrs, 'wrapper');
        array_unset($attrs, 'grouper');
        $attr = $this->createHtmlAttr($attrs, null, 'select');
        return $hidden . $this->_wrapInput('wrapper', $wrapper, 'select', $attrs['name'], $value, "<select $attr>" . implode('', $result) . "</select>");
    }

    protected function _inputText($attrs)
    {
        $attrs['type'] = $attrs['type'] ?? $this->getType();
        $attrs['class'] = concat($attrs['class'] ?? '', ' ') . 'validatable';
        $attrs['value'] = $attrs['value'] ?? $this->getValue();

        // Range を持っているなら min/max/step 属性
        $ranges = $this->_getRange();
        if (!isset($attrs['min']) && $ranges['min'] !== null) {
            $attrs['min'] = $ranges['min'];
        }
        if (!isset($attrs['max']) && $ranges['max'] !== null) {
            $attrs['max'] = $ranges['max'];
        }
        if (!isset($attrs['step']) && $ranges['step'] !== null) {
            $attrs['step'] = $ranges['step'];
        }

        // datalist
        $datalist = '';
        $options = array_unset($attrs, 'datalist', $this->datalist);
        if ($options) {
            $datalist_attrs = (array) array_unset($attrs, 'datalist_attrs', []);
            $optionhtmls = [];
            foreach ((array) $options as $key => $text) {
                $optionhtmls[] = $this->_inputOption([], is_int($key) ? $text : $key, $text, $datalist_attrs);
            }

            $attrs['list'] ??= $attrs['id'] . '-datalist';
            $datalist = "<datalist id='{$this->escapeHtml($attrs['list'])}'>" . implode('', $optionhtmls) . "</datalist>";
        }

        $wrapper = array_unset($attrs, 'wrapper');
        array_unset($attrs, 'grouper');
        $attr = $this->createHtmlAttr($attrs, null, $attrs['type']);
        return $this->_wrapInput('wrapper', $wrapper, $attrs['type'], $attrs['name'], $attrs['value'], "<input $attr>$datalist");
    }

    protected function _inputTextarea($attrs)
    {
        $attrs['class'] = concat($attrs['class'] ?? '', ' ') . 'validatable';

        $value = array_unset($attrs, 'value', $this->getValue());

        $wrapper = array_unset($attrs, 'wrapper');
        array_unset($attrs, 'grouper');
        $attr = $this->createHtmlAttr($attrs, null, 'textarea');
        $content = $this->vuemode ? "" : "\n" . $this->escapeHtml($value);
        return $this->_wrapInput('wrapper', $wrapper, 'textarea', $attrs['name'], $value, "<textarea $attr>$content</textarea>");
    }

    protected function _inputChoice($attrs)
    {
        $value = (array) array_unset($attrs, 'value', $this->getValue());
        $flipped_value = array_flip(array_map('strval', $value));

        $labeled = array_unset($attrs, 'labeled', 'right');
        $label_attrs = (array) array_unset($attrs, 'label_attrs', []);

        $format = array_unset($attrs, 'format', '');
        $separator = array_unset($attrs, 'separator', '');

        $options = (array) array_unset($attrs, 'options', $this->options);

        $wrapper = array_unset($attrs, 'wrapper');

        $result = [];
        foreach ($options as $key => $text) {
            // 個別な属性値
            $params = $attrs;
            $params['value'] = $key;

            // data-* で配列ならマップする
            foreach ($params as $name => $param) {
                if (strpos($name, 'data-') === 0 && is_array($param)) {
                    $params[$name] = array_get($param, $key, '');
                }
            }

            // 値が一致するなら checked
            if (isset($flipped_value[$key])) {
                $params['checked'] = "checked";
            }
            if (array_key_exists($key, $this->invalids)) {
                $params['class'] = concat($params['class'] ?? '', ' ') . 'validation_invalid';
            }

            // for id のペア
            $params['id'] = $this->_concatString([$params['id']], "-$key");
            $label_attrs['for'] = $params['id'];

            // 属性値を生成（input と label）
            $attr = $this->createHtmlAttr($params, null, $attrs['type']);

            // html 生成
            $html = "<input $attr>";
            if ($labeled) {
                $lattrs = $this->createHtmlAttr($label_attrs, $key, 'label');
                if ($labeled === 'left') {
                    $html = "<label $lattrs>{$this->escapeHtml($text)}</label>$html";
                }
                elseif ($labeled === 'outer') {
                    $html = "<label $lattrs>$html{$this->escapeHtml($text)}</label>";
                }
                else {// right
                    $html = "$html<label $lattrs>{$this->escapeHtml($text)}</label>";
                }
            }
            $html = $this->_wrapInput('wrapper', $wrapper, $attrs['type'], $attrs['name'], $key, $html);

            // フォーマットが指定されているなら sprintf を通す
            if (strlen($format) > 0) {
                $html = sprintf($format, $html);
            }

            $result[] = $html;
        }

        return implode($separator, $result);
    }

    protected function _inputOption($value, $key, $text, $option_attrs)
    {
        if ($text instanceof \stdClass) {
            $label = $text->label;

            unset($text->label);
            unset($text->invalid);

            $option_attrs += (array) $text;
            $text = $label;
        }

        if (isset($value[$key])) {
            $option_attrs['selected'] = 'selected';
        }
        if (array_key_exists($key, $this->invalids)) {
            $option_attrs['class'] = concat($option_attrs['class'] ?? '', ' ') . 'validation_invalid';
        }
        $option_attrs['value'] = $key;
        $oattrs = $this->createHtmlAttr($option_attrs, $key, 'option');

        return "<option $oattrs>{$this->escapeHtml($text)}</option>";
    }

    protected function _pseudoHidden($name)
    {
        $hiddenAttr = $this->createHtmlAttr([
            'type'               => 'hidden',
            'name'               => $name,
            'value'              => '',
            'data-vinput-pseudo' => 'true',
        ], null, null);
        return "<input $hiddenAttr>";
    }

    protected function _wrapInput($mode, $class, $type, $name, $value, $html)
    {
        if ("$class" === "") {
            return $html;
        }
        if (!in_array($type, ['checkbox', 'radio'])) {
            $value = '';
        }
        $class = $this->escapeHtml($class);
        $type = $this->escapeHtml($type);
        $name = $this->escapeHtml($name, ' ', ENT_COMPAT);
        $value = $this->escapeHtml($value);
        $vuemark = $this->vuemode ? ':' : '';
        $data_value = $mode === 'wrapper' ? "data-value=\"$value\" " : '';
        return "<span {$vuemark}data-vinput-$mode=\"$name\" {$data_value}class=\"$class input-$type\">$html</span>";
    }

    protected function _createDataAttrs($index)
    {
        $parent_name = $this->parent ? $this->parent->name : '';
        $name = $this->name;

        return [
            'id'    => $this->_concatString($parent_name, strlen($parent_name) ? '/' : '', [$index], strlen($index) ? '/' : '', $name),
            'class' => $this->_concatString($parent_name, strlen($parent_name) ? '/' : '', $name),
            'index' => $this->_concatString([$index]),
        ];
    }

    protected function _concatString(...$values)
    {
        if (!$this->vuemode) {
            return implode('', array_flatten($values));
        }

        $expression = [];
        foreach ($values as $value) {
            if (is_array($value)) {
                if ($value) {
                    $expression[] = implode('+', $value);
                }
            }
            else {
                if (strlen($value)) {
                    // JSON ではなく js の式なのでシングルクオートで問題ない（Htmlable::createHtmlAttr も参照）
                    $expression[] = preg_replace('#^"|"$#u', "'", json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
                }
            }
        }
        $expression = array_filter($expression, 'strlen');
        if (!$expression) {
            return "''";
        }
        return implode('+', $expression);
    }

    public function createHtmlAttr($attrs, $arg = null, $type = null)
    {
        if ($this->vuemode) {
            if ($type !== null) {
                if (in_array($type, ['checkbox', 'radio'])) {
                    unset($attrs['checked']);
                }
                elseif (in_array($type, ['option'])) {
                    unset($attrs['selected']);
                }
                else {
                    unset($attrs['value']);
                }
            }

            $vueattrs = ['^id$', '^for$', '^name$', '^data-vlabel-', '^data-vinput-'];
            foreach ($attrs as $k => $v) {
                if (preg_match("#" . implode('|', $vueattrs) . "#u", $k)) {
                    $attrs[":$k"] = $v;
                    unset($attrs[$k]);
                }
            }
        }

        return $this->_createHtmlAttr($attrs, $arg);
    }
}

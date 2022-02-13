<?php
namespace ryunosuke\chmonos;

use ryunosuke\chmonos\Condition;
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
    use Mixin\Htmlable;
    use Mixin\Jsonable;

    /** @var array 生成時のデフォルト値 */
    protected static $defaultRule = [
        'title'     => '',
        'condition' => [],
        'options'   => [],
        'event'     => ['change'],
        'propagate' => [],
        'dependent' => true,
        'message'   => [],
        'phantom'   => [],
        'attribute' => [],
        'inputs'    => [],
        'checkmode' => ['server' => true, 'client' => true],
        'wrapper'   => null,
        'invisible' => false,
        'ignore'    => false,
        'trimming'  => true,
        'ime-mode'  => true,
        'autocond'  => true,
        'multiple'  => null,
        'pseudo'    => true,
        // 'default'    => null, // あるかないかでデフォルト値を決めるのでコメントアウト
    ];

    /** @var Input */
    protected $parent;

    /** @var Context */
    protected $context;

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
        $rule['event'] = arrayize($rule['event']);

        // for compatible
        if (array_key_exists('javascript', $rule) && !$rule['javascript']) {
            $rule['checkmode']['client'] = false;
        }

        // 文字列指定の Condition をオブジェクト化する
        foreach ($rule['condition'] as $name => $condition) {
            if (!($condition instanceof AbstractCondition)) {
                $rule['condition'][$name] = AbstractCondition::create($name, $condition);
            }
            $rule['condition'][$name]->setCheckMode($rule['checkmode']);
        }

        // 検証メッセージ設定
        /** @var AbstractCondition $condition */
        foreach ($rule['message'] as $key => $message) {
            // 配列ならその要素のみの設定となる
            if (is_array($message)) {
                foreach ($message as $name => $msg) {
                    $condition = $rule['condition'][$key];
                    $condition->setMessageTemplate("$msg", $name);
                }
            }
            // 文字列なら共通で設定
            elseif (is_string($message)) {
                foreach ($rule['condition'] as $condition) {
                    $condition->setMessageTemplate("$message", $key);
                }
            }
        }

        // 属性の正規化
        foreach ($rule['attribute'] as $name => $attribute) {
            if (is_int($name)) {
                throw new \InvalidArgumentException("attribute requires hash array");
            }
            if (is_array($attribute)) {
                $rule['attribute'][$name] = $this->encodeJson($attribute);
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
            if ($rule['autocond'] === true || (is_array($rule['autocond']) && $rule['autocond'][$name])) {
                $this->$method();
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

    public function initialize(Context $root, Context $context)
    {
        foreach ($this->condition as $condition) {
            if ($condition instanceof Condition\Interfaces\Initialize) {
                $condition->initialize($root, $context, $this->parent->name, $this->name);
            }
        }

        foreach ($this->getDependent() as $dependent) {
            if ($dependent[0] === '/') {
                $target = $root->{substr($dependent, 1)};
                $target->rule['propagate'] = array_merge($target->propagate, [$this->parent->name . '/' . $this->name]);
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
        elseif (array_key_exists($this->name, $values)) {
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
            // options が通常配列なら combobox の場合が*多い*
            if (!is_hasharray($this->options)) {
                return 'combobox';
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
        foreach ($this->condition as $condition) {
            if ($condition instanceof Condition\StringLength) {
                return;
            }
        }

        $max = $this->_getMaxlength();

        if ($max === null) {
            return;
        }

        $stringlength = new Condition\StringLength(null, $max);
        $this->rule['condition'][class_shorten($stringlength)] = $stringlength;
    }

    /**
     * options を必要とする要素（select とか radio とか）に InArray 条件を自動で付加する
     *
     * ただし、すでに InArray 条件が登録されていたら何もしない。
     */
    protected function _setAutoInArray()
    {
        if ($this->type === 'combobox') {
            return;
        }

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
     * text 用の maxlength を算出する
     *
     * @return int maxlength
     */
    protected function _getMaxlength()
    {
        $lengths = [];
        foreach ($this->condition as $condition) {
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

        // 指定されているもののうち最も小さい値を返す
        return min($lengths);
    }

    /**
     * text 用の ime-mode を取得する
     *
     * @return string ime-mode
     */
    protected function _getImeMode()
    {
        if (!$this->rule['ime-mode']) {
            return null;
        }

        $modes = [];
        foreach ($this->condition as $condition) {
            if ($condition instanceof Interfaces\ImeMode) {
                $modes[] = $condition->getImeMode();
            }
        }

        // 一つもないなら属性自体を付加しないため null を返す
        if (count($modes) === 0) {
            return null;
        }

        $values = [
            Interfaces\ImeMode::AUTO     => 'auto',
            Interfaces\ImeMode::ACTIVE   => 'active',
            Interfaces\ImeMode::INACTIVE => 'inactive',
            Interfaces\ImeMode::DISABLED => 'disabled'
        ];

        // 最もきつい制限を返す（値に依存してるので変更時は注意）
        return $values[max($modes)];
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
        if ((!$this->multiple && is_array($value)) || ($this->multiple && !is_array($value))) {
            throw new ValidationException(null, sprintf("'%s' invalid type (%s).", $this->name, gettype($value)));
        }

        $fields = $values + $original;
        $this->messages = [];
        $isvalid = true;
        foreach ($this->condition as $cname => $condition) {
            // 空文字、あるいは空配列の時に「空」と定義し、「空」の場合は Requires 以後の検証をしない
            if (!$condition instanceof Condition\Requires && ($value === '' || $value === [])) {
                break;
            }
            $vs = $condition->isArrayableValidation() ? [$value] : arrayize($value);
            $flag = array_all($vs, function ($v) use ($condition, $fields) { return $condition->isValid($v, $fields); });
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
        $name = $this->name;
        $index = '';

        if ($this->parent) {
            $mainname = $this->parent->name;
            $subname = $this->name;

            $index = array_unset($attrs, 'index') ?? '__index';
            $name = "{$mainname}[{$index}][{$subname}]";
            $attrs['for'] = $attrs['for'] ?? $this->id . "$mainname-$index-$subname";
        }

        foreach ($this->_createDataAttrs($index) as $key => $val) {
            $attrs["data-vlabel-$key"] = $val;
        }
        $attrs['for'] = $attrs['for'] ?? $this->id . $name;
        $attrs['class'] = concat($attrs['class'] ?? '', ' ') . 'validatable_label';
        $attr = $this->createHtmlAttr($attrs);
        return "<label $attr>{$this->title}</label>";
    }

    /**
     * UI インプット描画
     *
     * @param array $attrs 属性連想配列
     * @return string 自身の type に基づく inputXXX の結果文字列
     */
    public function input($attrs = [])
    {
        $name = $this->name;
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
            $name = "{$parent_name}[{$index}][{$subname}]";
            $attrs['id'] = $attrs['id'] ?? $this->id . "$parent_name-$index-$subname";
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

        // multiple 属性はその数生成する
        if ($this->multiple) {
            // ただしいくつかの input は html レベルで複数入力に対応しているのでそっちを使う
            if (!in_array($type, ['arrays', 'file', 'checkbox', 'select'])) {
                $attrs['name'] = $name . '[]';
                $vs = strpos($name, '[__index]') === false ? $this->getValue() : $this->default;
                $format = array_unset($attrs, 'format', '%s');
                $result = [];
                foreach ((array) $vs as $n => $v) {
                    $attrs2 = $attrs;
                    $attrs2['id'] = $this->id . $name . '_' . $n;
                    $attrs2['value'] = $v;
                    $result[] = sprintf($format, $this->$method($attrs2));
                }
                return implode('', $result);
            }
        }

        $attrs['name'] = $name;
        $attrs['id'] = $attrs['id'] ?? $this->id . $name;
        return $this->$method($attrs);
    }

    protected function _inputArrays($attrs)
    {
        $attrs['name'] = "__{$this->name}";
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

        $attrs['name'] = $multiple_mode ? $attrs['name'] . '[]' : $attrs['name'];
        $attrs['class'] = concat($attrs['class'] ?? '', ' ') . 'validatable';
        $attrs['type'] = 'checkbox';

        return $hidden . $this->_inputChoice($attrs);
    }

    protected function _inputFile($attrs)
    {
        $attrs['multiple'] = $this->multiple;
        $attrs['name'] = $attrs['multiple'] ? $attrs['name'] . '[]' : $attrs['name'];
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
        $attr = $this->createHtmlAttr($attrs);
        return $this->_wrapInput($wrapper, $attrs['type'], "<input $attr>");
    }

    protected function _inputRadio($attrs)
    {
        $attrs['class'] = concat($attrs['class'] ?? '', ' ') . 'validatable';
        $attrs['type'] = 'radio';

        return $this->_inputChoice($attrs);
    }

    protected function _inputSelect($attrs)
    {
        $hidden = '';
        if ($this->pseudo !== false && $this->multiple) {
            $hidden = $this->_pseudoHidden($attrs['name']);
        }

        $attrs['multiple'] = $this->multiple;
        $attrs['name'] = $attrs['multiple'] ? $attrs['name'] . '[]' : $attrs['name'];
        $attrs['class'] = concat($attrs['class'] ?? '', ' ') . 'validatable';

        $value = (array) array_unset($attrs, 'value', $this->getValue());
        $flipped_value = array_flip($value);

        $options = (array) array_unset($attrs, 'options', $this->options);
        $option_attrs = (array) array_unset($attrs, 'option_attrs', []);

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
        $attr = $this->createHtmlAttr($attrs);
        return $hidden . $this->_wrapInput($wrapper, 'select', "<select $attr>" . implode('', $result) . "</select>");
    }

    protected function _inputCombobox($attrs)
    {
        $wrapper = array_unset($attrs, 'wrapper');

        $attrs['list'] = $attrs['id'] . '-datalist';
        $input = $this->_inputText($attrs);

        $option_attrs = (array) array_unset($attrs, 'option_attrs', []);
        $options = [];
        foreach ((array) array_unset($attrs, 'options', $this->options) as $key => $text) {
            $options[] = $this->_inputOption([], is_int($key) ? $text : $key, $text, $option_attrs);
        }
        $datalist = "<datalist id='{$this->escapeHtml($attrs['list'])}'>" . implode('', $options) . "</datalist>";

        return $this->_wrapInput($wrapper, $attrs['type'], $input . $datalist);
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

        // maxlength がない
        if (!isset($attrs['maxlength'])) {
            $maxlength = $this->_getMaxlength();
            if ($maxlength !== null) {
                $attrs['maxlength'] = $maxlength;
            }
        }

        // ime-mode がない
        $style = $attrs['style'] ?? '';
        if (strpos($style, 'ime-mode') === false) {
            $imemode = $this->_getImeMode();
            if ($imemode !== null) {
                $attrs['style'] = concat($style, ';') . "ime-mode:$imemode;";
            }
        }

        $wrapper = array_unset($attrs, 'wrapper');
        $attr = $this->createHtmlAttr($attrs);
        return $this->_wrapInput($wrapper, $attrs['type'], "<input $attr>");
    }

    protected function _inputTextarea($attrs)
    {
        $attrs['class'] = concat($attrs['class'] ?? '', ' ') . 'validatable';

        $value = array_unset($attrs, 'value', $this->getValue());

        if (!isset($attrs['maxlength'])) {
            $maxlength = $this->_getMaxlength();
            if ($maxlength !== null) {
                $attrs['maxlength'] = $maxlength;
            }
        }

        $wrapper = array_unset($attrs, 'wrapper');
        $attr = $this->createHtmlAttr($attrs);
        return $this->_wrapInput($wrapper, 'textarea', '<textarea ' . $attr . ">\n{$this->escapeHtml($value)}</textarea>");
    }

    protected function _inputChoice($attrs)
    {
        $value = (array) array_unset($attrs, 'value', $this->getValue());
        $flipped_value = array_flip($value);

        $labeled = array_unset($attrs, 'labeled', true);
        $label_attrs = (array) array_unset($attrs, 'label_attrs', []);

        $format = array_unset($attrs, 'format');
        $separator = array_unset($attrs, 'separator');

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

            // for id のペア
            $params['id'] .= '-' . $key;
            $label_attrs['for'] = $params['id'];

            // 属性値を生成（input と label）
            $attr = $this->createHtmlAttr($params);

            // html 生成
            $html = "<input $attr>";
            if ($labeled) {
                $lattrs = $this->createHtmlAttr($label_attrs, $key);
                $html = $html . "<label $lattrs>{$this->escapeHtml($text)}</label>";
            }
            $html = $this->_wrapInput($wrapper, $attrs['type'], $html);

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
        if (isset($value[$key])) {
            $option_attrs['selected'] = 'selected';
        }
        $option_attrs['value'] = $key;
        $oattrs = $this->createHtmlAttr($option_attrs, $key);

        return "<option $oattrs>{$this->escapeHtml($text)}</option>";
    }

    protected function _pseudoHidden($name)
    {
        $hiddenAttr = $this->createHtmlAttr([
            'type'               => 'hidden',
            'name'               => $name,
            'value'              => '',
            'data-vinput-pseudo' => 'true',
        ]);
        return "<input $hiddenAttr>";
    }

    protected function _wrapInput($wrapper, $type, $html)
    {
        if (!strlen($wrapper)) {
            return $html;
        }
        $wrapper = $this->escapeHtml($wrapper);
        $type = $this->escapeHtml($type);
        return "<span class=\"$wrapper input-$type\">$html</span>";
    }

    protected function _createDataAttrs($index)
    {
        $parent_name = $this->parent ? $this->parent->name : '';
        $name = $this->name;

        return [
            'id'    => concat($parent_name, '/') . concat($index, '/') . $name,
            'class' => concat($parent_name, '/') . $name,
            'index' => $index,
        ];
    }
}

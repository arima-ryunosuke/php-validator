<?php
namespace ryunosuke\chmonos;

/**
 * バリデータチェインと UI レンダリングと検証のまとめ上げクラス
 */
class Context implements \IteratorAggregate
{
    /** @var Input[] */
    private $inputs = [];

    /** @var string[][][] */
    private $messages = [];

    /**
     * コンストラクタ
     *
     * @param array $rules ルール配列
     * @param Input|null $parent 親
     * @param string|null $inputClass Input クラス
     */
    public function __construct(array $rules, $parent = null, $inputClass = null)
    {
        $id = 'cx' . spl_object_id($this) . '_';
        $inputClass = $inputClass ?? ($parent !== null ? get_class($parent) : Input::class);

        assert(is_a($inputClass, Input::class, true));

        $flatrules = [];
        foreach ($rules as $name => $rule) {
            if ($rule instanceof \Closure) {
                foreach ($rule() as $name2 => $rule2) {
                    $name2 = is_int($name) ? $name2 : "{$name}$name2";
                    $flatrules[$name2] = $rule2;
                }
            }
            else {
                $flatrules[$name] = $rule;
            }
        }

        foreach ($flatrules as $name => $rule) {
            if ($rule === null) {
                continue;
            }
            $ignore = false;
            if (substr($name, 0, 1) === '@') {
                $ignore = true;
                $name = substr($name, 1);
            }
            assert(!array_key_exists('id', $rule), 'id key is only internal set.');
            assert(!array_key_exists('name', $rule), 'name key is only internal set.');
            $initial = [
                'id'     => $id,
                'name'   => $name,
                'ignore' => $ignore,
            ];
            /** @see Input::__construct() */
            $this->inputs[$name] = new $inputClass($rule + $initial, $parent);
        }
    }

    /**
     * input メンバがあるか調べる
     *
     * @param string $name 要素の名前
     * @return bool
     */
    public function __isset($name)
    {
        if (isset($this->inputs[$name])) {
            return true;
        }

        if (strpos($name, '/') !== false) {
            [$name, $rest] = explode('/', $name, 2);
            return isset($this->inputs[$name]->context->$rest);
        }

        return false;
    }

    /**
     * input メンバへのプロクシ
     *
     * @param string $name 要素の名前
     * @return Input input 要素
     */
    public function __get($name)
    {
        if (isset($this->inputs[$name])) {
            return $this->inputs[$name];
        }

        if (strpos($name, '/') !== false) {
            [$name, $rest] = explode('/', $name, 2);
            return $this->inputs[$name]->context->$rest;
        }

        throw new \InvalidArgumentException("undefined property '{$name}'.");
    }

    /**
     * コンストラクタ内で不可能なことを行う（1パスで無理なものを2パス目で行うイメージ）
     *
     * 例えばコンストラクタ内でネスト要素のコンストラクタが呼ばれる可能性がある。
     * つまりコンストラクタ内で「親子を含めた全要素が出揃っている」という状況を得ることは出来ない。
     *
     * @param Context|null $root 親を辿るためにルート要素を持ち回すが内部用なので呼び出し側は気にしなくていい
     * @return $this
     */
    public function initialize($root = null)
    {
        $root = $root ?? $this;

        foreach ($this->inputs as $input) {
            $input->initialize($root, $this);
        }
        return $this;
    }

    /**
     * 値を正規化して返す
     *
     * 足りないキーを default で埋めたり不要なキーを伏せたりして返す。
     *
     * @param array $values 値の入った連想配列
     * @return array 正規化された $values
     */
    public function normalize($values)
    {
        $values = array_intersect_key($values, $this->inputs);

        // あらかじめ入れておかないと後述の setValue(phantom) で notice が出ることがある
        foreach ($this->inputs as $name => $input) {
            $values[$name] = $input->normalize($values);
        }

        foreach ($this->inputs as $name => $input) {
            $values[$name] = $input->setValue($values[$name]);
        }

        return $values;
    }

    /**
     * バリデート
     *
     * @param array $values 検証する値が入った連想配列
     * @param array|null $original 上位要素検証のため入力元配列を持ち回すが内部用なので呼び出し側は気にしなくていい
     * @return bool エラーがないならtrue
     */
    public function validate(array $values, $original = null)
    {
        if ($original === null) {
            $original = array_map_key($values, function ($k) { return "/$k"; });
        }

        $isvalid = true;

        foreach ($this->inputs as $input) {
            $isvalid = $input->validate($values, $original) && $isvalid;
        }

        return $isvalid && count($this->messages) === 0;
    }

    /**
     * フィルタ
     *
     * @param array $values 値が入った連想配列
     * @param bool $error エラー項目も伏せるか
     * @return array フィルタされた連想配列
     */
    public function filter(array $values, $error = false)
    {
        foreach ($this->inputs as $name => $input) {
            if ($input->ignore) {
                unset($values[$name]);
            }
            foreach ($input->context->inputs ?? [] as $name2 => $input2) {
                if ($input2->ignore) {
                    foreach ($values[$name] as $key => $value) {
                        unset($values[$name][$key][$name2]);
                    }
                }
            }
        }

        if ($error) {
            $messages = $this->getMessages();
            foreach ($messages as $name => $message) {
                if (array_depth($message, 3) > 2) {
                    foreach ($message as $n => $msgs) {
                        foreach ($msgs as $name2 => $msg) {
                            unset($values[$name][$n][$name2]);
                        }
                    }
                    if (isset($n) && !$values[$name][$n]) {
                        unset($values[$name][$n]);
                    }
                }
                else {
                    unset($values[$name]);
                }
            }
        }

        return $values;
    }

    /**
     * 汎用機構を使用しない任意のエラー
     *
     * ログイン認証とかセッション切れとか。
     *
     * @param string $name 要素名
     * @param string $message エラーメッセージ
     */
    public function error($name, $message)
    {
        // 初めてなら作る
        if (!isset($this->messages[$name])) {
            $this->messages[$name] = [];
        }

        // 追加。push([]=)だと数値キーで色々都合がわるいので userxx という名前で作成する
        $length = count($this->messages[$name]);
        $this->messages[$name]['users']["user$length"] = $message;
    }

    /**
     * バリデーション結果をクリアする
     */
    public function clear()
    {
        $this->messages = [];
        foreach ($this->inputs as $input) {
            $input->clear();
            if ($input->context) {
                $input->context->clear();
            }
        }
    }

    /**
     * 各要素のデフォルト値を返す
     *
     * @return array デフォルト値
     */
    public function getDefaults()
    {
        $defaults = [];
        foreach ($this->inputs as $name => $input) {
            if (isset($input->context)) {
                $defaults[$name] = $input->context->getDefaults();
            }
            else {
                $defaults[$name] = $input->default;
            }
        }
        return $defaults;
    }

    /**
     * 値を返す
     *
     * @return array 値
     */
    public function getValues()
    {
        $values = [];
        foreach ($this->inputs as $name => $input) {
            $values[$name] = $input->getValue();
        }
        return $values;
    }

    /**
     * 検証メッセージを array で返す
     *
     * @return array 検証メッセージ
     */
    public function getMessages()
    {
        $messages = [];
        foreach ($this->inputs as $name => $input) {
            $message = $input->getMessages();
            if (count($message) > 0) {
                $messages[$name] = $message;
            }
        }

        return array_merge_recursive($messages, $this->messages);
    }

    /**
     * エラーメッセージキーなどを含めず、フラットなメッセージ一覧を返す
     *
     * @param string $format 表示フォーマット。タイトルとメッセージが与えられる
     * @param string $childformat 配列用表示フォーマット。タイトルと行数とサブタイトルが与えられる
     * @return array エラーメッセージ配列
     */
    public function getFlatMessages($format = '[%s] %s', $childformat = '%s %d行目 - %s')
    {
        $result = [];
        foreach ($this->inputs as $input) {
            foreach ($input->getMessages() as $n => $messages) {
                foreach ($messages as $key => $message) {
                    if (is_array($message)) {
                        foreach ($message as $msgs) {
                            $ctitle = $input->context->inputs[$key]->title;
                            foreach ($msgs as $m) {
                                $title = sprintf($childformat, $input->title, $n + 1, $ctitle);
                                $result[] = sprintf($format, $title, $m);
                            }
                        }
                    }
                    else {
                        $result[] = sprintf($format, $input->title, $message);
                    }
                }
            }
        }
        return $result;
    }

    /**
     * ルール（入力規則とか伝播先とかメッセージとかをまとめたもの）を返す
     *
     * @return array 入力検証ルール配列
     */
    public function getRules()
    {
        $rules = [];
        foreach ($this->inputs as $name => $input) {
            $rules[$name] = $input->getValidationRule();

            if ($input->getType() === 'arrays') {
                foreach ($input->context->getRules() as $n => $r) {
                    $rules["$name/$n"] = $r;
                }
            }
        }
        return $rules;
    }

    /**
     * File を持っているか返す
     *
     * @return bool 持っているならtrue
     */
    public function hasInputFile()
    {
        foreach ($this->inputs as $input) {
            // 子要素が持ってたら true
            if ($input->getType() === 'file') {
                return true;
            }

            // 子要素の子要素が持ってたら true
            if ($input->getType() === 'arrays') {
                if ($input->context->hasInputFile()) {
                    return true;
                }
            }
        }
        return false;
    }

    public function getFixture($defaults = [])
    {
        $scores = [];
        $score = function ($name) use (&$score, &$scores) {
            if (!isset($this->inputs[$name])) {
                return 0;
            }
            if (isset($scores[$name])) {
                return $scores[$name];
            }
            if ($this->inputs[$name]->getType() === 'arrays') {
                return $scores[$name] = 9999999;
            }
            $scores[$name] = 0;
            return $scores[$name] = array_sum(array_map(fn($name) => $score($name), $this->inputs[$name]->getDependent())) + 1;
        };
        $inputs = kvsort($this->inputs, fn($a, $b, $ak, $bk) => $score($ak) <=> $score($bk));

        $fixtures = [];
        foreach ($inputs as $name => $input) {
            $fixtures[$name] = $input->fixture($defaults[$name] ?? null, $fixtures);
        }
        foreach ($inputs as $name => $input) {
            $fixtures[$name] = $input->normalize($fixtures);
        }
        return $fixtures;
    }

    /**
     * ネスト要素も含めて全ての Input を返す
     *
     * @return Input[] 全ての Input
     */
    public function getAllInput()
    {
        $inputs = [];
        foreach ($this->inputs as $name => $input) {
            $inputs[$name] = $input;

            if ($input->getType() === 'arrays') {
                foreach ($input->context->getAllInput() as $name2 => $input2) {
                    $inputs["$name/$name2"] = $input2;
                }
            }
        }
        return $inputs;
    }

    /**
     * @return \Generator|\Traversable|Input[]
     */
    public function getIterator(): \Generator
    {
        foreach ($this->inputs as $name => $input) {
            yield $name => $input;
        }
    }
}

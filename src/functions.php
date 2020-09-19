<?php

# Don't touch this code. This is auto generated.

namespace ryunosuke\chmonos;

# constants
if (!defined("ryunosuke\\chmonos\\IS_OWNSELF")) {
    /** 自分自身を表す定数 */
    define("ryunosuke\\chmonos\\IS_OWNSELF", 128);
}

if (!defined("ryunosuke\\chmonos\\IS_PUBLIC")) {
    /** public を表す定数 @see \ReflectionProperty::IS_PUBLIC */
    define("ryunosuke\\chmonos\\IS_PUBLIC", 256);
}

if (!defined("ryunosuke\\chmonos\\IS_PROTECTED")) {
    /** protected を表す定数 @see \ReflectionProperty::IS_PROTECTED */
    define("ryunosuke\\chmonos\\IS_PROTECTED", 512);
}

if (!defined("ryunosuke\\chmonos\\IS_PRIVATE")) {
    /** private を表す定数 @see \ReflectionProperty::IS_PRIVATE */
    define("ryunosuke\\chmonos\\IS_PRIVATE", 1024);
}

if (!defined("ryunosuke\\chmonos\\TOKEN_NAME")) {
    /** parse_php 関数でトークン名変換をするか */
    define("ryunosuke\\chmonos\\TOKEN_NAME", 2);
}

if (!defined("ryunosuke\\chmonos\\SI_UNITS")) {
    /** SI 接頭辞 */
    define("ryunosuke\\chmonos\\SI_UNITS", [
        -8 => ["y"],
        -7 => ["z"],
        -6 => ["a"],
        -5 => ["f"],
        -4 => ["p"],
        -3 => ["n"],
        -2 => ["u", "μ", "µ"],
        -1 => ["m"],
        0  => [],
        1  => ["k", "K"],
        2  => ["M"],
        3  => ["G"],
        4  => ["T"],
        5  => ["P"],
        6  => ["E"],
        7  => ["Z"],
        8  => ["Y"],
    ]);
}


# functions
if (!isset($excluded_functions["arrayize"]) && (!function_exists("ryunosuke\\chmonos\\arrayize") || (!false && (new \ReflectionFunction("ryunosuke\\chmonos\\arrayize"))->isInternal()))) {
    /**
     * 引数の配列を生成する。
     *
     * 配列以外を渡すと配列化されて追加される。
     * 連想配列は未対応。あくまで普通の配列化のみ。
     * iterable や Traversable は考慮せずあくまで「配列」としてチェックする。
     *
     * Example:
     * ```php
     * that(arrayize(1, 2, 3))->isSame([1, 2, 3]);
     * that(arrayize([1], [2], [3]))->isSame([1, 2, 3]);
     * $object = new \stdClass();
     * that(arrayize($object, false, [1, 2, 3]))->isSame([$object, false, 1, 2, 3]);
     * ```
     *
     * @param mixed $variadic 生成する要素（可変引数）
     * @return array 引数を配列化したもの
     */
    function arrayize(...$variadic)
    {
        $result = [];
        foreach ($variadic as $arg) {
            if (!is_array($arg)) {
                $result[] = $arg;
            }
            elseif (!is_hasharray($arg)) {
                $result = array_merge($result, $arg);
            }
            else {
                $result += $arg;
            }
        }
        return $result;
    }
}
if (function_exists("ryunosuke\\chmonos\\arrayize") && !defined("ryunosuke\\chmonos\\arrayize")) {
    define("ryunosuke\\chmonos\\arrayize", "ryunosuke\\chmonos\\arrayize");
}

if (!isset($excluded_functions["is_indexarray"]) && (!function_exists("ryunosuke\\chmonos\\is_indexarray") || (!false && (new \ReflectionFunction("ryunosuke\\chmonos\\is_indexarray"))->isInternal()))) {
    /**
     * 配列が数値配列か調べる
     *
     * 空の配列も数値配列とみなす。
     * さらにいわゆる「連番配列」ではなく「キーが数値のみか？」で判定する。
     *
     * つまり、 is_hasharray とは排他的ではない。
     *
     * Example:
     * ```php
     * that(is_indexarray([]))->isTrue();
     * that(is_indexarray([1, 2, 3]))->isTrue();
     * that(is_indexarray(['x' => 'X']))->isFalse();
     * // 抜け番があっても true になる（これは is_hasharray も true になる）
     * that(is_indexarray([1 => 1, 2 => 2, 3 => 3]))->isTrue();
     * ```
     *
     * @param array $array 調べる配列
     * @return bool 数値配列なら true
     */
    function is_indexarray($array)
    {
        foreach ($array as $k => $dummy) {
            if (!is_int($k)) {
                return false;
            }
        }
        return true;
    }
}
if (function_exists("ryunosuke\\chmonos\\is_indexarray") && !defined("ryunosuke\\chmonos\\is_indexarray")) {
    define("ryunosuke\\chmonos\\is_indexarray", "ryunosuke\\chmonos\\is_indexarray");
}

if (!isset($excluded_functions["is_hasharray"]) && (!function_exists("ryunosuke\\chmonos\\is_hasharray") || (!false && (new \ReflectionFunction("ryunosuke\\chmonos\\is_hasharray"))->isInternal()))) {
    /**
     * 配列が連想配列か調べる
     *
     * 空の配列は普通の配列とみなす。
     *
     * Example:
     * ```php
     * that(is_hasharray([]))->isFalse();
     * that(is_hasharray([1, 2, 3]))->isFalse();
     * that(is_hasharray(['x' => 'X']))->isTrue();
     * ```
     *
     * @param array $array 調べる配列
     * @return bool 連想配列なら true
     */
    function is_hasharray(array $array)
    {
        $i = 0;
        foreach ($array as $k => $dummy) {
            if ($k !== $i++) {
                return true;
            }
        }
        return false;
    }
}
if (function_exists("ryunosuke\\chmonos\\is_hasharray") && !defined("ryunosuke\\chmonos\\is_hasharray")) {
    define("ryunosuke\\chmonos\\is_hasharray", "ryunosuke\\chmonos\\is_hasharray");
}

if (!isset($excluded_functions["first_value"]) && (!function_exists("ryunosuke\\chmonos\\first_value") || (!false && (new \ReflectionFunction("ryunosuke\\chmonos\\first_value"))->isInternal()))) {
    /**
     * 配列の最初の値を返す
     *
     * 空の場合は $default を返す。
     *
     * Example:
     * ```php
     * that(first_value(['a', 'b', 'c']))->isSame('a');
     * that(first_value([], 999))->isSame(999);
     * ```
     *
     * @param iterable $array 対象配列
     * @param mixed $default 無かった場合のデフォルト値
     * @return mixed 最初の値
     */
    function first_value($array, $default = null)
    {
        if (is_empty($array)) {
            return $default;
        }
        /** @noinspection PhpUnusedLocalVariableInspection */
        [$k, $v] = first_keyvalue($array);
        return $v;
    }
}
if (function_exists("ryunosuke\\chmonos\\first_value") && !defined("ryunosuke\\chmonos\\first_value")) {
    define("ryunosuke\\chmonos\\first_value", "ryunosuke\\chmonos\\first_value");
}

if (!isset($excluded_functions["first_keyvalue"]) && (!function_exists("ryunosuke\\chmonos\\first_keyvalue") || (!false && (new \ReflectionFunction("ryunosuke\\chmonos\\first_keyvalue"))->isInternal()))) {
    /**
     * 配列の最初のキー/値ペアをタプルで返す
     *
     * 空の場合は $default を返す。
     *
     * Example:
     * ```php
     * that(first_keyvalue(['a', 'b', 'c']))->isSame([0, 'a']);
     * that(first_keyvalue([], 999))->isSame(999);
     * ```
     *
     * @param iterable $array 対象配列
     * @param mixed $default 無かった場合のデフォルト値
     * @return array [最初のキー, 最初の値]
     */
    function first_keyvalue($array, $default = null)
    {
        foreach ($array as $k => $v) {
            return [$k, $v];
        }
        return $default;
    }
}
if (function_exists("ryunosuke\\chmonos\\first_keyvalue") && !defined("ryunosuke\\chmonos\\first_keyvalue")) {
    define("ryunosuke\\chmonos\\first_keyvalue", "ryunosuke\\chmonos\\first_keyvalue");
}

if (!isset($excluded_functions["last_key"]) && (!function_exists("ryunosuke\\chmonos\\last_key") || (!false && (new \ReflectionFunction("ryunosuke\\chmonos\\last_key"))->isInternal()))) {
    /**
     * 配列の最後のキーを返す
     *
     * 空の場合は $default を返す。
     *
     * Example:
     * ```php
     * that(last_key(['a', 'b', 'c']))->isSame(2);
     * that(last_key([], 999))->isSame(999);
     * ```
     *
     * @param iterable $array 対象配列
     * @param mixed $default 無かった場合のデフォルト値
     * @return mixed 最後のキー
     */
    function last_key($array, $default = null)
    {
        if (is_empty($array)) {
            return $default;
        }
        /** @noinspection PhpUnusedLocalVariableInspection */
        [$k, $v] = last_keyvalue($array);
        return $k;
    }
}
if (function_exists("ryunosuke\\chmonos\\last_key") && !defined("ryunosuke\\chmonos\\last_key")) {
    define("ryunosuke\\chmonos\\last_key", "ryunosuke\\chmonos\\last_key");
}

if (!isset($excluded_functions["last_keyvalue"]) && (!function_exists("ryunosuke\\chmonos\\last_keyvalue") || (!false && (new \ReflectionFunction("ryunosuke\\chmonos\\last_keyvalue"))->isInternal()))) {
    /**
     * 配列の最後のキー/値ペアをタプルで返す
     *
     * 空の場合は $default を返す。
     *
     * Example:
     * ```php
     * that(last_keyvalue(['a', 'b', 'c']))->isSame([2, 'c']);
     * that(last_keyvalue([], 999))->isSame(999);
     * ```
     *
     * @param iterable $array 対象配列
     * @param mixed $default 無かった場合のデフォルト値
     * @return array [最後のキー, 最後の値]
     */
    function last_keyvalue($array, $default = null)
    {
        if (is_empty($array)) {
            return $default;
        }
        if (is_array($array)) {
            $v = end($array);
            $k = key($array);
            return [$k, $v];
        }
        /** @noinspection PhpStatementHasEmptyBodyInspection */
        foreach ($array as $k => $v) {
            // dummy
        }
        // $k がセットされてるなら「ループが最低でも1度回った（≠空）」とみなせる
        if (isset($k)) {
            /** @noinspection PhpUndefinedVariableInspection */
            return [$k, $v];
        }
        return $default;
    }
}
if (function_exists("ryunosuke\\chmonos\\last_keyvalue") && !defined("ryunosuke\\chmonos\\last_keyvalue")) {
    define("ryunosuke\\chmonos\\last_keyvalue", "ryunosuke\\chmonos\\last_keyvalue");
}

if (!isset($excluded_functions["array_sprintf"]) && (!function_exists("ryunosuke\\chmonos\\array_sprintf") || (!false && (new \ReflectionFunction("ryunosuke\\chmonos\\array_sprintf"))->isInternal()))) {
    /**
     * キーと値で sprintf する
     *
     * 配列の各要素を文字列化して返すイメージ。
     * $glue を与えるとさらに implode して返す（返り値が文字列になる）。
     *
     * $format は書式文字列（$v, $k）。
     * callable を与えると sprintf ではなくコールバック処理になる（$v, $k）。
     * 省略（null）するとキーを format 文字列、値を引数として **vsprintf** する。
     *
     * Example:
     * ```php
     * $array = ['key1' => 'val1', 'key2' => 'val2'];
     * // key, value を利用した sprintf
     * that(array_sprintf($array, '%2$s=%1$s'))->isSame(['key1=val1', 'key2=val2']);
     * // 第3引数を与えるとさらに implode される
     * that(array_sprintf($array, '%2$s=%1$s', ' '))->isSame('key1=val1 key2=val2');
     * // クロージャを与えるとコールバック動作になる
     * $closure = function($v, $k){return "$k=" . strtoupper($v);};
     * that(array_sprintf($array, $closure, ' '))->isSame('key1=VAL1 key2=VAL2');
     * // 省略すると vsprintf になる
     * that(array_sprintf([
     *     'str:%s,int:%d' => ['sss', '3.14'],
     *     'single:%s'     => 'str',
     * ], null, '|'))->isSame('str:sss,int:3|single:str');
     * ```
     *
     * @param iterable $array 対象配列
     * @param string|callable $format 書式文字列あるいはクロージャ
     * @param string $glue 結合文字列。未指定時は implode しない
     * @return array|string sprintf された配列
     */
    function array_sprintf($array, $format = null, $glue = null)
    {
        if (is_callable($format)) {
            $callback = func_user_func_array($format);
        }
        elseif ($format === null) {
            $callback = function ($v, $k) { return vsprintf($k, is_array($v) ? $v : [$v]); };
        }
        else {
            $callback = function ($v, $k) use ($format) { return sprintf($format, $v, $k); };
        }

        $result = [];
        foreach ($array as $k => $v) {
            $result[] = $callback($v, $k);
        }

        if ($glue !== null) {
            return implode($glue, $result);
        }

        return $result;
    }
}
if (function_exists("ryunosuke\\chmonos\\array_sprintf") && !defined("ryunosuke\\chmonos\\array_sprintf")) {
    define("ryunosuke\\chmonos\\array_sprintf", "ryunosuke\\chmonos\\array_sprintf");
}

if (!isset($excluded_functions["array_get"]) && (!function_exists("ryunosuke\\chmonos\\array_get") || (!false && (new \ReflectionFunction("ryunosuke\\chmonos\\array_get"))->isInternal()))) {
    /**
     * デフォルト値付きの配列値取得
     *
     * 存在しない場合は $default を返す。
     *
     * $key に配列を与えるとそれらの値の配列を返す（lookup 的な動作）。
     * その場合、$default が活きるのは「全て無かった場合」となる。
     *
     * さらに $key が配列の場合に限り、 $default を省略すると空配列として動作する。
     *
     * 同様に、$key にクロージャを与えると、その返り値が true 相当のものを返す。
     * その際、 $default が配列なら一致するものを配列で返し、配列でないなら単値で返す。
     *
     * Example:
     * ```php
     * // 単純取得
     * that(array_get(['a', 'b', 'c'], 1))->isSame('b');
     * // 単純デフォルト
     * that(array_get(['a', 'b', 'c'], 9, 999))->isSame(999);
     * // 配列取得
     * that(array_get(['a', 'b', 'c'], [0, 2]))->isSame([0 => 'a', 2 => 'c']);
     * // 配列部分取得
     * that(array_get(['a', 'b', 'c'], [0, 9]))->isSame([0 => 'a']);
     * // 配列デフォルト（null ではなく [] を返す）
     * that(array_get(['a', 'b', 'c'], [9]))->isSame([]);
     * // クロージャ指定＆単値（コールバックが true を返す最初の要素）
     * that(array_get(['a', 'b', 'c'], function($v){return in_array($v, ['b', 'c']);}))->isSame('b');
     * // クロージャ指定＆配列（コールバックが true を返すもの）
     * that(array_get(['a', 'b', 'c'], function($v){return in_array($v, ['b', 'c']);}, []))->isSame([1 => 'b', 2 => 'c']);
     * ```
     *
     * @param array $array 配列
     * @param string|int|array $key 取得したいキー。配列を与えると全て返す。クロージャの場合は true 相当を返す
     * @param mixed $default 無かった場合のデフォルト値
     * @return mixed 指定したキーの値
     */
    function array_get($array, $key, $default = null)
    {
        if (is_array($key)) {
            $result = [];
            foreach ($key as $k) {
                // 深遠な事情で少しでも高速化したかったので isset || array_keys_exist にしてある
                if (isset($array[$k]) || array_keys_exist($k, $array)) {
                    $result[$k] = $array[$k];
                }
            }
            if (!$result) {
                // 明示的に与えられていないなら [] を使用する
                if (func_num_args() === 2) {
                    $default = [];
                }
                return $default;
            }
            return $result;
        }

        if ($key instanceof \Closure) {
            $result = [];
            foreach ($array as $k => $v) {
                if ($key($v, $k)) {
                    if (func_num_args() === 2) {
                        return $v;
                    }
                    $result[$k] = $v;
                }
            }
            if (!$result) {
                return $default;
            }
            return $result;
        }

        if (array_keys_exist($key, $array)) {
            return $array[$key];
        }
        return $default;
    }
}
if (function_exists("ryunosuke\\chmonos\\array_get") && !defined("ryunosuke\\chmonos\\array_get")) {
    define("ryunosuke\\chmonos\\array_get", "ryunosuke\\chmonos\\array_get");
}

if (!isset($excluded_functions["array_unset"]) && (!function_exists("ryunosuke\\chmonos\\array_unset") || (!false && (new \ReflectionFunction("ryunosuke\\chmonos\\array_unset"))->isInternal()))) {
    /**
     * 伏せると同時にその値を返す
     *
     * $key に配列を与えると全て伏せて配列で返す。
     * その場合、$default が活きるのは「全て無かった場合」となる。
     *
     * さらに $key が配列の場合に限り、 $default を省略すると空配列として動作する。
     *
     * 配列を与えた場合の返り値は与えた配列の順番・キーが活きる。
     * これを利用すると list の展開の利便性が上がったり、連想配列で返すことができる。
     *
     * 同様に、$key にクロージャを与えると、その返り値が true 相当のものを伏せて配列で返す。
     * callable ではなくクロージャのみ対応する。
     *
     * Example:
     * ```php
     * $array = ['a' => 'A', 'b' => 'B'];
     * // ない場合は $default を返す
     * that(array_unset($array, 'x', 'X'))->isSame('X');
     * // 指定したキーを返す。そのキーは伏せられている
     * that(array_unset($array, 'a'))->isSame('A');
     * that($array)->isSame(['b' => 'B']);
     *
     * $array = ['a' => 'A', 'b' => 'B', 'c' => 'C'];
     * // 配列を与えるとそれらを返す。そのキーは全て伏せられている
     * that(array_unset($array, ['a', 'b', 'x']))->isSame(['A', 'B']);
     * that($array)->isSame(['c' => 'C']);
     *
     * $array = ['a' => 'A', 'b' => 'B', 'c' => 'C'];
     * // 配列のキーは返されるキーを表す。順番も維持される
     * that(array_unset($array, ['x2' => 'b', 'x1' => 'a']))->isSame(['x2' => 'B', 'x1' => 'A']);
     *
     * $array = ['hoge' => 'HOGE', 'fuga' => 'FUGA', 'piyo' => 'PIYO'];
     * // 値に "G" を含むものを返す。その要素は伏せられている
     * that(array_unset($array, function($v){return strpos($v, 'G') !== false;}))->isSame(['hoge' => 'HOGE', 'fuga' => 'FUGA']);
     * that($array)->isSame(['piyo' => 'PIYO']);
     * ```
     *
     * @param array $array 配列
     * @param string|int|array|callable $key 伏せたいキー。配列を与えると全て伏せる。クロージャの場合は true 相当を伏せる
     * @param mixed $default 無かった場合のデフォルト値
     * @return mixed 指定したキーの値
     */
    function array_unset(&$array, $key, $default = null)
    {
        if (is_array($key)) {
            $result = [];
            foreach ($key as $rk => $ak) {
                if (array_keys_exist($ak, $array)) {
                    $result[$rk] = $array[$ak];
                    unset($array[$ak]);
                }
            }
            if (!$result) {
                // 明示的に与えられていないなら [] を使用する
                if (func_num_args() === 2) {
                    $default = [];
                }
                return $default;
            }
            return $result;
        }

        if ($key instanceof \Closure) {
            $result = [];
            foreach ($array as $k => $v) {
                if ($key($v, $k)) {
                    $result[$k] = $v;
                    unset($array[$k]);
                }
            }
            if (!$result) {
                return $default;
            }
            return $result;
        }

        if (array_keys_exist($key, $array)) {
            $result = $array[$key];
            unset($array[$key]);
            return $result;
        }
        return $default;
    }
}
if (function_exists("ryunosuke\\chmonos\\array_unset") && !defined("ryunosuke\\chmonos\\array_unset")) {
    define("ryunosuke\\chmonos\\array_unset", "ryunosuke\\chmonos\\array_unset");
}

if (!isset($excluded_functions["array_dive"]) && (!function_exists("ryunosuke\\chmonos\\array_dive") || (!false && (new \ReflectionFunction("ryunosuke\\chmonos\\array_dive"))->isInternal()))) {
    /**
     * パス形式で配列値を取得
     *
     * 存在しない場合は $default を返す。
     *
     * Example:
     * ```php
     * $array = [
     *     'a' => [
     *         'b' => [
     *             'c' => 'vvv'
     *         ]
     *     ]
     * ];
     * that(array_dive($array, 'a.b.c'))->isSame('vvv');
     * that(array_dive($array, 'a.b.x', 9))->isSame(9);
     * // 配列を与えても良い。その場合 $delimiter 引数は意味をなさない
     * that(array_dive($array, ['a', 'b', 'c']))->isSame('vvv');
     * ```
     *
     * @param array $array 調べる配列
     * @param string|array $path パス文字列。配列も与えられる
     * @param mixed $default 無かった場合のデフォルト値
     * @param string $delimiter パスの区切り文字。大抵は '.' か '/'
     * @return mixed パスが示す配列の値
     */
    function array_dive($array, $path, $default = null, $delimiter = '.')
    {
        $keys = is_array($path) ? $path : explode($delimiter, $path);
        foreach ($keys as $key) {
            if (!is_arrayable($array)) {
                return $default;
            }
            if (!array_keys_exist($key, $array)) {
                return $default;
            }
            $array = $array[$key];
        }
        return $array;
    }
}
if (function_exists("ryunosuke\\chmonos\\array_dive") && !defined("ryunosuke\\chmonos\\array_dive")) {
    define("ryunosuke\\chmonos\\array_dive", "ryunosuke\\chmonos\\array_dive");
}

if (!isset($excluded_functions["array_keys_exist"]) && (!function_exists("ryunosuke\\chmonos\\array_keys_exist") || (!false && (new \ReflectionFunction("ryunosuke\\chmonos\\array_keys_exist"))->isInternal()))) {
    /**
     * array_key_exists の複数版
     *
     * 指定キーが全て存在するなら true を返す。
     * 配列ではなく単一文字列を与えても動作する（array_key_exists と全く同じ動作になる）。
     *
     * $keys に空を与えると例外を投げる。
     * $keys に配列を与えるとキーで潜ってチェックする（Example 参照）。
     *
     * Example:
     * ```php
     * // すべて含むので true
     * that(array_keys_exist(['a', 'b', 'c'], ['a' => 'A', 'b' => 'B', 'c' => 'C']))->isTrue();
     * // N は含まないので false
     * that(array_keys_exist(['a', 'b', 'N'], ['a' => 'A', 'b' => 'B', 'c' => 'C']))->isFalse();
     * // 配列を与えると潜る（日本語で言えば「a というキーと、x というキーとその中に x1, x2 というキーがあるか？」）
     * that(array_keys_exist(['a', 'x' => ['x1', 'x2']], ['a' => 'A', 'x' => ['x1' => 'X1', 'x2' => 'X2']]))->isTrue();
     * ```
     *
     * @param array|string $keys 調べるキー
     * @param array|\ArrayAccess $array 調べる配列
     * @return bool 指定キーが全て存在するなら true
     */
    function array_keys_exist($keys, $array)
    {
        $keys = is_iterable($keys) ? $keys : [$keys];
        if (is_empty($keys)) {
            throw new \InvalidArgumentException('$keys is empty.');
        }

        $is_arrayaccess = $array instanceof \ArrayAccess;

        foreach ($keys as $k => $key) {
            if (is_array($key)) {
                // まずそのキーをチェックして
                if (!array_keys_exist($k, $array)) {
                    return false;
                }
                // あるなら再帰する
                if (!array_keys_exist($key, $array[$k])) {
                    return false;
                }
            }
            elseif ($is_arrayaccess) {
                if (!$array->offsetExists($key)) {
                    return false;
                }
            }
            elseif (!array_key_exists($key, $array)) {
                return false;
            }
        }
        return true;
    }
}
if (function_exists("ryunosuke\\chmonos\\array_keys_exist") && !defined("ryunosuke\\chmonos\\array_keys_exist")) {
    define("ryunosuke\\chmonos\\array_keys_exist", "ryunosuke\\chmonos\\array_keys_exist");
}

if (!isset($excluded_functions["array_map_key"]) && (!function_exists("ryunosuke\\chmonos\\array_map_key") || (!false && (new \ReflectionFunction("ryunosuke\\chmonos\\array_map_key"))->isInternal()))) {
    /**
     * キーをマップして変換する
     *
     * $callback が null を返すとその要素は取り除かれる。
     *
     * Example:
     * ```php
     * that(array_map_key(['a' => 'A', 'b' => 'B'], 'strtoupper'))->isSame(['A' => 'A', 'B' => 'B']);
     * that(array_map_key(['a' => 'A', 'b' => 'B'], function(){}))->isSame([]);
     * ```
     *
     * @param iterable $array 対象配列
     * @param callable $callback 評価クロージャ
     * @return array キーが変換された新しい配列
     */
    function array_map_key($array, $callback)
    {
        $callback = func_user_func_array($callback);
        $result = [];
        foreach ($array as $k => $v) {
            $k2 = $callback($k, $v);
            if ($k2 !== null) {
                $result[$k2] = $v;
            }
        }
        return $result;
    }
}
if (function_exists("ryunosuke\\chmonos\\array_map_key") && !defined("ryunosuke\\chmonos\\array_map_key")) {
    define("ryunosuke\\chmonos\\array_map_key", "ryunosuke\\chmonos\\array_map_key");
}

if (!isset($excluded_functions["array_map_method"]) && (!function_exists("ryunosuke\\chmonos\\array_map_method") || (!false && (new \ReflectionFunction("ryunosuke\\chmonos\\array_map_method"))->isInternal()))) {
    /**
     * メソッドを指定できるようにした array_map
     *
     * 配列内の要素は全て同一（少なくともシグネチャが同じ $method が存在する）オブジェクトでなければならない。
     * スルーする場合は $ignore=true とする。スルーした場合 map ではなく filter される（結果配列に含まれない）。
     * $ignore=null とすると 何もせずそのまま要素を返す。
     *
     * Example:
     * ```php
     * $exa = new \Exception('a');
     * $exb = new \Exception('b');
     * $std = new \stdClass();
     * // getMessage で map される
     * that(array_map_method([$exa, $exb], 'getMessage'))->isSame(['a', 'b']);
     * // getMessage で map されるが、メソッドが存在しない場合は取り除かれる
     * that(array_map_method([$exa, $exb, $std, null], 'getMessage', [], true))->isSame(['a', 'b']);
     * // getMessage で map されるが、メソッドが存在しない場合はそのまま返す
     * that(array_map_method([$exa, $exb, $std, null], 'getMessage', [], null))->isSame(['a', 'b', $std, null]);
     * ```
     *
     * @param iterable $array 対象配列
     * @param string $method メソッド
     * @param array $args メソッドに渡る引数
     * @param bool|null $ignore メソッドが存在しない場合にスルーするか。null を渡すと要素そのものを返す
     * @return array $method が true を返した新しい配列
     */
    function array_map_method($array, $method, $args = [], $ignore = false)
    {
        if ($ignore === true) {
            $array = array_filter(arrayval($array, false), function ($object) use ($method) {
                return is_callable([$object, $method]);
            });
        }
        return array_map(function ($object) use ($method, $args, $ignore) {
            if ($ignore === null && !is_callable([$object, $method])) {
                return $object;
            }
            return ([$object, $method])(...$args);
        }, arrayval($array, false));
    }
}
if (function_exists("ryunosuke\\chmonos\\array_map_method") && !defined("ryunosuke\\chmonos\\array_map_method")) {
    define("ryunosuke\\chmonos\\array_map_method", "ryunosuke\\chmonos\\array_map_method");
}

if (!isset($excluded_functions["array_each"]) && (!function_exists("ryunosuke\\chmonos\\array_each") || (!false && (new \ReflectionFunction("ryunosuke\\chmonos\\array_each"))->isInternal()))) {
    /**
     * array_reduce の参照版（のようなもの）
     *
     * 配列をループで回し、その途中経過、値、キー、連番をコールバック引数で渡して最終的な結果を返り値として返す。
     * array_reduce と少し似てるが、下記の点が異なる。
     *
     * - いわゆる $carry は返り値で表すのではなく、参照引数で表す
     * - 値だけでなくキー、連番も渡ってくる
     * - 巨大配列の場合でも速度劣化が少ない（array_reduce に巨大配列を渡すと実用にならないレベルで遅くなる）
     *
     * $callback の引数は `($value, $key, $n)` （$n はキーとは関係がない 0 ～ 要素数-1 の通し連番）。
     *
     * 返り値ではなく参照引数なので return する必要はない（ワンライナーが書きやすくなる）。
     * 返り値が空くのでループ制御に用いる。
     * 今のところ $callback が false を返すとそこで break するのみ。
     *
     * 第3引数を省略した場合、**クロージャの第1引数のデフォルト値が使われる**。
     * これは特筆すべき動作で、不格好な第3引数を完全に省略することができる（サンプルコードを参照）。
     * ただし「php の文法違反（今のところエラーにはならないし、全てにデフォルト値をつければ一応回避可能）」「リフレクションを使う（ほんの少し遅くなる）」などの弊害が有るので推奨はしない。
     * （ただ、「意図していることをコードで表す」といった観点ではこの記法の方が正しいとも思う）。
     *
     * Example:
     * ```php
     * // 全要素を文字列的に足し合わせる
     * that(array_each([1, 2, 3, 4, 5], function(&$carry, $v){$carry .= $v;}, ''))->isSame('12345');
     * // 値をキーにして要素を2乗値にする
     * that(array_each([1, 2, 3, 4, 5], function(&$carry, $v){$carry[$v] = $v * $v;}, []))->isSame([
     *     1 => 1,
     *     2 => 4,
     *     3 => 9,
     *     4 => 16,
     *     5 => 25,
     * ]);
     * // 上記と同じ。ただし、3 で break する
     * that(array_each([1, 2, 3, 4, 5], function(&$carry, $v, $k){
     *     if ($k === 3) return false;
     *     $carry[$v] = $v * $v;
     * }, []))->isSame([
     *     1 => 1,
     *     2 => 4,
     *     3 => 9,
     * ]);
     *
     * // 下記は完全に同じ（第3引数の代わりにデフォルト引数を使っている）
     * that(array_each([1, 2, 3], function(&$carry = [], $v) {
     *         $carry[$v] = $v * $v;
     *     }))->isSame(array_each([1, 2, 3], function(&$carry, $v) {
     *         $carry[$v] = $v * $v;
     *     }, [])
     *     // 個人的に↑のようなぶら下がり引数があまり好きではない（クロージャを最後の引数にしたい）
     * );
     * ```
     *
     * @param iterable $array 対象配列
     * @param callable $callback 評価クロージャ。(&$carry, $key, $value) を受ける
     * @param mixed $default ループの最初や空の場合に適用される値
     * @return mixed each した結果
     */
    function array_each($array, $callback, $default = null)
    {
        if (func_num_args() === 2) {
            /** @var \ReflectionFunction $ref */
            $ref = reflect_callable($callback);
            $params = $ref->getParameters();
            if ($params[0]->isDefaultValueAvailable()) {
                $default = $params[0]->getDefaultValue();
            }
        }

        $n = 0;
        foreach ($array as $k => $v) {
            $return = $callback($default, $v, $k, $n++);
            if ($return === false) {
                break;
            }
        }
        return $default;
    }
}
if (function_exists("ryunosuke\\chmonos\\array_each") && !defined("ryunosuke\\chmonos\\array_each")) {
    define("ryunosuke\\chmonos\\array_each", "ryunosuke\\chmonos\\array_each");
}

if (!isset($excluded_functions["array_depth"]) && (!function_exists("ryunosuke\\chmonos\\array_depth") || (!false && (new \ReflectionFunction("ryunosuke\\chmonos\\array_depth"))->isInternal()))) {
    /**
     * 配列の次元数を返す
     *
     * フラット配列は 1 と定義する。
     * つまり、配列を与える限りは 0 以下を返すことはない。
     *
     * 第2引数 $max_depth を与えるとその階層になった時点で走査を打ち切る。
     * 「1階層のみか？」などを調べるときは指定したほうが高速に動作する。
     *
     * Example:
     * ```php
     * that(array_depth([]))->isSame(1);
     * that(array_depth(['hoge']))->isSame(1);
     * that(array_depth([['nest1' => ['nest2']]]))->isSame(3);
     * ```
     *
     * @param array $array 調べる配列
     * @param int|null $max_depth 最大階層数
     * @return int 次元数。素のフラット配列は 1
     */
    function array_depth($array, $max_depth = null)
    {
        assert((is_null($max_depth)) || $max_depth > 0);

        $main = function ($array, $depth) use (&$main, $max_depth) {
            // $max_depth を超えているなら打ち切る
            if ($max_depth !== null && $depth >= $max_depth) {
                return 1;
            }

            // 配列以外に興味はない
            $arrays = array_filter($array, 'is_array');

            // ネストしない配列は 1 と定義
            if (!$arrays) {
                return 1;
            }

            // 配下の内で最大を返す
            return 1 + max(array_map(function ($v) use ($main, $depth) { return $main($v, $depth + 1); }, $arrays));
        };

        return $main($array, 1);
    }
}
if (function_exists("ryunosuke\\chmonos\\array_depth") && !defined("ryunosuke\\chmonos\\array_depth")) {
    define("ryunosuke\\chmonos\\array_depth", "ryunosuke\\chmonos\\array_depth");
}

if (!isset($excluded_functions["array_all"]) && (!function_exists("ryunosuke\\chmonos\\array_all") || (!false && (new \ReflectionFunction("ryunosuke\\chmonos\\array_all"))->isInternal()))) {
    /**
     * 全要素が true になるなら true を返す（1つでも false なら false を返す）
     *
     * $callback が要求するならキーも渡ってくる。
     *
     * Example:
     * ```php
     * that(array_all([true, true]))->isTrue();
     * that(array_all([true, false]))->isFalse();
     * that(array_all([false, false]))->isFalse();
     * ```
     *
     * @param iterable $array 対象配列
     * @param callable $callback 評価クロージャ。 null なら値そのもので評価
     * @param bool|mixed $default 空配列の場合のデフォルト値
     * @return bool 全要素が true なら true
     */
    function array_all($array, $callback = null, $default = true)
    {
        if (is_empty($array)) {
            return $default;
        }

        $callback = func_user_func_array($callback);

        foreach ($array as $k => $v) {
            if (!$callback($v, $k)) {
                return false;
            }
        }
        return true;
    }
}
if (function_exists("ryunosuke\\chmonos\\array_all") && !defined("ryunosuke\\chmonos\\array_all")) {
    define("ryunosuke\\chmonos\\array_all", "ryunosuke\\chmonos\\array_all");
}

if (!isset($excluded_functions["array_lookup"]) && (!function_exists("ryunosuke\\chmonos\\array_lookup") || (!false && (new \ReflectionFunction("ryunosuke\\chmonos\\array_lookup"))->isInternal()))) {
    /**
     * キー保存可能な array_column
     *
     * array_column は キーを保存することが出来ないが、この関数は引数を2つだけ与えるとキーはそのままで array_column 相当の配列を返す。
     *
     * Example:
     * ```php
     * $array = [
     *     11 => ['id' => 1, 'name' => 'name1'],
     *     12 => ['id' => 2, 'name' => 'name2'],
     *     13 => ['id' => 3, 'name' => 'name3'],
     * ];
     * // 第3引数を渡せば array_column と全く同じ
     * that(array_lookup($array, 'name', 'id'))->isSame(array_column($array, 'name', 'id'));
     * that(array_lookup($array, 'name', null))->isSame(array_column($array, 'name', null));
     * // 省略すればキーが保存される
     * that(array_lookup($array, 'name'))->isSame([
     *     11 => 'name1',
     *     12 => 'name2',
     *     13 => 'name3',
     * ]);
     * ```
     *
     * @param iterable $array 対象配列
     * @param string|null $column_key 値となるキー
     * @param string|null $index_key キーとなるキー
     * @return array 新しい配列
     */
    function array_lookup($array, $column_key = null, $index_key = null)
    {
        $array = arrayval($array, false);
        if (func_num_args() === 3) {
            return array_column($array, $column_key, $index_key);
        }
        return array_combine(array_keys($array), array_column($array, $column_key));
    }
}
if (function_exists("ryunosuke\\chmonos\\array_lookup") && !defined("ryunosuke\\chmonos\\array_lookup")) {
    define("ryunosuke\\chmonos\\array_lookup", "ryunosuke\\chmonos\\array_lookup");
}

if (!isset($excluded_functions["class_shorten"]) && (!function_exists("ryunosuke\\chmonos\\class_shorten") || (!false && (new \ReflectionFunction("ryunosuke\\chmonos\\class_shorten"))->isInternal()))) {
    /**
     * クラスの名前空間部分を除いた短い名前を取得する
     *
     * Example:
     * ```php
     * that(class_shorten('vendor\\namespace\\ClassName'))->isSame('ClassName');
     * ```
     *
     * @param string|object $class 対象クラス・オブジェクト
     * @return string クラスの短い名前
     */
    function class_shorten($class)
    {
        if (is_object($class)) {
            $class = get_class($class);
        }

        $parts = explode('\\', $class);
        return array_pop($parts);
    }
}
if (function_exists("ryunosuke\\chmonos\\class_shorten") && !defined("ryunosuke\\chmonos\\class_shorten")) {
    define("ryunosuke\\chmonos\\class_shorten", "ryunosuke\\chmonos\\class_shorten");
}

if (!isset($excluded_functions["get_class_constants"]) && (!function_exists("ryunosuke\\chmonos\\get_class_constants") || (!false && (new \ReflectionFunction("ryunosuke\\chmonos\\get_class_constants"))->isInternal()))) {
    /**
     * クラス定数を配列で返す
     *
     * `(new \ReflectionClass($class))->getConstants()` とほぼ同じだが、可視性でフィルタができる。
     * さらに「自分自身の定義か？」でもフィルタできる。
     *
     * Example:
     * ```php
     * $class = new class extends \ArrayObject
     * {
     *     private   const C_PRIVATE   = 'private';
     *     protected const C_PROTECTED = 'protected';
     *     public    const C_PUBLIC    = 'public';
     * };
     * // 普通に全定数を返す
     * that(get_class_constants($class))->isSame([
     *     'C_PRIVATE'      => 'private',
     *     'C_PROTECTED'    => 'protected',
     *     'C_PUBLIC'       => 'public',
     *     'STD_PROP_LIST'  => \ArrayObject::STD_PROP_LIST,
     *     'ARRAY_AS_PROPS' => \ArrayObject::ARRAY_AS_PROPS,
     * ]);
     * // public のみを返す
     * that(get_class_constants($class, IS_PUBLIC))->isSame([
     *     'C_PUBLIC'       => 'public',
     *     'STD_PROP_LIST'  => \ArrayObject::STD_PROP_LIST,
     *     'ARRAY_AS_PROPS' => \ArrayObject::ARRAY_AS_PROPS,
     * ]);
     * // 自身定義でかつ public のみを返す
     * that(get_class_constants($class, IS_OWNSELF | IS_PUBLIC))->isSame([
     *     'C_PUBLIC'       => 'public',
     * ]);
     * ```
     *
     * @param string|object $class クラス名 or オブジェクト
     * @param int $filter アクセスレベル定数
     * @return array クラス定数の配列
     */
    function get_class_constants($class, $filter = null)
    {
        $class = ltrim(is_object($class) ? get_class($class) : $class, '\\');
        $filter = $filter ?? (IS_PUBLIC | IS_PROTECTED | IS_PRIVATE);

        $result = [];
        foreach ((new \ReflectionClass($class))->getReflectionConstants() as $constant) {
            if (($filter & IS_OWNSELF) === IS_OWNSELF && $constant->getDeclaringClass()->name !== $class) {
                continue;
            }
            $modifiers = $constant->getModifiers();
            if (($modifiers & $filter) === $modifiers) {
                $result[$constant->name] = $constant->getValue();
            }
        }
        return $result;
    }
}
if (function_exists("ryunosuke\\chmonos\\get_class_constants") && !defined("ryunosuke\\chmonos\\get_class_constants")) {
    define("ryunosuke\\chmonos\\get_class_constants", "ryunosuke\\chmonos\\get_class_constants");
}

if (!isset($excluded_functions["get_object_properties"]) && (!function_exists("ryunosuke\\chmonos\\get_object_properties") || (!false && (new \ReflectionFunction("ryunosuke\\chmonos\\get_object_properties"))->isInternal()))) {
    /**
     * オブジェクトのプロパティを可視・不可視を問わず取得する
     *
     * get_object_vars + no public プロパティを返すイメージ。
     *
     * Example:
     * ```php
     * $object = new \Exception('something', 42);
     * $object->oreore = 'oreore';
     *
     * // get_object_vars はそのスコープから見えないプロパティを取得できない
     * // var_dump(get_object_vars($object));
     *
     * // array キャストは全て得られるが null 文字を含むので扱いにくい
     * // var_dump((array) $object);
     *
     * // この関数を使えば不可視プロパティも取得できる
     * that(get_object_properties($object))->arraySubset([
     *     'message' => 'something',
     *     'code'    => 42,
     *     'oreore'  => 'oreore',
     * ]);
     * ```
     *
     * @param object $object オブジェクト
     * @return array 全プロパティの配列
     */
    function get_object_properties($object)
    {
        if (function_exists('get_mangled_object_vars')) {
            get_mangled_object_vars($object); // @codeCoverageIgnore
        }

        static $refs = [];
        $class = get_class($object);
        if (!isset($refs[$class])) {
            // var_export や var_dump で得られるものは「親が優先」となっているが、不具合的動作だと思うので「子を優先」とする
            $refs[$class] = [];
            $ref = new \ReflectionClass($class);
            do {
                $refs[$ref->name] = array_each($ref->getProperties(), function (&$carry, \ReflectionProperty $rp) {
                    if (!$rp->isStatic()) {
                        $rp->setAccessible(true);
                        $carry[$rp->getName()] = $rp;
                    }
                }, []);
                $refs[$class] += $refs[$ref->name];
            } while ($ref = $ref->getParentClass());
        }

        // 配列キャストだと private で ヌル文字が出たり static が含まれたりするのでリフレクションで取得して勝手プロパティで埋める
        $vars = array_map_method($refs[$class], 'getValue', [$object]);
        $vars += get_object_vars($object);

        return $vars;
    }
}
if (function_exists("ryunosuke\\chmonos\\get_object_properties") && !defined("ryunosuke\\chmonos\\get_object_properties")) {
    define("ryunosuke\\chmonos\\get_object_properties", "ryunosuke\\chmonos\\get_object_properties");
}

if (!isset($excluded_functions["dirmtime"]) && (!function_exists("ryunosuke\\chmonos\\dirmtime") || (!false && (new \ReflectionFunction("ryunosuke\\chmonos\\dirmtime"))->isInternal()))) {
    /**
     * ディレクトリの最終更新日時を返す
     *
     * 「ディレクトリの最終更新日時」とは filemtime で得られる結果ではなく、「配下のファイル群で最も新しい日時」を表す。
     * ディレクトリの mtime も検出に含まれるので、ファイルを削除した場合も検知できる。
     *
     * ファイル名を与えると例外を投げる。
     * 空ディレクトリの場合は自身の mtime を返す。
     *
     * Example:
     * ```php
     * $dirname = sys_get_temp_dir() . '/mtime';
     * rm_rf($dirname);
     * mkdir($dirname);
     *
     * // この時点では現在日時（単純に自身の更新日時）
     * that(dirmtime($dirname))->isBetween(time() - 2, time());
     * // ファイルを作って更新するとその時刻
     * touch("$dirname/tmp", time() + 10);
     * that(dirmtime($dirname))->isSame(time() + 10);
     * ```
     *
     * @param string $dirname ディレクトリ名
     * @param bool $recursive 再帰フラグ
     * @return int 最終更新日時
     */
    function dirmtime($dirname, $recursive = true)
    {
        if (!is_dir($dirname)) {
            throw new \InvalidArgumentException("'$dirname' is not directory.");
        }

        $rdi = new \RecursiveDirectoryIterator($dirname, \FilesystemIterator::SKIP_DOTS);
        $dirtime = filemtime($dirname);
        foreach ($rdi as $path) {
            /** @var \SplFileInfo $path */
            $mtime = $path->getMTime();
            if ($path->isDir() && $recursive) {
                $mtime = max($mtime, dirmtime($path->getPathname(), $recursive));
            }
            if ($dirtime < $mtime) {
                $dirtime = $mtime;
            }
        }
        return $dirtime;
    }
}
if (function_exists("ryunosuke\\chmonos\\dirmtime") && !defined("ryunosuke\\chmonos\\dirmtime")) {
    define("ryunosuke\\chmonos\\dirmtime", "ryunosuke\\chmonos\\dirmtime");
}

if (!isset($excluded_functions["delegate"]) && (!function_exists("ryunosuke\\chmonos\\delegate") || (!false && (new \ReflectionFunction("ryunosuke\\chmonos\\delegate"))->isInternal()))) {
    /**
     * 指定 callable を指定クロージャで実行するクロージャを返す
     *
     * ほぼ内部向けで外から呼ぶことはあまり想定していない。
     *
     * @param \Closure $invoker クロージャを実行するためのクロージャ（実処理）
     * @param callable $callable 最終的に実行したいクロージャ
     * @param int $arity 引数の数
     * @return callable $callable を実行するクロージャ
     */
    function delegate($invoker, $callable, $arity = null)
    {
        $arity = $arity ?? parameter_length($callable, true, true);

        if (reflect_callable($callable)->isInternal()) {
            static $cache = [];
            $cache[$arity] = $cache[$arity] ?? evaluate('return new class()
            {
                private $invoker, $callable;

                public function spawn($invoker, $callable)
                {
                    $that = clone($this);
                    $that->invoker = $invoker;
                    $that->callable = $callable;
                    return $that;
                }

                public function __invoke(' . implode(',', is_infinite($arity)
                        ? ['...$_']
                        : array_map(function ($v) { return '$_' . $v; }, array_keys(array_fill(1, $arity, null)))
                    ) . ')
                {
                    return ($this->invoker)($this->callable, func_get_args());
                }
            };');
            return $cache[$arity]->spawn($invoker, $callable);
        }

        switch (true) {
            case $arity === 0:
                return function () use ($invoker, $callable) { return $invoker($callable, func_get_args()); };
            case $arity === 1:
                return function ($_1) use ($invoker, $callable) { return $invoker($callable, func_get_args()); };
            case $arity === 2:
                return function ($_1, $_2) use ($invoker, $callable) { return $invoker($callable, func_get_args()); };
            case $arity === 3:
                return function ($_1, $_2, $_3) use ($invoker, $callable) { return $invoker($callable, func_get_args()); };
            case $arity === 4:
                return function ($_1, $_2, $_3, $_4) use ($invoker, $callable) { return $invoker($callable, func_get_args()); };
            case $arity === 5:
                return function ($_1, $_2, $_3, $_4, $_5) use ($invoker, $callable) { return $invoker($callable, func_get_args()); };
            case is_infinite($arity):
                return function (...$_) use ($invoker, $callable) { return $invoker($callable, func_get_args()); };
            default:
                $args = implode(',', array_map(function ($v) { return '$_' . $v; }, array_keys(array_fill(1, $arity, null))));
                $stmt = 'return function (' . $args . ') use ($invoker, $callable) { return $invoker($callable, func_get_args()); };';
                return eval($stmt);
        }
    }
}
if (function_exists("ryunosuke\\chmonos\\delegate") && !defined("ryunosuke\\chmonos\\delegate")) {
    define("ryunosuke\\chmonos\\delegate", "ryunosuke\\chmonos\\delegate");
}

if (!isset($excluded_functions["reflect_callable"]) && (!function_exists("ryunosuke\\chmonos\\reflect_callable") || (!false && (new \ReflectionFunction("ryunosuke\\chmonos\\reflect_callable"))->isInternal()))) {
    /**
     * callable から ReflectionFunctionAbstract を生成する
     *
     * Example:
     * ```php
     * that(reflect_callable('sprintf'))->isInstanceOf(\ReflectionFunction::class);
     * that(reflect_callable('\Closure::bind'))->isInstanceOf(\ReflectionMethod::class);
     * ```
     *
     * @param callable $callable 対象 callable
     * @return \ReflectionFunction|\ReflectionMethod リフレクションインスタンス
     */
    function reflect_callable($callable)
    {
        // callable チェック兼 $call_name 取得
        if (!is_callable($callable, true, $call_name)) {
            throw new \InvalidArgumentException("'$call_name' is not callable");
        }

        if ($callable instanceof \Closure || strpos($call_name, '::') === false) {
            return new \ReflectionFunction($callable);
        }
        else {
            [$class, $method] = explode('::', $call_name, 2);
            // for タイプ 5: 相対指定による静的クラスメソッドのコール (PHP 5.3.0 以降)
            if (strpos($method, 'parent::') === 0) {
                [, $method] = explode('::', $method);
                return (new \ReflectionClass($class))->getParentClass()->getMethod($method);
            }
            return new \ReflectionMethod($class, $method);
        }
    }
}
if (function_exists("ryunosuke\\chmonos\\reflect_callable") && !defined("ryunosuke\\chmonos\\reflect_callable")) {
    define("ryunosuke\\chmonos\\reflect_callable", "ryunosuke\\chmonos\\reflect_callable");
}

if (!isset($excluded_functions["callable_code"]) && (!function_exists("ryunosuke\\chmonos\\callable_code") || (!false && (new \ReflectionFunction("ryunosuke\\chmonos\\callable_code"))->isInternal()))) {
    /**
     * callable のコードブロックを返す
     *
     * 返り値は2値の配列。0番目の要素が定義部、1番目の要素が処理部を表す。
     *
     * Example:
     * ```php
     * list($meta, $body) = callable_code(function(...$args){return true;});
     * that($meta)->isSame('function(...$args)');
     * that($body)->isSame('{return true;}');
     *
     * // ReflectionFunctionAbstract を渡しても動作する
     * list($meta, $body) = callable_code(new \ReflectionFunction(function(...$args){return true;}));
     * that($meta)->isSame('function(...$args)');
     * that($body)->isSame('{return true;}');
     * ```
     *
     * @param callable|\ReflectionFunctionAbstract $callable コードを取得する callable
     * @return array ['定義部分', '{処理コード}']
     */
    function callable_code($callable)
    {
        $ref = $callable instanceof \ReflectionFunctionAbstract ? $callable : reflect_callable($callable);
        $contents = file($ref->getFileName());
        $start = $ref->getStartLine();
        $end = $ref->getEndLine();
        $codeblock = implode('', array_slice($contents, $start - 1, $end - $start + 1));

        $meta = parse_php("<?php $codeblock", [
            'begin' => T_FUNCTION,
            'end'   => '{',
        ]);
        array_pop($meta);

        $body = parse_php("<?php $codeblock", [
            'begin'  => '{',
            'end'    => '}',
            'offset' => count($meta),
        ]);

        return [trim(implode('', array_column($meta, 1))), trim(implode('', array_column($body, 1)))];
    }
}
if (function_exists("ryunosuke\\chmonos\\callable_code") && !defined("ryunosuke\\chmonos\\callable_code")) {
    define("ryunosuke\\chmonos\\callable_code", "ryunosuke\\chmonos\\callable_code");
}

if (!isset($excluded_functions["parameter_length"]) && (!function_exists("ryunosuke\\chmonos\\parameter_length") || (!false && (new \ReflectionFunction("ryunosuke\\chmonos\\parameter_length"))->isInternal()))) {
    /**
     * callable の引数の数を返す
     *
     * クロージャはキャッシュされない。毎回リフレクションを生成し、引数の数を調べてそれを返す。
     * （クロージャには一意性がないので key-value なキャッシュが適用できない）。
     * ので、ループ内で使ったりすると目に見えてパフォーマンスが低下するので注意。
     *
     * Example:
     * ```php
     * // trim の引数は2つ
     * that(parameter_length('trim'))->isSame(2);
     * // trim の必須引数は1つ
     * that(parameter_length('trim', true))->isSame(1);
     * ```
     *
     * @param callable $callable 対象 callable
     * @param bool $require_only true を渡すと必須パラメータの数を返す
     * @param bool $thought_variadic 可変引数を考慮するか。 true を渡すと可変引数の場合に無限長を返す
     * @return int 引数の数
     */
    function parameter_length($callable, $require_only = false, $thought_variadic = false)
    {
        // クロージャの $call_name には一意性がないのでキャッシュできない（spl_object_hash でもいいが、かなり重複するので完全ではない）
        if ($callable instanceof \Closure) {
            /** @var \ReflectionFunctionAbstract $ref */
            $ref = reflect_callable($callable);
            if ($thought_variadic && $ref->isVariadic()) {
                return INF;
            }
            elseif ($require_only) {
                return $ref->getNumberOfRequiredParameters();
            }
            else {
                return $ref->getNumberOfParameters();
            }
        }

        // $call_name 取得
        is_callable($callable, false, $call_name);

        $cache = cache($call_name, function () use ($callable) {
            /** @var \ReflectionFunctionAbstract $ref */
            $ref = reflect_callable($callable);
            return [
                '00' => $ref->getNumberOfParameters(),
                '01' => $ref->isVariadic() ? INF : $ref->getNumberOfParameters(),
                '10' => $ref->getNumberOfRequiredParameters(),
                '11' => $ref->isVariadic() ? INF : $ref->getNumberOfRequiredParameters(),
            ];
        }, __FUNCTION__);
        return $cache[(int) $require_only . (int) $thought_variadic];
    }
}
if (function_exists("ryunosuke\\chmonos\\parameter_length") && !defined("ryunosuke\\chmonos\\parameter_length")) {
    define("ryunosuke\\chmonos\\parameter_length", "ryunosuke\\chmonos\\parameter_length");
}

if (!isset($excluded_functions["func_user_func_array"]) && (!function_exists("ryunosuke\\chmonos\\func_user_func_array") || (!false && (new \ReflectionFunction("ryunosuke\\chmonos\\func_user_func_array"))->isInternal()))) {
    /**
     * パラメータ定義数に応じて呼び出し引数を可変にしてコールする
     *
     * デフォルト引数はカウントされない。必須パラメータの数で呼び出す。
     *
     * $callback に null を与えると例外的に「第1引数を返すクロージャ」を返す。
     *
     * php の標準関数は定義数より多い引数を投げるとエラーを出すのでそれを抑制したい場合に使う。
     *
     * Example:
     * ```php
     * // strlen に2つの引数を渡してもエラーにならない
     * $strlen = func_user_func_array('strlen');
     * that($strlen('abc', null))->isSame(3);
     * ```
     *
     * @param callable $callback 呼び出すクロージャ
     * @return callable 引数ぴったりで呼び出すクロージャ
     */
    function func_user_func_array($callback)
    {
        // null は第1引数を返す特殊仕様
        if ($callback === null) {
            return function ($v) { return $v; };
        }
        // クロージャはユーザ定義しかありえないので調べる必要がない
        if ($callback instanceof \Closure) {
            // と思ったが、\Closure::fromCallable で作成されたクロージャは内部属性が伝播されるようなので除外
            if (reflect_callable($callback)->isUserDefined()) {
                return $callback;
            }
        }

        // 上記以外は「引数ぴったりで削ぎ落としてコールするクロージャ」を返す
        $plength = parameter_length($callback, true, true);
        return delegate(function ($callback, $args) use ($plength) {
            if (is_infinite($plength)) {
                return $callback(...$args);
            }
            return $callback(...array_slice($args, 0, $plength));
        }, $callback, $plength);
    }
}
if (function_exists("ryunosuke\\chmonos\\func_user_func_array") && !defined("ryunosuke\\chmonos\\func_user_func_array")) {
    define("ryunosuke\\chmonos\\func_user_func_array", "ryunosuke\\chmonos\\func_user_func_array");
}

if (!isset($excluded_functions["concat"]) && (!function_exists("ryunosuke\\chmonos\\concat") || (!false && (new \ReflectionFunction("ryunosuke\\chmonos\\concat"))->isInternal()))) {
    /**
     * strcat の空文字回避版
     *
     * 基本は strcat と同じ。ただし、**引数の内1つでも空文字を含むなら空文字を返す**。
     *
     * 「プレフィックスやサフィックスを付けたいんだけど、空文字の場合はそのままで居て欲しい」という状況はまれによくあるはず。
     * コードで言えば `strlen($string) ? 'prefix-' . $string : '';` のようなもの。
     * 可変引数なので 端的に言えば mysql の CONCAT みたいな動作になる（あっちは NULL だが）。
     *
     * ```php
     * that(concat('prefix-', 'middle', '-suffix'))->isSame('prefix-middle-suffix');
     * that(concat('prefix-', '', '-suffix'))->isSame('');
     * ```
     *
     * @param mixed $variadic 結合する文字列（可変引数）
     * @return string 結合した文字列
     */
    function concat(...$variadic)
    {
        $result = '';
        foreach ($variadic as $s) {
            if (strlen($s) === 0) {
                return '';
            }
            $result .= $s;
        }
        return $result;
    }
}
if (function_exists("ryunosuke\\chmonos\\concat") && !defined("ryunosuke\\chmonos\\concat")) {
    define("ryunosuke\\chmonos\\concat", "ryunosuke\\chmonos\\concat");
}

if (!isset($excluded_functions["quoteexplode"]) && (!function_exists("ryunosuke\\chmonos\\quoteexplode") || (!false && (new \ReflectionFunction("ryunosuke\\chmonos\\quoteexplode"))->isInternal()))) {
    /**
     * エスケープやクオートに対応した explode
     *
     * $enclosures は配列で開始・終了文字が別々に指定できるが、実装上の都合で今のところ1文字ずつのみ。
     *
     * 歴史的な理由により第3引数は $limit でも $enclosures でもどちらでも渡すことができる。
     *
     * Example:
     * ```php
     * // シンプルな例
     * that(quoteexplode(',', 'a,b,c\\,d,"e,f"'))->isSame([
     *     'a', // 普通に分割される
     *     'b', // 普通に分割される
     *     'c\\,d', // \\ でエスケープしているので区切り文字とみなされない
     *     '"e,f"', // "" でクオートされているので区切り文字とみなされない
     * ]);
     *
     * // $enclosures で囲い文字の開始・終了文字を明示できる
     * that(quoteexplode(',', 'a,b,{e,f}', ['{' => '}']))->isSame([
     *     'a', // 普通に分割される
     *     'b', // 普通に分割される
     *     '{e,f}', // { } で囲まれているので区切り文字とみなされない
     * ]);
     *
     * // このように第3引数に $limit 引数を差し込むことができる
     * that(quoteexplode(',', 'a,b,{e,f}', 2, ['{' => '}']))->isSame([
     *     'a',
     *     'b,{e,f}',
     * ]);
     * ```
     *
     * @param string|array $delimiter 分割文字列
     * @param string $string 対象文字列
     * @param int $limit 分割数。負数未対応
     * @param array|string $enclosures 囲い文字。 ["start" => "end"] で開始・終了が指定できる
     * @param string $escape エスケープ文字
     * @return array 分割された配列
     */
    function quoteexplode($delimiter, $string, $limit = null, $enclosures = "'\"", $escape = '\\')
    {
        // for compatible 1.3.x
        if (!is_int($limit) && $limit !== null) {
            if (func_num_args() > 3) {
                $escape = $enclosures;
            }
            $enclosures = $limit;
            $limit = PHP_INT_MAX;
        }

        if ($limit === null) {
            $limit = PHP_INT_MAX;
        }
        $limit = max(1, $limit);

        $delimiters = arrayize($delimiter);
        $current = 0;
        $result = [];
        for ($i = 0, $l = strlen($string); $i < $l; $i++) {
            if (count($result) === $limit - 1) {
                break;
            }
            $i = strpos_quoted($string, $delimiters, $i, $enclosures, $escape);
            if ($i === false) {
                break;
            }
            foreach ($delimiters as $delimiter) {
                $delimiterlen = strlen($delimiter);
                if (substr_compare($string, $delimiter, $i, $delimiterlen) === 0) {
                    $result[] = substr($string, $current, $i - $current);
                    $current = $i + $delimiterlen;
                    $i += $delimiterlen - 1;
                    break;
                }
            }
        }
        $result[] = substr($string, $current, $l);
        return $result;
    }
}
if (function_exists("ryunosuke\\chmonos\\quoteexplode") && !defined("ryunosuke\\chmonos\\quoteexplode")) {
    define("ryunosuke\\chmonos\\quoteexplode", "ryunosuke\\chmonos\\quoteexplode");
}

if (!isset($excluded_functions["strpos_quoted"]) && (!function_exists("ryunosuke\\chmonos\\strpos_quoted") || (!false && (new \ReflectionFunction("ryunosuke\\chmonos\\strpos_quoted"))->isInternal()))) {
    /**
     * クオートを考慮して strpos する
     *
     * Example:
     * ```php
     * // クオート中は除外される
     * that(strpos_quoted('hello "this" is world', 'is'))->isSame(13);
     * // 開始位置やクオート文字は指定できる（5文字目以降の \* に囲まれていない hoge の位置を返す）
     * that(strpos_quoted('1:hoge, 2:*hoge*, 3:hoge', 'hoge', 5, '*'))->isSame(20);
     * ```
     *
     * @param string $haystack 対象文字列
     * @param string|iterable $needle 位置を取得したい文字列
     * @param int $offset 開始位置
     * @param string|array $enclosure 囲い文字。この文字中にいる $from, $to 文字は走査外になる
     * @param string $escape エスケープ文字。この文字が前にある $from, $to 文字は走査外になる
     * @return false|int $needle の位置
     */
    function strpos_quoted($haystack, $needle, $offset = 0, $enclosure = "'\"", $escape = '\\')
    {
        if (is_string($enclosure) || is_null($enclosure)) {
            if (strlen($enclosure)) {
                $chars = str_split($enclosure);
                $enclosure = array_combine($chars, $chars);
            }
            else {
                $enclosure = [];
            }
        }
        $needles = arrayval($needle);

        $strlen = strlen($haystack);

        if ($offset < 0) {
            $offset += $strlen;
        }

        $enclosing = [];
        for ($i = $offset; $i < $strlen; $i++) {
            if ($i !== 0 && $haystack[$i - 1] === $escape) {
                continue;
            }
            foreach ($enclosure as $start => $end) {
                if (substr_compare($haystack, $end, $i, strlen($end)) === 0) {
                    if ($enclosing && $enclosing[count($enclosing) - 1] === $end) {
                        array_pop($enclosing);
                        $i += strlen($end) - 1;
                        continue 2;
                    }
                }
                if (substr_compare($haystack, $start, $i, strlen($start)) === 0) {
                    $enclosing[] = $end;
                    $i += strlen($start) - 1;
                    continue 2;
                }
            }

            if (empty($enclosing)) {
                foreach ($needles as $needle) {
                    if (substr_compare($haystack, $needle, $i, strlen($needle)) === 0) {
                        return $i;
                    }
                }
            }
        }
        return false;
    }
}
if (function_exists("ryunosuke\\chmonos\\strpos_quoted") && !defined("ryunosuke\\chmonos\\strpos_quoted")) {
    define("ryunosuke\\chmonos\\strpos_quoted", "ryunosuke\\chmonos\\strpos_quoted");
}

if (!isset($excluded_functions["str_contains"]) && (!function_exists("ryunosuke\\chmonos\\str_contains") || (!false && (new \ReflectionFunction("ryunosuke\\chmonos\\str_contains"))->isInternal()))) {
    /**
     * 指定文字列を含むか返す
     *
     * Example:
     * ```php
     * that(str_contains('abc', 'b'))->isTrue();
     * that(str_contains('abc', 'B', true))->isTrue();
     * that(str_contains('abc', ['b', 'x'], false, false))->isTrue();
     * that(str_contains('abc', ['b', 'x'], false, true))->isFalse();
     * ```
     *
     * @param string $haystack 対象文字列
     * @param string|array $needle 調べる文字列
     * @param bool $case_insensitivity 大文字小文字を無視するか
     * @param bool $and_flag すべて含む場合に true を返すか
     * @return bool $needle を含むなら true
     */
    function str_contains($haystack, $needle, $case_insensitivity = false, $and_flag = false)
    {
        if (!is_array($needle)) {
            $needle = [$needle];
        }

        // あくまで文字列としての判定に徹する（strpos の第2引数は闇が深い気がする）
        $haystack = (string) $haystack;
        $needle = array_map('strval', $needle);

        foreach ($needle as $str) {
            if ($str === '') {
                continue;
            }
            $pos = $case_insensitivity ? stripos($haystack, $str) : strpos($haystack, $str);
            if ($and_flag && $pos === false) {
                return false;
            }
            if (!$and_flag && $pos !== false) {
                return true;
            }
        }
        return !!$and_flag;
    }
}
if (function_exists("ryunosuke\\chmonos\\str_contains") && !defined("ryunosuke\\chmonos\\str_contains")) {
    define("ryunosuke\\chmonos\\str_contains", "ryunosuke\\chmonos\\str_contains");
}

if (!isset($excluded_functions["css_selector"]) && (!function_exists("ryunosuke\\chmonos\\css_selector") || (!false && (new \ReflectionFunction("ryunosuke\\chmonos\\css_selector"))->isInternal()))) {
    /**
     * CSS セレクタ文字をパースして配列で返す
     *
     * 包含などではない属性セレクタを与えると属性として認識する。
     * 独自仕様として・・・
     *
     * - [!attr]: 否定属性として false を返す
     * - {styles}: style 属性とみなす
     *
     * がある。
     *
     * Example:
     * ```php
     * that(css_selector('#hoge.c1.c2[name=hoge\[\]][href="http://hoge"][hidden][!readonly]{width:123px;height:456px}'))->is([
     *     'id'       => 'hoge',
     *     'class'    => ['c1', 'c2'],
     *     'name'     => 'hoge[]',
     *     'href'     => 'http://hoge',
     *     'hidden'   => true,
     *     'readonly' => false,
     *     'style'    => [
     *         'width'  => '123px',
     *         'height' => '456px',
     *     ],
     * ]);
     * ```
     *
     * @param string $selector CSS セレクタ
     * @return array 属性配列
     */
    function css_selector($selector)
    {
        $id = '';
        $classes = [];
        $styles = [];
        $attrs = [];

        $context = null;
        $escaping = null;
        $chars = preg_split('##u', $selector, -1, PREG_SPLIT_NO_EMPTY);
        for ($i = 0, $l = count($chars); $i < $l; $i++) {
            $char = $chars[$i];
            if ($char === '"' || $char === "'") {
                $escaping = $escaping === $char ? null : $char;
            }

            if (!$escaping && $char === '#') {
                if (strlen($id)) {
                    throw new \InvalidArgumentException('#id is multiple.');
                }
                $context = $char;
                continue;
            }
            if (!$escaping && $char === '.') {
                $context = $char;
                $classes[] = '';
                continue;
            }
            if (!$escaping && $char === '{') {
                $context = $char;
                $styles[] = '';
                continue;
            }
            if (!$escaping && $char === ';') {
                $styles[] = '';
                continue;
            }
            if (!$escaping && $char === '}') {
                $context = null;
                continue;
            }
            if (!$escaping && $char === '[') {
                $context = $char;
                $attrs[] = '';
                continue;
            }
            if (!$escaping && $char === ']') {
                $context = null;
                continue;
            }

            if ($char === '\\') {
                $char = $chars[++$i];
            }

            if ($context === '#') {
                $id .= $char;
                continue;
            }
            if ($context === '.') {
                $classes[count($classes) - 1] .= $char;
                continue;
            }
            if ($context === '{') {
                $styles[count($styles) - 1] .= $char;
                continue;
            }
            if ($context === '[') {
                $attrs[count($attrs) - 1] .= $char;
                continue;
            }
        }

        $attrkv = [];
        if (strlen($id)) {
            $attrkv['id'] = $id;
        }
        if ($classes) {
            $attrkv['class'] = $classes;
        }
        foreach ($styles as $style) {
            $declares = array_filter(array_map('trim', explode(';', $style)), 'strlen');
            foreach ($declares as $declare) {
                [$k, $v] = array_map('trim', explode(':', $declare, 2)) + [1 => null];
                if ($v === null) {
                    throw new \InvalidArgumentException("[$k] is empty.");
                }
                $attrkv['style'][$k] = $v;
            }
        }
        foreach ($attrs as $attr) {
            [$k, $v] = explode('=', $attr, 2) + [1 => true];
            if (array_key_exists($k, $attrkv)) {
                throw new \InvalidArgumentException("[$k] is dumplicated.");
            }
            if ($k[0] === '!') {
                $k = substr($k, 1);
                $v = false;
            }
            $attrkv[$k] = is_string($v) ? json_decode($v) ?? $v : $v;
        }

        return $attrkv;
    }
}
if (function_exists("ryunosuke\\chmonos\\css_selector") && !defined("ryunosuke\\chmonos\\css_selector")) {
    define("ryunosuke\\chmonos\\css_selector", "ryunosuke\\chmonos\\css_selector");
}

if (!isset($excluded_functions["paml_import"]) && (!function_exists("ryunosuke\\chmonos\\paml_import") || (!false && (new \ReflectionFunction("ryunosuke\\chmonos\\paml_import"))->isInternal()))) {
    /**
     * paml 的文字列をパースする
     *
     * paml とは yaml を簡易化したような独自フォーマットを指す。
     * ざっくりと下記のような特徴がある。
     *
     * - ほとんど yaml と同じだがフロースタイルのみでキーコロンの後のスペースは不要
     * - yaml のアンカーや複数ドキュメントのようなややこしい仕様はすべて未対応
     * - 配列を前提にしているので、トップレベルの `[]` `{}` は不要
     * - `[]` でいわゆる php の配列、 `{}` で stdClass を表す（オプション指定可能）
     * - bare string で php の定数を表す
     *
     * 簡易的な設定の注入に使える（yaml は標準で対応していないし、json や php 配列はクオートの必要やケツカンマ問題がある）。
     * なお、かなり緩くパースしてるので基本的にエラーにはならない。
     *
     * 早見表：
     *
     * - php:  `["n" => null, "f" => false, "i" => 123, "d" => 3.14, "s" => "this is string", "a" => [1, 2, "x" => "X"]]`
     *     - ダブルアローとキーのクオートが冗長
     * - json: `{"n":null, "f":false, "i":123, "d":3.14, "s":"this is string", "a":{"0": 1, "1": 2, "x": "X"}}`
     *     - キーのクオートが冗長だしケツカンマ非許容
     * - yaml: `{n: null, f: false, i: 123, d: 3.14, s: "this is string", a: {0: 1, 1: 2, x: X}}`
     *     - 理想に近いが、コロンの後にスペースが必要だし連想配列が少々難。なにより拡張や外部ライブラリが必要
     * - paml: `n:null, f:false, i:123, d:3.14, s:"this is string", a:[1, 2, x:X]`
     *     - シンプルイズベスト
     *
     * Example:
     * ```php
     * // こういったスカラー型はほとんど yaml と一緒だが、コロンの後のスペースは不要（あってもよい）
     * that(paml_import('n:null, f:false, i:123, d:3.14, s:"this is string"'))->isSame([
     *     'n' => null,
     *     'f' => false,
     *     'i' => 123,
     *     'd' => 3.14,
     *     's' => 'this is string',
     * ]);
     * // 配列が使える（キーは連番なら不要）。ネストも可能
     * that(paml_import('a:[1,2,x:X,3], nest:[a:[b:[c:[X]]]]'))->isSame([
     *     'a'    => [1, 2, 'x' => 'X', 3],
     *     'nest' => [
     *         'a' => [
     *             'b' => [
     *                 'c' => ['X']
     *             ],
     *         ],
     *     ],
     * ]);
     * // bare 文字列で定数が使える
     * that(paml_import('pv:PHP_VERSION, ao:ArrayObject::STD_PROP_LIST'))->isSame([
     *     'pv' => \PHP_VERSION,
     *     'ao' => \ArrayObject::STD_PROP_LIST,
     * ]);
     * ```
     *
     * @param string $pamlstring PAML 文字列
     * @param array $options オプション配列
     * @return array php 配列
     */
    function paml_import($pamlstring, $options = [])
    {
        $options += [
            'cache'          => true,
            'trailing-comma' => true,
            'stdclass'       => true,
        ];

        static $caches = [];
        if ($options['cache']) {
            $key = $pamlstring . json_encode($options);
            return $caches[$key] = $caches[$key] ?? paml_import($pamlstring, ['cache' => false] + $options);
        }

        $escapers = ['"' => '"', "'" => "'", '[' => ']', '{' => '}'];

        $values = array_map('trim', quoteexplode(',', $pamlstring, null, $escapers));
        if ($options['trailing-comma'] && end($values) === '') {
            array_pop($values);
        }

        $result = [];
        foreach ($values as $value) {
            $key = null;
            $kv = array_map('trim', quoteexplode(':', $value, 2, $escapers));
            if (count($kv) === 2) {
                [$key, $value] = $kv;
            }

            $prefix = $value[0] ?? null;
            $suffix = $value[-1] ?? null;

            if (($prefix === '[' && $suffix === ']') || ($prefix === '{' && $suffix === '}')) {
                $value = paml_import(substr($value, 1, -1), $options);
                $value = ($prefix === '[' || !$options['stdclass']) ? (array) $value : (object) $value;
            }
            elseif ($prefix === '"' && $suffix === '"') {
                //$value = stripslashes(substr($value, 1, -1));
                $value = json_decode($value);
            }
            elseif ($prefix === "'" && $suffix === "'") {
                $value = substr($value, 1, -1);
            }
            elseif (defined($value)) {
                $value = constant($value);
            }
            elseif (is_numeric($value)) {
                if (ctype_digit(ltrim($value, '+-'))) {
                    $value = (int) $value;
                }
                else {
                    $value = (double) $value;
                }
            }

            if ($key === null) {
                $result[] = $value;
            }
            else {
                $result[$key] = $value;
            }
        }
        return $result;
    }
}
if (function_exists("ryunosuke\\chmonos\\paml_import") && !defined("ryunosuke\\chmonos\\paml_import")) {
    define("ryunosuke\\chmonos\\paml_import", "ryunosuke\\chmonos\\paml_import");
}

if (!isset($excluded_functions["mb_trim"]) && (!function_exists("ryunosuke\\chmonos\\mb_trim") || (!false && (new \ReflectionFunction("ryunosuke\\chmonos\\mb_trim"))->isInternal()))) {
    /**
     * マルチバイト対応 trim
     *
     * Example:
     * ```php
     * that(mb_trim(' 　 あああ　 　'))->isSame('あああ');
     * ```
     *
     * @param string $string 対象文字列
     * @return string trim した文字列
     */
    function mb_trim($string)
    {
        return preg_replace('/\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z/u', '', $string);
    }
}
if (function_exists("ryunosuke\\chmonos\\mb_trim") && !defined("ryunosuke\\chmonos\\mb_trim")) {
    define("ryunosuke\\chmonos\\mb_trim", "ryunosuke\\chmonos\\mb_trim");
}

if (!isset($excluded_functions["evaluate"]) && (!function_exists("ryunosuke\\chmonos\\evaluate") || (!false && (new \ReflectionFunction("ryunosuke\\chmonos\\evaluate"))->isInternal()))) {
    /**
     * eval のプロキシ関数
     *
     * 一度ファイルに吐いてから require した方が opcache が効くので抜群に速い。
     * また、素の eval は ParseError が起こったときの表示がわかりにくすぎるので少し見やすくしてある。
     *
     * 関数化してる以上 eval におけるコンテキストの引き継ぎはできない。
     * ただし、引数で変数配列を渡せるようにしてあるので get_defined_vars を併用すれば基本的には同じ（$this はどうしようもない）。
     *
     * 短いステートメントだと opcode が少ないのでファイルを経由せず直接 eval したほうが速いことに留意。
     * 一応引数で指定できるようにはしてある。
     *
     * Example:
     * ```php
     * $a = 1;
     * $b = 2;
     * $phpcode = ';
     * $c = $a + $b;
     * return $c * 3;
     * ';
     * that(evaluate($phpcode, get_defined_vars()))->isSame(9);
     * ```
     *
     * @param string $phpcode 実行する php コード
     * @param array $contextvars コンテキスト変数配列
     * @param int $cachesize キャッシュするサイズ
     * @return mixed eval の return 値
     */
    function evaluate($phpcode, $contextvars = [], $cachesize = 256)
    {
        $cachefile = null;
        if ($cachesize && strlen($phpcode) >= $cachesize) {
            $cachefile = cachedir() . '/' . rawurlencode(__FUNCTION__) . '-' . sha1($phpcode) . '.php';
            if (!file_exists($cachefile)) {
                file_put_contents($cachefile, "<?php $phpcode", LOCK_EX);
            }
        }

        try {
            if ($cachefile) {
                return (static function () {
                    extract(func_get_arg(1));
                    return require func_get_arg(0);
                })($cachefile, $contextvars);
            }
            else {
                return (static function () {
                    extract(func_get_arg(1));
                    return eval(func_get_arg(0));
                })($phpcode, $contextvars);
            }
        }
        catch (\ParseError $ex) {
            $errline = $ex->getLine();
            $errline_1 = $errline - 1;
            $codes = preg_split('#\\R#u', $phpcode);
            $codes[$errline_1] = '>>> ' . $codes[$errline_1];

            $N = 5; // 前後の行数
            $message = $ex->getMessage();
            $message .= "\n" . implode("\n", array_slice($codes, max(0, $errline_1 - $N), $N * 2 + 1));
            if ($cachefile) {
                $message .= "\n in " . realpath($cachefile) . " on line " . $errline . "\n";
            }
            throw new \ParseError($message, $ex->getCode(), $ex);
        }
    }
}
if (function_exists("ryunosuke\\chmonos\\evaluate") && !defined("ryunosuke\\chmonos\\evaluate")) {
    define("ryunosuke\\chmonos\\evaluate", "ryunosuke\\chmonos\\evaluate");
}

if (!isset($excluded_functions["parse_php"]) && (!function_exists("ryunosuke\\chmonos\\parse_php") || (!false && (new \ReflectionFunction("ryunosuke\\chmonos\\parse_php"))->isInternal()))) {
    /**
     * php のコード断片をパースする
     *
     * 結果配列は token_get_all したものだが、「字句の場合に文字列で返す」仕様は適用されずすべて配列で返す。
     * つまり必ず `[TOKENID, TOKEN, LINE]` で返す。
     *
     * Example:
     * ```php
     * $phpcode = 'namespace Hogera;
     * class Example
     * {
     *     // something
     * }';
     *
     * // namespace ～ ; を取得
     * $part = parse_php($phpcode, [
     *     'begin' => T_NAMESPACE,
     *     'end'   => ';',
     * ]);
     * that(implode('', array_column($part, 1)))->isSame('namespace Hogera;');
     *
     * // class ～ { を取得
     * $part = parse_php($phpcode, [
     *     'begin' => T_CLASS,
     *     'end'   => '{',
     * ]);
     * that(implode('', array_column($part, 1)))->isSame("class Example\n{");
     * ```
     *
     * @param string $phpcode パースする php コード
     * @param array|int $option パースオプション
     * @return array トークン配列
     */
    function parse_php($phpcode, $option = [])
    {
        if (is_int($option)) {
            $option = ['flags' => $option];
        }

        $default = [
            'begin'      => [],   // 開始トークン
            'end'        => [],   // 終了トークン
            'offset'     => 0,    // 開始トークン位置
            'flags'      => 0,    // token_get_all の $flags. TOKEN_PARSE を与えると ParseError が出ることがあるのでデフォルト 0
            'cache'      => true, // キャッシュするか否か
            'nest_token' => [
                ')' => '(',
                '}' => '{',
                ']' => '[',
            ],
        ];
        $option += $default;

        static $cache = [];
        $tokens = $cache[$phpcode] ?? array_map(function ($token) use ($option) {
                // token_get_all の結果は微妙に扱いづらいので少し調整する（string/array だったり、名前変換の必要があったり）
                if (is_array($token)) {
                    // for debug
                    if ($option['flags'] & TOKEN_NAME) {
                        $token[] = token_name($token[0]);
                    }
                    return $token;
                }
                else {
                    // string -> [TOKEN, CHAR, LINE]
                    return [null, $token, 0];
                }
            }, token_get_all("<?php $phpcode", $option['flags']));
        if ($option['cache']) {
            $cache[$phpcode] = $tokens;
        }

        $begin_tokens = (array) $option['begin'];
        $end_tokens = (array) $option['end'];
        $nest_tokens = $option['nest_token'];

        $result = [];
        $starting = !$begin_tokens;
        $nesting = 0;
        for ($i = $option['offset'], $l = count($tokens); $i < $l; $i++) {
            $token = $tokens[$i];

            foreach ($begin_tokens as $t) {
                if ($t === $token[0] || $t === $token[1]) {
                    $starting = true;
                    break;
                }
            }
            if (!$starting) {
                continue;
            }

            $result[$i] = $token;

            foreach ($end_tokens as $t) {
                if (isset($nest_tokens[$t])) {
                    $nest_token = $nest_tokens[$t];
                    if ($token[0] === $nest_token || $token[1] === $nest_token) {
                        $nesting++;
                    }
                }
                if ($t === $token[0] || $t === $token[1]) {
                    $nesting--;
                    if ($nesting <= 0) {
                        break 2;
                    }
                    break;
                }
            }
        }
        return $result;
    }
}
if (function_exists("ryunosuke\\chmonos\\parse_php") && !defined("ryunosuke\\chmonos\\parse_php")) {
    define("ryunosuke\\chmonos\\parse_php", "ryunosuke\\chmonos\\parse_php");
}

if (!isset($excluded_functions["blank_if"]) && (!function_exists("ryunosuke\\chmonos\\blank_if") || (!false && (new \ReflectionFunction("ryunosuke\\chmonos\\blank_if"))->isInternal()))) {
    /**
     * 値が空なら null を返す
     *
     * `is_empty($value) ? $value : null` とほぼ同じ。
     * 言ってしまえば「falsy な値を null に変換する」とも言える。
     *
     * ここでいう falsy とは php 標準の `empty` ではなく本ライブラリの `is_empty` であることに留意（"0" は空ではない）。
     * さらに利便性のため 0, 0.0 も空ではない判定をする（strpos や array_search などで「0 は意味のある値」という事が多いので）。
     * 乱暴に言えば「仮に文字列化したとき、情報量がゼロ」が falsy になる。
     *
     * - 「 `$var ?: 'default'` で十分なんだけど "0" が…」
     * - 「 `$var ?? 'default'` で十分なんだけど false が…」
     *
     * という状況はまれによくあるはず。
     *
     * ?? との親和性のため null を返す動作がデフォルトだが、そのデフォルト値は引数で渡すこともできる。
     * 用途は Example を参照。
     *
     * Example:
     * ```php
     * // falsy な値は null を返すので null 合体演算子でデフォルト値が得られる
     * that(blank_if(null) ?? 'default')->isSame('default');
     * that(blank_if('')   ?? 'default')->isSame('default');
     * // falsy じゃない値の場合は引数をそのまま返すので null 合体演算子には反応しない
     * that(blank_if(0)   ?? 'default')->isSame(0);   // 0 は空ではない
     * that(blank_if('0') ?? 'default')->isSame('0'); // "0" は空ではない
     * that(blank_if(1)   ?? 'default')->isSame(1);
     * that(blank_if('X') ?? 'default')->isSame('X');
     * // 第2引数で返る値を指定できるので下記も等価となる。ただし、php の仕様上第2引数が必ず評価されるため、関数呼び出しなどだと無駄な処理となる
     * that(blank_if(null, 'default'))->isSame('default');
     * that(blank_if('',   'default'))->isSame('default');
     * that(blank_if(0,    'default'))->isSame(0);
     * that(blank_if('0',  'default'))->isSame('0');
     * that(blank_if(1,    'default'))->isSame(1);
     * that(blank_if('X',  'default'))->isSame('X');
     * // 第2引数の用途は少し短く書けることと演算子の優先順位のつらみの回避程度（`??` は結構優先順位が低い。下記を参照）
     * that(0 < blank_if(null) ?? 1)->isFalse();  // (0 < null) ?? 1 となるので false
     * that(0 < blank_if(null, 1))->isTrue();     // 0 < 1 となるので true
     * that(0 < (blank_if(null) ?? 1))->isTrue(); // ?? で同じことしたいならこのように括弧が必要
     *
     * # ここから下は既存言語機構との比較（愚痴っぽいので読まなくてもよい）
     *
     * // エルビス演算子は "0" にも反応するので正直言って使いづらい（php における falsy の定義は広すぎる）
     * that(null ?: 'default')->isSame('default');
     * that(''   ?: 'default')->isSame('default');
     * that(1    ?: 'default')->isSame(1);
     * that('0'  ?: 'default')->isSame('default'); // こいつが反応してしまう
     * that('X'  ?: 'default')->isSame('X');
     * // 逆に null 合体演算子は null にしか反応しないので微妙に使い勝手が悪い（php の標準関数が false を返したりするし）
     * that(null ?? 'default')->isSame('default'); // こいつしか反応しない
     * that(''   ?? 'default')->isSame('');
     * that(1    ?? 'default')->isSame(1);
     * that('0'  ?? 'default')->isSame('0');
     * that('X'  ?? 'default')->isSame('X');
     * // 恣意的な例だが、 substr は false も '0' も返し得るので ?: は使えない。 null を返すこともないので ?? も使えない（エラーも吐かない）
     * that(substr('000', 1, 1) ?: 'default')->isSame('default'); // '0' を返すので 'default' になる
     * that(substr('xxx', 9, 1) ?: 'default')->isSame('default'); // （文字数が足りなくて）false を返すので 'default' になる
     * that(substr('000', 1, 1) ?? 'default')->isSame('0');   // substr が null を返すことはないので 'default' になることはない
     * that(substr('xxx', 9, 1) ?? 'default')->isSame(false); // substr が null を返すことはないので 'default' になることはない
     * // 要するに単に「false が返ってきた場合に 'default' としたい」だけなんだが、下記のようにめんどくさいことをせざるを得ない
     * that(substr('xxx', 9, 1) === false ? 'default' : substr('xxx', 9, 1))->isSame('default'); // 3項演算子で2回呼ぶ
     * that(($tmp = substr('xxx', 9, 1) === false) ? 'default' : $tmp)->isSame('default');       // 一時変数を使用する（あるいは if 文）
     * // このように書きたかった
     * that(blank_if(substr('xxx', 9, 1)) ?? 'default')->isSame('default'); // null 合体演算子版
     * that(blank_if(substr('xxx', 9, 1), 'default'))->isSame('default');   // 第2引数版
     *
     * // 恣意的な例その2。 0 は空ではないので array_search などにも応用できる（見つからない場合に false を返すので ?? はできないし、 false 相当を返し得るので ?: もできない）
     * that(array_search('x', ['a', 'b', 'c']) ?? 'default')->isSame(false);     // 見つからないので 'default' としたいが false になってしまう
     * that(array_search('a', ['a', 'b', 'c']) ?: 'default')->isSame('default'); // 見つかったのに 0 に反応するので 'default' になってしまう
     * that(blank_if(array_search('x', ['a', 'b', 'c'])) ?? 'default')->isSame('default'); // このように書きたかった
     * that(blank_if(array_search('a', ['a', 'b', 'c'])) ?? 'default')->isSame(0);         // このように書きたかった
     * ```
     *
     * @param mixed $var 判定する値
     * @param mixed $default 空だった場合のデフォルト値
     * @return mixed 空なら $default, 空じゃないなら $var をそのまま返す
     */
    function blank_if($var, $default = null)
    {
        if (is_object($var)) {
            // 文字列化できるかが優先
            if (is_stringable($var)) {
                return strlen($var) ? $var : $default;
            }
            // 次点で countable
            if (is_countable($var)) {
                return count($var) ? $var : $default;
            }
            return $var;
        }

        // 0, 0.0, "0" は false
        if ($var === 0 || $var === 0.0 || $var === '0') {
            return $var;
        }

        // 上記以外は empty に任せる
        return empty($var) ? $default : $var;
    }
}
if (function_exists("ryunosuke\\chmonos\\blank_if") && !defined("ryunosuke\\chmonos\\blank_if")) {
    define("ryunosuke\\chmonos\\blank_if", "ryunosuke\\chmonos\\blank_if");
}

if (!isset($excluded_functions["get_uploaded_files"]) && (!function_exists("ryunosuke\\chmonos\\get_uploaded_files") || (!false && (new \ReflectionFunction("ryunosuke\\chmonos\\get_uploaded_files"))->isInternal()))) {
    /**
     * $_FILES の構造を組み替えて $_POST などと同じにする
     *
     * $_FILES の配列構造はバグとしか思えないのでそれを是正する関数。
     * 第1引数 $files は指定可能だが、大抵は $_FILES であり、指定するのはテスト用。
     *
     * サンプルを書くと長くなるので例は{@source \ryunosuke\Test\Package\UtilityTest::test_get_uploaded_files() テストファイル}を参照。
     *
     * @param array $files $_FILES の同じ構造の配列。省略時は $_FILES
     * @return array $_FILES を $_POST などと同じ構造にした配列
     */
    function get_uploaded_files($files = null)
    {
        $result = [];
        foreach (($files ?: $_FILES) as $name => $file) {
            if (is_array($file['name'])) {
                $file = get_uploaded_files(array_each($file['name'], function (&$carry, $dummy, $subkey) use ($file) {
                    $carry[$subkey] = array_lookup($file, $subkey);
                }, []));
            }
            $result[$name] = $file;
        }
        return $result;
    }
}
if (function_exists("ryunosuke\\chmonos\\get_uploaded_files") && !defined("ryunosuke\\chmonos\\get_uploaded_files")) {
    define("ryunosuke\\chmonos\\get_uploaded_files", "ryunosuke\\chmonos\\get_uploaded_files");
}

if (!isset($excluded_functions["cachedir"]) && (!function_exists("ryunosuke\\chmonos\\cachedir") || (!false && (new \ReflectionFunction("ryunosuke\\chmonos\\cachedir"))->isInternal()))) {
    /**
     * 本ライブラリで使用するキャッシュディレクトリを設定する
     *
     * @param string|null $dirname キャッシュディレクトリ。省略時は返すのみ
     * @return string 設定前のキャッシュディレクトリ
     */
    function cachedir($dirname = null)
    {
        static $cachedir;
        if ($cachedir === null) {
            $cachedir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . strtr(__NAMESPACE__, ['\\' => '%']);
            cachedir($cachedir); // for mkdir
        }

        if ($dirname === null) {
            return $cachedir;
        }

        if (!file_exists($dirname)) {
            @mkdir($dirname, 0777 & (~umask()), true);
        }
        $result = $cachedir;
        $cachedir = realpath($dirname);
        return $result;
    }
}
if (function_exists("ryunosuke\\chmonos\\cachedir") && !defined("ryunosuke\\chmonos\\cachedir")) {
    define("ryunosuke\\chmonos\\cachedir", "ryunosuke\\chmonos\\cachedir");
}

if (!isset($excluded_functions["cache"]) && (!function_exists("ryunosuke\\chmonos\\cache") || (!false && (new \ReflectionFunction("ryunosuke\\chmonos\\cache"))->isInternal()))) {
    /**
     * シンプルにキャッシュする
     *
     * この関数は get/set/delete を兼ねる。
     * キャッシュがある場合はそれを返し、ない場合は $provider を呼び出してその結果をキャッシュしつつそれを返す。
     *
     * $provider に null を与えるとキャッシュの削除となる。
     *
     * Example:
     * ```php
     * $provider = function(){return rand();};
     * // 乱数を返す処理だが、キャッシュされるので同じ値になる
     * $rand1 = cache('rand', $provider);
     * $rand2 = cache('rand', $provider);
     * that($rand1)->isSame($rand2);
     * // $provider に null を与えると削除される
     * cache('rand', null);
     * $rand3 = cache('rand', $provider);
     * that($rand1)->isNotSame($rand3);
     * ```
     *
     * @param string $key キャッシュのキー
     * @param callable $provider キャッシュがない場合にコールされる callable
     * @param string $namespace 名前空間
     * @return mixed キャッシュ
     */
    function cache($key, $provider, $namespace = null)
    {
        static $cacheobject;
        $cacheobject = $cacheobject ?? new class(cachedir()) {
                const CACHE_EXT = '.php-cache';

                /** @var string キャッシュディレクトリ */
                private $cachedir;

                /** @var array 内部キャッシュ */
                private $cache;

                /** @var array 変更感知配列 */
                private $changed;

                public function __construct($cachedir)
                {
                    $this->cachedir = $cachedir;
                    $this->cache = [];
                    $this->changed = [];
                }

                public function __destruct()
                {
                    // 変更されているもののみ保存
                    foreach ($this->changed as $namespace => $dummy) {
                        $filepath = $this->cachedir . '/' . rawurlencode($namespace) . self::CACHE_EXT;
                        $content = "<?php\nreturn " . var_export($this->cache[$namespace], true) . ";\n";

                        $temppath = tempnam(sys_get_temp_dir(), 'cache');
                        if (file_put_contents($temppath, $content) !== false) {
                            @chmod($temppath, 0644);
                            if (!@rename($temppath, $filepath)) {
                                @unlink($temppath);
                            }
                        }
                    }
                }

                public function has($namespace, $key)
                {
                    // ファイルから読み込む必要があるので get しておく
                    $this->get($namespace, $key);
                    return array_key_exists($key, $this->cache[$namespace]);
                }

                public function get($namespace, $key)
                {
                    // 名前空間自体がないなら作る or 読む
                    if (!isset($this->cache[$namespace])) {
                        $nsarray = [];
                        $cachpath = $this->cachedir . '/' . rawurldecode($namespace) . self::CACHE_EXT;
                        if (file_exists($cachpath)) {
                            $nsarray = require $cachpath;
                        }
                        $this->cache[$namespace] = $nsarray;
                    }

                    return $this->cache[$namespace][$key] ?? null;
                }

                public function set($namespace, $key, $value)
                {
                    // 新しい値が来たら変更フラグを立てる
                    if (!isset($this->cache[$namespace]) || !array_key_exists($key, $this->cache[$namespace]) || $this->cache[$namespace][$key] !== $value) {
                        $this->changed[$namespace] = true;
                    }

                    $this->cache[$namespace][$key] = $value;
                }

                public function delete($namespace, $key)
                {
                    $this->changed[$namespace] = true;
                    unset($this->cache[$namespace][$key]);
                }

                public function clear()
                {
                    // インメモリ情報をクリアして・・・
                    $this->cache = [];
                    $this->changed = [];

                    // ファイルも消す
                    foreach (glob($this->cachedir . '/*' . self::CACHE_EXT) as $file) {
                        unlink($file);
                    }
                }
            };

        // flush (for test)
        if ($key === null) {
            if ($provider === null) {
                $cacheobject->clear();
            }
            $cacheobject = null;
            return;
        }

        $namespace = $namespace ?? __FILE__;

        $exist = $cacheobject->has($namespace, $key);
        if ($provider === null) {
            $cacheobject->delete($namespace, $key);
            return $exist;
        }
        if (!$exist) {
            $cacheobject->set($namespace, $key, $provider());
        }
        return $cacheobject->get($namespace, $key);
    }
}
if (function_exists("ryunosuke\\chmonos\\cache") && !defined("ryunosuke\\chmonos\\cache")) {
    define("ryunosuke\\chmonos\\cache", "ryunosuke\\chmonos\\cache");
}

if (!isset($excluded_functions["error"]) && (!function_exists("ryunosuke\\chmonos\\error") || (!false && (new \ReflectionFunction("ryunosuke\\chmonos\\error"))->isInternal()))) {
    /**
     * エラー出力する
     *
     * 第1引数 $message はそれらしく文字列化されて出力される。基本的にはあらゆる型を与えて良い。
     *
     * 第2引数 $destination で出力対象を指定する。省略すると error_log 設定に従う。
     * 文字列を与えるとファイル名とみなし、ファイルに追記される。
     * ファイルを開くが、**ファイルは閉じない**。閉じ処理は php の終了処理に身を任せる。
     * したがって閉じる必要がある場合はファイルポインタを渡す必要がある。
     *
     * @param string|mixed $message 出力メッセージ
     * @param resource|string|mixed $destination 出力先
     * @return int 書き込んだバイト数
     */
    function error($message, $destination = null)
    {
        static $persistences = [];

        $time = date('d-M-Y H:i:s e');
        $content = stringify($message);
        $location = '';
        if (!($message instanceof \Exception || $message instanceof \Throwable)) {
            foreach (debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS) as $trace) {
                if (isset($trace['file'], $trace['line'])) {
                    $location = " in {$trace['file']} on line {$trace['line']}";
                    break;
                }
            }
        }
        $line = "[$time] PHP Log:  $content$location\n";

        if ($destination === null) {
            $destination = blank_if(ini_get('error_log'), 'php://stderr');
        }

        if ($destination === 'syslog') {
            syslog(LOG_INFO, $message);
            return strlen($line);
        }

        if (is_resource($destination)) {
            $fp = $destination;
        }
        elseif (is_string($destination)) {
            if (!isset($persistences[$destination])) {
                $persistences[$destination] = fopen($destination, 'a');
            }
            $fp = $persistences[$destination];
        }

        if (empty($fp)) {
            throw new \InvalidArgumentException('$destination must be resource or string.');
        }

        flock($fp, LOCK_EX);
        fwrite($fp, $line);
        flock($fp, LOCK_UN);

        return strlen($line);
    }
}
if (function_exists("ryunosuke\\chmonos\\error") && !defined("ryunosuke\\chmonos\\error")) {
    define("ryunosuke\\chmonos\\error", "ryunosuke\\chmonos\\error");
}

if (!isset($excluded_functions["stringify"]) && (!function_exists("ryunosuke\\chmonos\\stringify") || (!false && (new \ReflectionFunction("ryunosuke\\chmonos\\stringify"))->isInternal()))) {
    /**
     * 値を何とかして文字列化する
     *
     * この関数の出力は互換性を考慮しない。頻繁に変更される可能性がある。
     *
     * @param mixed $var 文字列化する値
     * @return string $var を文字列化したもの
     */
    function stringify($var)
    {
        $type = gettype($var);
        switch ($type) {
            case 'NULL':
                return 'null';
            case 'boolean':
                return $var ? 'true' : 'false';
            case 'array':
                return var_export2($var, true);
            case 'object':
                if (method_exists($var, '__toString')) {
                    return (string) $var;
                }
                if ($var instanceof \Serializable) {
                    return serialize($var);
                }
                if ($var instanceof \JsonSerializable) {
                    return get_class($var) . ':' . json_encode($var, JSON_UNESCAPED_UNICODE);
                }
                return get_class($var);

            default:
                return (string) $var;
        }
    }
}
if (function_exists("ryunosuke\\chmonos\\stringify") && !defined("ryunosuke\\chmonos\\stringify")) {
    define("ryunosuke\\chmonos\\stringify", "ryunosuke\\chmonos\\stringify");
}

if (!isset($excluded_functions["arrayval"]) && (!function_exists("ryunosuke\\chmonos\\arrayval") || (!false && (new \ReflectionFunction("ryunosuke\\chmonos\\arrayval"))->isInternal()))) {
    /**
     * array キャストの関数版
     *
     * intval とか strval とかの array 版。
     * ただキャストするだけだが、関数なのでコールバックとして使える。
     *
     * $recursive を true にすると再帰的に適用する（デフォルト）。
     * 入れ子オブジェクトを配列化するときなどに使える。
     *
     * Example:
     * ```php
     * // キャストなので基本的には配列化される
     * that(arrayval(123))->isSame([123]);
     * that(arrayval('str'))->isSame(['str']);
     * that(arrayval([123]))->isSame([123]); // 配列は配列のまま
     *
     * // $recursive = false にしない限り再帰的に適用される
     * $stdclass = stdclass(['key' => 'val']);
     * that(arrayval([$stdclass], true))->isSame([['key' => 'val']]); // true なので中身も配列化される
     * that(arrayval([$stdclass], false))->isSame([$stdclass]);       // false なので中身は変わらない
     * ```
     *
     * @param mixed $var array 化する値
     * @param bool $recursive 再帰的に行うなら true
     * @return array array 化した配列
     */
    function arrayval($var, $recursive = true)
    {
        // return json_decode(json_encode($var), true);

        // 無駄なループを回したくないので非再帰で配列の場合はそのまま返す
        if (!$recursive && is_array($var)) {
            return $var;
        }

        if (is_primitive($var)) {
            return (array) $var;
        }

        $result = [];
        foreach ($var as $k => $v) {
            if ($recursive && !is_primitive($v)) {
                $v = arrayval($v, $recursive);
            }
            $result[$k] = $v;
        }
        return $result;
    }
}
if (function_exists("ryunosuke\\chmonos\\arrayval") && !defined("ryunosuke\\chmonos\\arrayval")) {
    define("ryunosuke\\chmonos\\arrayval", "ryunosuke\\chmonos\\arrayval");
}

if (!isset($excluded_functions["si_prefix"]) && (!function_exists("ryunosuke\\chmonos\\si_prefix") || (!false && (new \ReflectionFunction("ryunosuke\\chmonos\\si_prefix"))->isInternal()))) {
    /**
     * 数値に SI 接頭辞を付与する
     *
     * 値は 1 <= $var < 1000(1024) の範囲内に収められる。
     * ヨクト（10^24）～ヨタ（1024）まで。整数だとしても 64bit の範囲を超えるような値の精度は保証しない。
     *
     * Example:
     * ```php
     * // シンプルに k をつける
     * that(si_prefix(12345))->isSame('12.345 k');
     * // シンプルに m をつける
     * that(si_prefix(0.012345))->isSame('12.345 m');
     * // 書式フォーマットを指定できる
     * that(si_prefix(12345, 1000, '%d%s'))->isSame('12k');
     * that(si_prefix(0.012345, 1000, '%d%s'))->isSame('12m');
     * // ファイルサイズを byte で表示する
     * that(si_prefix(12345, 1000, '%d %sbyte'))->isSame('12 kbyte');
     * // ファイルサイズを byte で表示する（1024）
     * that(si_prefix(10240, 1024, '%.3f %sbyte'))->isSame('10.000 kbyte');
     * // フォーマットに null を与えると sprintf せずに配列で返す
     * that(si_prefix(12345, 1000, null))->isSame([12.345, 'k']);
     * // フォーマットにクロージャを与えると実行して返す
     * that(si_prefix(12345, 1000, function ($v, $u) {
     *     return number_format($v, 2) . $u;
     * }))->isSame('12.35k');
     * ```
     *
     * @param mixed $var 丸める値
     * @param int $unit 桁単位。実用上は 1000, 1024 の2値しか指定することはないはず
     * @param string|\Closure $format 書式フォーマット。 null を与えると sprintf せずに配列で返す
     * @return string|array 丸めた数値と SI 接頭辞で sprintf した文字列（$format が null の場合は配列）
     */
    function si_prefix($var, $unit = 1000, $format = '%.3f %s')
    {
        assert($unit > 0);

        $result = function ($format, $var, $unit) {
            if ($format instanceof \Closure) {
                return $format($var, $unit);
            }
            if ($format === null) {
                return [$var, $unit];
            }
            return sprintf($format, $var, $unit);
        };

        if ($var == 0) {
            return $result($format, $var, '');
        }

        $original = $var;
        $var = abs($var);
        $n = 0;
        while (!(1 <= $var && $var < $unit)) {
            if ($var < 1) {
                $n--;
                $var *= $unit;
            }
            else {
                $n++;
                $var /= $unit;
            }
        }
        if (!isset(SI_UNITS[$n])) {
            throw new \InvalidArgumentException("$original is too large or small ($n).");
        }
        return $result($format, ($original > 0 ? 1 : -1) * $var, SI_UNITS[$n][0] ?? '');
    }
}
if (function_exists("ryunosuke\\chmonos\\si_prefix") && !defined("ryunosuke\\chmonos\\si_prefix")) {
    define("ryunosuke\\chmonos\\si_prefix", "ryunosuke\\chmonos\\si_prefix");
}

if (!isset($excluded_functions["is_empty"]) && (!function_exists("ryunosuke\\chmonos\\is_empty") || (!false && (new \ReflectionFunction("ryunosuke\\chmonos\\is_empty"))->isInternal()))) {
    /**
     * 値が空か検査する
     *
     * `empty` とほぼ同じ。ただし
     *
     * - string: "0"
     * - countable でない object
     * - countable である object で count() > 0
     *
     * は false 判定する。
     * ただし、 $empty_stcClass に true を指定すると「フィールドのない stdClass」も true を返すようになる。
     * これは stdClass の立ち位置はかなり特殊で「フィールドアクセスできる組み込み配列」のような扱いをされることが多いため。
     * （例えば `json_decode('{}')` は stdClass を返すが、このような状況は空判定したいことが多いだろう）。
     *
     * なお、関数の仕様上、未定義変数を true 判定することはできない。
     * 未定義変数をチェックしたい状況は大抵の場合コードが悪いが `$array['key1']['key2']` を調べたいことはある。
     * そういう時には使えない（?? する必要がある）。
     *
     * 「 `if ($var) {}` で十分なんだけど "0" が…」という状況はまれによくあるはず。
     *
     * Example:
     * ```php
     * // この辺は empty と全く同じ
     * that(is_empty(null))->isTrue();
     * that(is_empty(false))->isTrue();
     * that(is_empty(0))->isTrue();
     * that(is_empty(''))->isTrue();
     * // この辺だけが異なる
     * that(is_empty('0'))->isFalse();
     * // 第2引数に true を渡すと空の stdClass も empty 判定される
     * $stdclass = new \stdClass();
     * that(is_empty($stdclass, true))->isTrue();
     * // フィールドがあれば empty ではない
     * $stdclass->hoge = 123;
     * that(is_empty($stdclass, true))->isFalse();
     * ```
     *
     * @param mixed $var 判定する値
     * @param bool $empty_stdClass 空の stdClass を空とみなすか
     * @return bool 空なら true
     */
    function is_empty($var, $empty_stdClass = false)
    {
        // object は is_countable 次第
        if (is_object($var)) {
            // が、 stdClass だけは特別扱い（stdClass は継承もできるので、クラス名で判定する（継承していたらそれはもう stdClass ではないと思う））
            if ($empty_stdClass && get_class($var) === 'stdClass') {
                return !(array) $var;
            }
            if (is_countable($var)) {
                return !count($var);
            }
            return false;
        }

        // "0" は false
        if ($var === '0') {
            return false;
        }

        // 上記以外は empty に任せる
        return empty($var);
    }
}
if (function_exists("ryunosuke\\chmonos\\is_empty") && !defined("ryunosuke\\chmonos\\is_empty")) {
    define("ryunosuke\\chmonos\\is_empty", "ryunosuke\\chmonos\\is_empty");
}

if (!isset($excluded_functions["is_primitive"]) && (!function_exists("ryunosuke\\chmonos\\is_primitive") || (!false && (new \ReflectionFunction("ryunosuke\\chmonos\\is_primitive"))->isInternal()))) {
    /**
     * 値が複合型でないか検査する
     *
     * 「複合型」とはオブジェクトと配列のこと。
     * つまり
     *
     * - is_scalar($var) || is_null($var) || is_resource($var)
     *
     * と同義（!is_array($var) && !is_object($var) とも言える）。
     *
     * Example:
     * ```php
     * that(is_primitive(null))->isTrue();
     * that(is_primitive(false))->isTrue();
     * that(is_primitive(123))->isTrue();
     * that(is_primitive(STDIN))->isTrue();
     * that(is_primitive(new \stdClass))->isFalse();
     * that(is_primitive(['array']))->isFalse();
     * ```
     *
     * @param mixed $var 調べる値
     * @return bool 複合型なら false
     */
    function is_primitive($var)
    {
        return is_scalar($var) || is_null($var) || is_resource($var);
    }
}
if (function_exists("ryunosuke\\chmonos\\is_primitive") && !defined("ryunosuke\\chmonos\\is_primitive")) {
    define("ryunosuke\\chmonos\\is_primitive", "ryunosuke\\chmonos\\is_primitive");
}

if (!isset($excluded_functions["is_stringable"]) && (!function_exists("ryunosuke\\chmonos\\is_stringable") || (!false && (new \ReflectionFunction("ryunosuke\\chmonos\\is_stringable"))->isInternal()))) {
    /**
     * 変数が文字列化できるか調べる
     *
     * 「配列」「__toString を持たないオブジェクト」が false になる。
     * （厳密に言えば配列は "Array" になるので文字列化できるといえるがここでは考えない）。
     *
     * Example:
     * ```php
     * // こいつらは true
     * that(is_stringable(null))->isTrue();
     * that(is_stringable(true))->isTrue();
     * that(is_stringable(3.14))->isTrue();
     * that(is_stringable(STDOUT))->isTrue();
     * that(is_stringable(new \Exception()))->isTrue();
     * // こいつらは false
     * that(is_stringable(new \ArrayObject()))->isFalse();
     * that(is_stringable([1, 2, 3]))->isFalse();
     * ```
     *
     * @param mixed $var 調べる値
     * @return bool 文字列化できるなら true
     */
    function is_stringable($var)
    {
        if (is_array($var)) {
            return false;
        }
        if (is_object($var) && !method_exists($var, '__toString')) {
            return false;
        }
        return true;
    }
}
if (function_exists("ryunosuke\\chmonos\\is_stringable") && !defined("ryunosuke\\chmonos\\is_stringable")) {
    define("ryunosuke\\chmonos\\is_stringable", "ryunosuke\\chmonos\\is_stringable");
}

if (!isset($excluded_functions["is_arrayable"]) && (!function_exists("ryunosuke\\chmonos\\is_arrayable") || (!false && (new \ReflectionFunction("ryunosuke\\chmonos\\is_arrayable"))->isInternal()))) {
    /**
     * 変数が配列アクセス可能か調べる
     *
     * Example:
     * ```php
     * that(is_arrayable([]))->isTrue();
     * that(is_arrayable(new \ArrayObject()))->isTrue();
     * that(is_arrayable(new \stdClass()))->isFalse();
     * ```
     *
     * @param array $var 調べる値
     * @return bool 配列アクセス可能なら true
     */
    function is_arrayable($var)
    {
        return is_array($var) || $var instanceof \ArrayAccess;
    }
}
if (function_exists("ryunosuke\\chmonos\\is_arrayable") && !defined("ryunosuke\\chmonos\\is_arrayable")) {
    define("ryunosuke\\chmonos\\is_arrayable", "ryunosuke\\chmonos\\is_arrayable");
}

if (!isset($excluded_functions["is_countable"]) && (!function_exists("ryunosuke\\chmonos\\is_countable") || (!true && (new \ReflectionFunction("ryunosuke\\chmonos\\is_countable"))->isInternal()))) {
    /**
     * 変数が count でカウントできるか調べる
     *
     * 要するに {@link http://php.net/manual/function.is-countable.php is_countable} の polyfill。
     *
     * Example:
     * ```php
     * that(is_countable([1, 2, 3]))->isTrue();
     * that(is_countable(new \ArrayObject()))->isTrue();
     * that(is_countable((function () { yield 1; })()))->isFalse();
     * that(is_countable(1))->isFalse();
     * that(is_countable(new \stdClass()))->isFalse();
     * ```
     *
     * @polyfill
     *
     * @param mixed $var 調べる値
     * @return bool count でカウントできるなら true
     */
    function is_countable($var)
    {
        return is_array($var) || $var instanceof \Countable;
    }
}
if (function_exists("ryunosuke\\chmonos\\is_countable") && !defined("ryunosuke\\chmonos\\is_countable")) {
    define("ryunosuke\\chmonos\\is_countable", "ryunosuke\\chmonos\\is_countable");
}

if (!isset($excluded_functions["var_export2"]) && (!function_exists("ryunosuke\\chmonos\\var_export2") || (!false && (new \ReflectionFunction("ryunosuke\\chmonos\\var_export2"))->isInternal()))) {
    /**
     * 組み込みの var_export をいい感じにしたもの
     *
     * 下記の点が異なる。
     *
     * - 配列は 5.4 以降のショートシンタックス（[]）で出力
     * - インデントは 4 固定
     * - ただの配列は1行（[1, 2, 3]）でケツカンマなし、連想配列は桁合わせインデントでケツカンマあり
     * - 文字列はダブルクオート
     * - null は null（小文字）
     * - 再帰構造を渡しても警告がでない（さらに NULL ではなく `'*RECURSION*'` という文字列になる）
     * - 配列の再帰構造の出力が異なる（Example参照）
     *
     * Example:
     * ```php
     * // 単純なエクスポート
     * that(var_export2(['array' => [1, 2, 3], 'hash' => ['a' => 'A', 'b' => 'B', 'c' => 'C']], true))->isSame('[
     *     "array" => [1, 2, 3],
     *     "hash"  => [
     *         "a" => "A",
     *         "b" => "B",
     *         "c" => "C",
     *     ],
     * ]');
     * // 再帰構造を含むエクスポート（標準の var_export は形式が異なる。 var_export すれば分かる）
     * $rarray = [];
     * $rarray['a']['b']['c'] = &$rarray;
     * $robject = new \stdClass();
     * $robject->a = new \stdClass();
     * $robject->a->b = new \stdClass();
     * $robject->a->b->c = $robject;
     * that(var_export2(compact('rarray', 'robject'), true))->isSame('[
     *     "rarray"  => [
     *         "a" => [
     *             "b" => [
     *                 "c" => "*RECURSION*",
     *             ],
     *         ],
     *     ],
     *     "robject" => stdClass::__set_state([
     *         "a" => stdClass::__set_state([
     *             "b" => stdClass::__set_state([
     *                 "c" => "*RECURSION*",
     *             ]),
     *         ]),
     *     ]),
     * ]');
     * ```
     *
     * @param mixed $value 出力する値
     * @param bool $return 返すなら true 出すなら false
     * @return string|null $return=true の場合は出力せず結果を返す
     */
    function var_export2($value, $return = false)
    {
        // インデントの空白数
        $INDENT = 4;

        // 再帰用クロージャ
        $export = function ($value, $nest = 0, $parents = []) use (&$export, $INDENT) {
            // 再帰を検出したら *RECURSION* とする（処理に関しては is_recursive のコメント参照）
            foreach ($parents as $parent) {
                if ($parent === $value) {
                    return $export('*RECURSION*');
                }
            }
            // 配列は連想判定したり再帰したり色々
            if (is_array($value)) {
                $spacer1 = str_repeat(' ', ($nest + 1) * $INDENT);
                $spacer2 = str_repeat(' ', $nest * $INDENT);

                $hashed = is_hasharray($value);

                // スカラー値のみで構成されているならシンプルな再帰
                if (!$hashed && array_all($value, is_primitive)) {
                    return '[' . implode(', ', array_map($export, $value)) . ']';
                }

                // 連想配列はキーを含めて桁あわせ
                if ($hashed) {
                    $keys = array_map($export, array_combine($keys = array_keys($value), $keys));
                    $maxlen = max(array_map('strlen', $keys));
                }
                $kvl = '';
                $parents[] = $value;
                foreach ($value as $k => $v) {
                    /** @noinspection PhpUndefinedVariableInspection */
                    $keystr = $hashed ? $keys[$k] . str_repeat(' ', $maxlen - strlen($keys[$k])) . ' => ' : '';
                    $kvl .= $spacer1 . $keystr . $export($v, $nest + 1, $parents) . ",\n";
                }
                return "[\n{$kvl}{$spacer2}]";
            }
            // オブジェクトは単にプロパティを __set_state する文字列を出力する
            elseif (is_object($value)) {
                $parents[] = $value;
                return get_class($value) . '::__set_state(' . $export(get_object_properties($value), $nest, $parents) . ')';
            }
            // 文字列はダブルクオート
            elseif (is_string($value)) {
                return '"' . addcslashes($value, "\$\"\0\\") . '"';
            }
            // null は小文字で居て欲しい
            elseif (is_null($value)) {
                return 'null';
            }
            // それ以外は標準に従う
            else {
                return var_export($value, true);
            }
        };

        // 結果を返したり出力したり
        $result = $export($value);
        if ($return) {
            return $result;
        }
        echo $result, "\n";
    }
}
if (function_exists("ryunosuke\\chmonos\\var_export2") && !defined("ryunosuke\\chmonos\\var_export2")) {
    define("ryunosuke\\chmonos\\var_export2", "ryunosuke\\chmonos\\var_export2");
}

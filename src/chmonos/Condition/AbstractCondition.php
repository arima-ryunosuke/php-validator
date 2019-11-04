<?php
namespace ryunosuke\chmonos\Condition;

use ryunosuke\chmonos\Mixin\Jsonable;
use function ryunosuke\chmonos\array_each;
use function ryunosuke\chmonos\array_map_key;
use function ryunosuke\chmonos\array_unset;
use function ryunosuke\chmonos\callable_code;
use function ryunosuke\chmonos\dirmtime;
use function ryunosuke\chmonos\get_class_constants;
use function ryunosuke\chmonos\paml_import;
use function ryunosuke\chmonos\str_contains;

/**
 * 検証条件抽象クラス
 *
 * このクラスを継承して細かな条件を定義していく。
 * protected 以上のアンダースコア付きフィールドは検証パラメータとしてクライアントサイドに流れるので注意すること。
 */
abstract class AbstractCondition
{
    use Jsonable;

    public const INVALID = 'InvalidAbstract';

    /** @var array 逐次生成しなくても良いもののキャッシュ */
    private static $cache = [];

    /** @var array 名前指定時の探索名前空間とディレクトリ */
    private static $namespaces = [__NAMESPACE__ => __DIR__];

    /** @var array バリデーションメッセージのテンプレート（共通） */
    protected static $messageTemplates = [];

    /** @var array バリデーションメッセージのテンプレート（固有） */
    protected $changedMessageTemplates = [];

    /** @var array バリデーションメッセージ */
    protected $messages = [];

    /**
     * 組み込み条件以外を使用したい時に使われる任意空間を登録
     *
     * このメソッドで名前空間とディレクトリを指定すると create で生成するときに名前解決されるようになる。
     * 名前空間とディレクトリを登録するのみで、オートロードされるようになるわけではない。
     * $with_default を false にすると基底の名前空間が除外されるので完全に置き換えることができる。
     *
     * @param array $namespace_directory 名前空間とディレクトリの連想配列
     * @param bool $with_default デフォルト空間を維持するか
     * @return array 設定前の値
     */
    public static function setNamespace($namespace_directory, $with_default = true)
    {
        $current = self::$namespaces;
        self::$namespaces = $namespace_directory;
        if ($with_default) {
            self::$namespaces[__NAMESPACE__] = __DIR__;
        }
        return $current;
    }

    /**
     * 検証メッセージを一括で設定する
     *
     * 一括設定を想定しているので AbstractCondition に対してだけはグローバルで設定できる。
     * 設定する場合は outputJavascript で吐き出す前に設定すること。
     *
     * @param array $messages
     */
    public static function setMessages($messages)
    {
        $classes = [];
        foreach (self::$namespaces as $ns => $dir) {
            foreach (glob("$dir/*.php") as $file) {
                $classes[] = "$ns\\" . pathinfo($file, PATHINFO_FILENAME);
            }
        }

        foreach ($messages as $key => $message) {
            if (class_exists($key)) {
                $key::$messageTemplates = (array) $message + $key::$messageTemplates;
            }
            elseif (isset(static::$messageTemplates[$key])) {
                static::$messageTemplates[$key] = $message;
            }
            else {
                foreach ($classes as $class) {
                    if (isset($class::$messageTemplates[$key])) {
                        /** @noinspection PhpUndefinedVariableInspection */
                        $class::$messageTemplates[$key] = $message;
                        break;
                    }
                }
            }
        }
    }

    /**
     * 指定ディレクトリ以下に js ファイルを書き込む
     *
     * 配下のクラス群に更新がないときは書き込まれない。
     *
     * ディスクアクセスしまくるので、本番運用で毎回書きだすようなことはしないこと。
     * 得られた結果は動的な部分が一切無く、「変更がない」という前提なら得られたコードを保存して静的ファイルとすればよい。
     * 変更がない限り毎回書きだす必要は一切ないので、吐き出したものをリポジトリ管理で問題ない。
     *
     * @param string $outdir 書き込むディレクトリ
     * @param bool $force_flg 強制書き込みフラグ
     * @return bool 書き込まなかったら false
     */
    public static function outputJavascript($outdir, $force_flg = false)
    {
        if (!is_writable($outdir)) {
            throw new \UnexpectedValueException("'$outdir' is not writable.");
        }

        $dirtime = dirmtime($outdir);

        $v_path = "$outdir/validator.js";
        if ($force_flg || (!is_file($v_path) || $dirtime > filemtime($v_path))) {
            // Condition クラスからメタ情報を収集
            $contents = $condition = $constants = $messages = [];
            foreach (array_reverse(self::$namespaces, true) as $ns => $dir) {
                foreach (glob("$dir/*.php") as $file) {
                    /** @var static $class */
                    $name = pathinfo($file, PATHINFO_FILENAME);
                    $class = "$ns\\$name";
                    $rlass = new \ReflectionClass($class);
                    if ($rlass->isAbstract()) {
                        continue;
                    }

                    $args = ['$value', '$fields', '$params', '$consts', '$error', '$context'];

                    $block = callable_code($rlass->getMethod('validate'))[1];
                    $block = preg_replace('#(^\s*{)|}\s*$#u', '', $block);
                    $vars = array_diff(array_unique(array_column(array_filter(token_get_all("<?php $block"), function ($v) {
                        return ($v[0] ?? null) === T_VARIABLE;
                    }), 1)), $args);

                    $code = $class::getJavascriptCode();
                    $code = str_replace('@validationcode:inject', "\n" . $block, $code);
                    $code = preg_replace('#\s*<script>|</script>\s*#us', '', $code);
                    if ($vars) {
                        $code = 'var ' . implode(', ', $vars) . ";\n" . $code;
                    }
                    $code = 'function(input, ' . implode(', ', $args) . ', e) {' . trim($code) . '}';

                    $contents[$name] = $code;
                    $condition[$name] = self::literalJson($code);
                    $constants[$name] = $rlass->getConstants();
                    $messages[$name] = $class::$messageTemplates;
                }
            }

            // locutus, override, phpjs から全関数名を引っ張って・・・
            $phpjs_dir = __DIR__ . '/../../template/phpjs';
            $jsfiles = array_merge(glob("$phpjs_dir/locutus/*/*.js"), glob("$phpjs_dir/override/*.js"), glob("$outdir/phpjs/*.js"));
            $jsfiles = array_each($jsfiles, function (&$carry, $fn) {
                $carry[basename($fn, '.js')] = trim(file_get_contents($fn));
            }, []);

            // $allcode 内に含まれていたら「使っている」とみなす
            $jsfuncs = [];
            $allcode = implode(';', $contents) . file_get_contents(__DIR__ . "/../../template/validator.js");
            foreach ($jsfiles as $funcname => $jscontent) {
                if (preg_match('#' . $funcname . '\(#ms', $allcode)) {
                    $jsfuncs[$funcname] = self::literalJson($jscontent);

                    // require('../hoge/fuga') のような依存も見る（依存の依存も見ていたらキリがないので1段階のみ）
                    if (preg_match("#require\\('\.\./.+?/(.+?)'\\)#ms", $jscontent, $match)) {
                        $jsfuncs[$match[1]] = self::literalJson($jsfiles[$match[1]]);
                    }
                }
            }

            /** @noinspection PhpUnusedLocalVariableInspection,PhpDocSignatureInspection */
            $echo_function = function ($array) {
                foreach ($array as $key => $value) {
                    echo "*/var $key = this.$key = (function(){\n" . self::encodeJson($value) . "\nreturn module.exports;\n})();\n\n/*";
                }
            };
            /** @noinspection PhpUnusedLocalVariableInspection,PhpDocSignatureInspection */
            $echo_keyvalue = function ($key, $value) {
                echo "*/\nthis.$key = " . self::encodeJson($value) . ";/*\n";
            };

            ob_start();
            include __DIR__ . "/../../template/validator.js";
            file_put_contents($v_path, ob_get_clean());

            return true;
        }

        return false;
    }

    /**
     * js 用の検証コードを返す
     */
    protected static function getJavascriptCode()
    {
        // このように
        // @validationcode:inject
        // と記述した部分は共通コードに置換される
        // この場合、これしか記述がないので完全に共通コードで動作することになる
        return <<<'JS'
// @validationcode:inject
JS;
    }

    /**
     * php 用の検証コードを返す
     *
     * 返すというか、実際に実行される。
     * - validate にはクライアントサイドに渡るので書きたくない
     * - php 固有すぎてシンタックスがカバーできない
     * 場合はこのメソッドに書く。
     *
     * @param mixed $value 検証する値
     * @param array $fields 依存要素
     * @param array $params 検証パラメータ
     * @return array ローカル変数
     */
    protected static function prevalidate($value, $fields, $params)
    {
        return [];
    }

    /**
     * php/js で共通のバリデーションコードを記述する
     *
     * @param mixed $value 検証する値
     * @param array $fields 依存要素
     * @param array $params 検証パラメータ
     * @param array $consts 定数配列
     * @param \Closure $error エラー出力クロージャ
     * @param array $context 実行コンテキスト
     * @codeCoverageIgnore
     */
    protected static function validate($value, $fields, $params, $consts, $error, $context)
    {
        // ここに共通検証コードを記述する
    }

    /**
     * インストタンスを渡さなくてもいいようにするためのファクトリメソッド
     *
     * @param string $name クラス名
     * @param array $arguments コンストラクタに渡す引数配列
     * @return static
     */
    public static function create($name, $arguments = [])
    {
        if (is_string($arguments) && str_contains($arguments, ['(', ')'], false, true) !== false) {
            $name = $arguments;
            $arguments = [];
        }

        $message = [];
        $parts = explode('(', $name, 2);
        if (count($parts) === 2) {
            $message = $arguments;
            $name = trim($parts[0]);
            $arguments = paml_import(substr($parts[1], 0, -1));
        }

        $arguments = (array) $arguments;

        foreach (['' => ''] + self::$namespaces as $ns => $dir) {
            if (class_exists($class = "$ns\\$name")) {
                $args = [];
                $refparams = (new \ReflectionClass($class))->getConstructor()->getParameters();
                foreach ($refparams as $n => $refparam) {
                    $pname = $refparam->name;
                    if ($refparam->isVariadic()) {
                        break;
                    }
                    elseif (array_key_exists($n, $arguments)) {
                        $args[$n] = array_unset($arguments, $n);
                    }
                    elseif (array_key_exists($pname, $arguments)) {
                        $args[$n] = array_unset($arguments, $pname);
                    }
                    elseif ($refparam->isDefaultValueAvailable()) {
                        $args[$n] = $refparam->getDefaultValue();
                    }
                    else {
                        $n1 = $n + 1;
                        throw new \InvalidArgumentException("class $class is required parameter #$n1(\$$pname)");
                    }
                }
                /** @var self $instance */
                $instance = new $class(...array_merge($args, $arguments));
                $instance->setMessageTemplates($message);
                return $instance;
            }
        }

        throw new \InvalidArgumentException("class '$name' is not found");
    }

    public function __construct()
    {
        self::$cache['context'] = self::$cache['context'] ?? [
                'lang'       => 'php',
                'chmonos'    => new class() implements \ArrayAccess
                {
                    public function offsetExists($offset) { }

                    public function offsetGet($offset) { return $offset; }

                    public function offsetSet($offset, $value) { }

                    public function offsetUnset($offset) { }
                },
                'function'   => static function ($callback) {
                    $args = array_slice(func_get_args(), 1);
                    return function () use ($callback, $args) {
                        return $callback(...array_merge(func_get_args(), $args));
                    };
                },
                'foreach'    => static function ($array, $callback) {
                    $args = func_get_args();
                    foreach ($array as $k => $v) {
                        $args[0] = $k;
                        $args[1] = $v;
                        if ($callback(...$args) === false) {
                            return false;
                        }
                    }
                    return true;
                },
                'cast'       => static function ($type, $value) {
                    if ($type === 'array') {
                        return (array) $value;
                    }
                    throw new \InvalidArgumentException('invalid cast type');
                },
                'str_concat' => static function () {
                    return implode('', func_get_args());
                },
            ];
    }

    /**
     * 値を検証する
     *
     * @param mixed $value 検証する値自身
     * @param array $fields 依存フィールド配列
     * @return bool
     */
    public function isValid($value, $fields = [])
    {
        static $constants;
        $constants = $constants ?? get_class_constants($this);

        $params = $this->getValidationParam();
        $error = function ($messageKey, $message = null) use ($constants) {
            if (!in_array($messageKey, $constants, true)) {
                $message = $messageKey;
                $messageKey = static::INVALID;
            }
            $this->addMessage($messageKey, $message);
        };

        $this->messages = [];

        $context = self::$cache['context'] + static::prevalidate($value, $fields, $params);
        static::validate($value, $fields, $params, $constants, $error, $context);

        return !count($this->messages);
    }

    /**
     * 配列を対象にしたクラスかを返す
     *
     * @return bool
     */
    public function isArrayableValidation()
    {
        return false;
    }

    /**
     * 検証用のパラメータを返す
     *
     * 基本的にはアンダースコアのプロパティ値を返すだけ。
     * 特別なことがしたかったらオーバーライドで対応。
     *
     * @return array 検証パラメータ
     */
    public function getValidationParam()
    {
        return array_map_key(get_object_vars($this), function ($name) {
            return $name[0] === '_' ? substr($name, 1) : null;
        });
    }

    /**
     * 関連 field 名を返す
     *
     * 空なら自分自身を返す。
     * ここで返されたフィールドが isValid メソッドの引数として渡されることになる。
     */
    public function getFields()
    {
        // 基本的には空。個別はオーバーライドして対応
        return [];
    }

    /**
     * 単体メッセージ変更メソッド
     *
     * @param string $messageString エラーメッセージ
     * @param string $messageKey エラーメッセージキー
     * @return static
     */
    public function setMessageTemplate($messageString, $messageKey)
    {
        if (!isset(static::$messageTemplates[$messageKey])) {
            return $this;
        }

        $this->changedMessageTemplates[$messageKey] = $messageString;
        return $this;
    }

    /**
     * 複数メッセージ変更メソッド
     *
     * @param array $messages array(エラーメッセージキー => エラーメッセージ)
     * @return static
     */
    public function setMessageTemplates(array $messages)
    {
        foreach ($messages as $messageKey => $messageString) {
            $this->setMessageTemplate($messageString, $messageKey);
        }
        return $this;
    }

    /**
     * メッセージのもととなるテンプレートを返す
     *
     * @return array メッセージテンプレート
     */
    public function getMessageTemplates()
    {
        return $this->changedMessageTemplates;
    }

    /**
     * エラーメッセージを追加する
     *
     * @param string $messageKey
     * @param string $message
     * @return static
     */
    public function addMessage($messageKey, $message = null)
    {
        $message = $message ?? $this->changedMessageTemplates[$messageKey] ?? static::$messageTemplates[$messageKey];

        $params = $this->getValidationParam();
        $message = preg_replace_callback("#%(.+?)%#", function ($match) use ($params) {
            if (array_key_exists($match[1], $params)) {
                return is_array($params[$match[1]]) ? implode(',', $params[$match[1]]) : $params[$match[1]];
            }
            return $match[0];
        }, $message);

        $this->messages[$messageKey] = $message;
        return $this;
    }

    /**
     * 検証した結果のエラーメッセージを返す
     *
     * @return array エラーメッセージ配列
     */
    public function getMessages()
    {
        return $this->messages;
    }
}

<?php
namespace ryunosuke\chmonos;

use ryunosuke\chmonos\Exception\ValidationException;

/**
 * Context のフォームラッパークラス
 *
 * html form 的な仕事はこのクラスが行う。
 * こいつを使うことで js と php のバリデーションルールを共通化できる。
 *
 * @property-read Context $context
 * @method array getRules()
 * @method $this error($name, $message)
 * @method array getDefaults()
 * @method array getMessages()
 * @method array getFlatMessages($format = '[%s] %s', $childformat = '%s %d行目 - %s')
 * @method Input[] getAllInput()
 * @method array getFixture($defaults = [])
 */
class Form
{
    use Mixin\Htmlable;
    use Mixin\Jsonable;

    /** @var Context */
    private $context;

    /** @var string */
    private $id;

    /** @var array */
    private $options;

    /** @var array */
    private $currents = [];

    /** @var string[] */
    private $templateValues = [];

    /**
     * コンストラクタ
     *
     * ルール配列については AbstractInput のコンストラクタを参照。
     *
     * @param array $rules コンテキストのルール配列
     * @param array $options オプション配列
     */
    public function __construct(array $rules, $options = [])
    {
        $options += [
            'nonce'      => '',
            'inputClass' => Input::class,
            'vuejs'      => false,
        ];
        $this->options = $options;

        $this->context = (new Context($rules, null, $options['inputClass']))->initialize();
    }

    /**
     * input 要素があるか返す isset プロキシ
     *
     * @see Context
     * @param string $name 要素名
     * @return bool
     */
    public function __isset($name)
    {
        if ($name === 'context') {
            return false;
        }

        return isset($this->context->$name);
    }

    /**
     * input 要素を返す get プロキシ
     *
     * @see Context
     * @param string $name 要素名
     * @return Context|Input input 要素
     */
    public function __get($name)
    {
        if ($name === 'context') {
            return $this->context;
        }

        return $this->context->$name;
    }

    /**
     * context への移譲
     *
     * @see Context
     * @param string $name メソッド名
     * @param array $argument 引数
     * @return mixed
     */
    public function __call($name, $argument)
    {
        return $this->context->$name(...$argument);
    }

    private function _is_uploaded_file($filename)
    {
        // 開発中は get_included_files を「アップロードされたファイル」とみなす
        if (php_sapi_name() === 'cli') {
            if (in_array(realpath($filename), get_included_files(), true)) {
                return true;
            }
        }
        return is_uploaded_file($filename);
    }

    public function getValues($obtain_file = true)
    {
        $values = $this->context->getValues();

        if (!$obtain_file) {
            array_walk_recursive($values, function (&$value) {
                if (is_string($value) && $this->_is_uploaded_file($value)) {
                    $value = null;
                }
            });
        }

        return $values;
    }

    public function setValues(array $values)
    {
        $parseFile = function ($file) {
            $error = $file['error'];
            switch ($error) {
                case UPLOAD_ERR_OK:
                    if (!$this->_is_uploaded_file($file['tmp_name'])) {
                        throw new \UnexpectedValueException("file is not uploaded ({$file['name']})", UPLOAD_ERR_EXTENSION);
                    }
                    return $file['tmp_name'];
                case UPLOAD_ERR_NO_FILE:
                    return '';
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    throw new \UnexpectedValueException("file size too large", $error);
                default:
                    throw new \UnexpectedValueException("upload error [$error]", $error);
            }
        };
        $mergeFiles = function (Context $context, &$values, array $files) use (&$mergeFiles, $parseFile) {
            foreach ($context as $name => $input) {
                if ($input->getType() === 'file') {
                    unset($values[$name]); // POST と競合すると非常にまずいので一旦伏せる
                    if (isset($files[$name])) {
                        if (is_indexarray($files[$name])) {
                            foreach ($files[$name] as $n => $file) {
                                if (strlen($tmp_name = $parseFile($file))) {
                                    $values[$name][$n] = $tmp_name;
                                }
                            }
                        }
                        else {
                            if (strlen($tmp_name = $parseFile($files[$name]))) {
                                $values[$name] = $tmp_name;
                            }
                        }
                    }
                }

                if ($input->getType() === 'arrays') {
                    foreach ($values[$name] ?? [] as $i => $dummy) {
                        $mergeFiles($input->context, $values[$name][$i], $files[$name][$i] ?? []);
                    }
                }
            }
        };

        $mergeFiles($this->context, $values, get_uploaded_files());
        return $this->context->normalize($values);
    }

    /**
     * 値を検証せずフィルタだけ行う
     *
     * 返り値としてフィルタされた値を返す。
     *
     * @see Form::validate
     * @param array $values 検証する値
     * @return array 検証・フィルタされた値
     */
    public function filter(array $values)
    {
        $values = $this->setValues($values);
        $result = $this->context->validate($values);
        $values = $this->context->filter($values, true);
        if (!$result) {
            $this->context->clear();
        }
        return $values;
    }

    /**
     * 値を検証して bool を返す
     *
     * @see Context::validate
     * @param array $values 検証する値
     * @return bool 検証結果
     */
    public function validate(array &$values)
    {
        $values = $this->setValues($values);
        $result = $this->context->validate($values);
        $values = $this->context->filter($values, false);
        return $result;
    }

    /**
     * 値を検証してダメなら例外を投げる
     *
     * validate と違って返り値が空くので検証・フィルタされた値を返す。
     *
     * @see Form::validate
     * @param array $values 検証する値
     * @return array 検証・フィルタされた値
     */
    public function validateOrThrow(array $values)
    {
        if ($this->validate($values) === false) {
            throw new ValidationException($this, 'validation error.');
        }

        return $values;
    }

    /**
     * 自分自身のタグを描画する
     *
     * 必要な js とか属性とかも同時に吐かれる。
     * 空呼び出しで閉じタグになる。
     *
     * @param array|string $attrs 属性連想配列・CSSセレクタ文字列
     * @return string html 文字列
     */
    public function form($attrs = [])
    {
        $E = fn($v) => $v;

        $scriptAttrs = [];
        if (strlen($this->options['nonce'])) {
            $scriptAttrs['nonce'] = $this->options['nonce'];
        }

        // 引数があるなら開きタグ
        if (func_num_args() > 0) {
            $this->currents = [];
            $this->templateValues = [];

            $attrs = $this->convertHtmlAttrs($attrs);

            // ファイルを持っているなら強制的に multipart/form-data な post にする。
            if ($this->context->hasInputFile()) {
                $attrs['method'] = $attrs['method'] ?? 'post';
                $attrs['enctype'] = $attrs['enctype'] ?? 'multipart/form-data';
            }

            $attrs['id'] = $attrs['id'] ?? 'form' . spl_object_id($this);
            $attrs['class'] = concat($attrs['class'] ?? '', ' ') . 'validatable_form';
            $attrs['novalidate'] = $attrs['novalidate'] ?? true;

            $this->id = $attrs['id'];

            $jsoption = $this->encodeJson([
                'allrules' => $this->getRules(),
                'errors'   => $this->getMessages(),
            ]);

            $script = <<<JAVASCRIPT
            document.addEventListener('DOMContentLoaded', function() {
                var thisform = document.getElementById({$this->encodeJson($this->id)});
                thisform.chmonos = new Chmonos(thisform, $jsoption);
            });
            JAVASCRIPT;

            return "<form {$this->createHtmlAttr($attrs)}><script {$this->createHtmlAttr($scriptAttrs)}>$script</script>";
        }
        // 閉じタグ
        else {
            $script = <<<JAVASCRIPT
            document.addEventListener('DOMContentLoaded', function() {
                var thisform = document.getElementById({$this->encodeJson($this->id)});
                {$E($this->options['vuejs'] ? "thisform.chmonos.data = {$this->encodeJson($this->getValues(false))};" : "")}
                {$E($this->options['vuejs'] ? "thisform.chmonos.defaults = {$this->encodeJson($this->getDefaults())};" : "")}
                {$E($this->options['vuejs'] ? '' : "thisform.chmonos.initialize({$this->encodeJson($this->templateValues)});")}
            });
            JAVASCRIPT;

            return "<script {$this->createHtmlAttr($scriptAttrs)}>$script</script></form>";
        }
    }

    public function open($attrs)
    {
        return $this->form($attrs);
    }

    public function close()
    {
        return $this->form();
    }

    public function context($name = null, $index = null)
    {
        if (func_num_args() > 0) {
            $this->currents[] = [$name, $index];
        }
        else {
            array_pop($this->currents);
        }
    }

    public function template($name = null)
    {
        if (func_num_args() > 0) {
            $this->currents[] = [$name];

            $attrs = [
                'type'                => 'text/x-template',
                'data-vtemplate-name' => $name,
            ];
            if (strlen($this->options['nonce'])) {
                $attrs['nonce'] = $this->options['nonce'];
            }
            return "<script {$this->createHtmlAttr($attrs)}>\n";
        }
        else {
            [$name] = array_pop($this->currents);

            $this->templateValues[$name] = $this->getValues(false)[$name] ?? [];

            return "</script>";
        }
    }

    public function vuefor($name = null, $child = null, $index = null)
    {
        if (!$this->options['vuejs']) {
            throw new \UnexpectedValueException('vuejs flag is false');
        }
        if (func_num_args() > 0) {
            $this->currents[] = [$name, $index, $child];
        }
        else {
            array_pop($this->currents);
        }
    }

    /**
     * UI ラベルの描画
     *
     * @param string $name 要素名
     * @param array|string $attrs 属性連想配列・CSSセレクタ文字列
     * @return string html 文字列
     */
    public function label($name, $attrs = [])
    {
        return $this->_ui($name, $attrs)->label($attrs);
    }

    /**
     * UI インプットの描画
     *
     * @param string $name 要素名
     * @param array|string $attrs 属性連想配列・CSSセレクタ文字列
     * @return string html 文字列
     */
    public function input($name, $attrs = [])
    {
        return $this->_ui($name, $attrs)->input($attrs);
    }

    protected function _ui(string $name, array &$attrs)
    {
        $attrs = $this->convertHtmlAttrs($attrs);
        $attrs['vuejs'] ??= $this->options['vuejs'];

        if ($this->currents) {
            [$cname, $cindex, $child] = last_value($this->currents) + [null, null, null];
            $name = "$cname/$name";
            $attrs['index'] = $cindex;
            $attrs['child'] = $child;
        }
        /** @var Input $input */
        $input = $this;
        foreach (explode('/', $name) as $key) {
            $input = $input->context->$key;
        }
        return $input;
    }
}

<?php
namespace ryunosuke\chmonos\Condition;

use function ryunosuke\chmonos\first_value;
use function ryunosuke\chmonos\get_uploaded_files;

/**
 * Ajax バリデータ
 *
 * 検証 URL とクロージャを渡して ajax な検証を行う。
 * js だけではどうしても行えない検証（メアドの重複チェックとか排他ロックとか）が行える。
 *
 * - url: string
 *   - ajax 先の URL。空文字なら js 検証を行わない（=サーバーサイドのみの検証に利用できる）
 * - fields: array
 *   - 依存フィールド。ajax の際にこれらも引き連れてリクエストされる
 * - method: callable
 *   - 実際の処理。 js ではなく php レイヤの検証のとき実行される
 *   - 引数として配列を受け取り、戻り値としてエラーメッセージを返さなければならない。エラーがない場合は null を返す
 *   - オプショナルであり完全外部 API に投げる場合などは null で構わない
 */
class Ajax extends AbstractCondition
{
    public const INVALID = 'AjaxInvalid';

    protected static $messageTemplates = [
        // ajax の返り値でエラーメッセージが確定されるのでなし
    ];

    protected $_request;
    protected $_fields;
    protected $_method;

    public function __construct($request, $fields = [], $method = null)
    {
        if (is_string($request)) {
            $request = ['url' => $request];
        }
        $request += [
            'api'         => 'xhr',  // xhr, fetch, other window.foo
            'url'         => '',
            'method'      => 'POST',
            'headers'     => [
                'X-Requested-With' => 'XMLHttpRequest',
            ],
            'cache'       => 'no-cache',
            'credentials' => 'same-origin',
            'timeout'     => null,
            'handler'     => null,   // response handler
        ];
        $this->_request = $request;
        $this->_fields = $fields;
        $this->_method = $method;

        parent::__construct();
    }

    public function getFields()
    {
        return $this->_fields;
    }

    public static function getJavascriptCode()
    {
        /** @noinspection JSUnresolvedFunction */
        return <<<'JS'
            (function() {
                if ($value && $params.request.url) {
                    function request() {
                        var formdata = new FormData();
                        formdata.append(input.name, $value);
                        var keys = Object.keys($fields);
                        for (var i = 0; i < keys.length; i++) {
                            formdata.append(keys[i], $fields[keys[i]]);
                        }
    
                        var handler = $params.request.handler || function(response){return response};
                        if ($params.request.api === 'xhr') {
                            return new Promise(function(resolve, reject) {
                                var xhr = new XMLHttpRequest();
                                xhr.open($params.request.method, $params.request.url);
                                Object.keys($params.request.headers).forEach(function(header) {
                                    xhr.setRequestHeader(header, $params.request.headers[header]);
                                });
                                xhr.responseType = 'json';
                                xhr.withCredentials = $params.request.credentials !== 'omit';
                                xhr.timeout = $params.request.timeout || 0;
                                xhr.addEventListener('load', function(e) {
                                    if (this.status !== 200){
                                        console.log(e);
                                        return;
                                    }
                                    $error(handler(this.response));
                                    resolve();
                                });
                                xhr.addEventListener('error', function(e) {
                                    reject(e);
                                });
                                xhr.send(formdata);
                            });
                        }
                        if ($params.request.api === 'fetch') {
                            var request = new Request($params.request.url, Object.assign({body: formdata}, $params.request));
                            return window.fetch(request).then(function(response) {
                                if (!response.ok) {
                                    throw response;
                                }
                                return response.json();
                            }).then(function(json){
                                $error(handler(json));
                            }).catch(function(e) {
                                console.log(e);
                            });
                        }
                        
                        return new Promise(function(resolve, reject) {
                            window[$params.request.api](Object.assign({body: formdata}, $params.request)).then(function(response) {
                                $error(response);
                                resolve();
                            }).catch(function(e) {
                                reject(e);
                            });
                        });
                    }

                    if (e.type === 'submit') {
                        if (input.validationErrors) {
                            $error();
                            input.validationAjaxStop = false;
                        }
                        else {
                            if (!input.validationAjaxStop) {
                                $error(request());
                            }
                            input.validationAjaxStop = true;
                        }
                    }
                    else {
                        if (!input.validationAjaxDebounce) {
                            $error(request());
                            input.validationAjaxDebounce = setTimeout(function() {
                                input.validationAjaxDebounce = null;
                            }, 1500);
                        }
                        else {
                            clearTimeout(input.validationAjaxDebounce);
                            $error(new Promise(function (resolve) {
                                input.validationAjaxDebounce = setTimeout(function() {
                                    request().then(resolve);
                                    input.validationAjaxDebounce = null;
                                }, 1000);
                            }));
                        }
                    }
                }
            })();
JS;
    }

    public static function validate($value, $fields, $params, $consts, $error, $context)
    {
        if (!$params['method']) {
            return;
        }
        $message = call_user_func($params['method'], $value, $fields);

        if ($message !== null) {
            $error($message);
        }
    }

    /**
     * ajax 用のレスポンスデータを返す
     *
     * コントローラ内でこのメソッドを呼んで結果を json で返すような使い方になる。
     *
     * @param array $fields 依存データ。未指定時はよしなに
     * @return array isvalid フラグと message 配列
     */
    public function response($fields = null)
    {
        if (!$this->_method) {
            return null;
        }
        if ($fields === null) {
            if (strtoupper($this->_request['method']) === 'GET') {
                $fields = $_GET;
            }
            if (strtoupper($this->_request['method']) === 'POST') {
                $fields = get_uploaded_files() + $_POST;
            }
        }
        $value = first_value(array_diff_key($fields, array_flip($this->_fields)));
        if (is_array($value) && count(array_intersect_key($value, array_flip(['name', 'type', 'tmp_name', 'error', 'size']))) === 5) {
            $value = $value['tmp_name'];
        }

        $this->isValid($value, $fields);
        return $this->getMessages() ?: null;
    }
}

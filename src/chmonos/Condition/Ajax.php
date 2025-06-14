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
 *   - オプショナルであり完全外部 API に投げる場合などは null で構わない（=クライアントサイドのみの検証に利用できる）
 */
class Ajax extends AbstractCondition
{
    public const INVALID = 'AjaxInvalid';

    protected static $messageTemplates = [
        self::INVALID => 'invalid',
    ];

    protected $_request;
    protected $_fields;
    protected $_method;

    public function __construct($request, $fields = [], $method = null)
    {
        if (is_string($request)) {
            $request = [
                'url'    => $request,
                'method' => 'GET',
            ];
        }
        $request += [
            'api'         => 'xhr',  // xhr, fetch, other window.foo
            'url'         => '',
            'method'      => 'POST',
            'headers'     => [
                'X-Requested-With' => 'XMLHttpRequest',
            ],
            'cache'       => 'no-cache',
            'expire'      => 60,
            'credentials' => 'same-origin',
            'timeout'     => null,
            'handler'     => null,   // response handler
        ];
        $request['method'] = strtoupper($request['method']);

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
        /** @noinspection JSValidateTypes */
        return <<<'JS'
            (function() {
                $params.request.caches = $params.request.caches ?? {};
                if ($value && $params.request.url) {
                    function request() {
                        var url = new URL($params.request.url, location.href);
                        var formdata = undefined;
                        var body = $params.request.method === 'GET' ? url.searchParams : formdata = new FormData();

                        body.append(input.name, $value);
                        var keys = Object.keys($fields);
                        for (var i = 0; i < keys.length; i++) {
                            body.append(keys[i], $fields[keys[i]]);
                        }

                        if ($params.request.method === 'GET') {
                            if ($params.request.caches[url]) {
                                if ($params.request.caches[url].expire > (new Date()).getTime()) {
                                    $error($params.request.caches[url].data);
                                    return Promise.resolve(null);
                                }
                                else {
                                    delete $params.request.caches[url];
                                }
                            }
                        }

                        var handler = function (response) {
                            var data = ($params.request.handler || function(response){return response})(response);
                            if ($params.request.method === 'GET') {
                                $params.request.caches[url] = {
                                    expire: (new Date()).getTime() + $params.request.expire * 1000,
                                    data: data,
                                };
                            }
                            return data;
                        };

                        if ($params.request.api === 'xhr') {
                            return new Promise(function(resolve, reject) {
                                var xhr = new XMLHttpRequest();
                                xhr.open($params.request.method, url);
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
                            return window.fetch(url, Object.assign({body: formdata}, $params.request)).then(function(response) {
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
                            window[$params.request.api](Object.assign({url: url, body: formdata}, $params.request)).then(function(response) {
                                $error(response);
                                resolve();
                            }).catch(function(e) {
                                reject(e);
                            });
                        });
                    }

                    if (e.type === 'submit') {
                        $error(request());
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
            $error($message, []);
        }
    }

    /**
     * ajax 用のレスポンスデータを返す
     *
     * コントローラ内でこのメソッドを呼んで結果を json で返すような使い方になる。
     *
     * @param array|null $fields 依存データ。未指定時はよしなに
     * @return array isvalid フラグと message 配列
     */
    public function response($fields = null)
    {
        if (!$this->_method) {
            return null;
        }
        if ($fields === null) {
            if ($this->_request['method'] === 'GET') {
                parse_str(parse_url($this->_request['url'], PHP_URL_QUERY) ?? '', $query);
                $fields = array_diff_key($_GET, $query);
            }
            if ($this->_request['method'] === 'POST') {
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

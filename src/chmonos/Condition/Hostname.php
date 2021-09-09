<?php
namespace ryunosuke\chmonos\Condition;

use ryunosuke\chmonos\Condition\Interfaces;

/**
 * ホスト名バリデータ
 *
 * - types: array
 *   - 許可する形式を指定
 *   - ''(普通のホスト名), 'cidr'(CIDR形式), '4'(IPv4形式), '6'(IPv6形式) が指定可能
 * - require_port: ?bool
 *   - ポート番号を許容するか
 *   - true: ポート必須, false: ポート不要, null: どちらでも良い
 */
class Hostname extends AbstractCondition implements Interfaces\ImeMode
{
    public const INVALID      = 'InvalidHostname';
    public const INVALID_PORT = 'InvalidHostnamePort';

    protected static $messageTemplates = [
        self::INVALID      => 'ホスト名を正しく入力してください',
        self::INVALID_PORT => 'ポート番号を正しく入力してください',
    ];

    protected $_types;
    protected $_require_port;

    public function __construct($types = '', $require_port = false)
    {
        $this->_types = (array) $types;
        $this->_require_port = $require_port;

        parent::__construct();
    }

    public static function validate($value, $fields, $params, $consts, $error, $context)
    {
        $checkport = function ($port, $require_port, $error, $consts) {
            if (strlen($port)) {
                if ($require_port === false) {
                    $error($consts['INVALID']);
                }
                if ($port > 65535) {
                    $error($consts['INVALID_PORT']);
                }
            }
            else {
                if ($require_port === true) {
                    $error($consts['INVALID_PORT']);
                }
            }
        };

        $matches = [];

        if (in_array('', $params['types']) && preg_match('#^(([a-z0-9])|([a-z0-9][a-z0-9-]{0,61}[a-z0-9])|((([a-z0-9])|([a-z0-9][a-z0-9-]{0,61}[a-z0-9]))\\.)+[a-z]+)(:([1-9][0-9]{0,4}))?$#i', $value, $matches)) {
            $checkport(isset($matches[9]) ? $matches[9] : '', $params['require_port'], $error, $consts);
            return;
        }
        if (in_array('cidr', $params['types']) && preg_match('#^(?:25[0-5]|2[0-4]\\d|1\\d\\d|\\d\\d|\\d)(?:\.(?:25[0-5]|2[0-4]\\d|1\\d\\d|\\d\\d|\\d)){3}/([0-9]|[1-2][0-9]|3[0-2])(:([1-9][0-9]{0,4}))?$#i', $value, $matches)) {
            $checkport(isset($matches[3]) ? $matches[3] : '', $params['require_port'], $error, $consts);
            return;
        }
        if (in_array(4, $params['types']) && preg_match('#^(?:25[0-5]|2[0-4]\\d|1\\d\\d|\\d\\d|\\d)(?:\.(?:25[0-5]|2[0-4]\\d|1\\d\\d|\\d\\d|\\d)){3}(:([1-9][0-9]{0,4}))?$#i', $value, $matches)) {
            $checkport(isset($matches[2]) ? $matches[2] : '', $params['require_port'], $error, $consts);
            return;
        }
        if (in_array(6, $params['types']) && preg_match('#^::$#i', $value, $matches)) {
            return;
        }

        $error($consts['INVALID']);
    }

    public function getImeMode()
    {
        return Interfaces\ImeMode::DISABLED;
    }
}

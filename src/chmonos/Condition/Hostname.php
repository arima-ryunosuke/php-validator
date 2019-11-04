<?php
namespace ryunosuke\chmonos\Condition;

use ryunosuke\chmonos\Condition\Interfaces;

/**
 * ホスト名バリデータ
 *
 * - types: array
 *   - 許可する形式を指定
 *   - ''(普通のホスト名), 'cidr'(CIDR形式), '4'(IPv4形式), '6'(IPv6形式) が指定可能
 */
class Hostname extends AbstractCondition implements Interfaces\ImeMode
{
    public const INVALID = 'InvalidHostname';

    protected static $messageTemplates = [
        self::INVALID => 'ホスト名を正しく入力してください',
    ];

    protected $_types;

    public function __construct($types = '')
    {
        $this->_types = (array) $types;

        parent::__construct();
    }

    public static function validate($value, $fields, $params, $consts, $error, $context)
    {
        if (in_array('', $params['types']) && preg_match('#^(([a-z0-9])|([a-z0-9][a-z0-9-]{0,61}[a-z0-9])|((([a-z0-9])|([a-z0-9][a-z0-9-]{0,61}[a-z0-9]))\\.)+[a-z]+)$#i', $value)) {
            return;
        }
        if (in_array('cidr', $params['types']) && preg_match('#^(?:25[0-5]|2[0-4]\\d|1\\d\\d|\\d\\d|\\d)(?:\.(?:25[0-5]|2[0-4]\\d|1\\d\\d|\\d\\d|\\d)){3}/([0-9]|[1-2][0-9]|3[0-2])$#i', $value)) {
            return;
        }
        if (in_array(4, $params['types']) && preg_match('#^(?:25[0-5]|2[0-4]\\d|1\\d\\d|\\d\\d|\\d)(?:\.(?:25[0-5]|2[0-4]\\d|1\\d\\d|\\d\\d|\\d)){3}$#i', $value)) {
            return;
        }
        if (in_array(6, $params['types']) && preg_match('#^::$#i', $value)) {
            return;
        }

        $error($consts['INVALID']);
    }

    public function getImeMode()
    {
        return Interfaces\ImeMode::DISABLED;
    }
}

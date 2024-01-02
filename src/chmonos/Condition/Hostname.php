<?php
namespace ryunosuke\chmonos\Condition;

/**
 * ホスト名バリデータ
 *
 * - types: array
 *   - 許可する形式を指定
 *   - ''(普通のホスト名), 'cidr'(CIDR形式), '4'(IPv4形式), '6'(IPv6形式) が指定可能
 * - require_port: ?bool
 *   - ポート番号を許容するか
 *   - true: ポート必須, false: ポート不要, null: どちらでも良い
 * - delimiter: ?string
 *   - 非 null を渡すと複数値が許容され、指定文字がデリミタ（正規表現）として使用される
 *   - どのような文字を渡しても空白文字は取り除かれる（"," と ", " は実質同じ意味になる）
 */
class Hostname extends AbstractCondition implements Interfaces\ImeMode, Interfaces\MultipleValue
{
    public const INVALID      = 'InvalidHostname';
    public const INVALID_PORT = 'InvalidHostnamePort';

    protected static $messageTemplates = [
        self::INVALID      => 'ホスト名を正しく入力してください',
        self::INVALID_PORT => 'ポート番号を正しく入力してください',
    ];

    protected $_types;
    protected $_require_port;
    protected $_delimiter;

    public function __construct($types = '', $require_port = false, $delimiter = null)
    {
        $this->_types = (array) $types;
        $this->_require_port = $require_port;
        $this->_delimiter = $delimiter;

        parent::__construct();
    }

    public static function validate($value, $fields, $params, $consts, $error, $context)
    {
        $checkport = function ($port, $require_port, $error, $consts) {
            if (strlen($port)) {
                if ($require_port === false) {
                    $error($consts['INVALID']);
                    return false;
                }
                if ($port > 65535) {
                    $error($consts['INVALID_PORT']);
                    return false;
                }
            }
            else {
                if ($require_port === true) {
                    $error($consts['INVALID_PORT']);
                    return false;
                }
            }
            return true;
        };

        if ($params['delimiter'] === null) {
            $value = $context['cast']('array', $value);
        }
        else {
            $value = preg_split($params['delimiter'], $value, -1, PREG_SPLIT_NO_EMPTY);
        }

        $context['foreach']($value, function ($key, $value, $params, $checkport, $error, $consts) {
            $matches = [];

            if (in_array('', $params['types']) && preg_match('#^(([a-z0-9])|([a-z0-9][a-z0-9-]{0,61}[a-z0-9])|((([a-z0-9])|([a-z0-9][a-z0-9-]{0,61}[a-z0-9]))\\.)+[a-z]+)(:([1-9][0-9]{0,4}))?$#i', $value, $matches)) {
                return $checkport(isset($matches[9]) ? $matches[9] : '', $params['require_port'], $error, $consts);
            }
            if (in_array('cidr', $params['types']) && preg_match('#^(?:25[0-5]|2[0-4]\\d|1\\d\\d|\\d\\d|\\d)(?:\.(?:25[0-5]|2[0-4]\\d|1\\d\\d|\\d\\d|\\d)){3}/([0-9]|[1-2][0-9]|3[0-2])(:([1-9][0-9]{0,4}))?$#i', $value, $matches)) {
                return $checkport(isset($matches[3]) ? $matches[3] : '', $params['require_port'], $error, $consts);
            }
            if (in_array(4, $params['types']) && preg_match('#^(?:25[0-5]|2[0-4]\\d|1\\d\\d|\\d\\d|\\d)(?:\.(?:25[0-5]|2[0-4]\\d|1\\d\\d|\\d\\d|\\d)){3}(:([1-9][0-9]{0,4}))?$#i', $value, $matches)) {
                return $checkport(isset($matches[2]) ? $matches[2] : '', $params['require_port'], $error, $consts);
            }
            if (in_array(6, $params['types']) && preg_match('#^::$#i', $value, $matches)) {
                return true;
            }

            $error($consts['INVALID']);
            return false;
        }, $params, $checkport, $error, $consts);
    }

    public function getImeMode()
    {
        return Interfaces\ImeMode::DISABLED;
    }

    public function getDelimiter()
    {
        return $this->_delimiter;
    }

    public function getFixture($value, $fields)
    {
        $type = (string) $this->fixtureArray($this->_types);
        if ($type === '') {
            $value = "h" . $this->fixtureString(4) . ".example.jp";
        }
        elseif ($type === 'cidr') {
            $value = "192.168." . $this->fixtureInt(0, 255) . '.' . $this->fixtureInt(0, 255) . '/16';
        }
        elseif ($type === '4') {
            $value = "192.168." . $this->fixtureInt(0, 255) . '.' . $this->fixtureInt(0, 255);
        }
        elseif ($type === '6') {
            $value = "2001:db8::" . dechex($this->fixtureInt(0, 255));
        }

        if ($this->_require_port === true || ($this->_require_port !== false && $this->fixtureBool())) {
            if ($type === '6') {
                $value = "[$value]:" . $this->fixtureInt(1, 65535);
            }
            else {
                $value .= ':' . $this->fixtureInt(1, 65535);
            }
        }

        return $value;
    }
}

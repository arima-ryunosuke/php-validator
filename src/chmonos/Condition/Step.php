<?php
namespace ryunosuke\chmonos\Condition;

/**
 * 倍数バリデータ
 *
 * 倍数を指定して検証する。
 *
 * - step: float
 *   - 倍数。例えば 0.5 を与えると 0.5,1.0,1.5 などしか許容されないようになる
 * - timeunit: array|string
 *   - 時刻モード指定兼単位文字列。現状の仕様だと単位文字列をロケール指定できないので引数で与えるようにしてある
 *   - とはいえ煩雑なので文字列で ja-jp のようなロケール文字列を与えるとそれっぽくなるようにしてある（ja-jp のみ対応）
 *   - h,i を与えると hh:mm, i,s を与えると mm:ss の文字列はそれっぽくパースされる
 */
class Step extends AbstractCondition implements Interfaces\InferableType, Interfaces\Range
{
    public const INVALID      = 'StepInvalid';
    public const INVALID_STEP = 'StepInvalidInt';
    public const INVALID_TIME = 'StepInvalidTime';

    protected static $messageTemplates = [
        self::INVALID      => 'Invalid value given',
        self::INVALID_STEP => '${_step}の倍数で入力してください',
        self::INVALID_TIME => '${_timemessage}単位で入力してください',
    ];

    protected $_step;
    protected $_timeunit;
    protected $_timemessage;

    public function __construct($step, $timeunit = [])
    {
        if ($step <= 0) {
            throw new \InvalidArgumentException('$step must be positive number.');
        }

        if ($timeunit === 'ja-jp') {
            $timeunit = ['h' => '時', 'i' => '分', 's' => '秒'];
        }

        $this->_step = $step;
        $this->_timeunit = $timeunit;

        if ($this->_timeunit) {
            if (is_int($dec = ($step / 60 / 60))) {
                $this->_timemessage = "$dec{$this->_timeunit['h']}";
            }
            elseif (is_int($dec = ($step / 60))) {
                $this->_timemessage = "$dec{$this->_timeunit['i']}";
            }
            else {
                $this->_timemessage = "$step{$this->_timeunit['s']}";
            }
        }

        parent::__construct();
    }

    public static function validate($value, $fields, $params, $consts, $error, $context)
    {
        $match = [];
        if (isset($params['timeunit']['h']) && isset($params['timeunit']['i'])) {
            if (!preg_match('#(\\d{1,2}):?(\\d{1,2})(:?(\\d{1,2}))?$#u', $value, $match)) {
                return $error($consts['INVALID'], []);
            }
            $value = (3600 * $match[1]) + (60 * $match[2]) + intval($match[4] ?? 0);
        }
        elseif (isset($params['timeunit']['i']) && isset($params['timeunit']['s'])) {
            if (!preg_match('#(\\d{1,2}):?(\\d{1,2})$#u', $value, $match)) {
                return $error($consts['INVALID'], []);
            }
            $value = (60 * $match[1]) + intval($match[2] ?? 0);
        }
        else {
            if (!preg_match('#^-?([1-9]\\d*|0)(\\.\\d+)?$#u', $value)) {
                return $error($consts['INVALID'], []);
            }
        }
        if (abs(round($value / $params['step']) * $params['step'] - $value) > pow(2, -52)) {
            if (count($params['timeunit'])) {
                $error($consts['INVALID_TIME'], []);
            }
            else {
                $error($consts['INVALID_STEP'], []);
            }
        }
    }

    public function getMin()
    {
        return null;
    }

    public function getMax()
    {
        return null;
    }

    public function getStep()
    {
        return $this->_step;
    }

    public function getType()
    {
        return 'number';
    }

    public function getFixture($value, $fields)
    {
        if ($this->_timeunit) {
            $timestamp = ((int) (rand() / $this->_step)) * $this->_step;
            return (new \DateTime("@$timestamp"))->format(implode(':', array_keys($this->_timeunit)));
        }
        return sprintf('%g', ((int) ((float) ($value) / $this->_step)) * $this->_step);
    }
}

<?php
namespace ryunosuke\chmonos\Condition;

/**
 * 日付バリデータ
 *
 * format には js の兼ね合いで実質的に YmdHis のみ。
 * 「js で引っかからないけど php で引っかかる」が許容出来るなら使ってもいい。
 *
 * - format: string
 *   - 許容する日付フォーマット
 */
class Date extends AbstractCondition implements Interfaces\Range, Interfaces\MaxLength, Interfaces\ImeMode, Interfaces\ConvertibleValue
{
    public const INVALID      = 'dateInvalid';
    public const INVALID_DATE = 'dateInvalidDate';
    public const FALSEFORMAT  = 'dateFalseFormat';

    protected static $messageTemplates = [
        self::INVALID      => 'Invalid value given',
        self::INVALID_DATE => '有効な日付を入力してください',
        self::FALSEFORMAT  => '%format%形式で入力してください',
    ];

    protected $_format;
    protected $_member;
    protected $_isRFC3339; // TZ は除く（実質的に type=date に適合するか？ を表す）

    public function __construct($format)
    {
        $this->_format = $format;

        // （よほど変なフォーマットじゃない限り）各要素にユニークな値を入れて date すれば「どの要素があるか？」が取得できる
        $YmdHis = date($this->_format, strtotime('2006-01-02 15:04:05'));
        $this->_member = [
            'Y' => strpos($YmdHis, '2006') !== false,
            'm' => strpos($YmdHis, '01') !== false,
            'd' => strpos($YmdHis, '02') !== false,
            'H' => strpos($YmdHis, '15') !== false,
            'i' => strpos($YmdHis, '04') !== false,
            's' => strpos($YmdHis, '05') !== false,
        ];

        $this->_isRFC3339 = false;
        if ($this->_member['Y'] && $this->_member['m'] && $this->_member['d'] && $this->_member['H'] && $this->_member['i'] && $this->_member['s']) {
            $this->_isRFC3339 = $YmdHis === '2006-01-02T15:04:05';
        }
        elseif ($this->_member['Y'] && $this->_member['m'] && $this->_member['d'] && $this->_member['H'] && $this->_member['i']) {
            $this->_isRFC3339 = $YmdHis === '2006-01-02T15:04';
        }
        elseif ($this->_member['Y'] && $this->_member['m'] && $this->_member['d']) {
            $this->_isRFC3339 = $YmdHis === '2006-01-02';
        }
        elseif ($this->_member['Y'] && $this->_member['m']) {
            $this->_isRFC3339 = $YmdHis === '2006-01';
        }
        elseif (!$this->_member['Y'] && !$this->_member['m'] && !$this->_member['d']) {
            $this->_isRFC3339 = $YmdHis === '15:04:05';
        }

        parent::__construct();
    }

    public static function validate($value, $fields, $params, $consts, $error, $context)
    {
        $time = strtotime($value);

        // 時刻のみの場合を考慮して年月日を付加して再チャレンジ
        if ($time === false) {
            $time = strtotime($context['str_concat']('2000/10/10 ', $value));
        }

        if ($time === false) {
            $error($consts['INVALID_DATE']);
        }
        else if (date($params['format'], $time) !== $value) {
            $error($consts['FALSEFORMAT']);
        }
    }

    public function getMin()
    {
        if (!$this->_isRFC3339) {
            return null;
        }

        if ($this->_member['Y'] && $this->_member['m'] && $this->_member['d'] && $this->_member['H'] && $this->_member['i'] && $this->_member['s']) {
            return date($this->_format, strtotime('1000-01-01T00:00:00'));
        }
        if ($this->_member['Y'] && $this->_member['m'] && $this->_member['d'] && $this->_member['H'] && $this->_member['i']) {
            return date($this->_format, strtotime('1000-01-01T00:00'));
        }
        if ($this->_member['Y'] && $this->_member['m'] && $this->_member['d']) {
            return date($this->_format, strtotime('1000-01-01'));
        }
        if ($this->_member['Y'] && $this->_member['m']) {
            return date($this->_format, strtotime('1000-01'));
        }
    }

    public function getMax()
    {
        if (!$this->_isRFC3339) {
            return null;
        }

        if ($this->_member['Y'] && $this->_member['m'] && $this->_member['d'] && $this->_member['H'] && $this->_member['i'] && $this->_member['s']) {
            return date($this->_format, strtotime('9999-12-31T23:59:59'));
        }
        if ($this->_member['Y'] && $this->_member['m'] && $this->_member['d'] && $this->_member['H'] && $this->_member['i']) {
            return date($this->_format, strtotime('9999-12-31T23:59'));
        }
        if ($this->_member['Y'] && $this->_member['m'] && $this->_member['d']) {
            return date($this->_format, strtotime('9999-12-31'));
        }
        if ($this->_member['Y'] && $this->_member['m']) {
            return date($this->_format, strtotime('9999-12'));
        }
    }

    public function getStep()
    {
        if (!$this->_isRFC3339) {
            return null;
        }

        if ($this->_member['s']) {
            return '1';
        }
    }

    public function getMaxLength()
    {
        // 指定フォーマットで発生しうる最大の日付文字長を返す
        return strlen(date($this->_format, strtotime('2010/10/10 10:10:10')));
    }

    public function getImeMode()
    {
        return Interfaces\ImeMode::DISABLED;
    }

    public function getType()
    {
        // @task InferableType を implement してないので有効になっていない
        // type=date,datetime は format をパースしなければならないし、そもそもブラウザ対応状況が劣悪なので text
        return 'text';
    }

    public function getValue($value)
    {
        if ($value instanceof \DateTimeInterface) {
            return $value->format($this->_format);
        }
        if (ctype_digit("$value")) {
            return date($this->_format, $value);
        }
        if (is_string($value) && ($time = strtotime($value)) !== false && date_create_from_format($this->_format, $value) === false) {
            return date($this->_format, $time);
        }
        return $value;
    }
}

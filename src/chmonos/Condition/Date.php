<?php
namespace ryunosuke\chmonos\Condition;

use ryunosuke\chmonos\Condition\Interfaces;

/**
 * 日付バリデータ
 *
 * format には js の兼ね合いで実質的に YmdHis のみ。
 * 「js で引っかからないけど php で引っかかる」が許容出来るなら使ってもいい。
 *
 * - format: string
 *   - 許容する日付フォーマット
 */
class Date extends AbstractCondition implements Interfaces\MaxLength, Interfaces\ImeMode
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

    public function __construct($format)
    {
        $this->_format = $format;

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
}

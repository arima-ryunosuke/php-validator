<?php
namespace ryunosuke\chmonos\Condition;

/**
 * 複数行バリデータ
 *
 * 行数・桁数を検証する。
 *
 * - min_row: int
 *   - 最小行数
 * - max_row: int
 *   - 最大行数
 * - min_col: int
 *   - 最小桁数
 * - max_col: int
 *   - 最大桁数
 * - col_unit: string(byte, length, width)
 *   - 桁の単位
 */
class MultiLine extends AbstractCondition implements Interfaces\InferableType
{
    public const INVALID           = 'MultiLineInvalid';
    public const INVALID_COUNTROW  = 'MultiLineInvalidCountRow';
    public const INVALID_MINROW    = 'MultiLineInvalidMinRow';
    public const INVALID_MAXROW    = 'MultiLineInvalidMaxRow';
    public const INVALID_MINMAXROW = 'MultiLineInvalidMinMaxRow';
    public const INVALID_COUNTCOL  = 'MultiLineInvalidCountCol';
    public const INVALID_MINCOL    = 'MultiLineInvalidMinCol';
    public const INVALID_MAXCOL    = 'MultiLineInvalidMaxCol';
    public const INVALID_MINMAXCOL = 'MultiLineInvalidMinMaxCol';

    protected static $messageTemplates = [
        self::INVALID           => 'Invalid value given',
        self::INVALID_COUNTROW  => '${_min_row}行で入力して下さい',
        self::INVALID_MINROW    => '${_min_row}行以上で入力して下さい',
        self::INVALID_MAXROW    => '${_max_row}行以下で入力して下さい',
        self::INVALID_MINMAXROW => '${_min_row}行～${_max_row}行で入力して下さい',
        self::INVALID_COUNTCOL  => '${line}行目:${_min_col}桁で入力して下さい',
        self::INVALID_MINCOL    => '${line}行目:${_min_col}桁以上で入力して下さい',
        self::INVALID_MAXCOL    => '${line}行目:${_max_col}桁以下で入力して下さい',
        self::INVALID_MINMAXCOL => '${line}行目:${_min_col}桁～${_max_col}桁で入力して下さい',
    ];

    protected $_min_row;
    protected $_max_row;
    protected $_min_col;
    protected $_max_col;
    protected $_col_unit;

    public function __construct(
        $min_row = null,
        $max_row = null,
        $min_col = null,
        $max_col = null,
        $col_unit = 'byte',
    ) {
        $this->_min_row = $min_row;
        $this->_max_row = $max_row;
        $this->_min_col = $min_col;
        $this->_max_col = $max_col;
        $this->_col_unit = $col_unit;

        parent::__construct();
    }

    public static function validate($value, $fields, $params, $consts, $error, $context)
    {
        $checker = $context['function'](function ($length, $min, $max) {
            if (!is_null($max) && !is_null($min) && ($length > $max || $length < $min)) {
                if ($min === $max) {
                    return 'COUNT';
                }
                else {
                    return 'MINMAX';
                }
            }
            elseif (is_null($max) && !is_null($min) && $length < $min) {
                return 'MIN';
            }
            elseif (is_null($min) && !is_null($max) && $length > $max) {
                return 'MAX';
            }
            return null;
        });
        $lengther = $context['function'](function ($value, $params) {
            if ($params['col_unit'] === 'byte') {
                return strlen($value);
            }
            if ($params['col_unit'] === 'length') {
                return mb_strlen($value);
            }
            if ($params['col_unit'] === 'width') {
                return array_sum(array_map(function ($c) {
                    if ($c === "‍") {
                        return -1;
                    }
                    return strlen($c) === 1 ? 1 : 2;
                }, mb_str_split($value)));
            }
        }, $params);

        $lines = preg_split("#\\r?\\n#", $value);

        $rowresult = $checker(count($lines), $params['min_row'], $params['max_row']);
        if ($rowresult !== null) {
            $error($consts[$context['str_concat']('INVALID_', $rowresult, 'ROW')], []);
        }

        $context['foreach']($lines, function ($key, $value, $params, $error, $consts, $context, $lengther, $checker) {
            $colresult = $checker($lengther($value), $params['min_col'], $params['max_col']);
            if ($colresult !== null) {
                $error($consts[$context['str_concat']('INVALID_', $colresult, 'COL')], [
                    ['line', +$key + 1],
                ]);
            }
        }, $params, $error, $consts, $context, $lengther, $checker);
    }

    public function getType()
    {
        return 'textarea';
    }

    public function getFixture($value, $fields)
    {
        return implode("\n", array_map(function () use ($value) {
            $value = mb_substr($value ?? '', 0, $this->_max_col ?? PHP_INT_MAX);
            $value = mb_str_pad($value, $this->_min_col ?? 0, 'X');
            return $value;
        }, range(0, rand($this->_min_row ?? 0, $this->_max_row ?? 256) - ($this->_min_row ?? 0) + 1)));
    }
}

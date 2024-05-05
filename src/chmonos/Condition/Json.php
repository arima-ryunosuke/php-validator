<?php
namespace ryunosuke\chmonos\Condition;

/**
 * JSON バリデータ
 *
 * JSON Schema でキーや値の検証がしたいが、実装が難しいので今のところ未実装。
 */
class Json extends AbstractCondition
{
    public const INVALID                = 'JsonInvalid';
    public const INVALID_INVALID_SCHEMA = 'JsonInvalidSchema';

    protected static $messageTemplates = [
        self::INVALID                => 'JSON文字列が不正です',
        self::INVALID_INVALID_SCHEMA => 'キーが不正です',
    ];

    protected $_schema;

    public function __construct($schema = [])
    {
        assert(!$schema, 'JSON Schema is not implemented yet');

        $this->_schema = $schema;

        parent::__construct();
    }

    public static function validate($value, $fields, $params, $consts, $error, $context)
    {
        $decode = json_decode($value, true);
        if ($decode === null && strtolower(trim($value)) !== 'null') {
            $error($consts['INVALID'], []);
            return;
        }
    }

    public function getFixture($value, $fields)
    {
        // JSON Schema に対応したらこの値も埋められる
        return json_encode($value);
    }
}

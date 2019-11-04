<?php
namespace custom\Condition;

use ryunosuke\chmonos\Condition\AbstractCondition;
use ryunosuke\chmonos\Condition\Interfaces;

class CustomFileCondition extends AbstractCondition implements Interfaces\InferableType
{
    const INVALID = 'CustomFileInvalid';

    protected static $messageTemplates = [
        self::INVALID => "invalid file",
    ];

    public static function validate($value, $fields, $params, $consts, $error, $context)
    {
        return $error($consts['INVALID']);
    }

    public function getType()
    {
        return 'file';
    }
}

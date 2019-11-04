<?php
namespace custom\Condition;

use ryunosuke\chmonos\Condition\AbstractCondition;
use ryunosuke\chmonos\Condition\Interfaces;

class CustomDependFileCondition extends AbstractCondition implements Interfaces\InferableType
{
    const INVALID = 'DependFileInvalid';

    protected static $messageTemplates = [
        self::INVALID => "MIME タイプが不正です",
    ];

    protected $_field;

    public function __construct($field)
    {
        $this->_field = $field;

        parent::__construct();
    }

    public static function validate($value, $fields, $params, $consts, $error, $context)
    {
        $mime = mime_content_type($value);
        if ($mime !== $fields[$params['field']]) {
            return $error($consts['INVALID']);
        }
    }

    public function getType()
    {
        return 'file';
    }
}

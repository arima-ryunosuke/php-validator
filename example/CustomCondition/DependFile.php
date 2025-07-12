<?php
namespace ryunosuke\chmonos\CustomCondition;

use ryunosuke\chmonos\Condition\AbstractCondition;
use ryunosuke\chmonos\Condition\Interfaces;

/**
 * 依存ファイルバリデータ
 */
class DependFile extends AbstractCondition implements Interfaces\InferableType, Interfaces\Propagation
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

    public function getFields()
    {
        return [$this->_field];
    }

    public function getPropagation()
    {
        return $this->getFields();
    }

    public static function validate($value, $fields, $params, $consts, $error, $context)
    {
        if ($context['lang'] === 'php') {
            $value = strval($value);
        }

        $mime = mime_content_type($value);
        if ($mime !== $fields[$params['field']]) {
            return $error($consts['INVALID'], []);
        }
    }

    public function getType()
    {
        return 'file';
    }
}

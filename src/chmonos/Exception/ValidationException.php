<?php
namespace ryunosuke\chmonos\Exception;

use ryunosuke\chmonos\Form;

/**
 * バリデーション失敗例外
 */
class ValidationException extends \RuntimeException
{
    /** @var Form */
    private $form;

    public function __construct(Form $form = null, $message = '', $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->form = $form;
    }

    public function setForm(Form $form)
    {
        $this->form = $form;
    }

    public function getForm()
    {
        return $this->form;
    }
}

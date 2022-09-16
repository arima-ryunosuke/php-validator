<?php
namespace ryunosuke\Test\UnitTest\chmonos\Exception;

use ryunosuke\chmonos\Exception\ValidationException;
use ryunosuke\chmonos\Form;

class TokenExceptionTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test___construct()
    {
        $ex = new ValidationException(null, 'message', 123, new \DomainException('prev'));
        that($ex)->getForm()->isNull();
        that($ex)->getMessage()->is("message");
        that($ex)->getCode()->is(123);
        that($ex)->getPrevious()->getMessage()->is("prev");
    }

    function test_form()
    {
        $ex = new ValidationException(null);
        $form = new Form([]);
        $ex->setForm($form);
        that($ex)->getForm()->isSame($form);
    }
}

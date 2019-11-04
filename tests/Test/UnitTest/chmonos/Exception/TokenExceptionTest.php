<?php
namespace ryunosuke\Test\UnitTest\chmonos\Exception;

use ryunosuke\chmonos\Exception\ValidationException;
use ryunosuke\chmonos\Form;

class TokenExceptionTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test___construct()
    {
        $ex = new ValidationException(null, 'message', 123, new \DomainException('prev'));
        $this->assertEquals(null, $ex->getForm());
        $this->assertEquals('message', $ex->getMessage());
        $this->assertEquals(123, $ex->getCode());
        $this->assertEquals('prev', $ex->getPrevious()->getMessage());
    }

    function test_form()
    {
        $ex = new ValidationException(null);
        $form = new Form([]);
        $ex->setForm($form);
        $this->assertSame($form, $ex->getForm());
    }
}

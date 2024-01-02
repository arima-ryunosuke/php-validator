<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\StringWidth;

class StringWidthTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_min()
    {
        $validate = new StringWidth(2, null);

        that($validate)->isValid('')->isFalse();
        that($validate)->isValid('x')->isFalse();
        that($validate)->isValid('xx')->isTrue();
        that($validate)->isValid('ｘ')->isTrue();
    }

    function test_max()
    {
        $validate = new StringWidth(null, 5);

        that($validate)->isValid('xxxxx')->isTrue();
        that($validate)->isValid('xxxｘ')->isTrue();
        that($validate)->isValid('xxxxxx')->isFalse();
        that($validate)->isValid('xxxxｘ')->isFalse();
    }

    function test_minmax()
    {
        $validate = new StringWidth(2, 5);

        that($validate)->isValid("x")->isFalse();
        that($validate)->isValid("ｘ")->isTrue();
        that($validate)->isValid("xｘｘ")->isTrue();
        that($validate)->isValid("xxｘｘ")->isFalse();
    }

    function test_different()
    {
        $validate = new StringWidth(3, 3);

        $validate->isValid(str_repeat('ｘｘ', 0));
        $messages = $validate->getMessages();
        that($messages)[StringWidth::DIFFERENT]->contains('3文字で');
    }

    function test_getMaxLength()
    {
        $validate = new StringWidth(3, 4);
        that($validate)->getMaxLength()->is(4);
        $validate = new StringWidth(3);
        that($validate)->getMaxLength()->isNull();
    }

    function test_getFixture()
    {
        $validate = new StringWidth(2, 5);
        that($validate)->getFixture('abcd', [])->is('abcd');
        that($validate)->getFixture('abcdefg', [])->is('abcde');
        that($validate)->getFixture('a', [])->is('aX');
    }
}

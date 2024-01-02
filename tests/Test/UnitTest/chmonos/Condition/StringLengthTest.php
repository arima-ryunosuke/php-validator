<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\StringLength;

class StringLengthTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_min()
    {
        $validate = new StringLength(1, null);

        that($validate)->isValid(str_repeat('x', 0))->isFalse();
        that($validate)->isValid(str_repeat('x', 1))->isTrue();
        that($validate)->isValid(str_repeat('x', 65535))->isTrue();
    }

    function test_max()
    {
        $validate = new StringLength(null, 10);

        that($validate)->isValid(str_repeat('x', 0))->isTrue();
        that($validate)->isValid(str_repeat('x', 1))->isTrue();
        that($validate)->isValid(str_repeat('x', 10))->isTrue();
        that($validate)->isValid(str_repeat('x', 11))->isFalse();
    }

    function test_minmax()
    {
        $validate = new StringLength(1, 10);

        that($validate)->isValid(str_repeat('x', 0))->isFalse();
        that($validate)->isValid(str_repeat('x', 1))->isTrue();
        that($validate)->isValid(str_repeat('x', 10))->isTrue();
        that($validate)->isValid(str_repeat('x', 11))->isFalse();
    }

    function test_different()
    {
        $validate = new StringLength(3, 3);

        $validate->isValid(str_repeat('x', 0));
        $messages = $validate->getMessages();
        that($messages)[StringLength::DIFFERENT]->contains('3文字で');
    }

    function test_grapheme()
    {
        $validate = new StringLength(2, 4, true);

        that($validate)->getMaxLength('')->is(null);

        that($validate)->isValid('')->isFalse();
        that($validate)->isValid('👨‍👩‍👧‍👦')->isFalse();
        that($validate)->isValid('👨‍👩‍👧‍👦a')->isTrue();
        that($validate)->isValid('👨‍👩‍👧‍👦👨‍👩‍👧‍👦')->isTrue();
        that($validate)->isValid('👨‍👩‍👧‍👦👨‍👩‍👧‍👦👨‍👩‍👧‍👦👨‍👩‍👧‍👦')->isTrue();
        that($validate)->isValid('👨‍👩‍👧‍👦👨‍👩‍👧‍👦👨‍👩‍👧‍👦👨‍👩‍👧‍👦a')->isFalse();
    }

    function test_getFixture()
    {
        $validate = new StringLength(2, 5);
        that($validate)->getFixture('abcd', [])->is('abcd');
        that($validate)->getFixture('abcdefg', [])->is('abcde');
        that($validate)->getFixture('a', [])->is('aX');
    }
}

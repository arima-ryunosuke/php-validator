<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\AlphaDigit;

class AlphaDigitTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_valid()
    {
        $validate = new AlphaDigit(true, '_-', null);
        that($validate)->isValid('hoge')->isTrue();
        that($validate)->isValid('HOGE')->isTrue();
        that($validate)->isValid('0hoge')->isTrue();
        that($validate)->isValid('hoge_')->isTrue();
        that($validate)->isValid('hoge-')->isTrue();
        that($validate)->isValid('hoge_-')->isTrue();
        that($validate)->isValid('0HOGE_')->isTrue();
        that($validate)->isValid('hoge+')->isFalse();

        $validate = new AlphaDigit(false, '', null);
        that($validate)->isValid('hoge')->isTrue();
        that($validate)->isValid('HOGE')->isTrue();
        that($validate)->isValid('0hoge')->isFalse();
        that($validate)->isValid('hoge_')->isFalse();
        that($validate)->isValid('hoge-')->isFalse();
        that($validate)->isValid('hoge_-')->isFalse();
        that($validate)->isValid('0HOGE_')->isFalse();
        that($validate)->isValid('hoge+')->isFalse();

        $validate = new AlphaDigit(false, '_-', null);
        that($validate)->isValid('hoge')->isTrue();
        that($validate)->isValid('HOGE')->isTrue();
        that($validate)->isValid('0hoge')->isFalse();
        that($validate)->isValid('hoge_')->isTrue();
        that($validate)->isValid('hoge-')->isTrue();
        that($validate)->isValid('hoge_-')->isTrue();
        that($validate)->isValid('0HOGE_')->isFalse();
        that($validate)->isValid('hoge+')->isFalse();

        $validate = new AlphaDigit(true, '', null);
        that($validate)->isValid('hoge')->isTrue();
        that($validate)->isValid('HOGE')->isTrue();
        that($validate)->isValid('0hoge')->isTrue();
        that($validate)->isValid('hoge_')->isFalse();
        that($validate)->isValid('hoge-')->isFalse();
        that($validate)->isValid('hoge_-')->isFalse();
        that($validate)->isValid('0HOGE_')->isFalse();
        that($validate)->isValid('hoge+')->isFalse();

        $validate = new AlphaDigit(false, '', true);
        that($validate)->isValid('hoge')->isTrue();
        that($validate)->isValid('HOGE')->isFalse();
        that($validate)->getMessages()->is([AlphaDigit::INVALID_UPPERCASE => "大文字は使えません"]);

        $validate = new AlphaDigit(false, '', false);
        that($validate)->isValid('HOGE')->isTrue();
        that($validate)->isValid('hoge')->isFalse();
        that($validate)->getMessages()->is([AlphaDigit::INVALID_LOWERCASE => "小文字は使えません"]);
    }

    function test_getImeMode()
    {
        $validate = new AlphaDigit();
        that($validate)->getImeMode()->is(AlphaDigit::DISABLED);
    }

    function test_getFixture()
    {
        $validate = new AlphaDigit(true);
        that($validate)->getFixture(null, [])->matches('#^[0-9]+#');

        $validate = new AlphaDigit(false);
        that($validate)->getFixture(null, [])->matches('#^[_a-z]+#i');

        $validate = new AlphaDigit(false, '-_', true);
        that($validate)->getFixture(null, [])->matches('#^[-_0-9a-z]+#');

        $validate = new AlphaDigit(false, '', false);
        that($validate)->getFixture(null, [])->matches('#^[0-9A-Z]+#');
    }
}

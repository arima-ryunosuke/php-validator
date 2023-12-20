<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\Telephone;

class TelephoneTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_valid()
    {
        $validate = new Telephone(true);

        that($validate)->isValid('000000000000000000')->isFalse();

        that($validate)->isValid('03-3200-2222')->isTrue();  // 市外局番1桁: 東京など
        that($validate)->isValid('043-000-0000')->isTrue();  // 市外局番2桁: 千葉
        that($validate)->isValid('0166-00-0000')->isTrue();  // 市外局番3桁: 旭川
        that($validate)->isValid('09913-0-0000')->isTrue();  // 市外局番4桁: 硫黄島
        that($validate)->isValid('090-0000-0000')->isTrue(); // 携帯電話
    }

    function test_hyphen()
    {
        // ハイフン有り
        $validate = new Telephone(true);
        that($validate)->isValid('090-1234-5678')->isTrue();
        that($validate)->isValid('09012345678')->isFalse();

        // ハイフン無し
        $validate = new Telephone(false);
        that($validate)->isValid('090-1234-5678')->isFalse();
        that($validate)->isValid('09012345678')->isTrue();

        // ハイフンどっちでもいい
        $validate = new Telephone(null);
        that($validate)->isValid('090-1234-5678')->isTrue();
        that($validate)->isValid('09012345678')->isTrue();
        that($validate)->isValid('09012345678')->isTrue();

        // 形式自体のテスト
        $validate = new Telephone();
        that($validate)->isValid('09012345678000')->isFalse(); // 13桁より大きい

        // 例外
        that(Telephone::class)->new('string')->getValidationParam()->wasThrown(new \UnexpectedValueException('hyphen is invalid value'));
    }

    function test_multiple()
    {
        $validate = new Telephone(true, '#,#');
        that($validate)->isValid('')->isTrue();
        that($validate)->isValid('070-1234-5678, 080-1234-5678,090-1234-5678')->isTrue();
        that($validate)->isValid('070-1234-5678, 080-1234-5678,090-1234-5678, 123456789')->isFalse();
    }

    function test_getImeMode()
    {
        $validate = new Telephone();
        that($validate)->getImeMode()->is(Telephone::DISABLED);
    }

    function test_getType()
    {
        $validate = new Telephone();
        that($validate)->getType()->is("tel");
    }

    function test_getMaxLength()
    {
        $validate = new Telephone();
        that($validate)->getMaxLength()->is(15);

        $validate = new Telephone(false);
        that($validate)->getMaxLength()->is(13);

        $validate = new Telephone(false, ',');
        that($validate)->getMaxLength()->is(null);
    }
}

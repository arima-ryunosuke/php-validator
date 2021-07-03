<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\Interfaces;
use ryunosuke\chmonos\Condition\Telephone;

class TelephoneTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_valid()
    {
        $validate = new Telephone(true);

        $this->assertEquals(false, $validate->isValid('000000000000000000'));

        $this->assertEquals(true, $validate->isValid('03-3200-2222'));  // 市外局番1桁: 東京など
        $this->assertEquals(true, $validate->isValid('043-000-0000'));  // 市外局番2桁: 千葉
        $this->assertEquals(true, $validate->isValid('0166-00-0000'));  // 市外局番3桁: 旭川
        $this->assertEquals(true, $validate->isValid('09913-0-0000'));  // 市外局番4桁: 硫黄島
        $this->assertEquals(true, $validate->isValid('090-0000-0000')); //携帯電話
    }

    function test_hyphen()
    {
        // ハイフン有り
        $validate = new Telephone(true);
        $this->assertEquals(true, $validate->isValid('090-1234-5678'));
        $this->assertEquals(false, $validate->isValid('09012345678'));

        // ハイフン無し
        $validate = new Telephone(false);
        $this->assertEquals(false, $validate->isValid('090-1234-5678'));
        $this->assertEquals(true, $validate->isValid('09012345678'));

        // ハイフンどっちでもいい
        $validate = new Telephone(null);
        $this->assertEquals(true, $validate->isValid('090-1234-5678'));
        $this->assertEquals(true, $validate->isValid('09012345678'));
        $this->assertEquals(true, $validate->isValid('09012345678'));

        // 形式自体のテスト
        $validate = new Telephone();
        $this->assertEquals(false, $validate->isValid('09012345678000')); // 13桁より大きい

        // 例外
        $this->assertException(new \UnexpectedValueException('hyphen is invalid value'), function () {
            $validate = new Telephone('string');
            $validate->getValidationParam();
        });
    }

    function test_getImeMode()
    {
        $validate = new Telephone();
        $this->assertEquals(Interfaces\ImeMode::DISABLED, $validate->getImeMode());
    }

    function test_getType()
    {
        $validate = new Telephone();
        $this->assertEquals('tel', $validate->getType());
    }
}

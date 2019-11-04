<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\Interfaces;
use ryunosuke\chmonos\Condition\Telephone;

class TelephoneTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_valid()
    {
        $validate = new Telephone(true);

        $this->assertEquals($validate->isValid('000000000000000000'), false);

        $this->assertEquals($validate->isValid('03-3200-2222'), true);  // 市外局番1桁: 東京など
        $this->assertEquals($validate->isValid('043-000-0000'), true);  // 市外局番2桁: 千葉
        $this->assertEquals($validate->isValid('0166-00-0000'), true);  // 市外局番3桁: 旭川
        $this->assertEquals($validate->isValid('09913-0-0000'), true);  // 市外局番4桁: 硫黄島
        $this->assertEquals($validate->isValid('090-0000-0000'), true); //携帯電話
    }

    function test_hyphen()
    {
        // ハイフン有り
        $validate = new Telephone(true);
        $this->assertEquals($validate->isValid('090-1234-5678'), true);
        $this->assertEquals($validate->isValid('09012345678'), false);

        // ハイフン無し
        $validate = new Telephone(false);
        $this->assertEquals($validate->isValid('090-1234-5678'), false);
        $this->assertEquals($validate->isValid('09012345678'), true);

        // ハイフンどっちでもいい
        $validate = new Telephone(null);
        $this->assertEquals($validate->isValid('090-1234-5678'), true);
        $this->assertEquals($validate->isValid('09012345678'), true);
        $this->assertEquals($validate->isValid('09012345678'), true);

        // 形式自体のテスト
        $validate = new Telephone();
        $this->assertEquals($validate->isValid('09012345678000'), false); // 13桁より大きい

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

<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\RegexAll;

class RegexAllTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_valid()
    {
        // 普通
        $validate = new RegexAll('#^\d{4}/\d{2}/\d{2}$#');
        that($validate)->isValid(<<<TEXT
        2014/12/23
        2014/12/24
        2014/12/25
        TEXT,)->isTrue();

        // 一つでもマッチしないならダメ
        $validate = new RegexAll('#^\d{4}/\d{2}/\d{2}$#');
        that($validate)->isValid(<<<TEXT
        2014/12/23X
        2014/12/24
        2014/12/25
        TEXT,)->isFalse();

        // カンマ区切り
        $validate = new RegexAll('#^\d{4}/\d{2}/\d{2}$#', "#\s*,\s*#", 2);
        that($validate)->isValid(<<<TEXT
        2014/12/23,2014/12/24, 2014/12/25
        TEXT,)->isTrue();

        // 最大エラー2
        $validate = new RegexAll('#^\d{4}/\d{2}/\d{2}$#', "#\n#", 2);
        that($validate)->isValid(<<<TEXT
        2014/12/23X
        2014/12/24Y
        2014/12/25Z
        TEXT,)->isFalse();
        that($validate->getMessages())->is([
            "regexAllNotMatch"   => <<<ERROR
            1行目(2014/12/23X)がパターンに一致しません
            2行目(2014/12/24Y)がパターンに一致しません
            ERROR,
            "regexAllErrorLimit" => "エラーが多すぎるためすべては表示しません",
        ]);

        // 空行 OK
        $validate = new RegexAll('#^(\d{4}/\d{2}/\d{2})?$#');
        that($validate)->isValid(<<<TEXT
        2014/12/23
        
        2014/12/25
        TEXT,)->isTrue();
        // 空行 NG
        $validate = new RegexAll('#^\d{4}/\d{2}/\d{2}$#');
        that($validate)->isValid(<<<TEXT
        2014/12/23
        
        2014/12/25
        TEXT,)->isFalse();

        // 空白 OK
        $validate = new RegexAll('#^\s*\d{4}/\d{2}/\d{2}\s*$#');
        that($validate)->isValid(<<<TEXT
        2014/12/23  
          2014/12/24
          2014/12/25  
        TEXT,)->isTrue();
        // 空白 NG
        $validate = new RegexAll('#^\d{4}/\d{2}/\d{2}$#');
        that($validate)->isValid(<<<TEXT
        2014/12/23
        2014/12/24
          2014/12/25
        TEXT,)->isFalse();

        // 文字列的な物以外はダメ
        $validate = new RegexAll('/.*/');
        that($validate)->isValid([null])->isFalse();
    }

    function test_getType()
    {
        $validate = new RegexAll('#^$#');
        that($validate)->getType()->is("textarea");
    }

    function test_getDelimiter()
    {
        $validate = new RegexAll('#^$#');
        that($validate)->getDelimiter()->is("#\n#");

        $validate = new RegexAll('#^$#', "#\s*,#");
        that($validate)->getDelimiter()->is("#\s*,#");
    }

    function test_getFixture()
    {
        $validate = new RegexAll('/(?:\D+|<\d+>)*[!?]/');
        that($validate)->getFixture(null, [])->isSame(null);
    }
}

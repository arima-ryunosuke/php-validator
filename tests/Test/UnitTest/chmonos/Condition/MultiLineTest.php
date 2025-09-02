<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\MultiLine;

class MultiLineTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_valid()
    {
        // 複合兼メッセージ
        $validate = new MultiLine(min_row: null, max_row: 1, min_col: null, max_col: 1);
        that($validate)->isValid("")->isTrue();
        that($validate)->isValid(<<<TEXT
        1
        TEXT,)->isTrue();
        that($validate)->isValid(<<<TEXT
        1
        2
        TEXT,)->isFalse();
        that(implode("\n", $validate->getMessages()))->contains('1行以下で');
        that($validate)->isValid(<<<TEXT
        12
        TEXT,)->isFalse();
        that(implode("\n", $validate->getMessages()))->contains('1行目:1桁以下で');

        $validate = new MultiLine(min_row: 2, max_row: null, min_col: 2, max_col: null);
        that($validate)->isValid("")->isFalse();
        that($validate)->isValid(<<<TEXT
        1
        TEXT,)->isFalse();
        that(implode("\n", $validate->getMessages()))->containsAll(['2行以上で', '1行目:2桁以上で']);
        that($validate)->isValid(<<<TEXT
        12
        TEXT,)->isFalse();
        that(implode("\n", $validate->getMessages()))->contains('2行以上で');
        that($validate)->isValid(<<<TEXT
        1
        2
        TEXT,)->isFalse();
        that(implode("\n", $validate->getMessages()))->contains('1行目:2桁以上で');
        that($validate)->isValid(<<<TEXT
        12
        34
        TEXT,)->isTrue();

        $validate = new MultiLine(min_row: 1, max_row: 1, min_col: 2, max_col: 2);
        that($validate)->isValid("")->isFalse();
        that($validate)->isValid(<<<TEXT
        1
        TEXT,)->isFalse();
        that($validate)->isValid(<<<TEXT
        1
        2
        TEXT,)->isFalse();
        that($validate)->isValid(<<<TEXT
        12
        TEXT,)->isTrue();

        // 行数
        $validate = new MultiLine(min_row: 2, max_row: 3);
        that($validate)->isValid(<<<TEXT
        1
        TEXT,)->isFalse();
        that($validate)->isValid(<<<TEXT
        1
        2
        TEXT,)->isTrue();
        that($validate)->isValid(<<<TEXT
        1
        2
        3
        TEXT,)->isTrue();
        that($validate)->isValid(<<<TEXT
        1
        2
        3
        4
        TEXT,)->isFalse();

        // 桁数（byte）
        $validate = new MultiLine(min_col: 2, max_col: 3, col_unit: 'byte');
        that($validate)->isValid(<<<TEXT
        1
        TEXT,)->isFalse();
        that($validate)->isValid(<<<TEXT
        12
        TEXT,)->isTrue();
        that($validate)->isValid(<<<TEXT
        123
        TEXT,)->isTrue();
        that($validate)->isValid(<<<TEXT
        1234
        TEXT,)->isFalse();
        that($validate)->isValid(<<<TEXT
        あい
        TEXT,)->isFalse();

        // 桁数（length）
        $validate = new MultiLine(min_col: 2, max_col: 3, col_unit: 'length');
        that($validate)->isValid(<<<TEXT
        あ
        TEXT,)->isFalse();
        that($validate)->isValid(<<<TEXT
        あい
        TEXT,)->isTrue();
        that($validate)->isValid(<<<TEXT
        あいう
        TEXT,)->isTrue();
        that($validate)->isValid(<<<TEXT
        あい🥺
        TEXT,)->isTrue();
        that($validate)->isValid(<<<TEXT
        あいうえ
        TEXT,)->isFalse();

        // 桁数（width）
        $validate = new MultiLine(min_col: 2, max_col: 3, col_unit: 'width');
        that($validate)->isValid(<<<TEXT
        1
        TEXT,)->isFalse();
        that($validate)->isValid(<<<TEXT
        1あ
        TEXT,)->isTrue();
        that($validate)->isValid(<<<TEXT
        1🥺
        TEXT,)->isTrue();
        that($validate)->isValid(<<<TEXT
        12あ
        TEXT,)->isFalse();
        that($validate)->isValid(<<<TEXT
        👨‍👩‍👧‍👦
        TEXT,)->isFalse();
    }

    function test_getType()
    {
        $validate = new MultiLine();
        that($validate)->getType()->is("textarea");
    }

    function test_getFixture()
    {
        $validate = new MultiLine(min_row: 2, max_row: 2, min_col: 4, max_col: 4);
        that($validate)->getFixture('abcd', [])->is("abcd\nabcd");
        that($validate)->getFixture('abcdefg', [])->is("abcd\nabcd");
        that($validate)->getFixture('a', [])->is("aXXX\naXXX");
    }
}

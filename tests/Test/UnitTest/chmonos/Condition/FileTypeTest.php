<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\FileType;

class FileTypeTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_valid()
    {
        $dir = __DIR__ . '/_files/';

        // jpg のみ受けつける
        $types = [
            'JPG' => 'jpg'
        ];
        $validate = new FileType($types);
        that($validate)->isValid($dir . 'csv.txt')->isFalse(); // 明らかにダメ
        that($validate)->isValid($dir . 'png.jpg')->isFalse(); // jpg 偽装 jpg なのでダメ
        that($validate)->isValid($dir . 'jpg.jpg')->isTrue();  // OK

        // csv のみ受けつける
        $types = [
            'CSV' => 'csv'
        ];
        $validate = new FileType($types);
        that($validate)->isValid($dir . 'png.jpg')->isFalse();                   // 明らかにダメ
        that($validate)->isValid($dir . 'csv.csv')->is(PHP_VERSION_ID >= 80000); // csv と plain の判別は難しいのでダメになる
        that($validate)->isValid($dir . 'dmesg')->isFalse();                     // 判別不能はダメ

        // csv、あるいはよくわからないものを受け付ける
        $types = [
            'CSV' => ['csv', '*']
        ];
        $validate = new FileType($types);
        that($validate)->isValid($dir . 'png.jpg')->isFalse(); // 判別可能で異なる場合はダメ
        that($validate)->isValid($dir . 'dmesg')->isTrue();  // 判別不能を許容する

        // csv と txt のみ受けつける
        $types = [
            'CSV' => ['csv', 'txt']
        ];
        $validate = new FileType($types);
        that($validate)->isValid($dir . 'png.jpg')->isFalse(); // 明らかにダメ
        that($validate)->isValid($dir . 'csv.csv')->isTrue();  // OK
        that($validate)->isValid($dir . 'csv.txt')->isTrue();  // OK

        // html のみ受けつける
        $types = [
            'HTML' => 'htm'
        ];
        $validate = new FileType($types);
        that($validate)->isValid($dir . 'jpg.jpg')->isFalse(); // 明らかにダメ
        that($validate)->isValid($dir . 'htm.htm')->isTrue();  // html は判別しやすいのでOK
        that($validate)->isValid($dir . 'htm.txt')->isTrue();  // 同上

        // 拡張子の大文字小文字は区別されない
        $types = [
            'HTML' => 'HtM'
        ];
        $validate = new FileType($types);
        that($validate)->isValid($dir . 'htm.htm')->isTrue();

        // 存在しないファイル
        $validate = new FileType([]);
        @that($validate)->isValid($dir . 'notfound')->isFalse();
    }

    function test_getAccepts()
    {
        // 画像系
        $validate = new FileType([
            'image' => ['gif', 'png', 'jpg'],
        ]);
        that($validate)->getAccepts()->is([".gif", ".png", ".jpg", "image/gif", "image/jpeg", "image/png"]);

        // * は無視される
        $validate = new FileType([
            'image' => ['*', 'gif', 'png', 'jpg'],
        ]);
        that($validate)->getAccepts()->is([".gif", ".png", ".jpg", "image/gif", "image/jpeg", "image/png"]);
    }

    function test_getType()
    {
        $validate = new FileType([]);
        that($validate)->getType()->is("file");
    }

    function test_getFixture()
    {
        $validate = new FileType([]);
        that($validate)->getFixture(null, [])->isSame(null);
    }
}

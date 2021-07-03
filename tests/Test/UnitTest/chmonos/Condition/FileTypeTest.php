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
        $this->assertEquals(false, $validate->isValid($dir . 'csv.txt')); // 明らかにダメ
        $this->assertEquals(false, $validate->isValid($dir . 'png.jpg')); // jpg 偽装 jpg なのでダメ
        $this->assertEquals(true, $validate->isValid($dir . 'jpg.jpg'));  // OK

        // csv のみ受けつける
        $types = [
            'CSV' => 'csv'
        ];
        $validate = new FileType($types);
        $this->assertEquals(false, $validate->isValid($dir . 'png.jpg')); // 明らかにダメ
        $this->assertEquals(false, $validate->isValid($dir . 'csv.csv')); // csv と plain の判別は難しいのでダメになる
        $this->assertEquals(false, $validate->isValid($dir . 'dmesg'));   // 判別不能はダメ

        // csv、あるいはよくわからないものを受け付ける
        $types = [
            'CSV' => ['csv', '*']
        ];
        $validate = new FileType($types);
        $this->assertEquals(false, $validate->isValid($dir . 'png.jpg')); // 判別可能で異なる場合はダメ
        $this->assertEquals(true, $validate->isValid($dir . 'dmesg'));    // 判別不能を許容する

        // csv と txt のみ受けつける
        $types = [
            'CSV' => ['csv', 'txt']
        ];
        $validate = new FileType($types);
        $this->assertEquals(false, $validate->isValid($dir . 'png.jpg')); // 明らかにダメ
        $this->assertEquals(true, $validate->isValid($dir . 'csv.csv'));  // OK
        $this->assertEquals(true, $validate->isValid($dir . 'csv.txt'));  // OK

        // html のみ受けつける
        $types = [
            'HTML' => 'htm'
        ];
        $validate = new FileType($types);
        $this->assertEquals(false, $validate->isValid($dir . 'jpg.jpg')); // 明らかにダメ
        $this->assertEquals(true, $validate->isValid($dir . 'htm.htm'));  // html は判別しやすいのでOK
        $this->assertEquals(true, $validate->isValid($dir . 'htm.txt'));  // 同上

        // 拡張子の大文字小文字は区別されない
        $types = [
            'HTML' => 'HtM'
        ];
        $validate = new FileType($types);
        $this->assertEquals(true, $validate->isValid($dir . 'htm.htm'));

        // 存在しないファイル
        $validate = new FileType([]);
        $this->assertEquals(false, @$validate->isValid($dir . 'notfound'));
    }

    function test_getAccepts()
    {
        // 画像系
        $validate = new FileType([
            'image' => ['gif', 'png', 'jpg'],
        ]);
        $this->assertEquals([
            '.gif',
            '.png',
            '.jpg',
            'image/gif',
            'image/png',
            'image/jpeg',
        ], $validate->getAccepts());

        // * は無視される
        $validate = new FileType([
            'image' => ['*', 'gif', 'png', 'jpg'],
        ]);
        $this->assertEquals([
            '.gif',
            '.png',
            '.jpg',
            'image/gif',
            'image/png',
            'image/jpeg',
        ], $validate->getAccepts());
    }

    function test_getType()
    {
        $validate = new FileType([]);
        $this->assertEquals('file', $validate->getType());
    }
}

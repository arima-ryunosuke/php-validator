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
        $this->assertEquals($validate->isValid($dir . 'csv.txt'), false); // 明らかにダメ
        $this->assertEquals($validate->isValid($dir . 'png.jpg'), false); // jpg 偽装 jpg なのでダメ
        $this->assertEquals($validate->isValid($dir . 'jpg.jpg'), true);  // OK

        // csv のみ受けつける
        $types = [
            'CSV' => 'csv'
        ];
        $validate = new FileType($types);
        $this->assertEquals($validate->isValid($dir . 'png.jpg'), false); // 明らかにダメ
        $this->assertEquals($validate->isValid($dir . 'csv.csv'), false); // csv と plain の判別は難しいのでダメになる
        $this->assertEquals($validate->isValid($dir . 'dmesg'), false);   // 判別不能はダメ

        // csv、あるいはよくわからないものを受け付ける
        $types = [
            'CSV' => ['csv', '*']
        ];
        $validate = new FileType($types);
        $this->assertEquals($validate->isValid($dir . 'png.jpg'), false); // 判別可能で異なる場合はダメ
        $this->assertEquals($validate->isValid($dir . 'dmesg'), true);    // 判別不能を許容する

        // csv と txt のみ受けつける
        $types = [
            'CSV' => ['csv', 'txt']
        ];
        $validate = new FileType($types);
        $this->assertEquals($validate->isValid($dir . 'png.jpg'), false); // 明らかにダメ
        $this->assertEquals($validate->isValid($dir . 'csv.csv'), true);  // OK
        $this->assertEquals($validate->isValid($dir . 'csv.txt'), true);  // OK

        // html のみ受けつける
        $types = [
            'HTML' => 'htm'
        ];
        $validate = new FileType($types);
        $this->assertEquals($validate->isValid($dir . 'jpg.jpg'), false); // 明らかにダメ
        $this->assertEquals($validate->isValid($dir . 'htm.htm'), true);  // html は判別しやすいのでOK
        $this->assertEquals($validate->isValid($dir . 'htm.txt'), true);  // 同上

        // 拡張子の大文字小文字は区別されない
        $types = [
            'HTML' => 'HtM'
        ];
        $validate = new FileType($types);
        $this->assertEquals($validate->isValid($dir . 'htm.htm'), true);

        // 存在しないファイル
        $validate = new FileType([]);
        $this->assertEquals(@$validate->isValid($dir . 'notfound'), false);
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

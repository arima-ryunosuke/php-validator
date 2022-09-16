<?php
/** @noinspection PhpDocSignatureInspection */

namespace ryunosuke\Test\SeleniumTest;

use Facebook\WebDriver\WebDriverBy;

class ExampleTest extends \ryunosuke\Test\SeleniumTest\AbstractSeleniumTestCase
{
    /**
     * @dataProvider provideDriver
     */
    function test_ignore_invisible(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('toggleInvisible', 1);
        $driver->setValue('invisible', 'hoge');
        that($driver)->getErrors(null, false)->count(0);

        $driver->setValue('invisible', '');
        $driver->setValue('toggleInvisible', 0);
        that($driver)->getErrors(null, false)->count(1);
    }

    /**
     * @dataProvider provideDriver
     */
    function test_ignore_checkmode(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('client', 'hoge');
        $driver->setValue('server', 'hoge');
        that($driver)->getErrors()->count(0);

        $driver->setValue('client', '');
        $driver->setValue('server', '');
        that($driver)->getErrors()->count(1);
    }

    /**
     * @dataProvider provideDriver
     */
    function test_ajax(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('ajax1', '1');
        $driver->setValue('ajax2', '2');
        $driver->setValue('ajax_sum', '5');
        that($driver)->getErrors(1)->count(1);
    }

    /**
     * @dataProvider provideDriver
     */
    function test_arraylength_checkbox(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('array_length_checkbox[]', [2, 3, 4, 5]);
        that($driver)->getErrors()->count(5);

        $driver->setValue('array_length_checkbox[]', [2, 3, 4]);
        that($driver)->getErrors()->count(0);
    }

    /**
     * @dataProvider provideDriver
     */
    function test_arraylength_select(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('array_length_select[]', [2, 3, 4, 5]);
        that($driver)->getErrors()->count(1);

        $driver->setValue('array_length_select[]', [2, 3, 4]);
        that($driver)->getErrors()->count(0);
    }

    /**
     * @dataProvider provideDriver
     */
    function test_arraylength_text(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('array_length_text[]', ['1', '2', '3', '4']);
        that($driver)->getErrors()->count(4);

        // DOM の追加/削除を実装してないのでテストできない
        // that($driver)->getErrors()->count(0);
    }

    /**
     * @dataProvider provideDriver
     */
    function test_arraylength_file(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('array_length_file[]', [
            __DIR__ . '/_files/1.txt',
            __DIR__ . '/_files/2.txt',
            __DIR__ . '/_files/3.txt',
            __DIR__ . '/_files/4.txt',
        ]);
        that($driver)->getErrors()->count(1);

        $driver->setValue('array_length_file[]', [
            __DIR__ . '/_files/1.txt',
            __DIR__ . '/_files/2.txt',
            __DIR__ . '/_files/3.txt',
        ]);
        that($driver)->getErrors()->count(0);
    }

    /**
     * @dataProvider provideDriver
     */
    function test_aruiha(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('aruiha_range', -4);
        that($driver)->getErrors()->count(1);
        $driver->setValue('aruiha_range', 4);
        that($driver)->getErrors()->count(1);
        $driver->setValue('aruiha_range', 0);
        that($driver)->getErrors()->count(1);
        $driver->setValue('aruiha_range', -1);
        that($driver)->getErrors()->count(0);
    }

    /**
     * @dataProvider provideDriver
     */
    function test_callback(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('callback1', 'hoge');
        $driver->setValue('callback2', 'hoge');
        that($driver)->getErrors()->count(2);
        $driver->setValue('callback1', 'a');
        $driver->setValue('callback2', 'b');
        that($driver)->getErrors()->count(0);
    }

    /**
     * @dataProvider provideDriver
     */
    function test_compare(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('compare', 'hoge');
        $driver->setValue('compare_confirm', 'fuga');
        that($driver)->getErrors()->count(1);
        $driver->setValue('compare_confirm', 'hoge');
        that($driver)->getErrors()->count(0);
    }

    /**
     * @dataProvider provideDriver
     */
    function test_compare_direct(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('compare_direct', '2000/01/01');
        that($driver)->getErrors()->count(1);
        $driver->setValue('compare_direct', '2037/12/31');
        that($driver)->getErrors()->count(0);
    }

    /**
     * @dataProvider provideDriver
     */
    function test_compare_greater(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('greater', '2011/11/11 11:11:11');
        $driver->setValue('greater_confirm', '2011/11/11 11:11:10');
        that($driver)->getErrors()->count(1);
        $driver->setValue('greater_confirm', '2011/11/11 11:11:12');
        that($driver)->getErrors()->count(0);
    }

    /**
     * @dataProvider provideDriver
     */
    function test_date(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('dateYmd', '2011/02/30');
        $driver->setValue('dateYmdHis', '2011/02/28 12:34:66');
        that($driver)->getErrors()->count(2);
        $driver->setValue('dateYmd', '2011/02/20');
        $driver->setValue('dateYmdHis', '2011/02/28 12:34:56');
        that($driver)->getErrors()->count(0);
    }

    /**
     * @dataProvider provideDriver
     */
    function test_decimal(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('decimal', '2.222');
        that($driver)->getErrors()->count(1);
        $driver->setValue('decimal', '2.22');
        that($driver)->getErrors()->count(0);
    }

    /**
     * @dataProvider provideDriver
     */
    function test_digits(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('digits', '2.1');
        that($driver)->getErrors()->count(1);
        $driver->setValue('digits', '2');
        that($driver)->getErrors()->count(0);
    }

    /**
     * @dataProvider provideDriver
     */
    function test_email(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('email', 'test@@hostname');
        that($driver)->getErrors()->count(1);
        $driver->setValue('email', 'test@hostname');
        that($driver)->getErrors()->count(0);
    }

    /**
     * @dataProvider provideDriver
     */
    function test_email_noerror(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('email_noerror', 'test@@hostname');
        that($driver)->getErrors(null, true, false)->count(0);
        that($driver)->getErrors(null, true, true)->count(1);
        $driver->setValue('email_noerror', 'test@hostname');
        that($driver)->getErrors(null, true, false)->count(0);
    }

    /**
     * @dataProvider provideDriver
     */
    function test_require_file(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('image_file_require', '1');
        that($driver)->getErrors()->count(1);
    }

    /**
     * @dataProvider provideDriver
     */
    function test_image_file(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('image_file', __DIR__ . '/_files/plain.txt');
        that($driver)->getErrors()->count(1);
        $driver->setValue('image_file', __DIR__ . '/_files/image.jpg');
        that($driver)->getErrors()->count(1);
        $driver->setValue('image_file', __DIR__ . '/_files/image.png');
        that($driver)->getErrors()->count(0);
    }

    /**
     * @dataProvider provideDriver
     */
    function test_hostname(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('hostname', '127.0.0.1');
        $driver->setValue('hostname_v4', 'host_name');
        $driver->setValue('hostname_port', 'host_name');
        that($driver)->getErrors()->count(3);
        $driver->setValue('hostname', 'aaa.bbb');
        $driver->setValue('hostname_v4', '127.0.0.1/32');
        $driver->setValue('hostname_port', 'aaa.bbb:80');
        that($driver)->getErrors()->count(0);
    }

    /**
     * @dataProvider provideDriver
     */
    function test_inarray(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('inarray', '5');
        $driver->setValue('notinarray', '2');
        that($driver)->getErrors()->count(2);
        $driver->setValue('inarray', '2');
        $driver->setValue('notinarray', '5');
        that($driver)->getErrors()->count(0);
    }

    /**
     * @dataProvider provideDriver
     */
    function test_inarray_strict(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('inarray_strict', '2');
        that($driver)->getErrors()->count(1);

        $driver->setValue('inarray_strict', '1');
        that($driver)->getErrors()->count(0);
    }

    /**
     * @dataProvider provideDriver
     */
    function test_json(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('json', 'aaa');
        that($driver)->getErrors()->count(1);
        $driver->setValue('json', '{]');
        that($driver)->getErrors()->count(1);
        $driver->setValue('json', '{"aaa": [1,2,3]}');
        that($driver)->getErrors()->count(0);
    }

    /**
     * @dataProvider provideDriver
     */
    function test_password(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('password', 'a1$S');
        that($driver)->getErrors()->count(1);
        $driver->setValue('password', 'aZsX09#!');
        that($driver)->getErrors()->count(0);
    }

    /**
     * @dataProvider provideDriver
     */
    function test_filename(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('filename', '/directory/file.csv');
        that($driver)->getErrors()->count(1);
        $driver->setValue('filename', '/directory/file.json');
        that($driver)->getErrors()->count(0);
    }

    /**
     * @dataProvider provideDriver
     */
    function test_step(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('step', '1.4');
        that($driver)->getErrors()->count(1);
        $driver->setValue('step', '1.5');
        that($driver)->getErrors()->count(0);
    }

    /**
     * @dataProvider provideDriver
     */
    function test_range(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('range', '101');
        that($driver)->getErrors()->count(1);
        $driver->setValue('range', '99');
        that($driver)->getErrors()->count(0);
    }

    /**
     * @dataProvider provideDriver
     */
    function test_regex(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('regex', '123');
        $driver->setValue('notregex', 'abc');
        that($driver)->getErrors()->count(2);
        $driver->setValue('regex', 'abc');
        $driver->setValue('notregex', '123');
        that($driver)->getErrors()->count(0);
    }

    /**
     * @dataProvider provideDriver
     */
    function test_require_checkbox(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('require_checkbox[]', '1');
        that($driver)->getErrors()->count(0);
        $driver->setValue('require_checkbox[]', []);
        that($driver)->getErrors()->count(5);
        $driver->setValue('require_checkbox[]', '1');
        that($driver)->getErrors()->count(0);
    }

    /**
     * @dataProvider provideDriver
     */
    function test_require_select(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('require_select[]', []);
        that($driver)->getErrors()->count(0);
        $driver->setValue('require_select[]', [1]);
        $driver->setValue('require_select[]', []);
        that($driver)->getErrors()->count(1);
    }

    /**
     * @dataProvider provideDriver
     */
    function test_require_depend(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('require_simple', '4');
        that($driver)->getErrors()->count(1);

        $driver->setValue('require_simple', '5');
        that($driver)->getErrors()->count(2);
    }

    /**
     * @dataProvider provideDriver
     */
    function test_require_andor(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('require_andor1', 'a');
        $driver->setValue('require_andor2', 'x');
        that($driver)->getErrors()->count(1);

        $driver->setValue('require_andor2', 'b');
        that($driver)->getErrors()->count(2);
    }

    /**
     * @dataProvider provideDriver
     */
    function test_require_complex(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('require_should', [1]);
        that($driver)->getErrors()->count(0);
        $driver->setValue('require_option', '2');
        that($driver)->getErrors()->count(1);
        $driver->setValue('require_option', '3');
        that($driver)->getErrors()->count(1);
        $driver->setValue('require_option', '1');
        that($driver)->getErrors()->count(0);
        $driver->setValue('require_always', [1]);
        that($driver)->getErrors()->count(1);
    }

    /**
     * @dataProvider provideDriver
     */
    function test_require_contain(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('require_array[]', ['2']);
        that($driver)->getErrors()->count(0);
        $driver->setValue('require_array[]', ['1', '2']);
        that($driver)->getErrors()->count(1);
        $driver->setValue('require_array[]', ['1', '2', '3']);
        that($driver)->getErrors()->count(2);
    }

    /**
     * @dataProvider provideDriver
     */
    function test_stringlength(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('stringlength', 'x');
        that($driver)->getErrors()->count(1);
        $driver->setValue('stringlength', 'xxx');
        that($driver)->getErrors()->count(0);
    }

    /**
     * @dataProvider provideDriver
     */
    function test_stringwidth(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('stringwidth', 'x');
        that($driver)->getErrors()->count(1);
        $driver->setValue('stringwidth', 'あ');
        that($driver)->getErrors()->count(0);
    }

    /**
     * @dataProvider provideDriver
     */
    function test_telephone(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('telephone', '090-1234-567a');
        $driver->setValue('telephone-hyphen', '09012345678');
        that($driver)->getErrors()->count(2);
        $driver->setValue('telephone', '090-1234-567');
        $driver->setValue('telephone-hyphen', '090-1234-567');
        that($driver)->getErrors()->count(0);
    }

    /**
     * @dataProvider provideDriver
     */
    function test_url(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('url', 'hostname');
        $driver->setValue('url-http', 'hoge://hostname');
        that($driver)->getErrors()->count(2);
        $driver->setValue('url', 'hoge://hostname');
        $driver->setValue('url-http', 'http://hostname');
        that($driver)->getErrors()->count(0);
    }

    /**
     * @dataProvider provideDriver
     */
    function test_dynamic_arraylength(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->click('.append_row1');
        $driver->click('#template_form_submit');
        $baseCount = count($driver->getErrors());
        $driver->click('.append_row1');
        $driver->click('.append_row1');
        $driver->click('.append_row1');
        $driver->click('#template_form_submit');

        that($driver)->getErrors(null, false)->count($baseCount * 5 + 1);
    }

    /**
     * @dataProvider provideDriver
     */
    function test_dynamic_require(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->click('.append_row1');
        $driver->click('.append_row1');

        $driver->setValue('rows[-1][title]', '');
        $driver->setValue('rows[-2][title]', '');
        $driver->setValue('require_address', '1');
        that($driver)->getErrors()->count(2);
    }

    /**
     * @dataProvider provideDriver
     */
    function test_dynamic_arraylength_checkbox(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->click('.append_row1');
        $driver->click('.append_row1');

        $driver->setValue('rows[-1][checkbox][]', [2]);
        that($driver)->getErrors()->count(3);
        $driver->setValue('rows[-1][checkbox][]', [1, 3]);
        that($driver)->getErrors()->count(0);
    }

    /**
     * @dataProvider provideDriver
     */
    function test_dynamic_unique(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->click('.append_row1');
        $driver->click('.append_row1');

        $driver->setValue('rows[-1][unique_require]', '1');
        $driver->setValue('rows[-2][unique_require]', '1');
        that($driver)->getErrors()->count(2);
    }

    /**
     * @dataProvider provideDriver
     */
    function test_dynamic_vuejs(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->click('.append_row3');
        $driver->click('.append_row3');

        $driver->setValue('require_address', '1', '#vuejs_form');
        $driver->setValue('rows[0][checkbox][]', [1], '#vuejs_form');
        $driver->setValue('rows[1][checkbox][]', [2], '#vuejs_form');
        that($driver)->getErrors()->count(8);
    }

    /**
     * @dataProvider provideDriver
     */
    function test_phantom(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('year', '2000');
        $driver->setValue('month', '5');
        $driver->setValue('day', '24');
        that($driver)->getErrors()->count(0);
        that($driver)->findElement(WebDriverBy::name('year-month-day'))->getAttribute('class')->contains('validation_ok');

        $driver->setValue('month', '13');
        that($driver)->getErrors()->count(2);
    }

    /**
     * @dataProvider provideDriver
     */
    function test_trimming(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('flag_trimming_true', ' ');
        that($driver)->getErrors()->count(1);

        $driver->setValue('flag_trimming_false', ' ');
        that($driver)->getErrors()->count(1);

        $driver->setValue('flag_trimming_false', '');
        that($driver)->getErrors()->count(2);
    }

    /**
     * @dataProvider provideDriver
     */
    function test_jstest(WebDriver $driver)
    {
        $driver->path('/example/testing.php');

        sleep(1); // wait for js testing
        that($driver)->findElement(WebDriverBy::cssSelector('.jasmine-alert'))->getText()->contains('0 failures');
    }
}

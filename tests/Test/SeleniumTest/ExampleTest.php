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
        $this->assertCount(0, $driver->getErrors(null, false));

        $driver->setValue('invisible', '');
        $driver->setValue('toggleInvisible', 0);
        $this->assertCount(1, $driver->getErrors(null, false));
    }

    /**
     * @dataProvider provideDriver
     */
    function test_ignore_checkmode(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('client', 'hoge');
        $driver->setValue('server', 'hoge');
        $this->assertCount(0, $driver->getErrors());

        $driver->setValue('client', '');
        $driver->setValue('server', '');
        $this->assertCount(1, $driver->getErrors());
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
        $this->assertCount(1, $driver->getErrors());
    }

    /**
     * @dataProvider provideDriver
     */
    function test_arraylength_checkbox(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('array_length_checkbox[]', [2, 3, 4, 5]);
        $this->assertCount(5, $driver->getErrors());

        $driver->setValue('array_length_checkbox[]', [2, 3, 4]);
        $this->assertCount(0, $driver->getErrors());
    }

    /**
     * @dataProvider provideDriver
     */
    function test_arraylength_select(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('array_length_select[]', [2, 3, 4, 5]);
        $this->assertCount(1, $driver->getErrors());

        $driver->setValue('array_length_select[]', [2, 3, 4]);
        $this->assertCount(0, $driver->getErrors());
    }

    /**
     * @dataProvider provideDriver
     */
    function test_arraylength_text(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('array_length_text[]', ['1', '2', '3', '4']);
        $this->assertCount(4, $driver->getErrors());

        // DOM の追加/削除を実装してないのでテストできない
        // $this->assertCount(0, $driver->getErrors());
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
        $this->assertCount(1, $driver->getErrors());

        $driver->setValue('array_length_file[]', [
            __DIR__ . '/_files/1.txt',
            __DIR__ . '/_files/2.txt',
            __DIR__ . '/_files/3.txt',
        ]);
        $this->assertCount(0, $driver->getErrors());
    }

    /**
     * @dataProvider provideDriver
     */
    function test_aruiha(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('aruiha_range', -4);
        $this->assertCount(1, $driver->getErrors());
        $driver->setValue('aruiha_range', 4);
        $this->assertCount(1, $driver->getErrors());
        $driver->setValue('aruiha_range', 0);
        $this->assertCount(1, $driver->getErrors());
        $driver->setValue('aruiha_range', -1);
        $this->assertCount(0, $driver->getErrors());
    }

    /**
     * @dataProvider provideDriver
     */
    function test_callback(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('callback1', 'hoge');
        $driver->setValue('callback2', 'hoge');
        $this->assertCount(2, $driver->getErrors());
        $driver->setValue('callback1', 'a');
        $driver->setValue('callback2', 'b');
        $this->assertCount(0, $driver->getErrors());
    }

    /**
     * @dataProvider provideDriver
     */
    function test_compare(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('compare', 'hoge');
        $driver->setValue('compare_confirm', 'fuga');
        $this->assertCount(1, $driver->getErrors());
        $driver->setValue('compare_confirm', 'hoge');
        $this->assertCount(0, $driver->getErrors());
    }

    /**
     * @dataProvider provideDriver
     */
    function test_compare_direct(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('compare_direct', '2000/01/01');
        $this->assertCount(1, $driver->getErrors());
        $driver->setValue('compare_direct', '2037/12/31');
        $this->assertCount(0, $driver->getErrors());
    }

    /**
     * @dataProvider provideDriver
     */
    function test_compare_greater(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('greater', '2011/11/11 11:11:11');
        $driver->setValue('greater_confirm', '2011/11/11 11:11:10');
        $this->assertCount(1, $driver->getErrors());
        $driver->setValue('greater_confirm', '2011/11/11 11:11:12');
        $this->assertCount(0, $driver->getErrors());
    }

    /**
     * @dataProvider provideDriver
     */
    function test_date(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('dateYmd', '2011/02/30');
        $driver->setValue('dateYmdHis', '2011/02/28 12:34:66');
        $this->assertCount(2, $driver->getErrors());
        $driver->setValue('dateYmd', '2011/02/20');
        $driver->setValue('dateYmdHis', '2011/02/28 12:34:56');
        $this->assertCount(0, $driver->getErrors());
    }

    /**
     * @dataProvider provideDriver
     */
    function test_decimal(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('decimal', '2.222');
        $this->assertCount(1, $driver->getErrors());
        $driver->setValue('decimal', '2.22');
        $this->assertCount(0, $driver->getErrors());
    }

    /**
     * @dataProvider provideDriver
     */
    function test_digits(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('digits', '2.1');
        $this->assertCount(1, $driver->getErrors());
        $driver->setValue('digits', '2');
        $this->assertCount(0, $driver->getErrors());
    }

    /**
     * @dataProvider provideDriver
     */
    function test_email(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('email', 'test@@hostname');
        $this->assertCount(1, $driver->getErrors());
        $driver->setValue('email', 'test@hostname');
        $this->assertCount(0, $driver->getErrors());
    }

    /**
     * @dataProvider provideDriver
     */
    function test_require_file(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('image_file_require', '1');
        $this->assertCount(1, $driver->getErrors());
    }

    /**
     * @dataProvider provideDriver
     */
    function test_image_file(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('image_file', __DIR__ . '/_files/plain.txt');
        $this->assertCount(1, $driver->getErrors());
        $driver->setValue('image_file', __DIR__ . '/_files/image.jpg');
        $this->assertCount(1, $driver->getErrors());
        $driver->setValue('image_file', __DIR__ . '/_files/image.png');
        $this->assertCount(0, $driver->getErrors());
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
        $this->assertCount(3, $driver->getErrors());
        $driver->setValue('hostname', 'aaa.bbb');
        $driver->setValue('hostname_v4', '127.0.0.1/32');
        $driver->setValue('hostname_port', 'aaa.bbb:80');
        $this->assertCount(0, $driver->getErrors());
    }

    /**
     * @dataProvider provideDriver
     */
    function test_inarray(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('inarray', '5');
        $driver->setValue('notinarray', '2');
        $this->assertCount(2, $driver->getErrors());
        $driver->setValue('inarray', '2');
        $driver->setValue('notinarray', '5');
        $this->assertCount(0, $driver->getErrors());
    }

    /**
     * @dataProvider provideDriver
     */
    function test_inarray_strict(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('inarray_strict', '2');
        $this->assertCount(1, $driver->getErrors());

        $driver->setValue('inarray_strict', '1');
        $this->assertCount(0, $driver->getErrors());
    }

    /**
     * @dataProvider provideDriver
     */
    function test_json(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('json', 'aaa');
        $this->assertCount(1, $driver->getErrors());
        $driver->setValue('json', '{]');
        $this->assertCount(1, $driver->getErrors());
        $driver->setValue('json', '{"aaa": [1,2,3]}');
        $this->assertCount(0, $driver->getErrors());
    }

    /**
     * @dataProvider provideDriver
     */
    function test_password(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('password', 'a1$S');
        $this->assertCount(1, $driver->getErrors());
        $driver->setValue('password', 'aZsX09#!');
        $this->assertCount(0, $driver->getErrors());
    }

    /**
     * @dataProvider provideDriver
     */
    function test_step(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('step', '1.4');
        $this->assertCount(1, $driver->getErrors());
        $driver->setValue('step', '1.5');
        $this->assertCount(0, $driver->getErrors());
    }

    /**
     * @dataProvider provideDriver
     */
    function test_range(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('range', '101');
        $this->assertCount(1, $driver->getErrors());
        $driver->setValue('range', '99');
        $this->assertCount(0, $driver->getErrors());
    }

    /**
     * @dataProvider provideDriver
     */
    function test_regex(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('regex', '123');
        $driver->setValue('notregex', 'abc');
        $this->assertCount(2, $driver->getErrors());
        $driver->setValue('regex', 'abc');
        $driver->setValue('notregex', '123');
        $this->assertCount(0, $driver->getErrors());
    }

    /**
     * @dataProvider provideDriver
     */
    function test_require_checkbox(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('require_checkbox[]', '1');
        $this->assertCount(0, $driver->getErrors());
        $driver->setValue('require_checkbox[]', []);
        $this->assertCount(5, $driver->getErrors());
        $driver->setValue('require_checkbox[]', '1');
        $this->assertCount(0, $driver->getErrors());
    }

    /**
     * @dataProvider provideDriver
     */
    function test_require_select(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('require_select[]', []);
        $this->assertCount(0, $driver->getErrors());
        $driver->setValue('require_select[]', [1]);
        $driver->setValue('require_select[]', []);
        $this->assertCount(1, $driver->getErrors());
    }

    /**
     * @dataProvider provideDriver
     */
    function test_require_depend(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('require_simple', '4');
        $this->assertCount(1, $driver->getErrors());

        $driver->setValue('require_simple', '5');
        $this->assertCount(2, $driver->getErrors());
    }

    /**
     * @dataProvider provideDriver
     */
    function test_require_andor(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('require_andor1', 'a');
        $driver->setValue('require_andor2', 'x');
        $this->assertCount(1, $driver->getErrors());

        $driver->setValue('require_andor2', 'b');
        $this->assertCount(2, $driver->getErrors());
    }

    /**
     * @dataProvider provideDriver
     */
    function test_require_complex(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('require_should', [1]);
        $this->assertCount(0, $driver->getErrors());
        $driver->setValue('require_option', '2');
        $this->assertCount(1, $driver->getErrors());
        $driver->setValue('require_option', '3');
        $this->assertCount(1, $driver->getErrors());
        $driver->setValue('require_option', '1');
        $this->assertCount(0, $driver->getErrors());
        $driver->setValue('require_always', [1]);
        $this->assertCount(1, $driver->getErrors());
    }

    /**
     * @dataProvider provideDriver
     */
    function test_require_contain(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('require_array[]', ['2']);
        $this->assertCount(0, $driver->getErrors());
        $driver->setValue('require_array[]', ['1', '2']);
        $this->assertCount(1, $driver->getErrors());
        $driver->setValue('require_array[]', ['1', '2', '3']);
        $this->assertCount(2, $driver->getErrors());
    }

    /**
     * @dataProvider provideDriver
     */
    function test_telephone(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('telephone', '090-1234-567a');
        $driver->setValue('telephone-hyphen', '09012345678');
        $this->assertCount(2, $driver->getErrors());
        $driver->setValue('telephone', '090-1234-567');
        $driver->setValue('telephone-hyphen', '090-1234-567');
        $this->assertCount(0, $driver->getErrors());
    }

    /**
     * @dataProvider provideDriver
     */
    function test_url(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('url', 'hostname');
        $driver->setValue('url-http', 'hoge://hostname');
        $this->assertCount(2, $driver->getErrors());
        $driver->setValue('url', 'hoge://hostname');
        $driver->setValue('url-http', 'http://hostname');
        $this->assertCount(0, $driver->getErrors());
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

        $this->assertCount($baseCount * 5 + 1, $driver->getErrors(null, false));
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
        $driver->setValue('require-address', '1');
        $this->assertCount(2, $driver->getErrors());
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
        $this->assertCount(3, $driver->getErrors());
        $driver->setValue('rows[-1][checkbox][]', [1, 3]);
        $this->assertCount(0, $driver->getErrors());
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
        $this->assertCount(2, $driver->getErrors());
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
        $this->assertCount(0, $driver->getErrors());
        // js で書き換えても value が変わらない？ DOM ではなく html 属性を見ているような気がする
        //$this->assertEquals('2000/05/24', $driver->findElement(WebDriverBy::name('year-month-day'))->getAttribute('class'));
        $this->assertStringContainsString('validation_ok', $driver->findElement(WebDriverBy::name('year-month-day'))->getAttribute('class'));

        $driver->setValue('month', '13');
        $this->assertCount(2, $driver->getErrors());
    }

    /**
     * @dataProvider provideDriver
     */
    function test_trimming(WebDriver $driver)
    {
        $driver->path('/example/index.php');

        $driver->setValue('flag_trimming_true', ' ');
        $this->assertCount(1, $driver->getErrors());

        $driver->setValue('flag_trimming_false', ' ');
        $this->assertCount(1, $driver->getErrors());

        $driver->setValue('flag_trimming_false', '');
        $this->assertCount(2, $driver->getErrors());
    }

    /**
     * @dataProvider provideDriver
     */
    function test_jstest(WebDriver $driver)
    {
        $driver->path('/example/testing.php');

        sleep(1); // wait for js testing
        $this->assertStringContainsString('0 failures', $driver->findElement(WebDriverBy::cssSelector('.jasmine-alert'))->getText());
    }
}

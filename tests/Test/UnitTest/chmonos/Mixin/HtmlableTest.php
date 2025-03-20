<?php
namespace ryunosuke\Test\UnitTest\chmonos\Mixin;

use ryunosuke\chmonos\HtmlString;
use ryunosuke\chmonos\Mixin\Htmlable;

class HtmlableTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    use Htmlable;

    function test_convertHtmlAttrs()
    {
        $expected = [
            'id'       => 'hoge',
            'class'    => 'c1 c2',
            'href'     => 'http://hoge[]',
            'target'   => 'hoge[]',
            'hidden'   => true,
            'readonly' => false,
            'style'    => [
                'width'  => '123px!important',
                'height' => '456px',
            ],
        ];

        that($this)->convertHtmlAttrs('.c1#hoge.c2[target=hoge\[\]][href="http://hoge[]"][hidden][!readonly]{width:123px!important;height:456px;}')->is($expected);

        that($this)->convertHtmlAttrs([
            'id'       => 'hoge',
            'class'    => ['c1', 'c2'],
            'href'     => 'http://hoge[]',
            'target'   => 'hoge[]',
            'hidden'   => true,
            'readonly' => false,
            'style'    => [
                'width'  => '123px!important',
                'height' => '456px',
            ],
        ])->is($expected);
    }

    function test_createHtmlAttr()
    {
        that($this)->createHtmlAttr([
            'a' => 1,
            'b' => '&',
        ])->is("a=\"1\" b=\"&amp;\"");
        that($this)->createHtmlAttr([
            'logical_true'  => true,
            'logical_false' => false,
        ])->is("logical_true");
        that($this)->createHtmlAttr([
            'hoge' => 'ho%sge',
        ], 'fuga')->is("hoge=\"hofugage\"");
        that($this)->createHtmlAttr([
            'style' => [
                'color'            => 'red',
                'background-color' => 'white',
                'font-family:monospace;',
            ],
        ], 'fuga')->is("style=\"color:red;background-color:white;font-family:monospace;\"");
    }

    function test_createStyleAttr()
    {
        that($this)->createStyleAttr([
            'color'            => 'red',
            'background-color' => 'white',
            'font-family:monospace;',
        ])->is("color:red;background-color:white;font-family:monospace;");
    }

    function test_escapeHtml()
    {
        that($this)->escapeHtml('<&>"')->is("&lt;&amp;&gt;&quot;");
        that($this)->escapeHtml(['<', '>'])->is("&lt; &gt;");
        that($this)->escapeHtml(['<', '>'], '---')->is("&lt;---&gt;");
        that($this)->escapeHtml(['<', '>'], null)->is(["&lt;", "&gt;"]);

        that($this)->escapeHtml(new HtmlString('<&>"'))->is('<&>"');
        that($this)->escapeHtml([new HtmlString('<'), new HtmlString('>')])->is('< >');
    }
}

<?php
namespace ryunosuke\Test\UnitTest\chmonos\Mixin;

use ryunosuke\chmonos\Mixin\Htmlable;

class HtmlableTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    use Htmlable;

    function test_createHtmlAttr()
    {
        $this->assertEquals('a="1" b="&amp;"', $this->createHtmlAttr([
            'a' => 1,
            'b' => '&',
        ]));
        $this->assertEquals('logical_true', $this->createHtmlAttr([
            'logical_true'  => true,
            'logical_false' => false,
        ]));
        $this->assertEquals('hoge="hofugage"', $this->createHtmlAttr([
            'hoge' => 'ho%sge',
        ], 'fuga'));
        $this->assertEquals('style="color:red;background-color:white;font-family:monospace;"', $this->createHtmlAttr([
            'style' => [
                'color'            => 'red',
                'background-color' => 'white',
                'font-family:monospace;'
            ],
        ], 'fuga'));
    }

    function test_createStyleAttr()
    {
        $this->assertEquals('color:red;background-color:white;font-family:monospace;', $this->createStyleAttr([
            'color'            => 'red',
            'background-color' => 'white',
            'font-family:monospace;'
        ]));
    }

    function test_escapeHtml()
    {
        $this->assertEquals('&lt;&amp;&gt;&quot;', $this->escapeHtml('<&>"'));
        $this->assertEquals('&lt; &gt;', $this->escapeHtml(['<', '>']));
        $this->assertEquals('&lt;---&gt;', $this->escapeHtml(['<', '>'], '---'));
        $this->assertEquals(['&lt;', '&gt;'], $this->escapeHtml(['<', '>'], null));
    }
}

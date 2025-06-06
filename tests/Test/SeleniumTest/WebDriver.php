<?php
namespace ryunosuke\Test\SeleniumTest;

use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Remote\LocalFileDetector;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\RemoteWebElement;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverSelect;
use function ryunosuke\chmonos\uri_build;
use function ryunosuke\chmonos\uri_parse;

class WebDriver extends RemoteWebDriver
{
    public function path($path, ?array $query = [])
    {
        $parts = uri_parse($path);

        $current = uri_parse($this->getCurrentURL());
        $current['path'] = $parts['path'];
        if ($query === null) {
            $current['query'] = [];
        }
        else {
            $current['query'] = array_merge($query, $parts['query'], $current['query']);
        }
        return $this->get(uri_build($current));
    }

    public function click($select)
    {
        foreach ($this->findElements(WebDriverBy::cssSelector($select)) as $e) {
            $e->click();
        }
    }

    public function setValue($name, $value, $parent = '')
    {
        $values = (array) $value;
        $clearable = true;

        $es = $this->findElements(WebDriverBy::cssSelector("$parent [name='$name']:not([type=hidden])"));
        $e = reset($es);

        switch ($e->getTagName()) {
            case 'select':
                $select = new WebDriverSelect($e);
                if ($select->isMultiple()) {
                    if ($clearable) {
                        $select->deselectAll();
                    }
                    foreach ($values as $v) {
                        $select->selectByValue($v);
                    }
                }
                else {
                    $select->selectByValue($value);
                }
                break;

            /** @noinspection PhpMissingBreakStatementInspection */
            case 'input':
                if (in_array($e->getAttribute('type'), ['checkbox', 'radio'])) {
                    $inputs = $this->findElements(WebDriverBy::cssSelector("$parent [name='$name']:not([type=hidden])"));
                    foreach ($inputs as $input) {
                        $this->executeScript('arguments[0].scrollIntoView(true);', [$input]);
                        if ($clearable && $input->isSelected()) {
                            $input->click();
                        }
                        if (in_array($input->getAttribute('value'), $values)) {
                            $input->click();
                        }
                    }
                    break;
                }
                foreach ($es as $e) {
                    if (in_array($e->getAttribute('type'), ['file'])) {
                        $e->setFileDetector(new LocalFileDetector());
                    }
                }

            case 'textarea':
                if ($clearable) {
                    foreach ($es as $e) {
                        $e->clear();
                    }
                }
                foreach ($values as $v) {
                    $e->sendKeys($v);
                }
                break;
        }
    }

    public function waitFor($selector, $timeout = 5)
    {
        $by = WebDriverBy::cssSelector($selector);
        $start = microtime(true);
        while (true) {
            $es = $this->findElements($by);
            if (count($es)) {
                return reset($es);
            }
            if ((microtime(true) - $start) > $timeout) {
                throw new NoSuchElementException("$selector not found for $timeout seconds.");
            }
            usleep(1000 * 100);
        }
    }

    public function getOks($sleep = null, $displayed = true, $blur = true)
    {
        return $this->getResult('.validation_ok', $sleep, $displayed, $blur);
    }

    public function getWarnings($sleep = null, $displayed = true, $blur = true)
    {
        return $this->getResult('.validation_warning', $sleep, $displayed, $blur);
    }

    public function getErrors($sleep = null, $displayed = true, $blur = true)
    {
        return $this->getResult('.validation_error', $sleep, $displayed, $blur);
    }

    public function getMessages($sleep = null, $displayed = true, $blur = true)
    {
        return $this->getResult('.validation_message', $sleep, $displayed, $blur);
    }

    public function getMessage($sleep = null, $displayed = true, $blur = true)
    {
        return implode("\n\n", array_map(fn($e) => $e->getText(), $this->getResult('.validation_message', $sleep, $displayed, $blur)));
    }

    private function getResult(string $cssClass, ?int $sleep, bool $displayed, bool $blur)
    {
        // デフォルトが change なのでフォーカスを移さないと検証が走らないので代替
        if ($blur) {
            $this->findElement(WebDriverBy::id('dummy-focus'))->click();
        }
        if ($sleep) {
            sleep($sleep);
        }

        $es = $this->findElements(WebDriverBy::cssSelector($cssClass));
        return array_filter($es, function (RemoteWebElement $e) use ($displayed) {
            return (!$displayed || ($displayed && $e->isDisplayed()));
        });
    }
}

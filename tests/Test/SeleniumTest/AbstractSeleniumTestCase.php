<?php
namespace ryunosuke\Test\SeleniumTest;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use function ryunosuke\chmonos\ping;
use function ryunosuke\chmonos\uri_parse;

abstract class AbstractSeleniumTestCase extends \ryunosuke\Test\AbstractUnitTestCase
{
    private static function getDrivers()
    {
        $parts = uri_parse(SELENIUM_URL);
        if (ping($parts['host'], $parts['port']) === false) {
            return [];
        }

        static $drivers = null;
        if ($drivers === null) {
            $drivers = [];
            $parts = uri_parse(TESTWEB_URL);
            if (ping($parts['host'], $parts['port']) === false) {
                self::markTestSkipped('testweb server is not running.');
            }
            $parts = uri_parse(SELENIUM_URL);
            if (ping($parts['host'], $parts['port']) === false) {
                self::markTestSkipped('selenium server is not running.');
            }
            foreach (explode(',', BROWSERS) as $browser) {
                if (!method_exists(DesiredCapabilities::class, $browser)) {
                    fwrite(STDERR, "webdriver '$browser' is not supported.\n");
                    continue;
                }
                $drivers[$browser] = WebDriver::create(SELENIUM_URL, DesiredCapabilities::$browser());
            }
        }
        return $drivers;
    }

    public static function provideDriver()
    {
        return array_map(function ($driver) { return [$driver]; }, self::getDrivers());
    }

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        foreach (self::getDrivers() as $driver) {
            $driver->get(TESTWEB_URL);
        }
    }

    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();

        foreach (self::getDrivers() as $driver) {
            $driver->quit();
        }
    }
}

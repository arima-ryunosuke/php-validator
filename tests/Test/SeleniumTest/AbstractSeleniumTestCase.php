<?php
namespace ryunosuke\Test\SeleniumTest;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use function ryunosuke\chmonos\parse_uri;
use function ryunosuke\chmonos\ping;

abstract class AbstractSeleniumTestCase extends \ryunosuke\Test\AbstractUnitTestCase
{
    private static function getDrivers()
    {
        $parts = parse_uri(SELENIUM_URL);
        if (ping($parts['host'], $parts['port']) === false) {
            return [];
        }

        static $drivers = null;
        if ($drivers === null) {
            $drivers = [];
            $parts = parse_uri(TESTWEB_URL);
            if (ping($parts['host'], $parts['port']) === false) {
                self::markTestSkipped('testweb server is not running.');
            }
            $parts = parse_uri(SELENIUM_URL);
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

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        foreach (self::getDrivers() as $driver) {
            $driver->get(TESTWEB_URL);
        }
    }

    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();

        foreach (self::getDrivers() as $driver) {
            $driver->quit();
        }
    }
}

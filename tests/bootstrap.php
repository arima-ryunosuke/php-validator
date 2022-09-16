<?php
namespace ryunosuke\Test;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../vendor/ryunosuke/phpunit-extension/inc/bootstrap.php';

\ryunosuke\PHPUnit\Actual::$constraintVariations['isValid'] = false;
\ryunosuke\PHPUnit\Actual::generateStub(__DIR__ . '/../src', __DIR__ . '/.stub');

defined('TESTWEB_URL') or define('TESTWEB_URL', 'http://localhost:9999');
defined('SELENIUM_URL') or define('SELENIUM_URL', 'http://localhost:9999');
defined('BROWSERS') or define('BROWSERS', 'phantomjs');

$custom = __DIR__ . '/Test/UnitTest/chmonos/Condition/_files/Test';
require_once("$custom/CustomCondition.php");
require_once("$custom/CustomFileCondition.php");
require_once("$custom/CustomDependFileCondition.php");
\ryunosuke\chmonos\Condition\AbstractCondition::setNamespace(['custom\\Condition' => $custom]);

class CustomInput extends \ryunosuke\chmonos\Input
{
}

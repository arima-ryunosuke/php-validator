<?php
namespace ryunosuke\Test;

error_reporting(~E_DEPRECATED);

require_once __DIR__ . '/../vendor/autoload.php';

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

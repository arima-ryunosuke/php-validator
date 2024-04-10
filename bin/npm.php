<?php

require_once __DIR__ . '/../tests/functions.php';

$npmdir = __DIR__ . '/../npm/' . PHP_OS_FAMILY;
@mkdir($npmdir, 0777, true);
$npmdir = realpath($npmdir);

file_put_contents("$npmdir/package.json", json_encode([
    "name"         => "dummy",
    "version"      => "1.0.0",
    "description"  => "dummy",
    "repository"   => "dummy",
    "license"      => "MIT",
    "dependencies" => [
        "locutus" => "2.0.32",
    ],
], JSON_PRETTY_PRINT));

set_include_path(getenv('PATH'));

\ryunosuke\chmonos\process(stream_resolve_include_path('npm'), 'install --no-bin-links', '', $stdout, $stderr, $npmdir);
fwrite(STDOUT, $stdout);
fwrite(STDERR, $stderr);
\ryunosuke\chmonos\cp_rf("$npmdir/node_modules/locutus/php", dirname(__DIR__) . "/src/template/phpjs/locutus");
array_map('unlink', \ryunosuke\chmonos\globstar(dirname(__DIR__) . "/src/template/phpjs/locutus/**.map"));

<?php
require __DIR__ . '/../vendor/autoload.php';
$g = new \OpenApi\Generator();
$openapi = $g->generate([__DIR__ . '/../app'], null, false);
if ($openapi) {
    echo "OPENAPI OK\n";
    if ($openapi->info) {
        echo "INFO: " . $openapi->info->title . "\n";
    } else {
        echo "NO INFO\n";
    }
} else {
    echo "NO OPENAPI\n";
}
$analysis = new ReflectionClass($g);
echo "Generator constructed\n";

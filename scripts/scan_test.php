<?php
require __DIR__ . '/../vendor/autoload.php';
$analysis = OpenApi\scan(__DIR__ . '/../app');
$found = false;
foreach ($analysis->getAnnotations() as $a) {
    if ($a instanceof OpenApi\Annotations\Info) {
        echo "FOUND INFO\n";
        $found = true;
    }
}
if (!$found) echo "NO INFO\n";
echo "Counts: " . count($analysis->getAnnotations()) . "\n";

foreach ($analysis->getAnnotations() as $a) {
    echo get_class($a) . "\n";
}

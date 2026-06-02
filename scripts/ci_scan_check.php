<?php
require __DIR__ . '/../vendor/autoload.php';
echo "PHP: " . phpversion() . "\n";
$path = __DIR__ . '/../app/Swagger/ApiInfo.php';
echo "ApiInfo exists: " . (file_exists($path) ? 'yes' : 'no') . "\n";
$g = new \OpenApi\Generator();
try {
    $openapi = $g->generate([__DIR__ . '/../app'], null, false);
    if ($openapi && $openapi->info) {
        echo "Found Info: " . ($openapi->info->title ?? 'no title') . "\n";
    } else {
        echo "No Info found in generate()\n";
    }
    echo "Generation OK.\n";
} catch (Throwable $e) {
    echo "Generator exception: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
}

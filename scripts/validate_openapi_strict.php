<?php
require __DIR__ . '/../vendor/autoload.php';

use cebe\openapi\Reader;

$file = __DIR__ . '/../storage/api-docs/api-docs.json';
if (! file_exists($file)) {
    fwrite(STDERR, "api-docs.json not found: $file\n");
    exit(1);
}

try {
    $openapi = Reader::readFromJsonFile($file);
    $errors = $openapi->validate();
    if (! empty($errors)) {
        fwrite(STDERR, "Strict validation failed:\n");
        foreach ($errors as $e) {
            fwrite(STDERR, "- $e\n");
        }
        exit(1);
    }
    fwrite(STDOUT, "Strict OpenAPI validation passed\n");
    exit(0);
} catch (Throwable $e) {
    fwrite(STDERR, "Exception during strict validation: " . $e->getMessage() . "\n");
    exit(1);
}

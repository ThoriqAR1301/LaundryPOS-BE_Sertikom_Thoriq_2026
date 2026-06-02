<?php
$file = __DIR__ . '/../storage/api-docs/api-docs.json';
if (! file_exists($file)) {
    fwrite(STDERR, "api-docs.json not found: $file\n");
    exit(1);
}
$json = file_get_contents($file);
$data = json_decode($json, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    fwrite(STDERR, "Invalid JSON in api-docs.json: " . json_last_error_msg() . "\n");
    exit(1);
}

$errs = [];
if (empty($data['openapi'])) {
    $errs[] = 'Missing top-level "openapi" field';
}
if (empty($data['info']) || empty($data['info']['title']) || empty($data['info']['version'])) {
    $errs[] = 'Missing info.title or info.version';
}
if (empty($data['paths']) || ! is_array($data['paths'])) {
    $errs[] = 'Missing or invalid paths object';
}

if (! empty($errs)) {
    foreach ($errs as $e) {
        fwrite(STDERR, "Error: $e\n");
    }
    exit(1);
}

$pathsCount = is_array($data['paths']) ? count($data['paths']) : 0;
$schemasCount = isset($data['components']['schemas']) && is_array($data['components']['schemas']) ? count($data['components']['schemas']) : 0;
fwrite(STDOUT, "OpenAPI OK: openapi={$data['openapi']} paths={$pathsCount} schemas={$schemasCount}\n");

// Additional checks: each path must have at least one operation and all $ref targets must exist
$errs = [];
// check operations
foreach ($data['paths'] as $p => $ops) {
    $hasOp = false;
    if (is_array($ops)) {
        foreach ($ops as $k => $v) {
            if (in_array(strtolower($k), ['get','post','put','delete','patch','options','head'], true)) {
                $hasOp = true;
                // ensure responses exist
                if (empty($v['responses']) || ! is_array($v['responses'])) {
                    $errs[] = "Path $p operation $k has no responses";
                }
            }
        }
    }
    if (! $hasOp) {
        $errs[] = "Path $p has no operations";
    }
}

// collect component schema names
$components = [];
if (! empty($data['components']['schemas']) && is_array($data['components']['schemas'])) {
    $components = array_keys($data['components']['schemas']);
}

// find all $ref occurrences
$refs = [];
$iter = new RecursiveIteratorIterator(new RecursiveArrayIterator($data));
foreach ($iter as $key => $value) {
    if ($key === '$ref') {
        $refs[] = $value;
    }
}

foreach ($refs as $r) {
    // only care about component schema refs like #/components/schemas/Name
    $prefix = '#/components/schemas/';
    if (is_string($r) && str_starts_with($r, $prefix)) {
        $name = substr($r, strlen($prefix));
        if (! in_array($name, $components, true)) {
            $errs[] = "Missing component schema referenced: $name";
        }
    }
}

if (! empty($errs)) {
    foreach ($errs as $e) {
        fwrite(STDERR, "Validation error: $e\n");
    }
    exit(1);
}

fwrite(STDOUT, "Extended OpenAPI checks passed\n");
exit(0);

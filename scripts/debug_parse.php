<?php
require __DIR__ . '/../vendor/autoload.php';

use OpenApi\Generator;
use OpenApi\Analysers\DocBlockAnnotationFactory;
use OpenApi\Analysers\ReflectionAnalyser;
use OpenApi\Context;

$gen = new Generator();
$gen->setAnalyser(new ReflectionAnalyser([new \OpenApi\Analysers\AttributeAnnotationFactory(), new \OpenApi\Analysers\DocBlockAnnotationFactory()]));

$factory = new DocBlockAnnotationFactory();
$factory->setGenerator($gen);

$classes = [
    'App\\Swagger\\ApiInfo',
    'App\\Swagger\\Components',
];

$ok = true;
// ApiInfo is required; other classes are optional
$required = [
    'App\\Swagger\\ApiInfo',
];

// seed classes
foreach ($classes as $class) {
    if (! class_exists($class)) {
        echo "Class $class not found\n";
        if (in_array($class, $required, true)) {
            echo "Required class $class is missing\n";
            $ok = false;
        }
        continue;
    }
    $ref = new ReflectionClass($class);
    $ctx = new Context([
        'filename' => $ref->getFileName(),
        'class' => $ref->getShortName(),
        'namespace' => $ref->getNamespaceName(),
        'scanned' => ['uses' => ['OA' => 'OpenApi\\Annotations']],
    ]);

    try {
        $anns = $factory->build($ref, $ctx);
        $count = is_array($anns) ? count($anns) : 0;
        echo "Class $class -> $count annotations\n";
        if ($count === 0) {
            echo "No annotations parsed for $class\n";
            $ok = false;
        } else {
            foreach ($anns as $a) {
                echo get_class($a) . "\n";
            }
        }
    } catch (Throwable $e) {
        echo "EX in $class: " . $e->getMessage() . "\n";
        $ok = false;
    }
}

// Scan controllers and models for annotations
$scanDirs = [
    __DIR__ . '/../app/Http/Controllers',
    __DIR__ . '/../app/Models',
];

$processed = [];
foreach ($scanDirs as $dir) {
    if (! is_dir($dir)) {
        continue;
    }
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($it as $file) {
        if (! $file->isFile()) {
            continue;
        }
        if ($file->getExtension() !== 'php') {
            continue;
        }
        $path = $file->getPathname();
        // derive FQCN from path: app/.../File.php -> App\\...\\File
        $rel = substr($path, strlen(__DIR__ . '/../'));
        $rel = substr($rel, 0, -4); // remove .php
        $class = str_replace('/', '\\', $rel);
        $class = preg_replace('#^app\\\\#', 'App\\\\', $class);

        if (in_array($class, $processed, true)) {
            continue;
        }
        $processed[] = $class;

        // Only validate API controllers and models
        if (! (str_starts_with($class, 'App\\Http\\Controllers\\Api\\') || str_starts_with($class, 'App\\Models\\')) ) {
            // skip non-API controllers / other classes
            continue;
        }

        if (! class_exists($class)) {
            echo "Class $class not found (from $path)\n";
            $ok = false;
            continue;
        }

        $ref = new ReflectionClass($class);
        $ctx = new Context([
            'filename' => $ref->getFileName(),
            'class' => $ref->getShortName(),
            'namespace' => $ref->getNamespaceName(),
            'scanned' => ['uses' => ['OA' => 'OpenApi\\Annotations']],
        ]);
        try {
            $anns = $factory->build($ref, $ctx);
            $count = is_array($anns) ? count($anns) : 0;
            // also inspect methods for OA annotations (PathItem, Operation, etc.)
            $methodCount = 0;
            foreach ($ref->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
                if ($method->getDeclaringClass()->getName() !== $ref->getName()) {
                    continue;
                }
                $mctx = new Context([
                    'filename' => $method->getFileName(),
                    'class' => $ref->getShortName(),
                    'namespace' => $ref->getNamespaceName(),
                    'method' => $method->getName(),
                    'scanned' => ['uses' => ['OA' => 'OpenApi\\Annotations']],
                ]);
                $manns = $factory->build($method, $mctx);
                $methodCount += is_array($manns) ? count($manns) : 0;
            }
            $total = $count + $methodCount;
            echo "Class $class -> $total annotations (class:$count method:$methodCount)\n";
            // If this is an API controller but has no method or class docblocks, skip (likely uses inline annotations)
            $isApiController = str_starts_with($class, 'App\\Http\\Controllers\\Api\\');
            if ($isApiController) {
                $hasDoc = false;
                if ($ref->getDocComment()) {
                    $hasDoc = true;
                } else {
                    foreach ($ref->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
                        if ($method->getDeclaringClass()->getName() !== $ref->getName()) {
                            continue;
                        }
                        if ($method->getDocComment()) {
                            $hasDoc = true;
                            break;
                        }
                    }
                }
                if (! $hasDoc) {
                    echo "Skipping $class — no method/class docblocks to parse (inline annotations possible)\n";
                    continue;
                }
            }

            if ($total === 0) {
                echo "No annotations parsed for $class\n";
                $ok = false;
            }
        } catch (Throwable $e) {
            echo "EX in $class: " . $e->getMessage() . "\n";
            $ok = false;
        }
    }
}

if (! $ok) {
    echo "One or more annotation checks failed.\n";
    exit(1);
}
echo "Annotation sanity checks passed.\n";
exit(0);

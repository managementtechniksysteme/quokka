<?php

namespace Tests\Unit\Architecture;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionMethod;

/**
 * authorizeResource() only wires a `can:` middleware check for methods present
 * in resourceAbilityMap(). Any route-reachable public method missing from that
 * map (and not manually authorized in its own body) gets zero authorization -
 * the single most-repeated bug class found while backfilling this app's tests.
 */
test('every route action on an authorizeResource() controller is covered by its ability map or a manual authorize() call', function () {
    $violations = [];

    foreach (File::allFiles(app_path('Http/Controllers')) as $file) {
        $source = $file->getContents();

        if (! str_contains($source, 'authorizeResource(')) {
            continue;
        }

        $namespace = Str::of($file->getPath())
            ->after(app_path())
            ->replace(DIRECTORY_SEPARATOR, '\\')
            ->prepend('App');

        $class = $namespace.'\\'.$file->getFilenameWithoutExtension();

        if (! class_exists($class)) {
            continue;
        }

        $reflection = new ReflectionClass($class);

        if ($reflection->isAbstract()) {
            continue;
        }

        $mapMethod = $reflection->getMethod('resourceAbilityMap');
        $mapMethod->setAccessible(true);
        $abilityMap = $mapMethod->invoke(app($class));

        $routedActions = collect(Route::getRoutes())
            ->map(fn ($route) => $route->getActionName())
            ->filter(fn ($action) => Str::startsWith($action, $class.'@'))
            ->map(fn ($action) => Str::after($action, '@'))
            ->unique();

        $ownMethods = collect($reflection->getMethods(ReflectionMethod::IS_PUBLIC))
            ->filter(fn (ReflectionMethod $method) => $method->getDeclaringClass()->getName() === $class && ! $method->isConstructor())
            ->map(fn (ReflectionMethod $method) => $method->getName());

        foreach ($ownMethods->intersect($routedActions) as $method) {
            if (array_key_exists($method, $abilityMap)) {
                continue;
            }

            $methodReflection = $reflection->getMethod($method);
            $lines = file($file->getRealPath());
            $methodSource = implode('', array_slice(
                $lines,
                $methodReflection->getStartLine() - 1,
                $methodReflection->getEndLine() - $methodReflection->getStartLine() + 1
            ));

            // Customer-facing routes (e.g. `customerSign`) authorize via an
            // unguessable per-request token instead of a Gate/Policy check.
            if (str_contains($methodSource, '->authorize(') || str_contains($methodSource, 'Gate::') || str_contains($methodSource, '::fromToken(')) {
                continue;
            }

            $violations[] = "{$class}@{$method}";
        }
    }

    expect($violations)->toBe([]);
});

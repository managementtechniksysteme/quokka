<?php

namespace App\Support\GlobalSearch;

use Illuminate\Support\Str;

class CrossReferenceResolver
{
    public static function tokenFor(string $model, mixed $id): string
    {
        return Str::kebab(class_basename($model)) . '-' . $id;
    }

    public static function resolve(string $token): ?GlobalSearchResult
    {
        if (!preg_match('/^(.+)-(\d+)$/', $token, $matches)) {
            return null;
        }

        [, $slug, $id] = $matches;

        $model = collect(config('search.models'))
            ->first(fn ($model) => Str::kebab(class_basename($model)) === $slug);

        if (!$model || !in_array(FiltersGlobalSearch::class, class_implements($model))) {
            return null;
        }

        return forward_static_call([$model, FiltersGlobalSearch::RESOLVE_METHOD], $id);
    }
}

<?php

namespace App\Traits;

use Illuminate\Support\Collection;

/**
 * UI-facing metadata and value suggestions for the filter-query autocomplete overlay
 * (FilterSearchInput.vue), derived from a model's existing $filterKeys (see FiltersSearch)
 * rather than a hand-maintained duplicate. Originally written for Task, generalized here
 * once a second model needed the same behavior.
 */
trait HasFilterMetadata
{
    /**
     * Literal keys sharing the same text before their first ':' are grouped into one
     * enumerable key (e.g. every 'ist:*' entry groups under 'ist'). Keys with a capture
     * group (any '(', not just the literal '(.*)' used by most of them -- a handful of
     * domains use a different pattern like '(\d+)') become free-value lookups. Labels
     * come from $filterKeyLabels with a humanized fallback, so a new $filterKeys entry
     * never breaks this, only looks plainer until a label is added.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function filterKeyMetadata(): array
    {
        $labels = (new static)->filterKeyLabels;
        $filterKeys = (new static)->filterKeys;

        $enumGroups = [];
        $lookups = [];
        $seenLookupLabels = [];

        foreach (array_keys($filterKeys) as $key) {
            $captureStart = strpos($key, '(');

            if ($captureStart !== false) {
                $prefix = substr($key, 0, $captureStart);
                $label = $labels[$key] ?? ucfirst(rtrim($prefix, ':'));

                // Aliases (e.g. 'projekt:'/'p:') share a label and mean the same
                // thing -- kept in full (still valid to type/search on and still
                // recognized/colored), just flagged so the frontend only lists
                // the first one as its own dropdown row.
                $duplicate = in_array($label, $seenLookupLabels, true);
                $seenLookupLabels[] = $label;

                $lookups[$prefix] = [
                    'prefix' => $prefix,
                    'kind' => 'lookup',
                    'label' => $label,
                    'duplicate' => $duplicate,
                ];
            } elseif (str_contains($key, ':')) {
                [$groupPrefix, $value] = explode(':', $key, 2);
                $label = $labels[$key] ?? ucfirst(str_replace('_', ' ', $value));

                // Same reasoning for enum aliases (e.g. 'ist:nicht_verrechnet' /
                // 'ist:nv') -- both stay in the data (still recognized/colored
                // when typed), only flagged as a duplicate for display purposes.
                $duplicate = collect($enumGroups[$groupPrefix]['values'] ?? [])->contains('label', $label);

                $enumGroups[$groupPrefix]['values'][] = ['value' => $value, 'label' => $label, 'duplicate' => $duplicate];
            }
        }

        $groupedKeys = collect($enumGroups)->map(fn ($group, $prefix) => [
            'prefix' => $prefix.':',
            'kind' => 'enum',
            'label' => $labels[$prefix] ?? ucfirst($prefix),
            'values' => $group['values'],
        ])->values();

        return $groupedKeys->concat(array_values($lookups))->all();
    }

    /**
     * Dynamic value candidates for a lookup prefix (e.g. 'p:'), scoped through filterPermissions()
     * so a value can only be suggested if it appears on a record the current user can already see.
     * The relation/column to search is read back from $filterKeys itself, not a separate mapping.
     *
     * Handles three $filterKeys value shapes:
     *  - ['relation.column', '%{value}%', ...]        -- normal related-model lookup
     *  - ['column', '{value}']                         -- bare model column, no relation at all
     *  - ['hasraw' => [$relation, 'concat(first_name, " ", last_name) like "%{value}%"', ...]]
     *    -- a person-name search; every occurrence of this shape in this codebase uses the exact
     *    same raw SQL, so it's handled as one generic branch rather than per-model bespoke code.
     *    A hasraw shape that doesn't match this known pattern returns [] rather than guessing.
     *
     * @return array<int, string>
     */
    public static function filterSuggestionValues(string $prefix, string $term): array
    {
        $term = trim($term);

        if ($term === '') {
            return [];
        }

        $filterKeys = (new static)->filterKeys;
        $definition = null;

        foreach ($filterKeys as $key => $value) {
            $captureStart = strpos($key, '(');

            if ($captureStart !== false && substr($key, 0, $captureStart) === $prefix) {
                $definition = $value;
                break;
            }
        }

        if (!$definition) {
            return [];
        }

        if (isset($definition['hasraw'])) {
            return self::filterSuggestionValuesForHasRaw($definition['hasraw'], $term);
        }

        $path = $definition[0];
        $lastDot = strrpos($path, '.');

        if ($lastDot === false) {
            $values = self::filterPermissionsOrQuery()
                ->where($path, 'LIKE', "%{$term}%")
                ->pluck($path)
                ->all();

            return self::finalizeSuggestionValues($values);
        }

        $relation = substr($path, 0, $lastDot);
        $column = substr($path, $lastDot + 1);

        // The eager-load must carry the same constraint as whereHas() -- for a
        // to-many relation, whereHas() only filters which PARENT rows match (at
        // least one related row matches), so an unconstrained ->with($relation)
        // would pull every sibling of a matching row too, not just the ones that
        // actually matched the search term.
        $models = self::filterPermissionsOrQuery()
            ->whereHas($relation, fn ($query) => $query->where($column, 'LIKE', "%{$term}%"))
            ->with([$relation => fn ($query) => $query->where($column, 'LIKE', "%{$term}%")])
            ->get();

        $values = [];

        foreach ($models as $model) {
            $values = array_merge($values, self::extractRelationColumn($model, explode('.', $relation), $column));
        }

        return self::finalizeSuggestionValues($values);
    }

    private static function filterSuggestionValuesForHasRaw(array $hasraw, string $term): array
    {
        $relation = $hasraw[0];
        $rawSql = $hasraw[1] ?? '';

        if (!str_starts_with($rawSql, 'concat(first_name, " ", last_name)')) {
            return [];
        }

        $constraint = fn ($query) => $query->whereRaw('concat(first_name, " ", last_name) LIKE ?', ["%{$term}%"]);

        // Same reasoning as the plain-path branch above: constrain the eager-load
        // too, not just whereHas(), so a to-many relation only returns the people
        // who actually matched, not every sibling of a matching report.
        $models = self::filterPermissionsOrQuery()
            ->whereHas($relation, $constraint)
            ->with([$relation => $constraint])
            ->get();

        $values = [];

        foreach ($models as $model) {
            $people = self::extractRelationColumn($model, explode('.', $relation), null);

            foreach ($people as $person) {
                $values[] = trim("{$person->first_name} {$person->last_name}");
            }
        }

        return self::finalizeSuggestionValues($values);
    }

    /**
     * Suggestion values must always come back as strings regardless of the
     * underlying column's cast (e.g. an 'integer'-cast column would otherwise
     * pluck() as an int) -- they're inserted as literal text into the search
     * input, so a consistent return type matters more than preserving the
     * original PHP type.
     *
     * @return array<int, string>
     */
    private static function finalizeSuggestionValues(array $values): array
    {
        return collect($values)
            ->map(fn ($value) => (string) $value)
            ->filter(fn ($value) => $value !== '')
            ->unique()
            ->sort()
            ->values()
            ->take(8)
            ->all();
    }

    /**
     * Not every model using this trait also scopes rows by ownership tier
     * (e.g. DeliveryNote has no FiltersPermissions/$permissionFilters at all --
     * access there is a flat viewAny gate, not per-row scoping) -- fall back to
     * an unscoped query in that case rather than fatal-erroring on a missing scope.
     */
    private static function filterPermissionsOrQuery()
    {
        return method_exists(static::class, 'scopeFilterPermissions') ? self::filterPermissions() : self::query();
    }

    /**
     * Walks a dotted relation chain on a loaded model, transparently handling to-many
     * relations (Collections) at any depth, to collect every matching column value --
     * or, when $column is null, the leaf related models themselves.
     *
     * @return array<int, mixed>
     */
    private static function extractRelationColumn($model, array $segments, ?string $column): array
    {
        if (empty($segments)) {
            return [$column === null ? $model : $model->{$column}];
        }

        $related = $model->{array_shift($segments)};

        if ($related instanceof Collection) {
            $values = [];

            foreach ($related as $item) {
                $values = array_merge($values, self::extractRelationColumn($item, $segments, $column));
            }

            return $values;
        }

        if ($related === null) {
            return [];
        }

        return self::extractRelationColumn($related, $segments, $column);
    }
}

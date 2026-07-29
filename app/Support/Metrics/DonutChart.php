<?php

namespace App\Support\Metrics;

use Illuminate\Support\Collection;

class DonutChart
{
    private const COLORS = ['--q-accent', '--q-sky', '--q-violet', '--q-amber', '--q-green', '--q-red'];
    private const REST_COLOR = '--q-faint';
    private const REST_LABEL = 'Sonstige';

    /**
     * @return array{total: float, segments: Collection}
     */
    public static function segments(Collection $items, string $valueKey, float $radius = 58): array
    {
        $total = $items->sum($valueKey);
        $circumference = 2 * M_PI * $radius;
        $offset = 0.0;

        $segments = $items->values()->map(function ($item, $index) use ($total, $circumference, &$offset, $valueKey) {
            $value = $item->{$valueKey};
            $share = $total > 0 ? $value / $total : 0;
            $length = $share * $circumference;

            $segment = (object) [
                'label' => $item->label,
                'value' => $value,
                'percentage' => (int) round($share * 100),
                'color' => $item->label === self::REST_LABEL ? self::REST_COLOR : self::COLORS[$index % count(self::COLORS)],
                'dasharray' => round($length, 1).' '.round($circumference - $length, 1),
                'dashoffset' => round(-$offset, 1),
            ];

            $offset += $length;

            return $segment;
        });

        return ['total' => $total, 'segments' => $segments];
    }
}

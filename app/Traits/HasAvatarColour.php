<?php

namespace App\Traits;

trait HasAvatarColour
{
    /**
     * A stable colour key for the entity's initials avatar, derived by hashing
     * its name into the fixed --q-* palette. The same entity always resolves to
     * the same colour. Use in a view as: q-avatar--{{ $model->avatar_colour }}.
     */
    public function getAvatarColourAttribute(): string
    {
        $palette = ['accent', 'sky', 'violet', 'green', 'amber', 'red'];

        $source = $this->full_name ?? $this->name ?? (string) $this->getKey();

        return $palette[crc32($source) % count($palette)];
    }
}

<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\MassPrunable;

class Notification extends Model
{
    use MassPrunable;

    public function prunable(): Builder
    {
        // feels like a hack
        if(!ApplicationSettings::get()->prune_read_notifications) {
            return static::whereNotNull('read_at')
                ->whereDate('read_at', '>', Carbon::tomorrow());
        }

        return static::whereNotNull('read_at')
            ->whereDate('read_at', '<', Carbon::today()->subMonth());
    }
}

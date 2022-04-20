<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSettings extends Model
{
    protected $casts = [
        'user_id' => 'int',
        'list_pagination_size' => 'int',
        'show_finished_items' => 'bool',
        'task_comments_sort_newest_first' => 'bool',
        'accounting_expand_errors' => 'bool',
        'accounting_filter_default_days' => 'int',
    ];

    protected $fillable = [
        'user_id',
        'avatar_colour',
        'list_pagination_size',
        'show_finished_items',
        'task_comments_sort_newest_first',
        'accounting_expand_errors',
        'accounting_filter_default_days',
    ];

    protected $primaryKey = 'user_id';
    public $incrementing = false;

    public const avatarColours = [
        ['color' => '#adb5bd', 'label' => 'gray'],
        ['color' => '#0d6efd', 'label' => 'blue'],
        ['color' => '#6610f2', 'label' => 'indigo'],
        ['color' => '#6f42c1', 'label' => 'purple'],
        ['color' => '#d63384', 'label' => 'pink'],
        ['color' => '#dc3545', 'label' => 'red'],
        ['color' => '#fd7e14', 'label' => 'orange'],
        ['color' => '#ffc107', 'label' => 'yellow'],
        ['color' => '#28a745', 'label' => 'green'],
        ['color' => '#20c997', 'label' => 'teal'],
        ['color' => '#17a2b8', 'label' => 'cyan'],
    ];

    public static function avatarColourFromHex(string $colour)
    {
        return static::avatarColours[
            array_search($colour, array_column(static::avatarColours, 'color'))
        ];
    }

    public static function avatarColourFromName(string $colour)
    {
        return static::avatarColours[
        array_search($colour, array_column(static::avatarColours, 'label'))
        ];
    }
}

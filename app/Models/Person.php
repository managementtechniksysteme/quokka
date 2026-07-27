<?php

namespace App\Models;

use App\Support\GlobalSearch\FiltersGlobalSearch;
use App\Support\GlobalSearch\GlobalSearchResult;
use App\Traits\FiltersLatestChanges;
use App\Traits\FiltersSearch;
use App\Traits\HasAvatarColour;
use App\Traits\HasFilterMetadata;
use App\Traits\OrdersResults;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class Person extends Model implements FiltersGlobalSearch
{
    use FiltersLatestChanges;
    use FiltersSearch;
    use HasAvatarColour;
    use HasFactory;
    use HasFilterMetadata;
    use Notifiable;
    use OrdersResults;

    protected $appends = [
        'name',
        'short_name',
        'avatar',
    ];

    protected $fillable = [
        'first_name', 'last_name', 'title_prefix', 'title_suffix', 'gender', 'department', 'role', 'phone_company',
        'phone_mobile', 'phone_private', 'fax', 'email', 'website', 'comment', 'company_id',
    ];

    protected $filterFields = [
        'first_name', 'last_name', 'department', 'role', 'company.name',
    ];

    protected $filterKeys = [
        'firma:(.*)' => ['company.name', '%{value}%', 'LIKE', 'NOT LIKE'],
        'f:(.*)' => ['company.name', '%{value}%', 'LIKE', 'NOT LIKE'],
    ];

    protected $filterKeyLabels = [
        'firma:(.*)' => 'Firma',
        'f:(.*)' => 'Firma',
    ];

    protected $orderKeys = [
        'default' => ['first_name', 'last_name'],
        'first-name-asc' => ['first_name', 'last_name'],
        'first-name-desc' => [['first_name', 'desc'], ['last_name', 'desc']],
        'last-name-asc' => ['last_name', 'first_name'],
        'last-name-desc' => [['last_name', 'desc'], ['first_name', 'desc']],
    ];

    public static function filterGlobalSearch(string $query, ?int $latestQuantity = null) : Collection
    {
        return Person::filterSearch($query)
            ->when($latestQuantity && $latestQuantity > 0, function ($query) use ($latestQuantity) {
                return $query->latest('updated_at')->limit($latestQuantity);
            })
            ->get()
            ->map(function(Person $person) {
                return new GlobalSearchResult(
                    Person::class,
                    'Person',
                    $person->id,
                    $person->name,
                    route('people.show', $person),
                    $person->created_at,
                    $person->updated_at,
                );
            });
    }

    public static function resolveGlobalSearchResult(int|string $id): ?GlobalSearchResult
    {
        if (Auth::user()->cannot('viewAny', Person::class)) {
            return null;
        }

        $person = Person::find($id);

        if (!$person) {
            return null;
        }

        return new GlobalSearchResult(
            Person::class,
            'Person',
            $person->id,
            $person->name,
            route('people.show', $person),
            $person->created_at,
            $person->updated_at,
        );
    }

    public function address()
    {
        return $this->morphToMany(Address::class, 'addressable')->wherePivot('address_type', 'private');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    public function memosPresentIn()
    {
        return $this->morphedByMany(Memo::class, 'personable')->wherePivot('person_type', 'present');
    }

    public function memosNotifiedBy()
    {
        return $this->morphedByMany(Memo::class, 'personable')->wherePivot('person_type', 'notified');
    }

    public function memosReceived()
    {
        return $this->hasMany(Memo::class);
    }

    public function additionsReportsPresentIn()
    {
        return $this->morphedByMany(AdditionsReport::class, 'personable')->wherePivot('person_type', 'present');
    }

    public function getNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getInitialsAttribute()
    {
        return \Illuminate\Support\Str::of($this->name)->explode(' ')->filter()
            ->take(2)->map(fn ($word) => \Illuminate\Support\Str::substr($word, 0, 1))->implode('');
    }

    // "Vorname N." — compact display for narrow table columns (accounting/logbook).
    public function getShortNameAttribute()
    {
        return $this->last_name
            ? "{$this->first_name} " . \Illuminate\Support\Str::substr($this->last_name, 0, 1) . '.'
            : $this->first_name;
    }

    /**
     * Round-avatar data for the JS components. Mirrors partials/employee_avatar:
     * when this person is shown as an employee (its employee.user relation is
     * eager-loaded) it uses the user's chosen colour + username initials; otherwise
     * the person's hashed colour + name initials. Relation-aware so serializing a
     * list of plain people triggers no employee/user queries.
     */
    public function getAvatarAttribute()
    {
        $user = ($this->relationLoaded('employee') && $this->employee && $this->employee->relationLoaded('user'))
            ? $this->employee->user
            : null;

        return [
            'initials' => $user ? $user->username_avatar_string : $this->initials,
            'colour' => $this->avatar_colour,
            'hex' => $user?->avatar_colour_hex,
        ];
    }

    public function mailableEntity()
    {
        $mailable = $this->replicate();
        $mailable->id = $this->id;

        return $mailable->only(['id', 'name', 'email']);
    }
}

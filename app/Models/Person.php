<?php

namespace Liamtseva\Cinema\Models;

use Database\Factories\PersonFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Facades\DB;
use Liamtseva\Cinema\Enums\Gender;
use Liamtseva\Cinema\Enums\PersonType;
use Liamtseva\Cinema\Models\Traits\HasSeo;

/**
 * @mixin IdeHelperPerson
 */
class Person extends Model
{
    /** @use HasFactory<PersonFactory> */
    use HasFactory, HasSeo, HasUlids;

    public function scopeByType(Builder $query, PersonType $type): Builder
    {
        return $query->where('type', $type->value);
    }

    public function scopeByName(Builder $query, string $name): Builder
    {
        return $query->where('name', 'like', '%'.$name.'%');
    }

    // TODO: fulltext

    public function scopeByGender(Builder $query, string $gender): Builder
    {
        return $query->where('gender', $gender);
    }

    // TODO: ідея з поєднанням character_name сюди потенційно фіговий
    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query
            ->select('people.*') // Вибираємо колонки з таблиці `people`
            ->addSelect(DB::raw("ts_rank(people.searchable, websearch_to_tsquery('ukrainian', ?)) AS rank"))
            ->addSelect(DB::raw("ts_headline('ukrainian', people.name, websearch_to_tsquery('ukrainian', ?), 'HighlightAll=true') AS name_highlight"))
            ->addSelect(DB::raw("ts_headline('ukrainian', people.original_name, websearch_to_tsquery('ukrainian', ?), 'HighlightAll=true') AS original_name_highlight"))
            ->addSelect(DB::raw("ts_headline('ukrainian', people.description, websearch_to_tsquery('ukrainian', ?), 'HighlightAll=true') AS description_highlight"))
            ->addSelect(DB::raw("ts_headline('ukrainian', movie_person.character_name, websearch_to_tsquery('ukrainian', ?), 'HighlightAll=true') AS character_name_highlight"))
            ->addSelect(DB::raw('similarity(people.name, ?) AS similarity'))
            ->leftJoin('movie_person', 'people.id', '=', 'movie_person.person_id')
            ->whereRaw("people.searchable @@ websearch_to_tsquery('ukrainian', ?)", [$search, $search, $search, $search, $search, $search, $search])
            ->orWhereRaw('people.name % ?', [$search])
            ->orWhereRaw('movie_person.character_name % ?', [$search])
            ->orderByDesc('rank')
            ->orderByDesc('similarity');
    }

    public function movies(): BelongsToMany
    {
        //return $this->belongsToMany(Movie::class, 'movie_person')
        return $this->belongsToMany(Movie::class)
            ->withPivot('character_name');
    }

    public function userLists(): MorphMany
    {
        return $this->morphMany(UserList::class, 'listable');
    }

    public function selections(): MorphToMany
    {
        return $this->morphToMany(Selection::class, 'selectionable');
    }

    protected function casts(): array
    {
        return [
            'type' => PersonType::class,
            'gender' => Gender::class,
            'birthday' => 'date',
        ];
    }

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->original_name
                ? "{$this->name} ({$this->original_name})"
                : $this->name,
        );
    }

    protected function age(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->birthday
                ? now()->diffInYears($this->birthday)
                : null,
        );
    }
}

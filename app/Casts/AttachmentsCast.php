<?php

namespace Liamtseva\Cinema\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Liamtseva\Cinema\Enums\AttachmentType;
use Liamtseva\Cinema\ValueObjects\Attachment;

class AttachmentsCast implements CastsAttributes
{
    /**
     * @return Collection<Attachment>
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): Collection
    {
        $collection = collect(json_decode($value, true));

        return $collection->isNotEmpty() ? $collection
            ->map(fn ($item) => new Attachment(AttachmentType::from($item['type']), $item['src'])) : $collection;
    }

    /**
     * @param  Collection<Attachment>|array  $value
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): string
    {
        if (! $value instanceof Collection) {
            $value = collect($value);
        }

        return json_encode($value->map(fn (Attachment $a) => ['type' => $a->type->value, 'src' => $a->src])->toArray());
    }
}

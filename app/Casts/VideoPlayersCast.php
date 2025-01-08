<?php

namespace Liamtseva\Cinema\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Liamtseva\Cinema\Enums\VideoPlayerName;
use Liamtseva\Cinema\Enums\VideoQuality;
use Liamtseva\Cinema\ValueObjects\VideoPlayer;

class VideoPlayersCast implements CastsAttributes
{
    /**
     * @return Collection<VideoPlayer>
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): Collection
    {
        $collection = collect(json_decode($value, true));

        return $collection->isNotEmpty() ? $collection
            ->map(fn ($item) => new VideoPlayer(
                VideoPlayerName::from($item['name']),
                $item['url'],
                $item['file_url'],
                $item['dubbing'],
                VideoQuality::from($item['quality']),
                $item['locale_code'])) : $collection;
    }

    /**
     * @param  Collection<VideoPlayer>|array  $value
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (! $value instanceof Collection) {
            $value = collect($value);
        }

        return json_encode($value->map(fn (VideoPlayer $vp) => [
            'name' => $vp->name->value,
            'url' => $vp->url,
            'file_url' => $vp->file_url,
            'dubbing' => $vp->dubbing,
            'quality' => $vp->quality->value,
            'locale_code' => $vp->locale_code])->toArray());
    }
}

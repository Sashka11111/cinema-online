<?php

namespace Liamtseva\Cinema\ValueObjects;

use Liamtseva\Cinema\Enums\MovieRelateType;

class MovieRelate
{
    public function __construct(
        public string $movie_id,
        public MovieRelateType $type,
    ) {}
}

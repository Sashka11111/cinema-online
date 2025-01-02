<?php

namespace Liamtseva\Cinema\ValueObjects;

use Liamtseva\Cinema\Enums\ApiSourceName;

class ApiSource
{
    public function __construct(
        public ApiSourceName $name,
        public string $id,
    ) {}
}

<?php

namespace Liamtseva\Cinema\ValueObjects;

use Liamtseva\Cinema\Enums\AttachmentType;

class Attachment
{
    public function __construct(
        public AttachmentType $type,
        public string $src,
    ) {}
}

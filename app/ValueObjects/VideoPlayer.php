<?php

namespace Liamtseva\Cinema\ValueObjects;

use Liamtseva\Cinema\Enums\VideoPlayerName;
use Liamtseva\Cinema\Enums\VideoQuality;

class VideoPlayer
{
    public function __construct(
        public VideoPlayerName $name,
        public string $url,
        public string $file_url,
        public string $dubbing,
        public VideoQuality $quality,
        public string $locale_code
    ) {}
}

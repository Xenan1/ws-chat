<?php

namespace App\DTO;

use App\Parsing\ParseSources;

readonly class PostDataDTO
{
    public function __construct(
        public string        $text,
        public int           $authorId,
        /**
         * @var array<int> $tags
         */
        public array         $tags,
        public ?string       $parsedId = null,
        public ?ParseSources $parsedSource = null,
    ) {}
}

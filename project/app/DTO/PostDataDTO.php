<?php

namespace App\DTO;

readonly class PostDataDTO
{
    public function __construct(
        public string $text,
        public int $authorId,
        /**
         * @var array<int> $tags
         */
        public array $tags,
    ) {}
}

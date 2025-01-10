<?php

namespace App\DTO;

readonly class CommentDataDTO
{
    public function __construct(
        public string $text,
        public int $authorId,
        public int $postId,
    ) {}
}

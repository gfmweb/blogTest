<?php

declare(strict_types=1);

namespace App\Dto;

final readonly class ArticleDto
{
    /**
     * @param array<int, CategoryDto> $categories
     */
    public function __construct(
        public int $id,
        public string $slug,
        public ?string $image,
        public string $name,
        public string $description,
        public string $text,
        public int $viewCount,
        public ?string $publishedAt,
        public string $createdAt,
        public array $categories = [],
    ) {
    }
}

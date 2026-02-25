<?php

declare(strict_types=1);

namespace App\Mapper;

use App\Dto\ArticleDto;
use App\Dto\CategoryDto;

final class ArticleMapper
{
    public function __construct(
        private readonly CategoryMapper $categoryMapper,
    ) {
    }

    public function fromRow(array $row, array $categoryRows = []): ArticleDto
    {
        $categories = [];
        foreach ($categoryRows as $catRow) {
            $categories[] = $this->categoryMapper->fromRow($catRow);
        }

        return new ArticleDto(
            id: (int) $row['id'],
            slug: (string) ($row['slug'] ?? ''),
            image: isset($row['image']) ? (string) $row['image'] : null,
            name: (string) $row['name'],
            description: (string) ($row['description'] ?? ''),
            text: (string) $row['text'],
            viewCount: (int) ($row['view_count'] ?? 0),
            publishedAt: isset($row['published_at']) ? (string) $row['published_at'] : null,
            createdAt: (string) $row['created_at'],
            categories: $categories,
        );
    }
}

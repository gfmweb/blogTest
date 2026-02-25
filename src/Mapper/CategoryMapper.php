<?php

declare(strict_types=1);

namespace App\Mapper;

use App\Dto\CategoryDto;

final class CategoryMapper
{
    public function fromRow(array $row): CategoryDto
    {
        return new CategoryDto(
            id: (int) $row['id'],
            slug: (string) $row['slug'],
            name: (string) $row['name'],
            description: (string) ($row['description'] ?? ''),
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Dto;

final readonly class CategoryDto
{
    public function __construct(
        public int $id,
        public string $slug,
        public string $name,
        public string $description,
    ) {
    }
}

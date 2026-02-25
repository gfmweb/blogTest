<?php

declare(strict_types=1);

namespace App\ValueObject\Article;

final readonly class ArticleDescription
{
    public function __construct(string $value)
    {
        $this->value = trim($value);
    }

    private string $value;

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}

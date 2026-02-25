<?php

declare(strict_types=1);

namespace App\ValueObject\Article;

use InvalidArgumentException;

final readonly class ArticleName
{
    public function __construct(string $value)
    {
        $value = trim($value);
        if ($value === '') {
            throw new InvalidArgumentException('Название статьи не может быть пустым.');
        }
        $this->value = $value;
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

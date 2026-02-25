<?php

declare(strict_types=1);

namespace App\ValueObject\Article;

use App\ValueObject\SlugNormalizer;
use InvalidArgumentException;

final readonly class ArticleSlug
{
    private string $value;

    public function __construct(string $value)
    {
        $normalized = SlugNormalizer::normalize($value);
        if ($normalized === '') {
            throw new InvalidArgumentException('Некорректный slug статьи.');
        }
        $this->value = $normalized;
    }

    public static function fromName(string $name): self
    {
        $trimmed = trim($name);
        if ($trimmed === '') {
            throw new InvalidArgumentException('Название статьи не может быть пустым.');
        }
        $slug = SlugNormalizer::slugFromName($trimmed);
        if ($slug === '') {
            throw new InvalidArgumentException('Некорректный slug статьи.');
        }
        return new self($slug);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}

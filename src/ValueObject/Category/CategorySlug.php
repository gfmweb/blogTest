<?php

declare(strict_types=1);

namespace App\ValueObject\Category;

use App\ValueObject\SlugNormalizer;
use InvalidArgumentException;

final readonly class CategorySlug
{
    private string $value;

    public function __construct(string $value)
    {
        $normalized = SlugNormalizer::normalize($value);
        if ($normalized === '') {
            throw new InvalidArgumentException('Некорректный slug категории.');
        }
        $this->value = $normalized;
    }

    public static function fromName(string $name): self
    {
        $trimmed = trim($name);
        if ($trimmed === '') {
            throw new InvalidArgumentException('Название категории не может быть пустым.');
        }
        $slug = SlugNormalizer::slugFromName($trimmed);
        if ($slug === '') {
            throw new InvalidArgumentException('Некорректный slug категории.');
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

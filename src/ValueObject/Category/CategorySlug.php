<?php

declare(strict_types=1);

namespace App\ValueObject\Category;

use InvalidArgumentException;

final readonly class CategorySlug
{
    private const TRANSLIT_MAP = [
        'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh', 'з' => 'z',
        'и' => 'i', 'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r',
        'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'ts', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch',
        'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
    ];

    public function __construct(string $value)
    {
        $normalized = $this->normalize($value);
        if ($normalized === '') {
            throw new InvalidArgumentException('Некорректный slug категории.');
        }
        $this->value = $normalized;
    }

    private string $value;

    public static function fromName(string $name): self
    {
        $trimmed = trim($name);
        if ($trimmed === '') {
            throw new InvalidArgumentException('Название категории не может быть пустым.');
        }
        $translit = strtr(mb_strtolower($trimmed, 'UTF-8'), self::TRANSLIT_MAP);
        $slug = preg_replace('/[^a-z0-9]+/u', '-', $translit);
        $slug = trim($slug, '-');
        if ($slug === '') {
            throw new InvalidArgumentException('Некорректный slug категории.');
        }
        return new self($slug);
    }

    private function normalize(string $value): string
    {
        $value = trim($value);
        $value = mb_strtolower($value, 'UTF-8');
        $value = preg_replace('/[^a-z0-9\-]/u', '', $value) ?? $value;
        $value = preg_replace('/-+/', '-', $value) ?? $value;
        return trim($value, '-');
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

<?php

declare(strict_types=1);

namespace App\ValueObject;

final class SlugNormalizer
{
    private const TRANSLIT_MAP = [
        'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh', 'з' => 'z',
        'и' => 'i', 'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r',
        'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'ts', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch',
        'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
    ];

    public static function normalize(string $value): string
    {
        $value = trim($value);
        $value = mb_strtolower($value, 'UTF-8');
        $value = preg_replace('/[^a-z0-9\-]/u', '', $value) ?? $value;
        $value = preg_replace('/-+/', '-', $value) ?? $value;
        return trim($value, '-');
    }

    public static function slugFromName(string $name): string
    {
        $trimmed = trim($name);
        if ($trimmed === '') {
            return '';
        }
        $translit = strtr(mb_strtolower($trimmed, 'UTF-8'), self::TRANSLIT_MAP);
        $slug = preg_replace('/[^a-z0-9]+/u', '-', $translit);
        return trim($slug, '-');
    }
}

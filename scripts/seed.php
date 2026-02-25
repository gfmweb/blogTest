<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use App\Config\Database;
use App\ValueObject\Article\ArticleSlug;
use App\ValueObject\Category\CategorySlug;

$pdo = Database::getConnection();

$categories = [
    ['name' => 'Автомобили', 'description' => 'Статьи о авто'],
    ['name' => 'Лес', 'description' => 'Статьи о лесе'],
    ['name' => 'Дом', 'description' => 'Статьи о доме'],
    ['name' => 'Одинадцатиклассница', 'description' => 'Проверяем очень длинное название'],
];

$categoryIds = [];
$stmtCat = $pdo->prepare('INSERT INTO categories (name, slug, description) VALUES (?, ?, ?)');

foreach ($categories as $cat) {
    $slug = CategorySlug::fromName($cat['name'])->value();
    $stmtCat->execute([$cat['name'], $slug, $cat['description']]);
    $categoryIds[] = (int) $pdo->lastInsertId();
}

$articles = [
    [
        'name' => 'Лада седан',
        'description' => 'Баклажан',
        'text' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Culpa ut velit proident consectetur dolor consectetur elit incididunt laboris occaecat qui id mollit elit do. Reprehenderit exercitation amet irure sunt exercitation aute adipiscing nulla commodo pariatur laboris pariatur cupidatat.'
    ],
    [
        'name' => 'Случай в лесу',
        'description' => 'Интересный случай в лесу',
        'text' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Culpa ut velit proident consectetur dolor consectetur elit incididunt laboris occaecat qui id mollit elit do. Reprehenderit exercitation amet irure sunt exercitation aute adipiscing nulla commodo pariatur laboris pariatur cupidatat.'
    ],
    [
        'name' => 'Домашнее',
        'description' => 'Секреты домашнего уюта. Очень длинное описание. Уже фантазия кончилась. Хоть Lorem вставляй. Ужас какое длинное описание',
        'text' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Culpa ut velit proident consectetur dolor consectetur elit incididunt laboris occaecat qui id mollit elit do. Reprehenderit exercitation amet irure sunt exercitation aute adipiscing nulla commodo pariatur laboris pariatur cupidatat.'
    ],
];

$articleIds = [];
$stmtArt = $pdo->prepare('INSERT INTO articles (slug, image, name, description, text, view_count, published_at, created_at) VALUES (?, ?, ?, ?, ?, 0, NOW(), NOW())');

foreach ($articles as $art) {
    $slug = ArticleSlug::fromName($art['name'])->value();
    $stmtArt->execute([$slug, null, $art['name'], $art['description'], $art['text']]);
    $articleIds[] = (int) $pdo->lastInsertId();
}

$links = [
    [$articleIds[0], $categoryIds[0]],
    [$articleIds[1], $categoryIds[1]],
    [$articleIds[1], $categoryIds[2]],
    [$articleIds[2], $categoryIds[0]],
    [$articleIds[2], $categoryIds[3]],
];

$stmtLink = $pdo->prepare('INSERT INTO article_category (article_id, category_id) VALUES (?, ?)');
foreach ($links as $link) {
    $stmtLink->execute($link);
}

echo "Ok";

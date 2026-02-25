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

$lorem = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Culpa ut velit proident consectetur dolor consectetur elit incididunt laboris occaecat qui id mollit elit do. Reprehenderit exercitation amet irure sunt exercitation aute adipiscing nulla commodo pariatur laboris pariatur cupidatat.';

$articles = [
    ['name' => 'Лада седан', 'description' => 'Баклажан', 'text' => $lorem],
    ['name' => 'Случай в лесу', 'description' => 'Интересный случай в лесу', 'text' => $lorem],
    ['name' => 'Домашнее', 'description' => 'Секреты домашнего уюта. Очень длинное описание.', 'text' => $lorem],
    ['name' => 'Волга на реставрации', 'description' => 'Как восстановить классику', 'text' => $lorem],
    ['name' => 'Москвич и его история', 'description' => 'Легенда советского автопрома', 'text' => $lorem],
    ['name' => 'Грибы в осеннем лесу', 'description' => 'Сбор и приготовление', 'text' => $lorem],
    ['name' => 'Прогулка по заповеднику', 'description' => 'Маршруты и впечатления', 'text' => $lorem],
    ['name' => 'Птицы средней полосы', 'description' => 'Наблюдение за пернатыми', 'text' => $lorem],
    ['name' => 'Ремонт кухни своими руками', 'description' => 'С чего начать и что учесть', 'text' => $lorem],
    ['name' => 'Уютная гостиная', 'description' => 'Идеи оформления интерьера', 'text' => $lorem],
    ['name' => 'Домашний сад на подоконнике', 'description' => 'Зелень и травы круглый год', 'text' => $lorem],
    ['name' => 'Выпускной вечер', 'description' => 'Подготовка к празднику', 'text' => $lorem],
    ['name' => 'Учёба в одиннадцатом классе', 'description' => 'Советы будущим абитуриентам', 'text' => $lorem],
    ['name' => 'Экзамены без стресса', 'description' => 'Как подготовиться к ЕГЭ', 'text' => $lorem],
    ['name' => 'Запчасти для отечественных авто', 'description' => 'Где искать и как выбирать', 'text' => $lorem],
    ['name' => 'Зимняя резина и безопасность', 'description' => 'Выбор шин для холодного сезона', 'text' => $lorem],
    ['name' => 'Тропа здоровья', 'description' => 'Скандинавская ходьба в лесу', 'text' => $lorem],
    ['name' => 'Ягоды и варенье', 'description' => 'Рецепты из лесных даров', 'text' => $lorem],
    ['name' => 'Осенний лес и фотосессия', 'description' => 'Лучшие места для съёмки', 'text' => $lorem],
    ['name' => 'Балкон как жилое пространство', 'description' => 'Обустройство и утепление', 'text' => $lorem],
    ['name' => 'Детская комната', 'description' => 'Планировка и мебель', 'text' => $lorem],
    ['name' => 'Хранение вещей в маленькой квартире', 'description' => 'Практичные решения', 'text' => $lorem],
    ['name' => 'Школьная форма и стиль', 'description' => 'Как выглядеть аккуратно', 'text' => $lorem],
    ['name' => 'Классный час и традиции', 'description' => 'Идеи для сплочения класса', 'text' => $lorem],
    ['name' => 'Первый автомобиль в семье', 'description' => 'Покупка подержанного авто', 'text' => $lorem],
    ['name' => 'Пикник у реки', 'description' => 'Что взять и как организовать', 'text' => $lorem],
    ['name' => 'Дача выходного дня', 'description' => 'Дом и участок за городом', 'text' => $lorem],
    ['name' => 'Последний звонок', 'description' => 'Организация праздника в школе', 'text' => $lorem],
];

$imagesDir = dirname(__DIR__) . '/public/Images';
if (!is_dir($imagesDir)) {
    mkdir($imagesDir, 0755, true);
}

$articleIds = [];
$stmtArt = $pdo->prepare('INSERT INTO articles (slug, image, name, description, text, view_count, published_at, created_at) VALUES (?, ?, ?, ?, ?, 0, NOW(), NOW())');

foreach ($articles as $art) {
    $slug = ArticleSlug::fromName($art['name'])->value();
    $imagePath = 'Images/' . $slug . '.jpg';
    $fullPath = dirname(__DIR__) . '/public/' . $imagePath;
    if (function_exists('imagecreatetruecolor')) {
        $img = imagecreatetruecolor(400, 300);
        if ($img !== false) {
            $colors = [[0x6b, 0x8e, 0x9c], [0x5a, 0x8a, 0x7a], [0x7a, 0x9a, 0x8a]];
            $idx = count($articleIds) % 3;
            imagefill($img, 0, 0, imagecolorallocate($img, $colors[$idx][0], $colors[$idx][1], $colors[$idx][2]));
            imagejpeg($img, $fullPath, 85);
            imagedestroy($img);
        }
    }
    $imageValue = file_exists($fullPath) ? $imagePath : null;
    $stmtArt->execute([$slug, $imageValue, $art['name'], $art['description'], $art['text']]);
    $articleIds[] = (int) $pdo->lastInsertId();
}

$links = [
    [$articleIds[0], $categoryIds[0]],
    [$articleIds[1], $categoryIds[1]],
    [$articleIds[1], $categoryIds[2]],
    [$articleIds[2], $categoryIds[0]],
    [$articleIds[2], $categoryIds[3]],
    [$articleIds[3], $categoryIds[0]],
    [$articleIds[4], $categoryIds[0]],
    [$articleIds[5], $categoryIds[1]],
    [$articleIds[6], $categoryIds[1]],
    [$articleIds[7], $categoryIds[1]],
    [$articleIds[8], $categoryIds[2]],
    [$articleIds[9], $categoryIds[2]],
    [$articleIds[10], $categoryIds[2]],
    [$articleIds[11], $categoryIds[3]],
    [$articleIds[12], $categoryIds[3]],
    [$articleIds[13], $categoryIds[0]],
    [$articleIds[14], $categoryIds[0]],
    [$articleIds[15], $categoryIds[1]],
    [$articleIds[16], $categoryIds[1]],
    [$articleIds[17], $categoryIds[2]],
    [$articleIds[18], $categoryIds[2]],
    [$articleIds[19], $categoryIds[3]],
    [$articleIds[20], $categoryIds[3]],
    [$articleIds[21], $categoryIds[0]],
    [$articleIds[22], $categoryIds[1]],
    [$articleIds[23], $categoryIds[2]],
    [$articleIds[24], $categoryIds[3]],
    [$articleIds[25], $categoryIds[0]],
    [$articleIds[25], $categoryIds[2]],
    [$articleIds[26], $categoryIds[1]],
    [$articleIds[26], $categoryIds[2]],
    [$articleIds[27], $categoryIds[2]],
    [$articleIds[27], $categoryIds[3]],
];

$stmtLink = $pdo->prepare('INSERT INTO article_category (article_id, category_id) VALUES (?, ?)');
foreach ($links as $link) {
    $stmtLink->execute($link);
}

echo "Ok";

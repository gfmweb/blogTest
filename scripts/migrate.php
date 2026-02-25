<?php

declare(strict_types=1);

/**
 * Миграция: выполнение sql/schema.sql (создание БД и таблиц).
 * Запуск: docker-compose exec php php scripts/migrate.php
 */

require dirname(__DIR__) . '/vendor/autoload.php';

use App\Config\Database;

$root = dirname(__DIR__);
$schemaPath = $root . '/sql/schema.sql';

if (!is_file($schemaPath)) {
    fwrite(STDERR, "Schema file not found: {$schemaPath}\n");
    exit(1);
}

$sql = file_get_contents($schemaPath);
if ($sql === false) {
    fwrite(STDERR, "Cannot read schema file.\n");
    exit(1);
}

$pdo = Database::getConnectionWithoutDatabase();

$statements = preg_split('/;\s*\n/', $sql);
if ($statements === false) {
    $statements = [];
}

foreach ($statements as $statement) {
    $statement = trim($statement);
    if ($statement === '') {
        continue;
    }
    $pdo->exec($statement);
}

echo "Migration completed: schema applied.\n";

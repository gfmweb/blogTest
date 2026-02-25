<?php

declare(strict_types=1);

/**
 * Запуск миграции и сидера
 */

$baseDir = dirname(__DIR__);
require $baseDir . '/scripts/migrate.php';
require $baseDir . '/scripts/seed.php';


<?php

declare(strict_types=1);


require dirname(__DIR__) . '/vendor/autoload.php';

use App\Config\Database;

$root = dirname(__DIR__);
$schemaPath = $root . '/sql/schema.sql';

$sql = file_get_contents($schemaPath);
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



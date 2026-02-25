<?php

declare(strict_types=1);

namespace App\Config;

use PDO;

final class Database
{
    private static ?PDO $connection = null;

    public static function getConnection(): PDO
    {
        if (self::$connection !== null) {
            return self::$connection;
        }

        self::loadEnv();

        $host = getenv('DB_HOST') ?: 'mysql';
        $port = getenv('DB_PORT') ?: '3306';
        $dbname = getenv('DB_NAME') ?: 'blog';
        $user = getenv('DB_USER') ?: 'blog';
        $password = getenv('DB_PASSWORD') ?: '';

        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
            $host,
            $port,
            $dbname,
        );

        $pdo = new PDO($dsn, $user, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);

        self::$connection = $pdo;
        return $pdo;
    }

    /**
     * Подключение без выбора БД (для миграций: CREATE DATABASE).
     */
    public static function getConnectionWithoutDatabase(): PDO
    {
        self::loadEnv();

        $host = getenv('DB_HOST') ?: 'mysql';
        $port = getenv('DB_PORT') ?: '3306';
        $user = getenv('DB_USER') ?: 'blog';
        $password = getenv('DB_PASSWORD') ?: '';

        $dsn = sprintf('mysql:host=%s;port=%s;charset=utf8mb4', $host, $port);

        return new PDO($dsn, $user, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }

    public static function resetConnection(): void
    {
        self::$connection = null;
    }

    private static function loadEnv(): void
    {
        if (getenv('DB_HOST') !== false) {
            return;
        }

        $root = dirname(__DIR__, 2);
        $envFile = $root . '/.env';
        if (!is_file($envFile)) {
            return;
        }

        if (class_exists(\Dotenv\Dotenv::class)) {
            \Dotenv\Dotenv::createImmutable($root)->load();
        }
    }
}

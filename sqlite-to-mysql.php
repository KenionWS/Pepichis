<?php

declare(strict_types=1);

set_time_limit(0);

header('Content-Type: text/plain; charset=UTF-8');

$baseDir = __DIR__;
$envPath = $baseDir . '/admin/.env';
$sqlitePath = $baseDir . '/admin/database/database.sqlite';
$lockPath = $baseDir . '/admin/storage/app/sqlite-to-mysql.imported';

function abortWithMessage(string $message, int $statusCode = 400): never
{
    http_response_code($statusCode);
    echo $message . PHP_EOL;
    exit;
}

function parseEnvFile(string $path): array
{
    if (!is_file($path)) {
        abortWithMessage("No se encontro admin/.env en el servidor. Crea ese archivo antes de ejecutar la importacion.");
    }

    $values = [];
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        $trimmed = trim($line);

        if ($trimmed === '' || str_starts_with($trimmed, '#') || !str_contains($trimmed, '=')) {
            continue;
        }

        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);

        if (
            (str_starts_with($value, '"') && str_ends_with($value, '"')) ||
            (str_starts_with($value, "'") && str_ends_with($value, "'"))
        ) {
            $value = substr($value, 1, -1);
        }

        $values[$key] = $value;
    }

    return $values;
}

function quoteIdentifier(string $name): string
{
    return '`' . str_replace('`', '``', $name) . '`';
}

function createSchema(PDO $mysql): void
{
    $queries = [
        "CREATE TABLE IF NOT EXISTS `admin_users` (
            `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            `name` VARCHAR(255) NOT NULL,
            `email` VARCHAR(255) NOT NULL,
            `password` VARCHAR(255) NOT NULL,
            `remember_token` VARCHAR(100) DEFAULT NULL,
            `created_at` TIMESTAMP NULL DEFAULT NULL,
            `updated_at` TIMESTAMP NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `admin_users_email_unique` (`email`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
        "CREATE TABLE IF NOT EXISTS `producers` (
            `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            `name` VARCHAR(255) NOT NULL,
            `slug` VARCHAR(255) NOT NULL,
            `sort_order` INT UNSIGNED NOT NULL DEFAULT 0,
            `image_path` VARCHAR(255) DEFAULT NULL,
            `city` VARCHAR(255) DEFAULT NULL,
            `state` VARCHAR(255) DEFAULT NULL,
            `country` VARCHAR(255) DEFAULT NULL,
            `short_description` TEXT DEFAULT NULL,
            `long_description` LONGTEXT DEFAULT NULL,
            `created_at` TIMESTAMP NULL DEFAULT NULL,
            `updated_at` TIMESTAMP NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `producers_slug_unique` (`slug`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
        "CREATE TABLE IF NOT EXISTS `attributes` (
            `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            `name` VARCHAR(255) NOT NULL,
            `scope` ENUM('producer','wine','both') NOT NULL DEFAULT 'both',
            `created_at` TIMESTAMP NULL DEFAULT NULL,
            `updated_at` TIMESTAMP NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `attributes_name_unique` (`name`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
        "CREATE TABLE IF NOT EXISTS `attribute_values` (
            `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            `attribute_id` BIGINT UNSIGNED NOT NULL,
            `value` VARCHAR(255) NOT NULL,
            `created_at` TIMESTAMP NULL DEFAULT NULL,
            `updated_at` TIMESTAMP NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `attribute_values_attribute_id_value_unique` (`attribute_id`, `value`),
            CONSTRAINT `attribute_values_attribute_id_foreign`
                FOREIGN KEY (`attribute_id`) REFERENCES `attributes` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
        "CREATE TABLE IF NOT EXISTS `wines` (
            `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            `producer_id` BIGINT UNSIGNED NOT NULL,
            `name` VARCHAR(255) NOT NULL,
            `slug` VARCHAR(255) NOT NULL,
            `sort_order` INT UNSIGNED NOT NULL DEFAULT 0,
            `image_path` VARCHAR(255) DEFAULT NULL,
            `short_description` TEXT DEFAULT NULL,
            `long_description` LONGTEXT DEFAULT NULL,
            `created_at` TIMESTAMP NULL DEFAULT NULL,
            `updated_at` TIMESTAMP NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `wines_slug_unique` (`slug`),
            CONSTRAINT `wines_producer_id_foreign`
                FOREIGN KEY (`producer_id`) REFERENCES `producers` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
        "CREATE TABLE IF NOT EXISTS `producer_attribute_value` (
            `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            `producer_id` BIGINT UNSIGNED NOT NULL,
            `attribute_value_id` BIGINT UNSIGNED NOT NULL,
            `created_at` TIMESTAMP NULL DEFAULT NULL,
            `updated_at` TIMESTAMP NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `producer_attribute_value_unique` (`producer_id`, `attribute_value_id`),
            CONSTRAINT `producer_attribute_value_producer_id_foreign`
                FOREIGN KEY (`producer_id`) REFERENCES `producers` (`id`) ON DELETE CASCADE,
            CONSTRAINT `producer_attribute_value_attribute_value_id_foreign`
                FOREIGN KEY (`attribute_value_id`) REFERENCES `attribute_values` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
        "CREATE TABLE IF NOT EXISTS `wine_attribute_value` (
            `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            `wine_id` BIGINT UNSIGNED NOT NULL,
            `attribute_value_id` BIGINT UNSIGNED NOT NULL,
            `created_at` TIMESTAMP NULL DEFAULT NULL,
            `updated_at` TIMESTAMP NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `wine_attribute_value_unique` (`wine_id`, `attribute_value_id`),
            CONSTRAINT `wine_attribute_value_wine_id_foreign`
                FOREIGN KEY (`wine_id`) REFERENCES `wines` (`id`) ON DELETE CASCADE,
            CONSTRAINT `wine_attribute_value_attribute_value_id_foreign`
                FOREIGN KEY (`attribute_value_id`) REFERENCES `attribute_values` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
        "CREATE TABLE IF NOT EXISTS `notes` (
            `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            `title` VARCHAR(255) NOT NULL,
            `slug` VARCHAR(255) NOT NULL,
            `cover_image_path` VARCHAR(255) DEFAULT NULL,
            `excerpt` TEXT DEFAULT NULL,
            `body` LONGTEXT DEFAULT NULL,
            `seo_title` VARCHAR(255) DEFAULT NULL,
            `seo_description` TEXT DEFAULT NULL,
            `is_published` TINYINT(1) NOT NULL DEFAULT 0,
            `published_at` TIMESTAMP NULL DEFAULT NULL,
            `created_at` TIMESTAMP NULL DEFAULT NULL,
            `updated_at` TIMESTAMP NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `notes_slug_unique` (`slug`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
    ];

    foreach ($queries as $query) {
        $mysql->exec($query);
    }
}

function tableCount(PDO $connection, string $table): int
{
    return (int) $connection->query('SELECT COUNT(*) FROM ' . quoteIdentifier($table))->fetchColumn();
}

function resetDestinationTables(PDO $mysql, array $tables): void
{
    $mysql->exec('SET FOREIGN_KEY_CHECKS=0');

    foreach (array_reverse($tables) as $table) {
        $mysql->exec('TRUNCATE TABLE ' . quoteIdentifier($table));
    }

    $mysql->exec('SET FOREIGN_KEY_CHECKS=1');
}

function importTable(PDO $sqlite, PDO $mysql, string $table): int
{
    $rows = $sqlite->query('SELECT * FROM ' . quoteIdentifier($table))->fetchAll(PDO::FETCH_ASSOC);

    if ($rows === []) {
        return 0;
    }

    $columns = array_keys($rows[0]);
    $columnList = implode(', ', array_map('quoteIdentifier', $columns));
    $placeholders = implode(', ', array_fill(0, count($columns), '?'));
    $updates = implode(', ', array_map(
        static fn(string $column) => quoteIdentifier($column) . ' = VALUES(' . quoteIdentifier($column) . ')',
        $columns
    ));

    $statement = $mysql->prepare(
        'INSERT INTO ' . quoteIdentifier($table) . ' (' . $columnList . ') VALUES (' . $placeholders . ') ' .
        'ON DUPLICATE KEY UPDATE ' . $updates
    );

    foreach ($rows as $row) {
        $statement->execute(array_values($row));
    }

    $maxId = (int) $mysql->query('SELECT COALESCE(MAX(id), 0) FROM ' . quoteIdentifier($table))->fetchColumn();
    $mysql->exec('ALTER TABLE ' . quoteIdentifier($table) . ' AUTO_INCREMENT = ' . ($maxId + 1));

    return count($rows);
}

$env = parseEnvFile($envPath);
$expectedToken = $env['IMPORT_TOKEN'] ?? '';
$token = $_GET['token'] ?? $_POST['token'] ?? '';
$force = isset($_GET['force']) || isset($_POST['force']);

if ($expectedToken === '') {
    abortWithMessage("Falta IMPORT_TOKEN en admin/.env. Agrega un valor largo y secreto antes de ejecutar este script.");
}

if (!hash_equals($expectedToken, $token)) {
    abortWithMessage("Token invalido. Usa la URL con ?token=TU_IMPORT_TOKEN");
}

if (is_file($lockPath) && !$force) {
    abortWithMessage("La importacion ya fue ejecutada. Si queres repetirla, usa ?token=TU_IMPORT_TOKEN&force=1");
}

if (!is_file($sqlitePath)) {
    abortWithMessage("No se encontro admin/database/database.sqlite en el servidor. Subi temporalmente ese archivo antes de importar.");
}

$dbHost = $env['DB_HOST'] ?? '127.0.0.1';
$dbPort = $env['DB_PORT'] ?? '3306';
$dbName = $env['DB_DATABASE'] ?? '';
$dbUser = $env['DB_USERNAME'] ?? '';
$dbPass = $env['DB_PASSWORD'] ?? '';
$dbConnection = $env['DB_CONNECTION'] ?? '';

if ($dbConnection !== 'mysql') {
    abortWithMessage("admin/.env debe tener DB_CONNECTION=mysql antes de importar.");
}

if ($dbName === '' || $dbUser === '') {
    abortWithMessage("Completa DB_DATABASE y DB_USERNAME en admin/.env antes de importar.");
}

$tables = [
    'admin_users',
    'producers',
    'attributes',
    'attribute_values',
    'wines',
    'producer_attribute_value',
    'wine_attribute_value',
    'notes',
];

try {
    $sqlite = new PDO('sqlite:' . $sqlitePath, null, null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    $mysql = new PDO(
        "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4",
        $dbUser,
        $dbPass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );

    createSchema($mysql);
    resetDestinationTables($mysql, $tables);

    echo "Importando desde SQLite a MySQL..." . PHP_EOL . PHP_EOL;

    foreach ($tables as $table) {
        $imported = importTable($sqlite, $mysql, $table);
        $current = tableCount($mysql, $table);
        echo str_pad($table, 28) . " {$imported} registros importados, {$current} en destino" . PHP_EOL;
    }

    file_put_contents($lockPath, date('c') . PHP_EOL);

    echo PHP_EOL . "Importacion terminada correctamente." . PHP_EOL;
    echo "Elimina sqlite-to-mysql.php y el archivo admin/database/database.sqlite del servidor cuando verifiques todo." . PHP_EOL;
} catch (Throwable $exception) {
    abortWithMessage('Error durante la importacion: ' . $exception->getMessage(), 500);
}

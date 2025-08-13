<?php

require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

try {
    // Test MySQL connection
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306', 'root', '');
    echo "✅ MySQL server connection successful\n";

    // Try to create database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS aiproject");
    echo "✅ Database 'aiproject' created/exists\n";

    // Test Laravel connection
    $capsule = new Capsule;
    $capsule->addConnection([
        'driver' => 'mysql',
        'host' => '127.0.0.1',
        'port' => '3306',
        'database' => 'aiproject',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
    ]);

    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    // Test query
    $result = $capsule->connection()->select('SELECT 1 as test');
    echo "✅ Laravel database connection successful\n";

} catch (PDOException $e) {
    echo "❌ MySQL Connection Error: ".$e->getMessage()."\n";
    echo "💡 Make sure XAMPP MySQL is running\n";
} catch (Exception $e) {
    echo "❌ Error: ".$e->getMessage()."\n";
}

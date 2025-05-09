<?php
// db_config.php
define('DB_HOST', 'mysql8');
define('DB_NAME', '01328183_test');
define('DB_USER', '01328183_test');
define('DB_PASS', 'Procomp19!');

function getDbConnection() {
    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec("SET NAMES utf8mb4");
        return $pdo;
    } catch (PDOException $e) {
        die('Błąd połączenia z bazą danych.');
        // Można tu też logować: error_log($e->getMessage());
    }
}
?>
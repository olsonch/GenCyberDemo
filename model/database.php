<?php
class Database {
    // add the 3 numbers from your username to the 2 lines below
    private static $dsn = 'mysql:host=localhost;dbname=gencyber_cyber';
    private static $username = 'gencyber_cyber';
    // add your password to the line below between the ''
    private static $password = '';
    private static $db;

    private function __construct() { }

    public static function getDB() {
        if (!isset(self::$db)) {
            try {
                self::$db = new PDO(self::$dsn, self::$username, self::$password);
            } catch (PDOException $e) {
                $error_message = $e->getMessage();
                include 'view/header.php';
                echo '<h1>Error</h1>';
                echo '<p>' . $error_message . '</p>';
                include 'view/footer.php';
                exit();
            }
        }
        return self::$db;
    }
}
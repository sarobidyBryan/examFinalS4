<?php
function getDB() {
    $host = 'localhost';
    $dbname = 'etablissement_financier';
    $username = 'root';
    $password = '';

    try {
        return new PDO("mysql:host=$host:3307;dbname=$dbname;charset=utf8", $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    } catch (PDOException $e) {
        die(json_encode(['error' => $e->getMessage()]));
    }
}

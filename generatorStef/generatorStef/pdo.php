<?php

$dbname = "couture";
$servername = "localhost:8889";
$username = "root";
$password = "root";

try {
    $conn = new \PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
} catch (\PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
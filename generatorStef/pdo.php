<?php

//$dbname = "randomapi-db";
$dbname = "fmmi";
$servername = "localhost:8889";
$username = "userame";
$password = "root";
$baseurl = "";
$baseapi = "";
$ownerTable = ['user', 'entreprise', 'people'];
$imageColumn = ['image', 'photo', 'icon', 'cover', 'profil'];
$documentColumn = ['file', 'pdf', 'fichier', 'doc'];
$videoColumn = ['video', 'film'];

try {
    $conn = new \PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
} catch (\PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

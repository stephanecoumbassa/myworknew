<?php
require 'vendor/autoload.php';
require 'pdo.php';
session_start();

//Commence par
function startsWith( $haystack, $needle ) {
    $length = strlen( $needle );
    return substr( $haystack, 0, $length ) === $needle;
}
//Transform en SnakeCase
function camel_to_snake($input)
{
    return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
}
//Transform en CamelCase
function snakeToCamel($input)
{
    return ucfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $input))));
}

//NOM de la base de données
$dtb = $dbname;

// RECUPERATION DE TOUTES LES TABLES
$query = $conn->query("SELECT table_name FROM information_schema.tables WHERE table_schema = '{$dtb}';");
$tablesnames = $query->fetchAll();
$tables = [];

//VARIABLES POUR LE SWAGGER
$pathsMerge = "";
$definitionsMerge = "";


foreach ($tablesnames as $value){
    $tables[] = $value[0];
}

foreach ($tables as $value) {
    $tablename = $value;
    $name = $value;
    $NAME = ucfirst($value);
    $typerubrique = 5;
    $search = 'name';
    $img = 'photo';
    $dollar = '$';

    // RECUPERE LES RELATIONSS
    $query = $conn->query(
        "SELECT TABLE_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
        FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
        WHERE REFERENCED_TABLE_SCHEMA = 'oronia' AND REFERENCED_TABLE_NAME = '$tablename';");
    $relations = $query->fetchAll(\PDO::FETCH_ASSOC) ?? [];

    require 'includes.php';
}
die();

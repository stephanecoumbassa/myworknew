<?php

    $query = $conn->query("SELECT COLUMN_NAME, COLUMN_TYPE, IS_NULLABLE, COLUMN_KEY, COLUMN_COMMENT 
                        FROM information_schema.COLUMNS WHERE table_schema = '{$dtb}' AND table_name = '{$tablename}'");
    $res = $query->fetchAll();
    $var = [];
    $vars = [];

    for( $i = 1; $i < count($res); $i++ ) {
        $var[] = $res[$i][0];
        $vars[] = [ $res[$i][0], $res[$i][1], $res[$i][2], $res[$i][3], $res[$i][4] ];
    }
    //dd($var, $vars);
    mkdir("./files/$tablename");

    require 'php/add.php';
    require 'php/update.php';
    require 'php/index.php';
    require 'php/relations.php';
    require 'node/nodejs.php';
    require 'node/nodejsRelation.php';
    require 'swagger/swagger.php';
    require 'playwright/test.php';
    require 'sql/sql.php';
    require 'php/listes.php';
    require 'quasar/quasar_crud.php';
    require 'quasar/quasar_component.php';

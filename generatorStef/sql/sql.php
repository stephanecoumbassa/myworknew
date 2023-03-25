<?php

$sql = <<<EOL
    DELIMITER $$
    create procedure {$name}_add($paramsproc)
    myrubrique:Begin

    DECLARE __id int default 0;
    DECLARE __status int default 0;
    DECLARE __error_msg varchar(255);

    set autocommit = 0;

    insert into $tablename ($columns) values ($params);
    SET __status = 1; SET __error_msg = 'Ajouté avec succes';

    SELECT id into __id from $tablename ORDER BY id DESC LIMIT 1 FOR UPDATE;
    SELECT __id id, $typerubrique typerubrique, __status status, __error_msg msg;

    set autocommit = 1;

    end $$ \n \r


    DELIMITER $$
    create procedure {$name}_delete(IN _id int)
    myrubrique:Begin

        DECLARE _filename text;
        set autocommit = 0;

        ## select img into $_filename from $tablename where id = _id;
        delete from $tablename WHERE id = _id;

        SELECT 1 status, 'Supprimé avec succès' msg, _id id, $typerubrique typerubrique;
        ##       $typerubrique typerubrique, $_filename filename;
        set autocommit = 1;

    end $$ \n \r


    DELIMITER $$
    create procedure {$name}_update( $paramsproc , IN _id text)
    myrubrique:Begin

        DECLARE __status int default 0;
        DECLARE __error_msg varchar(255); 
        set autocommit = 0;
            update $tablename SET $paramsupdate where id = _id;

            SET __status = 1; SET __error_msg = 'Modifié avec succes';
            SELECT _id id, $typerubrique typerubrique, __status status, __error_msg msg;

        set autocommit = 1;
    end $$ \n \r


    DELIMITER $$
    create function {$name}_func_get(_id text) returns text
    BEGIN
        DECLARE logs JSON;
        SET logs = (
            select json_object(
                       $paramfonctions
            )
            from $tablename
            WHERE `$tablename`.`id` = _id
        ) ;
        RETURN logs;
    end $$ \n \r

EOL;

//


file_put_contents("./files/{$name}/procedure_{$name}.sql", $sql);
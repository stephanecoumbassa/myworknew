<?php

$nodejsRel = <<<EOL
var Utilities = require("../../services/Utils");

var express = require('express');
const Bdd = require("../services/Db");
var router = express.Router();

// Liste des {$name}
router.get('/get/{$name}', (req, res, next) => {
    Bdd.query('SELECT * FROM $tablename ORDER BY id DESC LIMIT 500',   (error, results, fields) => {
    if (error) throw error;
    res.send(results);
    });
});


// DELETE {$name}
router.delete('/delete/:id', function(req, res, next) {
    Bdd.query('DELETE FROM {$tablename} WHERE id = ?;',
        [req.params.id], (error, results, fields) => {
        if (error) throw error;
        res.send({status:1, msg: 'INSERT OK'});
    });
});

module.exports = router;
EOL;

//const adresseRouter = require('./controller/AdresseRouter');
file_put_contents("./files/{$name}/{$NAME}RelationRouter.js", $nodejsRel);
file_put_contents("./files/_NODEJS/{$NAME}RelationRouter.js", $nodejsRel);
<?php


$nodejs = <<<EOL
var Utilities = require("../../services/Utils");

var express = require('express');
const Bdd = require("../../services/Db");
var router = express.Router();

// VALIDATION, JWT, SECURITY, RELATIONS, ADMIN

// Liste des {$name}
router.get('/get/{$name}', (req, res, next) => {
    Bdd.query('SELECT * FROM $tablename ORDER BY id DESC LIMIT 500',   (error, results, fields) => {
    if (error) throw error;
    res.send(results);
    });
});


// Recuperer {$name} By ID
router.get('/get/{$name}/:id', (req, res, next) => {
    Bdd.query('SELECT * FROM $tablename WHERE id = ?', [req.params.id],  (error, results, fields) => {
    if (error) throw error;
    res.send(results[0]);
    });
});

// Search {$name} By ID
router.get('/search/{$name}', (req, res, next) => {
    let searchFields = "req.body.search";
    let keys = Object.keys(searchFields);
    let values = Object.values(searchFields);
    let params = [];
    let where = "";
    
    searchFields.forEach((field) => {
        operator = field['operator']??'=';
        if(isset(field['key']) && !empty(field['key'])) {
            params.push(field['value']);
            where = where+`AND {$dollar}{field['key']} {$dollar}{operator} ? `;
        }
    });
    where = where.slice(3);
    Bdd.query('SELECT * FROM $tablename WHERE {$dollar}where',
     params,  (error, results, fields) => {
    if (error) throw error;
    res.send(results);
    });
});


// INSERT POST {$name}
router.post('/post/{$name}', function(req, res, next) {
    let params = req.body;
    Bdd.query('INSERT INTO {$tablename} SET $paramsupdate;',
        [$paramsNode],
        (error, results, fields) => {
        if (error) throw error;
        res.send({status:1, msg: 'INSERT OK'});
    });
});


// UPDATE PUT {$name}
router.put('/put/{$name}', function(req, res, next) {
    let params = req.body;
    Bdd.query('UPDATE {$tablename} SET $paramsupdate WHERE id = ?;',
        [$paramsNode, params.id],
        (error, results, fields) => {
        if (error) throw error;
        res.send({status:1, msg: 'INSERT OK'});
    });
});

// UPDATE PUT {$name}
router.put('/put2/{$name}', function(req, res, next) {
    let params = req.body;
    let keys = Object.keys(params);
    let values = Object.values(params);
    let params2 = [];
    let update = "";
    for(let i=0; i<keys.length; i++) {
        if(keys[i] !== ['id']) {
            params2.push(values[i]);
            update = update+`{$dollar}{keys[i]}=?,`;
        }
    }
    params2.push(params.id);
    Bdd.query(`UPDATE {$tablename} SET {$dollar}{update} WHERE id = ?;`,
        params2,
        (error, results, fields) => {
        if (error) throw error;
        res.send({status:1, msg: 'UPDATE OK'});
    });
});


// UPDATE PATCH {$name}
router.patch('/patch/{$name}', function(req, res, next) {
    let params = req.body;
    Bdd.query('UPDATE {$tablename} SET $paramsupdate WHERE id = ?;',
        [$paramsNode, params.id],
        (error, results, fields) => {
        if (error) throw error;
        res.send({status:1, msg: 'INSERT OK'});
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

$auth = <<<EOL
var nodemailer = require('nodemailer');
var bcrypt = require('bcrypt');
var randtoken = require('rand-token');
var jwt = require('jsonwebtoken');


function tokenGenerator(payload) {
    return jwt.sign({
        iat: Math.floor(Date.now() / 1000) - 30,
        exp: Math.floor(Date.now() / 1000) + (60 * 60),
        issuer: 'ID',
        data: payload
    }, 'MYKEYJWT1543', {algorithm: "HS512"});
}

function tokenVerify(token) {
    jwt.verify(token, 'MYKEYJWT1543', function(err, decoded) {
        console.log(decoded)
    });
}

app.post('/register', function(request, response) {
    let params = request.body;
    if (username && password) {
        var hashPassword="";
        bcrypt.genSalt(10, (err, salt) => {
            bcrypt.hash(params.password, salt, (err, hash) => {
                hashPassword = hash;
            });
        });
        connection.query('INSERT INTO user (nom, prenom, email, telephone, username, password) VALUES(?, ?, ?, ?, ?, ?)',
         [params.nom, params.prenom, params.email, params.telephone, params.username, hashPassword],
          (error, results, fields) => {
            if (error) throw error;
            if (results.length > 0) {
                response.send('TOKENID');
            } else {
                response.send('Incorrect Username and/or Password!');
            }
            response.end();
        });
    } else {
        response.send('Please enter Username and Password!');
        response.end();
    }
});


app.post('/connexion', function(request, response) {
    let username = request.body.username;
    let password = request.body.password;
    if (username && password) {
        connection.query('SELECT * FROM user WHERE username = ? AND password = ?', [username, password], function(error, results, fields) {
            bcrypt.compare(password, results[0].password, (err, result) => {
                // result == true
            });
            if (error) throw error;
            if (results.length > 0) {
                token = tokenGenerator(results[0]);
                response.send(token);
            } else {
                response.send('Incorrect Username and/or Password!');
            }
            response.end();
        });
    } else {
        response.send('Please enter Username and Password!');
        response.end();
    }
});


function sendEmail(email, token) {
    var email = email;
    var token = token;
    var mail = nodemailer.createTransport({
        service: 'gmail',
        auth: {
            user: '', // Your email id
            pass: '' // Your password
        }
    });
    var mailOptions = {
        from: 'tutsmake@gmail.com',
        to: email,
        subject: 'Reset Password Link - Tutsmake.com',
        html: '<p>You requested for reset password, kindly use this <a href="http://localhost:4000/reset-password?token=' + token + '">link</a> to reset your password</p>'
    };
    mail.sendMail(mailOptions, function(error, info) {
        if (error) {
            console.log(1);
        } else {
            console.log(0);
        }
    });
}

router.post('/reset-password-email', function(req, res, next) {
    var email = req.body.email;
    Bdd.query('SELECT * FROM users WHERE email = ?', (err, result) => {
        if (err) throw err;
        var type = ''; var msg = ''

        if (result[0].email.length > 0) {
            var token = randtoken.generate(20);
            var sent = sendEmail(email, token);

            if (sent !== 0) {
                Bdd.query('UPDATE users SET token=? WHERE email= ?', [token, email], function(err, result) {
                    if(err) throw err
                })
                type = 'success';
                msg = 'The reset password link has been sent to your email address';
            } else {
                type = 'error';
                msg = 'Something goes to wrong. Please try again';
            }
        } else {
            console.log('2');
            type = 'error';
            msg = 'The Email is not registered with us';
        }
        res.send({type,  msg});
    });
});

/* update password to database */
router.post('/update-password', function(req, res, next) {
    var token = req.body.token;
    var password = req.body.password;
    Bdd.query('SELECT * FROM users WHERE token = ?', [token], function(err, result) {
        if (err) throw err;
        var type;
        var msg;
        if (result.length > 0) {
            var saltRounds = 10;
            bcrypt.genSalt(saltRounds, function(err, salt) {
                bcrypt.hash(password, salt, function(err, hash) {
                    Bdd.query('UPDATE users SET password = ? WHERE email = ?', [hash, result[0].email],
                        function(err, result) {
                        if(err) throw err
                    });
                });
            });
            type = 'success';
            msg = 'Your password has been updated successfully';
        } else {
            type = 'success';
            msg = 'Invalid link; please try again';
        }
        res.send({type,  msg});
    });
})

EOL;


file_put_contents("./files/{$name}/{$NAME}Router.js", $nodejs);
file_put_contents("./files/_NODEJS/{$NAME}Router.js", $nodejs);
file_put_contents("./files/AuthRouter.js", $auth);








<?php
require_once 'vendor/autoload.php';
$json = '{
  "root": {
    "status": {
      "code": "200"
    },
    "data": {
      "CONTEXT_STATUS": "OK",
      "STATUS": "200",
      "IDENTIFIANT": "799403",
      "SIZE": "5",
      "MESSAGE": "Orange Money NSIA VIE ASS.Numéro d\'identifant N°799403 selectionné. Avec un montant total de 71375 FCFA",
      "MONTANT_TOTAL": "71375"
    }
  }
}';

$json = json_decode($json, true);

//
//function find_parent($array, $needle, $parent = null) {
//    foreach ($array as $key => $value) {
//        if (is_array($value)) {
//            $pass = $parent;
//            if (is_string($key)) {
//                $pass = $key;
//            }
//            $found = find_parent($value, $needle, $pass);
//            if ($found !== false) {
//                return $found;
//            }
//        } else if ($key === 'id' && $value === $needle) {
//            return $parent;
//        }
//    }
//
//    return false;
//}
//
////dump($json);
//$parentkey = find_parent($json, 'root');
////dd($parentkey);


$_GET['all'] = '';
$_GET['res'] = '';
$_GET['keys'] = [];


function camel_to_snake($input){
    return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
}

function snakeToCamel($input){
    return str_replace(' ', '', lcfirst(ucwords(str_replace('_', ' ', $input))) );
}

function writeLine($key1, array $keys) {
    $i=0;
    $k=[];
    dump($key1);
    foreach ($keys as $ke) {
        !empty($keys[$i]) ? $k[$i]= $keys[$i].'/' : $k[$i]= '';
        $i++;
    }

    if(!is_array($key1)) {
        $lkey = snakeToCamel(mb_strtolower($key1));;
        return <<<EOL
            { value,"$lkey","/descendant::{$k[0]}{$k[1]}{$k[2]}{$k[3]}{$k[4]}{$k[5]}{$k[6]}{$k[7]}{$k[8]}{$k[9]}$key1/text()" },\n
        EOL;
    }
    return false;
}

function myRecursiveFunction($json, $k='') {
    foreach($json as $key1 => $value1) {
        if(!is_array($value1)) {
            dump($key1);
            $_GET['res'] = $_GET['res'].''.writeLine($key1, $_GET['keys']);
        } else {
            if(!in_array((int)$key1, [1, 2, 3, 4, 5, 6, 7, 8, 9])) {
                $_GET['keys'][] = $key1;
                myRecursiveFunction($value1);
            }else{
                $_GET['keys'][] = '[]';
                myRecursiveFunction($value1);
            }
        }
    }
}

myRecursiveFunction($json);
dump($_GET['keys']);

$res = substr( $_GET['res'], 0, -2);
$res = str_replace('/[]/[]/[]/[]/[]/[]/[]/[]/[]/', '[9]/', $res);
$res = str_replace('/[]/[]/[]/[]/[]/[]/[]/[]/', '[8]/', $res);
$res = str_replace('/[]/[]/[]/[]/[]/[]/[]/', '[7]/', $res);
$res = str_replace('/[]/[]/[]/[]/[]/[]/', '[6]/', $res);
$res = str_replace('/[]/[]/[]/[]/[]/', '[5]/', $res);
$res = str_replace('/[]/[]/[]/[]/', '[4]/', $res);
$res = str_replace('/[]/[]/[]/', '[3]/', $res);
$res = str_replace('/[]/[]/', '[2]/', $res);
$res = str_replace('/[]/', '[1]/', $res);
//echo '['. $res. '].';

file_put_contents("./R2.mapping", '['. $res . '].');
file_put_contents("./R2.error", '[].');



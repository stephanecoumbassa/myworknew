var $json = `{
  "root": {
    "CONTEXT_STATUS": "OK",
    "NOM_PRENOMS": "M. TAMPE ELOUKOU WILLIAMS",
    "MESSAGE": "Bienvenue M.TAMPE dans votre menu de paiement de prime.",
    "data": [
      {
        "POLICE": "21827404",
        "EFFET_POLICE": "2021-05-01",
        "FIN_EFFET_POLICE": "2036-05-01",
        "PERIODICITE_PRIME": "ANNUEL",
        "FRACTIONNEMENT_PRIME": "MENSUELLE",
        "PRODUIT": "2180",
        "LIBELLE_PRODUIT": "NT-NSIA RETRAITE",
        "IDENTIFIANT": "799403",
        "NOM_PRENOMS": "M. TAMPE ELOUKOU WILLIAMS",
        "TELEPHONE": "0555734501",
        "MONTANT_PRIME": "16750"
      },
      {
        "POLICE": "31232921",
        "EFFET_POLICE": "2021-05-01",
        "FIN_EFFET_POLICE": "2044-05-01",
        "PERIODICITE_PRIME": "ANNUEL",
        "FRACTIONNEMENT_PRIME": "MENSUELLE",
        "PRODUIT": "3120",
        "LIBELLE_PRODUIT": "NT-NSIA Etudes",
        "IDENTIFIANT": "799403",
        "NOM_PRENOMS": "M. TAMPE ELOUKOU WILLIAMS",
        "TELEPHONE": "0555734501",
        "MONTANT_PRIME": "16342"
      },
      {
        "POLICE": "33445275",
        "EFFET_POLICE": "2021-05-01",
        "FIN_EFFET_POLICE": "2061-05-01",
        "PERIODICITE_PRIME": "ANNUEL",
        "FRACTIONNEMENT_PRIME": "ANNUEL",
        "PRODUIT": "3340",
        "LIBELLE_PRODUIT": "NSIA Assist. Fun. Indiv. V3",
        "IDENTIFIANT": "799403",
        "NOM_PRENOMS": "M. TAMPE ELOUKOU WILLIAMS",
        "TELEPHONE": "0555734501",
        "MONTANT_PRIME": "76000"
      },
      {
        "POLICE": "78603661",
        "EFFET_POLICE": "2018-02-01",
        "FIN_EFFET_POLICE": "2043-02-01",
        "PERIODICITE_PRIME": "ANNUEL",
        "FRACTIONNEMENT_PRIME": "MENSUELLE",
        "PRODUIT": "7860",
        "LIBELLE_PRODUIT": "NT- ECO PENSION",
        "IDENTIFIANT": "799403",
        "NOM_PRENOMS": "M. TAMPE ELOUKOU WILLIAMS",
        "TELEPHONE": "0555734501",
        "MONTANT_PRIME": "5191"
      }
    ],
    "status": {
      "code": "200"
    }
  }
}`;

$json = JSON.parse($json, true);


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

file_put_contents("./R1.mapping", '['. $res . '].');
file_put_contents("./R1.error", '[].');



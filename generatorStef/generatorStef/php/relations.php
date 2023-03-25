<?php
$snake = snakeToCamel($NAME);

$classRelations = <<<EOL
    <?php
        namespace App\Controller;
    
        use App\Controller\BaseController;
        use Symfony\Component\HttpFoundation\JsonResponse;
        use Symfony\Component\HttpFoundation\Request;
        use Symfony\Component\Routing\Annotation\Route;
        
        class {$snake}RelationController extends BaseController {
    EOL;


//$definitionsRelations = <<<EOL
//"{$name}": {
//      "type": "object",
//      "properties": {
//        "id": {
//          "type": "integer",
//          "format": "int64"
//        }
//        $paramsSwagger
//      }
//    },
//
//EOL;



foreach ($relations as $relation) {

    $relationTableName = $relation['TABLE_NAME'];

    $queryRel = $conn->query("SELECT COLUMN_NAME, COLUMN_TYPE, IS_NULLABLE, COLUMN_KEY, COLUMN_COMMENT FROM information_schema.COLUMNS
        WHERE table_schema = '{$dtb}' AND table_name = '{$relationTableName}'");
    $resRel = $queryRel->fetchAll();
    $varRel = [];
    $varsRel = [];

    for( $i = 1; $i < count($resRel); $i++ ) {
        $varRel[] = $resRel[$i][0];
        $varsRel[] = [ $resRel[$i][0], $resRel[$i][1], $resRel[$i][2], $resRel[$i][3], $resRel[$i][4] ];
    }

    $classRelations = $classRelations.<<<EOL
            /**
             * @Route("/api/get/$tablename/$relationTableName/{id}", name="{$tablename}_{$relationTableName}_get")
             */
            public function {$relationTableName}_{$name}_get({$dollar}id) :JsonResponse
            {
                {$dollar}em = {$dollar}this->getDoctrine();
                {$dollar}var = {$dollar}em->getConnection()->prepare(
                'SELECT * FROM $relationTableName t LEFT JOIN $tablename rt on rt.id = t.{$tablename}_id
                WHERE rt.{$tablename}_id = ?'
                );
                {$dollar}var->execute([{$dollar}id]);
                {$dollar}res = {$dollar}var->fetchAll();
                return new JsonResponse({$dollar}res);
            }
        
            /**
             * @Route("/api/delete/$tablename/$name/{id}", name="{$tablename}_{$relationTableName}_delete")
             */
            public function {$relationTableName}_{$name}_delete({$dollar}id)
            {
                {$dollar}em = {$dollar}this->getDoctrine();
                // {$dollar}var = {$dollar}em->getConnection()->prepare('call {$name}_delete(?);');
                {$dollar}var = {$dollar}em->getConnection()->prepare('DELETE FROM {$name}_delete WHERE {$name}_id = ?;');
                {$dollar}var->execute([{$dollar}id]);
                {$dollar}res = {$dollar}var->fetch();
                return new JsonResponse({$dollar}res);
            }           
    
    EOL;

    //dump($pathsMerge);

    $pathsMerge = $pathsMerge.<<<EOL
    "/get/{$name}/$relationTableName/{{$name}Id}": {
      "get": {
        "tags": [
          "{$name}"
        ],
        "summary": "get all {$name}",
        "operationId": "getRelation{$name}",
        "parameters": [
          {
            "in": "body",
            "name": "{$name}Id",
            "description": "{$name} object that needs to be added to the store",
            "schema": {
              "{$dollar}ref": "#/definitions/{$relationTableName}"
            }
          }
        ],
        "responses": {
          "401": {
            "description": "No Authentification"
          },
          "200": {
            "description": "Token créé",
            "schema": {
              "type": "array",
              "items": {
                "{$dollar}ref": "#/definitions/{$relationTableName}"
              }
            }
          }
        }
      }
    },
    "/delete/{$name}/$relationTableName/{{$name}Id}": {
      "delete": {
        "tags": [
          "{$name}"
        ],
        "summary": "get one {$name}",
        "operationId": "deleteRelationOne{$name}",
        "parameters": [
          {
            "in": "path",
            "name": "{$name}Id",
            "type": "integer",
            "format": "int64",
            "required": true,
            "description": "{$name} object that needs to be added to the store"
          }
        ],
        "responses": {
          "401": {
            "description": "No Authentification"
          },
          "200": {
            "description": "Token créé",
            "schema": {
              "type": "object"
            }
          }
        }
      }
    },
EOL;

}


$classRelations = $classRelations."}";
file_put_contents("./files/{$name}/{$snake}RelationController.php", $classRelations);
file_put_contents("./files/controller/{$snake}RelationController.php", $classRelations);



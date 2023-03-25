<?php

$paths = <<<EOL
    "/get/{$name}": {
      "get": {
        "tags": [
          "{$name}"
        ],
        "summary": "get all {$name}",
        "operationId": "get{$name}",
        "parameters": [
          {
            "in": "body",
            "name": "body",
            "description": "{$name} object that needs to be added to the store",
            "schema": {
              "{$dollar}ref": "#/definitions/{$name}"
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
                "{$dollar}ref": "#/definitions/{$name}"
              }
            }
          }
        }
      }
    },
    "/get/{$name}/{{$name}Id}": {
      "get": {
        "tags": [
          "{$name}"
        ],
        "summary": "get one {$name}",
        "operationId": "getOne{$name}",
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
              "type": "array",
              "items": {
                "{$dollar}ref": "#/definitions/{$name}"
              }
            }
          }
        }
      }
    },
    "/post/{$name}": {
      "post": {
        "tags": [
          "{$name}"
        ],
        "summary": "Add a new {$name}",
        "operationId": "add{$name}",
        "parameters": [
          {
            "in": "body",
            "name": "body",
            "description": "{$name} object that needs to be added to the store",
            "schema": {
              "{$dollar}ref": "#/definitions/{$name}"
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
              "type": "object"
            }
          }
        }
      }
    },
    "/put/{$name}": {
      "put": {
        "tags": [
          "{$name}"
        ],
        "summary": "Update a new {$name}",
        "operationId": "update{$name}",
        "parameters": [
          {
            "in": "body",
            "name": "body",
            "description": "{$name} object that needs to be added to the store",
            "schema": {
              "{$dollar}ref": "#/definitions/{$name}"
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
              "type": "object"
            }
          }
        }
      }
    },
    "/delete/{$name}/{{$name}Id}": {
      "delete": {
        "tags": [
          "{$name}"
        ],
        "summary": "get one {$name}",
        "operationId": "deletaOne{$name}",
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

$definitions = <<<EOL
"{$name}": {
      "type": "object",
      "properties": {
        "id": {
          "type": "integer",
          "format": "int64"
        }
        $paramsSwagger
      }
    },
EOL;

$nodeRouter = "const {$snake}Router = require('./{$snake}Router'); \n";
$nodeUse = "router.use('/', {$snake}Router); \n";


if(empty($pathsMerge)) {
    $pathsMerge = $paths;
} else {
    $pathsMerge = $pathsMerge.$paths;
}

if(empty($pathsMerge)) {
    $definitionsMerge = $definitions;
} else {
    $definitionsMerge = $definitionsMerge.$definitions;
}

if(empty($nodeRouterMerge)) {
    $nodeRouterMerge = $nodeRouter;
} else {
    $nodeRouterMerge = $nodeRouterMerge.$nodeRouter;
}

if(empty($nodeUseMerge)) {
    $nodeUseMerge = $nodeUse;
} else {
    $nodeUseMerge = $nodeUseMerge.$nodeUse;
}


$swagger = <<<EOL
{
  "swagger": "2.0",
  "info": {
    "title": "Swagger API",
    "description": "API avec swagger",
    "version": "1.0.0"
  },
  "host": "rest-api.local",
  "basePath": "/api",
  "schemes": [
    "http"
  ],
  "produces": [
    "application/json",
    "application/xml"
  ],
  "consumes": [
    "application/json",
    "application/xml"
  ],
  "paths": {
     $pathsMerge
   },
  "securityDefinitions": {
    "{$name}store_auth": {
      "type": "oauth2",
      "authorizationUrl": "http://petstore.swagger.io/oauth/dialog",
      "flow": "implicit",
      "scopes": {
        "write:{$name}": "modify {$name} in your account",
        "read:{$name}": "read {$name} {$name}"
      }
    },
    "api_key": {
      "type": "apiKey",
      "name": "api_key",
      "in": "header"
    }
  },
  "definitions": {
    $definitionsMerge
  }
}
EOL;


//echo $_SESSION["newsession"];
//$pathsMerge = $pathsMerge.$paths;

//file_put_contents("./files/{$name}/{$snake}.json", $swagger);
file_put_contents("./files/swagger.json", $swagger);
file_put_contents("./files/paths.json", $pathsMerge);
file_put_contents("./files/definitions.json", $definitionsMerge);
file_put_contents("./files/_NODEJS/index.js",
    $nodeRouterMerge."\n".$nodeUseMerge);
//file_put_contents("./files/_NODEJS/indexUse.js", $nodeUseMerge);

<?php

//$var = ["titre", "autheur", "cat", "date_full"];
$columns = '';
$params = '';
$paramsproc = '';
$paramsupdate = '';
$paramrequest = '';
$paramfonctions = '';
$interogation = "";
$paramsSwagger = "";
$paramsNode = "";
$paramsJson = "";
$_filename = '';

$paths = "";
$definitions = "";

// BOOTSTRAP-REACJS TESTS-PLAYWRIGHT SWAGGER NODEJS-RELATIONS
// VALIDATION, JWT, SECURITY, RELATIONS, ADMIN, FIXTURES-FAKER, GUARD, STATS, INVOICE, UTILS
// locked, active, status, created_at, updated_at, required

foreach ($var as $item){
    $columns = $columns.', '.$item;
    $params = $params.', _'.$item;
    $paramsproc = $paramsproc.', IN _'.$item.' text';
    $paramsNode = $paramsNode.', params.'.$item;
//    $paramsupdate = $paramsupdate.", ".$item." = _".$item;
    $paramsupdate = $paramsupdate.", ".$item." = ?";
    //$paramsupdate2 = $paramsupdate.", ".$item." =;
    //$paramrequest = $paramrequest.', $data[] ?? null";
    $interogation = $interogation.', ?';
    $paramfonctions = $paramfonctions.",'$item', $item \n ";
    $paramrequest = $paramrequest.", {$dollar}data['$item']??null";
    $paramsJson = $paramsJson.", $item: faker.name.jobTitle()";
    $sw = <<<EOL
            ,
            "$item": {
              "type": "string",
              "example": ""
            }
            EOL;
    $paramsSwagger = $paramsSwagger.$sw;
}
$columns = substr($columns, 1);
$params = substr($params, 1);
$paramsproc = substr($paramsproc, 1);
$paramsupdate = substr($paramsupdate, 1);
$paramsupdate2 = substr($paramsupdate2, 1);
$paramrequest = substr($paramrequest, 1);
$interogation = substr($interogation, 1);
$paramfonctions = substr($paramfonctions, 1);
$paramsNode = substr($paramsNode, 1);
$paramsJson = substr($paramsJson, 1);


$snake = snakeToCamel($NAME);
$class = <<<EOL
<?php
    namespace App\Controller;

    use App\Controller\BaseController;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Annotation\Route;
    
    class {$snake}Controller extends BaseController {

    /**
     * @Route("/api/get/{$name}", name="{$name}_get")
     */
    public function {$name}_get() :JsonResponse
    {
        {$dollar}em = {$dollar}this->getDoctrine();
        {$dollar}var = {$dollar}em->getConnection()->prepare('SELECT * FROM $tablename ORDER BY id DESC LIMIT 500;');
        {$dollar}res = {$dollar}var->executeQuery()->fetchAll();
        return new JsonResponse({$dollar}res);
    }

    /**
     * @Route("/api/search/$name", name="{$name}_search_get")
     */
    public function {$name}_search(Request {$dollar}request) :JsonResponse
    {
        //{$dollar}search = "%{{$dollar}_GET['search']}%";
        {$dollar}data = json_decode({$dollar}request->getContent(),true);
        {$dollar}searchFields = {$dollar}data['search'];
        
        {$dollar}params = [];
        {$dollar}where = "";
        foreach ({$dollar}searchFields as {$dollar}field) {
            {$dollar}operator = {$dollar}field['operator']??'=';
            if(isset({$dollar}field['key']) && !empty({$dollar}field['key'])) {
                array_push({$dollar}params, {$dollar}field['value']);
                {$dollar}where = {$dollar}where."AND {{$dollar}field['key']} {$dollar}operator ? ";
            }
        }
        {$dollar}where = substr({$dollar}where, 3);

        {$dollar}em = {$dollar}this->getDoctrine();
        {$dollar}var = {$dollar}em->getConnection()->executeQuery("SELECT * FROM $tablename WHERE {$dollar}where", {$dollar}params);
        {$dollar}res = {$dollar}var->fetchAll();
        return new JsonResponse({$dollar}res);
    }

    /**
     * @Route("/api/post/$name", name="admin_{$name}_add")
     */
    public function {$name}_add(Request {$dollar}request)
    {
        {$dollar}data = json_decode({$dollar}request->getContent(),true);
        {$dollar}em = {$dollar}this->getDoctrine();
        {$dollar}params = [ $paramrequest ];
        //{$dollar}var = {$dollar}em->getConnection()->prepare('call {$tablename}_add($interogation);');
        {$dollar}var = {$dollar}em->getConnection()->prepare('INSERT INTO {$tablename} SET $paramsupdate');
        {$dollar}var->executeQuery({$dollar}params);
        
        return new JsonResponse(['msg'=>'Ajouté avec succes', 'status'=>1]);
    }

    /**
     * @Route("/api/put/{$name}", name="admin_{$name}_update")
     */
    public function {$name}_update(Request {$dollar}request)
    {
        {$dollar}data = json_decode({$dollar}request->getContent(),true);
        {$dollar}em = {$dollar}this->getDoctrine();
        {$dollar}params = [ $paramrequest, {$dollar}data['id'] ];
        //{$dollar}var = {$dollar}em->getConnection()->prepare('call {$name}_update($interogation, ?);');
        {$dollar}var = {$dollar}em->getConnection()->prepare('UPDATE {$tablename} SET $paramsupdate WHERE id = ?;');
        {$dollar}var->executeQuery({$dollar}params);
        return new JsonResponse(['msg'=>'Modifié avec succes', 'status'=>1]);
    }
    
    /**
     * @Route("/api/put2/{$name}", name="admin_{$name}_update2")
     */
    public function {$name}_update2(Request {$dollar}request)
    {
        {$dollar}data = json_decode({$dollar}request->getContent(),true);
        
        {$dollar}params = [];
        {$dollar}update = "";
        foreach({$dollar}data as {$dollar}key => {$dollar}value) {
            if({$dollar}key !=='id') {
                array_push({$dollar}params, {$dollar}value);
                {$dollar}update = {$dollar}update."{$dollar}key=?,";
            }
        }
        {$dollar}update = substr({$dollar}update, 0, -1);
        array_push({$dollar}params, {$dollar}data['id']);
        {$dollar}em = {$dollar}this->getDoctrine();
        {$dollar}var = {$dollar}em->getConnection()->executeQuery(
        "UPDATE {$tablename} SET {$dollar}update WHERE id = ?;", {$dollar}params);
        
        return new JsonResponse(['msg'=>'Modifié avec succes', 'status'=>1]);
    }

    /**
     * @Route("/api/delete/$name/{id}", name="admin_{$name}_delete")
     */
    public function {$name}_delete({$dollar}id)
    {
        {$dollar}em = {$dollar}this->getDoctrine();
        {$dollar}var = {$dollar}em->getConnection()->prepare('call {$name}_delete(?);');
        {$dollar}var->executeQuery([{$dollar}id]);
        return new JsonResponse(['status'=>1, 'msg'=>'supprimé avec succès']);
    }

}
EOL;

file_put_contents("./files/{$name}/{$snake}Controller.php", $class);
file_put_contents("./files/controller/{$snake}Controller.php", $class);
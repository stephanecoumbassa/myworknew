<?php
    namespace App\Controller;

    use App\Controller\BaseController;
    use App\Service\FileUploader;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Annotation\Route;
    
    class PEvenementController extends BaseController {

    /**
     * @Route("/api/get/p_evenement", name="p_evenement_get")
     */
    public function p_evenement_get() :JsonResponse
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT * FROM p_evenement ORDER BY id DESC LIMIT 500;');
        $res = $var->executeQuery()->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }

    /**
     * @Route("/api/search/p_evenement", name="p_evenement_search_get")
     */
    public function p_evenement_search(Request $request) :JsonResponse
    {
        //$search = "%{$_GET['search']}%";
        $data = json_decode($request->getContent(),true) ?? $_POST;
        $searchFields = $data['search'];
        
        $params = [];
        $where = "";
        foreach ($searchFields as $field) {
            $operator = $field['operator']??'=';
            if(isset($field['key']) && !empty($field['key'])) {
                array_push($params, $field['value']);
                $where = $where."AND {$field['key']} $operator ? ";
            }
        }
        $where = substr($where, 3);

        $em = $this->getDoctrine();
        $var = $em->getConnection()->executeQuery("SELECT * FROM p_evenement WHERE $where", $params);
        $res = $var->fetchAll();
        return new JsonResponse($res);
    }

    /**
     * @Route("/api/post/p_evenement", name="admin_p_evenement_add")
     */
    public function p_evenement_add(Request $request, FileUploader $fileUploader)
    {
        $data = json_decode($request->getContent(),true) ?? $_POST; 
        $em = $this->getDoctrine();
        
        $params = [  $data['titre']??null, $data['description']??null ];
        //$var = $em->getConnection()->prepare('call p_evenement_add( ?, ?);');
        $var = $em->getConnection()->prepare('INSERT INTO p_evenement SET  titre = ?, description = ?');
        $var->executeQuery($params);
        
        return new JsonResponse(['msg'=>'Ajout?? avec succes', 'status'=>1]);
    }

    /**
     * @Route("/api/put/p_evenement", name="admin_p_evenement_update")
     */
    public function p_evenement_update(Request $request)
    {
        $data = json_decode($request->getContent(),true) ?? $_POST;
        $em = $this->getDoctrine();
        $params = [  $data['titre']??null, $data['description']??null, $data['id'] ];
        //$var = $em->getConnection()->prepare('call p_evenement_update( ?, ?, ?);');
        $var = $em->getConnection()->prepare('UPDATE p_evenement SET  titre = ?, description = ? WHERE id = ?;');
        $var->executeQuery($params);
        return new JsonResponse(['msg'=>'Modifi?? avec succes', 'status'=>1]);
    }
    
    /**
     * @Route("/api/put2/p_evenement", name="admin_p_evenement_update2")
     */
    public function p_evenement_update2(Request $request)
    {
        $data = json_decode($request->getContent(),true) ?? $_POST;
        
        $params = [];
        $update = "";
        foreach($data as $key => $value) {
            if($key !=='id') {
                array_push($params, $value);
                $update = $update."$key=?,";
            }
        }
        $update = substr($update, 0, -1);
        array_push($params, $data['id']);
        $em = $this->getDoctrine();
        $var = $em->getConnection()->executeQuery(
        "UPDATE p_evenement SET $update WHERE id = ?;", $params);
        
        return new JsonResponse(['msg'=>'Modifi?? avec succes', 'status'=>1]);
    }

    /**
     * @Route("/api/delete/p_evenement/{id}", name="admin_p_evenement_delete")
     */
    public function p_evenement_delete($id)
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('DELETE FROM p_evenement WHERE id = ?;');
        $var->executeQuery([$id]);
        return new JsonResponse(['status'=>1, 'msg'=>'supprim?? avec succ??s']);
    }

}
<?php
    namespace App\Controller;

    use App\Controller\BaseController;
    use App\Service\FileUploader;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Annotation\Route;
    
    class PAssignationController extends BaseController {

    /**
     * @Route("/api/get/p_assignation", name="p_assignation_get")
     */
    public function p_assignation_get() :JsonResponse
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT * FROM p_assignation ORDER BY id DESC LIMIT 500;');
        $res = $var->executeQuery()->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }

    /**
     * @Route("/api/search/p_assignation", name="p_assignation_search_get")
     */
    public function p_assignation_search(Request $request) :JsonResponse
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
        $var = $em->getConnection()->executeQuery("SELECT * FROM p_assignation WHERE $where", $params);
        $res = $var->fetchAll();
        return new JsonResponse($res);
    }

    /**
     * @Route("/api/post/p_assignation", name="admin_p_assignation_add")
     */
    public function p_assignation_add(Request $request, FileUploader $fileUploader)
    {
        $data = json_decode($request->getContent(),true) ?? $_POST; 
        $em = $this->getDoctrine();
        
        $params = [  $data['p_task_id']??null, $data['p_stask_id']??null, $data['p_executant_id']??null, $data['p_assigneur_id']??null ];
        //$var = $em->getConnection()->prepare('call p_assignation_add( ?, ?, ?, ?);');
        $var = $em->getConnection()->prepare('INSERT INTO p_assignation SET  p_task_id = ?, p_stask_id = ?, p_executant_id = ?, p_assigneur_id = ?');
        $var->executeQuery($params);
        
        return new JsonResponse(['msg'=>'Ajouté avec succes', 'status'=>1]);
    }

    /**
     * @Route("/api/put/p_assignation", name="admin_p_assignation_update")
     */
    public function p_assignation_update(Request $request)
    {
        $data = json_decode($request->getContent(),true) ?? $_POST;
        $em = $this->getDoctrine();
        $params = [  $data['p_task_id']??null, $data['p_stask_id']??null, $data['p_executant_id']??null, $data['p_assigneur_id']??null, $data['id'] ];
        //$var = $em->getConnection()->prepare('call p_assignation_update( ?, ?, ?, ?, ?);');
        $var = $em->getConnection()->prepare('UPDATE p_assignation SET  p_task_id = ?, p_stask_id = ?, p_executant_id = ?, p_assigneur_id = ? WHERE id = ?;');
        $var->executeQuery($params);
        return new JsonResponse(['msg'=>'Modifié avec succes', 'status'=>1]);
    }
    
    /**
     * @Route("/api/put2/p_assignation", name="admin_p_assignation_update2")
     */
    public function p_assignation_update2(Request $request)
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
        "UPDATE p_assignation SET $update WHERE id = ?;", $params);
        
        return new JsonResponse(['msg'=>'Modifié avec succes', 'status'=>1]);
    }

    /**
     * @Route("/api/delete/p_assignation/{id}", name="admin_p_assignation_delete")
     */
    public function p_assignation_delete($id)
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('DELETE FROM p_assignation WHERE id = ?;');
        $var->executeQuery([$id]);
        return new JsonResponse(['status'=>1, 'msg'=>'supprimé avec succès']);
    }

}
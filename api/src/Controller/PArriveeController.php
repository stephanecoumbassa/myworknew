<?php
    namespace App\Controller;

    use App\Controller\BaseController;
    use App\Service\FileUploader;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Annotation\Route;
    
    class PArriveeController extends BaseController {

    /**
     * @Route("/api/get/p_arrivee", name="p_arrivee_get")
     */
    public function p_arrivee_get() :JsonResponse
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT * FROM p_arrivee ORDER BY id DESC LIMIT 500;');
        $res = $var->executeQuery()->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }

    /**
     * @Route("/api/search/p_arrivee", name="p_arrivee_search_get")
     */
    public function p_arrivee_search(Request $request) :JsonResponse
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
        $var = $em->getConnection()->executeQuery("SELECT * FROM p_arrivee WHERE $where", $params);
        $res = $var->fetchAll();
        return new JsonResponse($res);
    }

    /**
     * @Route("/api/post/p_arrivee", name="admin_p_arrivee_add")
     */
    public function p_arrivee_add(Request $request, FileUploader $fileUploader)
    {
        $data = json_decode($request->getContent(),true) ?? $_POST; 
        $em = $this->getDoctrine();
        
        $params = [  $data['venue']??null, $data['depart']??null, $data['heurepause']??null, $data['p_employe_id']??null ];
        //$var = $em->getConnection()->prepare('call p_arrivee_add( ?, ?, ?, ?);');
        $var = $em->getConnection()->prepare('INSERT INTO p_arrivee SET  venue = ?, depart = ?, heurepause = ?, p_employe_id = ?');
        $var->executeQuery($params);
        
        return new JsonResponse(['msg'=>'Ajouté avec succes', 'status'=>1]);
    }

    /**
     * @Route("/api/put/p_arrivee", name="admin_p_arrivee_update")
     */
    public function p_arrivee_update(Request $request)
    {
        $data = json_decode($request->getContent(),true) ?? $_POST;
        $em = $this->getDoctrine();
        $params = [  $data['venue']??null, $data['depart']??null, $data['heurepause']??null, $data['p_employe_id']??null, $data['id'] ];
        //$var = $em->getConnection()->prepare('call p_arrivee_update( ?, ?, ?, ?, ?);');
        $var = $em->getConnection()->prepare('UPDATE p_arrivee SET  venue = ?, depart = ?, heurepause = ?, p_employe_id = ? WHERE id = ?;');
        $var->executeQuery($params);
        return new JsonResponse(['msg'=>'Modifié avec succes', 'status'=>1]);
    }
    
    /**
     * @Route("/api/put2/p_arrivee", name="admin_p_arrivee_update2")
     */
    public function p_arrivee_update2(Request $request)
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
        "UPDATE p_arrivee SET $update WHERE id = ?;", $params);
        
        return new JsonResponse(['msg'=>'Modifié avec succes', 'status'=>1]);
    }

    /**
     * @Route("/api/delete/p_arrivee/{id}", name="admin_p_arrivee_delete")
     */
    public function p_arrivee_delete($id)
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('DELETE FROM p_arrivee WHERE id = ?;');
        $var->executeQuery([$id]);
        return new JsonResponse(['status'=>1, 'msg'=>'supprimé avec succès']);
    }

}
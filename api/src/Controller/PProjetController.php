<?php
    namespace App\Controller;

    use App\Controller\BaseController;
    use App\Service\FileUploader;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Annotation\Route;
    
    class PProjetController extends BaseController {

    /**
     * @Route("/api/get/p_projet", name="p_projet_get")
     */
    public function p_projet_get() :JsonResponse
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT * FROM p_projet ORDER BY id DESC LIMIT 500;');
        $res = $var->executeQuery()->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }

    /**
     * @Route("/api/search/p_projet", name="p_projet_search_get")
     */
    public function p_projet_search(Request $request) :JsonResponse
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
        $var = $em->getConnection()->executeQuery("SELECT * FROM p_projet WHERE $where", $params);
        $res = $var->fetchAll();
        return new JsonResponse($res);
    }

    /**
     * @Route("/api/post/p_projet", name="admin_p_projet_add")
     */
    public function p_projet_add(Request $request, FileUploader $fileUploader)
    {
        $data = json_decode($request->getContent(),true) ?? $_POST; 
        $em = $this->getDoctrine();
        
        if (!empty($request->files->get('photo'))) {
            $data['photo'] = $this->upload_files('p_projet', $request->files->get('photo'), $fileUploader);
        }
    
        $params = [  $data['titre']??null, $data['description']??null, $data['status']??null, $data['objectif']??null, $data['progress']??null, $data['datedebut']??null, $data['datefin']??null, $data['createdby']??null, $data['priorite']??null, $data['cout']??null, $data['clientid']??null, $data['porteur']??null, $data['execucants']??null, $data['puuid']??null, $data['photo']??null ];
        //$var = $em->getConnection()->prepare('call p_projet_add( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);');
        $var = $em->getConnection()->prepare('INSERT INTO p_projet SET  titre = ?, description = ?, status = ?, objectif = ?, progress = ?, datedebut = ?, datefin = ?, createdby = ?, priorite = ?, cout = ?, clientid = ?, porteur = ?, execucants = ?, puuid = ?, photo = ?');
        $var->executeQuery($params);
        
        return new JsonResponse(['msg'=>'Ajouté avec succes', 'status'=>1]);
    }

    /**
     * @Route("/api/put/p_projet", name="admin_p_projet_update")
     */
    public function p_projet_update(Request $request)
    {
        $data = json_decode($request->getContent(),true) ?? $_POST;
        $em = $this->getDoctrine();
        $params = [  $data['titre']??null, $data['description']??null, $data['status']??null, $data['objectif']??null, $data['progress']??null, $data['datedebut']??null, $data['datefin']??null, $data['createdby']??null, $data['priorite']??null, $data['cout']??null, $data['clientid']??null, $data['porteur']??null, $data['execucants']??null, $data['puuid']??null, $data['photo']??null, $data['id'] ];
        //$var = $em->getConnection()->prepare('call p_projet_update( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);');
        $var = $em->getConnection()->prepare('UPDATE p_projet SET  titre = ?, description = ?, status = ?, objectif = ?, progress = ?, datedebut = ?, datefin = ?, createdby = ?, priorite = ?, cout = ?, clientid = ?, porteur = ?, execucants = ?, puuid = ?, photo = ? WHERE id = ?;');
        $var->executeQuery($params);
        return new JsonResponse(['msg'=>'Modifié avec succes', 'status'=>1]);
    }
    
    /**
     * @Route("/api/put2/p_projet", name="admin_p_projet_update2")
     */
    public function p_projet_update2(Request $request)
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
        "UPDATE p_projet SET $update WHERE id = ?;", $params);
        
        return new JsonResponse(['msg'=>'Modifié avec succes', 'status'=>1]);
    }

    /**
     * @Route("/api/delete/p_projet/{id}", name="admin_p_projet_delete")
     */
    public function p_projet_delete($id)
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('DELETE FROM p_projet WHERE id = ?;');
        $var->executeQuery([$id]);
        return new JsonResponse(['status'=>1, 'msg'=>'supprimé avec succès']);
    }

}
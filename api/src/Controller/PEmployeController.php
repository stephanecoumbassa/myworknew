<?php
    namespace App\Controller;

    use App\Controller\BaseController;
    use App\Service\FileUploader;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Annotation\Route;
    
    class PEmployeController extends BaseController {

    /**
     * @Route("/api/get/p_employe", name="p_employe_get")
     */
    public function p_employe_get() :JsonResponse
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT * FROM p_employe ORDER BY id DESC LIMIT 500;');
        $res = $var->executeQuery()->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }

    /**
     * @Route("/api/search/p_employe", name="p_employe_search_get")
     */
    public function p_employe_search(Request $request) :JsonResponse
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
        $var = $em->getConnection()->executeQuery("SELECT * FROM p_employe WHERE $where", $params);
        $res = $var->fetchAll();
        return new JsonResponse($res);
    }

    /**
     * @Route("/api/post/p_employe", name="admin_p_employe_add")
     */
    public function p_employe_add(Request $request, FileUploader $fileUploader)
    {
        $data = json_decode($request->getContent(),true) ?? $_POST; 
        $em = $this->getDoctrine();
        
        if (!empty($request->files->get('photo'))) {
            $data['photo'] = $this->upload_files('p_employe', $request->files->get('photo'), $fileUploader);
        }
    
        $params = [  $data['nom']??null, $data['prenom']??null, $data['telephone']??null, $data['email']??null, $data['cni']??null, $data['photo']??null, $data['whatsapp']??null, $data['adresse']??null, $data['datenaissance']??null, $data['genre']??null, $data['banquerib']??null, $data['banquename']??null, $data['fonction']??null, $data['datearrivee']??null, $data['datefin']??null, $data['experience']??null, $data['education']??null, $data['euuid']??null ];
        //$var = $em->getConnection()->prepare('call p_employe_add( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);');
        $var = $em->getConnection()->prepare('INSERT INTO p_employe SET  nom = ?, prenom = ?, telephone = ?, email = ?, cni = ?, photo = ?, whatsapp = ?, adresse = ?, datenaissance = ?, genre = ?, banquerib = ?, banquename = ?, fonction = ?, datearrivee = ?, datefin = ?, experience = ?, education = ?, euuid = ?');
        $var->executeQuery($params);
        
        return new JsonResponse(['msg'=>'Ajouté avec succes', 'status'=>1]);
    }

    /**
     * @Route("/api/put/p_employe", name="admin_p_employe_update")
     */
    public function p_employe_update(Request $request)
    {
        $data = json_decode($request->getContent(),true) ?? $_POST;
        $em = $this->getDoctrine();
        $params = [  $data['nom']??null, $data['prenom']??null, $data['telephone']??null, $data['email']??null, $data['cni']??null, $data['photo']??null, $data['whatsapp']??null, $data['adresse']??null, $data['datenaissance']??null, $data['genre']??null, $data['banquerib']??null, $data['banquename']??null, $data['fonction']??null, $data['datearrivee']??null, $data['datefin']??null, $data['experience']??null, $data['education']??null, $data['euuid']??null, $data['id'] ];
        //$var = $em->getConnection()->prepare('call p_employe_update( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);');
        $var = $em->getConnection()->prepare('UPDATE p_employe SET  nom = ?, prenom = ?, telephone = ?, email = ?, cni = ?, photo = ?, whatsapp = ?, adresse = ?, datenaissance = ?, genre = ?, banquerib = ?, banquename = ?, fonction = ?, datearrivee = ?, datefin = ?, experience = ?, education = ?, euuid = ? WHERE id = ?;');
        $var->executeQuery($params);
        return new JsonResponse(['msg'=>'Modifié avec succes', 'status'=>1]);
    }
    
    /**
     * @Route("/api/put2/p_employe", name="admin_p_employe_update2")
     */
    public function p_employe_update2(Request $request)
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
        "UPDATE p_employe SET $update WHERE id = ?;", $params);
        
        return new JsonResponse(['msg'=>'Modifié avec succes', 'status'=>1]);
    }

    /**
     * @Route("/api/delete/p_employe/{id}", name="admin_p_employe_delete")
     */
    public function p_employe_delete($id)
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('DELETE FROM p_employe WHERE id = ?;');
        $var->executeQuery([$id]);
        return new JsonResponse(['status'=>1, 'msg'=>'supprimé avec succès']);
    }

}
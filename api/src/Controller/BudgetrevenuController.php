<?php
    namespace App\Controller;

    use App\Controller\BaseController;
    use App\Service\FileUploader;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Annotation\Route;
    
    class BudgetrevenuController extends BaseController {

    /**
     * @Route("/api/get/budgetrevenu", name="budgetrevenu_get")
     */
    public function budgetrevenu_get() :JsonResponse
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT * FROM budgetrevenu ORDER BY id DESC LIMIT 500;');
        $res = $var->executeQuery()->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }

    /**
     * @Route("/api/search/budgetrevenu", name="budgetrevenu_search_get")
     */
    public function budgetrevenu_search(Request $request) :JsonResponse
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
        $var = $em->getConnection()->executeQuery("SELECT * FROM budgetrevenu WHERE $where", $params);
        $res = $var->fetchAll();
        return new JsonResponse($res);
    }

    /**
     * @Route("/api/post/budgetrevenu", name="admin_budgetrevenu_add")
     */
    public function budgetrevenu_add(Request $request, FileUploader $fileUploader)
    {
        $data = json_decode($request->getContent(),true) ?? $_POST; 
        $em = $this->getDoctrine();
        
        $params = [  $data['montant']??null, $data['mois']??null, $data['annee']??null, $data['description']??null ];
        //$var = $em->getConnection()->prepare('call budgetrevenu_add( ?, ?, ?, ?);');
        $var = $em->getConnection()->prepare('INSERT INTO budgetrevenu SET  montant = ?, mois = ?, annee = ?, description = ?');
        $var->executeQuery($params);
        
        return new JsonResponse(['msg'=>'Ajouté avec succes', 'status'=>1]);
    }

    /**
     * @Route("/api/put/budgetrevenu", name="admin_budgetrevenu_update")
     */
    public function budgetrevenu_update(Request $request)
    {
        $data = json_decode($request->getContent(),true) ?? $_POST;
        $em = $this->getDoctrine();
        $params = [  $data['montant']??null, $data['mois']??null, $data['annee']??null, $data['description']??null, $data['id'] ];
        //$var = $em->getConnection()->prepare('call budgetrevenu_update( ?, ?, ?, ?, ?);');
        $var = $em->getConnection()->prepare('UPDATE budgetrevenu SET  montant = ?, mois = ?, annee = ?, description = ? WHERE id = ?;');
        $var->executeQuery($params);
        return new JsonResponse(['msg'=>'Modifié avec succes', 'status'=>1]);
    }
    
    /**
     * @Route("/api/put2/budgetrevenu", name="admin_budgetrevenu_update2")
     */
    public function budgetrevenu_update2(Request $request)
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
        "UPDATE budgetrevenu SET $update WHERE id = ?;", $params);
        
        return new JsonResponse(['msg'=>'Modifié avec succes', 'status'=>1]);
    }

    /**
     * @Route("/api/delete/budgetrevenu/{id}", name="admin_budgetrevenu_delete")
     */
    public function budgetrevenu_delete($id)
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('DELETE FROM budgetrevenu WHERE id = ?;');
        $var->executeQuery([$id]);
        return new JsonResponse(['status'=>1, 'msg'=>'supprimé avec succès']);
    }

}
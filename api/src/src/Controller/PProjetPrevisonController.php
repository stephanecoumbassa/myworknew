<?php
    namespace App\Controller;

    use App\Controller\BaseController;
    use App\Service\FileUploader;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Annotation\Route;

    class PProjetPrevisonController extends BaseController {

    /**
     * @Route("/api/get/p_projet_previson", name="p_projet_previson_get")
     */
    public function p_projet_previson_get() :JsonResponse
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT * FROM p_projet_previson WHERE annee = ? AND mois = ?;');
        $res = $var->executeQuery([$_GET['annee'], $_GET['mois']])->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }


    /**
     * @Route("/api/search/p_projet_previson", name="p_projet_previson_search_get")
     */
    public function p_projet_previson_search(Request $request) :JsonResponse
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
        $var = $em->getConnection()->executeQuery("SELECT * FROM p_projet_previson WHERE $where", $params);
        $res = $var->fetchAll();
        return new JsonResponse($res);
    }

    /**
     * @Route("/api/post/p_projet_previson", name="admin_p_projet_previson_add")
     */
    public function p_projet_previson_add(Request $request, FileUploader $fileUploader)
    {
        $data = json_decode($request->getContent(),true) ?? $_POST;
        $em = $this->getDoctrine();

        $params = [  $data['mois']??null, $data['annee']??null, $data['qte_prevision']??null, $data['date_prevision']??null, $data['qte_effective']??null, $data['date_effective']??null, $data['status_effective']??null, $data['reste']??null, $data['dejalivre']??null, $data['locked']??null, $data['project_id']??null ];
        //$var = $em->getConnection()->prepare('call p_projet_previson_add( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);');
        $var = $em->getConnection()->prepare('INSERT INTO p_projet_previson SET  mois = ?, annee = ?, qte_prevision = ?, date_prevision = ?, qte_effective = ?, date_effective = ?, status_effective = ?, reste = ?, dejalivre = ?, locked = ?, project_id = ?');
        $var->executeQuery($params);

        return new JsonResponse(['msg'=>'Ajouté avec succes', 'status'=>1]);
    }

    /**
     * @Route("/api/put/p_projet_previson", name="admin_p_projet_previson_update")
     */
    public function p_projet_previson_update(Request $request)
    {
        $data = json_decode($request->getContent(),true) ?? $_POST;
        $em = $this->getDoctrine();
        $params = [  $data['mois']??null, $data['annee']??null, $data['qte_prevision']??null, $data['date_prevision']??null, $data['qte_effective']??null, $data['date_effective']??null, $data['status_effective']??null, $data['reste']??null, $data['dejalivre']??null, $data['locked']??null, $data['project_id']??null, $data['id'] ];
        //$var = $em->getConnection()->prepare('call p_projet_previson_update( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);');
        $var = $em->getConnection()->prepare('UPDATE p_projet_previson SET  mois = ?, annee = ?, qte_prevision = ?, date_prevision = ?, qte_effective = ?, date_effective = ?, status_effective = ?, reste = ?, dejalivre = ?, locked = ?, project_id = ? WHERE id = ?;');
        $var->executeQuery($params);
        return new JsonResponse(['msg'=>'Modifié avec succes', 'status'=>1]);
    }

    /**
     * @Route("/api/put2/p_projet_previson", name="admin_p_projet_previson_update2")
     */
    public function p_projet_previson_update2(Request $request)
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
        "UPDATE p_projet_previson SET $update WHERE id = ?;", $params);

        return new JsonResponse(['msg'=>'Modifié avec succes', 'status'=>1]);
    }

    /**
     * @Route("/api/delete/p_projet_previson/{id}", name="admin_p_projet_previson_delete")
     */
    public function p_projet_previson_delete($id)
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('DELETE FROM p_projet_previson WHERE id = ?;');
        $var->executeQuery([$id]);
        return new JsonResponse(['status'=>1, 'msg'=>'supprimé avec succès']);
    }

}

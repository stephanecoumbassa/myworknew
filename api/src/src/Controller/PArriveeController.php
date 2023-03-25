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
        $var = $em->getConnection()->prepare('SELECT *,
            if(p_arrivee.depart IS NOT NULL, TIMESTAMPDIFF(HOUR, venue, depart), 0) as heure
            FROM p_arrivee ORDER BY id DESC LIMIT 1000;');
        $res = $var->executeQuery()->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }

    /**
     * @Route("/api/get/p_arrivee/{employeid}", name="p_arrivee_employe_get")
     */
    public function p_arrivee_by_id($employeid) :JsonResponse
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT *,
            if(p_arrivee.depart IS NOT NULL, TIMESTAMPDIFF(HOUR, venue, depart), 0) as heure
            FROM p_arrivee where p_employe_id = ? ORDER BY venue DESC LIMIT 1000;');
        $res = $var->executeQuery([$employeid])->fetchAll();
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
        $var = $em->getConnection()->executeQuery("SELECT *,
            if(p_arrivee.depart IS NOT NULL, TIMESTAMPDIFF(HOUR, venue, depart), 0) as heure
            FROM p_arrivee WHERE $where", $params);
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

        $var = $em->getConnection()->prepare('SELECT * FROM p_arrivee where p_employe_id = ? AND DATE(venue) = CURDATE();');
        $res = $var->executeQuery([$data['p_employe_id']])->fetch();

        if (empty($res)) {
            $data['status'] = 'venue';
            $data['venue'] = date('Y-m-d h:i:s');
            $data['depart'] = null;
            $params = [ $data['venue'], $data['depart'], $data['heurepause']??1, $data['p_employe_id']??null ];
            $var = $em->getConnection()
                ->prepare('INSERT INTO p_arrivee SET venue = ?, depart = ?, heurepause = ?, p_employe_id = ?');
            $var->executeQuery($params);
        }else {
            $data['status'] = 'depart';
            $data['venue'] = $res['venue'];
            $data['depart'] = date('Y-m-d h:i:s');
            $params = [ $res['id'], $data['venue'], $data['depart'], $data['heurepause']??1, $data['p_employe_id']??null ];
            $var = $em->getConnection()
                ->prepare('REPLACE INTO p_arrivee SET id = ?, venue = ?, depart = ?, heurepause = ?, p_employe_id = ?');
            $var->executeQuery($params);
        }

        $data['status'] === 'venue'
            ? $msg = 'Votre arrivée à bien été enregistrée'
            : $msg = 'Votre départ à bien été enregistré';
        return new JsonResponse(['msg'=> $msg, 'status'=>1]);
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

<?php
namespace App\Controller;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CreditController extends BaseController {

    /**
     * @Route("/my/get/credit", name="credit_get")
     */
    public function credit_get() :JsonResponse
    {
        $idmagasin =$this->getUser()->getShopId();
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT * FROM s_credit WHERE id_magasin = ? ORDER BY id DESC LIMIT 500;');
        $res = $var->executeQuery([$idmagasin])->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }

    /**
     * @Route("/my/get/credit_vente", name="credit_vente")
     */
    public function credit_vente() :JsonResponse
    {
        $idmagasin =$this->getUser()->getShopId();
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare("select distinct vcv.id_vente, vcv.client_id,
               (select sum(montant_vendu) from s_ventes sv WHERE sv.id_vente = vcv.id_vente) as total,
               (select sum(montant) from s_credit sc WHERE sc.factureid = vcv.id_vente) as credit
                from v_credit_vente vcv WHERE vcv.id_magasin = ? and type = 'vente';");
        $res = $var->executeQuery([$idmagasin])->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }

    /**
     * @Route("/my/get/credit_appro", name="credit_appro")
     */
    public function credit_appro() :JsonResponse
    {
        $idmagasin =$this->getUser()->getShopId();
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('select distinct vcv.id_ap, vcv.fournisseur_user,
                (select sum(total) from s_aprovisionements sap WHERE sap.id_ap = vcv.id_ap) as total,
               (select sum(montant) from s_credit sc WHERE sc.factureid = vcv.id_ap) as credit
               #-- appro_credit_total(id_ap, id_magasin) total,
               #-- appro_credit_credit(id_ap, id_magasin) credit
            from v_credit_appro vcv WHERE id_magasin = ?;');
        $res = $var->executeQuery([$idmagasin])->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }

    /**
     * @Route("/my/get/credit_list", name="credit_list")
     */
    public function credit_view() :JsonResponse
    {
        $idmagasin =$this->getUser()->getShopId();
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT *, credit_sum_facture(factureid, id_magasin) versement FROM v_credit WHERE id_magasin = ?;');
        $res = $var->executeQuery([$idmagasin])->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }

    /**
     * @Route("/api/get/credit_facture", name="credit__factureget")
     */
    public function credit_facture() :JsonResponse
    {
        $idmagasin =$this->getUser()->getShopId();
        $factureid = $_GET['factureid'];
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT * FROM s_credit WHERE factureid = ? AND id_magasin = ? ORDER BY id DESC LIMIT 500;');
        $res = $var->executeQuery([$factureid, $idmagasin])->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }

    /**
     * @Route("/api/get/credit", name="credit_one_get")
     */
    public function credit_one_get() :JsonResponse
    {
        $id = (int) $_GET['id'];
        $em = $this->getDoctrine();
        $var = $em->getConnection()
            ->prepare('SELECT * FROM s_credit WHERE id = ?;');
        $res = $var->executeQuery([$id])->fetch();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }


    /**
     * @Route("/my/search/credit", name="credit_search_get")
     */
    public function credit_search_get() :JsonResponse
    {
        $idmagasin =$this->getUser()->getShopId();
        $search = "%{$_GET['search']}%";
        $em = $this->getDoctrine();
        $var = $em->getConnection()
            ->prepare('SELECT * FROM s_credit WHERE factureid LIKE ? AND id_magasin = ?;');
        $res = $var->executeQuery([$search, $idmagasin])->fetch();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }


    /**
     * @Route("/my/post/credit", name="admin_credit_add")
     */
    public function credit_add(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $em = $this->getDoctrine();
        $params = [  $data['factureid'], $data['type'] ?? null, $data['montant'] ?? null,
            $data['date'] ?? null, $this->getUser()->getShopId() ];
        $var = $em->getConnection()->prepare('call credit_add( ?, ?, ?, ?, ?);');
        $res = $var->executeQuery($params)->fetch();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }


    /**
     * @Route("/my/put/credit", name="admin_credit_update")
     */
    public function credit_update(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $em = $this->getDoctrine();
        $params = [  $data['factureid'] ?? null, $data['type'] ?? null, $data['montant'] ?? null,
            $data['date'] ?? null, $this->getUser()->getShopId(), $data['id'] ?? null ];
        $var = $em->getConnection()->prepare('call credit_update( ?, ?, ?, ?, ?, ?);');
        $res = $var->executeQuery($params)->fetch();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }


    /**
     * @Route("/my/delete/credit/{id}", name="admin_credit_delete")
     */
    public function credit_delete($id)
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('call credit_delete(?);');
        $res = $var->executeQuery([$id])->fetch();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }

}

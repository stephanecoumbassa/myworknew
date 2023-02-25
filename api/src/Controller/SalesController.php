<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SalesController extends AbstractController
{

    /**
     * @Route("/my/get/sales", name="sales_get")
     */
    public function sales_get(Request $request)
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('CALL __sales_get_all(?)');
        $res = $var->executeQuery([$this->getUser()->getShopId()])->fetchAll();

        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }

    /**
     * @Route("/my/get/sales_stats", name="sales_stats_get")
     */
    public function sales_statst_get(Request $request)
    {
        $em = $this->getDoctrine();
        $params = [$_GET['first'], $_GET['last'], $this->getUser()->getShopId()];
        $var = $em->getConnection()->prepare('CALL __sales_by_date(?, ?, ?)');
        $res = $var->executeQuery($params)->fetchAll();

        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);

        return new JsonResponse($res);
    }

    /**
     * @Route("/my/get/sales_product_stats", name="sales_product_stats_get")
     */
    public function sales_product_stat_get(Request $request)
    {
        $em = $this->getDoctrine();
        $params = [$_GET['first'], $_GET['last'], $this->getUser()->getShopId(), $_GET['pid']];
        $var = $em->getConnection()->prepare('CALL __sales_product_by_date(?, ?, ?, ?)');
        $res = $var->executeQuery($params)->fetchAll();

        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);

        return new JsonResponse($res);
    }

    /**
     * @Route("/my/get/sales_client_stats", name="sales_client_stats_get")
     */
    public function sales_client_stat_get(Request $request)
    {
        $em = $this->getDoctrine();
        $params = [$_GET['first'], $_GET['last'], $this->getUser()->getShopId(), $_GET['clientid']];
        $var = $em->getConnection()->prepare('CALL __sales_client_by_date(?, ?, ?, ?)');
        $res = $var->executeQuery($params)->fetchAll();

        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);

        return new JsonResponse($res);
    }

    /**
     * @Route("/my/get/factures_id", name="factures_get")
     */
    public function facture_get(Request $request)
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare("SELECT DISTINCT (id_vente) facture, 'vente' type, id_magasin, client_id, client_func_get(client_id, id_magasin) client, date_posted dateposted,
                    credit_sum_facture(id_vente, id_magasin) versement,
                __sales_sum_facture(id_vente, id_magasin) total from s_ventes
                                               WHERE id_magasin = ? AND type = 'vente' ORDER BY facture DESC;");
        $res = $var->executeQuery([$this->getUser()->getShopId()])->fetchAll();

        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }

    /**
     * @Route("/my/get/sales_by_facture", name="sales_factures_get")
     */
    public function facture_get_by_id(Request $request)
    {
        $id_vente = $_GET['id_vente'];
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare("
                SELECT s_ventes.id, quantite_vendu, prix_unitaire, montant_vendu, total, benefice_vente, date_posted, date_posted dateposted,
                       product_id, sp.name, user_id, id_vente, s_ventes.id_magasin, fournisseur_id,
                       date(date_posted) date, client_id, code_ap, s_ventes.tva, credit, codecomptable,
                       status, type, retour, motif, avoir_num, avoir_unitaire, avoir_ttc,
                       client_func_get(client_id, s_ventes.id_magasin) client from s_ventes
                    left join s_product sp on s_ventes.product_id = sp.id
                where s_ventes.id_magasin = ? AND id_vente = ? AND s_ventes.type = 'vente' ORDER BY s_ventes.id_vente");
        $res = $var->executeQuery([$this->getUser()->getShopId(), $id_vente])->fetchAll();

        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }

    /**
     * @Route("/my/get/sales_by_idvente", name="sales_factures_get2")
     */
    public function facture_get_by_id2(Request $request)
    {
        $id_vente = $_GET['id_vente'];
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare("CALL __sales_by_idvente(?, ?)");
        $res = $var->executeQuery([ $id_vente, $this->getUser()->getShopId() ])->fetchAll();

        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }

    /**
     * @Route("/my/get/sales_by_credit", name="sales_factures_credit_get")
     */
    public function facture_credit(Request $request)
    {
        $idmagasin =$this->getUser()->getShopId();
        $id_vente = $_GET['id_vente'];
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT * from s_credit where factureid = ? AND id_magasin = ? ORDER BY id DESC');
        $res = $var->executeQuery([$id_vente, $idmagasin])->fetchAll();

        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }

    /**
     * @Route("/my/get/facture_number", name="factures_number_get")
     */
    public function facture_number(Request $request)
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT id_vente+1 as nb from s_ventes
                                        WHERE id_magasin = ? ORDER BY id_vente desc limit 1');
        $res = $var->executeQuery([$this->getUser()->getShopId()]);
        $res = $var->fetch();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);

        return new JsonResponse($res);
    }

    /**
     * @Route("/my/post/sales", name="sales_post")
     */
    public function commands_register(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $em = $this->getDoctrine();
        $agent = $this->getUser()->getId();
        $shop_id = $this->getUser()->getShopId();
        $code_vente = 'V-'.date("ymd-his", time());
        $clientid = $data['clientid'];
        $products = $data['products'];
        $dateposted = $data['dateposted'] ?? date("y-m-d h:i", time());

        $count = count($products);
        for ($i=0; $i<$count; $i++) {
            $pid = $products[$i]['p']['id'] ?? $products[$i]['id'];
            $qty = $products[$i]['quantity'];
            $sell = $products[$i]['p']['sales_price'] ?? $products[$i]['sales_price'];
            $tva = $products[$i]['p']['tva'] ?? 0;
            $var = $em->getConnection()->prepare('CALL __sales_register_prix_moyen4(?, ?, ? ,?, ?, ?, ?, ?, ?);');
            $res = $var->executeQuery([$agent, $pid , $qty, $sell, $tva, $clientid, $code_vente, $dateposted, $shop_id])
                        ->fetch();
        }
        $res['factureid'] = $code_vente;
        if($data['credit'] == true){
            $var = $em->getConnection()->prepare('INSERT INTO s_credit (montant, type, factureid, date, id_magasin) VALUES (?, ?, ?, NOW(), ?);');
            $res = $var->executeQuery([$data['avance'], 'vente', $code_vente, $this->getUser()->getShopId()]);
        }elseif($data['credit'] == false){
            $var = $em->getConnection()->prepare('INSERT INTO s_credit ( montant, type, factureid, date, id_magasin) VALUES (?, ?, ?, NOW(), ?);');
            $res = $var->executeQuery([$data['total'], 'vente', $code_vente, $this->getUser()->getShopId()]);
        }
        return new JsonResponse($res);
    }

    /**
     * @Route("/my/put/sales", name="sales_put")
     */
    public function sales_update(Request $request){
        $data = json_decode($request->getContent(),true);
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('UPDATE s_ventes
        SET quantite_vendu = ?, prix_unitaire = ?, montant_vendu = ?, product_id = ? WHERE id = ? AND id_magasin = ? ORDER BY id DESC');
        $res = $var->executeQuery([$data['quantite_vendu'], $data['prix_unitaire'], $data['montant_vendu']??null, $data['p_id'], $data['id'], $this->getUser()->getShopId() ]);
        return new JsonResponse(["msg"=>"Modifié avec succès", "status"=>1]);
    }

    /**
     * @Route("/my/dateposted/sales", name="sales_dateposted")
     */
    public function sales_dateposted(Request $request){
        $data = json_decode($request->getContent(),true);
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('UPDATE s_ventes SET date_posted = ? WHERE id_vente = ? AND id_magasin = ?;');
        $res = $var->executeQuery([ $data['dateposted'], $data['id_vente'], $this->getUser()->getShopId() ]);
        return new JsonResponse(["msg"=>"Modifié avec succès", "status"=>1]);
    }

//  /**
//   * @Route("/my/put/retour", name="retour_put")
//   */
//  public function retour_update(Request $request){
//    $data = json_decode($request->getContent(),true);
//    $em = $this->getDoctrine();
//    $var = $em->getConnection()->prepare('UPDATE s_ventes SET retour = ?, motif = ? WHERE id = ? AND id_magasin = ?;');
//    $res = $var->executeQuery([$data['retour'], $data['motif'], $data['id'], $this->getUser()->getShopId() ]);
//    return new JsonResponse(["msg"=>"Modifié avec succès", "status"=>1]);
//  }

    /**
     * @Route("/my/get/command", name="command_list_get")
     */
    public function command_get(Request $request)
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT * FROM s_cmde_product
        LEFT JOIN s_product ON s_product.id = s_cmde_product.product_id where s_cmde_product.id_magasin = ?;');
        $res = $var->executeQuery([$this->getUser()->getShopId()])->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }

    /**
     * @Route("/my/delete/sales", name="sales_delete")
     */
    public function sales_delete(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $shopid = $this->getUser()->getShopId();
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('DELETE FROM s_ventes WHERE id = ? AND id_magasin = ?;');
        $res = $var->executeQuery([$data['id'], $shopid]);
        return new JsonResponse(["msg"=>"supprimé avec succès", "status"=>1]);
    }


  /**
   * @Route("/my/delete/sales_all", name="sales_delete_all")
   */
  public function sales_delete_all(Request $request)
  {
      $data = json_decode($request->getContent(), true);
      $factureid = $data['factureid'];
      $shopid = $this->getUser()->getShopId();
      $em = $this->getDoctrine();
      $var = $em->getConnection()->prepare('DELETE FROM s_ventes where id_vente = ? AND id_magasin = ?;');
      $res = $var->executeQuery([ $factureid, $shopid ]);
      $var = $em->getConnection()->prepare("DELETE FROM s_credit where factureid = ? AND id_magasin = ? AND type = 'vente';");
      $res = $var->executeQuery([ $factureid, $shopid ]);
      return new JsonResponse(["msg"=>"supprimé avec succès", "status"=>1]);
  }

}

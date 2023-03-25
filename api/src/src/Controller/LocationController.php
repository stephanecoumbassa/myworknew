<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LocationController extends AbstractController
{

    /**
     * @Route("/my/get/location", name="location_get")
     */
    public function location_get(Request $request)
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare("SELECT s_location.id, s_location.quantity, quantity_back, is_back, s_location.price, price_total, remise_unite, remise_totale, benefice, date_posted,
         spl.name, product_id, user_id, id_location, s_location.id_magasin, client_id, s_location.tva, status, type, date_start, date_end, caution,
         client_func_get(client_id, s_location.id_magasin) client from s_location
        left join s_product_location spl on s_location.product_id = spl.id
       where s_location.id_magasin = ? AND type = 'location' ORDER BY id DESC");
        $var->execute([$this->getUser()->getShopId()]);
        $res = $var->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }

    /**
     * @Route("/my/get/location_stats", name="location_stats_get")
     */
    public function location_statst_get(Request $request)
    {
        $em = $this->getDoctrine();
        $params = [$_GET['first'], $_GET['last'], $this->getUser()->getShopId()];
        $var = $em->getConnection()->prepare('CALL __location_by_date(?, ?, ?)');
        $var->execute($params);
        $res = $var->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);

        return new JsonResponse($res);
    }

    /**
     * @Route("/my/get/location_product_stats", name="location_product_stats_get")
     */
    public function location_product_stat_get(Request $request)
    {
        $em = $this->getDoctrine();
        $params = [$_GET['first'], $_GET['last'], $this->getUser()->getShopId(), $_GET['pid']];
        $var = $em->getConnection()->prepare('CALL __location_product_by_date(?, ?, ?, ?)');
        $var->execute($params);
        $res = $var->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);

        return new JsonResponse($res);
    }

    /**
     * @Route("/my/get/location_client_stats", name="location_client_stats_get")
     */
    public function location_client_stat_get(Request $request)
    {
        $em = $this->getDoctrine();
        $params = [$_GET['first'], $_GET['last'], $this->getUser()->getShopId(), $_GET['clientid']];
        $var = $em->getConnection()->prepare('CALL __location_client_by_date(?, ?, ?, ?)');
        $var->execute($params);
        $res = $var->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);

        return new JsonResponse($res);
    }

    /**
     * @Route("/my/get/factures_id", name="factures_get")
     */
    public function facture_get(Request $request)
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare("SELECT DISTINCT id_vente facture, 'vente' type, id_magasin,
                    credit_sum_facture(id_vente, id_magasin) versement, __location_sum_facture(id_vente, id_magasin) total from s_ventes
                                               WHERE id_magasin = ? AND type = 'vente' ORDER BY facture DESC;");
        $var->execute([$this->getUser()->getShopId()]);
        $res = $var->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }

    /**
     * @Route("/my/get/location_by_facture", name="location_factures_get")
     */
    public function location_facture(Request $request)
    {
        $id_location = $_GET['id_location'];
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare("SELECT s_location.id, s_location.quantity, quantity_back, is_back, s_location.price, price_total, remise_unite, remise_totale, benefice, date_posted,
                                            spl.name, product_id, user_id, id_location, s_location.id_magasin, client_id, s_location.tva, status, type, date_start, date_end, caution,
                                            client_func_get(client_id, s_location.id_magasin) client from s_location
                                            left join s_product_location spl on s_location.product_id = spl.id
                                            where s_location.id_magasin = ? AND id_location = ? AND type = 'location' ORDER BY id_location");
        $var->execute([$this->getUser()->getShopId(), $id_location]);
        $res = $var->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }

    /**
     * @Route("/my/get/location_by_credit", name="location_factures_credit_get")
     */
    public function facture_credit(Request $request)
    {
        $idmagasin =$this->getUser()->getShopId();
        $id_vente = $_GET['id_vente'];
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT * from s_credit where factureid = ? AND id_magasin = ? ORDER BY id DESC');
        $var->execute([$id_vente, $idmagasin]);
        $res = $var->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }


    /**
     * @Route("/my/post/location", name="location_post")
     */
    public function location_register(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $em = $this->getDoctrine();
        $agent = $this->getUser()->getId();
        $shop_id = $this->getUser()->getShopId();
        $code_vente = 'L-'.date("ymd-his", time());
        $clientid = $data['clientid'];
        $products = $data['products'];
        $date_start = $data['date_start'];
        $date_end = $data['date_end'];
        $caution = $data['caution'] ?? 0;

        $count = count($products);
        for ($i=0; $i<$count; $i++) {
            $pid = $products[$i]['product_id'];
            $qty = $products[$i]['quantity'];
            $sell = $products[$i]['price'];
            $tva = $products[$i]['tva'];
            $remise = $products[$i]['remise'] ?? 0;
            $var = $em->getConnection()->prepare("INSERT INTO s_location
                    (user_id, product_id, quantity, price, tva, remise_unite, caution, client_id, date_start, date_end, id_location, id_magasin)
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                    ");
            $var->execute([$agent, $pid , $qty, $sell, $tva, $remise, $caution, $clientid, $date_start, $date_end, $code_vente, $shop_id]);
        }

        $res['factureid'] = $code_vente;
        $res['status'] = 1;
        $res['msg'] = "Ajouté avec succès";
        return new JsonResponse($res);
    }


    /**
     * @Route("/my/put/location", name="location_put")
     */
    public function location_update(Request $request){
        $data = json_decode($request->getContent(),true);
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('UPDATE s_location
        SET quantity = ?, price = ?, product_id = ?, tva = ? WHERE id = ? AND id_magasin = ? ORDER BY id DESC');
        $var->execute([$data['quantity'], $data['price'], $data['product_id'], $data['tva'], $data['id'], $this->getUser()->getShopId() ]);
        return new JsonResponse(["msg"=>"Modifié avec succès", "status"=>1]);
    }

    /**
     * @Route("/my/get/command", name="command_list_get")
     */
    public function command_get(Request $request)
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT * FROM s_cmde_product
        LEFT JOIN s_product ON s_product.id = s_cmde_product.product_id where s_cmde_product.id_magasin = ?;');
        $var->execute([$this->getUser()->getShopId()]);
        $res = $var->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);

        return new JsonResponse($res);
    }

    /**
     * @Route("/my/delete/location", name="location_delete")
     */
    public function location_delete(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $shopid = $this->getUser()->getShopId();
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('DELETE FROM s_ventes WHERE id = ? AND id_magasin = ?;');
        $var->execute([$data['id'], $shopid]);
        return new JsonResponse(["msg"=>"supprimé avec succès", "status"=>1]);
    }


    /**
     * @Route("/my/delete/location_all", name="location_delete_all")
     */
    public function location_delete_all(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $factureid = $data['factureid'];
        $shopid = $this->getUser()->getShopId();
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('DELETE FROM s_ventes where id_vente = ? AND id_magasin = ?;');
        $var->execute([ $factureid, $shopid ]); $var->closeCursor();
        $var = $em->getConnection()->prepare("DELETE FROM s_credit where factureid = ? AND id_magasin = ? AND type = 'vente';");
        $var->execute([ $factureid, $shopid ]); $var->closeCursor();
        return new JsonResponse(["msg"=>"supprimé avec succès", "status"=>1]);
    }

}

<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PerteController extends AbstractController
{

    /**
     * @Route("/my/get/pertes", name="pertes_get")
     */
    public function sales_get(Request $request)
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare("CALL __pertes_get_all(?);");
        $var->execute([$this->getUser()->getShopId()]);
        $res = $var->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }

    /**
     * @Route("/my/get/pertes_stats", name="pertes_stats_get")
     */
    public function pertes_stats_get(Request $request)
    {
        $em = $this->getDoctrine();
        $params = [$_GET['first'], $_GET['last'], $this->getUser()->getShopId()];
        $var = $em->getConnection()->prepare('CALL __pertes_by_date(?, ?, ?)');
        $var->execute($params);
        $res = $var->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }

    /**
     * @Route("/my/get/pertes_product_stats", name="pertes_product_stats_get")
     */
    public function sales_product_stat_get(Request $request)
    {
        $em = $this->getDoctrine();
        $params = [$_GET['first'], $_GET['last'], $this->getUser()->getShopId(), $_GET['pid']];
        $var = $em->getConnection()->prepare('CALL __sales_product_by_date(?, ?, ?, ?)');
        $var->execute($params);
        $res = $var->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }

//    /**
//     * @Route("/my/get/sales_client_stats", name="sales_client_stats_get")
//     */
//    public function sales_client_stat_get(Request $request)
//    {
//        $em = $this->getDoctrine();
//        $params = [$_GET['first'], $_GET['last'], $this->getUser()->getShopId(), $_GET['clientid']];
//        $var = $em->getConnection()->prepare('CALL __sales_client_by_date(?, ?, ?, ?)');
//        $var->execute($params);
//        $res = $var->fetchAll();
//        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
//
//        return new JsonResponse($res);
//    }

    /**
     * @Route("/my/post/pertes", name="pertes_post")
     */
    public function commands_register(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $em = $this->getDoctrine();
        $agent = $this->getUser()->getId();
        $shop_id = $this->getUser()->getShopId();
        $code_vente = 'PER-'.date("ymd-his", time());
        $products = $data['products'];

        $count = count($products);
        for ($i=0; $i<$count; $i++) {
            $pid = $products[$i]['p']['id'];
            $qty = $products[$i]['quantity'];
            $sell = $products[$i]['p']['sales_price'];
            $tva = $products[$i]['p']['tva'];
            $var = $em->getConnection()->prepare('INSERT INTO s_ventes
                        (user_id, product_id, quantite_vendu, prix_unitaire, montant_vendu, id_vente, id_magasin, type)
                        VALUES(?, ?, ?, ?, ?, ?, ?, ?)');
            $var->execute([$agent, $pid , $qty, $sell, (int)$qty * (int)$sell , $code_vente, $shop_id, 'perte']);
        }
        $res['factureid'] = $code_vente;
        return new JsonResponse(['status'=>1, 'msg'=>'Perte ajoutée']);
    }


}

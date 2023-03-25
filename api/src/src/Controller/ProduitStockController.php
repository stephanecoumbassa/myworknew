<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProduitStockController extends AbstractController
{

    use JwtTrait;


//    /**
//     * @Route("/my/get/stock_list/date", name="product_stocks_list")
//     */
//    public function stock_list($date)
//    {
//        $idmagasin =$this->getUser()->getShopId();
//        $em = $this->getDoctrine();
//        $var = $em->getConnection()->prepare('SELECT s_product_stock.id, product_id, quantite, date, name from s_product_stock
//         left join s_product sp on s_product_stock.product_id = sp.id
//         where date = ? ORDER BY id DESC');
//        $res = $var->execute([$date])->fetchAll();
//        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
//        return new JsonResponse($res);
//    }

    /**
     * @Route("/my/get/stock/{date}", name="stocks_get")
     */
    public function stock($date) {
        $idmagasin =$this->getUser()->getShopId();
        $em = $this->getDoctrine();
        $var = $em->getConnection()
            ->prepare("SELECT distinct (s_product.id), s_product.id as product_id, sps.id stockid,
                        ifnull(quantite, 0) quantite, ifnull(date, $date) date, name from s_product
                       left join s_product_stock sps on sps.product_id = s_product.id
                       where (date = ? OR date IS NULL OR date = '')
                       ORDER BY sps.id DESC");
        $res = $var->execute([$date])->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }

    // stock
    /**
     * @Route("/my/post/stock", name="stock_posts")
     */
    public function product_stock(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $em = $this->getDoctrine();
        $var = $em->getConnection()
            ->prepare('REPLACE INTO s_product_stock
                (product_id, quantite, date, id_magasin) VALUES (?,?,?,?)');
        $var->executeQuery([$data['product_id'], $data['quantite'], $data['date'], $data['id_magasin']?? 1 ])->fetchAll();
        return new JsonResponse(['msg'=> 'La quantité initiale a été modifiée']);
    }


}

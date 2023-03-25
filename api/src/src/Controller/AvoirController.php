<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AvoirController extends AbstractController
{

  /**
   * @Route("/my/get/avoirs", name="my_avoirs_get")
   */
  public function avoir_get(Request $request)
  {
    $em = $this->getDoctrine();
    $var = $em->getConnection()->prepare("SELECT s_retours.id, product_id, sp.name p_name, quantite, prix_unitaire, s_retours.tva, prix_ttc, user_id, motif,
       type, id_vente, id_ap, avoir_num, clientid, s_retours.id_magasin, fournisseurid, s_retours.dateposted, su.name a_name, su.last_name a_last_name FROM s_retours
        LEFT JOIN s_product sp ON product_id = sp.id
        LEFT JOIN s_users su on user_id = su.id
        WHERE s_retours.id_magasin = ? AND s_retours.type = 'achat';");
    $var->execute([$this->getUser()->getShopId()]);
    $res = $var->fetchAll();
    $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
    return new JsonResponse($res);
  }

  /**
   * @Route("/my/get/avoir_by_facture", name="avoir_factures_get")s_retours
   */
  public function avoir_get_by_id(Request $request)
  {
    $id_vente = $_GET['id_vente'];
    $em = $this->getDoctrine();
    $var = $em->getConnection()->prepare("SELECT * from s_retours
                                          where id_magasin = ? AND avoir_num = ? AND type = 'vente' ORDER BY id_vente");
    $var->execute([$this->getUser()->getShopId(), $id_vente]);
    $res = $var->fetchAll();
    $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
    return new JsonResponse($res);
  }

  /**
   * @Route("/my/get/avoir_by_idvente", name="avoir_factures_get2")
   */
  public function avoir_get_by_id2(Request $request)
  {
    $id_vente = $_GET['id_vente'];
    $em = $this->getDoctrine();
    $var = $em->getConnection()->prepare("SELECT s_retours.id, product_id, sp.name p_name, quantite, prix_unitaire, s_retours.tva, prix_ttc, user_id, motif, type,
                                            id_vente, id_ap, avoir_num, clientid, fournisseurid, dateposted, description, s_retours.id_magasin from s_retours
                                          LEFT JOIN s_product sp ON sp.id = product_id
                                          where s_retours.id_magasin = ? AND avoir_num = ? AND type = 'vente' ORDER BY id_vente");
    $var->execute([$this->getUser()->getShopId(), $id_vente]);
    $res = $var->fetchAll();
    $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
    return new JsonResponse($res);
  }


  /**
   * @Route("/my/post/avoir", name="avoir_post")
   */
  public function commands_register(Request $request)
  {
    $data = json_decode($request->getContent(),true);
    $em = $this->getDoctrine();
    $agent = $this->getUser()->getId();
    $shop_id = $this->getUser()->getShopId();
    $avoir_num = 'RA-'.date("ymd-his", time());
    $products = $data['products'];

    $count = count($products);
    for ($i=0; $i<$count; $i++) {
      $pid = $products[$i]['p']['id'];
      $qty = $products[$i]['quantite'];
      $sell = $products[$i]['p']['sales_price'];
      $tva = $products[$i]['p']['tva'];
      $motif = $products[$i]['p']['motif'];
      $fournisseurid = $data['fournisseurid'] ?? null;
      $id_vente = $data['id_vente'] ?? null;
      $var = $em->getConnection()->prepare('INSERT INTO s_retours
        (user_id, product_id, quantite, prix_unitaire, tva, avoir_num, motif, type, fournisseurid, id_magasin)
        VALUES (?, ?, ? ,?, ?, ?, ?, ?, ?, ?);');
      $var->execute([$agent, $pid , $qty, $sell, $tva, $avoir_num, $motif, 'achat', $fournisseurid, $shop_id]);
    }
    $res['factureid'] = $avoir_num;
    $res['status'] = 1;
    $res['msg'] = 'Ajouté avec succès';
    return new JsonResponse($res);
  }


  /**
   * @Route("/my/put/avoir", name="avoir_put")
   */
  public function avoir_update(Request $request){
    $data = json_decode($request->getContent(),true);
    $em = $this->getDoctrine();
    $var = $em->getConnection()->prepare('UPDATE s_retours
        SET quantite = ?, prix_unitaire = ?, prix_ttc = ?, product_id = ? WHERE id = ? AND id_magasin = ? ORDER BY id DESC');
    $var->execute([$data['quantite'], $data['prix_unitaire'], $data['prix_ttc']??null, $data['product_id'], $data['id'], $this->getUser()->getShopId() ]);
    return new JsonResponse(["msg"=>"Modifié avec succès", "status"=>1]);
  }


  /**
   * @Route("/my/delete/avoir", name="avoir_delete")
   */
  public function avoir_delete(Request $request)
  {
    $data = json_decode($request->getContent(), true);
    $shopid = $this->getUser()->getShopId();
    $em = $this->getDoctrine();
    $var = $em->getConnection()->prepare('DELETE FROM s_ventes WHERE id = ? AND id_magasin = ?;');
    $var->execute([$data['id'], $shopid]);
    return new JsonResponse(["msg"=>"supprimé avec succès", "status"=>1]);
  }


  /**
   * @Route("/my/delete/avoir_all", name="avoir_delete_all")
   */
  public function avoir_delete_all(Request $request)
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

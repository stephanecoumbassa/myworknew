<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BonCommandeController extends AbstractController
{

  /**
   * @Route("/api/get/bon", name="bon_get")
   */
  public function appro_get(Request $request)
  {
    $em = $this->getDoctrine();
    $var = $em->getConnection()->prepare('SELECT * FROM s_boncommande');
    $res = $var->executeQuery()->fetchAll();
    $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
    return new JsonResponse($res);
  }

  /**
   * @Route("/my/get/bon", name="bon_factures_get")
   */
  public function bon_appro_get(Request $request)
  {
    $em = $this->getDoctrine();
    $var = $em->getConnection()->prepare("SELECT distinct(id_ap), fournisseur_user, sc.last_name, sc.name,
               (select dateposted from s_boncommande WHERE id_ap = s_boncommande.id_ap ORDER BY id DESC LIMIT 1) date_posted FROM s_boncommande
               LEFT JOIN s_fournisseurs sc on s_boncommande.fournisseur_user = sc.id AND sc.id_magasin = s_boncommande.id_magasin
                        WHERE s_boncommande.id_magasin = ? ORDER BY id_ap DESC");
    $res = $var->executeQuery([$this->getUser()->getShopId()])->fetchAll();
    $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
    return new JsonResponse($res);
  }

  /**
   * @Route("/my/get/bon/{idvente}", name="bon_factures_by")
   */
  public function bon_vente_get($idvente)
  {
    $em = $this->getDoctrine();
    $var = $em->getConnection()->prepare('SELECT *, fournisseur_func_get(fournisseur_user, s_boncommande.id_magasin) fournisseur FROM s_boncommande WHERE
                            s_boncommande.id_magasin = ? AND id_ap = ? ORDER BY s_boncommande.id DESC');
    $res = $var->executeQuery([$this->getUser()->getShopId(), $idvente])->fetchAll();
    $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
    return new JsonResponse($res);
  }

  /**
   * @Route("/my/get/bon", name="appro_bon_get")
   */
  public function facture_get_by_id(Request $request)
  {
    $id_vente = $_GET['id_ap'];
    $em = $this->getDoctrine();
    $var = $em->getConnection()->prepare('SELECT *, fournisseur_func_get(fournisseur_user) fournisseur from s_boncommande where id_magasin = ? AND id_ap = ?;');
    $res = $var->executeQuery([$this->getUser()->getShopId(), $id_vente])->fetchAll();
    $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
    return new JsonResponse($res);
  }

  /**
   * @Route("/my/get/bon_stats", name="bon_stats_get")
   */
  public function bon_statst_get(Request $request)
  {
    $em = $this->getDoctrine();
    $params = [$_GET['first'], $_GET['last'], $this->getUser()->getShopId()];
    $var = $em->getConnection()->prepare('CALL __appro_by_date(?, ?, ?)');
    $res = $var->executeQuery($params)->fetchAll();
    return new JsonResponse($res);
  }

  /**
   * @Route("/my/get/bon_fournisseur_stats", name="bon_stats_fournisseur_get1")
   */
  public function bon_fournisseur_stat_get(Request $request)
  {
    $em = $this->getDoctrine();
    $params = [$_GET['first'], $_GET['last'], $this->getUser()->getShopId(), $_GET['fournisseurid']];
    $var = $em->getConnection()->prepare('CALL __appro_fournisseur_by_date(?, ?, ?, ?)');
    $res = $var->executeQuery($params)->fetchAll();
    return new JsonResponse($res);
  }

  /**
   * @Route("/my/post/bon", name="bon_post")
   */
  public function bon_register(Request $request)
  {
    $data = json_decode($request->getContent(),true);
    $em = $this->getDoctrine();
    $agent = $this->getUser()->getId();
    $shop_id = $this->getUser()->getShopId();
    $fournisseur = $data['fournisseur'];
    $products = $data['products'];
    $id_facture = 'BON-'.date("ymd-his", time());
    $count = count($products);
    for ($i=0; $i<$count; $i++) {
      $var = $em->getConnection()->prepare('
                INSERT INTO s_boncommande (product_id, user_id, fournisseur_user, amount, buying_price, sell_price, tva, id_magasin, id_ap)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);');
      $res = $var->executeQuery([$products[$i]['id'], $agent, $fournisseur, $products[$i]['amount'],
                     $products[$i]['buying_price'], $products[$i]['sell_price'] ?? 0, $products[$i]['tva'], $shop_id, $id_facture]);
    }
    return new JsonResponse(['msg'=>'Bon de commande ajouté avec succès', 'factureid'=> $id_facture]);
  }

  /**
   * @Route("/my/put/bon", name="bon_put")
   */
  public function bon_update(Request $request){
    $data = json_decode($request->getContent(),true);
    $em = $this->getDoctrine();
    $var = $em->getConnection()->prepare('UPDATE s_boncommande SET sell_price = ?, buying_price = ?, amount = ?, product_id = ? WHERE id = ? AND id_magasin = ? ORDER BY id DESC');
    $res = $var->executeQuery([$data['sell_price']??null, $data['buying_price']??null, $data['amount']??null, $data['product_id']??null, $data['id']??null, $this->getUser()->getShopId() ]);
    return new JsonResponse(["msg"=>"Modifié avec succès", "status"=>1]);
  }

  /**
   * @Route("/my/delete/appro_all", name="appro_delete_all")
   */
  public function appro_delete_all(Request $request)
  {
    $data = json_decode($request->getContent(), true);
    $factureid = $data['factureid'];
    $shopid = $this->getUser()->getShopId();
    $em = $this->getDoctrine();
    $var = $em->getConnection()->prepare('DELETE FROM s_boncommande where id_ap = ? AND id_magasin = ?;');
    $res = $var->executeQuery([ $factureid, $shopid ]); $res = $var->closeCursor();
    return new JsonResponse(["msg"=>"supprimé avec succès", "status"=>1]);
  }

}

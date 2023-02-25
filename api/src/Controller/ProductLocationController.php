<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductLocationController extends AbstractController
{

  use JwtTrait;

  /**
   * @Route("/my/get/products_location", name="products_location_get")
   */
  public function product_location_get(Request $request)
  {
    $em = $this->getDoctrine();
    $var = $em->getConnection()->prepare("SELECT s_product_location.id, s_product_location.name, disabled,
                                reference, description, product_url_img, domain_id, agent_user, __product_location_qty(s_product_location.id_magasin, s_product_location.id) reste,
                                quantity, price, tva, priceweb, color, webstatus, largeur, longueur, poids, hauteur, parent_categorie_id,  media_get(2, s_product_location.id) photos
                              FROM s_product_location
                              LEFT JOIN s_categorie_parent scp on scp.id = s_product_location.parent_categorie_id
                              LEFT JOIN s_categories_domain scd on scd.id = domain_id
                              WHERE s_product_location.id_magasin = ? AND disabled= 0 ORDER BY id DESC;");
    $var->execute([$this->getUser()->getIdMagasin()]);
    $res = $var->fetchAll();
    $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);

    return new JsonResponse($res);
  }

  /**
   * @Route("/my/disabled/products_location/{productid}", name="product_location_disabled")
   */
  public function product_disabled(Request $request, $productid)
  {
    $data = json_decode($request->getContent(),true);
    $em = $this->getDoctrine();
    $var = $em->getConnection()->prepare('UPDATE s_product_location SET disabled = 1 WHERE id = ? AND id_magasin = ?');
    $var->execute([$productid, $this->getUser()->getShopId()]);
    return new JsonResponse(['status' =>1, 'msg' => 'Désactivé avec succès']);
  }

  /**
   * @Route("/my/post/products_location", name="product_location_add")
   */
  public function product_location_register(Request $request)
  {
    $data = json_decode($request->getContent(),true);
    $em = $this->getDoctrine();
    $params = [
      $data['name'], $data['domain_id'] ?? null, $data['parent_categorie_id'] ?? 1, $data['quantity'] ?? 0, $data['webstatus'] ?? 1, $data['marque_id'] ?? null,
      $data['description'] ?? null, $data['price'] ?? null, $data['largeur'] ?? null, $data['longueur'] ?? null, $data['tva'] ?? 0,
      $data['hauteur'] ?? null, $data['poids'] ?? null, $data['youtube'] ?? null, (int)$this->getUser()->getId(), (int)$this->getUser()->getIdShopId()
    ];
    $var = $em->getConnection()->prepare('INSERT INTO s_product_location (name, domain_id, parent_categorie_id, quantity, webstatus, marque_id,
                                                                          description, price, largeur, longueur, tva,
                                                                          hauteur, poids, youtube, agent_user, id_magasin )
                                                                            VALUES (?, ?, ?, ?, ?, ?,
                                                                                    ?, ?, ?, ?, ?,
                                                                                    ?, ?, ?, ?, ?);');
    $var->execute($params);
    return new JsonResponse(["msg"=>"Ajouté avec succès", "status"=>1]);
  }

  /**
   * @Route("/my/put/products_location", name="product_location_put_add")
   */
  public function product_update(Request $request)
  {
    $data = json_decode($request->getContent(),true);
    $em = $this->getDoctrine();
    $params = [
      $data['name'], $data['quantity'] ?? null, $data['description'] ?? null, (int) $data['domain_id'] ?? null,
      $data['parent_categorie_id'] ?? null, $data['price'] ?? null, $data['priceweb'] ?? null, (float) $data['tva'] ?? 0,
      $data['largeur'] ?? null, $data['longueur'] ?? null, $data['poids'] ?? null, $data['hauteur'] ?? null,
      (int)$this->getUser()->getId(), (int)$this->getUser()->getIdShopId(), (int) $data['id']
    ];
    $var = $em->getConnection()->prepare('UPDATE s_product SET
            name = ?, alert_threshold = ?, product_desc = ?, product_categories_id = ?, parent_categorie_id = ?,
            price = ?, priceweb = ?, tva = ?,
            largeur = ?, longueur = ?, poids = ?, hauteur = ?,
                     agent_user = ?, id_magasin = ? WHERE id = ?;');
    $var->execute($params);
    return new JsonResponse(['status'=>1, 'msg'=>'Modifier avec succès']);
  }

  /**
   * @Route("/api/get/products_location", name="products_location_get_all")
   */
  public function product_location_get_all(Request $request)
  {
    $em = $this->getDoctrine();
    $var = $em->getConnection()->prepare("SELECT s_product_location.id, s_product_location.name, disabled,
                                reference, description, product_url_img, domain_id, agent_user, s_product_location.id_magasin,
                                quantity, price, price_day, price_week, price_month, tva, priceweb, color, webstatus, largeur, longueur, poids, hauteur, parent_categorie_id,  media_get(2, s_product_location.id) photos
                              FROM s_product_location
                              LEFT JOIN s_categorie_parent scp on scp.id = s_product_location.parent_categorie_id
                              LEFT JOIN s_categories_domain scd on scd.id = domain_id
                              WHERE disabled = 0 ORDER BY id DESC;");
    $var->execute();
    $res = $var->fetchAll();
    $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);

    return new JsonResponse($res);
  }

  /**
   * @Route("/api/get/products_location/{id}", name="products_location_shop")
   */
  public function product_location_shop($id)
  {
    $em = $this->getDoctrine();
    $var = $em->getConnection()->prepare("SELECT s_product_location.id, s_product_location.name, disabled,
                                reference, description, product_url_img, domain_id, agent_user, s_product_location.id_magasin,
                                quantity, price, price_day, price_week, price_month, tva, priceweb, color, webstatus, largeur, longueur, poids, hauteur, parent_categorie_id,  media_get(2, s_product_location.id) photos
                              FROM s_product_location
                              LEFT JOIN s_categorie_parent scp on scp.id = s_product_location.parent_categorie_id
                              LEFT JOIN s_categories_domain scd on scd.id = domain_id
                              WHERE disabled = 0 AND s_product_location.id_magasin = ? ORDER BY id DESC;");
    $var->execute([$id]);
    $res = $var->fetchAll();
    $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);

    return new JsonResponse($res);
  }

  /**
   * @Route("/api/get/products_location_one", name="products_location_get_one")
   */
  public function product_location_get_one(Request $request)
  {
    $id = $_GET['id'];
    $em = $this->getDoctrine();
    $var = $em->getConnection()->prepare("SELECT s_product_location.id, s_product_location.name, disabled,
                                reference, description, product_url_img, domain_id, agent_user, s_product_location.id_magasin,
                                quantity, price, price_day, price_week, price_month, tva, priceweb, color, webstatus, largeur, longueur, poids, hauteur, parent_categorie_id,  media_get(2, s_product_location.id) photos
                              FROM s_product_location
                              LEFT JOIN s_categorie_parent scp on scp.id = s_product_location.parent_categorie_id
                              LEFT JOIN s_categories_domain scd on scd.id = domain_id
                              WHERE s_product_location.id = ? AND disabled = 0 ORDER BY id DESC;");
    $var->execute([$id]);
    $res = $var->fetch();
    $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);

    return new JsonResponse($res);
  }


}

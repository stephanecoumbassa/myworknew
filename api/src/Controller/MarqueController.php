<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MarqueController extends AbstractController
{

  /**
   * @Route("/my/get/marques", name="marques_get")
   */
  public function marques_get(Request $request)
  {
    $em = $this->getDoctrine();
    $var = $em->getConnection()->prepare('SELECT * FROM s_marques WHERE s_marques.id_magasin = ?;');
    $res = $var->executeQuery([$this->getUser()->getShopId()])->fetchAll();
    $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);

    return new JsonResponse($res);
  }

  /**
   * @Route("/my/post/marques", name="marques_post")
   */
  public function marques_register(Request $request)
  {
    $data = json_decode($request->getContent(),true);
    $em = $this->getDoctrine();
    $shop_id = $this->getUser()->getShopId();

    $nom = $data['nom'];
    $photo = $data['photo'] ?? null;
    $description = $data['description'] ?? null;
    $var = $em->getConnection()->prepare('INSERT INTO s_marques (nom, photo, description, id_magasin) VALUES ( ?, ?, ?, ? );'
    );
    $var->executeQuery([$nom, $photo, $description, $this->getUser()->getShopId()]);
    // $res = $var->fetch();
    return new JsonResponse(["msg"=>"ajoute avec succès", "status"=> 1]);
  }

  /**
   * @Route("/my/put/marques", name="marques_put")
   */
  public function marques_update(Request $request)
  {
    $data = json_decode($request->getContent(),true);
    $em = $this->getDoctrine();
    $agent = $this->getUser()->getId();
    $shop_id = $this->getUser()->getShopId();

    $nom = $data['nom'];
    $photo = $data['photo'] ?? null;
    $description = $data['description'] ?? null;
    $id = $data['id'];
    $var = $em->getConnection()->prepare('UPDATE s_marques SET
              nom = ?, photo = ?, description = ? WHERE id_magasin = ? AND id = ?;'
    );
    $var->executeQuery([$nom, $photo, $description, $this->getUser()->getShopId(), $id]);
    // $res = $var->fetch();
    return new JsonResponse(["msg"=>"Modifié avec succès", "status"=> 1]);
  }

  /**
   * @Route("/my/delete/marques/{id}", name="marques_delete")
   */
  public function service_delete($id)
  {
    $em = $this->getDoctrine();
    $var = $em->getConnection()->prepare('DELETE FROM s_marques WHERE id = ? AND id_magasin = ?');
    $var->executeQuery([$id, $this->getUser()->getShopId()]);
    $var->closeCursor();
    return new JsonResponse(["msg"=>"Supprimé avec succès", "status"=> 1]);
  }

    /**
     * @Route("/api/get/marques", name="marques_get_all")
     */
    public function marques_get_all(Request $request)
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT * FROM s_marques WHERE s_marques.id_magasin = ?;');
        $res = $var->executeQuery([$this->getUser()->getShopId()])->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }

//  /**
//   * @Route("/my/get/marques_items", name="marques_item_get")
//   */
//  public function marques_items_get(Request $request)
//  {
//    $em = $this->getDoctrine();
//    $var = $em->getConnection()->prepare('SELECT * FROM s_marques_items WHERE id_magasin = ?;');
//    $var->executeQuery([$this->getUser()->getShopId()]);
//    $res = $var->fetchAll();
//    $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
//
//    return new JsonResponse($res);
//  }
//
//  /**
//   * @Route("/my/post/marques_items", name="marques_items_post")
//   */
//  public function marques_items_register(Request $request)
//  {
//    $data = json_decode($request->getContent(),true);
//    $em = $this->getDoctrine();
//    $agent = $this->getUser()->getId();
//    $shop_id = $this->getUser()->getShopId();
//
//    $name = $data['name'];
//    $description = $data['description'] ?? null;
//    $price_min = $data['price_min'];
//    $price_max = $data['price_max'] ?? null;
//    $domaind_id = $data['domaind_id'] ?? null;
//    $categorie_id = $data['categorie_id'] ?? null;
//
//    $var = $em->getConnection()->prepare('INSERT INTO s_marques_items
//              (name, description, price_min, price_max, domaind_id, categorie_id, user_id, id_magasin)
//              VALUES ( ?, ?, ?, ?, ?, ?, ?, ? );'
//    );
//    $var->executeQuery([$name,$description,$price_min,$price_max,$domaind_id,$categorie_id, $agent, $shop_id]);
//    // $res = $var->fetch();
//    return new JsonResponse(["msg"=>"ajoute avec succès", "status"=> 1]);
//  }
//
//  /**
//   * @Route("/my/put/marques_items", name="marques_items_put")
//   */
//  public function marques_items_update(Request $request)
//  {
//    $data = json_decode($request->getContent(),true);
//    $em = $this->getDoctrine();
//    $agent = $this->getUser()->getId();
//    $shop_id = $this->getUser()->getShopId();
//
//    $name = $data['name'];
//    $description = $data['description'] ?? null;
//    $price_min = $data['price_min'];
//    $price_max = $data['price_max'] ?? null;
//    $domaind_id = $data['domaind_id'] ?? null;
//    $categorie_id = $data['categorie_id'] ?? null;
//    $id = $data['id'];
//
//    $var = $em->getConnection()->prepare('UPDATE s_marques_items SET
//                name = ?, description = ?, price_min = ?, price_max = ?, domaind_id = ?, categorie_id = ?, user_id = ?
//                WHERE id_magasin = ? AND id = ?;'
//    );
//    $var->executeQuery([$name,$description,$price_min,$price_max,$domaind_id,$categorie_id, $agent, $shop_id, $id]);
//    // $res = $var->fetch();
//    return new JsonResponse(["msg"=>"Modifié avec succès", "status"=> 1]);
//  }

}

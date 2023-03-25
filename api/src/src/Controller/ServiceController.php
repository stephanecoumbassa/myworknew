<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ServiceController extends AbstractController
{

    /**
     * @Route("/my/get/services", name="my_services_get")
     */
    public function services_get(Request $request)
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT s_services.id, price, tva, code_comptable, s_services_items.name, service_item,
                                                s_clients.last_name, s_clients.name nom, s_clients.telephone_code, s_clients.telephone, s_clients.email, s_services.description, clientid, s_services.id_magasin, Date(date) date, versement1, versement2, versement3, versement4, versement5
                                                FROM s_services
                                                LEFT JOIN s_clients ON s_clients.id = s_services.clientid AND s_clients.id_magasin = ?
                                                LEFT JOIN s_services_items ON s_services_items.id = s_services.service_item AND s_services_items.id_magasin = ?
                                                WHERE s_services.id_magasin = ?;');
        $var->execute([$this->getUser()->getShopId(), $this->getUser()->getShopId(), $this->getUser()->getShopId()]);
        $res = $var->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);

        return new JsonResponse($res);
    }

    /**
     * @Route("/my/get/services_stats", name="my_services_stats_get")
     */
    public function services_statst_get(Request $request)
    {
        $em = $this->getDoctrine();
        // $params = [$_GET['first'], $_GET['last'], $this->getUser()->getShopId()];
        $var = $em->getConnection()->prepare('SELECT services_sum() services;');
        $var->execute();
        $res = $var->fetch();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);

        return new JsonResponse($res);
    }

    /**
     * @Route("/my/post/services", name="my_services_post")
     */
    public function services_register(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $em = $this->getDoctrine();
        $agent = $this->getUser()->getId();
        $shop_id = $this->getUser()->getShopId();

        $service_item = $data['service_item'];
        $name = $data['name'] ?? null;
        $description = $data['description'] ?? null;
        $price = $data['price'];
        $date = $data['date'] ?? null;
        $tva = $data['tva'] ?? 0;
        $code_comptable = $data['code_comptable'] ?? null;
        $client = $data['client'] ?? null;
        $clientid = $data['clientid'] ?? null;
        $telephone = $data['telephone'] ?? null;
        $email = $data['email'] ?? null;
        $var = $em->getConnection()->prepare('INSERT INTO s_services
            (service_item, name, price, tva, code_comptable, client, telephone, email, description, clientid, id_magasin)
            VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? );'
        );
        $var->execute([$service_item, $name, $price, $tva, $code_comptable, $client,
         $telephone, $email, $description, $clientid, $this->getUser()->getShopId()]);
        // $res = $var->fetch();
        return new JsonResponse(["msg"=>"ajoute avec succès", "status"=> 1]);
    }

  /**
   * @Route("/my/put/services", name="my_services_put")
   */
    public function services_update(Request $request)
    {
      $data = json_decode($request->getContent(),true);
      $em = $this->getDoctrine();
      $agent = $this->getUser()->getId();
      $shop_id = $this->getUser()->getShopId();

      $name = $data['name'];
      $description = $data['description'] ?? null;
      $price = $data['price'];
      $date = $data['date'] ?? null;
      $tva = $data['tva'] ?? null;
      $code_comptable = $data['code_comptable'] ?? null;
      $client = $data['client'] ?? null;
      $clientid = $data['clientid'] ?? null;
      $telephone = $data['telephone'] ?? null;
      $email = $data['email'] ?? null;
      $id = $data['id'];
      $var = $em->getConnection()->prepare('UPDATE s_services SET
              name = ?, price = ?, tva = ?, code_comptable = ?, client = ?, telephone = ?, email = ?, description = ?, clientid = ?
              WHERE id_magasin = ? AND id = ?;'
      );
      $var->execute([$name, $price, $tva, $code_comptable, $client,
        $telephone, $email, $description, $clientid, $this->getUser()->getShopId(), $id]);
      // $res = $var->fetch();
      return new JsonResponse(["msg"=>"Modifié avec succès", "status"=> 1]);
    }

    /**
     * @Route("/my/delete/services/{id}", name="my_services_delete")
     */
    public function service_delete($id)
    {
      $em = $this->getDoctrine();
      $var = $em->getConnection()->prepare('DELETE FROM s_services WHERE id = ? AND id_magasin = ?');
      $var->execute([$id, $this->getUser()->getShopId()]);
      $var->closeCursor();
      return new JsonResponse(["msg"=>"Supprimé avec succès", "status"=> 1]);
    }

    /**
     * @Route("/my/get/services_items", name="my_services_item_get")
     */
    public function services_items_get(Request $request)
    {
      $em = $this->getDoctrine();
      $var = $em->getConnection()->prepare('SELECT *, media_get(3, s_services_items.id) photos FROM s_services_items WHERE id_magasin = ?;');
      $var->execute([$this->getUser()->getShopId()]);
      $res = $var->fetchAll();
      $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);

      return new JsonResponse($res);
    }

    /**
     * @Route("/api/get/services_items/{id}", name="services_item_get")
     */
    public function services_itemsget($id)
    {
      $em = $this->getDoctrine();
      $var = $em->getConnection()->prepare('SELECT *, media_get(3, s_services_items.id) photos FROM s_services_items WHERE id_magasin = ?;');
      $var->execute([$id]);
      $res = $var->fetchAll();
      $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);

      return new JsonResponse($res);
    }

    /**
     * @Route("/api/all/services_items", name="services_item_all")
     */
    public function services_items_all()
    {
      $em = $this->getDoctrine();
      $var = $em->getConnection()->prepare('SELECT *, media_get(3, s_services_items.id) photos FROM s_services_items;');
      $var->execute();
      $res = $var->fetchAll();
      $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
      return new JsonResponse($res);
    }

    /**
     * @Route("/my/post/services_items", name="my_services_items_post")
     */
    public function services_items_register(Request $request)
    {
      $data = json_decode($request->getContent(),true);
      $em = $this->getDoctrine();
      $agent = $this->getUser()->getId();
      $shop_id = $this->getUser()->getShopId();

      $name = $data['name'];
      $description = $data['description'] ?? null;
      $price_min = $data['price_min'];
      $price_max = $data['price_max'] ?? null;
      $domaind_id = $data['domaind_id'] ?? null;
      $categorie_id = $data['categorie_id'] ?? null;

      $var = $em->getConnection()->prepare('INSERT INTO s_services_items
              (name, description, price_min, price_max, domaind_id, categorie_id, user_id, id_magasin)
              VALUES ( ?, ?, ?, ?, ?, ?, ?, ? );'
      );
      $var->execute([$name,$description,$price_min,$price_max,$domaind_id,$categorie_id, $agent, $shop_id]);
      // $res = $var->fetch();
      return new JsonResponse(["msg"=>"ajoute avec succès", "status"=> 1]);
    }

    /**
     * @Route("/my/put/services_items", name="my_services_items_put")
     */
    public function services_items_update(Request $request)
    {
      $data = json_decode($request->getContent(),true);
      $em = $this->getDoctrine();
      $agent = $this->getUser()->getId();
      $shop_id = $this->getUser()->getShopId();

      $name = $data['name'];
      $description = $data['description'] ?? null;
      $price_min = $data['price_min'];
      $price_max = $data['price_max'] ?? null;
      $domaind_id = $data['domaind_id'] ?? null;
      $categorie_id = $data['categorie_id'] ?? null;
      $id = $data['id'];

      $var = $em->getConnection()->prepare('UPDATE s_services_items SET
                name = ?, description = ?, price_min = ?, price_max = ?, domaind_id = ?, categorie_id = ?, user_id = ?
                WHERE id_magasin = ? AND id = ?;'
      );
      $var->execute([$name,$description,$price_min,$price_max,$domaind_id,$categorie_id, $agent, $shop_id, $id]);
      // $res = $var->fetch();
      return new JsonResponse(["msg"=>"Modifié avec succès", "status"=> 1]);
    }

}

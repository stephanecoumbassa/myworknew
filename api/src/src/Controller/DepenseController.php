<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DepenseController extends AbstractController
{

    /**
     * @Route("/my/get/depenses", name="depenses_get")
     */
    public function depenses_get(Request $request)
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT * FROM s_depenses WHERE id_magasin = ?;');
        $var->execute([$this->getUser()->getShopId()]);
        $res = $var->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);

        return new JsonResponse($res);
    }

    /**
     * @Route("/my/get/depenses_stats", name="depenses_stats_get")
     */
    public function depenses_statst_get(Request $request)
    {
        $em = $this->getDoctrine();
        // $params = [$_GET['first'], $_GET['last'], $this->getUser()->getShopId()];
        $var = $em->getConnection()->prepare('SELECT depense_sum() depenses;');
        $var->execute();
        $res = $var->fetch();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);

        return new JsonResponse($res);
    }

    /**
     * @Route("/my/post/depenses", name="depenses_post")
     */
    public function depenses_register(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $em = $this->getDoctrine();
        $agent = $this->getUser()->getId();
        $shop_id = $this->getUser()->getShopId();
 
        $name = $data['name'];
        $description = $data['description'] ?? null;
        $price = $data['price'];
        $tva = $data['tva'] ?? null;
        $code_comptable = $data['code_comptable'] ?? null;
        $client = $data['client'] ?? null;
        $telephone = $data['telephone'] ?? null;
        $email = $data['email'] ?? null;
        $date = $data['date'] ?? null;
        $var = $em->getConnection()->prepare('INSERT INTO s_depenses 
            (name, price, tva, code_comptable, client, telephone, email, description, clientid, date, id_magasin)
            VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? );'
        );
        $var->execute([$name, $price, $tva, $code_comptable, $client,
         $telephone, $email, $description, NULL, $date, $this->getUser()->getShopId()]);
        // $res = $var->fetch();
        
        return new JsonResponse(["msg"=>"ajoute avec succès", "status"=> 1]);
    }

}

<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandClientController extends AbstractController
{

    /**
     * @Route("/api/post/user_command", name="user_command_post")
     */
    public function commands_user(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $em = $this->getDoctrine();
        $shopid = $data['shopid'] ?? 1;
        $telephone = $data['telephone'] ?? null;
        $userid = $data['user'] ?? 1;
        $email = $data['email'] ?? null;
        $name = $data['name'] ?? null;
        $cmdeid = 'cmd_'.$shopid.'_'.uniqid();
        $products = $data['products'];

        $count = count($products);
        for ($i=0; $i<$count; $i++){
            $pid = $products[$i]['id'];
            $qty = $products[$i]['qty'] ?? $products[$i]['cartQuantity'];
            $type = $products[$i]['type'] ?? null;
            $isCustomize = $products[$i]['isCustomize'] ?? 0;
            $konva = json_encode($products[$i]['konva']??null, true) ?? null;
            $konva==='null' ? $konva=null : null;
//            $var = $em->getConnection()->prepare('CALL __cmde_client_register(?, ?, ? ,?, ?, ?, ?, ?);');
            $var = $em->getConnection()->prepare('insert into s_cmde_product
                (product_id, quantity, user_id, id_cmde, id_magasin, email, telephone, client_name, type, isCustomize, konva)
                values (?, ?, ? ,?, ?, ?, ?, ?, ?, ?, ?)');
            $var->execute([$pid, $qty, $userid, $cmdeid, $shopid, $telephone, $email, $name, $type, $isCustomize, $konva]);
//            $res = $var->fetch();
        }
        return new JsonResponse(["msg"=>"Votre commandé a été validé", "status" => 1]);
    }

    /**
     * @Route("/api/get/command_client/{clientid}", name="command_client_get")
     */
    public function command_client_get($clientid)
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare("SELECT s_cmde_product.id, product_id, quantity, price, user_id,
                    date_posted, status, id_cmde,
                    concat('https://affairez.com/apistock/public/shop/',s_product.id_magasin,'/product/', media_name(1, s_product.id)) imageurl,
                    s_cmde_product.id_magasin, email, telephone, client_name, name,
                    DATE_FORMAT(date_posted,'%d/%m/%Y %H:%i') datefr
            FROM s_cmde_product
            LEFT JOIN s_product ON s_product.id = s_cmde_product.product_id
            where user_id = ? ORDER BY date_posted DESC ;");
        $var->execute([$clientid]);
        $res = $var->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }

    /**
     * @Route("/my/get/user_command", name="command_lisst_get")
     */
    public function command_get(Request $request)
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare("SELECT s_cmde_product.id, product_id, quantity, user_id, date_posted, status, id_cmde,
                    s_cmde_product.id_magasin, email, telephone, client_name, name, DATE_FORMAT(date_posted,'%d/%m/%Y %H:%i') datefr FROM s_cmde_product
            LEFT JOIN s_product ON s_product.id = s_cmde_product.product_id
            where s_cmde_product.id_magasin = ? ORDER BY date_posted DESC ;");
        $var->execute([$this->getUser()->getShopId()]);
        $res = $var->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }

    /**
     * @Route("/my/count/user_command", name="command_count_get")
     */
    public function command_count(Request $request)
    {
      $em = $this->getDoctrine();
      $var = $em->getConnection()->prepare("SELECT count(s_cmde_product.id) count FROM s_cmde_product
              where s_cmde_product.id_magasin = ? AND status = 1;");
      $var->execute([$this->getUser()->getShopId()]);
      $res = $var->fetch();
      $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
      return new JsonResponse($res);
    }

    /**
     * @Route("/my/accept/user_command", name="user_command_accept")
     */
    public function command_accept(Request $request)
    {

        $data = json_decode($request->getContent(), true);
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare("UPDATE s_cmde_product SET status = 2 where id = ? AND id_magasin = ?;");
        $var->execute([(int) $data['id'], $this->getUser()->getShopId()]);
        return new JsonResponse(['status'=> 1, 'msg'=> 'la commande à été validée']);
    }

    /**
     * @Route("/my/cancel/user_command", name="user_command_cancel")
     */
    public function command_cancel(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare("UPDATE s_cmde_product SET status = 0 where id = ? AND s_cmde_product.id_magasin = ?;");
        $var->execute([(int) $data['id'], $this->getUser()->getShopId()]);
        return new JsonResponse(['status'=> 1, 'msg'=> 'la commande à été annulée']);
    }

}

<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;


class ReviewController extends BaseController {

    /**
     * @Route("/api/get/review", name="review_get")
     */
    public function shop_get(Request $request)
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare("SELECT *, DATE_FORMAT(dateposted,'%d/%m/%Y %H:%i') datefr FROM s_review WHERE product_id = ?");
        $var->execute([$_GET['product_id']]);
        $res = $var->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);

        return new JsonResponse($res);
    }

    /**
     * @Route("/api/get/review/{product_id}", name="review_product_get")
     */
    public function review_product_get($product_id)
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare("SELECT *, DATE_FORMAT(dateposted,'%d/%m/%Y %H:%i') datefr FROM s_review WHERE product_id = ? ORDER BY id DESC");
        $var->execute([$product_id]);
        $res = $var->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }

    /**
     * @Route("/api/client/review/{client_id}", name="review_client_get")
     */
    public function review_client_get($client_id)
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare("SELECT s_review.id, product_id, client_id, s_review.name, note, email, description, dateposted,
            s_product.name product_name, DATE_FORMAT(dateposted,'%d/%m/%Y %H:%i') datefr,
            concat('$this->uploadShop', s_product.id_magasin, '/product/', media_name(1, s_product.id)) imageurl
            FROM s_review 
            left join s_product on s_product.id = product_id
            WHERE client_id = ? ORDER BY id DESC");
        $var->execute([$client_id]);
        $res = $var->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }


    /**
     * @Route("/api/post/review", name="review_post")
     */
    public function review_post(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $params = [ $data['client_id'], $data['product_id'], $data['note'], $data['email']??null, $data['description'], $data['name']??null];

        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare("INSERT INTO s_review (client_id, product_id, note, email, description, name) VALUES (?, ?, ?, ?, ?, ?);");
        $var->execute($params);

        $var2 = $em->getConnection()->prepare("UPDATE s_product_stat SET note=(note+?)/2 WHERE product_id = ?;");
        $var2->execute([$data['note'], $data['product_id']]);
        // $res = $var->fetch();
        return new JsonResponse(['status'=>1, 'msg'=> 'ajouté avec succès']);
    }


//  /**
//   * @Route("/api/get/review/{id}", name="review_get_id")
//   */
//  public function shop_get_id($id)
//  {
//    $em = $this->getDoctrine();
//    $var = $em->getConnection()->prepare('SELECT * FROM magasin WHERE id = ?');
//    $var->execute([$id]);
//    $res = $var->fetch();
//    $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
//    return new JsonResponse($res);
//  }
//
//  /**
//   * @Route("/api/get/review", name="review_gett_id")
//   */
//  public function shop_get_idd(Request $request)
//  {
//    $idmagasin = $request->headers->get('shopid');
//    $em = $this->getDoctrine();
//    $var = $em->getConnection()->prepare('SELECT * FROM magasin WHERE id = ?');
//    $var->execute([$idmagasin]);
//    $res = $var->fetch();
//    $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
//    return new JsonResponse($res);
//  }
//
//  /**
//   * @Route("/api/post/shop", name="shop_post")
//   */
//  public function shop_register(Request $request, UserPasswordEncoderInterface $encoder)
//  {
//
//    $data = json_decode($request->getContent(),true);
//    $params = [ $data['name'], $data['email']];
//    $em = $this->getDoctrine();
//    $var = $em->getConnection()->prepare("CALL __magasin_register2(? ,?) ");
//    $var->execute($params);
//    $res = $var->fetch();
//
//    $user = new SUsers();
//    $user->setEmail($data['email']);
//    $user->setRoles(1); $user->setPassword($encoder->encodePassword($user, $data['password']  ) );
//
//    $params = [ 'admin', 'admin', $data['telephone_code'] ?? 225, $data['telephone'] ?? '',
//      $data['email'], 1, $user->getPassword(), 1, $res['id']
//    ];
//
//    $em = $this->getDoctrine();
//    $var = $em->getConnection()->prepare('CALL __user_register(?, ?, ?, ?, ?, ?, ?, ?, ?)');
//    $var->execute($params);
////    $res = $var->fetchAll();
//
//    $folder = $_SERVER['DOCUMENT_ROOT'].'/apistock/public/';
//    $filesystem = new Filesystem();
//    $filesystem->remove($folder.'shop/'.$res['id']);
//
//    mkdir($folder.'shop/'.$res['id'], 0755);
//    mkdir($folder.'shop/'.$res['id'].'/product', 0755);
//    mkdir($folder.'shop/'.$res['id'].'/magasin', 0755);
//    mkdir($folder.'shop/'.$res['id'].'/user', 0755);
//    mkdir($folder.'shop/'.$res['id'].'/doc', 0755);
//
//    return new JsonResponse(['status'=>1, 'msg'=> 'Ajoute avec succès']);
//
//  }

}

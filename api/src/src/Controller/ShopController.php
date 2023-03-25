<?php

namespace App\Controller;

use App\Entity\SUsers;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Filesystem\Filesystem;

class ShopController extends BaseController
{

    /**
     * @Route("/my/get/shop", name="shop_get")
     */
    public function shop_get(Request $request)
    {
        $shop_id = $this->getUser()->getShopId();
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT * FROM magasin WHERE id = ?');
        $res = $var->executeQuery([$shop_id])->fetch();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);

        return new JsonResponse($res);
    }

    /**
     * @Route("/api/get/shop_list", name="shop_list_get")
     */
    public function shop_list(Request $request)
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare("SELECT *,
            concat('$this->uploadShop',magasin.id,'/magasin/', logo) logourl
        FROM magasin ORDER BY id DESC");
        $res = $var->executeQuery()->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }

    /**
     * @Route("/my/get/shop_stats", name="shop_stats_get")
     */
    public function shop_stats_get(Request $request)
    {
        $year = date('Y');
        $shop_id = $this->getUser()->getShopId();
        $em = $this->getDoctrine();

        $var2 = $em->getConnection()->prepare("SELECT montant, mois FROM budgetrevenu WHERE annee = ? ORDER BY mois");
        $res2 = $var2->executeQuery([$year])->fetchAll();

//        $var = $em->getConnection()->prepare("SELECT appro_sum(?, $year) appro_sum, appro_count(?) appro_count, vente_jour(?)	vente_jour,
//        vente_sum(?, 2023) vente_sum,
//         __sales_sum_monthly(?, 2023) vente_credit_sum, __appro_sum_monthly(?, 2023) appro_credit_sum,
//          vente_count(?) vente_count, depense_sum(?) depense_sum, service_sum(?) service_sum,
//        users_count(?) users_count, clients_count(?) clients_count, fournisseurs_count(?) fournisseurs_count ;");
        $var = $em->getConnection()
            ->prepare("SELECT appro_sum(?, $year) appro_sum, appro_count(?) appro_count, vente_jour(?) vente_jour,
        vente_sum(?, 2023) vente_sum, __sales_sum_monthly(?, 2023) vente_credit_sum, __appro_sum_monthly(?, 2023) appro_credit_sum,
          vente_count(?) vente_count, depense_sum(?) depense_sum, service_sum(?) service_sum,
        users_count(?) users_count, clients_count(?) clients_count, fournisseurs_count(?) fournisseurs_count ;");
        $res = $var->executeQuery([$shop_id, $shop_id, $shop_id, $shop_id,
//                                    $shop_id, $shop_id, $shop_id, $shop_id, $shop_id, $shop_id,
                                    $shop_id, $shop_id, $shop_id, $shop_id, $shop_id, $shop_id,
                                    $shop_id, $shop_id])->fetch();
        foreach ($res as $key => $value) {
            $res[$key] = json_decode($value, true);
        }

        $result = [];
        foreach ($res2 as $item) {
            $result[$item['mois']] = $item['montant'];
        }

        $res['budgetrevenu'] = $result;
        return new JsonResponse($res);
    }

    /**
     * @Route("/my/put/shop", name="shop_put")
     */
    public function shop_update(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $shop_id = $this->getUser()->getShopId();
        $params = [ $data['name'], $data['slogan']??null, $data['telephone']??null, $data['email']??null,
            $data['site']??null, $data['facebook']??null, $data['youtube']??null,
            $data['adresse']??null, $data['tva']??null, $data['theme']??null, $data['color']??null,
            $data['cc']??null, $data['rc']??null, $data['capital']??null, $data['sigle']??null,
            $data['longitude']??null, $data['latitude']??null, $data['city']??null,
            $data['footer_facture']??null, $data['footer_site']??null, $data['footer_livraison']??null,
            $data['footer_paiement']??null, $data['footer_faq']??null,
            $data['home_livraison']??null, $data['home_payer']??null, $data['home_jour']??null,
            $data['home_ouverture']??null, $data['home_newletter']??null,
            $shop_id ];

        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare("UPDATE magasin SET
        name = ?, slogan = ?, telephone = ?, email = ?, site = ? , facebook = ?, youtube = ?,
        adresse = ?, tva = ?, theme = ?, color = ?, cc = ?, rc = ?, capital = ?, sigle = ?,
        longitude = ?, latitude = ?, city = ?,
        footer_facture = ?, footer_site = ?, footer_livraison = ?,
        footer_paiement = ?, footer_faq = ?,
        home_livraison = ?, home_payer = ?, home_jour = ?,
        home_ouverture = ?, home_newletter = ?
    WHERE id = ? ");
        $var->executeQuery($params);
        // $res = $var->fetch();
        return new JsonResponse(['status'=>1, 'msg'=> 'modifie avec succès']);
    }

    /**
     * @Route("/my/put/shop_logo", name="shop_put_logo")
     */
    public function shop_logo(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $shop_id = $this->getUser()->getShopId();

        if( is_array($data['logo']) && isset($shop_id ) ){
            $this->image_upload( $data['logo'], "/$shop_id/magasin", uniqid().'_logo_'.$shop_id , $shop_id ,
                "UPDATE magasin SET logo = ? WHERE id = ?;");
        }

        return new JsonResponse(['status'=>1, 'msg'=> 'modifie avec succès']);
    }

    /**
     * @Route("/my/put/shop_slider1", name="shop_put_slider1")
     */
    public function shop_slider1(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $shop_id = $this->getUser()->getShopId();

        if( is_array($data['slider1']) && isset($shop_id ) ){
            $this->image_upload( $data['slider1'], "/$shop_id/magasin", uniqid().'_slider_'.$shop_id , $shop_id ,
                "UPDATE magasin SET slider1 = ? WHERE id = ?;");
        }

        return new JsonResponse(['status'=>1, 'msg'=> 'modifie avec succès']);
    }

    /**
     * @Route("/my/put/shop_slider2", name="shop_put_slider2")
     */
    public function shop_slider2(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $shop_id = $this->getUser()->getShopId();

        if( is_array($data['slider2']) && isset($shop_id ) ){
            $this->image_upload( $data['slider2'], "/$shop_id/magasin", uniqid().'_slider_'.$shop_id , $shop_id ,
                "UPDATE magasin SET slider2 = ? WHERE id = ?;");
        }

        return new JsonResponse(['status'=>1, 'msg'=> 'modifie avec succès']);
    }


    /**
     * @Route("/my/put/shop_slider3", name="shop_put_slider3")
     */
    public function shop_slider3(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $shop_id = $this->getUser()->getShopId();

        if( is_array($data['slider3']) && isset($shop_id ) ){
            $this->image_upload( $data['slider3'], "/$shop_id/magasin", uniqid().'_slider_'.$shop_id , $shop_id ,
                "UPDATE magasin SET slider3 = ? WHERE id = ?;");
        }

        return new JsonResponse(['status'=>1, 'msg'=> 'modifie avec succès']);
    }


    /**
     * @Route("/api/get/shop/{id}", name="shop_get_id")
     */
    public function shop_get_id($id)
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare("SELECT *,
                   concat('$this->uploadShop',magasin.id,'/magasin/', logo) logourl
                FROM magasin WHERE id = ?");
        $res = $var->executeQuery([$id])->fetch();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }


    /**
     * @Route("/api/get/shop", name="shop_gett_id")
     */
    public function shop_get_idd(Request $request)
    {
        $idmagasin = $request->headers->get('shopid');
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT * FROM magasin WHERE id = ?');
        $res = $var->executeQuery([$idmagasin])->fetch();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }

    /**
     * @Route("/api/post/shop", name="shop_post")
     */
    public function shop_register(Request $request, UserPasswordEncoderInterface $encoder)
    {

        $data = json_decode($request->getContent(),true);
        $params = [ $data['name'], $data['email']];
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare("CALL __magasin_register2(? ,?) ");
        $res = $var->executeQuery($params)->fetch();

        $user = new SUsers();
        $user->setEmail($data['email']);
        $user->setRoles(1); $user->setPassword($encoder->encodePassword($user, $data['password']  ) );

        $params = [ 'admin', 'admin', $data['telephone_code'] ?? 225, $data['telephone'] ?? '',
            $data['email'], 1, $user->getPassword(), 1, $res['id']
        ];

        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('CALL __user_register(?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $var->executeQuery($params);
//    $res = $var->fetchAll();

        $folder = $_SERVER['DOCUMENT_ROOT'].'/apistock/public/';
        $filesystem = new Filesystem();
        $filesystem->remove($folder.'shop/'.$res['id']);

        mkdir($folder.'shop/'.$res['id'], 0755);
        mkdir($folder.'shop/'.$res['id'].'/product', 0755);
        mkdir($folder.'shop/'.$res['id'].'/magasin', 0755);
        mkdir($folder.'shop/'.$res['id'].'/user', 0755);
        mkdir($folder.'shop/'.$res['id'].'/doc', 0755);

        return new JsonResponse(['status'=>1, 'msg'=> 'Ajoute avec succès']);

    }

}

<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends BaseController
{

    use JwtTrait;

    /**
     * @Route("/my/get/products", name="products_get")
     */
    public function appro_get(Request $request)
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare("SELECT concat(s_product.name, ' ', ifnull(scp.name, ''), ' ', ifnull(sma.nom, '')) prodcat, s_product.id, s_product.name, s_product.name title, disabled,
                                reference, alert_threshold, product_desc, product_desc description, product_url_img, product_categories_id, spc.name categorie, agent_user, 0 discount,
                                amount, price, sales_price, buy_price, tva, priceweb, color, webstatus, largeur, longueur, poids, hauteur, parent_categorie_id, scp.name parent_categorie_name,
                                        scp.domainid, scd.name domainname, __product_qty(s_product.id_magasin, s_product.id) reste, 0 def,
                                sma.nom marque, sma.photo marque_photo, customize,
                                media_get(1, s_product.id) photos,
                                media_name(1, s_product.id) image,
                                concat('$this->uploadShop',s_product.id_magasin,'/product/', media_name(1, s_product.id)) imageurl
                              FROM s_product
                              LEFT JOIN s_product_categories spc on s_product.product_categories_id = spc.id
                              LEFT JOIN s_categorie_parent scp on scp.id = s_product.parent_categorie_id
                              LEFT JOIN s_categories_domain scd on scp.domainid = scd.id
                              LEFT JOIN s_marques sma on sma.id = marque_id
                              WHERE s_product.id_magasin = ? AND disabled= 0 ORDER BY id DESC;");
        $res = $var->executeQuery([$this->getUser()->getIdMagasin()])->fetchAllAssociative();
//        $res = $var;
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);

        return new JsonResponse($res);
    }

    /**
     * @Route("/my/resume/products/{datee}", name="products_resume_get")
     */
    public function products_get($datee)
    {
        $datee = strip_tags(htmlspecialchars($datee));
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare("SELECT s_product.id, s_product.name, disabled, reference, DATE('{$datee}' + INTERVAL (6) DAY) datee, media_get(1, s_product.id) photos, __product_stock_date(s_product.id_magasin, s_product.id, '2022-11-01') stock,
                                                (select ifnull(sum(quantite_vendu), 0) from s_ventes where product_id = s_product.id and date(date_posted) = DATE('{$datee}') ) v1,
                                                (select ifnull(sum(s_aprovisionements.amount), 0) from s_aprovisionements where product_id = s_product.id and date(dateposted) = DATE('{$datee}' ) ) a1,
                                                (select ifnull(sum(quantite_vendu), 0) from s_ventes where product_id = s_product.id and date(date_posted) = DATE('{$datee}' + INTERVAL (1) DAY) ) v2,
                                                (select ifnull(sum(s_aprovisionements.amount), 0) from s_aprovisionements where product_id = s_product.id and date(dateposted) = DATE('{$datee}' + INTERVAL (1) DAY) ) a2,
                                                (select ifnull(sum(quantite_vendu), 0) from s_ventes where product_id = s_product.id and date(date_posted) = DATE('{$datee}' + INTERVAL (2) DAY) ) v3,
                                                (select ifnull(sum(s_aprovisionements.amount), 0) from s_aprovisionements where product_id = s_product.id and date(dateposted) = DATE('{$datee}' + INTERVAL (2) DAY) ) a3,
                                                (select ifnull(sum(quantite_vendu), 0) from s_ventes where product_id = s_product.id and date(date_posted) = DATE('{$datee}' + INTERVAL (3) DAY) ) v4,
                                                (select ifnull(sum(s_aprovisionements.amount), 0) from s_aprovisionements where product_id = s_product.id and date(dateposted) = DATE('{$datee}' + INTERVAL (3) DAY) ) a4,
                                                (select ifnull(sum(quantite_vendu), 0) from s_ventes where product_id = s_product.id and date(date_posted) = DATE('{$datee}' + INTERVAL (4) DAY) ) v5,
                                                (select ifnull(sum(s_aprovisionements.amount), 0) from s_aprovisionements where product_id = s_product.id and date(dateposted) = DATE('{$datee}' + INTERVAL (4) DAY) ) a5,
                                                (select ifnull(sum(quantite_vendu), 0) from s_ventes where product_id = s_product.id and date(date_posted) = DATE('{$datee}' + INTERVAL (5) DAY) ) v6,
                                                (select ifnull(sum(s_aprovisionements.amount), 0) from s_aprovisionements where product_id = s_product.id and date(dateposted) = DATE('{$datee}' + INTERVAL (5) DAY) ) a6,
                                                (select ifnull(sum(quantite_vendu), 0) from s_ventes where product_id = s_product.id and date(date_posted) = DATE('{$datee}' + INTERVAL (6) DAY) ) v7,
                                                (select ifnull(sum(s_aprovisionements.amount), 0) from s_aprovisionements where product_id = s_product.id and date(dateposted) = DATE('{$datee}' + INTERVAL (5) DAY) ) a7
                                              FROM s_product WHERE s_product.id_magasin = ? AND disabled= 0 ORDER BY id DESC;");
        $res = $var->executeQuery([$this->getUser()->getIdMagasin()])->fetchAllAssociative();
//        $res = $var;
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);

        return new JsonResponse($res);
    }

    /**
     * @Route("/my/get/products-by-categories/{id}", name="products_categories_get")
     */
    public function appro_categories_get($id)
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare("SELECT *,
            s_product.name title, 0 discount, media_get(1, s_product.id) photos, media_name(1, s_product.id) image,
            concat('https://affairez.com/apistock/public/shop/',s_product.id_magasin,'/product/', media_name(1, s_product.id)) imageurl
            FROM s_product
        WHERE product_categories_id = ? AND id_magasin = ? AND disabled = 0 ORDER BY name");
        $res = $var->executeQuery([$id, $this->getUser()->getIdMagasin()])->fetchAllAssociative();
//        $res = $var;
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }

    /**
     * @Route("/my/disabled/products/{productid}", name="product_post_disabled")
     */
    public function product_disabled(Request $request, $productid)
    {
        $data = json_decode($request->getContent(),true);
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('UPDATE s_product SET disabled = 1 WHERE id = ? AND id_magasin = ?');
        $var->executeQuery([$productid, $this->getUser()->getShopId()]);
        return new JsonResponse(['status' =>1, 'msg' => 'Désactivé avec succès']);
    }

    /**
     * @Route("/api/post/products", name="product_post_api_add")
     */
    public function product_post(Request $request)
    {
        $data = json_decode($request->getContent(), true) ?? $_POST;
        return new JsonResponse($data);
    }

    /**
     * @Route("/my/post/products", name="product_post_add")
     */
    public function product_register(Request $request)
    {
        $data = json_decode($request->getContent(),true) ?? $_POST;
//        return new JsonResponse($data);
        $em = $this->getDoctrine();
        $params = [
            htmlspecialchars($data['name']), $data['product_categories_id'] ?? null, $data['parent_categorie_id'] ?? 1, $data['alert_threshold'] ?? 0, $data['webstatus'] ?? 1,
            $data['description'] ?? null, null, $data['price'] ?? 0, $data['buy_price'] ?? 0, $data['largeur'] ?? 0, $data['longueur'] ?? 0,
            $data['hauteur'] ?? 0, $data['poids'] ?? 0, $data['youtube'] ?? null, $data['marque_id'] ?? null, (int)$this->getUser()->getId(), (int)$this->getUser()->getIdShopId()
        ];
        $var = $em->getConnection()->prepare('CALL __product_register7(?, ?, ? ,?, ?,
                                                                   ?, ?, ?, ?, ?, ?,
                                                                   ?, ?, ?, ?, ?, ?);');
        $res = $var->executeQuery($params)->fetch();
        $id_facture = 'A-'.date("ymd-his", time());

        if( isset($data['stock']) && !empty($data['stock']) ) {
            $var = $em->getConnection()->prepare
            ('CALL __ap_register_prix_moyen(?, ?, ? ,?, ?, ?, ?, ?, ?);');
            $var->executeQuery( [ $res['id'], $this->getUser()->getId(), 1, $data['stock'],
                $data['buy_price'] ?? 0, $data['price'] ?? 0, $data['tva'] ?? 0, $this->getUser()->getShopId(), $id_facture ]);
            //------------------------------------------------------------------------------------------------------
            $var = $em->getConnection()->prepare('INSERT INTO s_credit (montant, type, factureid, date, id_magasin)
            VALUES (?, ?, ?, NOW(), ?);');
            $var->executeQuery([ (int)$data['buy_price'] * (int)$data['stock'], 'achat', $id_facture, $this->getUser()->getShopId()]);
        }

        return new JsonResponse($res);
    }

    /**
     * @Route("/my/put/products", name="product_put_add")
     */
    public function product_update(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $em = $this->getDoctrine();
        $params = [
            htmlspecialchars($data['name']), $data['alert_threshold'] ?? null, $data['description'] ?? null, (int) $data['product_categories_id'] ?? null,
            $data['parent_categorie_id'] ?? null, $data['price'] ?? 0, $data['priceweb'] ?? null, (float) $data['tva'] ?? 0,
            $data['largeur'] ?? null, $data['longueur'] ?? null, $data['poids'] ?? null, $data['hauteur'] ?? null,
            $data['youtube'] ?? null, $data['marque_id'] ?? null, $data['customize'] ?? 0,
            (int)$this->getUser()->getId(), (int)$this->getUser()->getIdShopId(), (int) $data['id']
        ];
        $var = $em->getConnection()->prepare('UPDATE s_product SET
            name = ?, alert_threshold = ?, product_desc = ?, product_categories_id = ?, parent_categorie_id = ?,
            price = ?, priceweb = ?, tva = ?,
            largeur = ?, longueur = ?, poids = ?, hauteur = ?, youtube = ?, marque_id = ?, customize = ?,
                     agent_user = ?, id_magasin = ? WHERE id = ?;');
        $var->executeQuery($params);
        return new JsonResponse(['status'=>1, 'msg'=>'Modifier avec succès']);
    }

    /**
     * @Route("/my/post/qr_code", name="qrcode_post_add")
     */
    public function qr_code(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $em = $this->getDoctrine();
        $params = [
            $data['file'],
            (int) $data['typerubrique'],
            (int) $data['id']
        ];
        $var = $em->getConnection()->prepare('INSERT INTO qr_code (file, typerubrique, id_ligne) VALUES (?, ?, ?)');
        $var->executeQuery($params);
        return new JsonResponse(['msg' => 'ajouté avec succès']);
    }

    /**
     * @Route("/my/get/products_stats/{id}", name="products_stats_get")
     */
    public function products_stats_get($id)
    {
        $shop_id = $this->getUser()->getShopId();
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT
        vente_product_sum(?, ?) vente_sum, appro_product_sum(?, ?) appro_sum ;');

        $res = $var->executeQuery([$shop_id, $id, $shop_id, $id])->fetch();
//        $var->executeQuery();
//        $res = $var->fetch();

        return new JsonResponse($res);
    }


    /**
     * @Route("/api/get/products", name="product_get")
     */
    public function product_get(Request $request)
    {
//        $idmagasin = $request->headers->get('shopid');
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare("SELECT concat(s_product.name, ' ', ifnull(scp.name, ''), ' ', ifnull(sma.nom, '')) prodcat, s_product.id, s_product.name, s_product.name title, 0 discount, disabled,
                                        reference, alert_threshold, product_desc, product_desc description, product_url_img, product_categories_id, spc.name categorie, agent_user,
                                        amount, price, sales_price, buy_price, tva, priceweb, color, webstatus, largeur, longueur, poids, hauteur, parent_categorie_id, scp.name parent_categorie_name,
                                        scp.domainid, scd.name domainname, __product_qty(s_product.id_magasin, s_product.id) reste, 0 def,
                                        sma.nom marque, sma.photo marque_photo, s_product.id_magasin, media_get(1, s_product.id) photos,
                                        media_get(1, s_product.id) images,
                                        media_name(1, s_product.id) image, customize,
                                        concat('https://affairez.com/apistock/public/shop/',s_product.id_magasin,'/product/', media_name(1, s_product.id)) imageurl
                                FROM s_product
                                LEFT JOIN s_product_categories spc on s_product.product_categories_id = spc.id
                                LEFT JOIN s_categorie_parent scp on scp.id = s_product.parent_categorie_id
                                LEFT JOIN s_categories_domain scd on scp.domainid = scd.id
                                LEFT JOIN s_marques sma on sma.id = marque_id
                                WHERE disabled= 0 ORDER BY id DESC");
        $res = $var->executeQuery()->fetchAll();
        $res = json_decode(json_encode($res));
        return new JsonResponse($res);
    }

    /**
     * @Route("/api/get/products/{id}", name="product_shop_get")
     */
    public function product_by_shop($id)
    {
        $idmagasin = $id;
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare("SELECT concat(s_product.name, ' ', ifnull(scp.name, ''), ' ', ifnull(sma.nom, '')) prodcat, s_product.id, s_product.name, s_product.name title, 0 discount, disabled,
                                        reference, alert_threshold, product_desc, product_desc description, product_url_img, product_categories_id, spc.name categorie, agent_user,
                                        amount, price, sales_price, buy_price, tva, priceweb, color, webstatus, largeur, longueur, poids, hauteur, parent_categorie_id, scp.name parent_categorie_name,
                                        scp.domainid, scd.name domainname, __product_qty(s_product.id_magasin, s_product.id) reste, 0 def, customize,
                                        sma.nom marque, sma.photo marque_photo, s_product.id_magasin, media_get(1, s_product.id) photos,
                                        media_get(1, s_product.id) images,
                                        media_name(1, s_product.id) image,
                                        concat('https://affairez.com/apistock/public/shop/',s_product.id_magasin,'/product/', media_name(1, s_product.id)) imageurl
                                FROM s_product
                                LEFT JOIN s_product_categories spc on s_product.product_categories_id = spc.id
                                LEFT JOIN s_categorie_parent scp on scp.id = s_product.parent_categorie_id
                                LEFT JOIN s_categories_domain scd on scp.domainid = scd.id
                                LEFT JOIN s_marques sma on sma.id = marque_id
                                WHERE s_product.id_magasin = ? AND disabled = 0 ORDER BY id DESC");
        $res = $var->executeQuery([$idmagasin])->fetchAllAssociative();

        return new JsonResponse($res);
    }

    /**
     * @Route("/api/search/products", name="products_search_get")
     */
    public function products_search(Request $request)
    {
        $name = "%". $_GET['q']."%" ?? '';
        $shop_id = $request->headers->get('shopid');
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare("SELECT *, media_get(1, s_product.id) photos, media_name(1, s_product.id) image,
                                                        concat('https://affairez.com/apistock/public/shop/',s_product.id_magasin,'/product/', media_name(1, s_product.id)) imageurl
                                                        FROM s_product
                                                        WHERE MATCH(name, product_desc) AGAINST(?) AND disabled = 0;
                                             ");
        $res = $var->executeQuery([$name.'*'])->fetchAllAssociative();
//        $res = $var;
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }

    /**
     * @Route("/api/search/products_shop/{id}", name="products_shop_search_get")
     */
    public function products_shop_search($id)
    {
        $name = "%". $_GET['q']."%" ?? '';
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare("SELECT *, media_get(1, s_product.id) photos, media_name(1, s_product.id) image FROM s_product
                                              WHERE MATCH(name, product_desc) AGAINST(?) AND id_magasin = ? AND disabled = 0;
                                             ");
        $res = $var->executeQuery([$name.'*', $id])->fetchAll();
//        $res = $var->fetchAllAssociative();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }

    /**
     * @Route("/api/get/products_one", name="product_get_one")
     */
    public function product_get_one(Request $request)
    {
        $id = $_GET['id'];
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('CALL __product_get_one(?);');
        $res = $var->executeQuery([$id])->fetch();
//        $res = $var->fetch();
        return new JsonResponse($res);
    }

    /**
     * @Route("/api/get/products-by-categories/{id}", name="products_categories_api_get")
     */
    public function appro_categories_api_get(Request $request, $id)
    {
        $shop_id = $request->headers->get('shopid');
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare("SELECT *, media_get(1, s_product.id) photos, media_name(1, s_product.id) image,
                                                concat('https://affairez.com/apistock/public/shop/',s_product.id_magasin,'/product/', media_name(1, s_product.id)) imageurl
                                            FROM s_product
                                          WHERE product_categories_id = ? AND id_magasin = ? AND disabled = 0 ORDER BY name");
        $res = $var->executeQuery([$id, $shop_id])->fetchAllAssociative();
//        $res = $var->fetchAllAssociative();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }

    /**
     * @Route("/api/get/products-by-domain/{domaineid}", name="products_domain_api_get")
     */
    public function product_categories_domain_get(Request $request, $domaineid)
    {
        $shop_id = $request->headers->get('shopid') ?? 1;
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare("SELECT concat(s_product.name, ' ', ifnull(scp.name, ''), ' ', ifnull(sma.nom, '')) prodcat, s_product.id, s_product.name, s_product.name title, disabled,
                                            reference, alert_threshold, product_desc, product_desc description, product_url_img, product_categories_id, spc.name categorie, agent_user, 0 discount,
                                            amount, price, sales_price, buy_price, tva, priceweb, color, webstatus, largeur, longueur, poids, hauteur, parent_categorie_id, scp.name parent_categorie_name,
                                                    scp.domainid, scd.name domainname, __product_qty(s_product.id_magasin, s_product.id) reste, 0 def,
                                            sma.nom marque, sma.photo marque_photo,
                                            media_get(1, s_product.id) photos,
                                                media_name(1, s_product.id) image,
                                            concat('$this->uploadShop',s_product.id_magasin,'/product/', media_name(1, s_product.id)) imageurl
                                            FROM s_product
                                            LEFT JOIN s_marques sma on sma.id = marque_id
                                            LEFT JOIN s_product_categories spc on s_product.product_categories_id = spc.id
                                            LEFT JOIN s_categorie_parent scp on s_product.parent_categorie_id = scp.id
                                            LEFT JOIN s_categories_domain scd on scp.domainid = scd.id
                                            WHERE scd.id = ? AND s_product.id_magasin = ? AND disabled = 0
                                            ORDER BY s_product.name;");
        $res = $var->executeQuery([$domaineid, $shop_id])->fetchAllAssociative();
//        $res = $var->fetchAllAssociative();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }

    /**
     * @Route("/api/get/products-by-parent/{id}", name="products_parent_api_get")
     */
    public function product_categories_parent_get(Request $request, $id)
    {
        $shop_id = $request->headers->get('shopid') ?? 1;
        $em = $this->getDoctrine();
        $var = $em->getConnection()
            ->prepare("SELECT concat(s_product.name, ' ', ifnull(scp.name, ''), ' ', ifnull(sma.nom, '')) prodcat, s_product.id, s_product.name, s_product.name title, disabled,
                        reference, alert_threshold, product_desc, product_desc description, product_url_img, product_categories_id, spc.name categorie, agent_user, 0 discount,
                        amount, price, sales_price, buy_price, tva, priceweb, color, webstatus, largeur, longueur, poids, hauteur, parent_categorie_id, scp.name parent_categorie_name,
                        scp.domainid, scd.name domainname, __product_qty(s_product.id_magasin, s_product.id) reste, 0 def,
                        sma.nom marque, sma.photo marque_photo,
                        media_get(1, s_product.id) photos,
                        media_name(1, s_product.id) image,
                        concat('$this->uploadShop',s_product.id_magasin,'/product/', media_name(1, s_product.id)) imageurl
                        FROM s_product
                        LEFT JOIN s_marques sma on sma.id = marque_id
                        LEFT JOIN s_product_categories spc on s_product.product_categories_id = spc.id
                       LEFT JOIN s_categorie_parent scp on s_product.parent_categorie_id = scp.id
                        LEFT JOIN s_categories_domain scd on scp.domainid = scd.id
                           WHERE parent_categorie_id = ? AND s_product.id_magasin = ? AND disabled = 0
                       ORDER BY s_product.name;");
        $res = $var->executeQuery([$id, $shop_id])->fetchAllAssociative();
//        $res = $var->fetchAllAssociative();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }

}

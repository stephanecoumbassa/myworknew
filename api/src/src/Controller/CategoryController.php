<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{

    use JwtTrait;

    /**
     * @Route("/my/post/categories_stock", name="categories_post_stock")
     */
    public function categories_register(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $name = $data["name"];
        $icon = $data["icon"] ?? 'crop_square';
        $parentid = $data["parentid"] ?? null;
        $shop_id = $this->getUser()->getShopId();
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('INSERT INTO s_product_categories (name, icon, parentid, id_magasin) VALUES (?, ?, ?, ?);');
        $var->executeQuery([$name, $icon, $parentid, $shop_id]);
        return new JsonResponse(['msg'=>'Ajouté avec succès', 'status'=> 1]);
    }

    /**
     * @Route("/my/put/categories_stock", name="categories_put_stock")
     */
    public function categories_update(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $name = $data["name"];
        $parentid = $data["parentid"] ?? NULL;
        $icon = $data["icon"] ?? 'crop_square';
        $shop_id = $this->getUser()->getShopId();
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('UPDATE s_product_categories SET name = ?, icon = ?, parentid = ? WHERE id = ? AND id_magasin = ?');
        $var->executeQuery([$name, $icon, $parentid, $data['id'], $shop_id]);
        return new JsonResponse(['msg'=>'Modifié avec succès', 'status'=> 1]);
    }

    /**
     * @Route("/my/delete/categories_stock", name="categories_delete_stock")
     */
    public function categories_delete(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $id = $data["id"];
        $shop_id = $this->getUser()->getShopId();
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('DELETE FROM s_product_categories
                                              WHERE id = ? AND id_magasin = ?');
        $res = $var->executeQuery([$id, $shop_id])->fetch();
        return new JsonResponse($res);
    }

    /**
     * @Route("/my/get/categories_stock", name="categories_get_stock")
     */
    public function categories_get(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $shop_id = $this->getUser()->getShopId();
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT * FROM s_product_categories WHERE id_magasin = ?');
        $res = $var->executeQuery([$shop_id])->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }

    /**
     * @Route("/my/get/categories_parent", name="categories_get_parent")
     */
    public function categories_parents_get(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $shop_id = $this->getUser()->getShopId();
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare("SELECT s_categorie_parent.id, s_categorie_parent.name, domainid, id_magasin,
            scd.name domainname, concat(s_categorie_parent.name, ' - ',scd.name) fullname
            FROM s_categorie_parent LEFT JOIN s_categories_domain scd on s_categorie_parent.domainid = scd.id
        WHERE id_magasin = ? OR id_magasin IS NULL ORDER BY scd.name");
        $res = $var->executeQuery([$shop_id])->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }

    /**
     * @Route("/my/post/categories_parent", name="categories_parent_post")
     */
    public function categories_parent_register(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $name = $data["name"] ?? null; $domainid = $data["domainid"] ?? null;
        $shop_id = $this->getUser()->getShopId();
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('INSERT INTO s_categorie_parent (name, domainid, id_magasin) VALUES (?, ?, ?);');
        $var->executeQuery([$name, $domainid, $shop_id]);
        return new JsonResponse(['status'=>1, 'msg'=>"Ajouté avec succès"]);
    }

    /**
     * @Route("/my/put/categories_parent", name="categories_parent_put")
     */
    public function categories_parent_update(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $name = $data["name"] ?? null; $domainid = $data["domainid"] ?? null;
        $shop_id = $this->getUser()->getShopId();
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('UPDATE s_categorie_parent SET name = ?, domainid = ? WHERE id = ? AND id_magasin = ?;');
        $var->executeQuery([$name, $domainid, $data["id"], $shop_id]);
        return new JsonResponse(['status'=>1, 'msg'=>"Ajouté avec succès"]);
    }

    /**
     * @Route("/my/get/categories_all", name="categories_get_all2")
     */
    public function categories_all2(Request $request)
    {
        $shop_id = $this->getUser()->getShopId();
        $em = $this->getDoctrine();

        $var = $em->getConnection()->prepare('SELECT * from s_categories_domain ORDER BY name;');
        $domaines = $var->executeQuery()->fetchAll();
//        $domaines = $var->executeQuery([$shop_id])->fetchAll();

        $var = $em->getConnection()->prepare('SELECT * from s_categorie_parent WHERE id_magasin = ? OR id_magasin IS NULL ORDER BY name;');
        $parents = $var->executeQuery([$shop_id])->fetchAll();

        $var = $em->getConnection()->prepare('SELECT * from s_product_categories WHERE id_magasin = ? ORDER BY name;');
        $cats = $var->executeQuery([$shop_id])->fetchAll();

        $res = json_decode(json_encode([$domaines, $parents, $cats], JSON_NUMERIC_CHECK), true);

        return new JsonResponse($res);
    }

    /**
     * @Route("/my/post/categories_domain", name="categories_domain_post")
     */
    public function categories_domain_register(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $name = $data["name"] ?? null;
        $shop_id = $this->getUser()->getShopId();
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('INSERT INTO s_categorie_parent (name, id_magasin) VALUES (?, ?);');
        $var->executeQuery([$name, $shop_id]);
        return new JsonResponse(['status'=>1, 'msg'=>"Ajouté avec succès"]);
    }

    /**
     * @Route("/api/get/categories_domain", name="categories_domain")
     */
    public function categories_domain(Request $request)
    {
        $shop_id = $request->headers->get('shopid');
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT *, categorie_parent_get(id, ?) categories_parent
                                                FROM s_categories_domain ORDER BY name;');
        $res = $var->executeQuery([$shop_id])->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }

    /**
     * @Route("/api/get/categories_domain2", name="categories_domain2")
     */
    public function categories_domain2(Request $request)
    {
        $shop_id = $request->headers->get('shopid');
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('select domainid id, domainname name, id product_id
                                            from v_products where id_magasin = ? ORDER BY domainid;');
        $res = $var->executeQuery([$shop_id])->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);

        return new JsonResponse($res);
    }

    /**
     * @Route("/api/get/categories_stock", name="categories_get_one_stock")
     */
    public function categories_stock(Request $request)
    {
        $shop_id = $request->headers->get('shopid');
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT * FROM s_product_categories WHERE id_magasin = ?');
        $res = $var->executeQuery([$shop_id])->fetchAll();
        return new JsonResponse($res);
    }

    /**
     * @Route("/api/get/categories_stock2", name="categories_get_stock2")
     */
    public function categories_stock2(Request $request)
    {
        $parentid = $_GET['parentid'] ?? null;
        $shop_id = $request->headers->get('shopid');
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT * FROM s_product_categories
        WHERE id_magasin = ? AND parentid = ?;');
        $res = $var->executeQuery([$shop_id, $parentid])->fetchAll();
        return new JsonResponse($res);
    }

    /**
     * @Route("/api/get/categories_all", name="categories_get_all")
     */
    public function categories_all(Request $request)
    {

        $shop_id = $request->headers->get('shopid');
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT s_categorie_parent.id, s_categorie_parent.name, scd.name domainname, domainid, id_magasin,
                        categorie_product_get(s_categorie_parent.id, ?) categories,
                        categorie_parent_get(domainid, ?) categories_parent
                    from s_categorie_parent
                    LEFT JOIN s_categories_domain scd on s_categorie_parent.domainid = scd.id
                    WHERE id_magasin = ? OR id_magasin IS NULL;');
        $res = $var->executeQuery([$shop_id, $shop_id, $shop_id])->fetchAll();
        return new JsonResponse($res);
    }

    /**
     * @Route("/api/get/categories_parent/{id}/{idmagasin}", name="categories_parent_get2")
     */
    public function categories_one_get($id, $idmagasin)
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT * FROM s_categorie_parent WHERE
                                       (id_magasin IS NULL) AND domainid = ? ORDER BY id_magasin DESC');
        $res = $var->executeQuery([$id])->fetchAll();
        return new JsonResponse($res);
    }

    /**
     * @Route("/api/get/categories_parent_domain/{id}/{idmagasin}", name="categories_parent_domain")
     */
    public function categories_parent_domain($id, $idmagasin)
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('select parent_categorie_id id, parent_name name, domainid, domainname
                                            from v_products WHERE (id_magasin IS NULL OR id_magasin = ?)
                                                              AND domainid = ? ORDER BY id_magasin DESC');
        $res = $var->executeQuery([$idmagasin, $id])->fetchAll();
        return new JsonResponse($res);
    }

    /**
     * @Route("/api/get/categories/{id}/{idmagasin}", name="categories_parent_get3")
     */
    public function categories_get_by($id, $idmagasin)
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT * FROM s_product_categories WHERE
                                       (id_magasin IS NULL OR id_magasin = ?) AND parentid = ? ORDER BY id_magasin DESC');
        $res = $var->executeQuery([$idmagasin, $id])->fetchAll();
        return new JsonResponse($res);
    }

    /**
     * @Route("/api/get/categories_parent", name="categories_parent")
     */
    public function categories_parents(Request $request)
    {
        $shop_id = $request->headers->get('shopid')??1;
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT *, categorie_parent_get(id, ?) categories_parent
                                                FROM s_categories_domain ORDER BY name;');
        $res = $var->executeQuery([$shop_id])->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }


    /**
     * @Route("/api/list/domaines/{shopId}", name="domaines_list")
     */
    public function domaineListShop($shopId)
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()
                ->prepare('SELECT distinct scd.id, scd.name, categorie_parent_get(scd.id, ?) categories_parent,
                (select count(id) from v_products where domainid = scd.id and id_magasin = ?) count
            FROM s_categories_domain scd
            LEFT JOIN v_products sp on scd.id = sp.domainid
            where id_magasin = ? ORDER BY scd.name;');
        $res = $var->executeQuery([$shopId, $shopId, $shopId])->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);

        return new JsonResponse($res);
    }

    /**
     * @Route("/api/list/parent/{shopId}", name="parent_list")
     */
    public function parentListShop($shopId)
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()
            ->prepare('SELECT distinct scd.id, scd.name, categorie_parent_get(scd.id, ?) categories_parent,
                (select count(id) from v_products where domainid = scd.id and id_magasin = ?) count
            FROM s_categories_domain scd
            LEFT JOIN v_products sp on scd.id = sp.domainid
            where id_magasin = ? ORDER BY scd.name;');
        $res = $var->executeQuery([$shopId, $shopId, $shopId])->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);

        return new JsonResponse($res);
    }

}

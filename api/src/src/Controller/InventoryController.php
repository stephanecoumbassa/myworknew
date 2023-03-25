<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class InventoryController extends AbstractController
{

    use JwtTrait;

    /**
     * @Route("/my/get/inventaire", name="product_inventaires_get")
     */
    public function inventaire_get()
    {
        $idmagasin =$this->getUser()->getShopId();
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT DISTINCT name, inventory_id, dateposted from s_inventory where id_magasin = ? ORDER BY inventory_id DESC');
        $res = $var->execute([$idmagasin])->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }

    /**
     * @Route("/my/get/inventaire/{inventory}", name="product_inventaires_name")
     */
    public function inventaire_id($inventory)
    {
        $idmagasin =$this->getUser()->getShopId();
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT *, (select s_product.name from s_product where s_product.id = s_inventory.product_id) productname from s_inventory where id_magasin = ? AND inventory_id = ?
                                                       order by inventory_id desc');
        $res = $var->execute([$idmagasin, $inventory])->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }

    /**
     * @Route("/my/get/inventaire_list", name="product_inventaires_list")
     */
    public function inventaire_list()
    {
        $idmagasin =$this->getUser()->getShopId();
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT * from s_inventory where id_magasin = ? ORDER BY id DESC');
        $res = $var->execute([$idmagasin])->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }

    // INVENTAIRE
    /**
     * @Route("/my/inventaire/products", name="product_inventaires")
     */
    public function product_inventaire(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $shop_id = $this->getUser()->getShopId();
        $code_inventaire = 'INV-'.date("ymd-his", time());
        $name = $data["name"] ?? 'Inventaire-'.date("ymd-his", time());

        $products = [];
        $products_ok = [];
        $products_out = [];
        foreach($data["products"] as $item) {

            if($item['def'] !== $item['reste']) {
                $products_out[] = [ "product_id"=>$item["id"], "reste"=>$item["reste"], "def"=>$item["def"],
                    "name"=>$data["name"], "id_magasin" => $shop_id, "inventory_id"=> $code_inventaire ];
            } elseif ($item['def'] == $item['reste']) {
                $products[] = [ "product_id"=>$item["id"], "product_name"=>$item["name"], "reste"=>$item["reste"], "def"=>$item["def"],
                    "name"=>$data["name"], "id_magasin" => $shop_id, "inventory_id"=> $code_inventaire ];
            }
        }

        $em = $this->getDoctrine();

        $var = $em->getConnection()->prepare('INSERT INTO s_inventory
    (product_id, name, old, new, inventory_id, id_magasin, data) VALUES (?, ?, ?, ?, ?, ?, ?); ');
        $var->execute([0, $name, 0, 0, $code_inventaire, $shop_id, json_encode($products, true)]);

        $json_products = json_encode($products_out, true);
        $var = $em->getConnection()->prepare('CALL inventory_create(?)');
        $var->execute([$json_products]);

        return new JsonResponse(['status' =>1, 'msg' => 'Créer avec succès']);
    }


}

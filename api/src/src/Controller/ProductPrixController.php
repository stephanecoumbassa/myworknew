<?php
namespace App\Controller;

use App\Controller\BaseController;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductPrixController extends BaseController {

    /**
     * @Route("/my/get/s_product_prix", name="s_product_prix_get")
     */
    public function s_product_prix_get() :JsonResponse
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT * FROM s_product_prix ORDER BY id DESC LIMIT 500;');
        $res = $var->executeQuery()->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }

    /**
     * @Route("/my/get/s_product_prix/{id}", name="s_product_prix_by")
     */
    public function s_product_prix_by($id) :JsonResponse
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT * FROM s_product_prix WHERE product_id = ?;');
        $res = $var->executeQuery([$id])->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }

    /**
     * @Route("/my/client/s_product_prix/{id}", name="s_product_prix_by_client")
     */
    public function s_product_prix_by_client($id) :JsonResponse
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT * FROM s_product_prix WHERE client_id = ?;');
        $res = $var->executeQuery([$id])->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }


//    /**
//     * @Route("/my/client/s_product_prix/{id}", name="s_product_prix_by_client")
//     */
//    public function s_product_prix_by_client($id) :JsonResponse
//    {
//        $em = $this->getDoctrine();
//        $var = $em->getConnection()
//            ->prepare("SELECT concat( s_product.name, ' ', ifnull(scp.name, '') ) prodcat, s_product.id, s_product.name, s_product.name title, disabled,
//                                reference, alert_threshold, product_desc, product_desc description, product_url_img, product_categories_id, spc.name categorie, agent_user, 0 discount,
//                                amount, price, sales_price, buy_price, tva, priceweb, color, webstatus, largeur, longueur, poids, hauteur, parent_categorie_id, scp.name parent_categorie_name,
//                                        scp.domainid, scd.name domainname, __product_qty(s_product.id_magasin, s_product.id) reste, 0 def,
//                                 customize, media_get(1, s_product.id) photos, media_name(1, s_product.id) image, client_id, prix_vente,
//                                concat('$this->uploadShop',s_product.id_magasin,'/product/', media_name(1, s_product.id)) imageurl
//                              FROM s_product
//                              LEFT JOIN s_product_categories spc on s_product.product_categories_id = spc.id
//                              LEFT JOIN s_categorie_parent scp on scp.id = s_product.parent_categorie_id
//                              LEFT JOIN s_categories_domain scd on scp.domainid = scd.id
//                              LEFT JOIN s_product_prix spp on spp.product_id = s_product.id
//                              WHERE client_id = ? AND disabled= 0 ORDER BY id DESC;");
//        $res = $var->executeQuery([$id])->fetchAll();
//        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
//        return new JsonResponse($res);
//    }


    /**
     * @Route("/my/search/s_product_prix", name="s_product_prix_search_get")
     */
    public function s_product_prix_search(Request $request) :JsonResponse
    {
        //$search = "%{$_GET['search']}%";
        $data = json_decode($request->getContent(),true) ?? $_POST;
        $searchFields = $data['search'];

        $params = [];
        $where = "";
        foreach ($searchFields as $field) {
            $operator = $field['operator']??'=';
            if(isset($field['key']) && !empty($field['key'])) {
                array_push($params, $field['value']);
                $where = $where."AND {$field['key']} $operator ? ";
            }
        }
        $where = substr($where, 3);

        $em = $this->getDoctrine();
        $var = $em->getConnection()->executeQuery("SELECT * FROM s_product_prix WHERE $where", $params);
        $res = $var->fetchAll();
        return new JsonResponse($res);
    }

    /**
     * @Route("/my/post/s_product_prix", name="admin_s_product_prix_add")
     */
    public function s_product_prix_add(Request $request, FileUploader $fileUploader)
    {
        $data = json_decode($request->getContent(),true) ?? $_POST;
        $em = $this->getDoctrine();

        $params = [  $data['product_id']??null, $data['client_id']??null, $data['prix_vente']??null, $data['date']??null ];
        //$var = $em->getConnection()->prepare('call s_product_prix_add( ?, ?, ?, ?);');
        $var = $em->getConnection()->prepare('INSERT INTO s_product_prix SET  product_id = ?, client_id = ?, prix_vente = ?, date = ?');
        $var->executeQuery($params);

        return new JsonResponse(['msg'=>'Ajouté avec succes', 'status'=>1]);
    }

    /**
     * @Route("/my/put/s_product_prix", name="admin_s_product_prix_update")
     */
    public function s_product_prix_update(Request $request)
    {
        $data = json_decode($request->getContent(),true) ?? $_POST;
        $em = $this->getDoctrine();
        $params = [  $data['product_id']??null, $data['client_id']??null, $data['prix_vente']??null, $data['date']??null, $data['id'] ];
        //$var = $em->getConnection()->prepare('call s_product_prix_update( ?, ?, ?, ?, ?);');
        $var = $em->getConnection()->prepare('UPDATE s_product_prix SET  product_id = ?, client_id = ?, prix_vente = ?, date = ? WHERE id = ?;');
        $var->executeQuery($params);
        return new JsonResponse(['msg'=>'Modifié avec succes', 'status'=>1]);
    }

    /**
     * @Route("/my/put2/s_product_prix", name="admin_s_product_prix_update2")
     */
    public function s_product_prix_update2(Request $request)
    {
        $data = json_decode($request->getContent(),true) ?? $_POST;

        $params = [];
        $update = "";
        foreach($data as $key => $value) {
            if($key !=='id') {
                array_push($params, $value);
                $update = $update."$key=?,";
            }
        }
        $update = substr($update, 0, -1);
        array_push($params, $data['id']);
        $em = $this->getDoctrine();
        $var = $em->getConnection()->executeQuery(
            "UPDATE s_product_prix SET $update WHERE id = ?;", $params);

        return new JsonResponse(['msg'=>'Modifié avec succes', 'status'=>1]);
    }

    /**
     * @Route("/my/delete/s_product_prix/{id}", name="admin_s_product_prix_delete")
     */
    public function s_product_prix_delete($id)
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('DELETE FROM s_product_prix WHERE id = ?;');
        $var->executeQuery([$id]);
        return new JsonResponse(['status'=>1, 'msg'=>'supprimé avec succès']);
    }

}

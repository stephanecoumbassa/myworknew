<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DevisController extends AbstractController
{

    /**
     * @Route("/my/get/devis", name="devis_get")
     */
    public function devis_get(Request $request)
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT distinct(id_vente), client_id, sc.last_name, sc.name, concat(sc.last_name," ", sc.name) fullname,
               (select date_posted from s_devis WHERE id_vente = s_devis.id_vente ORDER BY id DESC LIMIT 1) date_posted FROM s_devis
               LEFT JOIN s_clients sc on s_devis.client_id = sc.id WHERE s_devis.id_magasin = ? ORDER BY id_vente DESC');
        $res = $var->executeQuery([$this->getUser()->getShopId()])->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }

    /**
     * @Route("/my/get/devis/{idvente}", name="devis_by_get")
     */
    public function devis_venete_get($idvente)
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT s_devis.id, quantite_vendu, prix_unitaire, montant_vendu, remise_unite, remise_totale, benefice_vente,
       date_posted, product_id, user_id, id_vente, s_devis.id_magasin, fournisseur_id, client_id, code_ap, price_vente_ttc, price_achat_ttc, s_devis.price_tva, s_devis.tva,
       credit, codecomptable, status, s_devis.type, sp.name, client_func_get(client_id, s_devis.id_magasin) client
            FROM s_devis LEFT JOIN s_product sp on s_devis.product_id = sp.id WHERE
                            s_devis.id_magasin = ? AND id_vente = ? ORDER BY s_devis.id DESC');
        $res = $var->executeQuery([$this->getUser()->getShopId(), $idvente])->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }

    /**
     * @Route("/my/get/devis_product_stats", name="devis_product_stats_get")
     */
    public function devis_product_stat_get(Request $request)
    {
        $em = $this->getDoctrine();
        $params = [$_GET['first'], $_GET['last'], $this->getUser()->getShopId(), $_GET['pid']];
        $var = $em->getConnection()->prepare('CALL __devis_product_by_date(?, ?, ?, ?)');
        $res = $var->executeQuery($params)->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);

        return new JsonResponse($res);
    }

    /**
     * @Route("/my/post/devis", name="devis_post")
     */
    public function devis_register(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $em = $this->getDoctrine();
        $agent = $this->getUser()->getId();
        $shop_id = $this->getUser()->getShopId();
        $code_vente = $data['id_vente'] ?? 'Dev-'.date("ymd-his", time());
        $clientid = $data['clientid'];
        $products = $data['products'];
        $bc = $data['bc'];
        $bl = $data['bl'];

        $count = count($products);
        for ($i=0; $i<$count; $i++) {
            $pid = $products[$i]['p']['id'];
            $qty = $products[$i]['quantity'];
            $sell = $products[$i]['p']['sales_price'];
            $tva = $products[$i]['p']['tva'];
            $var = $em->getConnection()->prepare('
                INSERT INTO s_devis (user_id, product_id, quantite_vendu, prix_unitaire, montant_vendu, tva, client_id, id_vente, id_magasin, type, bl, bc, vuuid) VALUES
                            (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, UUID())
            ');
            $var->executeQuery([$agent, $pid , $qty, $sell, ($qty * $sell) , $tva, $clientid, $code_vente, $shop_id, 'devis', $bl, $bc]);
        }
        return new JsonResponse(['status'=>1, 'msg'=> 'ajouté avec succès']);
    }

    /**
     * @Route("/my/put/devis", name="devis_put")
     */
    public function devis_update(Request $request){
        $data = json_decode($request->getContent(),true);
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('UPDATE s_devis
        SET quantite_vendu = ?, prix_unitaire = ?, montant_vendu = ?, product_id = ? WHERE id = ? AND id_magasin = ? ORDER BY id DESC');
        $var->executeQuery([$data['quantite_vendu'], $data['prix_unitaire'], $data['montant_vendu']??null, $data['p_id'], $data['id'], $this->getUser()->getShopId() ]);
        return new JsonResponse(["msg"=>"Modifié avec succès", "status"=>1]);
    }


    /**
     * @Route("/my/delete/devis", name="devis_delete")
     */
    public function devis_delete(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $shopid = $this->getUser()->getShopId();
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('DELETE FROM s_devis WHERE id = ? AND id_magasin = ?;');
        $var->executeQuery([$data['id'], $shopid]);
        return new JsonResponse(["msg"=>"supprimé avec succès", "status"=>1]);
    }


    /**
     * @Route("/my/delete/devis_all", name="devis_delete_all")
     */
    public function devis_delete_all(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $factureid = $data['factureid'];
        $shopid = $this->getUser()->getShopId();
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('DELETE FROM s_ventes where id_vente = ? AND id_magasin = ?;');
        $var->executeQuery([ $factureid, $shopid ]); $var->closeCursor();
        $var = $em->getConnection()->prepare("DELETE FROM s_credit where factureid = ? AND id_magasin = ? AND type = 'vente';");
        $var->executeQuery([ $factureid, $shopid ]); $var->closeCursor();
        return new JsonResponse(["msg"=>"supprimé avec succès", "status"=>1]);
    }

    /**
     * @Route("/my/post/devis_convert", name="devis_convert_post")
     */
    public function devis_convert(Request $request)
    {
        $data = json_decode($request->getContent(),true) ?? $_POST;
        $em = $this->getDoctrine();
        $agent = $this->getUser()->getId();
        $shop_id = $this->getUser()->getShopId();
        $code_vente = 'V-'.date("ymd-his", time());
        $clientid = $data['clientid'];
        $products = $data['products'];
//        return new JsonResponse($products);

        $count = count($products);
        for ($i=0; $i<$count; $i++) {
            $pid = $products[$i]['product_id'];
            $qty = $products[$i]['quantite_vendu'];
            $sell = $products[$i]['prix_unitaire'];
            $tva = $products[$i]['tva'];
            $var = $em->getConnection()->prepare("CALL __sales_register_prix_moyen(?, ?, ?, ?, ?, ?, ?, ?);");
            $res = $var->executeQuery([$agent, $pid , $qty, $sell, $tva, $clientid, $code_vente, $shop_id])->fetch();
        }
        $res['factureid'] = $code_vente;
        if($data['credit'] == true) {
            $var = $em->getConnection()->prepare('INSERT INTO s_credit (montant, type, factureid, date, id_magasin) VALUES (?, ?, ?, NOW(), ?);');
            $var->executeQuery([$data['avance'], 'vente', $code_vente, $this->getUser()->getShopId()]);
        } elseif($data['credit'] == false) {
            $var = $em->getConnection()->prepare('INSERT INTO s_credit ( montant, type, factureid, date, id_magasin) VALUES (?, ?, ?, NOW(), ?);');
            $var->executeQuery([$data['total'], 'vente', $code_vente, $this->getUser()->getShopId()]);
        }
        $var = $em->getConnection()->prepare('DELETE FROM s_devis WHERE id_vente = ? AND id_magasin = ?;');
        $var->executeQuery([$data['id_vente'], $this->getUser()->getShopId()]);
        return new JsonResponse(['msg'=> 'Devis converti en vente', 'status'=> 1]);
    }

    /**
     * @Route("/api/post/devis_file", name="devis_file_post")
     */
    public function devis_file(Request $request)
    {
        $data = json_decode($request->getContent(),true) ?? $_POST;

        $file = $request->files->get('doc');
        if( isset($file) && !empty($file) ) {
            $pdfFile = $request->files->get('doc');
            $pdfFileExtension = $pdfFile->getClientOriginalExtension();
            $name = 'devis'.uniqid().'.'.$pdfFileExtension;
            $data['doc'] = $name;
            $uploadDir = $_SERVER['DOCUMENT_ROOT'].'/apistock/public/assets/uploads/devis/';
            $pdfFile->move( $uploadDir, $name );
        }

        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('INSERT INTO s_devis_file ( file, devis_id, titre, extension ) VALUES ( ?, ?, ?, ? );');
        $var->executeQuery([$data['doc'], $data['devis_id'], $data['titre'], $pdfFileExtension ?? '' ])->fetchAll();
        return new JsonResponse(['msg'=>'Ajouté avec succès', 'status'=>1]);
    }

    /**
     * @Route("/api/get/devis_file/{id}", name="devis_file_get")
     */
    public function devis_file_get($id)
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()
                  ->prepare('SELECT * FROM s_devis_file WHERE devis_id = ?;');
        $res = $var->executeQuery([$id])->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }


}

<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;


class ApproController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/api/get/appro", name="codeap_get")
     */
    public function appro_get(Request $request)
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT * FROM s_aprovisionements');
        $res = $var->executeQuery()->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }

    /**
     * @Route("/my/get/factures_appro", name="factures_appro_get")
     */
    public function facture_appro_get(Request $request)
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare("SELECT DISTINCT id_ap facture, id_magasin, dateposted date, 'achat' type, fournisseur_func_get(fournisseur_user, id_magasin) fournisseur,
               credit_sum_facture(id_ap, id_magasin) versement, __appro_sum_facture(id_ap, id_magasin) total from s_aprovisionements WHERE id_magasin = ? ORDER BY facture DESC;");
        $res = $var->executeQuery([$this->getUser()->getShopId()])->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }

    /**
     * @Route("/my/get/appro_by_facture", name="appro_factures_get")
     */
    public function facture_get_by_id(Request $request)
    {
        $id_vente = $_GET['id_ap'];
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT s_aprovisionements.id, s_aprovisionements.amount, s_aprovisionements.amount quantity, product_id, dateposted, user_id, code_ap, fournisseur_user, buying_price, buying_price price,
                                                sell_price, total, s_aprovisionements.id_magasin, id_ap, amount_restant, s_aprovisionements.tva, codecomptable, code_ap_name,
                                                type, status, id_vente, motif, sp.name p_name, sp.name name, fournisseur_func_get(fournisseur_user, s_aprovisionements.id_magasin) fournisseur
                                                from s_aprovisionements
                                                    left join s_product sp on s_aprovisionements.product_id = sp.id
                                                where s_aprovisionements.id_magasin = ? AND id_ap = ?;');
        $res = $var->executeQuery([$this->getUser()->getShopId(), $id_vente])->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }

    /**
     * @Route("/my/get/appro_by_credit", name="appro_factures_credit_get")
     */
    public function facture_credit(Request $request)
    {
        $id_vente = $_GET['id_vente'];
        $idmagasin =$this->getUser()->getShopId();
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT * from s_credit where factureid = ? AND id_magasin = ? ORDER BY id DESC');
        $res = $var->executeQuery([$id_vente, $idmagasin])->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }

    /**
     * @Route("/my/get/appro_stats", name="appro_stats_get")
     */
    public function appro_statst_get(Request $request)
    {
        $em = $this->getDoctrine();
        $params = [$_GET['first'], $_GET['last'], $this->getUser()->getShopId()];
        $var = $em->getConnection()->prepare('CALL __appro_by_date(?, ?, ?)');
        $res = $var->executeQuery($params)->fetchAll();
        return new JsonResponse($res);
    }

    /**
     * @Route("/my/get/appro_product_stats", name="appro_stats_product_get")
     */
    public function appro_product_statst_get(Request $request)
    {
        $em = $this->getDoctrine();
        $params = [$_GET['first'], $_GET['last'], $this->getUser()->getShopId(), $_GET['pid']];
        $var = $em->getConnection()->prepare('CALL __appro_product_by_date(?, ?, ?, ?)');
        $res = $var->executeQuery($params)->fetchAll();
        return new JsonResponse($res);
    }

    /**
     * @Route("/my/get/appro_fournisseur_stats", name="appro_stats_fournisseur_get1")
     */
    public function appro_fournisseur_stat_get(Request $request)
    {
        $em = $this->getDoctrine();
        $params = [$_GET['first'], $_GET['last'], $this->getUser()->getShopId(), $_GET['fournisseurid']];
        $var = $em->getConnection()->prepare('CALL __appro_fournisseur_by_date(?, ?, ?, ?)');
        $res = $var->executeQuery($params)->fetchAll();
        return new JsonResponse($res);
    }

    /**
     * @Route("/my/get/appro", name="codeap_get_my")
     */
    public function appro_my_get(Request $request)
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare("SELECT * FROM s_aprovisionements WHERE id_magasin = ? AND type = 'achat' ORDER BY id DESC;");
        $res = $var->executeQuery([$this->getUser()->getShopId()])->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }

    /**
     * @Route("/my/post/commands", name="commands_post")
     */
    public function commands_register(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $em = $this->entityManager;
        $agent = $this->getUser()->getId();
        $shop_id = $this->getUser()->getShopId();
        $fournisseur = $data['fournisseur'];
        $products = $data['products'];
        $id_facture = 'A-'.date("ymd-his", time());
        $dateposted = $data['dateposted'] ?? date("y-m-d h:i", time());
        $count = count($products);
        $y = 0;
        for ($i=0; $i<$count; $i++) {
            $var = $em->getConnection()->prepare('CALL __ap_register_prix_moyen2(?, ?, ? ,?, ?,
                                                                          ?, ?, ?, ?, ?);');
            $var->execute([$products[$i]['id'], $agent, $fournisseur, $products[$i]['quantity'],
                $products[$i]['buy'], $products[$i]['sell'], $products[$i]['tva'], $dateposted, $shop_id, $id_facture ])
                ;
            $em->close();
        }

        return new JsonResponse(['msg'=>'Commande ajoutée avec succès',
            'factureid'=> $id_facture,
            'status' => 1,
            'data' => $data
        ]);
    }

    /**
     * @Route("/my/put/commands_credit", name="commands_credit_put")
     */
    public function commands_credit(Request $request){
        $data = json_decode($request->getContent(),true) ?? $_POST;
        $id_facture = $data['factureid'];
        $em = $this->entityManager;
        if ($data['credit'] == true) {
            $var2 = $em->getConnection()->prepare('INSERT INTO s_credit (montant, type, factureid, date, id_magasin) VALUES (?, ?, ?, NOW(), ?);');
            $var2->executeQuery([$data['avance'], 'achat', $id_facture, $this->getUser()->getShopId()]);
        }elseif ($data['credit'] == false) {
            $var3 = $em->getConnection()->prepare('INSERT INTO s_credit ( montant, type, factureid, date, id_magasin) VALUES (?, ?, ?, NOW(), ?);');
            $var3->executeQuery([$data['total'], 'achat', $id_facture, $this->getUser()->getShopId()]);
        }
        return new JsonResponse(['msg'=>'Commande ajoutée avec succès']);
    }

    /**
     * @Route("/my/put/commands", name="commands_put")
     */
    public function commands_update(Request $request){
        $data = json_decode($request->getContent(),true);
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('UPDATE s_aprovisionements SET sell_price = ?, buying_price = ?, amount = ?, product_id = ? WHERE id = ? AND id_magasin = ? ORDER BY id DESC');
        $res = $var->executeQuery([$data['sell_price']??null, $data['buying_price']??null, $data['amount']??null, $data['product_id']??null, $data['id']??null, $this->getUser()->getShopId() ]);
        return new JsonResponse(["msg"=>"Modifié avec succès", "status"=>1]);
    }

    /**
     * @Route("/my/put/appro", name="appro_put")
     */
    public function appro_update(Request $request){
        $data = json_decode($request->getContent(),true);
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('UPDATE s_aprovisionements SET sell_price = ?, buying_price = ?, amount = ?, product_id = ? WHERE id = ? AND id_magasin = ?;');
        $res = $var->executeQuery([$data['sell_price']??0, $data['buying_price']??0, $data['amount']??0, $data['product_id']??null, $data['id']??null, $this->getUser()->getShopId() ]);
        return new JsonResponse(["msg"=>"Modifié avec succès", "status"=>1]);
    }

    /**
     * @Route("/my/dateposted/appro", name="appro_dateposted")
     */
    public function appro_dateposted(Request $request){
        $data = json_decode($request->getContent(),true);
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('UPDATE s_aprovisionements SET dateposted = ? WHERE id_ap = ? AND id_magasin = ?;');
        $res = $var->executeQuery([ $data['dateposted'], $data['id_ap'], $this->getUser()->getShopId() ]);
        return new JsonResponse(["msg"=>"Modifié avec succès", "status"=>1]);
    }


    /**
     * @Route("/my/get/commands", name="command_get")
     */
    public function command_get(Request $request)
    {
        // $shopid = (int)$request->headers->get('shopid');
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('CALL __appro_get_all(?);');
        $res = $var->executeQuery([$this->getUser()->getShopId()])->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);

        return new JsonResponse($res);
    }

    /**
     * @Route("/my/delete/appro", name="appro_delete")
     */
    public function appro_delete(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $shopid = $this->getUser()->getShopId();
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('CALL __ap_delete(?, ?)');
        $res = $var->executeQuery([$data['id'], $shopid]);
        $res = $res = $var->fetch();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }

    /**
     * @Route("/my/delete/appro_all", name="appro_delete_all1")
     */
    public function appro_delete_all(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $factureid = $data['factureid'];
        $shopid = $this->getUser()->getShopId();
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('DELETE FROM s_aprovisionements where id_ap = ? AND id_magasin = ?;');
        $res = $var->executeQuery([ $factureid, $shopid ]);
        $var = $em->getConnection()->prepare("DELETE FROM s_credit where factureid = ? AND id_magasin = ? AND type = 'achat';");
        $res = $var->executeQuery([ $factureid, $shopid ]);
        return new JsonResponse(["msg"=>"supprimé avec succès", "status"=>1, $data['factureid']]);
    }

    /**
     * @Route("/my/get/retour", name="retour_get_my")
     */
    public function retour_my_get(Request $request)
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare("select s_aprovisionements.id, amount, s_aprovisionements.product_id, dateposted, s_aprovisionements.user_id, fournisseur_user, buying_price, sell_price, total, id_ap, amount_restant, code_ap_name,
                                           s_aprovisionements.type, s_aprovisionements.status, s_aprovisionements.id_vente, motif,
                                           sv.quantite_vendu, sv.prix_unitaire, sv.remise_unite, sv.remise_totale
                                        from s_aprovisionements
                                        LEFT JOIN s_ventes sv on sv.id_vente = s_aprovisionements.id_vente
                                        where s_aprovisionements.type = 'retour' AND s_aprovisionements.id_magasin = ? ORDER BY s_aprovisionements.id DESC;");
        $res = $var->executeQuery([$this->getUser()->getShopId()])->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }

    /**
     * @Route("/my/post/retour", name="retour_post")
     */
    public function retour_register(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $em = $this->getDoctrine();
        $userid = $this->getUser()->getId();
        $shop_id = $this->getUser()->getShopId();
        $id_facture = 'RET-'.date("ymd-his", time());
        $var = $em->getConnection()->prepare('INSERT INTO s_aprovisionements
           (product_id, amount, id_ap, fournisseur_user, id_vente, motif, user_id, id_magasin, buying_price, sell_price, type) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);');
        $res = $var->executeQuery([ $data['product_id'], $data['amount'], $data['fournisseur_user'], $id_facture, $data['id_vente'],
            $data['motif'], $userid, $shop_id, $data['buying_price'] ?? null, $data['sell_price'] ?? null, 'retour' ]);
        return new JsonResponse(['msg'=>'Commande ajoutée avec succès', 'factureid'=> $id_facture]);
    }

}

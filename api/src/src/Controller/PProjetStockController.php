<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PProjetStockController extends AbstractController
{

    use JwtTrait;


    /**
     * @Route("/my/get/projet_stock/{projetid}", name="projet_stock_get")
     */
    public function stock($projetid) {
        $idmagasin =$this->getUser()->getShopId();
        $em = $this->getDoctrine();
        $var = $em->getConnection()
            ->prepare("SELECT p_projet_stock.id, product_id, quantite, annee, p_project_id, sp.id_magasin
                        from p_projet_stock
                        left join s_product sp on p_projet_stock.product_id = sp.id
                        where p_project_id = ? ORDER BY id DESC");
        $res = $var->execute([$projetid])->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);
        return new JsonResponse($res);
    }

    // stock
    /**
     * @Route("/my/post/projet_stock", name="projet_stock_post")
     */
    public function project_stock(Request $request) {
        $idmagasin = $this->getUser()->getShopId();
        $data = json_decode($request->getContent(),true);
        $em = $this->getDoctrine();
        $var = $em->getConnection()
            ->prepare('REPLACE INTO p_projet_stock
                (product_id, quantite, annee, p_project_id, id_magasin) VALUES (?,?,?,?,?)');
        $var->executeQuery([$data['product_id'], $data['quantite'], $data['annee'], $data['p_project_id'], $data['id_magasin']?? 1 ])->fetchAll();
        return new JsonResponse(['msg'=> 'La quantité utilisée a été enregistré']);
    }

    /**
     * @Route("/my/put/projet_stock", name="projet_stock_put")
     */
    public function project_stock_put(Request $request) {
        $idmagasin = $this->getUser()->getShopId();
        $data = json_decode($request->getContent(),true);
        $em = $this->getDoctrine();
        $var = $em->getConnection()
            ->prepare('REPLACE INTO p_projet_stock
                (id, product_id, quantite, annee, p_project_id, id_magasin) VALUES (?,?,?,?,?,?)');
        $var->executeQuery([$data['id'], $data['product_id'], $data['quantite'], $data['annee'], $data['p_project_id'], $data['id_magasin']?? 1 ])->fetchAll();
        return new JsonResponse(['msg'=> 'La quantité utilisée a été modifiée']);
    }

    /**
     * @Route("/my/delete/projet_stock", name="projet_stock_put")
     */
    public function project_stock_delete(Request $request) {
        $idmagasin = $this->getUser()->getShopId();
        $data = json_decode($request->getContent(),true);
        $em = $this->getDoctrine();
        $var = $em->getConnection()
            ->prepare('DELETE FROM p_projet_stock WHERE id = ?');
        $var->executeQuery([$data['id'] ])->fetchAll();
        return new JsonResponse(['msg'=> 'supprimé avec succès']);
    }


}

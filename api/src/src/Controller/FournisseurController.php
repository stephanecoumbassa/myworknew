<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FournisseurController extends AbstractController
{

    use JwtTrait;

    /**
     * @Route("/my/post/fournisseur", name="fournisseur_post")
     */
    public function fournisseurs_register(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        // return new JsonResponse($data);
        $name = $data["name"] ?? null;
        $type = $data["type"] ?? null;
        $lastname = $data["last_name"] ?? null;
        $code_telephone = $data["telephone_code"] ?? null;
        $telephone = $data["telephone"] ?? null;
        $email = $data["email"] ?? null;
        $password = $data["password"] ?? null;
        $shop_id = $this->getUser()->getShopId();

        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('INSERT INTO s_fournisseurs (name, last_name, telephone_code, telephone,
                                                                    email, password, type, id_magasin )
                                                    VALUES(?, ?, ?, ?,
                                                            ?, ?, ?, ?);');
        $var->executeQuery([$name, $lastname, $code_telephone, $telephone,
         $email, $password, $type, $shop_id]);

        return new JsonResponse(['status' => 1, 'msg'=>'fournisseur ajouté avec succès']);
    }

    /**
     * @Route("/my/put/fournisseur", name="fournisseur_put")
     */
    public function fournisseurs_update(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $name = $data["name"] ?? null;
        $type = $data["type"] ?? null;
        $lastname = $data["last_name"] ?? null;
        $code_telephone = $data["telephone_code"] ?? null;
        $telephone = $data["telephone"] ?? null;
        $email = $data["email"] ?? null;
        $password = $data["password"] ?? null;
        $id = $data["id"] ?? null;
        $shop_id = $this->getUser()->getShopId();

        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('UPDATE s_fournisseurs SET
        name = ?, last_name = ?, telephone_code = ?, telephone = ?,
         email = ?, password = ?, type = ?, id_magasin = ? WHERE id = ?');
        $var->executeQuery([$name, $lastname, $code_telephone, $telephone,
         $email, $password, $type, $shop_id, $id]);

        return new JsonResponse(['status' => 1, 'msg'=>'fournisseur Modifié avec succès']);
    }

    /**
     * @Route("/my/get/fournisseur", name="fournisseur_liste_get")
     */
    public function fournisseur_get(Request $request)
    {
        $shop_id = $this->getUser()->getShopId();
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare("SELECT *, concat(name, ' ', last_name) fullname FROM s_fournisseurs WHERE id_magasin = ?;");
        $res = $var->executeQuery([$this->getUser()->getShopId()])->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);

        return new JsonResponse($res);
    }


    /**
     * @Route("/my/get/fournisseur/{id}", name="fournisseur_get_one")
     */
    public function fournisseurs_get($id)
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare("SELECT *, concat(name, ' ', last_name) fullname FROM s_fournisseurs WHERE id = ? AND id_magasin = ?;");
        $res = $var->executeQuery([$id, $this->getUser()->getIdMagasin()])->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);

        return new JsonResponse($res);
    }

    /**
     * @Route("/my/delete/fournisseur/{id}", name="fournisseur_delete_one")
     */
    public function fournisseurs_delete($id)
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('DELETE FROM s_fournisseurs WHERE id = ? AND id_magasin = ?;');
        $var->executeQuery([$id, $this->getUser()->getIdMagasin()]);

        return new JsonResponse(['status' => 1, 'msg'=>'fournisseur Supprimé avec succès']);
    }

}

<?php

namespace App\Controller;

use App\Entity\SUsers;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ClientController extends AbstractController
{

    use JwtTrait;

    /**
     * @Route("/api/register/client", name="client_register_post")
     */
    public function clients_register(Request $request)
    {
        $data = json_decode($request->getContent(),true) ?? $_POST;
        $name = $data["name"] ?? null;
        $type = $data["type"] ?? null;
        $lastname = $data["last_name"] ?? null;
        $code_telephone = $data["telephone_code"] ?? 225;
        $telephone = $data["telephone"] ?? null;
        $email = $data["email"] ?? null;
        $password = $data["password"] ?? null;
//        $shop_id = $this->getUser()->getShopId();
        $shop_id = 1;
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('INSERT INTO s_clients (name, last_name, telephone_code, telephone, email, password, type, id_magasin )
                                                    VALUES(?, ?, ?, ?, ?, sha1(?), ?, ?);');
        $var->executeQuery([$name, $lastname, $code_telephone, $telephone,
            $email, $password, $type, $shop_id]);

        return new JsonResponse(['status' => 1, 'msg'=>'Client ajouté avec succès']);
    }

    /**
     * @Route("/api/connexion/client", name="client_connexion_post")
     */
    public function clients_connexion(Request $request)
    {
        $data = json_decode($request->getContent(),true) ?? $_POST;
        $email = $data["email"] ?? null;
        $password = $data["password"] ?? null;
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT * FROM s_clients WHERE (email=? OR telephone=?) AND password=sha1(?);');
        $res = $var->executeQuery([$email,$email, $password])->fetch();
        if(empty($res)) {
            $res['status'] = 0;
            $res['msg'] = 'Identifiant incorrect';
        }else{
            unset($res['password']);
            $res['status'] = 1;
            $res['msg'] = 'Connecté avec succès';
            $res['token'] = $this->token_generate($res['id'], $res);
        }

        return new JsonResponse($res);
    }

    /**
     * @Route("/my/post/client", name="client_post")
     */
    public function clients_post(Request $request)
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
        $var = $em->getConnection()->prepare('INSERT INTO s_clients (name, last_name, telephone_code, telephone,
                                                                    email, password, type, id_magasin )
                                                    VALUES(?, ?, ?, ?, ?, ?, ?, ?);');
        $var->executeQuery([$name, $lastname, $code_telephone, $telephone,
         $email, $password, $type, $shop_id]);
        // $res = $var->fetch();

        return new JsonResponse(['status' => 1, 'msg'=>'Client ajouté avec succès']);
    }

    /**
     * @Route("/my/put/client", name="client_put")
     */
    public function clients_update(Request $request)
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
        $id = $data["id"] ?? null;
        $shop_id = $this->getUser()->getShopId();

        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('UPDATE s_clients SET
        name = ?, last_name = ?, telephone_code = ?, telephone = ?,
         email = ?, password = ?, type = ?, id_magasin = ? WHERE id = ?');
        $var->executeQuery([$name, $lastname, $code_telephone, $telephone,
         $email, $password, $type, $shop_id, $id]);
        // $res = $var->fetch();

        return new JsonResponse(['status' => 1, 'msg'=>'Client Modifié avec succès']);
    }

    /**
     * @Route("/my/get/client", name="client_get1")
     */
    public function client_get(Request $request)
    {
        $shop_id = $this->getUser()->getShopId();
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare("SELECT id, name, last_name, telephone_code, telephone,
                                                    reference, email, type, id_magasin, password, roles, uuid, concat(name, ' ', last_name) fullname FROM s_clients WHERE id_magasin = ?;");
        $res = $var->executeQuery([$this->getUser()->getShopId()])->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);

        return new JsonResponse($res);
    }


    /**
     * @Route("/my/get/client/{id}", name="client_get_one")
     */
    public function clients_get($id)
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare("SELECT *, concat(name, ' ', last_name) fullname FROM s_clients WHERE id = ? AND id_magasin = ?;");
        $res = $var->executeQuery([$id, $this->getUser()->getIdMagasin()])->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);

        return new JsonResponse($res);
    }

    /**
     * @Route("/my/delete/client/{id}", name="client_delete_one")
     */
    public function clients_delete($id)
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('DELETE FROM s_clients WHERE id = ? AND id_magasin = ?;');
        $var->executeQuery([$id, $this->getUser()->getIdMagasin()]);
        return new JsonResponse(['status' => 1, 'msg'=>'Client Supprimé avec succès']);
    }

}

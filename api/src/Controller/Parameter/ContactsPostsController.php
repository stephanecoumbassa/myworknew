<?php

namespace App\Controller\Parameter;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ContactsPostsController extends BaseController
{

    /**
     * @Route("/api/contacts_posts_add", name="contacts_posts_add")
     */
    public function contacts_add(Request $request){

        $data = json_decode($request->getContent(),true);

        $params = [
            $email = $data['email'],
            $tel =  $data['tel'],
            $fax =  $data['fax']
        ];
//        return new JsonResponse($params);
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('INSERT INTO contacts_posts
                                    (email, telephone, fax) VALUES ( ?, ?, ?)');
        $var->execute($params);
        $res = $var->fetchAll($params);

        return new JsonResponse($res);

    }

    /**
     * @Route("/api/contacts_posts_get", name="contacts_posts_get")
     */
    public function contacts_get(Request $request){
        $params = [
            $id = (int) $_GET['id']
        ];
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT * FROM contacts_posts WHERE id = ?');
        $var->execute($params);
        $res = $var->fetchAll($params);

        return new JsonResponse($res);
    }


    /**
     * @Route("/api/get/contacts", name="admin_contacts_get")
     */
    public function contacts()
    {
        $typerubrique = $_GET['typerubrique'];
        $idligne = $_GET['idligne'];
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT contacts_get(?, ?) as contacts');
        $var->execute([$typerubrique, $idligne]);
        $res = $var->fetch();

        return new JsonResponse($res);
    }


    /**
     * @Route("/api/delete/contacts", name="admin_contacts_delete")
     */
    public function contacts_delete(Request $request)
    {
        $data = json_decode($request->getContent(),true);
//        return new JsonResponse($data['id']);
        $params = [
            $id = (int) $data['id']
        ];
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('DELETE FROM contacts_posts WHERE id = ?');
        $var->execute($params);
        $res = $var->fetchAll();

        return new JsonResponse($res);
    }


    /**
     * @Route("/api/put/contacts", name="admin_contacts_put")
     */
    public function contacts_put(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $params = [
            $email = $data['email'],
            $indicatif = $data['indicatif'],
            $telephone = $data['telephone'],
            $bp = $data['bp'],
            $fax = $data['fax'],
            $website = $data['website'],
            $id = (int)$data['id']
        ];

        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('CALL contacts_update(?, ?, ?, ?, ?, ?, ?);');
        $var->execute($params);
        $res = $var->fetch();

        return new JsonResponse($res);
    }

    /**
     * @Route("/api/post/contacts", name="admin_contacts_post")
     */
    public function contacts_post(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $params = [
            $contacts = json_encode($data['contacts'], true),
            $id = $data['id'],
            $typerubrique = $data['typerubrique']
        ];

        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('CALL contacts_add(?, ?, ?)');
        $var->execute($params);
        $res = $var->fetch();

        return new JsonResponse($res);
    }

}

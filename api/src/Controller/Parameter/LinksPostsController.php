<?php

namespace App\Controller\Parameter;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LinksPostsController extends BaseController
{

    /**
     * @Route("/api/get/links_type", name="admin_links_type_get")
     */
    public function links_type()
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT * from links_type ;');
        $var->execute();
        $res = $var->fetchAll();
        return new JsonResponse($res);
    }

    /**
     * @Route(path="/admin/links", name="pj_links_get")
     */
    public function pj_links_get(Request $request)
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT * FROM links_type');
        $var->execute();
        $res = $var->fetchAll();
        return new JsonResponse($res);
    }


    /**
     * @Route("/api/get/links", name="admin_links_get")
     */
    public function links()
    {
        $typerubrique = $_GET['typerubrique'];
        $idligne = $_GET['idligne'];
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT links_get(?, ?) as links');
        $var->execute([$typerubrique, $idligne]);
        $res = $var->fetch();

        return new JsonResponse($res);
    }

    /**
     * @Route("/api/post/links", name="admin_links_post")
     */
    public function links_post(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $params = [
            $links = json_encode($data['links'], true),
            $id = $data['id'],
            $typerubrique = $data['typerubrique']
        ];

        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('CALL links_add(?, ?, ?);');
        $var->execute($params);
        $res = $var->fetch();

        return new JsonResponse($res);
    }

    /**
     * @Route("/api/delete/links", name="admin_links_delete")
     */
    public function links_delete(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $params = [ $id = (int) $data['id'] ];
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('CALL links_delete_one(?);');
        $var->execute($params);
        $res = $var->fetch();

        return new JsonResponse($res);
    }

    /**
     * @Route("/api/put/links", name="admin_links_put")
     */
    public function links_put(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $params = [
            $name = $data['name'],
            $typeid = (int) $data['typeid'],
            $id = (int) $data['id']
        ];

        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('CALL links_update (?, ?, ?);');
        $var->execute($params);
        $res = $var->fetchAll();

        return new JsonResponse($res);
    }

}

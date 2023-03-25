<?php

namespace App\Controller\Parameter;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TagsController extends BaseController
{

    /**
     * @Route("/api/get/tags", name="admin_tags_get")
     */
    public function tags()
    {
        $typerubrique = $_GET['typerubrique'];
        $idligne = $_GET['id'];
        $em = $this->getDoctrine();
        //$var = $em->getConnection()->prepare('SELECT tags_get(?, ?) as tags');
        $var = $em->getConnection()->prepare('SELECT tags_get(?, ?) as tags');
        $var->execute([$typerubrique, $idligne]);
        $res = $var->fetch();

        return new JsonResponse($res);
    }

    /**
     * @Route("/api/put/tags", name="admin_tags_put")
     */
    public function tags_put(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $params = [
            $name = htmlentities($data['name']),
            $id = (int) $data['id']
        ];

        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('UPDATE tags_posts SET name = ? WHERE id = ?');
        $var->execute($params);
        $res = $var->fetchAll();

        return new JsonResponse($res);
    }

    /**
     * @Route("/api/post/tags", name="admin_tags_post")
     */
    public function tags_post(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $params = [
            $tags = htmlentities($data['tags']),
            $id = $data['id'],
            $typerubrique = $data['typerubrique']
        ];

        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('CALL tags_add(?, ?, ?)');
        $var->execute($params);
        $res = $var->fetch();

        return new JsonResponse($res);
    }

}

<?php

namespace App\Controller\Parameter;

use App\Controller\BaseController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends BaseController
{

    /**
     * @Route("/admin/comments", name="admin_comments")
     */
    public function comments(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT * FROM v_comments ORDER BY id DESC ');
        $var->execute();
        $res = $var->fetchAll();

        dump($res);

        $pagination = $paginator->paginate($res, $request->query->getInt('page', 1),100);
        return $this->render('admin/parameters/comments.html.twig', [
            'table_name' => 'comments',
            'pagination' => $pagination,
            'typerubrique' => $this->typerubrique
        ]);
    }

   /**
     * @Route("/admin/comments_list/{rubrique}/{type}", name="admin_comments_list")
     */
    public function comments_list(Request $request,PaginatorInterface $paginator, $rubrique, $type)
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT * FROM v_comments WHERE typerubrique_id = ? AND type_id = ? ORDER BY id DESC');
        $var->execute([$rubrique, $type]);
        $res = $var->fetchAll();
        dump($res);
        dump($type);
        dump($rubrique);
        $pagination = $paginator->paginate($res, $request->query->getInt('page', 1),100);
        return $this->render('admin/parameters/comments.html.twig', [
            'table_name' => 'comments',
            'pagination' => $pagination,
            'typerubrique' => $rubrique,
            'type' => $type
        ]);
    }


    /**
     * @Route("/api/get/comments_one", name="admin_comments_get")
     */
    public function comments_one()
    {
        $id = $_GET['id'];
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT * FROM v_comments WHERE id = ?;');
        $var->execute([$id]);
        $res = $var->fetch();

        return new JsonResponse($res);
    }

    /**
     * @Route("/api/get/comments", name="admin_comments_all_get")
     */
    public function comments_all()
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT * FROM v_comments ORDER BY id DESC;');
        $var->execute();
        $res = $var->fetchAll();
        return new JsonResponse($res);
    }

    /**
     * @Route("/api/get/comments_by_rubrique", name="admin_comments_rubrique_get")
     */
    public function comments_by_rubrique()
    {
        $typerubrique = $_GET['typerubrique'];
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT * FROM v_comments WHERE typerubrique_id = ? ORDER BY status;');
        $var->execute([$typerubrique]);
        $res = $var->fetchAll();
        return new JsonResponse($res);
    }

    /**
     * @Route("/api/get/comments_by_type", name="admin_comments_by_type_get")
     */
    public function comments_by_type()
    {
        $comment_type= $_GET['comment_type'];
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT * FROM v_comments WHERE type_id = ? ORDER BY status;');
        $var->execute([$comment_type]);
        $res = $var->fetchAll();
        return new JsonResponse($res);
    }

    /**
     * @Route("/api/get/comments_filter", name="admin_comments_filter_get")
     */
    public function comments_filter()
    {
        $comment_type = $_GET['comment_type'];
        $typerubrique = $_GET['typerubrique'];
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT * FROM v_comments 
                    WHERE typerubrique_id = ? AND type_id = ? ORDER BY status;');
        $var->execute([$typerubrique, $comment_type]);
        $res = $var->fetchAll();
        return new JsonResponse($res);
    }

    /**
     * @Route("/api/get/comments_type", name="admin_comments_type_get")
     */
    public function comments_type_all()
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT * FROM comments_type ORDER BY name DESC;');
        $var->execute();
        $res = $var->fetchAll();
        return new JsonResponse($res);
    }


    /**
     * @Route("/api/post/comments_one", name="admin_person_post")
     */
    public function comments_add(Request $request)
    {
        $data = json_decode($request->getContent(),true);

        $params = [
            $comment_type_id = (int)$data['comment_type_id'],
            $description = $data['description'],
            $babinaute_id = (int)$data['babinaute_id'],
            $typerubrique = (int)$data['typerubrique'],
            $id = (int)$data['id'],
            $ip = htmlentities($data['ip'])
        ];

        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('CALL comments_add(?, ?, ?, ?, ?, ?);');
        $var->execute($params);
        $res = $var->fetch();

        return new JsonResponse($res);
    }

    /**
     * @Route(path="/api/put/comments_status", name="comments_status")
     */
    public function comments_status(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $params = [
            (int) $data['status'],
            (int) $data['id']
        ];
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('comments_update_status(?, ?);');
        $var->execute($params);
        $res = $var->fetch();
        return new JsonResponse($res);
    }


    /**
     * @Route("/api/put/comments", name="admin_comments_put")
     */
    public function comments_update_one(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $params = [
            $description = htmlentities($data['description']),
            $id = (int)$data['id']
        ];

        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('CALL comments_update(?, ?);');
        $var->execute($params);
        $res = $var->fetch();

        return new JsonResponse($res);

    }



    /**
     * @Route("/api/get/comments_company", name="admin_comments_company")
     */
    public function comments_company()
    {
        $id = $_GET['id'];
        $typerubrique = $_GET['typerubrique'];
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT comments_get(?, ?);');
        $var->execute([$id, $typerubrique]);
        $res = $var->fetchAll();

        return new JsonResponse($res);
    }


    /**
     * @Route("/api/delete/comments/{id}", name="admin_delete_comments")
     */
    public function comments_company_delete($id)
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('CALL comments_delete_one(?);');
        $var->execute([$id]);
        $res = $var->fetchAll();

        return new JsonResponse($res);
    }


    /**
     * @Route(path="/api/put/revendication_assign", name="comments_assign")
     */
    public function comments_assign(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $params = [
            (int) $data['status'],
            (int) $data['id'],
            (int) $data['typerubrique'],
            (int) $data['idligne'],
            (int) $data['babinaute_id']
        ];
        // return new JsonResponse($params);
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('CALL comments_revedication_assign(?, ?, ?, ?, ?);');
        $var->execute($params);
        $res = $var->fetch();
        return new JsonResponse($res);
    }


    /**
     * @Route(path="/api/put/revendication_unassign", name="comments_unassign")
     */
    public function comments_unassign(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $params = [
            (int) $data['id'],
            (int) $data['typerubrique'],
            (int) $data['idligne'],
            (int) $data['babinaute_id']
        ];
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('CALL comments_revedication_unassign(?, ?, ?, ?);');
        $var->execute($params);
        $res = $var->fetch();
        return new JsonResponse($res);
    }

}

<?php

namespace App\Controller\Parameter;

use App\Controller\BaseController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class NoteController extends BaseController
{

    /**
     * @Route("/admin/notes", name="admin_notes")
     */
    public function notes(Request $request,PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT * FROM v_notes ORDER BY id DESC ');
        $var->execute();
        $res = $var->fetchAll();

        $pagination = $paginator->paginate($res, $request->query->getInt('page', 1),100);
        return $this->render('admin/parameters/notes.html.twig', [
            'table_name' => 'notes',
            'pagination' => $pagination,
            'typerubrique' => $this->typerubrique
        ]);
    }


    /**
     * @Route("/admin/notes/{rubrique}", name="admin_notes_list")
     */
    public function notes_list(Request $request,PaginatorInterface $paginator, $rubrique)
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT * FROM v_notes where typerubrique_id = ? ORDER BY id DESC');
        $var->execute([$rubrique]);
        $res = $var->fetchAll();

        dump($res);

        $pagination = $paginator->paginate($res, $request->query->getInt('page', 1),100);
        return $this->render('admin/parameters/notes.html.twig', [
            'table_name' => 'notes',
            'pagination' => $pagination,
            'typerubrique' => $rubrique
        ]);
    }

    /**
     * @Route("/api/get/notes_one", name="admin_notes_get")
     */
    public function notes_one()
    {
        $id = $_GET['id'];
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT * FROM v_notes WHERE id = ?;');
        $var->execute([$id]);
        $res = $var->fetch();

        return new JsonResponse($res);
    }

    /**
     * @Route("/api/get/notes", name="admin_notes_all_get")
     */
    public function notes_all()
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT * FROM v_notes ORDER BY id DESC;');
        $var->execute();
        $res = $var->fetchAll();
        return new JsonResponse($res);
    }


    /**
     * @Route("/api/post/notes_one", name="admin_person_post")
     */
    public function notes_add(Request $request)
    {
        $data = json_decode($request->getContent(),true);

        $params = [
            $note = (int)$data['note'],
            $description = $data['description'],
            $typerubrique = (int)$data['typerubrique'],
            $id = (int)$data['id'],
            $babinaute_id = (int)$data['babinaute_id'],
            $ip = htmlentities($data['ip']),
        ];

        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('CALL notes_add(?, ?, ?, ?, ?, ?);');
        $var->execute($params);
        $res = $var->fetch();

        return new JsonResponse($res);
    }

    /**
     * @Route(path="/api/put/notes_status", name="notes_status")
     */
    public function notes_status(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $params = [ (int) $data['status'], (int) $data['id'] ];
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('CALL notes_update_status(?, ?);');
        $var->execute($params);
        $res = $var->fetch();
        return new JsonResponse($res);
    }


    /**
     * @Route("/api/put/notes", name="admin_note_put")
     */
    public function notes_update_one(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $params = [
            $note = (int)$data['note'],
            $description = htmlentities($data['description']),
            $id = (int)$data['id']
        ];

        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('CALL notes_update(?, ?, ?);');
        $var->execute($params);
        $res = $var->fetch();

        return new JsonResponse($res);

    }



    /**
     * @Route("/api/get/notes_company", name="admin_notes_company")
     */
    public function notes_company()
    {
        $id = $_GET['id'];
        $typerubrique = $_GET['typerubrique'];
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT notes_get(?, ?);');
        $var->execute([$id, $typerubrique]);
        $res = $var->fetchAll();

        return new JsonResponse($res);
    }


    /**
     * @Route("/api/delete/notes/{id}", name="admin_delete_notes")
     */
    public function notes_company_delete($id)
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('CALL notes_delete_one(?);');
        $var->execute([$id]);
        $res = $var->fetchAll();

        return new JsonResponse($res);
    }

}

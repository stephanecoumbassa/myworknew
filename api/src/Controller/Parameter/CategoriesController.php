<?php

namespace App\Controller\Parameter;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoriesController extends BaseController
{

    /**
     * @Route("/api/get/categories_master", name="admin_categories_master")
     */
    public function categories_master()
    {
        $id = 1;
        if (isset($_GET['id'])){
            $id = $_GET['id'];
        }
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT * FROM categories_master WHERE typerubrique_id = ?');
        $var->execute([$id]);
        $res = $var->fetchAll();

        if (isset($_GET['id'])){
            return new JsonResponse($res);
        }

        return $this->render('admin/parameters/categories_master.html.twig', [
            'data' => $res,
            'table_name' => 'categories_posts'
        ]);

    }

    /**
     * @Route("/api/get/categories", name="admin_categories")
     */
    public function categories()
    {
        $id = 1;
        if (isset($_GET['id'])){
            $id = $_GET['id'];
        }
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT * FROM categories_posts WHERE typerubrique_id = ?');
        $var->execute([$id]);
        $res = $var->fetchAll();

        if (isset($_GET['id'])){
            return new JsonResponse($res);
        }

        return $this->render('admin/parameters/categories.html.twig', [
            'data' => $res,
            'table_name' => 'categories_posts'
        ]);

    }



    /**
     * @Route("/api/get/categories_by_master", name="admin_categories_by_master")
     */
    public function categories_by_master(Request $request)
    {

        $id = $_GET['id'];
        $typerubrique = $_GET['typerubrique'];


            $em = $this->getDoctrine();
            $var = $em->getConnection()->prepare('SELECT * FROM cms4.categories_posts 
                        WHERE categories_master_id = ? AND typerubrique_id = ?');
            $var->execute([$id, $typerubrique]);
            $res = $var->fetchAll();
            return new JsonResponse($res);

    }

    /**
     * @Route("/api/post/categories", name="admin_categories_add")
     */
    public function categories_add(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $image_name = $data['image_name'];
        $extension = $data['image_extension'];
        $img = $data['image'];// Your data 'data:image/png;base64,AAAFBfj42Pj4';
        $url_photo = $image_name;
        $id = $_GET['id'];
        $typerubrique = $_GET['typerubrique'];
        $name = (string) $typerubrique.'_'.$id.'_categories';

        if($this->uploadApi($request,  $name,'uploads/categories/') === true){
            $params = [
                $name = $data['name'],
                $categories_master_id = $data['categories_master_id'],
                $typerubrique = $data['typerubrique']
            ];

            $em = $this->getDoctrine();
            $var = $em->getConnection()->prepare('CALL categories_add(?, ?, ?)');
            $var->execute($params);
            $res = $var->fetchAll();
            return new JsonResponse($res);
        }

        return new JsonResponse('impossible d\'envoyer cette icone');
    }

    /**
     * @Route("/api/add/categories_master", name="admin_categories_master_add")
     */
    public function categories_master_add(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $image_name = $data['image_name'];
        $extension = $data['image_extension'];
        $img = $data['image'];// Your data 'data:image/png;base64,AAAFBfj42Pj4';
        $url_photo = $image_name;
        $name = $data['typerubrique'].'_'.uniqid('master_', 0).'.'.$extension;

        if($data['image'] == null){
            $params = [
                $title = $data['name'],
                $description = $data['description'],
                $icon = null,
                $typerubrique = $data['typerubrique']
            ];

            $em = $this->getDoctrine();
            $var = $em->getConnection()->prepare('CALL categories_master_add(?, ?, ?, ?)');
            $var->execute($params);
            $res = $var->fetch();

            return new JsonResponse($res);
        }

        if($this->uploadApi($request,  $name,'uploads/categories/') === true){
            $params = [
                $title = $data['name'],
                $description = $data['description'],
                $icon = $name,
                $typerubrique = $data['typerubrique']
            ];

            $em = $this->getDoctrine();
            $var = $em->getConnection()->prepare('CALL categories_master_add(?, ?, ?, ?)');
            $var->execute($params);
            $res = $var->fetchAll();

            return new JsonResponse($res);
        }

        return new JsonResponse('impossible d\'envoyer cette icone');
    }


    /**
     * @Route("/api/put/categories_master", name="admin_categories_master_put")
     */
    public function categories_master_update(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $image_name = $data['image_name'];
        $extension = $data['image_extension'];
        $img = $data['image'];// Your data 'data:image/png;base64,AAAFBfj42Pj4';
        $url_photo = $image_name;
        $icon_name = $data['icon'];

        $name = null;
        if ($icon_name == null){
            $name = $data['typerubrique_id'].'_'.uniqid('master_', 0).'.'.$extension;
        }else{
            $name = $data['icon'];
        }

        $title = $data['name'];
        $description = $data['description'];
        $id = $data['id'];
        $typerubrique_id = $data['typerubrique_id'];


        if ($img == null || $extension === ''){
            $em = $this->getDoctrine();
            $var = $em->getConnection()->prepare('UPDATE categories_master SET name = ?, description = ? WHERE id = ?');
            $var->execute([$title, $description, $id]);
//            $res = $var->fetchAll();
            return new JsonResponse('Update OK');
        }

        if($this->uploadApi($request,  $name,'uploads/categories/') === true) {
            $em = $this->getDoctrine();
            $var = $em->getConnection()->prepare('CALL categories_master_update(?, ?, ?, ?, ?)');
            $var->execute([$title, $description, $name, $id, $typerubrique_id]);
            $res = $var->fetchAll();
            return new JsonResponse($res);
        }

        return new JsonResponse('impossible d\'envoyer cette icone');
    }


    /**
     * @Route("/api/put/categories", name="admin_categories_put")
     */
    public function categories_update(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $image_name = $data['image_name'];
        $extension = $data['image_extension'];
        $img = $data['image'];// Your data 'data:image/png;base64,AAAFBfj42Pj4';
        $url_photo = $image_name;
        $icon_name = $data['icon'];

        $name = null;
        if ($icon_name == null){
            $name = $data['typerubrique_id'].'_'.uniqid('master_', 0).'.'.$extension;
        }else{
            $name = $data['icon'];
        }

        $title = $data['name'];
        $description = $data['description'];
        $id = $data['id'];
        $categories_master_id = $data['categories_master_id'];
        $typerubrique_id = $data['typerubrique_id'];


        if ($img == null || $extension === ''){
            $em = $this->getDoctrine();
            $var = $em->getConnection()->prepare('UPDATE categories_posts SET name = ?, description = ?, categories_master_id = ? WHERE id = ?');
            $var->execute([$title, $description, $categories_master_id, $id]);
//            $res = $var->fetchAll();
            return new JsonResponse('Update OK');
        }

        if($this->uploadApi($request,  $name,'uploads/categories/') === true) {
            $em = $this->getDoctrine();
            $var = $em->getConnection()->prepare('CALL categories_posts_update(?, ?, ?, ?, ?, ?)');
            $var->execute([$title, $description, $name, $categories_master_id, $id, $typerubrique_id]);
            $res = $var->fetchAll();
            return new JsonResponse($res);
        }

        return new JsonResponse('impossible d\'envoyer cette icone');
    }

}

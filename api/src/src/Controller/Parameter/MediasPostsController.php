<?php

namespace App\Controller\Parameter;

use App\Controller\BaseController;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class MediasPostsController extends BaseController
{
    public $uploadDir;
    public function __construct(SessionInterface $session)
    {
        parent::__construct($session);
        // $magasinid = $this->getUser()->getShopId();
        // $this->uploadDir = $_SERVER['DOCUMENT_ROOT'].'/apistock/assets/uploads/'.$magasinid .'/';
        $this->uploadDir = $_SERVER['DOCUMENT_ROOT'].'/apistock/public/assets/uploads/';
    }

    /**
     * @Route("/api/update/medias", name="admin_medias_update")
     */
    public function medias_update(Request $request)
    {

        $data = json_decode($request->getContent(),true);

        $image_name = $data['image_name'];
        $extension = $data['image_extension'];
        $img = $data['image'];// Your data 'data:image/png;base64,AAAFBfj42Pj4';
        $url_photo = $image_name;

        if($this->uploadApi($request, $image_name,'uploads/articles/pensee/') === true){

            $params = [
                $name = $data['image_name'],
                $id = $data['id'],
                $typerubrique = $data['typerubrique']
            ];

            $em = $this->getDoctrine();
            $var = $em->getConnection()->prepare('CALL media_update(?, ?, ?)');
            $res = $var->executeQuery($params)->fetchAll();
            return new JsonResponse($res);
        }

        return new JsonResponse(["Impossible d'ajouter ce fichier"]);

    }



    /**
     * @Route("/api/get/medias", name="admin_medias_get")
     */
    public function medias()
    {
        $typerubrique = $_GET['typerubrique'];
        $idligne = $_GET['id'];
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT media_get(?, ?) as medias');
        $res = $var->executeQuery([$typerubrique, $idligne])->fetch();
        return new JsonResponse($res);
    }

    /**
     * @Route("/api/get/medias_type", name="admin_medias_type_get")
     */
    public function medias_type()
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('CALL media_type_get()');
        $res = $var->executeQuery()->fetchAll();
        return new JsonResponse($res);
    }

    /**
     * @Route("/api/delete/medias", name="admin_medias_delete")
     */
    public function medias_delete(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $folder = $data['folder'];
        $params = [
            $id = (int) $data['id'],
            $filename = $data['filename']
        ];

        $filesystem = new Filesystem();
        $uploadDir =  $this->getParameter('kernel.project_dir').'/public/uploads/articles/';
        $folder = $this->uploadDir.$data['folder'];
        $file = $this->uploadDir.$data['folder'].$data['filename'];
        try {
            $filesystem->remove( $file);
            $em = $this->getDoctrine();
            $var = $em->getConnection()->prepare('DELETE FROM `media_posts` WHERE id = ? AND name = ?');
            $var->executeQuery($params);
            return new JsonResponse(['id'=> (int) $data['idligne'],
                'typerubrique'=> (int) $data['typerubrique'], 'msg' => 'supprime avec succès']);
        } catch (IOExceptionInterface $exception) {
            echo "An error occurred while creating your directory at ".$exception->getPath();
        }

        return new JsonResponse($id, $filename, $folder);
//        return new JsonResponse($res);
    }

    /**
     * @Route("/api/post/medias", name="admin_medias_post")
     */
    public function medias_post(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $folder = $data['folder'] ?? null;
        $image_name = $data['image_name'] ?? null;
        $extension = $data['image_extension'] ?? null;
        $type = $data['type'] ?? null;
        $img = $data['image'] ?? null;// Your data 'data:image/png;base64,AAAFBfj42Pj4';
        $image_name1 = $data['image_name1'] ?? null;
        $extension1 = $data['image_extension1'] ?? null;
        $type2 = $data['type2'] ?? null;
        $img1 = $data['image1'] ?? null;// Your data 'data:image/png;base64,AAAFBfj42Pj4';
        $image_name2 = $data['image_name2'] ?? null;
        $extension2 = $data['image_extension2'] ?? null;
        $img2 = $data['image2'] ?? null;// Your data 'data:image/png;base64,AAAFBfj42Pj4';
        $type3 = $data['type3'] ?? null;

        $em = $this->getDoctrine();

        if( $img !== null && $this->uploadApi2($img, $extension, '_'.$data['id'].'_'.$image_name, 'uploads/articles/' .$folder)){
            $params = [
                '_'.$data['id'].'_'.$image_name, $type, $data['id'], $typerubrique = $data['typerubrique']
            ];
            $var = $em->getConnection()->prepare('CALL media_add_one(?, ?, ?, ?)');
            $res = $var->executeQuery($params)->fetch();
        }
        if( $img1 !== null && $this->uploadApi2($img1, $extension1, '_'.$data['id'].'_'.$image_name1, 'uploads/articles/' .$folder)){
            $params = [
                '_'.$data['id'].'_'.$image_name1, $type2, $data['id'], $typerubrique = $data['typerubrique']
            ];
            $var = $em->getConnection()->prepare('CALL media_add_one(?, ?, ?)');
            $res1 = $var->executeQuery($params)->fetch();
        }
        if ( $img2 !== null && $this->uploadApi2($img2, $extension2, '_'.$data['id'].'_'.$image_name2, 'uploads/articles/' .$folder)){
            $params = [
                '_'.$data['id'].'_'.$image_name2, $type2, $data['id'], $typerubrique = $data['typerubrique']
            ];
            $var = $em->getConnection()->prepare('CALL media_add_one(?, ?, ?)');
            $res2 = $var->executeQuery($params)->fetch();
        }

        return new JsonResponse(['id' =>$data['id'], 'typerubrique'=>$data['typerubrique'] ]);
    }

    /**
     * @Route("/api/post/medias_one", name="admin_medias_post_one")
     */
    public function medias_post_one(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $folder = './shop/'.$data['folder'].'/';
//        $folder = $this->uploadDir.$data['folder'];
        return new JsonResponse($folder);
        $extension = $data['image_extension'];
        $image_name = uniqid(1, false).'.'.$extension;
        $type = $data['type'];
        $img = $data['image'] ?? null;// Your data 'data:image/png;base64,AAAFBfj42Pj4';

        if( $img !== null && $this->uploadApi2($img, $extension,
                '_'.$data['id'].'_'.$image_name, $folder)){
            $em = $this->getDoctrine();
            $params = [
                '_'.$data['id'].'_'.$image_name, $type, $data['id'], $typerubrique = $data['typerubrique']
            ];
            $var = $em->getConnection()->prepare('CALL media_add_one(?, ?, ?, ?)');
            $res = $var->executeQuery($params)->fetch();
            return new JsonResponse($res);
        }

        return new JsonResponse(['id' =>$data['id'], 'typerubrique'=>$data['typerubrique'] ]);
    }

    /**
     * @Route("/my/post/medias_one", name="my_admin_medias_post_one")
     */
    public function medias_post_one_my(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $shop_id = $this->getUser()->getShopId();
        $folder = "./shop/$shop_id/".$data['folder'].'/';
//    return new Response($folder);
        $extension = $data['image_extension'];
        $image_name = uniqid(1, false).'.'.$extension;
        $type = $data['type'];
        $img = $data['image'] ?? null;// Your data 'data:image/png;base64,AAAFBfj42Pj4';

        if( $img !== null && $this->uploadApi2($img, $extension,
                '_'.$data['id'].'_'.$image_name, $folder)){
            $em = $this->getDoctrine();
            $params = [
                '_'.$data['id'].'_'.$image_name, $type, $data['id'], $typerubrique = $data['typerubrique']
            ];
            $var = $em->getConnection()->prepare('CALL media_add_one(?, ?, ?, ?)');
            $res = $var->executeQuery($params)->fetch();
            return new JsonResponse($res);
        }

        return new JsonResponse(['id' =>$data['id'], 'typerubrique'=>$data['typerubrique'] ]);
    }

}

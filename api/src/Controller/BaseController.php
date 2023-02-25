<?php

/**
 * Created by IntelliJ IDEA.
 * User: comptable
 * Date: 2019-04-04
 * Time: 17:27
 */

namespace App\Controller;

// ini_set('memory_limit', '1024M');

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BaseController extends AbstractController
{
    protected $session;
    protected $userlogsRepository;
    protected $typerubrique;
    protected $uploadDir;
    public $uploadShop = 'https://affairez.com/apistock/public/shop/';

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
        $this->uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/apistock/public/assets/uploads/';
//        if ($this->session->get('complete_login') == false) {
//            dump($this->session->get('complete_login'));
//            header('Location: /cms/logout');
//            die();
//        }
        //dump($this->session);
    }

    public function uploadApi(Request $request, string $image_name = null, string $directory_upload = 'uploads/articles/')
    {
        $post = json_decode($request->getContent(), true);
        $extension = $post['image_extension'];
        $img = $post['image']; // Your data 'data:image/png;base64,AAAFBfj42Pj4';

        //        $url_photo = $image.'.'.$extension;
        if ($image_name === null) {
            $image_name = uniqid('', true) . '.' . $extension;
        }

        $url_photo = $this->uploadDir . $directory_upload . $image_name;

        if ($extension === 'jpg' || $extension === 'jpeg') {
            $img = str_replace('data:image/jpeg;base64,', '', $img);
        } elseif ($extension === 'png') {
            $img = str_replace('data:image/png;base64,', '', $img);
        } elseif ($extension === 'svg') {
            $img = str_replace('data:image/svg+xml;base64,', '', $img);
        }

        $img = str_replace(' ', '+', $img);
        $data = base64_decode($img);

        if (file_put_contents($url_photo, $data) !== false) {
            return true;
        }
    }

    public function uploadApi2(
        string $img,
        string $extension,
        string $image_name,
        string $directory_upload
    ) {
        if ($image_name === null) {
            $image_name = uniqid('', true) . '.' . $extension;
        }
        $url_photo = $directory_upload . $image_name;

        if ($extension === 'jpg' || $extension === 'jpeg') {
            $img = str_replace('data:image/jpeg;base64,', '', $img);
        } elseif ($extension === 'png') {
            $img = str_replace('data:image/png;base64,', '', $img);
        }

        $img = str_replace(' ', '+', $img);
        $data = base64_decode($img);

        if (file_put_contents($url_photo, $data) !== false) {
            return true;
        }
    }

    public function image_upload(array $data, string $_folder, string $_image_name, int $id, string $sql_update){
//        $folder = $_SERVER['DOCUMENT_ROOT'] . '/api/public/assets/uploads'.$_folder.'/';
//        $folder = './assets/uploads'.$_folder.'/';
        $folder = './shop'.$_folder.'/';

        $extension = $data['image_extension'];
        $img = (string)$data['image'] ?? null;
        $image_name = $_image_name. '.' . $extension;

        $em = $this->getDoctrine();
        if (($img !== null || $img !== '') && $this->uploadApi2($img, $extension, $image_name, $folder)) {
            // $var = $em->getConnection()->prepare('UPDATE actualites_dossiers SET urlphotodossier = ? WHERE id = ?;');
            $var = $em->getConnection()->prepare($sql_update);
            $var->executeQuery([$image_name, (int) $id]);
        }
    }

    public function uploadDoc( $img, string $extension, string $image_name, string $directory_upload ) {
        if ($image_name === null) {
            $image_name = uniqid('', true) . '.' . $extension;
        }
        $url_photo = $directory_upload . $image_name;

        $img = str_replace(' ', '+', $img);
        $data = base64_decode($img);

        if($image_name != null){
            if($image_name != ''){
                if(!is_dir($url_photo)){
                    if (file_put_contents($url_photo, $data) !== false) {
                        return true;
                    }
                }
            }
        }
    }


    public function generate_json(array $data, string $filename)
    {
        file_put_contents(
            "$filename",
            json_encode($data, true)
        );
    }

    public function generate_json_one_by_one(array $res, string $filename, $idname)
    {
        foreach ($res as $re) {
            if (!file_exists($filename . $re["$idname"] . '.json')) {
                $this->generate_json($re, $filename . $re["$idname"] . '.json');
            }
        }
    }

    public function generate_delete_json($filename, $id)
    {
        if (file_exists($filename . $id . '.json')) {
            $myFile = $filename . $id . '.json';
            unlink($myFile) or  dump("Couldn't delete file");
        }
    }


    public function slugify($string, $delimiter = '-')
    {
        $oldLocale = setlocale(LC_ALL, '0');
        setlocale(LC_ALL, 'en_US.UTF-8');
        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = strtolower($clean);
        //        $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
        //        $clean = trim($clean, $delimiter);
        setlocale(LC_ALL, $oldLocale);
        return $clean;
    }


}

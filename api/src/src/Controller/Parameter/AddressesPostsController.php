<?php

namespace App\Controller\Parameter;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AddressesPostsController extends BaseController
{

    /**
     * @Route("/api/get/addresses", name="admin_addresses_get")
     */
    public function addresses()
    {
        $typerubrique = $_GET['typerubrique'];
        $idligne = $_GET['idligne'];
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT addresses_get(?, ?) as addresses');
        $var->execute([$typerubrique, $idligne]);
        $res = $var->fetch();

        return new JsonResponse($res);
    }

    /**
     * @Route("/api/delete/addresses", name="admin_addresses_delete")
     */
    public function addresses_delete(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $params = [ $id = (int) $data['id'] ];
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('CALL addresses_delete_one(?);');
        $var->execute($params);
        $res = $var->fetch();

        return new JsonResponse($res);
    }

    /**
     * @Route("/api/put/addresses", name="admin_addresses_put")
     */
    public function addresses_put(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $params = [
            $country_id = (int)$data['country_id'],
            $city_id = (int)$data['city_id'],
            $quartier_id = (int)$data['quartier_id'],
            $longitude = (float)$data['longitude'],
            $latitude = (float)$data['latitude'],
            $description = $data['description'],
            $id = (int) $data['id']
        ];

        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('CALL addresses_update(?, ?, ?, ?, ?, ?, ?);');
        $var->execute($params);
        $res = $var->fetch();

        return new JsonResponse($res);
    }

    /**
     * @Route("/api/post/addresses", name="admin_addresses_post")
     */
    public function addresses_post(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $params = [
            $addresses = json_encode($data['addresses'], true),
            $id = $data['id'],
            $typerubrique = $data['typerubrique']
        ];

        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('CALL addresses_add(?, ?, ?)');
        $var->execute($params);
        $res = $var->fetch();

        return new JsonResponse($res);
    }

}

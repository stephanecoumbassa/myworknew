<?php

namespace App\Controller\Parameter;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CityController extends BaseController
{

    /**
     * @Route("/api/get/cities", name="admin_cities_api_get")
     */
    public function city()
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT * FROM v_city');
        $var->execute();
        $res = $var->fetchAll();

        return new JsonResponse($res);
    }



    /**
     * @Route("/api/get/cities_list", name="admin_cities_list_get")
     */
    public function city_list()
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT * FROM city');
        $var->execute();
        $res = $var->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);

        return new JsonResponse($res);
    }
}

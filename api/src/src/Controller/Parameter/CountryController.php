<?php

namespace App\Controller\Parameter;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CountryController extends BaseController
{
    /**
     * @Route("/admin/get/country", name="admin_country")
     */
    public function country()
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT * FROM v_country');
        $var->execute();
        $res = $var->fetchAll();

        return $this->render('admin/parameters/country.html.twig', [
            'data' => $res,
            'table_name' => 'country'
        ]);
    }

    /**
     * @Route("/api/get/country", name="admin_country_get")
     */
    public function country_get()
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT * FROM v_country');
        $var->execute();
        $res = $var->fetchAll();

        return new JsonResponse($res);
    }


    /**
     * @Route("/api/get/countries", name="admin_countries_get")
     */
    public function countries_get()
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT * FROM country');
        $var->execute();
        $res = $var->fetchAll();

        return new JsonResponse($res);
    }


    /**
     * @Route("/api/post/countries", name="admin_countries_post")
     */
    public function countries_post(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $params = [
            $countries = json_encode($data['countries'], true)
        ];

        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('CALL country_add(?)');
        $var->execute($params);
        $res = $var->fetchAll();

        return new JsonResponse($res);
    }
}

<?php

namespace App\Controller\Parameter;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class QuartierController extends BaseController
{
    /**
     * @Route("/admin/quartiers", name="admin_quartier_get")
     */
    public function quartier_get()
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT * FROM v_quartier');
        $var->execute();
        $res = $var->fetchAll();

        return $this->render('admin/parameters/quartier.html.twig', [
            'data' => $res,
            'table_name' => 'city'
        ]);
    }

    /**
     * @Route("/api/get/quartiers", name="admin_quartier")
     */
    public function quartier()
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT * FROM v_quartier');
        $var->execute();
        $res = $var->fetchAll();

        return new JsonResponse($res);
    }
}

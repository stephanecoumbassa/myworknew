<?php
    namespace App\Controller;

    use App\Controller\BaseController;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Annotation\Route;
    
    class PStaskRelationController extends BaseController {        /**
         * @Route("/api/get/p_stask/p_assignation/{id}", name="p_stask_p_assignation_get")
         */
        public function p_assignation_p_stask_get($id) :JsonResponse
        {
            $em = $this->getDoctrine();
            $var = $em->getConnection()->prepare(
            'SELECT * FROM p_assignation t LEFT JOIN p_stask rt on rt.id = t.p_stask_id
            WHERE rt.p_stask_id = ?'
            );
            $var->execute([$id]);
            $res = $var->fetchAll();
            return new JsonResponse($res);
        }
    
        /**
         * @Route("/api/delete/p_stask/p_stask/{id}", name="p_stask_p_assignation_delete")
         */
        public function p_assignation_p_stask_delete($id)
        {
            $em = $this->getDoctrine();
            // $var = $em->getConnection()->prepare('call p_stask_delete(?);');
            $var = $em->getConnection()->prepare('DELETE FROM p_stask_delete WHERE p_stask_id = ?;');
            $var->execute([$id]);
            $res = $var->fetch();
            return new JsonResponse($res);
        }           
}
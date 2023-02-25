<?php
    namespace App\Controller;

    use App\Controller\BaseController;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Annotation\Route;
    
    class PTaskRelationController extends BaseController {        /**
         * @Route("/api/get/p_task/p_assignation/{id}", name="p_task_p_assignation_get")
         */
        public function p_assignation_p_task_get($id) :JsonResponse
        {
            $em = $this->getDoctrine();
            $var = $em->getConnection()->prepare(
            'SELECT * FROM p_assignation t LEFT JOIN p_task rt on rt.id = t.p_task_id
            WHERE rt.p_task_id = ?'
            );
            $var->execute([$id]);
            $res = $var->fetchAll();
            return new JsonResponse($res);
        }
    
        /**
         * @Route("/api/delete/p_task/p_task/{id}", name="p_task_p_assignation_delete")
         */
        public function p_assignation_p_task_delete($id)
        {
            $em = $this->getDoctrine();
            // $var = $em->getConnection()->prepare('call p_task_delete(?);');
            $var = $em->getConnection()->prepare('DELETE FROM p_task_delete WHERE p_task_id = ?;');
            $var->execute([$id]);
            $res = $var->fetch();
            return new JsonResponse($res);
        }           
        /**
         * @Route("/api/get/p_task/p_stask/{id}", name="p_task_p_stask_get")
         */
        public function p_stask_p_task_get($id) :JsonResponse
        {
            $em = $this->getDoctrine();
            $var = $em->getConnection()->prepare(
            'SELECT * FROM p_stask t LEFT JOIN p_task rt on rt.id = t.p_task_id
            WHERE rt.p_task_id = ?'
            );
            $var->execute([$id]);
            $res = $var->fetchAll();
            return new JsonResponse($res);
        }
    
        /**
         * @Route("/api/delete/p_task/p_task/{id}", name="p_task_p_stask_delete")
         */
        public function p_stask_p_task_delete($id)
        {
            $em = $this->getDoctrine();
            // $var = $em->getConnection()->prepare('call p_task_delete(?);');
            $var = $em->getConnection()->prepare('DELETE FROM p_task_delete WHERE p_task_id = ?;');
            $var->execute([$id]);
            $res = $var->fetch();
            return new JsonResponse($res);
        }           
}
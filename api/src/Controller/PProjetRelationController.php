<?php
    namespace App\Controller;

    use App\Controller\BaseController;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Annotation\Route;
    
    class PProjetRelationController extends BaseController {        /**
         * @Route("/api/get/p_projet/p_fichier/{id}", name="p_projet_p_fichier_get")
         */
        public function p_fichier_p_projet_get($id) :JsonResponse
        {
            $em = $this->getDoctrine();
            $var = $em->getConnection()->prepare(
            'SELECT * FROM p_fichier t LEFT JOIN p_projet rt on rt.id = t.p_projet_id
            WHERE rt.p_projet_id = ?'
            );
            $var->execute([$id]);
            $res = $var->fetchAll();
            return new JsonResponse($res);
        }
    
        /**
         * @Route("/api/delete/p_projet/p_projet/{id}", name="p_projet_p_fichier_delete")
         */
        public function p_fichier_p_projet_delete($id)
        {
            $em = $this->getDoctrine();
            // $var = $em->getConnection()->prepare('call p_projet_delete(?);');
            $var = $em->getConnection()->prepare('DELETE FROM p_projet_delete WHERE p_projet_id = ?;');
            $var->execute([$id]);
            $res = $var->fetch();
            return new JsonResponse($res);
        }           
        /**
         * @Route("/api/get/p_projet/p_task/{id}", name="p_projet_p_task_get")
         */
        public function p_task_p_projet_get($id) :JsonResponse
        {
            $em = $this->getDoctrine();
            $var = $em->getConnection()->prepare(
            'SELECT * FROM p_task t LEFT JOIN p_projet rt on rt.id = t.p_projet_id
            WHERE rt.p_projet_id = ?'
            );
            $var->execute([$id]);
            $res = $var->fetchAll();
            return new JsonResponse($res);
        }
    
        /**
         * @Route("/api/delete/p_projet/p_projet/{id}", name="p_projet_p_task_delete")
         */
        public function p_task_p_projet_delete($id)
        {
            $em = $this->getDoctrine();
            // $var = $em->getConnection()->prepare('call p_projet_delete(?);');
            $var = $em->getConnection()->prepare('DELETE FROM p_projet_delete WHERE p_projet_id = ?;');
            $var->execute([$id]);
            $res = $var->fetch();
            return new JsonResponse($res);
        }           
}
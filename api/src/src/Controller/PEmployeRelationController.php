<?php
    namespace App\Controller;

    use App\Controller\BaseController;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Annotation\Route;
    
    class PEmployeRelationController extends BaseController {        /**
         * @Route("/api/get/p_employe/p_absence/{id}", name="p_employe_p_absence_get")
         */
        public function p_absence_p_employe_get($id) :JsonResponse
        {
            $em = $this->getDoctrine();
            $var = $em->getConnection()->prepare(
            'SELECT * FROM p_absence t LEFT JOIN p_employe rt on rt.id = t.p_employe_id
            WHERE rt.p_employe_id = ?'
            );
            $var->execute([$id]);
            $res = $var->fetchAll();
            return new JsonResponse($res);
        }
    
        /**
         * @Route("/api/delete/p_employe/p_employe/{id}", name="p_employe_p_absence_delete")
         */
        public function p_absence_p_employe_delete($id)
        {
            $em = $this->getDoctrine();
            // $var = $em->getConnection()->prepare('call p_employe_delete(?);');
            $var = $em->getConnection()->prepare('DELETE FROM p_employe_delete WHERE p_employe_id = ?;');
            $var->execute([$id]);
            $res = $var->fetch();
            return new JsonResponse($res);
        }           
        /**
         * @Route("/api/get/p_employe/p_arrivee/{id}", name="p_employe_p_arrivee_get")
         */
        public function p_arrivee_p_employe_get($id) :JsonResponse
        {
            $em = $this->getDoctrine();
            $var = $em->getConnection()->prepare(
            'SELECT * FROM p_arrivee t LEFT JOIN p_employe rt on rt.id = t.p_employe_id
            WHERE rt.p_employe_id = ?'
            );
            $var->execute([$id]);
            $res = $var->fetchAll();
            return new JsonResponse($res);
        }
    
        /**
         * @Route("/api/delete/p_employe/p_employe/{id}", name="p_employe_p_arrivee_delete")
         */
        public function p_arrivee_p_employe_delete($id)
        {
            $em = $this->getDoctrine();
            // $var = $em->getConnection()->prepare('call p_employe_delete(?);');
            $var = $em->getConnection()->prepare('DELETE FROM p_employe_delete WHERE p_employe_id = ?;');
            $var->execute([$id]);
            $res = $var->fetch();
            return new JsonResponse($res);
        }           
        /**
         * @Route("/api/get/p_employe/p_assignation/{id}", name="p_employe_p_assignation_get")
         */
        public function p_assignation_p_employe_get($id) :JsonResponse
        {
            $em = $this->getDoctrine();
            $var = $em->getConnection()->prepare(
            'SELECT * FROM p_assignation t LEFT JOIN p_employe rt on rt.id = t.p_employe_id
            WHERE rt.p_employe_id = ?'
            );
            $var->execute([$id]);
            $res = $var->fetchAll();
            return new JsonResponse($res);
        }
    
        /**
         * @Route("/api/delete/p_employe/p_employe/{id}", name="p_employe_p_assignation_delete")
         */
        public function p_assignation_p_employe_delete($id)
        {
            $em = $this->getDoctrine();
            // $var = $em->getConnection()->prepare('call p_employe_delete(?);');
            $var = $em->getConnection()->prepare('DELETE FROM p_employe_delete WHERE p_employe_id = ?;');
            $var->execute([$id]);
            $res = $var->fetch();
            return new JsonResponse($res);
        }           
        /**
         * @Route("/api/get/p_employe/p_assignation/{id}", name="p_employe_p_assignation_get2")
         */
//        public function p_assignation_p_employe_get2($id) :JsonResponse
//        {
//            $em = $this->getDoctrine();
//            $var = $em->getConnection()->prepare(
//            'SELECT * FROM p_assignation t LEFT JOIN p_employe rt on rt.id = t.p_employe_id
//            WHERE rt.p_employe_id = ?'
//            );
//            $var->execute([$id]);
//            $res = $var->fetchAll();
//            return new JsonResponse($res);
//        }
//
//        /**
//         * @Route("/api/delete/p_employe/p_employe/{id}", name="p_employe_p_assignation_delete2")
//         */
//        public function p_assignation_p_employe_delete2($id)
//        {
//            $em = $this->getDoctrine();
//            // $var = $em->getConnection()->prepare('call p_employe_delete(?);');
//            $var = $em->getConnection()->prepare('DELETE FROM p_employe_delete WHERE p_employe_id = ?;');
//            $var->execute([$id]);
//            $res = $var->fetch();
//            return new JsonResponse($res);
//        }
        /**
         * @Route("/api/get/p_employe/p_conge/{id}", name="p_employe_p_conge_get")
         */
        public function p_conge_p_employe_get($id) :JsonResponse
        {
            $em = $this->getDoctrine();
            $var = $em->getConnection()->prepare(
            'SELECT * FROM p_conge t LEFT JOIN p_employe rt on rt.id = t.p_employe_id
            WHERE rt.p_employe_id = ?'
            );
            $var->execute([$id]);
            $res = $var->fetchAll();
            return new JsonResponse($res);
        }
    
        /**
         * @Route("/api/delete/p_employe/p_employe/{id}", name="p_employe_p_conge_delete")
         */
        public function p_conge_p_employe_delete($id)
        {
            $em = $this->getDoctrine();
            // $var = $em->getConnection()->prepare('call p_employe_delete(?);');
            $var = $em->getConnection()->prepare('DELETE FROM p_employe_delete WHERE p_employe_id = ?;');
            $var->execute([$id]);
            $res = $var->fetch();
            return new JsonResponse($res);
        }           
        /**
         * @Route("/api/get/p_employe/p_salaire/{id}", name="p_employe_p_salaire_get")
         */
        public function p_salaire_p_employe_get($id) :JsonResponse
        {
            $em = $this->getDoctrine();
            $var = $em->getConnection()->prepare(
            'SELECT * FROM p_salaire t LEFT JOIN p_employe rt on rt.id = t.p_employe_id
            WHERE rt.p_employe_id = ?'
            );
            $var->execute([$id]);
            $res = $var->fetchAll();
            return new JsonResponse($res);
        }
    
        /**
         * @Route("/api/delete/p_employe/p_employe/{id}", name="p_employe_p_salaire_delete")
         */
        public function p_salaire_p_employe_delete($id)
        {
            $em = $this->getDoctrine();
            // $var = $em->getConnection()->prepare('call p_employe_delete(?);');
            $var = $em->getConnection()->prepare('DELETE FROM p_employe_delete WHERE p_employe_id = ?;');
            $var->execute([$id]);
            $res = $var->fetch();
            return new JsonResponse($res);
        }           
}
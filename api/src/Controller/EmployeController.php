<?php
    namespace App\Controller;

    use App\Controller\BaseController;
    use App\Controller\Parameter\TyperubriqueTrait;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Annotation\Route;

    class EmployeController extends BaseController {

    /**
     * @Route("/my/get/employe", name="employe_get")
     */
    public function employe_get() :JsonResponse
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT * FROM employe ORDER BY id DESC LIMIT 500;');
        $var->execute();
        $res = $var->fetchAll();
        return new JsonResponse($res);
    }


    /**
     * @Route("/my/get/employe/{id}", name="employe_one_get")
     */
    public function employe_one_get($id) :JsonResponse
    {
//        $id = (int) $_GET['id'];
        $id = (int) $id;
        $em = $this->getDoctrine();
        $var = $em->getConnection()
                       ->prepare('SELECT * FROM employe WHERE id = ?;');
        $var->execute([$id]);
        $res = $var->fetch();
        return new JsonResponse($res);
    }


    /**
     * @Route("/my/search/employe", name="employe_search_get")
     */
    public function employe_search_get() :JsonResponse
    {
        $search = "%{$_GET['search']}%";
        $em = $this->getDoctrine();
        $var = $em->getConnection()
                       ->prepare('SELECT * FROM employe WHERE name LIKE ?;');
        $var->execute([$search]);
        $res = $var->fetch();
        return new JsonResponse($res);
    }


    /**
     * @Route("/my/post/employe", name="admin_employe_add")
     */
    public function employe_add(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $em = $this->getDoctrine();
        $params = [  $data['lastname'] ?? null, $data['firstname'] ?? null, $data['telephone'] ?? null, $data['indicatif'] ?? null, $data['adress'] ?? null, $data['sex'] ?? null, $data['cnps'] ?? null, $data['matricule'] ?? null, $data['status_matrimonial'] ?? null, $data['enfants'] ?? null, $data['password'] ?? null, $data['pays'] ?? null, $data['ville'] ?? null, $data['datenaissance'] ?? null, $data['photo'] ?? null, $data['departement'] ?? null, $data['fonction'] ?? null, $data['contrat'] ?? null, $data['dateentree'] ?? null, $data['datesortie'] ?? null, $data['salairebase'] ?? null, $data['heuresup'] ?? null, $data['superviseur'] ?? null, $data['contacturgence'] ?? null, $data['cni'] ?? null, $data['rib'] ?? null, $data['embauche'] ?? null, $data['id_magasin'] ?? null ];
        $var = $em->getConnection()->prepare('call employe_add( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);');
        $var->execute($params);
        $res = $var->fetch();
        $var->closeCursor();

        if( isset($data['image']) && is_array($data['image']) && isset($res['id']) ){
            $this->image_upload( $data['image'], '/employe', uniqid().'_'.'employe_'.$res['id'], $res['id'],
                "UPDATE employe SET photo = ? WHERE id = ?;");
        }
        return new JsonResponse($res);
    }


    /**
     * @Route("/my/put/employe", name="admin_employe_update")
     */
    public function employe_update(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $em = $this->getDoctrine();
        $params = [  $data['lastname'] ?? null, $data['firstname'] ?? null, $data['telephone'] ?? null, $data['indicatif'] ?? null, $data['adress'] ?? null, $data['sex'] ?? null, $data['cnps'] ?? null, $data['matricule'] ?? null, $data['status_matrimonial'] ?? null, $data['enfants'] ?? null, $data['password'] ?? null, $data['pays'] ?? null, $data['ville'] ?? null, $data['datenaissance'] ?? null, $data['photo'] ?? null, $data['departement'] ?? null, $data['fonction'] ?? null, $data['contrat'] ?? null, $data['dateentree'] ?? null, $data['datesortie'] ?? null, $data['salairebase'] ?? null, $data['heuresup'] ?? null, $data['superviseur'] ?? null, $data['contacturgence'] ?? null, $data['cni'] ?? null, $data['rib'] ?? null, $data['embauche'] ?? null, $data['id_magasin'] ?? null, $data['id'] ?? null ];
        $var = $em->getConnection()->prepare('call employe_update( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);');
        $var->execute($params);
        $res = $var->fetch();

        $var->closeCursor();

        if( isset($data['image']) && is_array($data['image']) && isset($res['id']) ){
            $this->image_upload( $data['image'], '/employe', $data['imagename'], $res['id'],
                "UPDATE employe SET photo = ? WHERE id = ?;");
        }
        return new JsonResponse($res);
    }


    /**
     * @Route("/my/delete/employe/{id}", name="admin_employe_delete")
     */
    public function employe_delete($id)
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('call employe_delete(?);');
        $var->execute([$id]);
        $res = $var->fetch();
        $var->closeCursor();
        $this->generate_delete_one($res['typerubrique'], $res['id']);
        return new JsonResponse($res);
    }

}

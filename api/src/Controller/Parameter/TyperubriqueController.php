<?php

namespace App\Controller\Parameter;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TyperubriqueController extends AbstractController
{
    public $generateDir;
    public $rubrique;

    public function __construct()
    {
        $this->generateDir = $_SERVER['DOCUMENT_ROOT'] . '/data';
        $this->rubrique = [
            1 => [
                'table_name' => 'PJ',
                'generate_one' => 'CALL pj_get_one(?)',
                'generate_all' => 'CALL pj_get_all()',
                'generate_list' => 'CALL pj_get_all()',
                'generate_relations' => 'CALL pj_relations()',
                'generate_featured' => 'CALL pj_get_all()',
                'set_pe' => 'CALL pj_get_all()',
                'filename' => $this->generateDir . '/pj/pj_'
            ],
            2 => [
                'table_name' => 'pensee',
                'generate_one' => 'SELECT * FROM v_pensees WHERE id = ? ORDER BY id DESC;',
                'generate_all' => 'SELECT * FROM v_pensees ORDER BY id DESC;',
                'generate_list' => 'SELECT * FROM v_pensees WHERE id = ? ORDER BY id DESC;',
                'generate_featured' => 'SELECT * FROM v_pensees WHERE id = ? ORDER BY id DESC;',
                'set_pe' => 'CALL pj_get_all();',
                'filename' => $this->generateDir . '/pensee/pensee_'
            ],
            3 => [
                'table_name' => 'qui',
                'generate_one' => 'CALL qui_get_one(?)',
                'generate_all' => 'CALL qui_get_all()',
                'generate_list' => 'SELECT * FROM v_qui WHERE id = ? ORDER BY id DESC;',
                'generate_featured' => 'CALL pj_get_all();',
                'generate_pe' => 'UPDATE personnes SET pe = ? WHERE id = ?;',
                'set_pe' => 'CALL pj_get_all();',
                'filename' => $this->generateDir . '/qui/qui_'
            ],
            90 => [
                'table_name' => 'personnes',
                'generate_one' => 'SELECT * FROM personnes WHERE id = ?;',
                'generate_all' => 'SELECT * FROM personnes;',
                'generate_list' => 'SELECT * FROM personnes ORDER BY id DESC;',
                'generate_featured' => 'SELECT * FROM personnes ORDER BY id DESC;',
                'generate_pe' => 'CALL pj_personnes_get_pe();',
                'set_pe' => 'UPDATE personnes SET pe = ? WHERE id = ?;',
                'filename' => $this->generateDir . '/personne/personne_'
            ]
        ];
    }


    /**
     * @Route("/admin/typerubrique", name="admin_typerubrique")
     */
    public function type_rubrique()
    {

        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT * FROM typerubrique;');
        $var->execute();
        $res = $var->fetchAll();

        return new JsonResponse($res);
    }

    /**
     * @Route(path="/api/set/pe", name="set_pe")
     */
    public function set_pe(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $typerubrique = (int) $data['typerubrique'];
        $params = [$id = (int) $data['id']];
        $rubrique = $this->rubrique[$typerubrique];

        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare($rubrique['set_pe']);
        $var->execute($params);
        $res = $var->fetch();
        return new JsonResponse($res);
    }

    /**
     * @Route(path="/api/generate/pe", name="generate_pe")
     */
    public function generate_pe(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $typerubrique = (int) $data['typerubrique'];
        $rubrique = $this->rubrique[$typerubrique];

        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare($rubrique['generate_pe']);
        $var->execute();
        $res = $var->fetchAll();

        $this->generate_json($res, $rubrique['filename'] . 'pe.json');

        return new JsonResponse($res);
    }


    /**
     * @Route("/api/generate/all", name="typerubrique_generate_all")
     */
    public function generate_json_pj_one(Request $request)
    {

        $data = json_decode($request->getContent(), true);

        $typerubrique = (int) $data['typerubrique'];
        $params = [$id = (int) $data['id']];
        $rubrique = $this->rubrique[$typerubrique];

        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare($rubrique['generate_all']);
        $var->execute($params);
        $res = $var->fetchAll();

        $this->generate_json_one_by_one($res, $rubrique['filename'], 'id');
        return new JsonResponse('OK', 200);
    }

    /**
     * @Route("/api/generate/all_force", name="typerubrique_generate_all_force")
     */
    public function generate_json_force(Request $request)
    {

        $data = json_decode($request->getContent(), true);

        $typerubrique = (int) $data['typerubrique'];
        $params = [$id = (int) $data['id']];
        $rubrique = $this->rubrique[$typerubrique];

        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare($rubrique['generate_all']);
        $var->execute($params);
        $res = $var->fetchAll();

        $this->generate_json_one_by_one_force($res, $rubrique['filename'], 'id');
        return new JsonResponse('OK', 200);
    }

    /**
     * @Route("/api/generate/typerubrique_one", name="typerubrique_generate_one")
     */
    public function generate_post_one(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $typerubrique = (int) $data['typerubrique'];
        $params = [$id = (int) $data['id']];
        $rubrique = $this->rubrique[$typerubrique];

        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare($rubrique['generate_one']);
        $var->execute($params);
        $res = $var->fetch();

        $this->generate_json($res, $rubrique['filename'] . $id . '.json');
        return new JsonResponse($res, 200);
    }

    /**
     * @Route("/api/generate/typerubrique_list", name="typerubrique_generate_list")
     */
    public function generate_json_list(Request $request)
    {

        $data = json_decode($request->getContent(), true);

        $typerubrique = (int) $data['typerubrique'];
        $params = [$id = (int) $data['typerubrique']];
        $rubrique = $this->rubrique[$typerubrique];

        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare($rubrique['generate_list']);
        $var->execute($params);
        $res = $var->fetchAll();

        $this->generate_json($res, $rubrique['filename'] . 'list.json');
        return new Response('OK', 200);
    }

    /**
     * @Route("/api/generate/typerubrique_sql", name="typerubrique_generate_sql")
     */
    public function generate_post_one_custom(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $typerubrique = (int) $data['typerubrique'];
        $params = $data['params'];
        $sql =  $data['sql'];
        $filename = $data['filename'];
        $rubrique = $this->rubrique[$typerubrique];

        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare($sql);
        $var->execute($params);
        $res = $var->fetch();



        $this->generate_json($res, $filename);
        return new JsonResponse($this->rubrique, 200);
    }


    /**
     * @Route("/api/delete/typerubrique_one", name="typerubrique_delete_one", methods={"POST","DELETE"})
     */
    public function generate_delete_one(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $typerubrique = (int) $data['typerubrique'];
        $params = [$id = (int) $data['id']];
        $rubrique = $this->rubrique[$typerubrique];

        if (file_exists($rubrique['filename'] . $id . '.json')) {
            $myFile = $rubrique['filename'] . $id . '.json';
            unlink($myFile) or  die("Couldn't delete file");
            return new JsonResponse('Fichier supprime id = ' . $id . ' ' . $rubrique['table_name']);
        }

        return new JsonResponse('Ce fichier n\'existe pas');
    }


    /**
     * @Route("/api/generate/categories", name="typerubrique_generate_categories")
     */
    public function generate_json_categories(Request $request)
    {

        $data = json_decode($request->getContent(), true);

        $typerubrique = (int) $data['typerubrique'];
        $params = [$id = (int) $data['typerubrique'], $id2 = (int) $data['typerubrique']];
        $rubrique = $this->rubrique[$typerubrique];

        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT categories_posts.id, categories_posts.name, categories_posts.description, categories_posts.icon, categories_master.name categories_master_name,
       categories_master.description categories_master_description, categories_master.icon categories_master_icon,
       categories_master.id categories_master_id, categories_posts.id, categories_master.typerubrique_id,
       categories_posts.categories_master_id
FROM categories_posts
         left join categories_master on (categories_master.id = categories_posts.categories_master_id AND categories_master.typerubrique_id = 1)
WHERE (categories_posts.typerubrique_id = 1);');
        $var->execute($params);
        $res = $var->fetchAll();

        $this->generate_json($res, $rubrique['filename'] . 'categories.json');
        return new Response('OK', 200);
    }

    /**
     * @Route("/api/generate/categories_master", name="typerubrique_generate_categories_master")
     */
    public function generate_json_categories_master(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $typerubrique = (int) $data['typerubrique'];
        $params = [$id = (int) $data['typerubrique']];
        $rubrique = $this->rubrique[$typerubrique];

        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT * FROM categories_master
                    WHERE typerubrique_id = ? AND id NOT IN (0)');
        $var->execute($params);
        $res = $var->fetchAll();

        $this->generate_json($res, $rubrique['filename'] . 'categories_master.json');
        return new JsonResponse($res, 200);
    }

    /**
     * @Route("/api/generate/location", name="typerubrique_generate_location")
     */
    public function generate_json_cities_quartier(Request $request)
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT * FROM v_city;');
        $var->execute();
        $res = $var->fetchAll();

        $this->generate_json($res, '../../cms_frontend/data/autocomplete/location.json');
        return new JsonResponse($res, 200);
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

    public function generate_json_one_by_one_force(array $res, string $filename, $idname)
    {
        foreach ($res as $re) {
            $this->generate_json($re, $filename . $re["$idname"] . '.json');
        }
    }

    public function generate_delete_json($filename, $id)
    {
        if (file_exists($filename . $id . '.json')) {
            $myFile = $filename . $id . '.json';
            unlink($myFile) or  die("Couldn't delete file");
            return new JsonResponse('Fichier supprime id = ' . $id);
        }
    }
}

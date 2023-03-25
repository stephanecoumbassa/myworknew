<?php

namespace App\Controller;

use App\Entity\SUsers;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpClient\HttpClient;

class LoginController extends AbstractController
{
    use JwtTrait;

    /**
     * @Route(path="/api/inscription", name="user_inscription")
     */
    public function user_inscription(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $data = json_decode($request->getContent(),true);

        $user = new SUsers();
        $user->setTelephone($data['telephone']);
        $user->setEmail($data['email']);
        $user->setRoles($data['type']);
        $user->setPassword($encoder->encodePassword($user, $data['password']));

        $params = [
            $data['name'],
            $data['lastname'],
            $data['telephone_code'],
            $data['telephone'],
            $data['email'],
            $data['type'],
            $user->getPassword(),
            $data['type'],
            $data['shop_id']
        ];

        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('CALL __user_register(?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $res = $var->executeQuery($params)->fetchAll();
        return new JsonResponse($res);
    }

    /**
     * @Route(path="/my/inscription", name="my_inscription")
     */
    public function my_inscription(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $data = json_decode($request->getContent(),true);

        $user = new SUsers();
        $user->setTelephone($data['telephone']);
        $user->setEmail($data['email']);
        $user->setRoles($data['type']);
        $user->setPassword($encoder->encodePassword($user, $data['password']));

        $params = [
            $data['name'],
            $data['lastname'],
            $data['telephone_code'],
            $data['telephone'],
            $data['email'],
            $data['type'],
            $user->getPassword(),
            $data['type'],
            $this->getUser()->getShopId()
        ];

        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('CALL __user_register(?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $res = $var->executeQuery($params)->fetchAll();
        return new JsonResponse($res);
    }

    /**
     * @Route("/api/connexion", name="user_connexion")
     */
    public function connexion(Request $request,
                              JWTTokenManagerInterface $JWTManager,
                              SessionInterface $session,
                              EncoderFactoryInterface $encoderFactory)
    {
        $data = json_decode($request->getContent(),true);

        $user = $this->getDoctrine()->getManager()->getRepository(SUsers::class)
            ->findOneBy(array('email' => $data['email']));

        if(!$user){
            return new JsonResponse(
                ["msg"=>"Email inexistant"]
            );
        }


        $encoder = $encoderFactory->getEncoder($user);
        $salt = $user->getSalt();

        if(!$encoder->isPasswordValid($user->getPassword(), $data['password'], $salt)) {
            return new JsonResponse(
                ["msg"=>'Mot de passe invalide']
            );
        }

        return new JsonResponse([
            'token' => $JWTManager->create($user),
            'token2' => $this->token_generate_username($user->getId(), $user->getRoles(),  $user->getEmail()),
            'res' => [
                "id"=>$user->getId(),
                "email"=>$user->getEmail(),
                "indicatif"=>$user->getTelephoneCode(),
                "uuid"=>$user->getUuid(),
                "telephone"=>$user->getTelephone(),
                "lastname"=>$user->getLastname(),
                "name"=>$user->getName(),
                "shop_id"=>$user->getIdMagasin(),
                "roles"=>$user->getRoles()
            ],
            'msg' => 'Connexion réussie',
            'status' => 1
        ]);

    }

    /**
     * @Route("/api/password_reset", name="password_reset", methods="post")
     */
    public function password_reset(Request $request)
    {
        $client = HttpClient::create();
        $data = json_decode($request->getContent(),true);
        $email = $data["email"];


        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('CALL __user_password_reset(?)');
        $var->executeQuery([$email]);
        $res = $var->fetch();

        if( $res['code_genere'] !== '0') {
            $response = $client->request('POST', 'https://www.affairez.com/email.php',
                [
                    'body' => ['email' => $email, 'content' => $res['code_genere'],
                        'webmaster' => 'contacts@affairez.com', 'subject' => 'Mot de passe oublie' ]
                ]
            );
            $content = $response->getContent();
            return new JsonResponse(["msg"=>"Votre code de rénitialisation a été envoye par émail", "status"=>1]);
        }

        return new JsonResponse($res);
    }


    /**
     * @Route("/api/password_renew", name="password_renew", methods="post")
     */
    public function password_renew(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $data = json_decode($request->getContent(),true);
        $email = $data["email"];
        $code = $data["code"];
        $password = $data["password"];

        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('CALL __token_verif(?)');
        $res = $var->executeQuery([$code])->fetch();

        if( $res['email'] == $email) {

            $user = new SUsers();
            $user->setEmail($data['email']);
            $user->setPassword($encoder->encodePassword($user, $data['password']));

            $em = $this->getDoctrine();
            $var = $em->getConnection()->prepare('CALL __user_password_change(?, ?, ?)');
            $res = $var->executeQuery([$email, $code, $user->getPassword()])->fetch();
            return new JsonResponse(['msg'=>"Votre mot de passe a été changé", 'status' => 1]);
        }

        return new JsonResponse(["msg" => "Impossible de changer le mot de passe", 'status' => 0]);
    }

    /**
     * @Route("/api/test", name="/api_test")
     */
    public function test(Request $request){
        return new JsonResponse('OK');
    }

    /**
     * @Route("/api/login_check", name="/api_login")
     */
    public function test_login(Request $request){
        return new JsonResponse('OK');
    }

}

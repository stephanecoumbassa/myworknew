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
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class UserController extends AbstractController
{

    use JwtTrait;

    /**
     * @Route("/api/post/user", name="user_post")
     */
    public function user_register(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $name = $data["name"];
        $type = $data["type"];
        $lastname = $data["lastname"];
        $telephone = $data["telephone"];
        $email = $data["email"];
        $password = $data["password"];

        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('CALL __user_register(?,?, ?, ?, ?, ?)');
        $var->execute([$name, $lastname, $telephone, $email, $type, $password]);
        $res = $var->fetch();

        return new JsonResponse($res);
    }

    /**
     * @Route("/my/put/user", name="my_user_put")
     */
    public function user_update(Request $request)
    {
      $data = json_decode($request->getContent(),true);
      $name = $data["name"] ?? null;
      $type = $data["type_users_id"];
      $last_name = $data["last_name"];
      $telephone_code = $data["telephone_code"];
      $telephone = $data["telephone"];
      $email = $data["email"];

      $em = $this->getDoctrine();
      $var = $em->getConnection()->prepare('UPDATE s_users SET name = ?, last_name = ?, telephone_code = ?, telephone = ?, email = ?,
                                            type_users_id = ? WHERE id = ?');
      $var->execute([$name, $last_name, $telephone_code, $telephone, $email, $type, $data["id"] ]);
      $res = ['status' => 1, 'msg'=> 'Modifié avec succès'];

      return new JsonResponse($res);
    }

    /**
     * @Route("/my/get/users", name="users_get")
     */
    public function users_get(Request $request)
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT id, name, last_name, telephone_code, telephone, email, type_users_id, roles, uuid FROM s_users WHERE id_magasin = ? ORDER BY name DESC');
        $var->execute([$this->getUser()->getIdMagasin()]);
        $res = $var->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);

        return new JsonResponse($res);
    }

    /**
     * @Route("/api/s_type_users", name="users_type_get")
     */
    public function users_type_get(Request $request)
    {
        $em = $this->getDoctrine();
        $var = $em->getConnection()->prepare('SELECT * FROM s_type_users;');
        $var->execute();
        $res = $var->fetchAll();
        $res = json_decode(json_encode($res, JSON_NUMERIC_CHECK), true);

        return new JsonResponse($res);
    }

  /**
   * @Route("/api/email")
   */
  public function sendEmail(MailerInterface $mailer)
  {
    $email = (new Email())
      ->from('jeanstefyu@gmail.com')
      ->to('stephane.coumbassa@gmail.com')
      //->cc('cc@example.com')
      //->bcc('bcc@example.com')
      //->replyTo('fabien@example.com')
      //->priority(Email::PRIORITY_HIGH)
      ->subject('Time for Symfony Mailer!')
      ->text('Sending emails is fun again!')
      ->html('<p>See Twig integration for better HTML integration!</p>');

    $mailer->send($email);
    return new JsonResponse([$email]);

    // ...
  }


}

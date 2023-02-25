<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Ahc\Jwt\JWT;


trait JwtTrait
{

    private $key = 'ilBXYdGYhzttt4uOB2E5WMmPGW4lQVTNdjRLw/YoA9FTL5br3etv3CjvC9mLbUDJ';

    public function token_generate($id, $data = null)
    {
        $jwt =( new JWT($this->key, 'HS512', 31536000, 10) )->encode(['uid' => $id, 'data' => $data]);
        return $jwt;
    }

    public function token_generate_username($id, $roles, $username)
    {
        $jwt =( new JWT($this->key, 'HS512', 31536000, 10) )->encode(['uid' => $id, 'roles' => $roles, 'username' => $username ]);
        return $jwt;
    }

    public function token_generate_magasin($id, $roles, $username,$magasin)
    {
        $jwt =( new JWT($this->key, 'HS512', 31536000, 10) )->encode(['uid' => $id, 'roles' => $roles, 'username' => $username, 'magasin_id' => $magasin ]);
        return $jwt;
    }

    public function token_verify(Request $request)
    {
        $token =  str_replace('Bearer ',"", $request->headers->get('authorization'));
        try{
            return [true, (new JWT($this->key, 'HS512', 360000000000))->decode($token)];
        }catch (\Exception $exception){
            echo $exception->getMessage();
            return false;
        }
    }

    public function token_getdata(Request $request)
    {
        $token =  str_replace('Bearer ',"", $request->headers->get('authorization'));
        try{
            return (new JWT($this->key, 'HS512', 360000000000))->decode($token);
        }catch (\Exception $exception){
            echo $exception->getMessage();
            return false;
        }
    }

    public function token_permission(Request $request)
    {
        //roles
        $token =  str_replace('Bearer ',"", $request->headers->get('authorization'));
        try{
            return (new JWT($this->key, 'HS512', 360000000000))->decode($token);
        }catch (\Exception $exception){
            echo $exception->getMessage();
            return false;
        }
    }

}

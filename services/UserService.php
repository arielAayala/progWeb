<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once "../vendor/autoload.php";

include_once "../models/User.php";
include_once "../repositories/UserRepository.php";

class UserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    private function createCookieWithJwt(User $user)
    {
        $time = time();
        $payload = [
            "iat" => $time,
            "exp" => $time + (60 * 60),
            "data" => [
                "User" => $user->getIdUser(),
                "nameUser" => $user->getNameUser()
            ]
        ];

        $cookiesConfiguration = [
            'expires' => (time() + (60 * 60)),
            'path' => '/',
            'domain' => '', // leading dot for compatibility or use subdomain
            'secure' => true,     // or false
            'httponly' => true,    // or false
            'samesite' => 'None' // None || Lax  || Strict
        ];

        $token = JWT::encode($payload, "SECRET_JWT", "HS256");

        setcookie('token', $token, $cookiesConfiguration);
    }

    private function deleteCookieWithJwt()
    {
        $time = time();
        $cookiesConfiguration = [
            'expires' => ($time - 60 * 60 * 24),
            'path' => '/',
            'domain' => '', // leading dot for compatibility or use subdomain
            'secure' => true,     // or false
            'httponly' => true,    // or false
            'samesite' => 'None' // None || Lax  || Strict
        ];

        setcookie('token', "", $cookiesConfiguration);
    }

    public function post(User $user): void
    {
        if (!strlen($user->getPasswordUser()) || !strlen($user->getNameUser()) || !strlen($user->getEmailUser())) {
            throw new Exception("Error crendenciales vacías", 400);
        }
        $this->userRepository->post($user);
    }

    public function signIn(User $user)
    {
        if (!strlen($user->getPasswordUser()) || !strlen($user->getNameUser())) {
            throw new Exception("Error crendenciales vacías", 400);
        }

        $user = $this->userRepository->signIn($user);
        $this->createCookieWithJwt($user);
        return $user;
    }

    public function logOut(User $user)
    {
        $this->deleteCookieWithJwt();
    }
}
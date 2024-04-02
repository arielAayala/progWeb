<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once "C:/xampp/htdocs/progWeb/vendor/autoload.php";
include_once "C:/xampp/htdocs/progWeb/models/User.php";
include_once "C:/xampp/htdocs/progWeb/repositories/UserRepository.php";

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
                "nameUser" => $user->getNameUser(),
                "profilesUser" => $user->getProfilesUser()
            ]
        ];


        $token = JWT::encode($payload, "SECRET", "HS256");

        $cookiesConfiguration = [
            'expires' => (time() + (60 * 60)),
            'path' => '/',
            'domain' => '', // leading dot for compatibility or use subdomain
            'secure' => true,     // or false
            'httponly' => true,    // or false
            'samesite' => 'None' // None || Lax  || Strict
        ];

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

    public function createUser(User $user): void
    {
        if (!strlen($user->getPasswordUser()) || !strlen($user->getNameUser()) || !strlen($user->getEmailUser())) {
            throw new Exception("Error crendenciales vacías", 400);
        }
        $this->userRepository->createUser($user);
    }

    public function signIn(User $user): User
    {
        if (!strlen($user->getPasswordUser()) || !strlen($user->getEmailUser())) {
            throw new Exception("Error crendenciales vacías", 400);
        }

        $user = $this->userRepository->signIn($user);
        $this->createCookieWithJwt($user);

        return $user;
    }

    public function logOut(): void
    {
        $this->deleteCookieWithJwt();
    }
}
<?php

include_once "C:/xampp/htdocs/progWeb/models/User.php";
include_once "C:/xampp/htdocs/progWeb/repositories/UserRepository.php";
include_once "C:/xampp/htdocs/progWeb/services/UserService.php";

class UserController
{
    private UserRepository $userRepository;
    private UserService $userService;
    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->userService = new UserService($this->userRepository);
    }

    public function createUser()
    {
        try {
            $datos = json_decode(file_get_contents("php://input"));

            if (!isset($datos->nameUser, $datos->passwordUser, $datos->emailUser)) {
                throw new Exception("Error faltan crendenciales", 400);
            }

            $user = new User();
            $user->setNameUser($datos->nameUser);
            $user->setEmailUser($datos->emailUser);
            $user->setPasswordUser($datos->passwordUser);

            $this->userService->createUser($user);

            http_response_code(200);
            echo json_encode(["msg" => "Se ha creado el usuario correctamente"]);

        } catch (Exception $exception) {
            http_response_code($exception->getCode());
            echo json_encode(["msg" => $exception->getMessage()]);
        }
    }


    public function signIn()
    {
        try {
            $datos = json_decode(file_get_contents("php://input"));
            if (!isset($datos->emailUser, $datos->passwordUser)) {
                throw new Exception("Error faltan crendenciales", 400);
            }

            $user = new User();
            $user->setEmailUser($datos->emailUser);
            $user->setPasswordUser($datos->passwordUser);

            $userData = $this->userService->signIn($user);

            http_response_code(200);
            echo json_encode(["message" => "Se ha iniciado sesión correctamente", "data" => ["name" => $userData->getNameUser(), "email" => "hola"]]);

        } catch (Exception $exception) {
            http_response_code($exception->getCode());
            echo json_encode(["error" => $exception->getMessage()]);
        }
    }

    public function logOut()
    {
        $this->logOut();
        http_response_code(200);
        echo json_encode(["msg" => "Se ha cerrado sesión correctamente"]);
    }
}

$userController = new UserController();
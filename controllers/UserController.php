<?php

include_once "../models/User.php";
include_once "../repositories/UserRepository.php";
include_once "../services/UserService.php";

class UserController
{
    private UserRepository $userRepository;
    private UserService $userService;
    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->userService = new UserService($this->userRepository);
    }

    public function post()
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

            $this->userService->post($user);

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
            if (!isset($datos->nameUser, $datos->passwordUser)) {
                throw new Exception("Error faltan crendenciales", 400);
            }

            $user = new User();
            $user->setNameUser($datos->nameUser);
            $user->setPasswordUser($datos->passwordUser);

            $user = $this->userService->signIn($user);

            http_response_code(200);
            echo json_encode(["msg" => "Se ha iniciado sesión correctamente", "data" => ["nameUser" => $user->getNameUser(), "idUser" => $user->getIdUser()]]);

        } catch (Exception $exception) {
            http_response_code($exception->getCode());
            echo json_encode(["msg" => $exception->getMessage()]);
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
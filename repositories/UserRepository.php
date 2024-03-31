<?php

include_once "Repository.php";
include_once "../models/User.php";

class UserRepository extends Repository
{

    public function post(User $user): void
    {
        $query = "INSERT INTO user(nameUser, emailUser, passwordUser) VALUES (?,?,?)";
        $smtm = $this->con->prepare($query);

        $nameUser = $user->getNameUser();
        $emailUser = $user->getEmailUser();
        $passwordUser = password_hash($user->getPasswordUser(), PASSWORD_DEFAULT);

        $smtm->bind_param("sss", $nameUser, $emailUser, $passwordUser);

        if (!$smtm->execute()) {
            $this->con->close();
            throw new Exception("Error al crear el usuario", 400);
        }

        $this->con->close();
    }


    public function signIn(User $user)
    {
        $query = "SELECT idUser, nameUser, passwordUser FROM user WHERE nameUser = ?";
        $smtm = $this->con->prepare($query);


        $nameUser = $user->getNameUser();
        $passwordUser = $user->getPasswordUser();

        $smtm->bind_param("s", $nameUser);
        if (!$smtm->execute()) {
            $this->con->close();
            throw new Exception("Error al iniciar Sesión el usuario", 400);
        }

        $result = $smtm->get_result();

        if ($result->num_rows == 0) {
            $this->con->close();
            throw new Exception("Error Usuario o contraseña incorrecta", 404);
        }

        $userData = $result->fetch_object();
        if (!(password_verify($passwordUser, $userData->passwordUser))) {
            $this->con->close();
            throw new Exception("Error Usuario o contraseña incorrecta", 404);
        }

        $this->con->close();

        $user = new User();
        $user->setIdUser($userData->idUser);
        $user->setNameUser($userData->nameUser);


        return $user;

    }

    public function getUserData(User $user)
    {
        $query = "SELECT u.idUser, u.nameUser, p.nameProfile, p.idProfile  FROM user u 
        INNER JOIN user_has_profile up ON u.idUser = up.idUser  
        INNER JOIN profile p ON p.idProfile = up.idProfile
        WHERE u.idUser = ? ";
        $smtm = $this->con->prepare($query);

        $idUser = $user->getIdUser();

        $smtm->bind_param("i", $idUser);
        if (!$smtm->execute()) {
            $this->con->close();
            throw new Exception("Error al obtener usuario", 400);
        }

        $result = $smtm->get_result();

        if ($result->num_rows == 0) {
            $this->con->close();
            throw new Exception("Error no existe el usuario", 404);
        }



        $userData = $result->fetch_object();

        $this->con->close();

        $user = new User();
        $user->setIdUser($userData->idUser);
        $user->setNameUser($userData->nameUser);

        return $user;


    }

}
<?php

include_once "C:/xampp/htdocs/progWeb/models/User.php";
include_once "C:/xampp/htdocs/progWeb/repositories/Repository.php";
require_once "C:/xampp/htdocs/progWeb/vendor/autoload.php";


class UserRepository extends Repository
{

    public function createUser(User $user): void
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


    public function signIn(User $user): User
    {
        $query = "SELECT u.idUser, u.nameUser, u.passwordUser,
                CONCAT('[', GROUP_CONCAT(JSON_OBJECT('nameProfile', p.nameProfile, 'idProfile', p.idProfile)), ']') AS profilesUser
                FROM user u
                INNER JOIN user_has_profile up ON u.idUser = up.User_idUser
                INNER JOIN profile p ON p.idProfile = up.Profile_idProfile
                WHERE u.emailUser = ?
                GROUP BY u.idUser, u.nameUser;";
        $smtm = $this->con->prepare($query);


        $emailUser = $user->getEmailUser();
        $passwordUser = $user->getPasswordUser();

        $smtm->bind_param("s", $emailUser);
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
        $user->setNameUser($userData->nameUser);
        $user->setProfilesUser($userData->profilesUser);


        return $user;

    }



}
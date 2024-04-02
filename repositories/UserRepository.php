<?php

include_once "C:/xampp/htdocs/progWeb/models/User.php";
include_once "C:/xampp/htdocs/progWeb/repositories/Repository.php";
require_once "C:/xampp/htdocs/progWeb/vendor/autoload.php";


class UserRepository extends Repository
{

    public function createUser(User $user): void
    {
        $query = "INSERT INTO users(userName, userEmail, userPassword) VALUES (?,?,?)";
        if (!($smtm = $this->con->prepare($query))) {
            $this->con->close();
            throw new Exception("Error En la base de datos", 500);
        }

        $userName = $user->getUserName();
        $userEmail = $user->getUserEmail();
        $userPassword = password_hash($user->getUserPassword(), PASSWORD_DEFAULT);

        $smtm->bind_param("sss", $userName, $userEmail, $userPassword);

        if (!$smtm->execute()) {
            $this->con->close();
            throw new Exception("Error al crear el usuario", 400);
        }

        $this->con->close();
    }


    public function signIn(User $user): User
    {
        $query = "SELECT u.userId, u.userName, u.userPassword, u.userEmail,
                CONCAT('[', GROUP_CONCAT(JSON_OBJECT('roleName', r.roleName, 'roleId', r.roleId)), ']') AS userRoles
                FROM users u
                LEFT JOIN usersroles ur ON u.userId = ur.userId
                LEFT JOIN roles r ON r.roleId = ur.roleId
                WHERE u.userEmail = ?
                GROUP BY u.userId, u.userName;";

        if (!($smtm = $this->con->prepare($query))) {
            $this->con->close();
            throw new Exception("Error en la Base de datos", 500);
        }


        $userEmail = $user->getUserEmail();
        $userPassword = $user->getUserPassword();

        $smtm->bind_param("s", $userEmail);
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
        if (!(password_verify($userPassword, $userData->userPassword))) {
            $this->con->close();
            throw new Exception("Error Usuario o contraseña incorrecta", 404);
        }

        $this->con->close();


        $user = new User();
        $user->setUserName($userData->userName);
        $user->setUserEmail($userData->userEmail);
        $user->setUserRoles($userData->userRoles);


        return $user;

    }



}
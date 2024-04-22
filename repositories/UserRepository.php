<?php

include_once "C:/xampp/htdocs/progWeb/models/User.php";
include_once "C:/xampp/htdocs/progWeb/repositories/Repository.php";
require_once "C:/xampp/htdocs/progWeb/vendor/autoload.php";


class UserRepository extends Repository
{
    public function getAllUsers(): array
    {
        $query = "SELECT userId, userName, userEmail FROM users";

        if (!($smtm = $this->con->prepare($query))) {
            $this->con->close();
            throw new Exception("Error En la base de datos", 500);
        }

        if (!$smtm->execute()) {
            $this->con->close();
            throw new Exception("Error al crear el usuario", 400);
        }

        $result = $smtm->get_result();

        $allUsers = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $allUsers[] = $row;
            }
        }

        return $allUsers;
    }

    public function getUser(User $user)
    {
        $query = "SELECT userId, userName, userEmail FROM users WHERE userId = ?";

        if (!($smtm = $this->con->prepare($query))) {
            $this->con->close();
            throw new Exception("Error En la base de datos", 500);
        }

        $userId = $user->getUserId();

        $smtm->bind_param("i", $userId);

        if (!$smtm->execute()) {
            $this->con->close();
            throw new Exception("Error al crear el usuario", 400);
        }

        $result = $smtm->get_result();
        $userData = $result->fetch_object();

        $this->con->close();

        $user = new User($userData->userId, $userData->userName, $userData->userEmail);

        return $user;
    }

    public function deleteUser(User $user): void
    {
        $query = "DELETE FROM usersroles WHERE userId = ?";

        if (!($smtm = $this->con->prepare($query))) {
            $this->con->close();
            throw new Exception("Error En la base de datos", 500);
        }

        $userId = $user->getUserId();
        $smtm->bind_param("i", $userId);

        if (!$smtm->execute()) {
            $this->con->close();
            throw new Exception("Error al borrar los roles del usuario", 500);
        }

        if ($smtm->affected_rows == 0) {
            $this->con->close();
            throw new Exception("Error usuario inexistente", 404);
        }

        $query = "DELETE FROM users WHERE userId = ?";

        if (!($smtm = $this->con->prepare($query))) {
            $this->con->close();
            throw new Exception("Error En la base de datos", 500);
        }

        $smtm->bind_param("i", $userId);

        if (!$smtm->execute()) {
            $this->con->close();
            throw new Exception("Error al borrar el usuario", 500);
        }

        if ($smtm->affected_rows == 0) {
            $this->con->close();
            throw new Exception("Error usuario inexistente", 404);
        }
    }

    public function updateUser(User $user): User
    {
        $query = "UPDATE users SET userName = ?, userEmail = ? WHERE userId = ?";

        if (!($smtm = $this->con->prepare($query))) {
            $this->con->close();
            throw new Exception("Error En la base de datos", 500);
        }

        $userName = $user->getUserName();
        $userEmail = $user->getUserEmail();
        $userId = $user->getUserId();

        $smtm->bind_param("ssi", $userName, $userEmail, $userId);

        if (!$smtm->execute()) {
            $this->con->close();
            throw new Exception("Error al actualizar el usuario", 500);
        }

        if ($smtm->affected_rows == 0) {
            $this->con->close();
            throw new Exception("Error usuario inexistente", 404);
        }

        $user = new User($smtm->insert_id);

        return $this->getUser($user);
    }

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
        /* get the last user id insert into the database */
        $lastUserIdInsert = $this->con->insert_id;

        /* By default all users start with the role user */
        $idRoleUser = 2;

        $query = "INSERT INTO usersroles(userId, roleId) VALUES (?,?)";
        if (!($smtm = $this->con->prepare($query))) {
            $this->con->close();
            throw new Exception("Error En la base de datos", 500);
        }

        $smtm->bind_param("ii", $lastUserIdInsert, $idRoleUser);

        if (!$smtm->execute()) {
            $this->con->close();
            throw new Exception("Error al crear el usuario", 400);
        }

        $this->con->close();
    }


    public function signIn(User $user): User
    {
        $query = "SELECT userId, userName, userPassword, userEmail FROM users  WHERE u.userEmail = ?";

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

        $user = new User($userData->userId, $userData->userName, $userData->userEmail);

        return $user;

    }
}
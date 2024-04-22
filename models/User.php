<?php

class User
{

    private int|null $userId;
    private string|null $userName;
    private string|null $userEmail;
    private string|null $userPassword;

    /* UserRoles is an array of Role objects */
    private array $userRoles;

    public function __construct(int $id = null, string $name = null, string $email = null, string $password = null, array $roles = null)
    {
        $this->userId = $id;
        $this->userName = $name;
        $this->userEmail = $email;
        $this->userPassword = $password;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function setUserName($userName)
    {
        $this->userName = $userName;
    }

    public function getUserName()
    {
        return $this->userName;
    }

    public function setUserPassword($userPassword)
    {
        $this->userPassword = $userPassword;
    }

    public function getUserPassword()
    {
        return $this->userPassword;
    }

    public function setUserEmail($userEmail)
    {
        $this->userEmail = $userEmail;
    }

    public function getUserEmail()
    {
        return $this->userEmail;
    }

    public function setUserRoles(array $userRoles)
    {
        $this->userRoles = $userRoles;
    }

    public function getUserRoles()
    {
        return $this->userRoles;
    }
}

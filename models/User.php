<?php

class User
{

    private int $userId;
    private string $userName;
    private string $userEmail;
    private string $userPassword;
    private array $userRoles;

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

    public function setUserRoles(string $userRoles)
    {
        $this->userRoles = json_decode($userRoles, true);
    }

    public function getUserRoles()
    {
        return $this->userRoles;
    }
}

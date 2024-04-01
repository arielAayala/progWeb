<?php

class User
{

    private int $idUser;
    private string $nameUser;
    private string $emailUser;
    private string $passwordUser;
    private array $profilesUser;

    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;
    }

    public function getIdUser()
    {
        return $this->idUser;
    }

    public function setNameUser($nameUser)
    {
        $this->nameUser = $nameUser;
    }

    public function getNameUser()
    {
        return $this->nameUser;
    }

    public function setPasswordUser($passwordUser)
    {
        $this->passwordUser = $passwordUser;
    }

    public function getPasswordUser()
    {
        return $this->passwordUser;
    }

    public function setEmailUser($emailUser)
    {
        $this->emailUser = $emailUser;
    }

    public function getEmailUser()
    {
        return $this->emailUser;
    }

    public function setProfilesUser(string $profilesUser)
    {
        $this->profilesUser = json_decode($profilesUser, true);
    }

    public function getProfilesUser()
    {
        return $this->profilesUser;
    }
}

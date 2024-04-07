<?php

class Repository
{
    protected mysqli $con;

    public function __construct()
    {
        /* TODO: USES .ENV TO PROTECT THE DB */
        $this->con = new mysqli("localhost", "root", "", "progweb");
    }
}
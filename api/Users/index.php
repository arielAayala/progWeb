<?php
include_once "../controllers/UserController.php";

switch ($_SERVER['REQUEST_METHOD']) {
    case "POST":
        $userController->createUser();
        break;

}
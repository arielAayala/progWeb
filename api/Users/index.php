<?php
include_once "C:/xampp/htdocs/progWeb/controllers/UserController.php";

switch ($_SERVER['REQUEST_METHOD']) {
    case "POST":
        $userController->createUser();
        break;
    case "GET":
        $userController->getAllUsers();
        break;
    case "DELETE":
        $userController->deleteUser();
        break;
}
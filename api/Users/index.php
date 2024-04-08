<?php

header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");

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
    case "PUT":
        $userController->updateUser();
        break;
}
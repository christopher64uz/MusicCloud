<?php

require_once(__DIR__ . "/../config/constants.php");
require_once(__DIR__ . "/../util/web.php");
require_once(__DIR__ . "/../util/security.php");
require_once(__DIR__ . "/../service/registration_service.php");
require_once(__DIR__ . "/../service/data_service.php");

session_start();
$validationResult = validate_registration_form($_POST);
if (count($validationResult) > 0) {
    $_SESSION["errors"] = $validationResult;    
    redirect(VIEWS . "/registration_form.php");
    exit;
} else {
    $connection = get_connection();
    $userName = mysqli_real_escape_string($connection, $_POST["userName"]);
    $userNameAvailable = verify_username_availability($userName);
    //exit();
    if($userNameAvailable){
        $_SESSION["errors"] = array("userName"=>"Username already exists");
        redirect(VIEWS . "/registration_form.php");        
    }

    $firstName = mysqli_real_escape_string($connection, $_POST["firstName"]);
    $lastName = mysqli_real_escape_string($connection, $_POST["lastName"]);    
    $password = mysqli_real_escape_string($connection, $_POST["password"]);

    $user = new_user($firstName,$lastName,$userName,$password);    
    if($user){
        $_SESSION["success"] = "Registration successful. Please login.";
    }
    redirect(VIEWS . "/login.php");    
}

?>

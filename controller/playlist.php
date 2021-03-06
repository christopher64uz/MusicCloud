<?php
require_once(__DIR__ . "/../config/constants.php");
require_once(__DIR__ . "/../controller/ensure_session.php");
require_once(__DIR__ . "/../util/web.php");
require_once(__DIR__ . "/../service/data_service.php");

$_SESSION["error"] = "";
$_SESSION["success"] = "";

if (empty($_POST["action"])) {  // isset() function does not work since it prints even when NULL
    redirect(VIEWS . "/playlist_form.php");
}

if ($_POST["action"] == "Save") {
    // Validating the input
    $connection = get_connection();
    $playlistname = trim(mysqli_real_escape_string($connection, $_POST["playlistname"]));
    $playlistgenre =  trim(mysqli_real_escape_string($connection, $_POST["playlistgenre"]));
    
    $playlistsongs = $_POST['songlist'];
    //print_r($playlistsongs);
    //exit;
    if (!empty($playlistname) && !empty($playlistgenre) && !empty($playlistsongs)) {
        new_playlist($playlistname, $playlistgenre, $playlistsongs);
    }
    else {
        $_SESSION["error"] = "Fields cannot be Empty.";
    }
}
redirect(VIEWS . "/playlist_form.php");





?>


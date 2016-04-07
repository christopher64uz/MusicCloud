<?php
require_once(__DIR__ . "/../config/constants.php");
require_once(__DIR__ . "/../controller/ensure_session.php");
require_once(__DIR__ . "/../util/web.php");
require_once(__DIR__ . "/../service/data_service.php");

$_SESSION["error"] = "";
$_SESSION["success"] = "";

if (empty($_POST["action"])) {  // isset() function does not work since it prints even when NULL
    redirect(VIEWS . "/upload_form.php");
}

 function checkuploadedfile() {
    //echo $_SESSION[CURRENT_USER];
    $foldername = get_upload_folder($_SESSION[CURRENT_USER]);
    $folderdir = '../data/'.$foldername.'/'.basename($_FILES["songfilename"]["name"]);
    $fileType = pathinfo($folderdir,PATHINFO_EXTENSION);
    
    // Mime type not working even after enabling it in php.ini
    /*if (preg_match('audio/', mime_content_type($_FILES['songfilename']['name']))) {
        echo "Matches";
    }*/
    if (!file_exists($folderdir)) {
        // Covers Basic Audio Format
        if ($fileType == "mp3" || $fileType == "mpeg" || $fileType == "wav" || $fileType == "ogg" || $fileType == "mp4") {
            if (move_uploaded_file($_FILES["songfilename"]["tmp_name"], $folderdir)) {
                $_SESSION["success"] =  "The file ". basename( $_FILES["songfilename"]["name"]). " has been uploaded.";
                return true;
            } else {                
                $_SESSION["error"] =  "Sorry, there was an error uploading your file.";
            }            
        }
        else {
            $_SESSION["error"] = "It may not be a valid Audio type. Accepted format(mp3/mp4/mpeg/wav/ogg)";
        }
    }
    else {
        $_SESSION["error"] = "File Already Exists.";
    }
    return false;
    //return true; // REMOVE THIS JUST FOR TESTING
}

if ($_POST["action"] == "Save") {
    // Validating the input
    $connection = get_connection();
    $songname =  trim(mysqli_real_escape_string($connection, $_POST["songname"]));
    $songauthor =  trim(mysqli_real_escape_string($connection, $_POST["songauthor"]));
    $songgenre =  trim(mysqli_real_escape_string($connection, $_POST["songgenre"]));
    $songreleasedate =  trim(mysqli_real_escape_string($connection, $_POST["songreleasedate"]));
    
    if (!empty($songname) && !empty($songauthor) && !empty($songgenre) && !empty($songreleasedate)) {
        if (preg_match("/^\d{2}\/\d{2}\/\d{4}$/", $songreleasedate) || preg_match("/^\d{4}\-\d{2}\-\d{2}$/", $songreleasedate)) {
            // Check if the file is proper.
            $valid = checkuploadedfile();
            
            if ($valid) {                
                $songfilename = trim(mysql_real_escape_string($connection, basename($_FILES["songfilename"]["name"])));
                new_song($songname, $songauthor, $songgenre, $songreleasedate, $songfilename);
                redirect(VIEWS . "/upload_form.php");
            }
        }
        else {
            $_SESSION["error"] = "Date Format is Incorrect";
        }        
    }
    else {
        $_SESSION["error"] = "Fields cannot be Empty";
    }
    
 if ($_SESSION["error"] != "") {
    redirect(VIEWS . "/upload_form.php");
 }   
}




?>


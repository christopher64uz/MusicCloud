<?php

require_once(__DIR__ . "/../config/constants.php");
require_once(__DIR__ . "/../util/security.php");
require_once(__DIR__ . "/../model/data_access.php");
/**
	User Methods
*/

//Has to be called before newUser
function verify_username_availability($userName){
    $exists = false;
    if(get_user($userName)){
        $exists = true;
    }
    return $exists;
}

//Username is assumed to be unique. Private. //Ensure that $email does not exist in the system
function new_user($firstName,$lastName,$email,$password){	
    $salt = generate_salt();
    $encPassword = encrypt_password($password,$salt);

    //$user = create_user_object($firstName,$lastName,$email,$encPassword,$salt,$userType);
    save_user_info($firstName,$lastName,$email,$encPassword,$salt);
    
    return true;
}


//Username is assumed to be unique
function get_user($userName){
    return get_user_object($userName);
}

/**
 	MusicCloud Methods
*/
// Created by Chris
function new_song($songname, $songauthor, $songgenre, $songreleasedate, $songfilename) {   
    save_new_song($songname, $songauthor, $songgenre, $songreleasedate, $songfilename, $_SESSION[CURRENT_USER]);    
}

function get_songs($userId,$flag) {
    return get_song_array($userId,$flag);
}

function get_playlists($userId) {
    return get_playlist_array($userId);
}

function new_playlist($playlistname, $playlistgenre, $playlistsongs) {
    return save_new_playlist($playlistname, $playlistgenre, $playlistsongs);
}

?>

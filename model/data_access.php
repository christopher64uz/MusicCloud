<?php

//require_once(__DIR__ . "/domain.php");

/**
Following are functions that are private (not to be used by API outside)
*/

function get_connection(){	
    $connection = mysqli_connect(mysql_HOSTNAME, mysql_USERNAME, mysql_PASSWORD, mysql_DATABASE, mysql_PORT);
    if(!$connection){        
        //there was an error
        $errorDescription = mysqli_connect_error();
        trigger_error($errorDescription, E_USER_ERROR);
    }
    return $connection;
}
/**/
function convert_mysql_user_array_to_map($usr){
    return array(
        user_FIRST_NAME=> $usr["first_name"],
        user_LAST_NAME=> $usr["last_name"],
        user_EMAIL=> $usr["email"],
        user_PASSWORD=> $usr["password"],
        user_SALT=> $usr["salt"]       
    );
}

function convert_mysql_song_array_to_map($song){    
    return array(
        song_id=> $song["id"],
        song_name=> $song["name"],
        song_author=> $song["author"],
        song_genre=> $song["genre"],
        song_filename=> $song["filename"]
    );
}

function convert_mysql_playlist_array_to_map($playlist) {
    return array (
        playlist_pid=> $playlist["pid"],
        playlist_name=> $playlist["name"],
        playlist_genre=> $playlist["genre"]        
    );
}

/**
Public functions (Maybe freely used by API outside)
*/

function get_upload_folder($email){
    $currentUserId = explode("@",$email);
    $useridpart1 = $currentUserId[0];
    $useridpart2 = explode(".",$currentUserId[1]);    
    $currentUserId = $useridpart1.$useridpart2[0];
    
    if (!file_exists('../data/'.$currentUserId)) {
        mkdir('../data/'.$currentUserId);
    }
    return $currentUserId;
}

function save_user_info($firstName,$lastName,$email,$encPassword,$salt){ 
    // Save in DB
    $connection = get_connection();
    $userquery = "INSERT INTO users (email, first_name, last_name, password, salt) VALUES ('$email','$firstName','$lastName','$encPassword','$salt')";
    //exit;
    mysqli_query($connection, $userquery) or trigger_error(mysqli_error()." in ".$userquery);  
}

function save_new_song($songname, $songauthor, $songgenre, $songreleasedate, $songfilename,$useremail) {
    // Save in DB
    $connection = get_connection();
    $songquery = "INSERT INTO song (name, author, release_date, filename, genre, users_email) VALUES ('$songname', '$songauthor', '$songreleasedate', '$songfilename', '$songgenre', '$useremail')";
    //exit;
    mysqli_query($connection, $songquery) or trigger_error(mysqli_error()." in ".$songquery);
}

function get_user_object($userId){    
    $query = "SELECT * FROM users WHERE email='$userId'";
    $connection = get_connection();
    $resultSet = mysqli_query($connection, $query);    
    $record = mysqli_fetch_array($resultSet);    
    $user = null;
    if ($record) {    	
        $user = convert_mysql_user_array_to_map($record);
    }    
    return $user;
}

function save_todo_object($todo){
	todolog("mysql_data_access.php | trying to save todo");
	
	$stmt = "INSERT INTO todo (description, scheduled_date, status, owner) VALUES(";
    $stmt = $stmt . "'" . $todo[todo_DESCRIPTION] . "',";
    $stmt = $stmt . "'" . $todo[todo_DATE] . "',";
    $stmt = $stmt . "'" . $todo[todo_STATUS] . "',";
    $stmt = $stmt . "'" . $todo[todo_OWNER] . "'";
    $stmt = $stmt . ")";

	todolog("mysql_data_access.php | insert stmt: $stmt");

    $connection = get_connection();
    mysqli_query($connection, $stmt);
    
    $previousId = mysqli_insert_id($connection);
    todolog("mysql_data_access.php | generated todo id: $previousId");
    $todo[todo_ID] = $previousId;

    return $todo;
}

function get_song_array($user,$flag){
    $user = mysql_real_escape_string($user);
    $songquery = "SELECT id, name, author, genre, filename FROM song WHERE users_email = '$user' ORDER BY id DESC";
    if ($flag == 1) {
        $songquery = "SELECT id, name, author, genre, filename FROM song WHERE users_email = '$user' AND playlists_id is NULL ORDER BY id DESC";
    }
    $connection = get_connection();
    $resultSet = mysqli_query($connection, $songquery) or trigger_error(mysqli_error()." in ".$songquery);
    //exit;
    $songs = [];
    while ($song = mysqli_fetch_array($resultSet)) {
        $songs[] = convert_mysql_song_array_to_map($song);
    }
    //print_r($songs);
    //exit;
    return $songs;
}

function get_playlist_array($user) {
    $user = mysql_real_escape_string($user);
    $playlistquery = "SELECT * FROM playlist WHERE users_email='$user' ORDER BY pid DESC";
    
    $connection = get_connection();
    $resultSet = mysqli_query($connection, $playlistquery) or trigger_error(mysqli_error()." in ".$playlistquery);

    $playlists =[];
    while ($playlist = mysqli_fetch_array($resultSet)) {
        $playlists[] = convert_mysql_playlist_array_to_map($playlist);
    }
    return $playlists;
}

function save_new_playlist($playlistname, $playlistgenre, $playlistsongs) {
    $playlistquery = "INSERT INTO playlist (name, genre, users_email) VALUES ('$playlistname', '$playlistgenre', '$_SESSION[CURRENT_USER]')";
    
    $connection = get_connection();
    mysqli_query($connection, $playlistquery) or trigger_error(mysqli_error()." in ".$playlistquery);
    
    $pid = mysqli_insert_id($connection);
    
    foreach ($playlistsongs as $songid) {
        $songquery = "UPDATE song SET playlists_id = '$pid' WHERE id = '$songid'";
        mysqli_query($connection, $songquery) or trigger_error(mysqli_error()." in ".$songquery);
    }
    //exit;
    return true;
}

function show_table($record){
    return array (
        playlist_name => $record['name'],
        playlist_genre => $record['genre'],
        playlist_users_email => $record['users_email'],
        playlist_pid => $record['pid'],
    );
}

function show_playlist(){
	$query = "SELECT * FROM playlist";
	$connection = get_connection();
	$resultSet = mysqli_query($connection, $query);
	while ($record = mysqli_fetch_array($resultSet)) {
    	$table [] = show_table($record);
    }
    return $table;
}


function get_playlist_object($playId){
    //echo $userId;
	//music_cloudlog("mysql_data_access.php | trying to retrieve user object: $userId");
	$query = "SELECT * FROM playlist WHERE pid='".$playId."';";
    //echo $query;
    $connection = get_connection();
    $resultSet = mysqli_query($connection, $query);

    while ($record = mysqli_fetch_array($resultSet)) {
    	//music_cloudlog("mysql_data_access.php | trying to convert stdclass to map");
        $playlist[] = convert_mysql_playlist_array_to_map($record);
    }
    //music_cloudlog("mysql_data_access.php | user: " . print_r($user, true));
    return $playlist;
}


function get_song_object($songId){
    //echo $userId;
	//music_cloudlog("mysql_data_access.php | trying to retrieve user object: $userId");
	$query = "SELECT * FROM song WHERE playlists_id='".$songId."';";
    //echo $query;
    $connection = get_connection();
    $resultSet = mysqli_query($connection, $query);

    $song = array();
    while ($record = mysqli_fetch_array($resultSet)) {
       $song[] = convert_mysql_song_array_to_map($record);
   }
    return $song;
}
?>
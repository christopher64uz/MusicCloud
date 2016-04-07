<?php
require_once(__DIR__ . "/../config/config.php");
//require_once(__DIR__ . "/domain.php");

/**
Following are functions that are private (not to be used by API outside)
*/

function get_connection(){
	//music_cloudlog("mysql_data_access.php | trying to retrieve mysql connection using: " . mysql_HOSTNAME . ", " . mysql_USERNAME . ", " . mysql_DATABASE . ", " . mysql_PORT);
	$connection = mysqli_connect(mysql_HOSTNAME,mysql_USERNAME,mysql_PASSWORD,mysql_DATABASE,mysql_PORT);
	if(!$connection){
		//music_cloudlog("mysql_data_access.php | Could not retrieve mysql connection");
        //echo 'hhahahaah';
		//there was an error
		$errorDescription = mysqli_connect_error();
		trigger_error($errorDescription, E_USER_ERROR);
	}

	return $connection;
}

function convert_mysql_user_array_to_map($user){
	return array(
		user_FIRST_NAME=> $user["first_name"],
		user_LAST_NAME=> $user["last_name"],
		user_EMAIL=> $user["email"],
		user_PASSWORD=> $user["password"],
		//user_SALT=> $user["salt"],
		//user_TYPE=> $user["type"],
		//user_ENABLED=> $user["enabled"]
	);
}

/**
Public functions (Maybe freely used by API outside)
*/
function convert_mysql_playlist_array_to_map($email){
	return array(
		playlist_name => $email['name'],
		playlist_genre => $email['genre'],
		playlist_users_email => $email['users_email'],
	);
}

function convert_mysql_song_array_to_map($song){
	return array(
		songname => $song['name'],
		songenre => $song['genre'],
		songemail => $song['users_email'],
		songauthor => $song['author'],
        songdate => $song['release_date'],
        songfilename => $song['filename'],

	);
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

    while ($record = mysqli_fetch_array($resultSet)) {
       $song[] = convert_mysql_song_array_to_map($record);
   }
    return $song;
}
?>
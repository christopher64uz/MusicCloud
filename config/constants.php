<?php
//domain
define("song_id", "id");
define("song_name","name");
define("song_author","author");
define("song_genre","genre");
define("song_filename","filename");
define("playlist_pid", "pid");
define("playlist_name", "name");
define("playlist_genre", "genre");
define("playlist_users_email","users_emails");

//Application paths
define("APPLICATION_NAME","musiccloud");
define("APPLICATION_ROOT", "http://" . $_SERVER["SERVER_NAME"] . "/" . APPLICATION_NAME);
define("CSS", APPLICATION_ROOT . "/resources/css");
define("JS", APPLICATION_ROOT . "/resources/js");
define("CONTROLLER", APPLICATION_ROOT . "/controller");
define("VIEWS", APPLICATION_ROOT . "/views");

define("CURRENT_USER","CURRENT_USER");

define("mysql_HOSTNAME", "localhost");
define("mysql_PORT", "3306");
define("mysql_USERNAME", "adminZKJ5CqA");
define("mysql_PASSWORD", "KtntRFX1m7JB");
define("mysql_DATABASE","music_cloud");

?>
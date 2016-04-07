<?php
$navMarkup = "&nbsp;";
require_once(__DIR__ . "/service/data_service.php");
$navMarkup = "";
$navMarkup = $navMarkup . "<li><a href=\"" . VIEWS . "/login.php\">Login</a></li>";
$navMarkup = $navMarkup . "<li><a href=\"" . VIEWS . "/registration_form.php\">Sign up</a></li>";

require_once("views/header.php");

if(session_id() == '' || !isset($_SESSION)) {
    todolog("todo.php | No session found. Starting new session");
    // session isn't started
    session_start();
}

if (!isset($_POST["action"])) {
    todolog("todo.php | No action found. Redirecting to home");
    redirect(VIEWS . "/home.php");
}

$action = $_POST["songId"];
$songs = get_song_object($action);
?>

<div class="container">
<div class="row">
<div class="col-md-12">
    <div class="row">
        <div class="col-md-2">&nbsp;</div>
            <span style="color:red">
            <?php
                if (isset($_SESSION["error"])) {
                    echo $_SESSION["error"];
                    unset($_SESSION["error"]);
                }
            ?>
            </span>
    </div>
</div>
<div class="col-lg-12">
    <div class="row" style="margin-top:10px;margin-bottom:10px">
        <div class="col-xs-12">
            <h3>Current Song List</h3>
        </div>
    </div>
    <div class="row">
    <table class="tableSection1">
    <thead>
        <tr>
            <th>Name</th>
            <th>Author</th>
            <th>Genre</th>
            <th>Filename</th>
            <th>Play Me</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (count($songs) == 0) {
            ?>
            <tr>
                <td colspan="4">
                    No Songs found. Enjoy your day. :)
                </td>
            </tr>
            <?php
        } else {
            for ($index = 0; $index < count($songs); $index++) {
                $song = $songs[$index];
        ?>
                <tr>
                    <td class="name"><?php echo $song[song_name] ?></td>
                    <td class="author"><?php echo $song[song_author] ?></td>
                    <td class="genre"><?php echo $song[song_genre] ?></td>
                    <td class="filename"><?php echo $song[song_filename] ?></td>
                    <td>
                    	<audio controls="play">
                    		<source src="test.mp3" type="audio/ogg">
                    	</audio>
                    </td>
                </tr>
                <?php
            }
        }
        ?>
    </tbody>
    </table>
    </div>
</div>
</div>
</div><!-- /.container -->

<?php
require_once("views/footer.php");
?>

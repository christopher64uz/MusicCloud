<?php
require_once(__DIR__ . "/../config/constants.php");
require_once(__DIR__ . "/../controller/ensure_session.php");

$navMarkup = "";
$navMarkup = $navMarkup . "<li><a href=\"" . VIEWS . "/upload_form.php\">Upload Song</a></li>";
$navMarkup = $navMarkup . "<li><a href=\"" . VIEWS . "/playlist_form.php\">Create Playlist</a></li>";
$navMarkup = $navMarkup . "<li><a href=\"" . CONTROLLER . "/auth.php\">Logout</a></li>";

require_once(__DIR__ . "/../service/data_service.php");

require_once("header.php");
?>
<div class="container">
<div class="row">    
<div class="col-xs-6">
    <div class="row" style="margin-top:10px;margin-bottom:10px">
        <div class="col-xs-12">
            <h3>Create a Playlist from the Music available</h3>
        </div>                
    </div>
    <?php
    $errs = [];
    if (isset($_SESSION["errors"]) && count($_SESSION["errors"] > 0)) {
        $errs = $_SESSION["errors"];
    }
    if(isset($_SESSION[CURRENT_USER])){
        $userId = $_SESSION[CURRENT_USER];    
        $songs = get_songs($userId,$flag=1);
        $playlists = get_playlists($userId);        
    }
    ?>    
    <form action="<?php echo CONTROLLER ?>/playlist.php" method="POST">        
        <div class="row tm5" style="margin-bottom:10px">
            <div class="col-xs-3 col-xs-offset-1">
                <label>Playlist Name : </label><br/>
            </div>
            <div class="col-xs-6">
                <input type="text" name="playlistname" required/>
            </div>
        </div>    
        <div class="row tm5" style="margin-bottom:10px">
            <div class="col-xs-3 col-xs-offset-1">
                <label>Genre : </label><br/>
            </div>
            <div class="col-xs-6">
                <input type="text" name="playlistgenre" required/>
            </div>            
        </div>
        <div class="row tm5" style="margin-bottom:10px">
            <div class="col-xs-3 col-xs-offset-1">
                <label>Song List : </label><br/>
            </div>
            <div class="col-xs-6">
                
                <select name="songlist[]" multiple="multiple" required size="10">
                <?php                
                if (count($songs) == 0) {
                ?>
                    <option disabled="disabled">No Songs found.</option>
                 <?php
                }
                else {                    
                    for ($index = 0; $index < count($songs); $index++) {
                    $song = $songs[$index];                    
                    
                    echo "<option value=\"$song[id]\">$song[author] - $song[filename]</option>";            
                    }
                }
                ?>                    
                </select>    
            </div>            
        </div>
        <div class="row tm5" style="margin-bottom:10px">
            <div class="col-xs-3 col-md-offset-4">
                <input type="submit" name="action" value="Save" />                        
            </div>
        </div>
    </form>
    <div class="row">
        <div class="col-xs-2">&nbsp;</div>
        <div class="col-xs-10">
            <span style="color:red">
            <?php
                if (isset($_SESSION["error"])) {
                    echo $_SESSION["error"];
                    unset($_SESSION["error"]);
                } 
                elseif (isset ($_SESSION["success"])) {
                    echo $_SESSION["success"];
                    unset($_SESSION["success"]);
                }                
            ?>
            </span>
        </div>    
    </div>
</div>
<div class="col-xs-6">
    <div class="row" style="margin-top:10px;margin-bottom:10px">
        <div class="col-xs-12 col-md-offset-3">
            <h3>Current Playlist</h3>
        </div>                
    </div>
    <div class="row">
    <table class="tableSection">
    <thead>
        <tr>
            <th>Name</th>            
            <th>Genre</th>
            <th>Publish</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (count($playlists) == 0) {
            ?>
            <tr>
                <td colspan="4">
                    No Playlist found. Enjoy your day. :)
                </td>
            </tr>
            <?php
        } else {
            for ($index = 0; $index < count($playlists); $index++) {
                $playlist = $playlists[$index];
        ?>
                <tr>
                    <td class="name"><?php echo $playlist[playlist_name] ?></td>
                    <td class="genre"><?php echo $playlist[playlist_genre] ?></td>                    
                    <td class="publish"><button class="facebookppub" name="publish" onclick="" value="<?php echo $playlist[playlist_pid] ?>">Share Facebook</button></td>
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
require_once("footer.php");
?>


<?php
require_once(__DIR__ . "/../config/constants.php");
require_once(__DIR__ . "/../controller/ensure_session.php");

$navMarkup = "";
$navMarkup = $navMarkup . "<li><a href=\"" . VIEWS . "/upload_form.php\">Upload Song</a></li>";
$navMarkup = $navMarkup . "<li><a href=\"" . VIEWS . "/playlist_form.php\">Create Playlist</a></li>";
$navMarkup = $navMarkup . "<li><a href=\"" . CONTROLLER . "/auth.php\">Logout</a></li>";

require_once(__DIR__ . "/../service/data_service.php");

if(isset($_SESSION[CURRENT_USER])){
    $userId = $_SESSION[CURRENT_USER];    
    $songs = get_songs($userId,$flag=0);
}
require_once("header.php");
?>
<div class="container">
<div class="row">    
<div class="col-xs-6">
    <form action="<?php echo CONTROLLER ?>/song.php" method="POST" enctype="multipart/form-data">
        <div class="row" style="margin-top:10px;margin-bottom:10px">
            <div class="col-xs-12 col-md-offset-3">
                <h3>Upload New Songs</h3>
            </div>                
        </div>
        <div class="clearfix"></div>
        <div class="row tm5" style="margin-bottom:8px">
            <div class="col-xs-3 col-xs-offset-2">
                <label>Name : </label><br/>
            </div>
            <div class="col-xs-7">
                <input type="text" name="songname" required/>
            </div>            
        </div>
        <div class="row tm5" style="margin-bottom:8px">
            <div class="col-xs-3 col-xs-offset-2">
                <label>Author : </label><br/>
            </div>
            <div class="col-xs-7">
                <input type="text" name="songauthor" required/>
            </div>            
        </div>
        <div class="row tm5" style="margin-bottom:8px">
            <div class="col-xs-3 col-xs-offset-2">
                <label>Genre : </label><br/>
            </div>
            <div class="col-xs-7">
                <input type="text" name="songgenre" required/>
            </div>            
        </div>
        <div class="row tm5" style="margin-bottom:8px">
            <div class="col-xs-3 col-xs-offset-2">
                <label>Release Date : </label><br/>
            </div>
            <div class="col-xs-7">
                <input type="date" name="songreleasedate" required/>
            </div>            
        </div>
       <div class="row tm5" style="margin-bottom:8px">
            <div class="col-xs-3 col-xs-offset-2">
                <label>Upload Audio : </label><br/>
            </div>
            <div class="col-xs-7">
                <input type="file" name="songfilename" required/>
            </div>            
        </div>
        <div class="row tm5" style="margin-bottom:8px">
            <div class="col-xs-2 col-md-offset-6">
                <input type="submit" name="action" id="upload"  value="Save" />                        
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
            <h3>Current Song List</h3>
        </div>                
    </div>
    <div class="row">
    <table class="tableSection">
    <thead>
        <tr>
            <th>Name</th>
            <th>Author</th>
            <th>Genre</th>
            <th>Filename</th>
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
<script type="text/javascript">
$(document).ready(function () {
    $('#upload').bind("click", function () {
        var audioVal = $('[type=file]').val();
        if (audioVal == '') {
            alert("Upload Audio File");
            return false;
        }
    });
});   
</script>    
<?php
require_once("footer.php");
?>

<?php
require_once(__DIR__ . "/config/constants.php");
require_once(__DIR__ . "/model/data_access.php");
$navMarkup = "";
$navMarkup = $navMarkup . "<li><a href=\"" . VIEWS . "/login.php\">Login</a></li>";
$navMarkup = $navMarkup . "<li><a href=\"" . VIEWS . "/registration_form.php\">Sign up</a></li>";

$list = show_playlist();

require_once(__DIR__ . "/views/header.php");

?>
 <form action="playpage.php" method="POST">
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
            <h3>Current Play List</h3>
        </div>
    </div>
    <div class="row">
    <table>
    <thead>
        <?php
            if (count($list) > 0) {
            ?>
            <tr>
                <th colspan="4">
                    <input type="submit" name="action" value="choose" />
                </th>
            </tr>
        <?php
            }
        ?>
        <tr>
            <th></th>
            <th>Name</th>            
            <th>Genre</th>
            <th>Users Email</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (count($list) == 0) {
            ?>
            <tr>
                <td colspan="4">
                    No Songs found. Enjoy your day. :)
                </td>
            </tr>
            <?php
        } else {
            for ($index = 0; $index < count($list); $index++) {
                $table = $list[$index];
        ?>
                <tr>
                    <td class="select">
                        <input type="radio" name="songId" value="<?php echo $table[playlist_pid]?>" />
                    </td>
                    <td class="name"><?php echo $table[playlist_name] ?></td>                        
                    <td class="genre"><?php echo $table[playlist_genre]?></td>
                    <td class="user_email"><?php echo $table[playlist_users_email] ?></td>                    
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
 </form>     

<?php
require_once(__DIR__ . "/views/footer.php");
session_unset();
session_destroy();
?>

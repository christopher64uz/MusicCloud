<?php
require_once(__DIR__ . "/../config/constants.php");

$navMarkup = "";
$navMarkup = $navMarkup . "<li><a href=\"" . VIEWS . "/login.php\">Login</a></li>";
$navMarkup = $navMarkup . "<li><a href=\"" . VIEWS . "/registration_form.php\">Sign up</a></li>";
//$navMarkup = $navMarkup . "<li><a href=\"" . CONTROLLER . "/auth.php\">Logout</a></li>";

require_once("header.php");
?>
<div class="container">
    <div class="home-content">
        <h1>Create an Online Music Cloud Account</h1>
        <p class="lead">Store and Play Music Online</p>
    </div>
    <?php
    $errs = [];
    if (isset($_SESSION["errors"]) && count($_SESSION["errors"] > 0)) {
        $errs = $_SESSION["errors"];
    }
    ?>
    <form action="<?php echo CONTROLLER ?>/register.php" method="POST">
        <div class="row tm5">
            <div class="col-xs-2 col-md-offset-4">
                <label>First Name: </label><br/>
            </div>
            <div class="col-xs-2">
                <input type="text" name="firstName" required/>
            </div>
            <div class="col-xs-4 error">
                <?php
                if (isset($errs["firstName"])) {
                    echo $errs["firstName"];
                }
                ?>
            </div>
        </div>
        <div class="row tm5">
            <div class="col-xs-2 col-md-offset-4">
                <label>Last Name: </label><br/>
            </div>
            <div class="col-xs-2">
                <input type="text" name="lastName" required/>
            </div>
            <div class="col-xs-4 error">
                <?php
                if (isset($errs["lastName"])) {
                    echo $errs["lastName"];
                }
                ?>
            </div>
        </div>
        <div class="row tm5">
            <div class="col-xs-2 col-md-offset-4">
                <label>User Name: </label><br/>
            </div>
            <div class="col-xs-2">
                <input type="email" name="userName" required/>
            </div>
            <div class="col-xs-4 error">
                <?php
                if (isset($errs["userName"])) {
                    echo $errs["userName"];
                }
                ?>
            </div>
        </div>
        <div class="row tm5">
            <div class="col-xs-2 col-md-offset-4">
                <label>Password: </label><br/>
            </div>
            <div class="col-xs-2">
                <input type="password" name="password" required/>
            </div>
            <div class="col-xs-4 error">
                <?php
                if (isset($errs["password"])) {
                    echo $errs["password"];
                }
                ?>
            </div>
        </div>
        <div class="row tm5">
            <div class="col-xs-2 col-md-offset-6">
                <input type="submit" name="action" value="Register" />                        
            </div>
        </div>
    </form>
</div><!-- /.container -->
<?php
session_unset();
session_destroy();
require_once("footer.php");
?>
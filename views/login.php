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
        <h1>Music Cloud</h1>
        <p class="lead">Store and Play Music Online</p>
    </div>
    <form action="<?php echo CONTROLLER ?>/auth.php" method="POST">
        <?php
        if(isset($_SESSION["errors"])){
        ?>
        <div class="row tm5">
            <div class="col-xs-6 col-md-offset-4 error">
                <?php 
                $errors = $_SESSION["errors"];                        

                if(isset($errors["auth"])){
                    echo $errors["auth"];
                }
                ?>
            </div>
        </div>
        <div class="clearfix" />
        <?php
        }
        ?>

        <?php
        if(isset($_SESSION["success"])){
        ?>
        <div class="row tm5">
            <div class="col-xs-6 col-md-offset-4 success">
                <?php 
                echo $_SESSION["success"];
                ?>
            </div>
        </div>
        <div class="clearfix" />
        <?php
        }
        ?>

        <div class="row tm5">
            <div class="col-xs-2 col-md-offset-4">
                <label>User name: </label><br/>
            </div>
            <div class="col-xs-6">
                <input type="text" name="userName" /> <?php if(isset($errors["validation.userName"])){ echo "<span class='error'>".$errors["validation.userName"] . "</span>"; } ?>
            </div>
        </div>
        <div class="clearfix" /> 
        <div class="row tm5">
            <div class="col-xs-2 col-md-offset-4">
                <label>Password: </label>
            </div>
            <div class="col-xs-6">
                <input type="password" name="password" /> <?php if(isset($errors["validation.password"])){ "<span class='error'>".$errors["validation.password"] . "</span>"; } ?>
            </div>                    
        </div>
        <div class="clearfix" />
        <div class="row tm5">
            <div class="col-xs-2 col-md-offset-6">
                <input type="submit" name="action" value="Login" />                        
                <!--<a href="views/registration_form.php">Register</a>-->
            </div>
        </div>
    </form>
 </div><!-- /.container -->
<?php
require_once("footer.php");
session_unset();
session_destroy();
?>

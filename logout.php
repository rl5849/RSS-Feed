<?php
/**
 * Created by PhpStorm.
 * User: robertliedka
 * Date: 4/17/18
 * Time: 9:00 AM
 */

session_start();

if(isset($_SESSION['username'])){
    unset($_SESSION['username']);
    unset($_SESSION["favs"]);
}


?>
<link rel="stylesheet" type="text/css" href="style.css">

<center>
<h1>Logged out!</h1><br>
<h1><a href="login.php">Click here to Login</a> </h1>
</center>
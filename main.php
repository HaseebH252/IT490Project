<?php
/* Displays user information and some useful messages */
session_start();
// Check if user is logged in using the session variable
if ($_SESSION['active'] != 'true') {
    $_SESSION['message'] = "You must log in before viewing this page!";
    header("location: error.php");
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['search'])) { //user logging in
        require 'back/search.php';
    }
}


?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link href="css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <link rel='stylesheet prefetch' href='css/font-awesome.min.css'>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
<div class="form">

    <h1>Welcome</h1>


        <div>
            <h1>Enter Zipcode to Search</h1>

            <form action="back/search.php" autocomplete="off" method="post">

                <div class="field-wrap">
                    <label>
                        Zip Code<span class="req">*</span>
                    </label>
                    <input type="text" required autocomplete="off" name="zip"/>
                </div>
                <button class="button button-block" type="submit" value="search" />Search</button>

            </form>

        </div>

        <div class="row">
                <div class=col-md-12 style="padding: 2%;">
                <a href="logout.php">
                    <button class="button button-block" name="logout">
                    Log Out</button></a>
                </div>
            </div>


    <script src='js/jquery.min.js'></script>
    <script src="js/index.js"></script>

</body>
</html>
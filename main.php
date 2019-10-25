<?php
/* Displays user information and some useful messages */
session_start();
// Check if user is logged in using the session variable
if ($_SESSION['logged_in'] != 1) {
    $_SESSION['message'] = "You must log in before viewing your profile page!";
    header("location: error.php");
} else {
    // Makes it easier to read
    $first_name = $_SESSION['first_name'];
    $last_name = $_SESSION['last_name'];
    $email = $_SESSION['email'];
    $active = $_SESSION['active'];
}


?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link href="css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery-1.11.1.min.js"></script>

    <link rel='stylesheet prefetch' href='css/font-awesome.min.css'>
    <title>Welcome <?= $first_name . ' ' . $last_name ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
<div class="form">

    <h1>Welcome</h1>


    <h2><?php echo $first_name . ' ' . $last_name; ?></h2>

    <div class="row">
        <div class="col-md-12">
            <form>
                <div id="search">
                    <h1>Search for a House</h1>

                    <form action="search.php" method="post" autocomplete="off">

                        <div class="field-wrap">
                            <label>
                                Zip Code<span class="req">*</span>
                            </label>
                            <input id="zip" name="zip" type="text" pattern="[0-9]*">

                        </div>
                        <input class="button button-block" type="submit" value="Submit">
                    </form>
                </div>

        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="field-wrap"
                <a href="saved.php">
                    <button class="button button-block" name="Saved Addresses"/>
                    Saved Addresses</button></a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="field-wrap"
                <a href="logout.php">
                    <button class="button button-block" name="logout"/>
                    Log Out</button></a>
            </div>
        </div>
    </div>

    <script src='js/jquery.min.js'></script>
    <script src="js/index.js"></script>

</body>
</html>
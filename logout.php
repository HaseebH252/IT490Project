<?php
/* Log out process, unsets and destroys session variables */
session_start();
session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Logged out</title>
    <?php include 'css/css.html'; ?>
</head>

<body>
<div class="form">
    <h1><?= 'You have been successfully logged out.'; ?></h1>

    <a href="index.php"><button class="button button-block"/>Home</button></a>

</div>
</body>
</html>
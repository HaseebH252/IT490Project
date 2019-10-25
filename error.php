
<?php
/* Displays all error messages */
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Error</title>
</head>
<body>
<div class="form">
    <h1>Error</h1>
    <p>
        <?php

        require('rmq/authClient.php');

        $authenticate = new RabbitMQAuthClient();

        if( isset($_SESSION['message']) AND !empty($_SESSION['message']) ):
            echo $_SESSION['message'];
            $date = date_create();
            file_put_contents('logs/error.log', "[".date_format($date, 'm-d-Y H:i:s')."] ".$_SESSION['message'].PHP_EOL, FILE_APPEND);


            $type ='error';
            $message =$_SESSION['message'].PHP_EOL;
            $logging = $authenticate->log($type,$message);

            echo $logging;




        else:
            header( "location: index.php" );
        endif;
        ?>
    </p>
    <a href="index.php"><button class="button button-block"/>Home</button></a>
</div>
</body>
</html>
<?php
/* Main page with two forms: sign up and log in */
require ('back/db.php');
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sign-Up/Login Form</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if (isset($_POST['login'])) { //user logging in
        require 'back/login.php';
    }
    elseif (isset($_POST['register'])) { //user registering
        require 'back/register.php';
    }
}
?>
<body>
<div class="form">

    <ul class="tab-group">
        <li class="tab"><a href="#signup">Create Account</a></li>
        <li class="tab active"><a href="#login">Log In</a></li>
    </ul>

    <div class="tab-content">

        <div id="login">
            <h1>Log in to continue</h1>

            <form action="index.php" method="post" autocomplete="off">

                <div class="field-wrap">
                    <label>
                        Email Address<span class="req">*</span>
                    </label>
                    <input type="email" required autocomplete="off" name="email"/>
                </div>

                <div class="field-wrap">
                    <label>
                        Password<span class="req">*</span>
                    </label>
                    <input type="password" required autocomplete="off" name="password"/>
                </div>


                <button class="button button-block" name="login" />Log In</button>

            </form>

        </div>

        <div id="signup">
            <h1>Create an Account</h1>

            <form action="index.php" method="post" autocomplete="off">

                <div class="top-row">
                    <div class="field-wrap">
                        <label>
                            First Name<span class="req">*</span>
                        </label>
                        <input type="text" required autocomplete="off" name='firstname' />
                    </div>

                    <div class="field-wrap">
                        <label>
                            Last Name<span class="req">*</span>
                        </label>
                        <input type="text"required autocomplete="off" name='lastname' />
                    </div>
                </div>

                <div class="field-wrap">
                    <label>
                        Email Address<span class="req">*</span>
                    </label>
                    <input type="email"required autocomplete="off" name='email' />
                </div>

                <div class="field-wrap">
                    <label>
                        Password<span class="req">*</span>
                    </label>
                    <input type="password"required autocomplete="off" name='password'/>
                </div>

                <button type="submit" class="button button-block" name="register" />Register</button>

            </form>

        </div>

    </div><!-- tab-content -->

</div> <!-- /form -->
<script src='js/jquery.min.js'></script>
<script src="js/index.js"></script>

</body>
</html>
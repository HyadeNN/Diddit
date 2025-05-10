<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

include("classes/connect.php");
include("classes/login.php");

$email = "";
$password = "";

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $login = new Login();
    $result = $login->evaluate($_POST);

    if($result != "")
    {
        echo "<div style='text-align:center;font-size:12px;color:white;background-color:grey;'>";
        echo "<br>The following errors occured:<br><br>";
        echo $result;
        echo "</div>";
    }else
    {
        header("Location: profile.php");
        die;
    }

    $email = $_POST['email'];
    $password = $_POST['password'];
}
?>

<html>

<head>
    <title>LinkedOut | Log in</title>
</head>

<style>
    a{color: #0a66c2; text-decoration: none;}
    body {
        font-family: -apple-system, system-ui, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', 'Fira Sans', Ubuntu, Oxygen, 'Oxygen Sans', Cantarell, 'Droid Sans', 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Lucida Grande', Helvetica, Arial, sans-serif;
        background-color: #f3f2ef;
        margin: 0;
    }
    #bar{
        height:70px;
        background-color: #ffffff;
        color: #0a66c2;
        padding: 4px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }

    #signup_button{
        background-color: #0a66c2;
        color: white;
        width: 70px;
        text-align: center;
        padding:8px;
        border-radius: 24px;
        float:right;
        margin-top: 12px;
        margin-right: 20px;
    }

    #bar2{
        background-color: #ffffff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        width:400px;
        margin:auto;
        margin-top: 50px;
        padding:20px;
        padding-top: 50px;
        text-align: center;
        border-radius: 8px;
    }

    #text{
        height: 40px;
        width: 300px;
        border-radius: 4px;
        border:solid 1px #ccc;
        padding: 4px 10px;
        font-size: 14px;
        margin-bottom: 10px;
    }

    #button{
        width: 300px;
        height: 48px;
        border-radius: 24px;
        font-weight: bold;
        border:none;
        background-color: #0a66c2;
        color: white;
        font-size: 16px;
        cursor: pointer;
    }
    #button:hover {
        background-color: #004182;
    }

</style>

<body>
<div id="bar">
    <div style="font-size: 35px;font-weight:bold;color:#0a66c2;padding:10px;">LinkedOut</div>
    <a href="signup.php">
        <div id="signup_button">Sign Up</div>
    </a>
</div>

<div id="bar2">
    <form method="post">
        <h2 style="color: #000;">Sign in to LinkedOut</h2>
        <p style="color: #666;font-size:14px;">Stay updated on your professional world</p>

        <input name="email" value="<?php echo $email ?>" type="text" id="text" placeholder="Email"><br>
        <input name="password" value="<?php echo $password ?>" type="password" id="text" placeholder="Password"><br><br>

        <input type="submit" id="button" value="Sign in">
        <br><br>
        <div style="color:#666;font-size:14px;">
            New to LinkedOut? <a href="signup.php">Join now</a>
        </div>
    </form>
</div>
</body>
</html>
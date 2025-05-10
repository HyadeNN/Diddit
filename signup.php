<?php

include("classes/connect.php");
include("classes/signup.php");

$first_name = "";
$last_name = "";
$gender = "";
$email = "";

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $signup = new Signup();
    $result = $signup->evaluate($_POST);

    if($result != "")
    {
        echo "<div style='text-align:center;font-size:12px;color:white;background-color:grey;'>";
        echo "<br>The following errors occured:<br><br>";
        echo $result;
        echo "</div>";
    }else
    {
        header("Location: login.php");
        die;
    }

    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
}
?>

<html>

<head>
    <title>LinkedOut | Sign Up</title>
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

    #login_button{
        background-color: white;
        color: #0a66c2;
        border: 1px solid #0a66c2;
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
        padding-top: 30px;
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
    <a href="login.php">
        <div id="login_button">Sign In</div>
    </a>
</div>

<div id="bar2">
    <form method="post" action="">
        <h2 style="color: #000;">Make the most of your professional life</h2>

        <input value="<?php echo $first_name ?>" name="first_name" type="text" id="text" placeholder="First name"><br>
        <input value="<?php echo $last_name ?>" name="last_name" type="text" id="text" placeholder="Last name"><br>

        <select id="text" name="gender" style="width: 320px;">
            <option value="">Select gender</option>
            <?php if($gender == "Male"): ?>
                <option selected value="Male">Male</option>
            <?php else: ?>
                <option value="Male">Male</option>
            <?php endif; ?>

            <?php if($gender == "Female"): ?>
                <option selected value="Female">Female</option>
            <?php else: ?>
                <option value="Female">Female</option>
            <?php endif; ?>
        </select>
        <br>

        <input value="<?php echo $email ?>" name="email" type="text" id="text" placeholder="Email"><br>
        <input name="password" type="password" id="text" placeholder="Password (6+ characters)"><br>
        <input name="password2" type="password" id="text" placeholder="Confirm Password"><br><br>

        <div style="color:#666;font-size:12px;width:300px;margin:auto;text-align:center;margin-bottom:15px;">
            By clicking Join now, you agree to the LinkedOut User Agreement, Privacy Policy, and Cookie Policy.
        </div>

        <input type="submit" id="button" value="Join now">
        <br><br>
        <div style="color:#666;font-size:14px;">
            Already on LinkedOut? <a href="login.php">Sign in</a>
        </div>
    </form>
</div>
</body>
</html>
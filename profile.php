<?php

include("classes/autoload.php");

$login = new Login();
$_SESSION['diddit_userid'] = isset($_SESSION['diddit_userid']) ? $_SESSION['diddit_userid'] : 0;

$user_data = $login->check_login($_SESSION['diddit_userid'],false);

$USER = $user_data;

if(isset($_GET['id']) && is_numeric($_GET['id'])){

    $profile = new Profile();
    $profile_data = $profile->get_profile($_GET['id']);

    if(is_array($profile_data)){
        $user_data = $profile_data[0];
    }

}

//posting starts here
if($_SERVER['REQUEST_METHOD'] == "POST")
{

    include("change_image.php");

    if(isset($_POST['first_name'])){

        $settings_class = new Settings();
        $settings_class->save_settings($_POST,$_SESSION['diddit_userid']);

    }else{

        $post = new Post();
        $id = $_SESSION['diddit_userid'];
        $result = $post->create_post($id, $_POST,$_FILES);

        if($result == "")
        {
            header("Location: profile.php");
            die;
        }else
        {

            echo "<div style='text-align:center;font-size:12px;color:#04161c;background-color:grey;'>";
            echo "<br>The following errors occured:<br><br>";
            echo $result;
            echo "</div>";
        }
    }

}

//collect posts
$post = new Post();
$id = $user_data['userid'];

$posts = $post->get_posts($id);

//collect connections
$user = new User();

$connections = $user->get_following($user_data['userid'],"user");

$image_class = new Image();

//check if this is from a notification
if(isset($_GET['notif'])){
    notification_seen($_GET['notif']);
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Profile | LinkedOut</title>
</head>

<style type="text/css">
    a{color: #0a66c2; text-decoration: none;}
    body {
        font-family: -apple-system, system-ui, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', 'Fira Sans', Ubuntu, Oxygen, 'Oxygen Sans', Cantarell, 'Droid Sans', 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Lucida Grande', Helvetica, Arial, sans-serif;
        background-color: #f3f2ef;
        margin: 0;
    }

    #blue_bar{
        height: 70px;
        background-color: white;
        color: #0a66c2;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }

    #search_box{
        width: 400px;
        height: 36px;
        border-radius: 4px;
        border:solid 1px #ccc;
        padding: 4px 10px;
        font-size: 14px;
        background-image: url(search.png);
        background-repeat: no-repeat;
        background-position: right 10px center;
    }

    #textbox{
        width: 100%;
        height: 40px;
        border-radius: 4px;
        border: solid 1px #ccc;
        padding: 8px;
        font-size: 14px;
        margin: 5px 0;
    }

    #profile_pic{
        width: 150px;
        margin-top: -75px;
        border-radius: 50%;
        border: 4px solid white;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    #menu_buttons{
        color: #666;
        font-size: 14px;
        font-weight: 500;
        padding: 10px 20px;
        display: inline-block;
        margin: 2px;
    }
    #menu_buttons:hover {
        color: #0a66c2;
        border-bottom: 2px solid #0a66c2;
    }

    #connections_img{
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 50%;
        float: left;
        margin: 8px;
        border: 1px solid #ddd;
    }

    #connections_bar{
        background-color: white;
        min-height: 400px;
        margin-top: 20px;
        color: #666;
        padding: 8px 20px;
        border-radius: 10px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }

    #connections{
        clear: both;
        font-size: 14px;
        font-weight: 500;
        color: #0a66c2;
        padding: 5px 0;
        text-align: center;
    }

    textarea{
        width: 100%;
        border: 1px solid #ddd;
        font-family: inherit;
        font-size: 14px;
        padding: 10px;
        height: 100px;
        resize: none;
        border-radius: 4px;
    }

    #post_button{
        float: right;
        background-color: #0a66c2;
        border: none;
        color: white;
        padding: 8px 16px;
        font-size: 14px;
        border-radius: 24px;
        width: auto;
        min-width: 60px;
        cursor: pointer;
    }
    #post_button:hover {
        background-color: #004182;
    }

    #post_bar{
        margin-top: 20px;
        background-color: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }

    #post{
        padding: 15px;
        font-size: 14px;
        display: flex;
        margin-bottom: 20px;
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }

</style>

<body>
<br>
<?php include("header.php"); ?>

<!--change profile image area-->
<div id="change_profile_image" style="display:none;position:absolute;width: 100%;height: 100%;background-color: rgba(0,0,0,0.5);z-index:10;">
    <div style="max-width:600px;margin:auto;margin-top:50px;background-color:white;padding:20px;border-radius:10px;">

        <form method="post" action="profile.php?change=profile" enctype="multipart/form-data">
            <div style="padding: 10px;">
                <div style="text-align:right;">
                    <span style="cursor:pointer;color:#aaa;font-size:18px;" onclick="hide_change_profile_image()">✕</span>
                </div>
                <h3 style="margin-top:0">Update Profile Picture</h3>
                <input type="file" name="file" style="margin-bottom:10px;"><br>
                <input id="post_button" type="submit" style="width:120px;" value="Update">
                <br>
                <div style="text-align: center;">
                    <br>
                    <?php
                    echo "<img src='$user_data[profile_image]' style='max-width:100%;border-radius:5px;' >";
                    ?>
                </div>
            </div>
        </form>
    </div>
</div>

<!--change cover image area-->
<div id="change_cover_image" style="display:none;position:absolute;width: 100%;height: 100%;background-color: rgba(0,0,0,0.5);z-index:10;">
    <div style="max-width:600px;margin:auto;margin-top:50px;background-color:white;padding:20px;border-radius:10px;">

        <form method="post" action="profile.php?change=cover" enctype="multipart/form-data">
            <div style="padding: 10px;">
                <div style="text-align:right;">
                    <span style="cursor:pointer;color:#aaa;font-size:18px;" onclick="hide_change_cover_image()">✕</span>
                </div>
                <h3 style="margin-top:0">Update Cover Photo</h3>
                <input type="file" name="file" style="margin-bottom:10px;"><br>
                <input id="post_button" type="submit" style="width:120px;" value="Update">
                <br>
                <div style="text-align: center;">
                    <br>
                    <?php
                    echo "<img src='$user_data[cover_image]' style='max-width:100%;border-radius:5px;' >";
                    ?>
                </div>
            </div>
        </form>
    </div>
</div>

<!--cover area-->
<div style="width: 800px;margin:auto;min-height: 400px;padding-top:20px;">

    <div style="background-color: white;text-align: center;border-radius:10px;box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);">

        <?php
        $image = "images/cover_image.jpg";
        if(file_exists($user_data['cover_image']))
        {
            $image = $image_class->get_thumb_cover($user_data['cover_image']);
        }
        ?>

        <img src="<?php echo $image ?>" style="width:100%;border-top-left-radius:10px;border-top-right-radius:10px;">


        <span style="font-size: 12px;">
					<?php
                    $image = "images/user_male.jpg";
                    if($user_data['gender'] == "Female")
                    {
                        $image = "images/user_female.jpg";
                    }
                    if(file_exists($user_data['profile_image']))
                    {
                        $image = $image_class->get_thumb_profile($user_data['profile_image']);
                    }
                    ?>

					<img id="profile_pic" src="<?php echo $image ?>"><br/>

					<?php if(i_own_content($user_data)):?>

                        <a onclick="show_change_profile_image(event)" style="text-decoration: none;color:#0a66c2;" href="change_profile_image.php?change=profile">Change Profile Photo</a> |
						<a onclick="show_change_cover_image(event)" style="text-decoration: none;color:#0a66c2;" href="change_profile_image.php?change=cover">Change Cover Photo</a>

                    <?php endif; ?>

				</span>
        <br>
        <div style="font-size: 20px;color: #000;font-weight:bold;margin-top:10px;">
            <a href="profile.php?id=<?php echo $user_data['userid'] ?>">
                <?php echo $user_data['first_name'] . " " . $user_data['last_name']  ?>
            </a>

            <?php
            if(isset($user_data['job_title']) && !empty($user_data['job_title'])) {
                echo "<div style='font-size:16px;font-weight:normal;color:#666;'>" . $user_data['job_title'];

                if(isset($user_data['company']) && !empty($user_data['company'])) {
                    echo " at " . $user_data['company'];
                }

                echo "</div>";
            }
            ?>

            <?php
            $endorsements = "";
            if($user_data['endorsements'] > 0){
                $endorsements = "(" . $user_data['endorsements'] . " Connections)";
            }
            ?>
            <br>
            <a href="like.php?type=user&id=<?php echo $user_data['userid'] ?>">
                <input id="post_button" type="button" value="Connect <?php echo $endorsements ?>" style="margin-right:10px;margin-bottom:15px;">
            </a>
        </div>
        <br>

        <div style="display:flex;justify-content:center;border-top:1px solid #ddd;background-color:#fff;">
            <a href="index.php"><div id="menu_buttons">Home</div></a>
            <a href="profile.php?section=about&id=<?php echo $user_data['userid'] ?>"><div id="menu_buttons">About</div></a>
            <a href="profile.php?section=connections&id=<?php echo $user_data['userid'] ?>"><div id="menu_buttons">Connections</div></a>
            <a href="profile.php?section=experience&id=<?php echo $user_data['userid'] ?>"><div id="menu_buttons">Experience</div></a>
            <a href="profile.php?section=education&id=<?php echo $user_data['userid'] ?>"><div id="menu_buttons">Education</div></a>
            <a href="profile.php?section=skills&id=<?php echo $user_data['userid'] ?>"><div id="menu_buttons">Skills</div></a>

            <?php
            if($user_data['userid'] == $_SESSION['diddit_userid']){
                echo '<a href="profile.php?section=settings&id='.$user_data['userid'].'"><div id="menu_buttons">Settings</div></a>';
            }
            ?>
        </div>
    </div>

    <!--below cover area-->

    <?php
    $section = "default";
    if(isset($_GET['section'])){
        $section = $_GET['section'];
    }

    if($section == "default"){
        include("profile_content_default.php");
    }elseif($section == "connections"){
        include("profile_content_connections.php");
    }elseif($section == "about"){
        include("profile_content_about.php");
    }elseif($section == "experience"){
        include("profile_content_experience.php");
    }elseif($section == "education"){
        include("profile_content_education.php");
    }elseif($section == "skills"){
        include("profile_content_skills.php");
    }elseif($section == "settings"){
        include("profile_content_settings.php");
    }
    ?>
</div>
</body>
</html>

<script type="text/javascript">

    function show_change_profile_image(event){
        event.preventDefault();
        var profile_image = document.getElementById("change_profile_image");
        profile_image.style.display = "block";
    }

    function hide_change_profile_image(){
        var profile_image = document.getElementById("change_profile_image");
        profile_image.style.display = "none";
    }

    function show_change_cover_image(event){
        event.preventDefault();
        var cover_image = document.getElementById("change_cover_image");
        cover_image.style.display = "block";
    }

    function hide_change_cover_image(){
        var cover_image = document.getElementById("change_cover_image");
        cover_image.style.display = "none";
    }

    window.onkeydown = function(key){
        if(key.keyCode == 27){
            //esc key was pressed
            hide_change_profile_image();
            hide_change_cover_image();
        }
    }
</script>
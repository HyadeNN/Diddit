<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("classes/autoload.php");

$login = new Login();
$user_data = $login->check_login($_SESSION['diddit_userid']);

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
    $post = new Post();
    $id = $_SESSION['diddit_userid'];
    $result = $post->create_post($id, $_POST,$_FILES);

    if($result == "")
    {
        header("Location: index.php");
        die;
    }else
    {
        echo "<div style='text-align:center;font-size:12px;color:#1A282D;background-color:grey;'>";
        echo "<br>The following errors occured:<br><br>";
        echo $result;
        echo "</div>";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>LinkedOut | Professional Network</title>
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
        background-color: #0a66c2;
        color: white;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }

    #search_box{
        width: 400px;
        height: 36px;
        border-radius: 4px;
        border: none;
        padding: 4px 10px;
        font-size: 14px;
    }

    #profile_pic{
        width: 60px;
        height: 60px;
        border-radius: 50%;
        border: 2px solid white;
        object-fit: cover;
    }

    #menu_buttons{
        color: #666;
        font-size: 14px;
        font-weight: 500;
        padding: 10px 20px;
        display: inline-block;
        margin: 2px;
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
        min-height: 200px;
        margin-top: 20px;
        color: #666;
        padding: 8px;
        border-radius: 10px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }

    #user_info{
        background-color: white;
        text-align: center;
        color: #666;
        padding: 20px 10px;
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
        height: 60px;
        resize: none;
        border-radius: 8px;
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
        margin-top: 10px;
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

    #create_post {
        background-color: white;
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    .start-post {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }

    .post-options {
        display: flex;
        justify-content: space-between;
        margin-top: 10px;
        border-top: 1px solid #eee;
        padding-top: 10px;
    }

    .post-option {
        display: flex;
        align-items: center;
        color: #666;
        font-size: 14px;
        padding: 8px;
        border-radius: 4px;
        cursor: pointer;
    }

    .post-option:hover {
        background-color: #f3f2ef;
    }

    .sidebar-section {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
        overflow: hidden;
    }

    .sidebar-section-title {
        font-size: 16px;
        font-weight: 600;
        padding: 15px;
        border-bottom: 1px solid #eee;
    }

    .sidebar-section-content {
        padding: 15px;
    }

    .nav-item {
        display: flex;
        align-items: center;
        padding: 8px 15px;
        color: #666;
        font-size: 14px;
        text-decoration: none;
    }

    .nav-item:hover {
        background-color: #f3f2ef;
    }

    .nav-icon {
        width: 24px;
        height: 24px;
        margin-right: 10px;
    }

</style>

<body>
<br>
<?php include("header.php"); ?>

<!--main content area-->
<div style="width: 800px;margin:auto;padding-top:20px;display:flex;">

    <!--left sidebar-->
    <div style="flex:1;margin-right:20px;">
        <div id="user_info">
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
            <a href="profile.php">
                <img id="profile_pic" src="<?php echo $image ?>">
            </a>
            <div style="font-size:18px;font-weight:bold;margin:10px 0 5px 0;">
                <a href="profile.php" style="color:#000;">
                    <?php echo $user_data['first_name'] . " " . $user_data['last_name'] ?>
                </a>
            </div>
            <?php
            if(isset($user_data['headline']) && !empty($user_data['headline'])) {
                echo "<div style='color:#666;font-size:14px;'>" . $user_data['headline'] . "</div>";
            } else if(isset($user_data['job_title']) && !empty($user_data['job_title'])) {
                echo "<div style='color:#666;font-size:14px;'>" . $user_data['job_title'];

                if(isset($user_data['company']) && !empty($user_data['company'])) {
                    echo " at " . $user_data['company'];
                }

                echo "</div>";
            }
            ?>
        </div>

        <div class="sidebar-section" style="margin-top:20px">
            <div class="sidebar-section-title">Your Dashboard</div>
            <div class="sidebar-section-content">
                <div style="font-size:16px;font-weight:600;color:#666;margin-bottom:5px;">
                    <?php
                    $connections_count = 0;
                    $user_class = new User();
                    $connections = $user_class->get_following($user_data['userid'],"user");
                    if(is_array($connections)){
                        $connections_count = count($connections);
                    }
                    echo $connections_count;
                    ?>
                    Connections
                </div>
                <div style="color:#666;font-size:12px;margin-bottom:10px;">Grow your network</div>

                <div style="font-size:16px;font-weight:600;color:#666;margin-bottom:5px;">
                    <?php
                    $DB = new Database();
                    $sql = "select count(*) as post_count from posts where userid = '$user_data[userid]' and parent = 0";
                    $result = $DB->read($sql);
                    $post_count = 0;
                    if(is_array($result)){
                        $post_count = $result[0]['post_count'];
                    }
                    echo $post_count;
                    ?>
                    Posts
                </div>
                <div style="color:#666;font-size:12px;">Share your thoughts</div>
            </div>
        </div>
    </div>

    <!--posts area-->
    <div style="flex:2.5;">
        <!-- Create post area -->
        <div id="create_post">
            <div class="start-post">
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
                <img src="<?php echo $image ?>" style="width:40px;height:40px;border-radius:50%;margin-right:10px;">

                <form method="post" enctype="multipart/form-data" style="width:100%;">
                    <textarea name="post" placeholder="Share an article, photo, video or idea"></textarea>
                    <input type="file" name="file" style="margin:10px 0;">
                    <input id="post_button" type="submit" value="Post">
                </form>
            </div>
            <div class="post-options">
                <div class="post-option">
                    <span style="font-size:18px;margin-right:5px;">&#128247;</span> Photo
                </div>
                <div class="post-option">
                    <span style="font-size:18px;margin-right:5px;">&#127909;</span> Video
                </div>
                <div class="post-option">
                    <span style="font-size:18px;margin-right:5px;">&#128197;</span> Event
                </div>
                <div class="post-option">
                    <span style="font-size:18px;margin-right:5px;">&#128221;</span> Write article
                </div>
            </div>
        </div>

        <!--posts-->
        <div id="post_bar">
            <?php
            $page_number = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $page_number = ($page_number < 1) ? 1 : $page_number;

            $limit = 10;
            $offset = ($page_number - 1) * $limit;

            $DB = new Database();
            $user_class = new User();
            $image_class = new Image();

            $followers = $user_class->get_following($_SESSION['diddit_userid'],"user");

            $follower_ids = false;
            if(is_array($followers)){
                $follower_ids = array_column($followers, "userid");
                $follower_ids = implode("','", $follower_ids);
            }

            if($follower_ids){
                $myuserid = $_SESSION['diddit_userid'];
                $sql = "select * from posts where parent = 0 and (userid = '$myuserid' || userid in('" .$follower_ids. "')) order by id desc limit $limit offset $offset";
                $posts = $DB->read($sql);
            }

            if(isset($posts) && $posts)
            {
                foreach ($posts as $ROW) {
                    $user = new User();
                    $ROW_USER = $user->get_user($ROW['userid']);
                    include("post_display.php");
                }
            } else {
                echo '<div style="text-align:center;padding:20px;color:#666;">
								<div style="font-size:18px;font-weight:bold;margin-bottom:10px;">No posts to show</div>
								<div style="font-size:14px;">Connect with more professionals to see their updates</div>
							</div>';
            }

            //get current url
            $pg = pagination_link();

            ?>
            <div style="display:flex;justify-content:space-between;margin-top:20px;">
                <a href="<?= $pg['prev_page'] ?>">
                    <button id="post_button" style="float:none;background-color:#fff;color:#0a66c2;border:1px solid #0a66c2;">Previous Page</button>
                </a>
                <a href="<?= $pg['next_page'] ?>">
                    <button id="post_button" style="float:none;">Next Page</button>
                </a>
            </div>
        </div>
    </div>

    <!--right sidebar-->
    <div style="flex:1;margin-left:20px;">
        <div class="sidebar-section">
            <div class="sidebar-section-title">Add to your feed</div>
            <div class="sidebar-section-content">
                <?php
                // Get users that current user is not following
                $sql = "select * from users where userid != '$user_data[userid]' limit 3";
                $suggestions = $DB->read($sql);

                if(is_array($suggestions)){
                    foreach($suggestions as $suggestion){
                        $image = "images/user_male.jpg";
                        if($suggestion['gender'] == "Female"){
                            $image = "images/user_female.jpg";
                        }
                        if(file_exists($suggestion['profile_image'])){
                            $image = $image_class->get_thumb_profile($suggestion['profile_image']);
                        }

                        echo '<div style="display:flex;align-items:center;margin-bottom:15px;">';
                        echo '<img src="'.$image.'" style="width:48px;height:48px;border-radius:50%;margin-right:10px;">';
                        echo '<div>';
                        echo '<div style="font-weight:600;"><a href="profile.php?id='.$suggestion['userid'].'" style="color:#000;">'.$suggestion['first_name'].' '.$suggestion['last_name'].'</a></div>';

                        if(isset($suggestion['job_title']) && !empty($suggestion['job_title'])) {
                            echo "<div style='color:#666;font-size:12px;'>" . $suggestion['job_title'];

                            if(isset($suggestion['company']) && !empty($suggestion['company'])) {
                                echo " at " . $suggestion['company'];
                            }

                            echo "</div>";
                        }

                        echo '<a href="like.php?type=user&id='.$suggestion['userid'].'" style="display:inline-block;margin-top:5px;color:#0a66c2;font-size:14px;font-weight:600;border:1px solid #0a66c2;border-radius:16px;padding:4px 12px;">+ Connect</a>';
                        echo '</div>';
                        echo '</div>';
                    }
                }
                ?>
            </div>
        </div>

        <div class="sidebar-section" style="margin-top:20px">
            <div class="sidebar-section-content" style="padding:0">
                <a href="jobs.php" class="nav-item">
                    <span style="font-size:18px;margin-right:5px;">&#128188;</span> Find a new job
                </a>
                <a href="#" class="nav-item">
                    <span style="font-size:18px;margin-right:5px;">&#128197;</span> Events
                </a>
                <a href="#" class="nav-item">
                    <span style="font-size:18px;margin-right:5px;">&#128101;</span> Groups
                </a>
                <a href="#" class="nav-item">
                    <span style="font-size:18px;margin-right:5px;">&#128240;</span> News
                </a>
                <a href="#" class="nav-item">
                    <span style="font-size:18px;margin-right:5px;">&#127891;</span> Learning
                </a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
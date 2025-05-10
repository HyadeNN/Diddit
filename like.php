<?php

include("classes/autoload.php");

$login = new Login();
$user_data = $login->check_login($_SESSION['diddit_userid']);


if(isset($_SERVER['HTTP_REFERER'])){

    $return_to = $_SERVER['HTTP_REFERER'];
}else{
    $return_to = "profile.php";
}

if(isset($_GET['type']) && isset($_GET['id'])){

    if(is_numeric($_GET['id'])){

        $allowed[] = 'post';
        $allowed[] = 'user';
        $allowed[] = 'comment';
        $allowed[] = 'skill';

        if(in_array($_GET['type'], $allowed)){

            $post = new Post();
            $user_class = new User();

            // Handle the post like action
            $post->like_post($_GET['id'], $_GET['type'], $_SESSION['diddit_userid']);

            // For user type, also handle follow/connection action
            if($_GET['type'] == "user"){
                $user_class->follow_user($_GET['id'], $_GET['type'], $_SESSION['diddit_userid']);
            }
        }

    }

}


header("Location: ". $return_to);
die;
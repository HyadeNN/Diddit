<div style="display: flex;">

    <!--connections area-->
    <div style="min-height: 400px;flex:1;">

        <div id="connections_bar">

            Connections<br>

            <?php
            $user_class = new User();
            $connections = $user_class->get_following($user_data['userid'],"user");

            if(is_array($connections) && count($connections) > 0)
            {
                foreach ($connections as $connection) {
                    # code...

                    $FRIEND_ROW = $user_class->get_user($connection['userid']);
                    if(is_array($FRIEND_ROW)) {
                        include("user.php");
                    }
                }
            }
            ?>

        </div>

    </div>

    <!--posts area-->
    <div style="min-height: 400px;flex:2.5;padding: 20px;padding-right: 0px;">

        <div style="border:solid thin #aaa; padding: 10px;background-color: white;border-radius:8px;">

            <form method="post" enctype="multipart/form-data">

                <textarea name="post" placeholder="Share an article, photo, video or idea"></textarea>
                <input type="file" name="file">
                <input id="post_button" type="submit" value="Post">
                <br>
            </form>
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
                    # code...

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
</div>
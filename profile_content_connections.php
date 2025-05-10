<div style="min-height: 400px;width:100%;background-color: white;border-radius:10px;box-shadow:0 0 5px rgba(0,0,0,0.1);margin-top:20px;padding:20px;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;border-bottom:1px solid #eee;padding-bottom:15px;">
        <h3 style="margin:0;">Connections</h3>

        <?php
        $user_class = new User();
        $connections = $user_class->get_following($user_data['userid'],"user");
        $connection_count = is_array($connections) ? count($connections) : 0;
        ?>

        <div style="color:#666;">
            <span style="font-weight:600;font-size:18px;"><?php echo $connection_count; ?></span> connections
        </div>
    </div>

    <div style="display:flex;flex-wrap:wrap;gap:20px;justify-content:flex-start;">
        <?php
        if(is_array($connections) && count($connections) > 0){
            foreach ($connections as $connection) {
                $FRIEND_ROW = $user_class->get_user($connection['userid']);
                if(is_array($FRIEND_ROW)){
                    $image = "images/user_male.jpg";
                    if($FRIEND_ROW['gender'] == "Female"){
                        $image = "images/user_female.jpg";
                    }
                    if(file_exists($FRIEND_ROW['profile_image'])){
                        $image = $image_class->get_thumb_profile($FRIEND_ROW['profile_image']);
                    }

                    echo '<div style="width:calc(33.333% - 20px);background-color:#f3f2ef;border-radius:10px;overflow:hidden;box-shadow:0 0 5px rgba(0,0,0,0.05);">';
                    echo '<div style="height:60px;background-color:#ddd;"></div>'; // Cover placeholder
                    echo '<div style="padding:0 15px 15px;text-align:center;position:relative;">';
                    echo '<img src="'.$image.'" style="width:80px;height:80px;border-radius:50%;border:4px solid white;margin-top:-40px;object-fit:cover;">';
                    echo '<h4 style="margin:10px 0 5px;"><a href="profile.php?id='.$FRIEND_ROW['userid'].'" style="color:#000;text-decoration:none;">'.$FRIEND_ROW['first_name'].' '.$FRIEND_ROW['last_name'].'</a></h4>';

                    if(isset($FRIEND_ROW['job_title']) && !empty($FRIEND_ROW['job_title'])) {
                        echo "<div style='color:#666;font-size:14px;'>" . $FRIEND_ROW['job_title'];

                        if(isset($FRIEND_ROW['company']) && !empty($FRIEND_ROW['company'])) {
                            echo " at " . $FRIEND_ROW['company'];
                        }

                        echo "</div>";
                    }

                    // Get mutual connections
                    $my_connections = $user_class->get_following($_SESSION['diddit_userid'],"user");
                    $their_connections = $user_class->get_following($FRIEND_ROW['userid'],"user");

                    $mutual_count = 0;
                    if(is_array($my_connections) && is_array($their_connections)) {
                        $my_connection_ids = array_column($my_connections, "userid");
                        $their_connection_ids = array_column($their_connections, "userid");
                        $mutual_connections = array_intersect($my_connection_ids, $their_connection_ids);
                        $mutual_count = count($mutual_connections);
                    }

                    if($mutual_count > 0) {
                        echo '<div style="color:#666;font-size:12px;margin:10px 0;">'.$mutual_count.' mutual connection'.($mutual_count > 1 ? 's' : '').'</div>';
                    }

                    echo '<div style="margin-top:15px;">';
                    echo '<a href="message.php?id='.$FRIEND_ROW['userid'].'" style="display:inline-block;color:#0a66c2;font-weight:600;padding:6px 16px;border:1px solid #0a66c2;border-radius:16px;text-decoration:none;font-size:14px;margin-right:5px;">Message</a>';
                    echo '<a href="like.php?type=user&id='.$FRIEND_ROW['userid'].'" style="display:inline-block;color:#666;padding:6px 16px;border:1px solid #666;border-radius:16px;text-decoration:none;font-size:14px;">Remove</a>';
                    echo '</div>';

                    echo '</div>';
                    echo '</div>';
                }
            }
        } else {
            echo '<div style="text-align:center;width:100%;padding:40px 20px;color:#666;">';
            echo '<div style="font-size:18px;font-weight:bold;margin-bottom:10px;">No connections yet</div>';

            if(i_own_content($user_data)) {
                echo "<p>Start building your network by connecting with colleagues, classmates, and other professionals in your field.</p>";
                echo '<a href="#" style="display:inline-block;margin-top:15px;background-color:#0a66c2;color:white;padding:8px 24px;border-radius:24px;text-decoration:none;font-weight:600;">Find connections</a>';
            } else {
                echo "<p>This user doesn't have any connections yet.</p>";

                // Check if current user is connected with this profile
                $my_connections = $user_class->get_following($_SESSION['diddit_userid'],"user");
                $is_connected = false;

                if(is_array($my_connections)) {
                    $connection_ids = array_column($my_connections, "userid");
                    if(in_array($user_data['userid'], $connection_ids)) {
                        $is_connected = true;
                    }
                }

                if(!$is_connected) {
                    echo '<a href="like.php?type=user&id='.$user_data['userid'].'" style="display:inline-block;margin-top:15px;background-color:#0a66c2;color:white;padding:8px 24px;border-radius:24px;text-decoration:none;font-weight:600;">Connect</a>';
                }
            }

            echo '</div>';
        }
        ?>
    </div>
</div>
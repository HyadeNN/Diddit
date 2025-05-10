<!--top bar-->
<?php

$corner_image = "images/user_male.jpg";
if(isset($USER)){

    if(file_exists($USER['profile_image']))
    {
        $image_class = new Image();
        $corner_image = $image_class->get_thumb_profile($USER['profile_image']);
    }else{

        if($USER['gender'] == "Female"){

            $corner_image = "images/user_female.jpg";
        }
    }
}
?>

<div id="blue_bar" style="background-color: rgb(10, 102, 194)">
    <div style="width: 800px;margin:auto;display:flex;align-items:center;justify-content:space-between;height:70px;">
        <!-- Logo and search section -->
        <div style="display:flex;align-items:center;">
            <a href="index.php" style="color: white;font-size:30px;font-weight:bold;text-decoration:none;margin-right:20px;">LinkedOut</a>
            <form method="get" action="search.php" style="margin:0;">
                <input type="text" id="search_box" name="find" placeholder="Search for professionals" />
            </form>
        </div>

        <!-- Navigation and profile section -->
        <?php if(isset($USER)): ?>
            <div style="display:flex;align-items:center;">
                <!-- Main navigation -->
                <a href="index.php" style="color:white;text-decoration:none;margin-right:20px;font-size:14px;">
                    <div style="display:flex;flex-direction:column;align-items:center;">
                        <span style="font-size:20px;">&#127968;</span>
                        <span>Home</span>
                    </div>
                </a>

                <a href="network.php" style="color:white;text-decoration:none;margin-right:20px;font-size:14px;">
                    <div style="display:flex;flex-direction:column;align-items:center;">
                        <span style="font-size:20px;">&#128101;</span>
                        <span>Network</span>
                    </div>
                </a>

                <a href="jobs.php" style="color:white;text-decoration:none;margin-right:20px;font-size:14px;">
                    <div style="display:flex;flex-direction:column;align-items:center;">
                        <span style="font-size:20px;">&#128188;</span>
                        <span>Jobs</span>
                    </div>
                </a>

                <a href="notifications.php" style="color:white;text-decoration:none;margin-right:20px;font-size:14px;position:relative;">
                    <div style="display:flex;flex-direction:column;align-items:center;">
                        <span style="font-size:20px;">&#128276;</span>
                        <span>Notifications</span>
                        <?php
                        $notif = check_notifications();
                        if($notif > 0):
                            ?>
                            <div style="background-color: red;color: white;position: absolute;right:-5px;top:-5px;
						width:15px;height: 15px;border-radius: 50%;padding: 4px;text-align:center;font-size: 12px;"><?= $notif ?></div>
                        <?php endif; ?>
                    </div>
                </a>

                <!-- Profile dropdown -->
                <a href="profile.php">
                    <img src="<?php echo $corner_image ?>" style="width: 40px;height:40px;border-radius: 50%;object-fit:cover;">
                </a>

                <a href="logout.php" style="margin-left:15px;">
                    <span style="font-size:11px;color:white;background-color:rgba(255,255,255,0.2);padding:5px 10px;border-radius:4px;">Logout</span>
                </a>
            </div>
        <?php else: ?>
            <div>
                <a href="login.php">
                    <span style="font-size:14px;color:white;background-color:rgba(255,255,255,0.2);padding:8px 16px;border-radius:4px;">Login</span>
                </a>
                <a href="signup.php" style="margin-left:10px;">
                    <span style="font-size:14px;color:#0a66c2;background-color:white;padding:8px 16px;border-radius:4px;font-weight:bold;">Sign Up</span>
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>
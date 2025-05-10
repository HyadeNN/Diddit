<div style="display:flex;margin-top:20px;">
    <div style="min-height:400px;flex:2.5;margin-right:20px;">
        <div style="background-color:white;border-radius:10px;box-shadow:0 0 5px rgba(0,0,0,0.1);padding:20px;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
                <h3 style="margin:0;">Skills</h3>
                <?php if(i_own_content($user_data)): ?>
                    <button id="add_skill_btn" style="background-color:#0a66c2;color:white;border:none;border-radius:16px;padding:6px 16px;font-size:14px;cursor:pointer;">+ Add skill</button>
                <?php endif; ?>
            </div>

            <div id="add_skill_form" style="display:none;background-color:#f3f2ef;padding:15px;border-radius:5px;margin-bottom:20px;">
                <form method="post" action="profile.php?section=skills&action=add">
                    <div style="margin-bottom:10px;">
                        <label style="display:block;margin-bottom:5px;font-weight:bold;">Skill</label>
                        <input type="text" name="skill" style="width:100%;padding:8px;border:1px solid #ddd;border-radius:4px;" placeholder="Ex: JavaScript" required>
                    </div>
                    <div style="display:flex;justify-content:flex-end;gap:10px;">
                        <button type="button" onclick="hideAddSkillForm()" style="padding:8px 16px;border:1px solid #0a66c2;color:#0a66c2;background:white;border-radius:16px;cursor:pointer;">Cancel</button>
                        <button type="submit" style="padding:8px 16px;background:#0a66c2;color:white;border:none;border-radius:16px;cursor:pointer;">Save</button>
                    </div>
                </form>
            </div>

            <?php
            $user_class = new User();
            $skills = $user_class->get_skills($user_data['userid']);

            if(is_array($skills) && count($skills) > 0) {
                foreach($skills as $skill) {
                    echo '<div style="border-bottom:1px solid #eee;padding:15px 0;display:flex;justify-content:space-between;align-items:center;">';
                    echo '<div>';
                    echo '<div style="font-weight:500;">' . htmlspecialchars($skill['skill']) . '</div>';

                    if($skill['endorsements'] > 0) {
                        echo '<div style="color:#666;font-size:14px;">' . $skill['endorsements'] . ' endorsement' . ($skill['endorsements'] > 1 ? 's' : '') . '</div>';
                    }

                    echo '</div>';

                    echo '<div style="display:flex;gap:10px;align-items:center;">';

                    if(!i_own_content($user_data)) {
                        echo '<a href="like.php?type=skill&id='.$skill['id'].'" class="endorse-btn" style="padding:4px 12px;border:1px solid #0a66c2;color:#0a66c2;background:white;border-radius:16px;font-size:14px;text-decoration:none;">Endorse</a>';
                    }

                    if(i_own_content($user_data)) {
                        echo '<a href="profile.php?section=skills&action=delete&id='.$skill['id'].'" onclick="return confirm(\'Are you sure you want to delete this skill?\')" style="color:#666;font-size:14px;text-decoration:none;">Delete</a>';
                    }

                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<div style="text-align:center;padding:20px;color:#666;">';
                echo '<p>No skills added yet.</p>';
                echo '</div>';
            }

            // Handle adding skill
            if(isset($_GET['action']) && $_GET['action'] == 'add' && i_own_content($user_data)) {
                if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['skill'])) {
                    $user_class->add_skill($user_data['userid'], $_POST['skill']);
                    echo "<script>window.location.href = 'profile.php?section=skills';</script>";
                }
            }

            // Handle deleting skill
            if(isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id']) && i_own_content($user_data)) {
                $user_class->delete_skill($_GET['id'], $user_data['userid']);
                echo "<script>window.location.href = 'profile.php?section=skills';</script>";
            }
            ?>
        </div>
    </div>

    <!-- Right sidebar -->
    <div style="flex:1;">
        <div style="background-color:white;border-radius:10px;box-shadow:0 0 5px rgba(0,0,0,0.1);padding:20px;">
            <h3 style="margin-top:0;">People with similar skills</h3>
            <?php
            $DB = new Database();
            $user_class = new User();
            $skills = $user_class->get_skills($user_data['userid']);

            if(is_array($skills) && count($skills) > 0) {
                // Get the top skill
                $top_skill = $skills[0]['skill'];

                // Find users with the same skill
                $sql = "SELECT DISTINCT u.* FROM users u 
                            JOIN skills s ON u.userid = s.userid 
                            WHERE s.skill LIKE '%$top_skill%' 
                            AND u.userid != '$user_data[userid]' 
                            LIMIT 3";

                $similar_users = $DB->read($sql);

                if(is_array($similar_users) && count($similar_users) > 0) {
                    foreach($similar_users as $user) {
                        $image = "images/user_male.jpg";
                        if($user['gender'] == "Female"){
                            $image = "images/user_female.jpg";
                        }
                        if(file_exists($user['profile_image'])){
                            $image = $image_class->get_thumb_profile($user['profile_image']);
                        }

                        echo '<div style="display:flex;align-items:center;margin-bottom:15px;">';
                        echo '<img src="'.$image.'" style="width:48px;height:48px;border-radius:50%;margin-right:10px;">';
                        echo '<div>';
                        echo '<div style="font-weight:600;"><a href="profile.php?id='.$user['userid'].'" style="color:#000;">'.$user['first_name'].' '.$user['last_name'].'</a></div>';

                        if(isset($user['job_title']) && !empty($user['job_title'])) {
                            echo "<div style='color:#666;font-size:12px;'>" . $user['job_title'];

                            if(isset($user['company']) && !empty($user['company'])) {
                                echo " at " . $user['company'];
                            }

                            echo "</div>";
                        }

                        echo '<a href="like.php?type=user&id='.$user['userid'].'" style="display:inline-block;margin-top:5px;color:#0a66c2;font-size:14px;font-weight:600;">+ Connect</a>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo "<p style='color:#666;'>No connections with similar skills found.</p>";
                }
            } else {
                echo "<p style='color:#666;'>Add your skills to find connections with similar expertise.</p>";
            }
            ?>
        </div>
    </div>
</div>

<script>
    function showAddSkillForm() {
        document.getElementById('add_skill_form').style.display = 'block';
        document.getElementById('add_skill_btn').style.display = 'none';
    }

    function hideAddSkillForm() {
        document.getElementById('add_skill_form').style.display = 'none';
        document.getElementById('add_skill_btn').style.display = 'block';
    }

    document.getElementById('add_skill_btn').addEventListener('click', showAddSkillForm);
</script>
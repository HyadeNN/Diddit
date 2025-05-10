<div style="display:flex;margin-top:20px;">
    <div style="min-height:400px;flex:2.5;margin-right:20px;">
        <div style="background-color:white;border-radius:10px;box-shadow:0 0 5px rgba(0,0,0,0.1);padding:20px;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
                <h3 style="margin:0;">Experience</h3>
                <?php if(i_own_content($user_data)): ?>
                    <button id="add_experience_btn" style="background-color:#0a66c2;color:white;border:none;border-radius:16px;padding:6px 16px;font-size:14px;cursor:pointer;">+ Add experience</button>
                <?php endif; ?>
            </div>

            <div id="add_experience_form" style="display:none;background-color:#f3f2ef;padding:15px;border-radius:5px;margin-bottom:20px;">
                <form method="post" action="profile.php?section=experience&action=add">
                    <div style="margin-bottom:10px;">
                        <label style="display:block;margin-bottom:5px;font-weight:bold;">Title</label>
                        <input type="text" name="position" style="width:100%;padding:8px;border:1px solid #ddd;border-radius:4px;" placeholder="Ex: Software Engineer" required>
                    </div>
                    <div style="margin-bottom:10px;">
                        <label style="display:block;margin-bottom:5px;font-weight:bold;">Company</label>
                        <input type="text" name="company" style="width:100%;padding:8px;border:1px solid #ddd;border-radius:4px;" placeholder="Ex: Microsoft" required>
                    </div>
                    <div style="margin-bottom:10px;">
                        <label style="display:block;margin-bottom:5px;font-weight:bold;">Description</label>
                        <textarea name="description" style="width:100%;padding:8px;border:1px solid #ddd;border-radius:4px;height:100px;" placeholder="Describe your role and achievements"></textarea>
                    </div>
                    <div style="display:flex;gap:10px;margin-bottom:10px;">
                        <div style="flex:1;">
                            <label style="display:block;margin-bottom:5px;font-weight:bold;">Start Date</label>
                            <input type="date" name="from_date" style="width:100%;padding:8px;border:1px solid #ddd;border-radius:4px;" required>
                        </div>
                        <div style="flex:1;">
                            <label style="display:block;margin-bottom:5px;font-weight:bold;">End Date</label>
                            <input type="date" name="to_date" id="end_date_input" style="width:100%;padding:8px;border:1px solid #ddd;border-radius:4px;">
                        </div>
                    </div>
                    <div style="margin-bottom:15px;">
                        <input type="checkbox" id="current_job" name="current_job" onchange="toggleEndDate()">
                        <label for="current_job">I am currently working in this role</label>
                    </div>
                    <div style="display:flex;justify-content:flex-end;gap:10px;">
                        <button type="button" onclick="hideAddExperienceForm()" style="padding:8px 16px;border:1px solid #0a66c2;color:#0a66c2;background:white;border-radius:16px;cursor:pointer;">Cancel</button>
                        <button type="submit" style="padding:8px 16px;background:#0a66c2;color:white;border:none;border-radius:16px;cursor:pointer;">Save</button>
                    </div>
                </form>
            </div>

            <?php
            $user_class = new User();
            $experiences = $user_class->get_experiences($user_data['userid']);

            if(is_array($experiences) && count($experiences) > 0) {
                foreach($experiences as $exp) {
                    // Format dates
                    $from_date = date("M Y", strtotime($exp['from_date']));
                    $to_date = $exp['current_job'] ? "Present" : ($exp['to_date'] ? date("M Y", strtotime($exp['to_date'])) : "");
                    $date_range = $from_date . ($to_date ? " - " . $to_date : "");

                    // Calculate duration
                    $from = new DateTime($exp['from_date']);
                    $to = $exp['current_job'] ? new DateTime() : ($exp['to_date'] ? new DateTime($exp['to_date']) : new DateTime());
                    $interval = $from->diff($to);
                    $duration = "";

                    if($interval->y > 0) {
                        $duration .= $interval->y . " yr" . ($interval->y > 1 ? "s" : "");
                    }
                    if($interval->m > 0) {
                        $duration .= ($duration ? " " : "") . $interval->m . " mo" . ($interval->m > 1 ? "s" : "");
                    }

                    echo '<div style="border-bottom:1px solid #eee;padding-bottom:15px;margin-bottom:15px;">';
                    echo '<div style="display:flex;">';
                    echo '<div style="width:48px;height:48px;background-color:#f3f2ef;border-radius:8px;margin-right:12px;display:flex;align-items:center;justify-content:center;">';
                    echo '<img src="images/building.png" style="width:24px;height:24px;">';
                    echo '</div>';
                    echo '<div style="flex:1;">';
                    echo '<h4 style="margin:0 0 5px 0;">' . htmlspecialchars($exp['position']) . '</h4>';
                    echo '<div style="color:#666;font-size:14px;">' . htmlspecialchars($exp['company']) . '</div>';
                    echo '<div style="color:#666;font-size:14px;">' . $date_range . ' · ' . $duration . '</div>';

                    if(!empty($exp['description'])) {
                        echo '<div style="margin-top:10px;font-size:14px;">' . nl2br(htmlspecialchars($exp['description'])) . '</div>';
                    }

                    if(i_own_content($user_data)) {
                        echo '<div style="margin-top:10px;">';
                        echo '<a href="profile.php?section=experience&action=delete&id='.$exp['id'].'" onclick="return confirm(\'Are you sure you want to delete this experience?\')" style="color:#0a66c2;font-size:14px;text-decoration:none;">Delete</a>';
                        echo '</div>';
                    }

                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<div style="text-align:center;padding:20px;color:#666;">';
                echo '<p>No experience added yet.</p>';
                echo '</div>';
            }

            // Handle adding experience
            if(isset($_GET['action']) && $_GET['action'] == 'add' && i_own_content($user_data)) {
                if($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $user_class->add_experience($_POST, $user_data['userid']);
                    echo "<script>window.location.href = 'profile.php?section=experience';</script>";
                }
            }

            // Handle deleting experience
            if(isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id']) && i_own_content($user_data)) {
                $user_class->delete_experience($_GET['id'], $user_data['userid']);
                echo "<script>window.location.href = 'profile.php?section=experience';</script>";
            }
            ?>
        </div>
    </div>

    <!-- Right sidebar -->
    <div style="flex:1;">
        <div style="background-color:white;border-radius:10px;box-shadow:0 0 5px rgba(0,0,0,0.1);padding:20px;">
            <h3 style="margin-top:0;">People also viewed</h3>
            <?php
            $DB = new Database();
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

                    echo '<a href="like.php?type=user&id='.$suggestion['userid'].'" style="display:inline-block;margin-top:5px;color:#0a66c2;font-size:14px;font-weight:600;">+ Connect</a>';
                    echo '</div>';
                    echo '</div>';
                }
            }
            ?>
        </div>
    </div>
</div>

<script>
    function showAddExperienceForm() {
        document.getElementById('add_experience_form').style.display = 'block';
        document.getElementById('add_experience_btn').style.display = 'none';
    }

    function hideAddExperienceForm() {
        document.getElementById('add_experience_form').style.display = 'none';
        document.getElementById('add_experience_btn').style.display = 'block';
    }

    function toggleEndDate() {
        var endDateInput = document.getElementById('end_date_input');
        var currentJobCheckbox = document.getElementById('current_job');

        if(currentJobCheckbox.checked) {
            endDateInput.disabled = true;
            endDateInput.value = '';
        } else {
            endDateInput.disabled = false;
        }
    }

    document.getElementById('add_experience_btn').addEventListener('click', showAddExperienceForm);
</script>
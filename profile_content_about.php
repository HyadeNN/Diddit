<div style="display:flex;margin-top:20px;">
    <div style="min-height:400px;flex:2.5;margin-right:20px;">
        <div style="background-color:white;border-radius:10px;box-shadow:0 0 5px rgba(0,0,0,0.1);padding:20px;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
                <h3 style="margin:0;">About</h3>
                <?php if(i_own_content($user_data)): ?>
                    <button id="edit_about_btn" style="background-color:#0a66c2;color:white;border:none;border-radius:16px;padding:6px 16px;font-size:14px;cursor:pointer;">Edit</button>
                <?php endif; ?>
            </div>

            <div id="about_display">
                <?php
                $settings_class = new Settings();
                $settings = $settings_class->get_settings($user_data['userid']);

                if(is_array($settings) && !empty($settings['about'])) {
                    echo '<div style="color:#333;line-height:1.5;">'.nl2br(htmlspecialchars($settings['about'])).'</div>';
                } else {
                    echo '<div style="color:#666;text-align:center;padding:20px;">';

                    if(i_own_content($user_data)) {
                        echo 'Add a summary about yourself to help others understand your background and interests.';
                    } else {
                        echo 'This user has not added an about section yet.';
                    }

                    echo '</div>';
                }
                ?>
            </div>

            <?php if(i_own_content($user_data)): ?>
                <div id="about_edit_form" style="display:none;">
                    <form method="post" action="profile.php?section=about&action=update">
                        <textarea name="about" style="width:100%;padding:10px;border:1px solid #ddd;border-radius:4px;min-height:200px;font-family:inherit;font-size:14px;resize:vertical;"><?php echo htmlspecialchars($settings['about']); ?></textarea>

                        <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:15px;">
                            <button type="button" onclick="cancelEditAbout()" style="padding:8px 16px;border:1px solid #0a66c2;color:#0a66c2;background:white;border-radius:16px;cursor:pointer;">Cancel</button>
                            <button type="submit" style="padding:8px 16px;background:#0a66c2;color:white;border:none;border-radius:16px;cursor:pointer;">Save</button>
                        </div>
                    </form>
                </div>
            <?php endif; ?>

            <?php
            // Handle updating about section
            if(isset($_GET['action']) && $_GET['action'] == 'update' && i_own_content($user_data)) {
                if($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $settings_class = new Settings();
                    $data = array(
                        'about' => $_POST['about']
                    );
                    $settings_class->save_settings($data, $user_data['userid']);
                    echo "<script>window.location.href = 'profile.php?section=about';</script>";
                }
            }
            ?>
        </div>

        <!-- Contact Information -->
        <div style="background-color:white;border-radius:10px;box-shadow:0 0 5px rgba(0,0,0,0.1);padding:20px;margin-top:20px;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
                <h3 style="margin:0;">Contact Information</h3>
                <?php if(i_own_content($user_data)): ?>
                    <button id="edit_contact_btn" style="background-color:#0a66c2;color:white;border:none;border-radius:16px;padding:6px 16px;font-size:14px;cursor:pointer;">Edit</button>
                <?php endif; ?>
            </div>

            <div id="contact_display">
                <div style="margin-bottom:15px;">
                    <div style="color:#666;font-size:14px;">Email</div>
                    <div style="font-size:16px;"><?php echo $user_data['email']; ?></div>
                </div>

                <?php
                $settings_class = new Settings();
                $settings = $settings_class->get_settings($user_data['userid']);

                if(is_array($settings)) {
                    // Phone
                    if(!empty($settings['phone'])) {
                        echo '<div style="margin-bottom:15px;">';
                        echo '<div style="color:#666;font-size:14px;">Phone</div>';
                        echo '<div style="font-size:16px;">'.htmlspecialchars($settings['phone']).'</div>';
                        echo '</div>';
                    }

                    // Website
                    if(!empty($settings['website'])) {
                        echo '<div style="margin-bottom:15px;">';
                        echo '<div style="color:#666;font-size:14px;">Website</div>';
                        echo '<div style="font-size:16px;"><a href="'.htmlspecialchars($settings['website']).'" target="_blank" style="color:#0a66c2;">'.htmlspecialchars($settings['website']).'</a></div>';
                        echo '</div>';
                    }
                }
                ?>
            </div>

            <?php if(i_own_content($user_data)): ?>
                <div id="contact_edit_form" style="display:none;">
                    <form method="post" action="profile.php?section=about&action=update_contact">
                        <div style="margin-bottom:15px;">
                            <label style="display:block;margin-bottom:5px;font-weight:bold;">Email</label>
                            <input type="email" name="email" value="<?php echo $user_data['email']; ?>" style="width:100%;padding:8px;border:1px solid #ddd;border-radius:4px;" readonly>
                            <div style="color:#666;font-size:12px;margin-top:5px;">Email cannot be changed</div>
                        </div>

                        <div style="margin-bottom:15px;">
                            <label style="display:block;margin-bottom:5px;font-weight:bold;">Phone</label>
                            <input type="tel" name="phone" value="<?php echo isset($settings['phone']) ? htmlspecialchars($settings['phone']) : ''; ?>" style="width:100%;padding:8px;border:1px solid #ddd;border-radius:4px;">
                        </div>

                        <div style="margin-bottom:15px;">
                            <label style="display:block;margin-bottom:5px;font-weight:bold;">Website</label>
                            <input type="url" name="website" value="<?php echo isset($settings['website']) ? htmlspecialchars($settings['website']) : ''; ?>" style="width:100%;padding:8px;border:1px solid #ddd;border-radius:4px;" placeholder="https://example.com">
                        </div>

                        <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:15px;">
                            <button type="button" onclick="cancelEditContact()" style="padding:8px 16px;border:1px solid #0a66c2;color:#0a66c2;background:white;border-radius:16px;cursor:pointer;">Cancel</button>
                            <button type="submit" style="padding:8px 16px;background:#0a66c2;color:white;border:none;border-radius:16px;cursor:pointer;">Save</button>
                        </div>
                    </form>
                </div>
            <?php endif; ?>

            <?php
            // Handle updating contact information
            if(isset($_GET['action']) && $_GET['action'] == 'update_contact' && i_own_content($user_data)) {
                if($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $settings_class = new Settings();
                    $data = array(
                        'phone' => $_POST['phone'],
                        'website' => $_POST['website']
                    );
                    $settings_class->save_settings($data, $user_data['userid']);
                    echo "<script>window.location.href = 'profile.php?section=about';</script>";
                }
            }
            ?>
        </div>
    </div>

    <!-- Right sidebar -->
    <div style="flex:1;">
        <!-- Profile strength -->
        <?php if(i_own_content($user_data)): ?>
            <div style="background-color:white;border-radius:10px;box-shadow:0 0 5px rgba(0,0,0,0.1);padding:20px;margin-bottom:20px;">
                <h3 style="margin-top:0;">Profile strength</h3>

                <?php
                // Calculate profile completeness
                $total_items = 6; // About, Job Title, Company, Education, Experience, Skills
                $completed_items = 0;

                // Check About
                if(!empty($settings['about'])) {
                    $completed_items++;
                }

                // Check Job Title
                if(!empty($user_data['job_title'])) {
                    $completed_items++;
                }

                // Check Company
                if(!empty($user_data['company'])) {
                    $completed_items++;
                }

                // Check Education
                $user_class = new User();
                $education = $user_class->get_education($user_data['userid']);
                if(is_array($education) && count($education) > 0) {
                    $completed_items++;
                }

                // Check Experience
                $experiences = $user_class->get_experiences($user_data['userid']);
                if(is_array($experiences) && count($experiences) > 0) {
                    $completed_items++;
                }

                // Check Skills
                $skills = $user_class->get_skills($user_data['userid']);
                if(is_array($skills) && count($skills) > 0) {
                    $completed_items++;
                }

                $percentage = round(($completed_items / $total_items) * 100);

                // Determine profile strength level
                $strength_level = "Beginner";
                if($percentage >= 80) {
                    $strength_level = "All-Star";
                } else if($percentage >= 50) {
                    $strength_level = "Intermediate";
                }
                ?>

                <div style="margin-bottom:10px;">
                    <div style="display:flex;justify-content:space-between;margin-bottom:5px;">
                        <div style="font-weight:bold;"><?php echo $strength_level; ?></div>
                        <div><?php echo $percentage; ?>%</div>
                    </div>
                    <div style="background-color:#e0e0e0;height:8px;border-radius:4px;overflow:hidden;">
                        <div style="background-color:#0a66c2;height:100%;width:<?php echo $percentage; ?>%;"></div>
                    </div>
                </div>

                <div style="font-size:14px;color:#666;">
                    <?php if($percentage < 100): ?>
                        <p>Complete the following to strengthen your profile:</p>
                        <ul style="padding-left:20px;margin-top:5px;">
                            <?php if(empty($settings['about'])): ?>
                                <li>Add an about section</li>
                            <?php endif; ?>

                            <?php if(empty($user_data['job_title'])): ?>
                                <li>Add your current job title</li>
                            <?php endif; ?>

                            <?php if(empty($user_data['company'])): ?>
                                <li>Add your current company</li>
                            <?php endif; ?>

                            <?php if(!is_array($education) || count($education) == 0): ?>
                                <li>Add education</li>
                            <?php endif; ?>

                            <?php if(!is_array($experiences) || count($experiences) == 0): ?>
                                <li>Add work experience</li>
                            <?php endif; ?>

                            <?php if(!is_array($skills) || count($skills) == 0): ?>
                                <li>Add skills</li>
                            <?php endif; ?>
                        </ul>
                    <?php else: ?>
                        <p>Great job! Your profile is complete and looks professional.</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Activity -->
        <div style="background-color:white;border-radius:10px;box-shadow:0 0 5px rgba(0,0,0,0.1);padding:20px;">
            <h3 style="margin-top:0;">Activity</h3>

            <?php
            $DB = new Database();
            $sql = "SELECT * FROM posts WHERE userid = '$user_data[userid]' AND parent = 0 ORDER BY date DESC LIMIT 3";
            $recent_posts = $DB->read($sql);

            if(is_array($recent_posts) && count($recent_posts) > 0) {
                foreach($recent_posts as $post) {
                    $post_date = date("M d, Y", strtotime($post['date']));

                    echo '<div style="padding:10px 0;border-bottom:1px solid #eee;">';
                    echo '<div style="font-size:14px;color:#666;margin-bottom:5px;">'.$post_date.'</div>';

                    $post_text = $post['post'];
                    if(strlen($post_text) > 150) {
                        $post_text = substr($post_text, 0, 150) . '...';
                    }

                    echo '<div style="font-size:14px;">'.htmlspecialchars($post_text).'</div>';

                    echo '<div style="margin-top:5px;">';
                    echo '<a href="single_post.php?id='.$post['postid'].'" style="color:#0a66c2;font-size:14px;text-decoration:none;">View post</a>';
                    echo '</div>';

                    echo '</div>';
                }

                echo '<div style="margin-top:15px;text-align:center;">';
                echo '<a href="profile.php" style="color:#0a66c2;font-size:14px;font-weight:600;text-decoration:none;">See all activity</a>';
                echo '</div>';
            } else {
                echo '<div style="text-align:center;padding:20px;color:#666;">';

                if(i_own_content($user_data)) {
                    echo '<p>You haven\'t posted anything yet.</p>';
                    echo '<a href="index.php" style="display:inline-block;margin-top:10px;color:#0a66c2;font-weight:600;text-decoration:none;">Start posting</a>';
                } else {
                    echo '<p>This user hasn\'t posted anything yet.</p>';
                }

                echo '</div>';
            }
            ?>
        </div>
    </div>
</div>

<script>
    function editAbout() {
        document.getElementById('about_display').style.display = 'none';
        document.getElementById('about_edit_form').style.display = 'block';
        document.getElementById('edit_about_btn').style.display = 'none';
    }

    function cancelEditAbout() {
        document.getElementById('about_display').style.display = 'block';
        document.getElementById('about_edit_form').style.display = 'none';
        document.getElementById('edit_about_btn').style.display = 'block';
    }

    function editContact() {
        document.getElementById('contact_display').style.display = 'none';
        document.getElementById('contact_edit_form').style.display = 'block';
        document.getElementById('edit_contact_btn').style.display = 'none';
    }

    function cancelEditContact() {
        document.getElementById('contact_display').style.display = 'block';
        document.getElementById('contact_edit_form').style.display = 'none';
        document.getElementById('edit_contact_btn').style.display = 'block';
    }

    // Add event listeners
    document.getElementById('edit_about_btn').addEventListener('click', editAbout);
    document.getElementById('edit_contact_btn').addEventListener('click', editContact);
</script>
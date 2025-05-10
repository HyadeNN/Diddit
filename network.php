<?php

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

?>

<!DOCTYPE html>
<html>
<head>
    <title>My Network | LinkedOut</title>
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
        border: solid 1px #ccc;
        padding: 4px 10px;
        font-size: 14px;
        background-image: url(search.png);
        background-repeat: no-repeat;
        background-position: right 10px center;
    }

    .tab{
        color: #666;
        font-size: 16px;
        font-weight: 600;
        padding: 16px 25px;
        display: inline-block;
        margin: 0;
        cursor: pointer;
        border-bottom: 2px solid transparent;
    }

    .tab.active{
        color: #0a66c2;
        border-bottom: 2px solid #0a66c2;
    }

    .tab:hover {
        color: #0a66c2;
        background-color: rgba(10, 102, 194, 0.08);
    }

    .connection-card {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 0 5px rgba(0,0,0,0.1);
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .connection-card-header {
        height: 60px;
        background-color: #ddd;
    }

    .connection-card-body {
        padding: 0 20px 20px;
        text-align: center;
        position: relative;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .connection-card-image {
        width: 104px;
        height: 104px;
        border-radius: 50%;
        border: 4px solid white;
        margin-top: -52px;
        object-fit: cover;
    }

    .connection-card-title {
        font-weight: 600;
        font-size: 16px;
        margin: 16px 0 5px;
    }

    .connection-card-position {
        color: #666;
        font-size: 14px;
        margin-bottom: 10px;
    }

    .connection-card-mutuals {
        color: #666;
        font-size: 12px;
        margin: 10px 0;
    }

    .connection-card-actions {
        margin-top: auto;
        display: flex;
        justify-content: center;
        gap: 8px;
    }

    .search-input-container {
        position: relative;
        margin-bottom: 20px;
    }

    .search-input {
        width: 100%;
        padding: 12px;
        font-size: 16px;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding-left: 40px;
    }

    .search-icon {
        position: absolute;
        left: 12px;
        top: 12px;
        color: #666;
    }
</style>

<body>
<br>
<?php include("header.php"); ?>

<!--main content area-->
<div style="width: 800px;margin:auto;padding-top:20px;">
    <div style="background-color:white;border-radius:10px 10px 0 0;border-bottom:1px solid #ddd;">
        <div class="tab active" data-tab="connections">Connections</div>
        <div class="tab" data-tab="requests">Connection Requests</div>
        <div class="tab" data-tab="suggestions">People You May Know</div>
    </div>

    <div id="connections-tab" class="tab-content" style="display:block;">
        <div style="padding:20px;background-color:white;border-radius:0 0 10px 10px;margin-bottom:20px;">
            <div class="search-input-container">
                <span class="search-icon">🔍</span>
                <input type="text" class="search-input" placeholder="Search by name">
            </div>

            <?php
            $user_class = new User();
            $connections = $user_class->get_following($user_data['userid'],"user");
            $connection_count = is_array($connections) ? count($connections) : 0;
            ?>

            <div style="font-size:16px;color:#666;margin-bottom:20px;">
                <span style="font-weight:600;"><?php echo $connection_count; ?></span> Connections
            </div>

            <div style="display:grid;grid-template-columns:repeat(3, 1fr);gap:20px;">
                <?php
                if(is_array($connections) && count($connections) > 0){
                    foreach ($connections as $connection) {
                        $connection_data = $user_class->get_user($connection['userid']);
                        if(is_array($connection_data)){
                            $image = "images/user_male.jpg";
                            if($connection_data['gender'] == "Female"){
                                $image = "images/user_female.jpg";
                            }
                            if(file_exists($connection_data['profile_image'])){
                                $image_class = new Image();
                                $image = $image_class->get_thumb_profile($connection_data['profile_image']);
                            }

                            echo '<div class="connection-card">';
                            echo '<div class="connection-card-header"></div>';
                            echo '<div class="connection-card-body">';
                            echo '<img src="'.$image.'" class="connection-card-image">';
                            echo '<h3 class="connection-card-title"><a href="profile.php?id='.$connection_data['userid'].'" style="color:#000;">'.$connection_data['first_name'].' '.$connection_data['last_name'].'</a></h3>';

                            if(isset($connection_data['job_title']) && !empty($connection_data['job_title'])) {
                                echo '<div class="connection-card-position">' . $connection_data['job_title'];

                                if(isset($connection_data['company']) && !empty($connection_data['company'])) {
                                    echo " at " . $connection_data['company'];
                                }

                                echo '</div>';
                            }

                            // Get mutual connections
                            $my_connections = $user_class->get_following($_SESSION['diddit_userid'],"user");
                            $their_connections = $user_class->get_following($connection_data['userid'],"user");

                            $mutual_count = 0;
                            if(is_array($my_connections) && is_array($their_connections)) {
                                $my_connection_ids = array_column($my_connections, "userid");
                                $their_connection_ids = array_column($their_connections, "userid");
                                $mutual_connections = array_intersect($my_connection_ids, $their_connection_ids);
                                $mutual_count = count($mutual_connections);
                            }

                            if($mutual_count > 0) {
                                echo '<div class="connection-card-mutuals">'.$mutual_count.' mutual connection'.($mutual_count > 1 ? 's' : '').'</div>';
                            }

                            echo '<div class="connection-card-actions">';
                            echo '<a href="message.php?id='.$connection_data['userid'].'" style="display:inline-block;color:#0a66c2;font-weight:600;padding:6px 16px;border:1px solid #0a66c2;border-radius:16px;text-decoration:none;font-size:14px;">Message</a>';
                            echo '<a href="like.php?type=user&id='.$connection_data['userid'].'" style="display:inline-block;color:#666;padding:6px 16px;border:1px solid #666;border-radius:16px;text-decoration:none;font-size:14px;">Remove</a>';
                            echo '</div>';

                            echo '</div>';
                            echo '</div>';
                        }
                    }
                } else {
                    echo '<div style="grid-column: span 3;text-align:center;padding:40px 20px;color:#666;">';
                    echo '<div style="font-size:18px;font-weight:bold;margin-bottom:10px;">No connections yet</div>';
                    echo "<p>Start building your network by connecting with colleagues, classmates, and other professionals in your field.</p>";
                    echo '<a href="#" onclick="switchTab(\'suggestions\')" style="display:inline-block;margin-top:15px;background-color:#0a66c2;color:white;padding:8px 24px;border-radius:24px;text-decoration:none;font-weight:600;">Find connections</a>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </div>

    <div id="requests-tab" class="tab-content" style="display:none;">
        <div style="padding:20px;background-color:white;border-radius:0 0 10px 10px;margin-bottom:20px;">
            <div style="font-size:16px;color:#666;margin-bottom:20px;">
                Pending Invitations
            </div>

            <div style="text-align:center;padding:40px 20px;color:#666;">
                <div style="font-size:18px;font-weight:bold;margin-bottom:10px;">No pending invitations</div>
                <p>Connection requests will appear here.</p>
            </div>
        </div>
    </div>

    <div id="suggestions-tab" class="tab-content" style="display:none;">
        <div style="padding:20px;background-color:white;border-radius:0 0 10px 10px;margin-bottom:20px;">
            <div style="font-size:16px;color:#666;margin-bottom:20px;">
                People you may know based on your profile and connections
            </div>

            <div style="display:grid;grid-template-columns:repeat(3, 1fr);gap:20px;">
                <?php
                $DB = new Database();

                // Get users that current user is not connected with
                $my_connections = $user_class->get_following($_SESSION['diddit_userid'],"user");
                $excluded_ids = array($_SESSION['diddit_userid']);

                if(is_array($my_connections)){
                    foreach($my_connections as $connection){
                        $excluded_ids[] = $connection['userid'];
                    }
                }

                $excluded_str = implode("','", $excluded_ids);
                $sql = "SELECT * FROM users WHERE userid NOT IN ('$excluded_str') LIMIT 9";
                $suggestions = $DB->read($sql);

                if(is_array($suggestions) && count($suggestions) > 0){
                    foreach($suggestions as $suggestion){
                        $image = "images/user_male.jpg";
                        if($suggestion['gender'] == "Female"){
                            $image = "images/user_female.jpg";
                        }
                        if(file_exists($suggestion['profile_image'])){
                            $image_class = new Image();
                            $image = $image_class->get_thumb_profile($suggestion['profile_image']);
                        }

                        echo '<div class="connection-card">';
                        echo '<div class="connection-card-header"></div>';
                        echo '<div class="connection-card-body">';
                        echo '<img src="'.$image.'" class="connection-card-image">';
                        echo '<h3 class="connection-card-title"><a href="profile.php?id='.$suggestion['userid'].'" style="color:#000;">'.$suggestion['first_name'].' '.$suggestion['last_name'].'</a></h3>';

                        if(isset($suggestion['job_title']) && !empty($suggestion['job_title'])) {
                            echo '<div class="connection-card-position">' . $suggestion['job_title'];

                            if(isset($suggestion['company']) && !empty($suggestion['company'])) {
                                echo " at " . $suggestion['company'];
                            }

                            echo '</div>';
                        }

                        // Try to find connections at same company
                        $same_company = false;
                        if(!empty($suggestion['company'])){
                            $sql = "SELECT COUNT(*) as count FROM users u 
												JOIN endorsements e ON u.userid = e.contentid
												WHERE e.type='user' AND JSON_CONTAINS(e.connections, 
												JSON_OBJECT('userid', '$_SESSION[diddit_userid]')) 
												AND u.company LIKE '%{$suggestion['company']}%'";
                            $company_result = $DB->read($sql);
                            if(is_array($company_result) && $company_result[0]['count'] > 0){
                                $same_company = true;
                            }
                        }

                        if($same_company){
                            echo '<div class="connection-card-mutuals">You both work at '.$suggestion['company'].'</div>';
                        }

                        echo '<div class="connection-card-actions">';
                        echo '<a href="like.php?type=user&id='.$suggestion['userid'].'" style="display:inline-block;background-color:#0a66c2;color:white;padding:6px 16px;border-radius:16px;text-decoration:none;font-size:14px;font-weight:600;">Connect</a>';
                        echo '</div>';

                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<div style="grid-column: span 3;text-align:center;padding:40px 20px;color:#666;">';
                    echo '<div style="font-size:18px;font-weight:bold;margin-bottom:10px;">No suggestions available</div>';
                    echo "<p>We couldn't find any connection suggestions at this time.</p>";
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </div>
</div>

<script>
    // Tab switching functionality
    const tabs = document.querySelectorAll('.tab');
    const tabContents = document.querySelectorAll('.tab-content');

    function switchTab(tabId) {
        // Remove active class from all tabs
        tabs.forEach(tab => {
            tab.classList.remove('active');
            if(tab.dataset.tab === tabId) {
                tab.classList.add('active');
            }
        });

        // Hide all tab contents
        tabContents.forEach(content => {
            content.style.display = 'none';
        });

        // Show selected tab content
        document.getElementById(tabId + '-tab').style.display = 'block';
    }

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const tabId = this.dataset.tab;
            switchTab(tabId);
        });
    });

    // Search functionality
    const searchInput = document.querySelector('.search-input');
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const connectionCards = document.querySelectorAll('#connections-tab .connection-card');

        connectionCards.forEach(card => {
            const name = card.querySelector('.connection-card-title').textContent.toLowerCase();
            const position = card.querySelector('.connection-card-position')?.textContent.toLowerCase() || '';

            if(name.includes(searchTerm) || position.includes(searchTerm)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });
</script>
</body>
</html>
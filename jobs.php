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
    <title>Jobs | LinkedOut</title>
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

    .job-card {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 0 5px rgba(0,0,0,0.1);
        padding: 20px;
        margin-bottom: 16px;
        display: flex;
        transition: all 0.2s ease;
    }

    .job-card:hover {
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }

    .job-logo {
        width: 56px;
        height: 56px;
        margin-right: 16px;
        border-radius: 8px;
        background-color: #f3f2ef;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        font-weight: bold;
        color: #0a66c2;
    }

    .job-details {
        flex: 1;
    }

    .job-title {
        font-size: 18px;
        font-weight: 600;
        margin: 0 0 4px 0;
        color: #000;
    }

    .job-company {
        font-size: 16px;
        color: #666;
        margin: 0 0 4px 0;
    }

    .job-location {
        font-size: 14px;
        color: #666;
    }

    .job-posted {
        font-size: 14px;
        color: #666;
        margin-top: 12px;
    }

    .job-metadata {
        font-size: 14px;
        color: #666;
        margin-top: 8px;
        display: flex;
        align-items: center;
    }

    .job-metadata div {
        margin-right: 16px;
        display: flex;
        align-items: center;
    }

    .job-metadata img {
        width: 16px;
        margin-right: 4px;
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

    .filter-container {
        display: flex;
        margin-bottom: 16px;
        overflow-x: auto;
        padding-bottom: 8px;
    }

    .filter-button {
        background-color: #fff;
        border: 1px solid #0a66c2;
        color: #0a66c2;
        border-radius: 16px;
        padding: 6px 16px;
        margin-right: 8px;
        font-size: 14px;
        font-weight: 600;
        white-space: nowrap;
        cursor: pointer;
    }

    .filter-button:hover {
        background-color: rgba(10, 102, 194, 0.08);
    }

    .apply-button {
        background-color: #0a66c2;
        color: white;
        border: none;
        border-radius: 16px;
        padding: 6px 16px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        margin-top: 8px;
    }

    .apply-button:hover {
        background-color: #004182;
    }

    .save-button {
        background-color: white;
        border: 1px solid #0a66c2;
        color: #0a66c2;
        border-radius: 16px;
        padding: 6px 16px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        margin-top: 8px;
        margin-right: 8px;
    }

    .save-button:hover {
        background-color: rgba(10, 102, 194, 0.08);
    }
</style>

<body>
<br>
<?php include("header.php"); ?>

<!--main content area-->
<div style="width: 800px;margin:auto;padding-top:20px;">
    <div style="background-color:white;border-radius:10px 10px 0 0;border-bottom:1px solid #ddd;">
        <div class="tab active" data-tab="recommended">Recommended</div>
        <div class="tab" data-tab="saved">Saved Jobs</div>
        <div class="tab" data-tab="applied">Applied Jobs</div>
        <div class="tab" data-tab="posted">Posted Jobs</div>
    </div>

    <div id="recommended-tab" class="tab-content" style="display:block;">
        <div style="padding:20px;background-color:white;border-radius:0 0 10px 10px;margin-bottom:20px;">
            <div class="search-input-container">
                <span class="search-icon">🔍</span>
                <input type="text" class="search-input" placeholder="Search by title, skill, or company">
            </div>

            <div class="filter-container">
                <button class="filter-button">Date Posted</button>
                <button class="filter-button">Experience Level</button>
                <button class="filter-button">Company</button>
                <button class="filter-button">Job Type</button>
                <button class="filter-button">Remote</button>
                <button class="filter-button">Salary Range</button>
                <button class="filter-button">Industry</button>
            </div>

            <div style="font-size:16px;color:#666;margin-bottom:20px;">
                <span style="font-weight:600;">Recommended jobs based on your profile</span>
            </div>

            <?php
            // Sample job data - in a real implementation, this would come from a database
            $jobs = array(
                array(
                    'title' => 'Software Engineer',
                    'company' => 'Tech Innovations Inc.',
                    'logo' => 'T',
                    'location' => 'San Francisco, CA (Remote)',
                    'posted' => '2 days ago',
                    'applicants' => 45,
                    'type' => 'Full-time',
                    'level' => 'Mid-Senior level',
                    'industry' => 'Software Development'
                ),
                array(
                    'title' => 'Product Manager',
                    'company' => 'Global Solutions',
                    'logo' => 'G',
                    'location' => 'New York, NY (On-site)',
                    'posted' => '1 week ago',
                    'applicants' => 89,
                    'type' => 'Full-time',
                    'level' => 'Senior level',
                    'industry' => 'Technology'
                ),
                array(
                    'title' => 'UX/UI Designer',
                    'company' => 'Creative Designs Ltd.',
                    'logo' => 'C',
                    'location' => 'Chicago, IL (Hybrid)',
                    'posted' => '3 days ago',
                    'applicants' => 27,
                    'type' => 'Full-time',
                    'level' => 'Mid level',
                    'industry' => 'Design'
                ),
                array(
                    'title' => 'Marketing Specialist',
                    'company' => 'Brand Masters',
                    'logo' => 'B',
                    'location' => 'Austin, TX (Remote)',
                    'posted' => 'Just now',
                    'applicants' => 5,
                    'type' => 'Contract',
                    'level' => 'Entry level',
                    'industry' => 'Marketing'
                ),
                array(
                    'title' => 'Data Scientist',
                    'company' => 'Analytics Pro',
                    'logo' => 'A',
                    'location' => 'Boston, MA (On-site)',
                    'posted' => '1 month ago',
                    'applicants' => 150,
                    'type' => 'Full-time',
                    'level' => 'Senior level',
                    'industry' => 'Data Science'
                )
            );

            foreach($jobs as $job) {
                echo '<div class="job-card">';
                echo '<div class="job-logo">'.$job['logo'].'</div>';
                echo '<div class="job-details">';
                echo '<h3 class="job-title">'.$job['title'].'</h3>';
                echo '<div class="job-company">'.$job['company'].'</div>';
                echo '<div class="job-location">'.$job['location'].'</div>';

                echo '<div class="job-metadata">';
                echo '<div><img src="images/clock.png" alt="Posted" title="Posted">'.$job['posted'].'</div>';
                echo '<div><img src="images/users.png" alt="Applicants" title="Applicants">'.$job['applicants'].' applicants</div>';
                echo '</div>';

                echo '<div class="job-metadata" style="margin-top:4px;">';
                echo '<div><img src="images/briefcase.png" alt="Job Type" title="Job Type">'.$job['type'].'</div>';
                echo '<div><img src="images/level.png" alt="Level" title="Level">'.$job['level'].'</div>';
                echo '</div>';

                echo '<div style="margin-top:16px;">';
                echo '<button class="save-button">Save</button>';
                echo '<button class="apply-button">Easy Apply</button>';
                echo '</div>';

                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>
    </div>

    <div id="saved-tab" class="tab-content" style="display:none;">
        <div style="padding:20px;background-color:white;border-radius:0 0 10px 10px;margin-bottom:20px;">
            <div style="text-align:center;padding:40px 20px;color:#666;">
                <div style="font-size:18px;font-weight:bold;margin-bottom:10px;">No saved jobs</div>
                <p>Jobs you save will appear here.</p>
            </div>
        </div>
    </div>

    <div id="applied-tab" class="tab-content" style="display:none;">
        <div style="padding:20px;background-color:white;border-radius:0 0 10px 10px;margin-bottom:20px;">
            <div style="text-align:center;padding:40px 20px;color:#666;">
                <div style="font-size:18px;font-weight:bold;margin-bottom:10px;">No applications</div>
                <p>Jobs you apply to will appear here.</p>
            </div>
        </div>
    </div>

    <div id="posted-tab" class="tab-content" style="display:none;">
        <div style="padding:20px;background-color:white;border-radius:0 0 10px 10px;margin-bottom:20px;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
                <div style="font-size:16px;color:#666;font-weight:600;">Jobs you've posted</div>
                <button class="apply-button">+ Post a Job</button>
            </div>

            <div style="text-align:center;padding:40px 20px;color:#666;">
                <div style="font-size:18px;font-weight:bold;margin-bottom:10px;">You haven't posted any jobs</div>
                <p>Jobs you post for your company will appear here.</p>
                <button class="apply-button" style="margin-top:20px;">Post a Free Job</button>
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
        const jobCards = document.querySelectorAll('#recommended-tab .job-card');

        jobCards.forEach(card => {
            const title = card.querySelector('.job-title').textContent.toLowerCase();
            const company = card.querySelector('.job-company').textContent.toLowerCase();
            const location = card.querySelector('.job-location').textContent.toLowerCase();

            if(title.includes(searchTerm) || company.includes(searchTerm) || location.includes(searchTerm)) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    });

    // Filter buttons - for demo, just toggle active state
    const filterButtons = document.querySelectorAll('.filter-button');
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            this.classList.toggle('active');
            if(this.classList.contains('active')) {
                this.style.backgroundColor = '#0a66c2';
                this.style.color = 'white';
            } else {
                this.style.backgroundColor = 'white';
                this.style.color = '#0a66c2';
            }
        });
    });

    // Job actions
    const saveButtons = document.querySelectorAll('.save-button');
    saveButtons.forEach(button => {
        button.addEventListener('click', function() {
            alert('Job saved!');
            this.textContent = 'Saved';
            this.disabled = true;
            this.style.backgroundColor = '#f3f2ef';
            this.style.color = '#666';
        });
    });

    const applyButtons = document.querySelectorAll('.apply-button');
    applyButtons.forEach(button => {
        if(button.textContent === 'Easy Apply') {
            button.addEventListener('click', function() {
                alert('Application submitted!');
                this.textContent = 'Applied';
                this.disabled = true;
                this.style.backgroundColor = '#057642';
            });
        }
    });
</script>
</body>
</html>
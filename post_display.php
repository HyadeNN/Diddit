<div id="post">
    <div>
        <?php
        $image = "images/user_male.jpg";
        if($ROW_USER['gender'] == "Female")
        {
            $image = "images/user_female.jpg";
        }

        if(file_exists($ROW_USER['profile_image']))
        {
            $image = $image_class->get_thumb_profile($ROW_USER['profile_image']);
        }
        ?>

        <img src="<?php echo $image ?>" style="width: 60px;height:60px;margin-right: 10px;border-radius: 50%;object-fit:cover;">
    </div>
    <div style="width: 100%;">
        <div style="font-weight: bold;color: #000;width: 100%;font-size:16px;">
            <?php
            echo "<a href='profile.php?id=$ROW[userid]' style='color:#000;'>";
            echo htmlspecialchars($ROW_USER['first_name']) . " " . htmlspecialchars($ROW_USER['last_name']);
            echo "</a>";

            if(isset($ROW_USER['job_title']) && !empty($ROW_USER['job_title'])) {
                echo "<span style='font-weight:normal;color:#666;font-size:14px;display:block;'>";
                echo $ROW_USER['job_title'];

                if(isset($ROW_USER['company']) && !empty($ROW_USER['company'])) {
                    echo " at " . $ROW_USER['company'];
                }

                echo "</span>";
            }

            if($ROW['is_profile_image'])
            {
                $pronoun = "their";
                if($ROW_USER['gender'] == "Male")
                {
                    $pronoun = "his";
                } else if($ROW_USER['gender'] == "Female") {
                    $pronoun = "her";
                }
                echo "<span style='font-weight:normal;color:#666;font-size:14px;'> updated $pronoun profile photo</span>";
            }

            if($ROW['is_cover_image'])
            {
                $pronoun = "their";
                if($ROW_USER['gender'] == "Male")
                {
                    $pronoun = "his";
                } else if($ROW_USER['gender'] == "Female") {
                    $pronoun = "her";
                }
                echo "<span style='font-weight:normal;color:#666;font-size:14px;'> updated $pronoun cover photo</span>";
            }

            echo "<span style='color:#666;font-size:13px;float:right;'>";
            $date = date("jS M Y", strtotime($ROW['date']));
            echo $date;
            echo "</span>";
            ?>
        </div>

        <div style="margin:10px 0;color:#333;font-size:14px;">
            <?php echo check_tags($ROW['post']) ?>
        </div>

        <?php
        if(file_exists($ROW['image']))
        {
            $post_image = $image_class->get_thumb_post($ROW['image']);
            echo "<img src='$post_image' style='width:100%;border-radius:8px;margin-bottom:10px;' />";
        }
        ?>

        <div style="display:flex;padding:5px 0;border-top:1px solid #eee;margin-top:10px;">
            <?php
            $endorsements = "";
            $endorsements = ($ROW['endorsements'] > 0) ? "(" .$ROW['endorsements']. ")" : "" ;
            ?>
            <a onclick="like_post(event)" href="like.php?type=post&id=<?php echo $ROW['postid'] ?>" style="flex:1;text-align:center;padding:8px;border-radius:4px;color:#666;font-size:14px;text-decoration:none;">
                <span style="font-size:18px;">&#128077;</span> Endorse<?php echo $endorsements ?>
            </a>

            <?php
            $comments = "";
            if($ROW['comments'] > 0){
                $comments = "(" . $ROW['comments'] . ")";
            }
            ?>

            <a href="single_post.php?id=<?php echo $ROW['postid'] ?>" style="flex:1;text-align:center;padding:8px;border-radius:4px;color:#666;font-size:14px;text-decoration:none;">
                <span style="font-size:18px;">&#128172;</span> Comment<?php echo $comments ?>
            </a>

            <a href="#" style="flex:1;text-align:center;padding:8px;border-radius:4px;color:#666;font-size:14px;text-decoration:none;">
                <span style="font-size:18px;">&#128257;</span> Share
            </a>
        </div>

        <?php

        if($ROW['has_image']){
            echo "<a href='image_view.php?id=$ROW[postid]' style='color:#0a66c2;font-size:13px;display:inline-block;margin-top:5px;'>";
            echo "View Full Image";
            echo "</a>";
        }
        ?>

        <span style="float:right">
			<?php
            $post = new Post();
            if($post->i_own_post($ROW['postid'],$_SESSION['diddit_userid'])){
                echo "
					<a href='edit.php?id=$ROW[postid]' style='color:#0a66c2;font-size:13px;margin-right:10px;'>
		 				<span style='font-size:14px;'>&#9998;</span> Edit
					</a>

					 <a href='delete.php?id=$ROW[postid]' style='color:#0a66c2;font-size:13px;'>
		 				<span style='font-size:14px;'>&#128465;</span> Delete
					</a>";
            }
            ?>
		</span>

        <?php
        $i_liked = false;
        if(isset($_SESSION['diddit_userid'])){
            $DB = new Database();
            $sql = "select endorsements from endorsements where type='post' && contentid = '$ROW[postid]' limit 1";
            $result = $DB->read($sql);
            if(is_array($result)){
                $likes = json_decode($result[0]['endorsements'],true);
                $user_ids = array_column($likes, "userid");

                if(in_array($_SESSION['diddit_userid'], $user_ids)){
                    $i_liked = true;
                }
            }
        }

        echo "<a id='info_$ROW[postid]' href='likes.php?type=post&id=$ROW[postid]' style='color:#666;font-size:13px;display:block;margin-top:5px;'>";

        if($ROW['endorsements'] > 0){
            if($ROW['endorsements'] == 1){
                if($i_liked){
                    echo "<div>You endorsed this post</div>";
                }else{
                    echo "<div>1 person endorsed this post</div>";
                }
            }else{
                if($i_liked){
                    $text = "others";
                    if($ROW['endorsements'] - 1 == 1){
                        $text = "other";
                    }
                    echo "<div>You and " . ($ROW['endorsements'] - 1) . " $text endorsed this post</div>";
                }else{
                    echo "<div>" . $ROW['endorsements'] . " people endorsed this post</div>";
                }
            }
        }
        echo "</a>";
        ?>
    </div>
</div>

<script type="text/javascript">
    function ajax_send(data,element){
        var ajax = new XMLHttpRequest();
        ajax.addEventListener('readystatechange', function(){
            if(ajax.readyState == 4 && ajax.status == 200){
                response(ajax.responseText,element);
            }
        });
        data = JSON.stringify(data);
        ajax.open("post","ajax.php",true);
        ajax.send(data);
    }

    function response(result,element){
        if(result != ""){
            var obj = JSON.parse(result);
            if(typeof obj.action != 'undefined'){
                if(obj.action == 'like_post'){
                    var likes = "";
                    if(typeof obj.likes != 'undefined'){
                        likes = (parseInt(obj.likes) > 0) ? "Endorse(" +obj.likes+ ")" : "Endorse" ;
                        element.innerHTML = '<span style="font-size:18px;">&#128077;</span> ' + likes;
                    }

                    if(typeof obj.info != 'undefined'){
                        var info_element = document.getElementById(obj.id);
                        info_element.innerHTML = obj.info;
                    }
                }
            }
        }
    }

    function like_post(e){
        e.preventDefault();
        var link = e.target.href;
        var data = {};
        data.link = link;
        data.action = "like_post";
        ajax_send(data,e.target);
    }
</script>
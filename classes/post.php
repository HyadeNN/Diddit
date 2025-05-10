<?php

class Post
{
    private $error = "";

    public function create_post($userid, $data, $files)
    {
        if(!empty($data['post']) || !empty($files['file']['name']) || isset($data['is_profile_image']) || isset($data['is_cover_image']))
        {
            $myimage = "";
            $has_image = 0;
            $is_cover_image = 0;
            $is_profile_image = 0;
            $post_type = "standard";

            if(isset($data['post_type'])) {
                $post_type = $data['post_type'];
            }

            if(isset($data['is_profile_image']) || isset($data['is_cover_image']))
            {
                $myimage = $files;
                $has_image = 1;

                if(isset($data['is_cover_image']))
                {
                    $is_cover_image = 1;
                }

                if(isset($data['is_profile_image']))
                {
                    $is_profile_image = 1;
                }
            }else
            {
                if(!empty($files['file']['name']))
                {
                    $folder = "uploads/" . $userid . "/";

                    //create folder
                    if(!file_exists($folder))
                    {
                        mkdir($folder,0777,true);
                        file_put_contents($folder . "index.php", "");
                    }

                    $allowed[] = "image/jpeg";

                    if(in_array($files['file']['type'], $allowed)){
                        $image_class = new Image();
                        $myimage = $folder . $image_class->generate_filename(15) . ".jpg";
                        move_uploaded_file($files['file']['tmp_name'], $myimage);
                        $image_class->resize_image($myimage,$myimage,1500,1500);
                        $has_image = 1;
                    }else{
                        $this->error .= "The selected image is not a valid type. only jpegs allowed!<br>";
                    }
                }
            }

            $post = "";
            if(isset($data['post'])){
                $post = addslashes($data['post']);
            }

            //add tagged users
            $tags = array();
            $tags = get_tags($post);
            $tags = json_encode($tags);

            if($this->error == ""){
                $postid = $this->create_postid();
                $parent = 0;
                $DB = new Database();

                if(isset($data['parent']) && is_numeric($data['parent'])){
                    $parent = $data['parent'];
                    $mypost = $this->get_one_post($data['parent']);

                    if(is_array($mypost) && $mypost['userid'] != $userid){
                        //follow this item
                        content_i_follow($userid,$mypost);
                        //add notification
                        add_notification($_SESSION['diddit_userid'],"comment",$mypost);
                    }

                    $sql = "update posts set comments = comments + 1 where postid = '$parent' limit 1";
                    $DB->save($sql);
                }

                $query = "insert into posts (userid,postid,post,image,has_image,is_profile_image,is_cover_image,parent,tags,post_type) values ('$userid','$postid','$post','$myimage','$has_image','$is_profile_image','$is_cover_image','$parent','$tags','$post_type')";
                $DB->save($query);

                //notify those that were tagged
                tag($postid);
            }
        }else
        {
            $this->error .= "Please type something to post!<br>";
        }

        return $this->error;
    }

    public function edit_post($data, $files)
    {
        if(!empty($data['post']) || !empty($files['file']['name']))
        {
            $myimage = "";
            $has_image = 0;

            if(!empty($files['file']['name']))
            {
                $folder = "uploads/" . $userid . "/";

                //create folder
                if(!file_exists($folder))
                {
                    mkdir($folder,0777,true);
                    file_put_contents($folder . "index.php", "");
                }

                $image_class = new Image();
                $myimage = $folder . $image_class->generate_filename(15) . ".jpg";
                move_uploaded_file($_FILES['file']['tmp_name'], $myimage);
                $image_class->resize_image($myimage,$myimage,1500,1500);
                $has_image = 1;
            }

            $post = "";
            if(isset($data['post'])){
                $post = addslashes($data['post']);
            }

            $postid = addslashes($data['postid']);

            if($has_image){
                $query = "update posts set post = '$post', image = '$myimage' where postid = '$postid' limit 1";
            }else{
                $query = "update posts set post = '$post' where postid = '$postid' limit 1";
            }

            //notify those that were tagged
            tag($postid, $post);

            $DB = new Database();
            $DB->save($query);
        }else
        {
            $this->error .= "Please type something to post!<br>";
        }

        return $this->error;
    }

    public function get_posts($id)
    {
        $page_number = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page_number = ($page_number < 1) ? 1 : $page_number;

        $limit = 10;
        $offset = ($page_number - 1) * $limit;

        $query = "select * from posts where parent = 0 and userid = '$id' order by id desc limit $limit offset $offset";

        $DB = new Database();
        $result = $DB->read($query);

        if($result)
        {
            return $result;
        }else
        {
            return false;
        }
    }

    public function get_comments($id)
    {
        $page_number = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page_number = ($page_number < 1) ? 1 : $page_number;

        $limit = 10;
        $offset = ($page_number - 1) * $limit;

        $query = "select * from posts where parent = '$id' order by id asc limit $limit offset $offset";

        $DB = new Database();
        $result = $DB->read($query);

        if($result)
        {
            return $result;
        }else
        {
            return false;
        }
    }

    public function get_one_post($postid)
    {
        if(!is_numeric($postid)){
            return false;
        }

        $query = "select * from posts where postid = '$postid' limit 1";

        $DB = new Database();
        $result = $DB->read($query);

        if($result)
        {
            return $result[0];
        }else
        {
            return false;
        }
    }

    public function delete_post($postid)
    {
        if(!is_numeric($postid)){
            return false;
        }

        $Post = new Post();
        $one_post = $Post->get_one_post($postid);

        $DB = new Database();
        $sql = "select parent from posts where postid = '$postid' limit 1";
        $result = $DB->read($sql);

        if(is_array($result)){
            if($result[0]['parent'] > 0){
                $parent = $result[0]['parent'];
                $sql = "update posts set comments = comments - 1 where postid = '$parent' limit 1";
                $DB->save($sql);
            }
        }

        $query = "delete from posts where postid = '$postid' limit 1";
        $DB->save($query);

        //delete any images and thumbnails
        if($one_post['image'] != "" && file_exists($one_post['image']))
        {
            unlink($one_post['image']);
        }

        if($one_post['image'] != "" && file_exists($one_post['image']. "_post_thumb"))
        {
            unlink($one_post['image']. "_post_thumb");
        }

        if($one_post['image'] != "" && file_exists($one_post['image']. "_cover_thumb"))
        {
            unlink($one_post['image']. "_cover_thumb");
        }

        //delete all comments
        $query = "delete from posts where parent = '$postid' ";
        $DB->save($query);
    }

    public function i_own_post($postid,$diddit_userid)
    {
        if(!is_numeric($postid)){
            return false;
        }

        $query = "select * from posts where postid = '$postid' limit 1";

        $DB = new Database();
        $result = $DB->read($query);

        if(is_array($result)){
            if($result[0]['userid'] == $diddit_userid){
                return true;
            }
        }

        return false;
    }

    public function get_likes($id,$type){
        $DB = new Database();
        $type = addslashes($type);

        if(is_numeric($id)){
            // First try in endorsements table
            $sql = "select endorsements from endorsements where type='$type' && contentid = '$id' limit 1";
            $result = $DB->read($sql);

            if(is_array($result)){
                $likes = json_decode($result[0]['endorsements'],true);
                return $likes;
            }

            // If not found, try in old likes table for compatibility
            $sql = "select likes from likes where type='$type' && contentid = '$id' limit 1";
            $result = $DB->read($sql);

            if(is_array($result)){
                $likes = json_decode($result[0]['likes'],true);
                return $likes;
            }
        }

        return false;
    }

    public function like_post($id,$type,$diddit_userid){
        $DB = new Database();

        // First check endorsements table
        $sql = "select endorsements from endorsements where type='$type' && contentid = '$id' limit 1";
        $result = $DB->read($sql);

        if(is_array($result)){
            $likes = json_decode($result[0]['endorsements'],true);

            // Check if $likes is null or not an array
            if(!is_array($likes)) {
                $likes = array();
            }

            $user_ids = array_column($likes, "userid");

            if(!in_array($diddit_userid, $user_ids)){
                $arr["userid"] = $diddit_userid;
                $arr["date"] = date("Y-m-d H:i:s");
                $likes[] = $arr;

                $likes_string = json_encode($likes);
                $sql = "update endorsements set endorsements = '$likes_string' where type='$type' && contentid = '$id' limit 1";
                $DB->save($sql);

                //increment the right table
                $sql = "update {$type}s set endorsements = endorsements + 1 where {$type}id = '$id' limit 1";
                $DB->save($sql);

                if($type != "user"){
                    $post = new Post();
                    $single_post = $post->get_one_post($id);
                    //add notification
                    add_notification($_SESSION['diddit_userid'],"endorsement",$single_post);
                }
            }else{
                $key = array_search($diddit_userid, $user_ids);
                unset($likes[$key]);

                $likes_string = json_encode($likes);
                $sql = "update endorsements set endorsements = '$likes_string' where type='$type' && contentid = '$id' limit 1";
                $DB->save($sql);

                //increment the right table
                $sql = "update {$type}s set endorsements = endorsements - 1 where {$type}id = '$id' limit 1";
                $DB->save($sql);
            }
        } else {
            // Check in old likes table for compatibility
            $sql = "select likes from likes where type='$type' && contentid = '$id' limit 1";
            $result = $DB->read($sql);

            if(is_array($result)){
                // Transfer from likes to endorsements
                $likes = json_decode($result[0]['likes'],true);

                // Check if $likes is null or not an array
                if(!is_array($likes)) {
                    $likes = array();
                }

                // Add new endorsement
                $arr["userid"] = $diddit_userid;
                $arr["date"] = date("Y-m-d H:i:s");
                $likes[] = $arr;

                $likes_string = json_encode($likes);
                // Create new entry in endorsements
                $sql = "insert into endorsements (type,contentid,endorsements) values ('$type','$id','$likes_string')";
                $DB->save($sql);

                //increment the right table
                $sql = "update {$type}s set endorsements = endorsements + 1 where {$type}id = '$id' limit 1";
                $DB->save($sql);

                if($type != "user"){
                    $post = new Post();
                    $single_post = $post->get_one_post($id);
                    //add notification
                    add_notification($_SESSION['diddit_userid'],"endorsement",$single_post);
                }
            } else {
                // Create a new entry
                $arr["userid"] = $diddit_userid;
                $arr["date"] = date("Y-m-d H:i:s");
                $arr2[] = $arr;

                $likes = json_encode($arr2);
                $sql = "insert into endorsements (type,contentid,endorsements) values ('$type','$id','$likes')";
                $DB->save($sql);

                //increment the right table
                $sql = "update {$type}s set endorsements = endorsements + 1 where {$type}id = '$id' limit 1";
                $DB->save($sql);

                if($type != "user"){
                    $post = new Post();
                    $single_post = $post->get_one_post($id);
                    //add notification
                    add_notification($_SESSION['diddit_userid'],"endorsement",$single_post);
                } else {
                    // For user connections, we need to send a "connect" notification
                    $user = new User();
                    $user_data = $user->get_user($id);

                    if(is_array($user_data)) {
                        add_notification($_SESSION['diddit_userid'],"connect",$user_data);
                    }
                }
            }
        }
    }

    private function create_postid()
    {
        $length = rand(4,19);
        $number = "";
        for ($i=0; $i < $length; $i++) {
            # code...
            $new_rand = rand(0,9);
            $number = $number . $new_rand;
        }

        return $number;
    }
}
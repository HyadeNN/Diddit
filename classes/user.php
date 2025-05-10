<?php

class User
{

    public function get_data($id)
    {

        $query = "select * from users where userid = '$id' limit 1";

        $DB = new Database();
        $result = $DB->read($query);

        if($result)
        {

            $row = $result[0];
            return $row;
        }else
        {
            return false;
        }
    }

    public function get_user($id)
    {

        $query = "select * from users where userid = '$id' limit 1";
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

    public function get_connections($id)
    {

        $query = "select * from users where userid != '$id' ";
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


    public function get_following($id,$type){

        $DB = new Database();
        $type = addslashes($type);

        if(is_numeric($id)){

            //get connections details - first try from endorsements table
            $sql = "select connections from endorsements where type='$type' && contentid = '$id' limit 1";
            $result = $DB->read($sql);

            // If not found, try from old likes table for compatibility
            if(!is_array($result)){
                $sql = "select following from likes where type='$type' && contentid = '$id' limit 1";
                $result = $DB->read($sql);

                if(is_array($result)){
                    $following = json_decode($result[0]['following'], true);
                    return $following;
                }
            } else {
                $following = json_decode($result[0]['connections'], true);
                if(!is_array($following)) {
                    return array();
                }
                return $following;
            }
        }

        return array();
    }

    public function follow_user($id,$type,$diddit_userid){
        if($id == $diddit_userid && $type == 'user'){
            return;
        }

        $DB = new Database();

        //save connections details
        $sql = "select connections from endorsements where type='$type' && contentid = '$diddit_userid' limit 1";
        $result = $DB->read($sql);

        if(is_array($result)){
            $connections = json_decode($result[0]['connections'],true);

            // Check if $connections is null or not an array
            if(!is_array($connections)) {
                $connections = array();
            }

            $user_ids = array_column($connections, "userid");

            if(!in_array($id, $user_ids)){

                $arr["userid"] = $id;
                $arr["date"] = date("Y-m-d H:i:s");

                $connections[] = $arr;

                $connections_string = json_encode($connections);
                $sql = "update endorsements set connections = '$connections_string' where type='$type' && contentid = '$diddit_userid' limit 1";
                $DB->save($sql);

                $user = new User();
                $user_data = $user->get_user($id);

                //add notification
                if(is_array($user_data)) {
                    add_notification($_SESSION['diddit_userid'],"connect",$user_data);
                }
            }else{

                $key = array_search($id, $user_ids);
                unset($connections[$key]);

                $connections_string = json_encode($connections);
                $sql = "update endorsements set connections = '$connections_string' where type='$type' && contentid = '$diddit_userid' limit 1";
                $DB->save($sql);
            }
        } else {
            // Check old likes table for backward compatibility
            $sql = "select following from likes where type='$type' && contentid = '$diddit_userid' limit 1";
            $result = $DB->read($sql);

            if(is_array($result)){
                // Transfer data from likes to endorsements
                $connections = json_decode($result[0]['following'],true);

                // Check if $connections is null or not an array
                if(!is_array($connections)) {
                    $connections = array();
                }

                // Add new connection
                $arr["userid"] = $id;
                $arr["date"] = date("Y-m-d H:i:s");
                $connections[] = $arr;

                $connections_string = json_encode($connections);

                // Create new entry in endorsements
                $sql = "insert into endorsements (type,contentid,connections) values ('$type','$diddit_userid','$connections_string')";
                $DB->save($sql);

                // Add notification
                $user = new User();
                $user_data = $user->get_user($id);

                if(is_array($user_data)) {
                    add_notification($_SESSION['diddit_userid'],"connect",$user_data);
                }
            } else {
                // Create new entry
                $arr["userid"] = $id;
                $arr["date"] = date("Y-m-d H:i:s");

                $arr2[] = $arr;

                $connections_string = json_encode($arr2);
                $sql = "insert into endorsements (type,contentid,connections) values ('$type','$diddit_userid','$connections_string')";
                $DB->save($sql);

                $user = new User();
                $user_data = $user->get_user($id);

                // Add notification
                if(is_array($user_data)) {
                    add_notification($_SESSION['diddit_userid'],"connect",$user_data);
                }
            }

            // Update user's connection count (endorsements) column
            $sql = "update users set endorsements = (select count(*) from endorsements where type='user' and contentid = '$diddit_userid') where userid = '$diddit_userid' limit 1";
            $DB->save($sql);
        }
    }

    public function get_skills($userid) {
        $query = "select * from skills where userid = '$userid' order by endorsements desc";
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

    public function get_education($userid) {
        $query = "select * from education where userid = '$userid' order by from_date desc";
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

    public function get_experiences($userid) {
        $query = "select * from experiences where userid = '$userid' order by from_date desc";
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

    public function add_skill($userid, $skill) {
        $skill = addslashes($skill);
        $query = "insert into skills (userid,skill) values ('$userid','$skill')";
        $DB = new Database();
        $DB->save($query);
    }

    public function delete_skill($id, $userid) {
        $query = "delete from skills where id = '$id' AND userid = '$userid' limit 1";
        $DB = new Database();
        $DB->save($query);
    }

    public function add_education($data, $userid) {
        $school = addslashes($data['school']);
        $degree = addslashes($data['degree']);
        $field = addslashes($data['field']);
        $from_date = $data['from_date'];
        $to_date = !empty($data['to_date']) ? "'".$data['to_date']."'" : "NULL";

        $query = "insert into education (userid,school,degree,field,from_date,to_date) 
				  values ('$userid','$school','$degree','$field','$from_date',$to_date)";
        $DB = new Database();
        $DB->save($query);
    }

    public function delete_education($id, $userid) {
        $query = "delete from education where id = '$id' AND userid = '$userid' limit 1";
        $DB = new Database();
        $DB->save($query);
    }

    public function add_experience($data, $userid) {
        $company = addslashes($data['company']);
        $position = addslashes($data['position']);
        $description = addslashes($data['description']);
        $from_date = $data['from_date'];
        $current_job = isset($data['current_job']) ? 1 : 0;
        $to_date = (!empty($data['to_date']) && !$current_job) ? "'".$data['to_date']."'" : "NULL";

        $query = "insert into experiences (userid,company,position,description,from_date,to_date,current_job) 
				  values ('$userid','$company','$position','$description','$from_date',$to_date,$current_job)";
        $DB = new Database();
        $DB->save($query);
    }

    public function delete_experience($id, $userid) {
        $query = "delete from experiences where id = '$id' AND userid = '$userid' limit 1";
        $DB = new Database();
        $DB->save($query);
    }
}
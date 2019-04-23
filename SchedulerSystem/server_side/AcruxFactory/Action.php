<?php
class Action {
    private $userId, $password, $conn;

    public function __construct($userId, $password, $conn){
        $this->userId = $userId;
        $this->password = $password;
        $this-> conn = $conn;
    }

    public function checkLogin(){
        $sql = "Select * From staff Where staffId = '".$this->userId."' and password = '".$this->password."';";
        $rs = mysqli_query($this->conn, $sql);
        $events = array();
        $row = mysqli_fetch_assoc($rs);
        if ($row==NULL){
            $eventsArray['message'] = "fail";
        } else {
            $eventsArray['message'] = "success";
            $eventsArray['username'] = $row['name'];
            $eventsArray['worktype'] = $row['worktype'];
            $eventsArray['staffId'] = $row['staffId'];
            $_SESSION["username"] = $row['name'];
        }
        $events[] = $eventsArray;
        return $events;
    }

    public function getSession($username){
        $events = array();
        if (isset($username)){
            $eventsArray["username"] = $username;
        } else {
            $eventsArray["username"] = "fail";
        }
        $events[] = $eventsArray;

        return $events;
    }

}

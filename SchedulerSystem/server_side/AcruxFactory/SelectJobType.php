<?php
class SelectJobType extends SelectFactory{

    private $table, $conn;

    public function __construct($table, $conn){
        $this->table = $table;
        $this->conn = $conn;
    }

    public function executeSelect()
    {
        $sql = "Select * From jobType;";
        $rs = mysqli_query($this->conn, $sql);
        $events = array();
        while ($row = mysqli_fetch_assoc($rs)) {
            $eventsArray['jtId'] = $row['jtId'];
            $eventsArray['name'] = $row['name'];
            $eventsArray['duration'] = $row['duration'];
            $events[] = $eventsArray;
        }
        return $events;
    }
}
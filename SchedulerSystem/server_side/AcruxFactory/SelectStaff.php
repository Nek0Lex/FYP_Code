<?php
class SelectStaff extends SelectFactory{

    private $table;
    private $conn;

    function __construct($table, $conn)
    {
        $this->table = $table;
        $this->conn = $conn;
    }

    public function executeSelect()
    {
        $sql = "Select * From $this->table;";
        $rs = mysqli_query($this->conn, $sql);
        $events = array();
        while ($row = mysqli_fetch_assoc($rs)) {
            if ($row['staffId'] != 1) {
                $eventsArray['id'] = $row['staffId'];
                $eventsArray['title'] = $row['name'];
                $eventsArray['workType'] = $row['worktype'];
                $events[] = $eventsArray;
            }
        }

        return $events;
    }
}
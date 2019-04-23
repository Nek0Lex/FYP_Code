<?php

class SelectNotice
{
    /**
     * SelectNotice constructor.
     * @param mixed $table
     * @param mysqli $conn
     * @param mixed $type
     */
    private $table, $conn, $type;

    public function __construct($table, $conn, $type)
    {
        $this->table = $table;
        $this->conn = $conn;
        $this->type = $type;
    }

    public function executeSelect()
    {
        $events = [];
        if ($this->type == "latest") {
            $sql = "Select MAX(dateTime) From notice;";
            $rs = mysqli_query($this->conn, $sql);
            $events = array();
            while ($row = mysqli_fetch_assoc($rs)) {
                $eventsArray['dateTime'] = $row['MAX(dateTime)'];
                $events[] = $eventsArray;
            }
        } else if ($this->type == "all") {
            $sql = "Select * From notice Order By dateTime DESC;";
            $rs = mysqli_query($this->conn, $sql);
            $events = array();
            while ($row = mysqli_fetch_assoc($rs)) {
                $eventsArray['dateTime'] = $row['dateTime'];
                $eventsArray['title'] = $row['title'];
                $eventsArray['content'] = $row['content'];
                $events[] = $eventsArray;
            }
        }

        return $events;
    }


}
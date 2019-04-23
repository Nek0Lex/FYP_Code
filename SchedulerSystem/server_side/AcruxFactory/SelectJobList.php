<?php
class SelectJobList extends SelectFactory
{
    private $conn;
    function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function executeSelect()
    {
        $this->executeSelectJobList();
    }

    public function executeSelectJobList()
    {
        $sql = "Select * From jobs Where parentJob IS NOT NULL;";
        $rs1 = mysqli_query($this->conn, $sql);

        $temp = array();
        while ($row1 = mysqli_fetch_assoc($rs1)){
            $tempArray['parentJob'] = $row1['parentJob'];
            $tempArray['status'] = $row1['status'];
            $temp[] = $tempArray;
        }

        $sql = "select jobId, plateNo, status from jobs where (status = 'TES' or status = 'TTG') and parentJob IS NULL;";
        $rs2 = mysqli_query($this->conn, $sql);

        $events = array();
        while ($row2 = mysqli_fetch_assoc($rs2)) {
            $all_completed = true;
            for ($i = 0; $i < mysqli_num_rows($rs1); $i++) {
                if ($row2['jobId']==$temp[$i]['parentJob'] && ($temp[$i]['status']!='TES' && $temp[$i]['status']!='TTG')){
                    $all_completed = false;
                }
            };

            if ($all_completed == true){
                $eventsArray['id'] = $row2['jobId'];
                $eventsArray['title'] = $row2['plateNo'];
                $eventsArray['status'] = $row2['status'];
                $events[] = $eventsArray;
            }
        }

        return $events;
    }


}
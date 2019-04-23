<?php

class SelectJobs extends SelectFactory
{

    private $table, $conn, $staffId, $date;

    function __construct($table, $conn, $staffId, $date)
    {
        $this->table = $table;
        $this->conn = $conn;
        $this->staffId = $staffId;
        $this->date = $date;
    }

    public function executeSelect()
    {
        $sql = "";

        if ($this->staffId != null) {
            $sql = "Select * From $this->table WHERE staffId = $this->staffId;";
        } else if ($this->date != null) {
            //
        } else {
            $sql = "Select * From $this->table;";
        }

        $rs = mysqli_query($this->conn, $sql);
        $events = array();

        while ($row = mysqli_fetch_assoc($rs)) {
            $eventsArray['id'] = $row['jobId'];
            $eventsArray['resourceId'] = "a";
            $eventsArray['title'] = $row['plateNo'];
            $eventsArray['date'] = $row['date'];

            if ($row['staffId'] == null) {
                $eventsArray['start'] = "尚未分配";
                $eventsArray['end'] = "尚未分配";
                $eventsArray['sname'] = "尚未分配";
            } else {
                $row['startTime'] = date('H:i:s', strtotime($row['startTime']));
                $eventsArray['start'] = $row['date'] . 'T' . $row['startTime'];
                //////////////////////////////////////////////////
                $row['endTime'] = date('H:i:s', strtotime($row['endTime']));
                $eventsArray['end'] = $row['date'] . 'T' . $row['endTime'];
                //////////////////////////////////////////////////

                $sql2 = "Select name From staff Where staffId=" . $row['staffId'];
                $result = mysqli_query($this->conn, $sql2);
                //$name = mysqli_fetch_object($result);
                //$eventsArray['sname'] = $name->name;
                $eventsArray['comment'] = $row['comment'];
            }

            $events[] = $eventsArray;
        }
        return $events;
    }

    public function ExecuteSelectWithJobID($jobId)
    {
        //$sql = "Select * From jobs Where jobId = '$jobId' OR parentJob = '$jobId';";
        $sql = "select Distinct(j1.jobId),j1.duration,j1.date,j1.startTime,j1.endTime,j1.plateNo,j1.status,j1.comment 
                from jobs j1, jobs j2 
                where j2.jobId = $jobId
                and (j2.parentJob = j1.jobId or j2.jobId = j1.jobId or j1.parentJob = j2.jobId or j2.parentJob = j1.parentJob) 
                order by jobId;";

        $rs = mysqli_query($this->conn, $sql);
        $events = array();
        while ($row = mysqli_fetch_assoc($rs)) {
            $eventsArray['id'] = $row['jobId'];
            $eventsArray['duration'] = $row['duration'];
            $eventsArray['date'] = $row['date'];
            $eventsArray['start'] = $row['startTime'];
            $eventsArray['end'] = $row['endTime'];
            $eventsArray['title'] = $row['plateNo'];
            $eventsArray['status'] = $row['status'];
            $eventsArray['comment'] = $row['comment'];
            $events[] = $eventsArray;
        }

        return $events;
    }

    public function ExecuteSelectTestList(){
        $sql = "Select * From jobs Where parentJob IS NOT NULL;";
        $rs1 = mysqli_query($this->conn, $sql);

        $temp = array();
        while ($row1 = mysqli_fetch_assoc($rs1)){
            $tempArray['parentJob'] = $row1['parentJob'];
            $tempArray['status'] = $row1['status'];
            $temp[] = $tempArray;
        }

        $sql = "select jobId, plateNo from jobs, staff where status = 'TES' and jobs.staffId = staff.staffId and parentJob IS NULL;";
        $rs2 = mysqli_query($this->conn, $sql);

        $events = array();
        while ($row2 = mysqli_fetch_assoc($rs2)) {
            $all_completed = true;
            for ($i = 0; $i < mysqli_num_rows($rs1); $i++) {
                if ($row2['jobId']==$temp[$i]['parentJob'] && $temp[$i]['status']!='TES'){
                    $all_completed = false;
                }
            };

            if ($all_completed == true){
                $eventsArray['id'] = $row2['jobId'];
                $eventsArray['title'] = $row2['plateNo'];
                $events[] = $eventsArray;
            }
        }
        //$events[] = $eventsArray;
        return $events;
    }

}
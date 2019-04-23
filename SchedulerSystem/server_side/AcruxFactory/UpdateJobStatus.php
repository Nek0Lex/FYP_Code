<?php
class UpdateJobStatus extends UpdateFactory{
    private $conn, $target, $jobId;

    function __construct($conn, $target, $jobId)
    {
        $this->conn = $conn;
        $this->target = $target;
        $this->jobId = $jobId;
    }

    public function executeUpdate()
    {
        $sql = "Select * From jobs Where jobId = '$this->jobId' and status = '$this->target';";
        $rs = mysqli_query($this->conn, $sql);
        $row = mysqli_fetch_assoc($rs);

        $events = array();
        if ($row==NULL){
            if ($this->target == 'COM' || $this->target == 'TTG'){
                $sql2 = "Update jobs SET status='$this->target' WHERE jobId='$this->jobId' OR parentJob='$this->jobId';";
            } else {
                $sql2 = "Update jobs SET status='$this->target' WHERE jobId='$this->jobId';";
            }
            mysqli_query($this->conn, $sql2);

            if ($this->target=="TES"){
                $sql = "Select * From jobs Where (jobId = '$this->jobId' or parentJob = '$this->jobId') and status != '$this->target';";
                $numOfRow = mysqli_num_rows(mysqli_query($this->conn, $sql));
                if ($numOfRow == 0)
                    $eventsArray['msg'] = "updatedToTest";
                else
                    $eventsArray['msg'] = "notUpdatedToTest";
            } else {
                $eventsArray['msg'] = "updated";
            }
        } else {
            $eventsArray['msg'] = "exist";
        }
        $events[] = $eventsArray;

        return $events;
    }
}

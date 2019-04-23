<?php;
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Content-Type: application/json');
require "AcruxFactory/connection.php";
foreach (glob("AcruxFactory/*.php") as $filename)
{
    include $filename;
}

$table = $_GET["table"];
$date = $_GET["date"];
$staffId = $_GET["staffId"];
$date = $_GET["date"];
$action = $_GET["action"];

if ($action == null) {
    switch ($table) {
        case 'staff':
            $staff = new SelectStaff($table, $conn);
            $selectThing = $staff->executeSelect();
            break;
        case 'jobs':
            $jobs = new SelectJobs($table, $conn, $staffId, $date);
            $selectThing = $jobs->executeSelect();
            if ($_GET['jobId'] != null){
                $selectThing = $jobs->ExecuteSelectWithJobID($_GET['jobId']);
            }
            break;
        case 'jobType':
            $jobType = new SelectJobType($conn, $table);
            $selectThing = $jobType->executeSelect();
            break;
        case 'attendance':
            //
        case 'notice':
            $type = $_GET['type'];
            $notice = new SelectNotice($table, $conn, $type);
            $selectThing = $notice->executeSelect();
            break;
        default:
            echo 'You shall not pass';
    }
}

switch ($action){
    case 'checkLogin':
        $userId = $_GET["userId"];
        $password = $_GET["password"];
        $checkLoginResult = new Action($userId, $password, $conn);
        $selectThing = $checkLoginResult->checkLogin();
        break;
    case 'getSession':
        $username = $_SESSION["username"];
        $getSessionResult = new Action($userId, $password, $conn);
        $selectThing = $checkLoginResult->getSession($username);
        break;
    case 'jobTestList':
        $jobTestList = new SelectJobList($conn);
        $selectThing = $jobTestList->executeSelectJobList();
        break;
    case 'updateJobStatus':
        $target = $_GET["target"];
        $jobId = $_GET["jobId"];
        $updateJobStatusResult = new UpdateJobStatus($conn, $target, $jobId);
        $selectThing = $updateJobStatusResult->executeUpdate();
        break;
}

if ($selectThing != null) {
    echo json_encode($selectThing, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
}
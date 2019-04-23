<?php
//echo $_GET['selectAction'];
//echo $_GET['parkSpaceId'];
require_once("conn.php");

$area_code = $_GET['parkSpaceId'];
$area_code_cancel = $_GET['parkSpaceId_cancel'];
$action = $_GET['selectAction'];

//echo $area_code_cancel.$action;

if ($action == "cancel" && $area_code_cancel != null){
    $sql = "Update spaces SET space_operation = NULL WHERE space_area_code='$area_code_cancel';";
} else {
    $sql = "Update spaces SET space_operation='$action' WHERE space_area_code='$area_code';";
}


if (mysqli_query($conn, $sql)){
    echo "Record updated successfully";
    mysqli_close($conn);
    header('Location: index.php');
    exit;
} else {
    echo "Error updating record: " . mysqli_error($conn);
    mysqli_close($conn);
    header('Location: index.php');
    exit;
}
//
//header('Location: index.php');
//exit;


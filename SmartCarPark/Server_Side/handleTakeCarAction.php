<?php
require_once("conn.php");

$area_code = $_GET['parkSpaceId_takeCar'];
$sql = "Update spaces SET commands = 'taking' WHERE space_area_code='$area_code';";

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
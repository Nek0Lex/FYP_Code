<?php

require_once('init.php');

// Set the content as json
header('Content-type: application/json; charset=UTF-8');

$stmt = DB::get()->prepare('SELECT * 
                        FROM spaces a
                        LEFT JOIN (
                            SELECT *
                            FROM updates b
                            WHERE update_time = (
                                SELECT max( update_time )
                                FROM updates um
                                WHERE um.update_space_id = b.update_space_id
                            )
                            GROUP BY b.update_space_id
                        ) b ON a.space_id = b.update_space_id
                        WHERE space_park_id = 1 ');
$stmt->execute();
$res = $stmt->fetchAll(PDO::FETCH_ASSOC);

//0 = avaliable, 1=occupied, 2=reserved, 3=maintenance

$parkingSpaceInfo['SpaceInfo'] = array();

$updateStatusArray = array();
$updateStatusArray['updateStatus'] = array();
$updateStatusArray['commands'] = array();


foreach ($res as $row){
    if ($row['update_status'] == "1"){
        array_push($updateStatusArray['updateStatus'], $row['update_status']);
    } else if ($row['update_status'] == "0"){
        if ($row['space_operation'] == 'reserved'){
            array_push($updateStatusArray['updateStatus'], "2");
        } else if ($row['space_operation'] == 'maintenance'){
            array_push($updateStatusArray['updateStatus'], "3");
        } else {
            array_push($updateStatusArray['updateStatus'], $row['update_status']);
        }
    }

//    if ($row['update_status'] == "0"){
//        array_push($updateStatusArray['commands'], "empty");
//    } else if($row['update_status'] == "1"){
//        if ($row['commands'] != null){
//            array_push($updateStatusArray['commands'], $row['commands']);
//        } else {
//            array_push($updateStatusArray['commands'], "idle");
//        }
//    }

    if ($row['commands'] != null){
            array_push($updateStatusArray['commands'], $row['commands']);
    } else {
            array_push($updateStatusArray['commands'], "empty");
    }
}

//array_push($parkingSpaceInfo,  $updateStatusArray);

print json_encode($updateStatusArray,JSON_PRETTY_PRINT);


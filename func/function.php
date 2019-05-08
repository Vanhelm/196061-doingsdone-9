<?php
function calculationTask (array $taskList, $projectName) {
    $countItem = 0;
    foreach ($taskList as $key => $val){
                if($val['category'] == $projectName){                   
                   $countItem++;
                   
        }
    }
    return $countItem;
}


function calculationDate($data, $taskComplete){
    if($data === "нет" or $taskComplete === "да"){
    return false;
    }
$currentData = time();
$formatDate = strtotime($data);
$dateOfComplete = $formatDate - $currentData;
    if($dateOfComplete <= 86400){
        
    return true;
    }
    return false;
}


?>
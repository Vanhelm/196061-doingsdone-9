<?php
function calculationTask (array $taskList, $projectName) 
{
    $countItem = 0;

    foreach ($taskList as $key => $val)
    {
        if($val['category'] == $projectName)
        {                   
           $countItem++;
        }
    }
    return $countItem;
}

function calculationDate($data, $taskComplete){
	if($data === "нет" or $taskComplete === "да")
	{ 
		return false; 
	} 

	$currentData = time(); 
	$formatDate = strtotime($data); 
	$dateOfComplete = $formatDate - $currentData; 
	
	if($dateOfComplete <= 86400)
	{ 
		return true; 
	}

	return false; 
}

function include_template($name, array $data = []) 
{
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) 
    {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}


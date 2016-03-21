<?php	
function cleanData_Default($data){
	$data = trim($data);
	$data = strip_tags($data);
	$data = stripcslashes($data);
	return htmlentities($data);
}

function cleanData_Alphanumeric($data){	
	$data = cleanData_Default($data);
	$data = preg_replace('/([^A-Za-z0-9])*/', "", $data);
	return $data;
}
function cleanData_Numeric($data){	
	$data = cleanData_Default($data);
	$data = preg_replace('/([^0-9])*/', "", $data);
	return $data;
}
function cleanData_Alpha($data){	
	$data = cleanData_Default($data);
	$data = preg_replace('/([^A-Za-z])*/', "", $data);
	return $data;
}

?>
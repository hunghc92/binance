<?php
session_start();
date_default_timezone_set('Europe/London');

function cmp($a, $b)
{
	$result = 0;
	if ($a->p > $b->p) {
		$result = 1;
	} else if ($a->p < $b->p) {
		$result = -1;
	}
	return $result;
}

function cmp2($b, $a)
{
	$result = 0;
	if ($a->p > $b->p) {
		$result = 1;
	} else if ($a->p < $b->p) {
		$result = -1;
	}
	return $result;
}

function endsWith($string, $endString) 
{ 
    $len = strlen($endString); 
    if ($len == 0) { 
        return true; 
    } 
    return (substr($string, -$len) === $endString); 
}

function CallAPI($method, $url, $data = false)
{
    $curl = curl_init();

    switch ($method)
    {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);

            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_PUT, 1);
            break;
        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
    }

    // Optional Authentication:
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($curl, CURLOPT_USERPWD, "username:password");

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);

    curl_close($curl);

    return $result;
}
function getDecimal($value) {
	$rs = '0.';
	for ($i = 0; $i < (8 - strlen($value)); $i++) {
		$rs = $rs.'0';
	}
	$rs = $rs.$value;
	return $rs;
}
?>
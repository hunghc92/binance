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

$dataResp = json_decode(CallAPI('GET', 'https://api.binance.com/api/v3/ticker/price'));
$number = 10000000;
if (is_array($dataResp) || is_object($dataResp))
{
	$arrObj;
	$table = "<table cellspacing=\"0\"><tr><th>Coin</th><th>PCT</th><th>Volume</th><th>Closed Price</th><th>Last Price</th><th> % </th><th> Buy </th><th> Sell </th></tr>";
	$tr = '';
	foreach ($dataResp as $value) {
		if (endsWith($value->symbol, "BTC")) {
			$tr = '<tr><td><a target="blank" href="https://www.binance.com/vn/trade/'.str_replace('BTC', '_BTC', $value->symbol).'">'.$value->symbol.'</a></td>';
			$closed = '';
			$percent = '';
			$p = 0;
			$buysell = '<td></td><td></td>';
			if(isset($_SESSION[$value->symbol.'_data']) && isset($_SESSION[$value->symbol.'_date'])) {
				$closed = '<td>'.$_SESSION[$value->symbol.'_date'].'</td><td>'.$_SESSION[$value->symbol.'_vol'].'</td><td>'.$_SESSION[$value->symbol.'_data'].'</td>';
				
				if(floatval($value->price) > floatval($_SESSION[$value->symbol.'_data'])) {
					$p = round(((floatval($value->price)*$number - floatval($_SESSION[$value->symbol.'_data'])*$number) * 100 / floatval($_SESSION[$value->symbol.'_data']))/$number, 2);
					$percent = '<td><font color="green">'.$p.'</font></td>';
				} elseif (floatval($value->price) < floatval($_SESSION[$value->symbol.'_data'])){
					$p = -1 * round(((floatval($_SESSION[$value->symbol.'_data'])*$number - floatval($value->price)*$number) * 100 / floatval($_SESSION[$value->symbol.'_data']))/$number, 2);
					$alert = '';
					if ($p <= -10) {
						$buysell = '<td>'.$_SESSION[$value->symbol.'_buy'].'</td>'.'<td>'.$_SESSION[$value->symbol.'_sell'].'</td>';
						$alert = '<span class="alert"></span>';
					}
					$percent = '<td><font color="red">'.$p.$alert.'</font></td>';
				} else {
					$percent = '<td>0</td>';
					
				}
			}
			$tr = $tr.$closed.'<td>'.$value->price.'</td>'.$percent.$buysell.'</tr>';
			
			$object = (object) [
				'tr' => $tr,
				'p'=> $p
			];
			$arrObj[] = $object;
		}
		
	}
	usort($arrObj, "cmp");
	foreach ($arrObj as $obj) {
		$table = $table.$obj->tr;
	}
	
	$table = $table."</table>";
	echo $table;
} else if(is_string($dataResp))
{
	echo $dataResp;
} else echo "invalid";
//print_r($data);
?>
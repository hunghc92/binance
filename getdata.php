<?php include 'common-function.php';?>
<?php

$dataResp = json_decode(CallAPI('GET', 'https://api.binance.com/api/v3/ticker/price'));
$number = 10000000;
if (is_array($dataResp) || is_object($dataResp))
{
	$arrObj;
	$arrObj2;
	$table = "<div style=\" width: 665px; float:left; /*border:1px solid;*/\"><table cellspacing=\"0\"><tr><th>Coin</th><th>Volume</th><th>Closed Price</th><th>Last Price</th><th> % </th><th style=\"min-width:80px;\"> Buy </th><th style=\"min-width:80px;\"> Sell </th></tr>";
	$table2 = "<div style=\" width: 665px; float:right; /*border:1px solid;*/\"><table cellspacing=\"0\"><tr><th>Coin</th><th>Volume</th><th>Closed Price</th><th>Last Price</th><th> % </th><th style=\"min-width:80px;\"> Buy </th><th style=\"min-width:80px;\"> Sell </th></tr>";
	$tr = '';
	$symbolList = '';
	$pct = '';
	foreach ($dataResp as $value) {
		
		if (endsWith($value->symbol, "BTC")) {
			$symbolList = $symbolList.$value->symbol.',';
			$tr = '<tr><td><a style="color:blue;" target="blank" href="https://www.binance.com/vn/trade/'.str_replace('BTC', '_BTC', $value->symbol).'">'.$value->symbol.'</a></td>';
			if (endsWith($value->symbol, "USDT")) {
				$tr = '<tr><td><a style="color:blue;" target="blank" href="https://www.binance.com/vn/trade/'.str_replace('USDT', '_USDT', $value->symbol).'">'.$value->symbol.'</a></td>';
			}
			$closed = '';
			$percent = '';
			$p = 0;
			$buysell = '<td></td><td></td>';
			if(isset($_SESSION[$value->symbol.'_data']) && isset($_SESSION[$value->symbol.'_date']) 
				&& floatval($_SESSION[$value->symbol.'_vol']) > 100) {
				$pct = '<div style="font-size:14px; color: blue;"><b> PCT: '.$_SESSION[$value->symbol.'_date'].'</b><br /><br /></div>';
				$cPrice = $_SESSION[$value->symbol.'_data'];
				/*if (endsWith($value->symbol, "USDT") && floatval($_SESSION[$value->symbol.'_data']) > 0) {
					$cPrice = round(floatval($_SESSION[$value->symbol.'_data']), 2);
				}*/
				//$closed = '<td>'.$_SESSION[$value->symbol.'_date'].'</td><td>'.$_SESSION[$value->symbol.'_vol'].'</td><td>'.$cPrice.'</td>';
				$closed = '<td>'.$_SESSION[$value->symbol.'_vol'].'</td><td>'.$cPrice.'</td>';
				if(floatval($value->price) > floatval($_SESSION[$value->symbol.'_data'])) {
					$p = round(((floatval($value->price)*$number - floatval($_SESSION[$value->symbol.'_data'])*$number) * 100 / floatval($_SESSION[$value->symbol.'_data']))/$number, 2);
					$prvPercent = '';
					if (isset($_SESSION[$value->symbol.'_prvPercent']) && $_SESSION[$value->symbol.'_prvPercent'] > 0.5) {
						$prvPercent = '<br /><font color="red">'.$_SESSION[$value->symbol.'_prvPercent'].'</font>';
					}
					$percent = '<td><font color="green"><b>'.$p.'</b></font>'.$prvPercent.'</td>';
				} elseif (floatval($value->price) < floatval($_SESSION[$value->symbol.'_data'])){
					$p = -1 * round(((floatval($_SESSION[$value->symbol.'_data'])*$number - floatval($value->price)*$number) * 100 / floatval($_SESSION[$value->symbol.'_data']))/$number, 2);
					$alert = '';
					if ($p <= -10) {
						$buysell = '<td>'.$_SESSION[$value->symbol.'_buy'].'</td>'.'<td>'.$_SESSION[$value->symbol.'_sell'].'</td>';
						$alert = '<span class="alert_down">'.$value->symbol.'</span>';
					} 
					
					$percent = '<td><font color="red"><b>'.$p.$alert.'</b></font></td>';
				} else {
					$percent = '<td>0</td>';
					
				}
			}
			$sPrice = $value->price;
			/*if (endsWith($value->symbol, "USDT") && floatval($value->symbol) > 0) {
				$sPrice = round(floatval($value->price), 2);
			}*/
			if ($p <= 0.5 && $p >= -2) {
				$tr = '';
			} else {
				$tr = $tr.$closed.'<td>'.$sPrice.'</td>'.$percent.$buysell.'</tr>';
			}
			
			$object = (object) [
				'tr' => $tr,
				'p'=> $p
			];
			if ($p < 0) {
				$arrObj[] = $object;
			} else {
				$arrObj2[] = $object;
			}
		}
		
	}
	usort($arrObj, "cmp");
	usort($arrObj2, "cmp2");
	foreach ($arrObj as $obj) {
		$table = $table.$obj->tr;
	}
	foreach ($arrObj2 as $obj) {
		$table2 = $table2.$obj->tr;
	}
	
	$table2 = $table2."</table></div><div style=\"clear:both;\"></div>";
	$table = $pct.$table."</table></div>".$table2;
	//echo $symbolList;
	echo $table;
} else if(is_string($dataResp))
{
	echo $dataResp;
} else echo "invalid";
//print_r($data);
?>
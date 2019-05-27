<?php
session_start();
date_default_timezone_set('Europe/London');

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

function initSymbolData() {
	$date = new DateTime(null);

	$minute = $date->format('i');
	$startTime = 0;
	$hour = 6;
	$startTime = $date->getTimestamp()-(60+$minute)*60-$date->format('s');
	
	//session_unset();
	
	if (!isset($_SESSION['symbolTimestam']) || $_SESSION['symbolTimestam'] != $startTime) {
		$symbolStr = 'ETHBTC,LTCBTC,BNBBTC,NEOBTC,BCCBTC,GASBTC,HSRBTC,MCOBTC,WTCBTC,LRCBTC,QTUMBTC,YOYOBTC,OMGBTC,ZRXBTC,STRATBTC,SNGLSBTC,BQXBTC,KNCBTC,FUNBTC,SNMBTC,IOTABTC,LINKBTC,XVGBTC,SALTBTC,MDABTC,MTLBTC,SUBBTC,EOSBTC,SNTBTC,ETCBTC,MTHBTC,ENGBTC,DNTBTC,ZECBTC,BNTBTC,ASTBTC,DASHBTC,OAXBTC,ICNBTC,BTGBTC,EVXBTC,REQBTC,VIBBTC,TRXBTC,POWRBTC,ARKBTC,XRPBTC,MODBTC,ENJBTC,STORJBTC,VENBTC,KMDBTC,RCNBTC,NULSBTC,RDNBTC,XMRBTC,DLTBTC,AMBBTC,BATBTC,BCPTBTC,ARNBTC,GVTBTC,CDTBTC,GXSBTC,POEBTC,QSPBTC,BTSBTC,XZCBTC,LSKBTC,TNTBTC,FUELBTC,MANABTC,BCDBTC,DGDBTC,ADXBTC,ADABTC,PPTBTC,CMTBTC,XLMBTC,CNDBTC,LENDBTC,WABIBTC,TNBBTC,WAVESBTC,GTOBTC,ICXBTC,OSTBTC,ELFBTC,AIONBTC,NEBLBTC,BRDBTC,EDOBTC,WINGSBTC,NAVBTC,LUNBTC,TRIGBTC,APPCBTC,VIBEBTC,RLCBTC,INSBTC,PIVXBTC,IOSTBTC,CHATBTC,STEEMBTC,NANOBTC,VIABTC,BLZBTC,AEBTC,RPXBTC,NCASHBTC,POABTC,ZILBTC,ONTBTC,STORMBTC,XEMBTC,WANBTC,WPRBTC,QLCBTC,SYSBTC,GRSBTC,CLOAKBTC,GNTBTC,LOOMBTC,BCNBTC,REPBTC,TUSDBTC,ZENBTC,SKYBTC,CVCBTC,THETABTC,IOTXBTC,QKCBTC,AGIBTC,NXSBTC,DATABTC,SCBTC,NPXSBTC,KEYBTC,NASBTC,MFTBTC,DENTBTC,ARDRBTC,HOTBTC,VETBTC,DOCKBTC,POLYBTC,PHXBTC,HCBTC,GOBTC,PAXBTC,RVNBTC,DCRBTC,USDCBTC,MITHBTC,BCHABCBTC,BCHSVBTC,RENBTC,BTTBTC,ONGBTC,FETBTC,CELRBTC,MATICBTC,ATOMBTC,PHBBTC,TFUELBTC';
		$symbolList = explode(',', $symbolStr);
		
		$btcData = json_decode(CallAPI('GET', 'https://api.binance.com/api/v3/ticker/price?symbol=BTCUSDT'));
		$btcPrice = floatval($btcData->price);
		foreach ($symbolList as $symbol) {
			
			$_SESSION['symbolTimestam'] = $startTime;

			$url = 'https://www.binance.com/api/v1/klines?symbol='.$symbol.'&interval=1h&startTime='.$startTime.'000'.'&limit=1';
			//echo $url.'-----';
			$dataResp = json_decode(CallAPI('GET', $url));

			$date = new DateTime();
			foreach ($dataResp as $item) {

				$date->setTimestamp($item[6]/1000 + ($hour*60*60));
				
				$_SESSION[$symbol.'_data'] = $item[4];
				$_SESSION[$symbol.'_date'] = $date->format('d/m/Y H:i:s');
				
				$dayVol = json_decode(CallAPI('GET', 'https://api.binance.com/api/v1/ticker/24hr?symbol='.$symbol));
				$volBTC = $dayVol->quoteVolume;
				$_SESSION[$symbol.'_vol'] = round(floatval($volBTC), 2);
				// round(floatval($item[5])/$btcPrice, 2);
				$buy = (round((floatval($item[4])*100000000 - floatval($item[4])*100000000*15/100), 0)).'';
				$sell = (round((floatval($item[4])*100000000 - floatval($item[4])*100000000*12/100), 0)).'';
				$_SESSION[$symbol.'_buy'] = getDecimal($buy);
				$_SESSION[$symbol.'_sell'] = getDecimal($sell);
			}
			
			
		}
	}
	
	//var_dump($_SESSION);
}

initSymbolData();
?>
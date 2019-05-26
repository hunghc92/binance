<html>
<head>
<script src="jquery-3.4.1.min.js"></script>
</head>
<body>
<div id="container" style="width:100%;">
<div id="result" style="border:1px solid;"></div>
<div style="clear:both;"></div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		var initSuccess = true;
		var threadInit = setInterval(function(){
			if (initSuccess) {
				initSuccess = false;
				$.get( "/binance/initsymboldata.php", function( data ) {
					initSuccess = true;
				});
			}
		}, 1000);
		var flag = true;
		var symbolsString = 'ETHBTC,LTCBTC,BNBBTC,NEOBTC,BCCBTC,GASBTC,HSRBTC,MCOBTC,WTCBTC,LRCBTC,QTUMBTC,YOYOBTC,OMGBTC,ZRXBTC,STRATBTC,SNGLSBTC,BQXBTC,KNCBTC,FUNBTC,SNMBTC,IOTABTC,LINKBTC,XVGBTC,SALTBTC,MDABTC,MTLBTC,SUBBTC,EOSBTC,SNTBTC,ETCBTC,MTHBTC,ENGBTC,DNTBTC,ZECBTC,BNTBTC,ASTBTC,DASHBTC,OAXBTC,ICNBTC,BTGBTC,EVXBTC,REQBTC,VIBBTC,TRXBTC,POWRBTC,ARKBTC,XRPBTC,MODBTC,ENJBTC,STORJBTC,VENBTC,KMDBTC,RCNBTC,NULSBTC,RDNBTC,XMRBTC,DLTBTC,AMBBTC,BATBTC,BCPTBTC,ARNBTC,GVTBTC,CDTBTC,GXSBTC,POEBTC,QSPBTC,BTSBTC,XZCBTC,LSKBTC,TNTBTC,FUELBTC,MANABTC,BCDBTC,DGDBTC,ADXBTC,ADABTC,PPTBTC,CMTBTC,XLMBTC,CNDBTC,LENDBTC,WABIBTC,TNBBTC,WAVESBTC,GTOBTC,ICXBTC,OSTBTC,ELFBTC,AIONBTC,NEBLBTC,BRDBTC,EDOBTC,WINGSBTC,NAVBTC,LUNBTC,TRIGBTC,APPCBTC,VIBEBTC,RLCBTC,INSBTC,PIVXBTC,IOSTBTC,CHATBTC,STEEMBTC,NANOBTC,VIABTC,BLZBTC,AEBTC,RPXBTC,NCASHBTC,POABTC,ZILBTC,ONTBTC,STORMBTC,XEMBTC,WANBTC,WPRBTC,QLCBTC,SYSBTC,GRSBTC,CLOAKBTC,GNTBTC,LOOMBTC,BCNBTC,REPBTC,TUSDBTC,ZENBTC,SKYBTC,CVCBTC,THETABTC,IOTXBTC,QKCBTC,AGIBTC,NXSBTC,DATABTC,SCBTC,NPXSBTC,KEYBTC,NASBTC,MFTBTC,DENTBTC,ARDRBTC,HOTBTC,VETBTC,DOCKBTC,POLYBTC,PHXBTC,HCBTC,GOBTC,PAXBTC,RVNBTC,DCRBTC,USDCBTC,MITHBTC,BCHABCBTC,BCHSVBTC,RENBTC,BTTBTC,ONGBTC,FETBTC,CELRBTC,MATICBTC,ATOMBTC,PHBBTC,TFUELBTC';
		var symbols = symbolsString.split(',');
		var threadLoad = setInterval(function(){
			try {
				if (flag) {
					flag = false;
					$.get( "/binance/getdata.php", function( data ) {
						// console.log(data);
						$("#result").html(data);
						flag = true;
					});
				}
				
			} catch(e) {
				console.log(e);
			}
		}, 3000);
	});
</script>
</body>
<html>
<head>
<script src="jquery-3.4.1.min.js"></script>
<style>
table {
	border-left: 1px solid;
	border-bottom: 1px solid;
}
table td, table th {
	border-top: 1px solid;
	border-right: 1px solid;
	padding-left: 10px;
	padding-right: 10px;
	margin: 0;
}
</style>
</head>
<body>
<div id="container" style="width:100%;">
<div id="result" style="margin: 0 auto; width: 900px; /*border:1px solid;*/"></div>
<div style="clear:both;"></div>
</div>
<!-- <button onclick="notifyMe()">Notify me!</button> -->
<script type="text/javascript">
	function notifyMe() {
	  if (Notification.permission !== 'granted')
		Notification.requestPermission();
	  else {
		var notification = new Notification('Notification title', {
		  icon: 'http://cdn.sstatic.net/stackexchange/img/logos/so/so-icon.png',
		  body: 'Hey there! You"ve been notified!',
		});

		notification.onclick = function () {
		  window.open('http://stackoverflow.com/a/13328397/1269037');
		};

	  }

	}
	$(document).ready(function() {
		var initSuccess = true;
		var threadInit = setInterval(function(){
			if (initSuccess) {
				initSuccess = false;
				$.get( "/binance/initsymboldata.php", function( data ) {
					console.log(data);
					initSuccess = true;
				});
			}
		}, 1000);
		var flag = true;
		var symbolsString = 'ETHBTC,LTCBTC,BNBBTC,NEOBTC,BCCBTC,GASBTC,HSRBTC,MCOBTC,WTCBTC,LRCBTC,QTUMBTC,YOYOBTC,OMGBTC,ZRXBTC,STRATBTC,SNGLSBTC,BQXBTC,KNCBTC,FUNBTC,SNMBTC,IOTABTC,LINKBTC,XVGBTC,SALTBTC,MDABTC,MTLBTC,SUBBTC,EOSBTC,SNTBTC,ETCBTC,MTHBTC,ENGBTC,DNTBTC,ZECBTC,BNTBTC,ASTBTC,DASHBTC,OAXBTC,ICNBTC,BTGBTC,EVXBTC,REQBTC,VIBBTC,TRXBTC,POWRBTC,ARKBTC,XRPBTC,MODBTC,ENJBTC,STORJBTC,VENBTC,KMDBTC,RCNBTC,NULSBTC,RDNBTC,XMRBTC,DLTBTC,AMBBTC,BATBTC,BCPTBTC,ARNBTC,GVTBTC,CDTBTC,GXSBTC,POEBTC,QSPBTC,BTSBTC,XZCBTC,LSKBTC,TNTBTC,FUELBTC,MANABTC,BCDBTC,DGDBTC,ADXBTC,ADABTC,PPTBTC,CMTBTC,XLMBTC,CNDBTC,LENDBTC,WABIBTC,TNBBTC,WAVESBTC,GTOBTC,ICXBTC,OSTBTC,ELFBTC,AIONBTC,NEBLBTC,BRDBTC,EDOBTC,WINGSBTC,NAVBTC,LUNBTC,TRIGBTC,APPCBTC,VIBEBTC,RLCBTC,INSBTC,PIVXBTC,IOSTBTC,CHATBTC,STEEMBTC,NANOBTC,VIABTC,BLZBTC,AEBTC,RPXBTC,NCASHBTC,POABTC,ZILBTC,ONTBTC,STORMBTC,XEMBTC,WANBTC,WPRBTC,QLCBTC,SYSBTC,GRSBTC,CLOAKBTC,GNTBTC,LOOMBTC,BCNBTC,REPBTC,TUSDBTC,ZENBTC,SKYBTC,CVCBTC,THETABTC,IOTXBTC,QKCBTC,AGIBTC,NXSBTC,DATABTC,SCBTC,NPXSBTC,KEYBTC,NASBTC,MFTBTC,DENTBTC,ARDRBTC,HOTBTC,VETBTC,DOCKBTC,POLYBTC,PHXBTC,HCBTC,GOBTC,PAXBTC,RVNBTC,DCRBTC,USDCBTC,MITHBTC,BCHABCBTC,BCHSVBTC,RENBTC,BTTBTC,ONGBTC,FETBTC,CELRBTC,MATICBTC,ATOMBTC,PHBBTC,TFUELBTC';
		var symbols = symbolsString.split(',');
		var counter = 0;
		var alert = false;
		var threadLoad = setInterval(function(){
			try {
				if (flag) {
					flag = false;
					$.get( "/binance/getdata.php", function( data ) {
						// console.log(data);
						$("#result").html(data);
						if ($(".alert").length > 0) {
							if (!alert) {
								if (Notification.permission !== 'granted')
									Notification.requestPermission();
								else {
									var notification = new Notification('Binance Alert', {
									  icon: 'http://cdn.sstatic.net/stackexchange/img/logos/so/so-icon.png',
									  body: 'ALERT !!!!!!',
									});
									
								}
								counter = 1;
								alert = true;
							}
						}
						flag = true;
					});
				}
				if (counter > 0) {
					counter++;
					if (counter == 4) {
						counter = 0;
						alert = false;
					}
				}
			} catch(e) {
				console.log(e);
			}
		}, 3000);
		
		// request permission on page load
		document.addEventListener('DOMContentLoaded', function () {
		  if (!Notification) {
			alert('Desktop notifications not available in your browser. Try Chromium.'); 
			return;
		  }

		  if (Notification.permission !== 'granted')
			Notification.requestPermission();
		});

		
	});
</script>
</body>
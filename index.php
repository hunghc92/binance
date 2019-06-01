<html>
<head>
<script src="jquery-3.4.1.min.js"></script>
<style>
.alert_down {
	display: none;
}
table {
	border-left: 1px solid;
	border-bottom: 1px solid;
}
table td, table th {
	border-top: 1px solid;
	border-right: 1px solid;
	padding-left: 5px;
	padding-right: 5px;
	margin: 0;
}
</style>
</head>
<body>
<div id="btc" style="color:red; font-weight:bold;"></div>
<div id="result" style="width:100%;">
	
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
	var initSuccess = true;
	function loadBTC() {
		if (initSuccess) {
			initSuccess = false;
			$.get( "/binance/initsymboldata.php", function( data ) {
				//console.log(data);
				$("#btc").text("BTC Price: " + data);
				document.title = "BTC Price: " + data;
				initSuccess = true;
			});
		}
	}
	
	var flag = true;
	var alert = false;
	var counter = 0;
	function loadData() {
		try {
				if (flag) {
					flag = false;
					$.get( "/binance/getdata.php", function( data ) {
						// console.log(data);
						$("#result").html(data);
						if ($(".alert_down").length > 0) {
							
							if (!alert) {
								$(".alert_down").each(function () {
									if (Notification.permission !== 'granted')
										Notification.requestPermission();
									else {
										var notification = new Notification('Binance Alert', {
										  icon: 'http://cdn.sstatic.net/stackexchange/img/logos/so/so-icon.png',
										  body: 'DOWN: ' + $(this).text(),
										});
										
									}
								});
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
	}
	$(document).ready(function() {
		// request permission on page load
		document.addEventListener('DOMContentLoaded', function () {
		  if (!Notification) {
			alert('Desktop notifications not available in your browser. Try Chromium.'); 
			return;
		  }

		  if (Notification.permission !== 'granted')
			Notification.requestPermission();
		});
		
		loadBTC();
		var threadInit = setInterval(loadBTC, 1000);
		
		loadData();
		var threadLoad = setInterval(loadData, 3000);
	});
</script>
</body>
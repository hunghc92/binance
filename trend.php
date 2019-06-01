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
<div id="result4h" style="width:100%;">
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
	var init4h = true;
	function loadData4h() {
		if (init4h) {
			init4h = false;
			$.get( "/binance/trend-4h.php", function( data ) {
				console.log(data);
				$("#result4h").html(data);
				init4h = true;
			});
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
		
		loadData4h();
		var threadInit = setInterval(loadData4h, 10000);
		
		
	});
</script>
</body>
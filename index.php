<html>
<head>
<script src="jquery-3.4.1.min.js"></script>
</head>
<body>
<div id="result">

</div>
<script type="text/javascript">

	var threadLoad = setInterval(function(){
		$.( "/binance/getdata.php", function( data ) {
			var arr = JSON.parse(data.replace(/'/g, '"'));
			var context = "";
			for (var i = 0; i< arr.length; i++){
				var date = new Date(arr[i].time);
				context += date.toString();
			}
			$("#result").text(context);
		});
	}, 1000);

</script>
</body>
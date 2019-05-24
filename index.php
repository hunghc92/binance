<html>
<head>
<script src="jquery-3.4.1.min.js"></script>
</head>
<body>
<div id="result">
<?php
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
$data = CallAPI('GET', 'https://api.binance.com/api/v1/trades?symbol=ETHBTC&limit=1');
?>
</div>
<input type="hidden" id="hdfData" value="<?php echo str_replace('"', "'", $data); ?>" />
<script type="text/javascript">
var arr = JSON.parse($("#hdfData").val().replace(/'/g, '"'));
var context = "";
for (var i = 0; i< arr.length; i++){
	var date = new Date(arr[i].time);
	context += date.toString();
}
	
$("#result").text(context);
</script>
</body>
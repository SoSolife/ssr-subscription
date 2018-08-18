<?php
	if (!isset($_GET["url"])) {
		die("参数错误！");
	}
	$http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
	$SubUrl = $http_type.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
	$SubUrl = dirname($SubUrl);
	$SubUrl = $SubUrl.'/sub.php?'.$_SERVER['QUERY_STRING'];

?>

<!doctype html>
<html>
<head>
	<meta charset="utf-8"/>
	<meta http-equiv="Cache-Control" content="no-siteapp" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>SSR订阅提取</title>
	<link type="text/css" rel="stylesheet" href="style.css"/>
</head>
<body>
	<center>
		<h2>SSR订阅提取</h2>
		<br>
		<p>
			您的订阅地址是：
			<br><br>
			<a href="<?php echo $SubUrl; ?>"><?php echo $SubUrl; ?></a>
		</p>
	<center>

	<footer>
	  <p><small>By <a href="http://hicasper.com">hiCasper</a></small></p>
	</footer>
	<script>
		var _hmt = _hmt || [];
		(function() {
		var hm = document.createElement("script");
		hm.src = "//hm.baidu.com/hm.js?b80974a784c1807a572f8d52160788b1";
		var s = document.getElementsByTagName("script")[0];
		s.parentNode.insertBefore(hm, s);
		})();
	</script>

</body>
</html>
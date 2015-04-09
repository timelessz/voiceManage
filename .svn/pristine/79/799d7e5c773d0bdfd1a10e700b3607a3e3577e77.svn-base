<?php
    if(C('LAYOUT_ON')) {
        echo '{__NOLAYOUT__}';
    }
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<link rel="stylesheet" type="text/css" href="__PUBLIC__{$style_common}/css/jquery.mobile.structure-1.4.2.min.css"/>
<link rel="stylesheet" type="text/css" href="__PUBLIC__{$style_common}/css/idangerous.swiper.css"/>
<link rel="stylesheet" type="text/css" href="__PUBLIC__{$style}/style/common.css"/>
<link rel="stylesheet" type="text/css" href="__PUBLIC__{$style}/style/style.css"/>
<title>跳转提示</title>
<style type="text/css">
*{ padding: 0; margin: 0; }
body{ background: #fff; font-family: '微软雅黑'; color: #333; font-size: 16px; }
.system-message{ margin:20px auto;}
.system-message .jump{ padding-top: 10px;text-align:center;}
.system-message .jump a{ color: #333;}
.system-message .success,.system-message .error{ line-height: 1.8em; text-align:center;}
.system-message .detail{ font-size: 12px; line-height: 20px; margin-top: 12px; display:none}
</style>
</head>
<body>
<div data-role="page" class="dogo-page">
	<div class="system-message">
	<p class="jump"><img src='__PUBLIC__/Common/img/load.gif' /></p>
	<?php if(isset($message)) {?>
	<p class="success"><?php echo($message); ?></p>
	<?php }else{?>
	<p class="error"><?php echo($error); ?></p>
	<?php }?>
	<p class="detail"></p>
	<p class="jump">
	页面自动 <a id="href" href="<?php echo($jumpUrl); ?>">跳转</a> 等待时间： <b id="wait"><?php echo($waitSecond); ?></b>
	</p>
	</div>
</div>
<script type="text/javascript">
(function(){
var wait = document.getElementById('wait'),href = document.getElementById('href').href;
var interval = setInterval(function(){
	var time = --wait.innerHTML;
	if(time <= 0) {
		location.href = href;
		clearInterval(interval);
	};
}, 1000);
})();
</script>
</body>
</html>
<script type="text/javascript" src="__PUBLIC__{$style_common}/js/jquery-2.1.0.min.js"></script>
<script type="text/javascript" src="__PUBLIC__{$style_common}/js/jquery.mobile-1.4.2.min.js"></script>

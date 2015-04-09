<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <title>录音管理</title>
        <link rel="stylesheet" type="text/css" href="/voiceManage/Public/Home/style/common.css"/>
        <link rel="stylesheet" type="text/css" href="/voiceManage/Public/Home/style/admin.css"/>
    </head>
    <body>

</head>
<body id="login">
    <div class="login wp">
        <div class="logintop"></div>
        <!--<div class="loginlogo"><img src="/voiceManage/Public/images/logo.gif"/></div>--> 
        <form action="<?php echo U('Admin/Login/dologin');?>" method="post" name="login_box" id="login_box">
            <div class="loginfrom">
                <div class="loginfrom_con">
                    <div class="user">
                        <input type="text" name="user_name" class="logininput" required />
                    </div>
                    <div class="pass">
                        <input type="password" name="user_password" class="logininput" required />
                    </div>
                    <div class="yazhengma">
                        <div class="yz_left">
                            <input type="text" name="vd_code" id="vd_code" class="logininput" required />
                        </div><!--yz_left-->
                        <div class="yz_right">
                            <span class="dogo-click-yzmurl" style="cursor: pointer;"><img src="<?php echo U('Admin/Login/verify');?>" title="看不清？点击更换验证码。">看不清楚？点击图片</span>
                        </div><!--yz_right--> 
                        <div class="clear"></div><!--clear-->
                    </div><!--yazhengma-->
                </div><!--loginfrom_con-->
                <div class="loginad"> </div><!--dloginad-->
                <div class="loginfromfoot">
                    <div class="loginfromfoot_left">
                        <h3></h3>
                    </div><!--loginfromfoot_left-->
                    <div class="loginfromfoot_right">
                        <input type="submit" class="login_sub button" value="登录"/>
                    </div><!--loginfromfoot_right-->
                    <div class="clear"></div>
                </div><!--loginfromfoot-->
            </div><!--loginfrom-->
        </form>
    </div>
    <script type="text/javascript" src="/voiceManage/Public/Common/js/jquery-2.1.1.min.js"></script>  
    <script>
        $(function () {
            $('.dogo-click-yzmurl').click(function () {
                var url = "<?php echo U('Admin/Login/verify');?>?tm=" + Math.random();
                $('.dogo-click-yzmurl img').attr('src', url);
            });
        });
    </script>




</body>
</html>
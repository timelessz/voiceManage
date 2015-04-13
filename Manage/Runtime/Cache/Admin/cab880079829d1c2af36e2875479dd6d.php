<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <title>录音管理</title>
        <link id="easyuiTheme" rel="stylesheet" type="text/css" href="/voiceManage/Public/Easyui/themes/default/easyui.css"/>
        <link rel="stylesheet" type="text/css" href="/voiceManage/Public/Easyui/themes/default/portal.css"/><!--此css引入要根据情况修改-->
        <link rel="stylesheet" type="text/css" href="/voiceManage/Public/Easyui/themes/icon.css"/>
        <link rel="stylesheet" type="text/css" href="/voiceManage/Public/Home/style/common.css"/>
        <link rel="stylesheet" type="text/css" href="/voiceManage/Public/Home/style/admin.css"/>
        <link rel="stylesheet" type="text/css" href="/voiceManage/Public/Kindeditor/themes/default/default.css"/>
        <link rel="stylesheet" type="text/css" href="/voiceManage/Public/Kindeditor/themes/simple/simple.css"/>
        <script type="text/javascript" src="/voiceManage/Public/Common/js/jquery-1.8.2.min.js"></script>
        <script type="text/javascript" src="/voiceManage/Public/Easyui/jquery.easyui.min.js"></script>
        <script type="text/javascript" src="/voiceManage/Public/Easyui/jquery.portal.js"></script>
        <script type="text/javascript" src="/voiceManage/Public/Easyui/EASYUICOMMON.js"></script>
        <script type="text/javascript" src="/voiceManage/Public/Easyui/locale/easyui-lang-zh_CN.js"></script>
        <script type="text/javascript" src="/voiceManage/Public/Kindeditor/kindeditor-min.js"></script>
        <script type="text/javascript" src="/voiceManage/Public/Kindeditor/lang/zh_CN.js"></script>
        <link rel="stylesheet" href="/voiceManage/Public/Common/js/validator/jquery.validator.css">
        <script type="text/javascript" src="/voiceManage/Public/Common/js/validator/jquery.validator.js"></script>
        <script type="text/javascript" src="/voiceManage/Public/Common/js/validator/local/zh_CN.js"></script>
        <script src="/voiceManage/Public/hightchart/highcharts.js"></script>
        <script type="text/javascript" src="/voiceManage/Public/Common/js/ajaxfileupload.js"></script>
    </head>

    <body>

</head>
<body class="easyui-layout layout_index">
    <script type="text/javascript">
        $(function () {
            $('.menulist li a').click(function () {
                var classId = 'proListTab';
                var subtitle = $(this).text();
                var url = $(this).attr('cmshref');
                var rel = $(this).attr('rel');
                if (rel == 'dialog') {
                    var type = $(this).attr('type');
                    openDialog(type, url, subtitle);
                    return false;
                }
                if (!$('#tabs_' + classId).tabs('exists', subtitle)) {
                    $('#tabs_' + classId).tabs('add', {
                        title: subtitle,
                        content: subtitle,
                        closable: true,
                        href: url,
                        tools: [{
                                iconCls: 'icon-mini-refresh',
                                handler: function () {
                                    updateTab(classId, url, subtitle);
                                }
                            }]
                    });
                    return false;
                } else {
                    $('#tabs_' + classId).tabs('select', subtitle);
                    return false;
                }
            });

            $('.sider li a').click(function () {
                var classId = 'index';
                var subtitle = $(this).text();
                var url = $(this).attr('cmshref');
                var rel = $(this).attr('rel');
                if (rel == 'dialog') {
                    var type = $(this).attr('type');
                    openDialog(type, url, subtitle);
                    return false;
                }
                if (!$('#tabs_' + classId).tabs('exists', subtitle)) {
                    $('#tabs_' + classId).tabs('add', {
                        title: subtitle,
                        content: subtitle,
                        closable: true,
                        href: url,
                        tools: [{
                                iconCls: 'icon-mini-refresh',
                                handler: function () {
                                    updateTab(classId, url, subtitle);
                                }
                            }]
                    });
                    return false;
                } else {
                    $('#tabs_' + classId).tabs('select', subtitle);
                    return false;
                }
            });
        });
    </script>

    <noscript>
    <div style=" position:absolute; z-index:100000; height:246px;top:0px;left:0px; width:100%; background:white; text-align:center;">
        <img src="images/noscript.gif" alt='抱歉，请开启脚本支持！' />
    </div>
    </noscript>
    <div data-options="region:'north',border:false,split:true" style="height:60px;padding:2px">
        <div class="admintop">
            <div class="adminlogo fleft">
                <!--<img src="/voiceManage/Public/Home/images/logo.gif" width="" height="55px"/>-->
            </div>
            <div class="admincon fleft"></div>
            <div class="adminmember fright">

                
<div style="background:#fafafa;padding:5px;margin-right: 100px ;margin-top:2px;border:1px solid #ccc">    
    <a href="#" class="easyui-menubutton" menu="" iconCls="icon-ok">您好：<?php echo session('USER_NAME');?></a>
    <a href="#" class="easyui-menubutton" menu="#mm3" iconCls="icon-ok">退出登录</a>
    <a href="#" class="easyui-menubutton" menu="#skin" iconCls="icon-edit">切换皮肤</a>
</div>

<div id="mm3" style="width:100px;">
    <div iconCls="icon-undo"><a href="<?php echo U('Admin/Login/logout');?>" class="">退出登录</a></div>
</div>
<div id="skin" style="width:100px;">
    <div onclick="changeTheme('default');">default</div>
    <div onclick="changeTheme('bootstrap');">bootstrap</div>
    <div onclick="changeTheme('gray');">gray</div>
    <div onclick="changeTheme('metro');">metro</div>
</div>
            </div>
            <div class="clear"></div>
        </div><!--admintop-->
    </div>
    <div data-options="region:'west',split:true,title:'导航菜单'" style="width:200px;">
        <div class="easyui-accordion sider" data-options="fit:true,border:false">
            <!--//左侧菜单导航-->
            <?php if(is_array($menu)): foreach($menu as $key=>$list): ?><div title="<?php echo ($list["label"]); ?>" data-options="iconCls:'icon-mini-add'" style="padding:10px;">
        <ul class="easyui-tree" data-options="animate:true">
            <?php if(is_array($list["items"])): foreach($list["items"] as $skey=>$slist): ?><li data-options="state:'open'">
                    <span><?php echo ($slist["label"]); ?></span>
                    <ul>
                        <?php if(is_array($slist["items"])): foreach($slist["items"] as $sskey=>$sslist): ?><li><a href="javascript:viod(0);" cmshref="<?php echo ($sslist["link"]); ?>" type="<?php echo ($sslist["type"]); ?>" rel="<?php echo ($sslist["rel"]); ?>"><?php echo ($sslist["label"]); ?></a></li><?php endforeach; endif; ?>
                    </ul>
                </li><?php endforeach; endif; ?>
        </ul>
    </div><!--waiceng--><?php endforeach; endif; ?>
            <!--//左侧菜单导航-->
        </div><!--accordion-->

    </div><!--west-->
    <div data-options="region:'east',split:true,collapsed:true,title:'公司电话联系方式；'" style="width:220px;padding:10px;">
        
    </div>
    <div data-options="region:'south',split:false,border:false" style="height:50px;padding:10px; text-align:center;">版权：强比科技<br/>开发者：强比科技技术部</div>
    <!--//主体内容部分-->
    <div data-options="region:'center'" class="indexcenter" title="内容管理系统">
        <div id="tabs_index" class="easyui-tabs"  fit="true" border="false">
            <div title="<?php echo L('home');?>" style="overflow:hidden; " >
                <div id="pp" style="position:relative;">
    <div style="width:40%;">
        <div title="实用工具" collapsible="true" closable="false" style="height:250px;padding:5px;">
        </div>
        <div title="日历" collapsible="true" closable="true" style="width: 80%;height:290px;padding:5px;margin:0 auto;">

        </div>
    </div>
    <div style="width:40%;">
        <div title="百度搜索" collapsible="true" closable="true" style="height:200px;padding:5px;">


        </div>
        <div title="日历" style="width:250px;height:250px;padding:5px;">

        </div>
    </div>
</div>
<script>
    $(function () {
        $('#pp').portal({
            border: false,
            fit: true,
        });
    });

</script>
            </div>
            <div title="<?php echo L('welcome');?>" style="padding:10px; " data-options="closable:true">

            </div>
        </div>
    </div><!--center-->
    <!--//主体内容部分-->
    <div id="dialog_cms" data-options="iconCls:'icon-save'">
    </div>




</body>
</html>
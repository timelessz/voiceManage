<?php

return array(
    //'配置项'=>'配置值'
    'SESSION_AUTO_START' => true, //是否开启session
    'LOAD_EXT_CONFIG' => 'db', // 加载扩展配置文件
    'MODULE_DENY_LIST' => array('Common', 'Runtime'),
    'MODULE_ALLOW_LIST' => array('Admin'), //允许访问的模块列表
    'DEFAULT_MODULE' => 'Admin', //默认模块
    'FILE_PATH' => 'D:\voice\pbxrecord',
    'URL_PATH' => 'http://192.168.1.150' . DIRECTORY_SEPARATOR,
//    'CURL_PATH' => 'salesmen.cn/index.php/Api/',
    'CURL_PATH' => '127.0.0.1/salesmen1/index.php/Api/',
);

<?php

$menuarray = array(
    array('label' => '我的录音', 'type' => 'user_name', 'state' => 'open', 'items' => array(
            array('label' => '我的录音', 'type' => 'member_name', 'items' => array(
                    array('label' => '查看录音', 'type' => 'changepwd', 'link' => __MODULE__ . '/Filelist/index'),
                )),
        )),
);

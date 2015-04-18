<?php

$menuarray_DEPARTMENTMANAGE = array(
    array('label' => '录音管理', 'type' => 'user_name', 'state' => 'open', 'items' => array(
            array('label' => '录音查看', 'type' => 'member_name', 'items' => array(
                    array('label' => '录音列表', 'type' => 'changepwd', 'link' => __MODULE__ . '/Alllist/index'),
                )),
            array('label' => '录音统计', 'type' => 'member_name', 'items' => array(
                    array('label' => '条件统计', 'link' => __MODULE__ . '/Statistics/conditionCount'),
                )),
            array('label' => '数据更新', 'items' => array(
                    array('label' => '更新数据', 'link' => __MODULE__ . '/Updatedata/index'),
                )),
        )),
);

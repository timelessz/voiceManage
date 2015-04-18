<?php

$menuarray_BOSS = array(
    array('label' => '录音管理', 'type' => 'user_name', 'state' => 'open', 'items' => array(
            array('label' => '录音查看', 'items' => array(
                    array('label' => '录音列表', 'link' => __MODULE__ . '/Alllist/index'),
                )),
            array('label' => '录音统计', 'items' => array(
                    array('label' => '条件统计', 'link' => __MODULE__ . '/Statistics/condition_count'),
                    array('label' => '部门或个人统计', 'link' => __MODULE__ . '/Statistics/deporperson_count'),
                )),
            array('label' => '数据更新', 'items' => array(
                    array('label' => '更新数据', 'link' => __MODULE__ . '/Updatedata/index'),
                )),
        )),
);

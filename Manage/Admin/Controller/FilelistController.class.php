<?php

/**
 * FilemanageController.class.php 
 * 订单列表实现
 * @author timelesszhuang
 * @version salesmen 1.0
 * @copyright 赵兴壮
 * @package  Controller
 * @todo 进入订单分类操作界面
 * 2014年10月20 16:47
 */

namespace Admin\Controller;

use Think\Controller;

class FilelistController extends BaseController {

    /**
     * 主页跳转到主页
     * @author 赵兴壮
     * @version salesmen 1.0
     * @access public
     * @todo 跳转到列表主页进行操作
     */
    public function index() {
        $this->display();
    }

    /**
     * 获取过去十天的时间列表   
     * @author 赵兴壮
     * @version salesmen 1.0
     * @access public
     * @todo 点击之后现实相应时间的数据
     * @return array data 根据id 查询到的数据
     */
    public function jsonTree() {
        $Data = $this->generateTreeData();
        $data = array(array('id' => 'no', 'text' => '日期列表', 'state' => 'open', 'children' => $Data));
        exit(json_encode($data));
    }

    /**
     * 形成jsontree 数据
     */
    public function generateTreeData() {
        $today = strtotime(date("Y-m-d 12:00:00", time()));
        $date = date('y年m月d日', $today);
        $Data[] = array('id' => '0', 'text' => '全部录音');
        $Data[] = array('id' => $today, 'text' => $date);
        for ($i = 1; $i < 15; $i++) {
            $str = "-" . $i . "day";
            $yesday = strtotime(date("Y-m-d 12:00:00", strtotime($str)));
            $date = date('y年m月d日', $yesday);
            $Data[] = array('id' => $yesday, 'text' => $date);
        }
        return $Data;
    }

    /**
     * 跳转到相应的录音列表
     */
    public function voicelist() {
        $time = I('get.id');
        $this->assign('id', $time);
        $this->display();
    }

    /**
     * 编辑信息页面
     * @author 赵兴壮
     * @version salesmen 1.0
     * @access public
     * @todo 编辑信息然后更新
     * @return array data 根据id 查询到的数据
     */
    public function play() {
        $id = I('get.id');
        $m = M('UserVoice');
        $condition['id'] = array('eq', $id);
        $perdata = $m->where($condition)->find();
        $this->assign('data', $perdata);
        $this->display();
    }

    /**
     * 编辑信息页面
     * @author 赵兴壮
     * @version salesmen 1.0
     * @access public
     * @todo 操纵数据
     * @return json data 获取用户信息根据 user_id
     */
    public function json() {
        $user_id = session("USER_ID");
        $m = M('UserVoice');
        $time = I('get.id');
        if ($time == 0) {
            $condition = 'user_id = ' . $user_id;
        } else {
            $starttime = strtotime(date('Y-m-d 00:00:00', $time));
            $stoptime = strtotime(date('Y-m-d 23:59:59', $time));
            $condition = 'user_id = ' . $user_id . ' and time > ' . $starttime . " and time < " . $stoptime;
        }
        #分页信息 前台发的请求
        $pageNumber = intval($_REQUEST['page']);
        $pageRows = intval($_REQUEST['rows']);
        #没有请求的话实现当前的页面是第一页
        $pageNumber = (($pageNumber == null || $pageNumber == 0) ? 1 : $pageNumber);
        #每一页显示的数量  默认是 10
        $pageRows = (($pageRows == FALSE) ? 10 : $pageRows);
        $firstRow = ($pageNumber - 1) * $pageRows;
        $payData = $m->where($condition)->order('time desc,id')->limit($firstRow . ',' . $pageRows)->select();
        $count = $m->where($condition)->count();
        $this->formatdata($payData);
        if ($count != 0) {
            $array['total'] = $count;
            $array['rows'] = $payData;
            echo json_encode($array);
        } else {
            $array['total'] = 0;
            $array['rows'] = array();
            echo json_encode($array);
        }
    }

    /**
     * formatdata
     * 格式化数据
     */
    private function formatdata(&$data) {
        foreach ($data as $k => $v) {
            $data[$k]['size'] = $this->formatSizeData($v['size']);
            $data[$k]['time'] = date('Y年m月d日 H:i:s', $v['time']);
            $data[$k]['play'] = '<audio controls="true" style="margin:0px;"><source src="' . $v['url_path'] . '" />你的浏览器不支持.</audio>';
            $data[$k]['type'] = $v['type'] == '10' ? '打出' : '打入';
        }
    }

    /**
     * 获取MP3时间长度
     */
    private function formatSizeData($size) {
        $min = intval($size / 60);
        $sec = $size % 60;
        if (empty($min)) {
            return $sec . '秒';
        }
        return $min . "分" . $sec . '秒';
    }

}

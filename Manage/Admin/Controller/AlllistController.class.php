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

class AlllistController extends BaseController {

    /**
     * 主页跳转到主页  职员的列表
     * @author 赵兴壮
     * @version salesmen 1.0
     * @access public
     * @todo 跳转到列表主页进行操作
     */
    public function index() {
        $this->display();
    }

    /**
     * jsonTree
     * 如果是部门经理的话只显示单个部门的信息   如果是 boss 的话 显示全部部门下面的职员信息
     * @author 赵兴壮
     * @version salesmen 1.0
     * @access public
     * @todo 编辑信息然后更新
     * @return array data 根据id 查询到的数据
     */
    public function jsonTree() {
        if (session('BOSS')) {
            $m = M('Department');
            $departmentdata = $m->where($condition)->getField('id,name');
            $data = array();
            foreach ($departmentdata as $key => $value) {
                $userdata = $this->getdata_bydepartmentid($key);
                if (!empty($userdata)) {
                    foreach ($userdata as $k => $v) {
                        $id = $k;
                        $text = $v;
                        $Data[] = array('id' => $id, 'text' => $text);
                    }
                } else {
                    $Data[] = array('id' => 0, 'text' => '暂无下级职员!');
                }
                $data[] = array('id' => 'no', 'text' => $value, 'state' => 'open', 'children' => $Data);
                #Data数据清空
                $Data = array();
            }
            #其他部门信息
            $department_id = 0;
            $otheruserdata = $this->getdata_bydepartmentid($department_id);
            if (!empty($otheruserdata)) {
                foreach ($otheruserdata as $k => $value) {
                    $id = $k;
                    $text = $value;
                    $Data[] = array('id' => $id, 'text' => $text);
                }
            } else {
                $Data[] = array('id' => 0, 'text' => '暂无下级职员!');
            }
            #其他用户
            $data[] = array('id' => 'no', 'text' => '其他部门', 'state' => 'open', 'children' => $Data);
            exit(json_encode($data));
        } else if (session('DEPARTMENT_ID')) {
            //产品分类获取  分配到前台
            $department_id = session('DEPARTMENT_ID');
            $userdata = $this->getdata_bydepartmentid($department_id);
            //要判断是不是该分组为空值
            if (!empty($userdata)) {
                foreach ($userdata as $k => $value) {
                    $id = $k;
                    $text = $value;
                    $Data[] = array('id' => $id, 'text' => $text);
                }
            } else {
                $Data[] = array('id' => 0, 'text' => '暂无下级职员!');
            }
            $data = array(array('id' => 'no', 'text' => '职员', 'state' => 'closed', 'children' => $Data));
            exit(json_encode($data));
        }
    }

    /**
     * getdeuserdata
     * 根据departmentid获取部门下的人
     */
    private function getdata_bydepartmentid($department_id) {
        $m = M('UserInfo');
        $condition['department_id'] = array('eq', $department_id);
        $userdata = $m->where($condition)->getField('user_id,name');
        return $userdata;
    }

    /**
     * 跳转到相应的录音列表
     */
    public function userlist() {
        $time = I('get.id');
        $this->assign('id', $time);
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
        $tel = $_REQUEST['tel'];
        $user_id = I('get.id');
        $m = M('UserVoice');
        #分页信息 前台发的请求
        $pageNumber = intval($_REQUEST['page']);
        $pageRows = intval($_REQUEST['rows']);
        #没有请求的话实现当前的页面是第一页
        $pageNumber = (($pageNumber == null || $pageNumber == 0) ? 1 : $pageNumber);
        #每一页显示的数量  默认是 10
        $pageRows = (($pageRows == FALSE) ? 10 : $pageRows);
        $firstRow = ($pageNumber - 1) * $pageRows;
        $condition['user_id'] = array('eq', $user_id);
        if (!empty($tel)) {
            $condition['tel'] = array('like', "%$tel%");
        }
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

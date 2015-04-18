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
     * 如果是部门经理的话只显示单个部门的信息   如果是 boss 的话 显示全部部门下面的职员信息   职员
     * @author 赵兴壮
     * @version salesmen 1.0
     * @access public
     * @todo 编辑信息然后更新
     * @return array data 根据id 查询到的数据
     */
    public function jsonTree() {
        $qiuyun = new \Org\Util\Qiuyun;
        list($org_data, $parent_id) = $this->get_tree_data();
        $tree = $qiuyun->list_to_tree($org_data, 'id', 'parent_id', 'children', $parent_id);
        exit(json_encode($tree));
    }

    /**
     * 获取部门信息跟用户信息
     */
    public function getorg_user_info() {
        $m = M('ComData');
        $where['id'] = array('eq', 2);
        $org_data = $m->where($where)->getField('serialize_data');
        $org = unserialize($org_data);
        $map['id'] = array('eq', 3);
        $userinfo_data = $m->where($map)->getField('serialize_data');
        $userinfo = unserialize($userinfo_data);
        return array($org, $userinfo);
    }

    /**
     * 根据权限信息获取组织架构信息以及职员
     * @access public
     */
    public function get_tree_data() {
        list($org, $userinfo) = $this->getorg_user_info();
        if (session('BOSS')) {
            //老板权限可以查看所用的职员的日报等的数据
            //要想使用刚才的   这个是总的架构
            $org_data = $org;
            $user_data = $userinfo;
            //要调用 该函数实现，两个数组合并并且新的在 $user_data中新生成id 字段 不能重复
            $parent_id = 0;
        } else if (session('DEPARTMENT_ID')) {
            //其他的只可以查看该部门的下的职员数据
            $dep_id = session('DEPARTMENT_ID');
            //如果是部门主管
            if (!empty($dep_id)) {
                //子孙节点id集合   当然还要获取当前的 部门的名称
                //同时把当前的部门id 放在id数组中
                $son_id_arr = $this->get_sondep_id($org, $dep_id);
                //然后获取当前的部门全部信息  跟该部门下面的职员信息
                //获取子部门的信息     获取到的数据有可能是空的
                $org_data = array();
                if (!empty($son_id_arr)) {
                    //表示不是空的时候执行的操作
                    $org_data = $this->get_son_dep_data($org, $son_id_arr);
                }
                $son_id_arr[] = $dep_id;
                //现在要获取当前的分类下面的用户
                $user_data = $this->get_dep_userinfo($userinfo, $son_id_arr);
                //获取自己部门的信息
                $this_org_data = $this->get_now_dep_info($org, $dep_id);
                $org_data[] = $this_org_data;
                //该元素的父亲元素id
                $parent_id = $this_org_data['parent_id'];
            }
        }
        $this->merge_array($org_data, $user_data);
        $return[] = $org_data;
        $return[] = $parent_id;
        return $return;
    }

    /**
     * 获取当前部门信息
     * @param array $org 部门信息
     * @param int $dep_id 
     * @return array 当前部门信息  一维数组
     */
    private function get_now_dep_info($org, $dep_id) {
        //$dep_where['id'] = array('eq', $dep_id);
        //$this_org_data = $orgstructure_m->where($dep_where)->field('id,parent_id,name as text')->order('myorder desc')->select();
        $data = array();
        foreach ($org as $k => $v) {
            if ($v['id'] == $dep_id) {
                $data = $v;
            }
        }
        return $data;
    }

    /**
     * 获取部门id下面的子部门信息
     * @param array $org 部门信息
     * @param int $dep_id 
     * @return array 当前部门下面的子部门信息 有可能值为空
     */
    private function get_sondep_id($org, $dep_id) {
        $match = ",$dep_id,";
        $son_arr = array();
        foreach ($org as $k => $v) {
            if (strstr($v['path'], $match)) {
                $son_arr[] = $v['id'];
            }
        }
        return $son_arr;
    }

    /**
     * 获取当前的部门下面的部门数据
     * @access private
     * @param array $org 公司组织架构信息
     * @param array $son_id_arr   该部门下面的子部门信息
     * @return array 获取到的子部门的信息
     */
    private function get_son_dep_data($org, $son_id_arr) {
//        $new_where['id'] = array('in', $in_str);
//        $org_data = $orgstructure_m->where($new_where)->field('id,parent_id,name as text')->order('myorder desc')->select();
        $data = array();
        foreach ($org as $k => $v) {
            if (in_array($v['id'], $son_id_arr)) {
                $data[] = $v;
            }
        }
        return $data;
    }

    /**
     * 获取当前主管下面的职员数据
     * @access private
     * @param array $userinfo  公司组织架构信息
     * @param array $id_arr  包含主管在内的部门id
     * @return array 获取到的这个部门下面用户信息
     */
    private function get_dep_userinfo($userinfo, $id_arr) {
        //   获取部门下的职员信息
        //   $map['department_id'] = array('in', $in_str . ",$dep_id");                
        //   $user_data = $user_info_m->where($map)->field('user_id as clerk_id,department_id as parent_id,name as text')->select();
        $data = array();
        foreach ($userinfo as $k => $v) {
            if (in_array($v['parent_id'], $id_arr)) {
                $data[] = $v;
            }
        }
        return $data;
    }

    /**
     * merge_array 
     * 数组合并   使两个数组中的id不重复
     * @access private 
     * @author 赵兴壮 <834916321@qq.com>
     * @return  null 引用传递
     */
    private function merge_array(&$org_data, $user_data) {
        foreach ($user_data as $k => $v) {
            //职员的id 要跟部门的id 区分开来   职员选项点击之后   正则判断id   带着 clerk的符合  表示点击的是 id下面的数据 
            $v['id'] = 'clerk' . $v['clerk_id'];
            $org_data[] = $v;
        }
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

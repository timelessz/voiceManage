<?php

/**
 * FiletraverseController.class.php 
 * 文件遍历操作列表实现
 * @author timelesszhuang <834916321@qq.com>
 * @version voicemanage
 * @copyright 赵兴壮
 * @package  Controller
 * @todo 数据添加到数据库
 * 2014年10月20 16:47
 */

namespace Admin\Controller;

use vendor;
use Think\Controller;

class UpdatedataController extends BaseController {

    /**
     * 更新操作   首页信息
     * @access public
     */
    public function index() {
        $this->display();
    }

    /**
     * 更新数据
     * @access public
     * @author timelesszhuang <834916321@qq.com>
     */
    public function update() {
        $flag = I('post.flag');
        if ($flag == 1) {
            //表示是更新电话分机号码
            $path = C('CURL_PATH');
            $path.='VoicemanageApiGetUserinfo/get_user_info';
            $Param[0] = $path;
            $check_result = R('Admin/Curl/get_user_info', $Param);
        } else if ($flag == 0) {
            //表示是更新的组织架构
            $path = C('CURL_PATH');
            $path.='VoicemanageApiGetUserinfo/get_org_structure';
            $Param[0] = $path;
            $check_result = R('Admin/Curl/get_org_structure', $Param);
        }
        $this->adddata($check_result, $flag);
    }

    /**
     * 序列化的信息存储到数据库中
     * @access public
     * @author timelesszhuang <834916321@qq.com>
     * @param array $flag 标记
     * @param string $flag 描述表示是组织架构
     */
    private function adddata($data, $flag) {
        $m = M('ComData');
        if ($flag == 1) {
            //分机号码信息添加
            //保存在id为1
            $adddata['serialize_data'] = serialize($data);
            $adddata['description'] = '表示分机号码';
            $adddata['updatetime'] = time();
            $where['id'] = array('eq', 1);
            $status = $m->where($where)->save($adddata);
            if ($status) {
                //save 返回更新的记录数量
                //更新成功
                $return['msg'] = '更新分机号码成功。';
                $return['status'] = 'suc';
            } else {
                //更新失败  
                $return['msg'] = '更新分机号码失败。';
                $return['status'] = 'failed';
            }
        } else if ($flag == 0) {
            //组织架构信息添加
            //保存在id为2的地方
            $org_data = $data[0];
            $adddata['serialize_data'] = serialize($org_data);
            $adddata['description'] = '表示公司组织架构。';
            $adddata['updatetime'] = time();
            $where['id'] = array('eq', 2);
            $addorg_status = $m->where($where)->save($adddata);
            //保存在id为3的地方
            $userinfodata = $data[1];
            $adddata = array();
            $adddata['serialize_data'] = serialize($userinfodata);
            $adddata['description'] = '表示用户信息。';
            $adddata['updatetime'] = time();
            $map['id'] = array('eq', 3);
            $adduserinfo_status = $m->where($map)->save($adddata);
            if ($addorg_status && $adduserinfo_status) {
                $return['msg'] = '更新公司组织架构成功。';
                $return['status'] = 'suc';
            } else {
                $return['msg'] = '更新公司组织架构失败，原因未知。';
                $return['status'] = 'failed';
            }
        }
        exit(json_encode($return));
    }
    
}

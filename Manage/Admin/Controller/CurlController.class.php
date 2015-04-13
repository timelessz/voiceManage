<?php

/**
 * CurlController
 * 远程操作 获取用户信息  发送域名信息  远程发送数据   
 * @author 强比科技有限公司 技术部  赵兴壮<834916321@qq.com>
 * @copyright  强比科技有限公司 -技术部
 * @license 强比科技 
 * @version whomx  3.0版本
 * @package  Controller
 * @todo 验证用户登录状态
 * 2014年12月23 17:22
 */

namespace Admin\Controller;

use Think\Controller;

class CurlController extends Controller {

    /**
     * sendcheckLoginData
     * @author timelesszhuang 834916321@qq.com
     * 向salesmen主机发送用户数据   表单填写的数据  验证用户登陆状态   返回数据
     * @param String $url  远程url路径
     * @param String $data  要验证的 数据
     * @return Array  获取到的数据
     * 2014 12.25 11:16
     */
    public function sendcheckLoginData($url, $data) {
        $post_data = array('checkfield' => md5('voicemanage'), 'login_name' => $data['login_name'], 'login_pwd' => $data['login_pwd']);
        return $this->send($url, $post_data);
    }

    /**
     * 获取全部信息
     * @access public
     * @return Array 获取到的组织架构信息 
     * 索引0表示 组织机构信息
     * 索引1表示 用户信息
     * 2015年4月13日
     */
    public function get_org_structure($url) {
        $post_data = array('checkfield' => md5('voicemanage'));
        return$this->send($url, $post_data);
    }

    /**
     * 获取用户信息
     * @access public
     * @return array 数组:分机号码  => 用户user_id
     * 2015年4月13日
     */
    public function get_user_info($url) {
        $post_data = array('checkfield' => md5('voicemanage'));
        return$this->send($url, $post_data);
    }

    /**
     * 发送CURL数据信息
     * @author timelesszhuang 834916321@qq.com
     * curl请求发送
     * @param String  url  发送的路径
     * @param array  $post_data  发送的数据
     * 修改 ：2014 12.27 10:53
     */
    private function send($url, $post_data) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // post数据
        curl_setopt($ch, CURLOPT_POST, 1);
        // post的变量
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $output = curl_exec($ch);
        curl_close($ch);
        //打印获得的数据
        $returndata = json_decode($output, true);
        return $returndata;
    }

}

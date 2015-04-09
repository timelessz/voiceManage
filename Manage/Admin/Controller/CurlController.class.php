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

namespace Home\Controller;

use Think\Controller;

class CurlController extends Controller {

    /**
     * sendcheckLoginData
     * @author timelesszhuang 834916321@qq.com
     * 向salesmen主机发送用户数据   表单填写的数据  验证用户登陆状态   返回数据
     * @param String $url  远程url路径
     * @param String $data  要验证的 数据
     * @return Array 梦想
     * 2014 12.25 11:16
     */
    public function sendcheckLoginData($url, $data) {
        $post_data = array('checkfield' => md5('whomx'), 'login_name' => $data['login_name'], 'login_pwd' => $data['login_pwd']);
        return $this->send($url, $post_data);
    }

    /**
     * sendWhmoxCustomerData
     * @author timelesszhuang 834916321@qq.com
     * 向salesmen主机发送客户的数据
     * @param String $url  远程url路径
     * @param String $data  要验证的 数据
     * 2014 12.25 11:16
     * @return json 返回值说明   status  00请求不正确    10 添加更新数据成功  20添加数据失败 
     */
    public function sendWhomxCustomerData($url, $data) {
        $checkdata = array('checkfield' => md5('whomx'));
        $post_data = array_merge($checkdata, $data);
        return $this->send($url, $post_data);
    }

    /**
     * 发送CURL数据信息
     * @author timelesszhuang 834916321@qq.com
     * curl请求发送
     *  @param String  url  发送的路径
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

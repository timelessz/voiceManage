<?php

/*
 * 公共类其他控制器继承该类   判断登录状态  
 * @author timelesszhuang
 * @version  salesmen
 * @copyright 赵兴壮
 * @package  Controller
 * @todo 验证用户登录状态
 * 2014年8月12 12:00
 */

namespace Admin\Controller;
use Think\Controller;

class BaseController extends Controller {

    /**
     * _initialize()
     * 判断用户登录状态
     * @access public
     * @version salesmen 1.0
     * @todo 登录验证
     */
    public function _initialize() {
        if (!isset($_SESSION['IS_LOGIN'])) {
            //跳转到登登录页面
            redirect(__MODULE__ . "/Login");
        }
    }

    /**
     * formatReturnData() 把数据操纵状态json形式传送到后台
     * @access protected
     * @author timelesszhuang<834916321@qq.com>
     * @param string $info 数据操纵结果信息
     * @param string $title 提示信息的时候标题
     * @param string $isclose 右下角显示的窗体是否关闭   c 表示关闭  o 表示不关闭 
     * @param boolen $status 数据操纵状态  默认成功
     * @version salesmen 1.0
     * @todo 返回数据到前台
     */
    protected function formatReturnData($info, $title = "提示信息", $isclose = 'c', $status = 'suc') {
        $data['info'] = $info;
        $data['title'] = $title;
        $data['isclose'] = $isclose;
        $data['status'] = $status;
        echo json_encode($data);
    }

    /**
     * checktelnum() 验证座机号码格式
     * @access protected
     * @author timelesszhuang<834916321@qq.com>
     * @param 
     * @version salesmen 1.0
     * @todo  返回数据到前台
     */
    protected function check_telnum($telnum) {
        
    }

    /**
     * checktelnum() 验证手机号码格式
     * @access protected
     * @author timelesszhuang<834916321@qq.com>
     * @param 
     * @version salesmen 1.0
     * @todo  返回数据到前台
     */
    protected function check_mobilenum($mobilennum) {
        
    }

    /**
     * checktelnum() 验证手机号码格式
     * @access protected
     * @author timelesszhuang<834916321@qq.com>
     * @param 
     * @version salesmen 1.0
     * @todo  返回数据到前台
     */
    protected function check_email($email) {
        
    }

    /**
     * checktelnum() 验证手机号码格式
     * @access protected
     * @author timelesszhuang<834916321@qq.com>
     * @param 
     * @version salesmen 1.0
     * @todo  返回数据到前台
     */
    protected function check_qqnum($qqnum) {
        
    }

    /**
     * 获取当前的ip地址
     */
    public function get_client_ip() {
        if ($_SERVER['REMOTE_ADDR']) {
            $cip = $_SERVER['REMOTE_ADDR'];
        } elseif (getenv("REMOTE_ADDR")) {
            $cip = getenv("REMOTE_ADDR");
        } elseif (getenv("HTTP_CLIENT_IP")) {
            $cip = getenv("HTTP_CLIENT_IP");
        } else {
            $cip = "unknown";
        }
        return $cip;
    }

    /**
     * 获取邮件帐号
     */
    public function getemailname() {
        $user_id = session('USER_ID');
        $condition['user_id'] = array('eq', $user_id);
        $m = M('UserAccount');
        $email = $m->where($condition)->getField('emailaccount');
        return $email;
    }

}

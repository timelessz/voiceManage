<?php

namespace Admin\Controller;

use Think\Controller;

/**
 * IndexController.class.php
 * @author timelesszhuang
 * @version salesmen 1.0
 * @copyright 赵兴壮
 * @package  Controller
 * @todo IndexController判断用户登录状态之后进入主界面
 * 2015年4月9 12:25
 */
class IndexController extends BaseController {

    /**
     * 跳转到首页    获取菜单信息
     * 因为使用人数限制所以不必要使用函数的方式来判断用户
     * @access public
     */
    public function index() {
        $menu = array();
        if (session('BOSS')) {
            require_once(APP_PATH . "Admin/Conf/leftmenu_BOSS.php"); //引入默认菜单 ;
            $menu = array_merge($menu, $menuarray_BOSS);
        } else if (session('DEPARTMENT_ID')) {
            require_once(APP_PATH . "Admin/Conf/leftmenu_DEPARTMENTMANAGE.php"); //引入默认菜单 ;
            $menu = array_merge($menu, $menuarray_DEPARTMENTMANAGE);
        }
        require_once(APP_PATH . "Admin/Conf/leftmenu.php"); //引入默认菜单 ;
        $menu = array_merge($menu, $menuarray);
        $this->assign('menu', $menu);
        $this->display();
    }

}

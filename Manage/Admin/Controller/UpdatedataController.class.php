<?php

/**
 * FiletraverseController.class.php 
 * 文件遍历操作列表实现
 * @author timelesszhuang
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
            
            
        } else if ($flag == 0) {
            //表示是更新的组织架构
            
            
        }
    }

}

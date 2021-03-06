<?php

/**
 * FiletraverseController.class.php 
 * 文件遍历操作列表实现
 * @author timelesszhuang<834916321@qq.com>
 * @version voicemanage
 * @copyright 赵兴壮
 * @package  Controller
 * @todo 数据添加到数据库
 * 2014年10月20 16:47
 */

namespace Admin\Controller;

use vendor;
use Think\Controller;

class FiletraverseController extends BaseController {

    /**
     * 获取今天的数据
     */
    public function getAllData() {
        $this->deleteAllData();
        ini_set("max_execution_time", 7200); // s 60 分钟 
        //获取配置文件中的信息
        $path = C('FILE_PATH');
        $data = array();
        $this->traverse($path, $data);
        $returndata[] = $this->formatData($data);
        $checkResult = R('Admin/Datamanage/addvoicedata', $returndata);
        $adddata['status'] = 'suc';
        $adddata['msg'] = '更新全部数据成功。';
        exit(json_encode($adddata));
    }

    /**
     * 遍历今日数据
     */
    private function traverse($path, &$data) {
        if (!is_dir($path)) {
            return false;
        }
        $current_dir = opendir($path);    //opendir()返回一个目录句柄,失败返回false
        while (($file = readdir($current_dir)) !== false) {    //readdir()返回打开目录句柄中的一个条目
            $sub_dir = $path . DIRECTORY_SEPARATOR . $file;    //构建子目录路径
            // $sub_dir = $path . DIRECTORY_SEPARATOR . $file;    //构建子目录路径F
            if ($file == '.' || $file == '..') {
                continue;
            } else if (is_dir($sub_dir)) {    //如果是目录,进行递归
                $this->traverse($sub_dir, $data);
            } else {
                //如果是文件,直接输出
                #因为 path不支持绝对路径  所以改为绝对路径
                #把 path改成 192.168.1.150 下的路径就可
                //这个地方还要判断文件的后缀是不是.mp3的
                $data[] = $path . DIRECTORY_SEPARATOR . $file;
                // $data[] = $path ."/" . $file;
            }
        }
    }

    /**
     * 删除全部信息然后全部遍历
     */
    private function deleteAllData() {
        $m = M('UserVoice');
        $status = $m->where('time > 0')->delete();
        if (!$status) {
            return false;
        }
        return TRUE;
    }

    /**
     * 截取路径 为 192.168.1.150/pbxrecord
     */
    private function subpath($string) {
        $string = substr($string, 9);
        return C('URL_PATH') . $string;
    }

    /**
     * 格式化字符串
     */
    private function formatData($data) {
        #数据格式化
        $returndata = $this->formInsertData($data);
        return $returndata;
    }

    /**
     * 截取字符串
     */
    private function formInsertData($data) {
        #数组分机号=>用户id
        $userInfo = $this->getuserinfo();
        $voicedata = array();
        foreach ($data as $k => $v) {
            $path_parts = pathinfo($v);
            $filename = $path_parts["basename"];
            $string = explode('_', $filename);
            if (strlen($string[0]) > strlen($string[1])) {
                $extension = $string[1];
                $voicedata[$k][1] = $this->subtel($string[0]);
                $voicedata[$k][2] = $this->formatTimeString($string[2]);
                #20表示是打进来的电话
                $voicedata[$k][3] = '20';
            } else {
                $extension = $string[0];
                $voicedata[$k][1] = $this->subtel($string[1]);
                $voicedata[$k][2] = $this->formatTimeString($string[2]);
                #表示打出的电话
                $voicedata[$k][3] = '10';
            }
            $voicedata[$k][4] = $v;
            $voicedata[$k][0] = empty($userInfo[$extension]) ? 0 : $userInfo[$extension];
            $voicedata[$k][5] = $this->mp3_len($v);
            $voicedata[$k][6] = $this->subpath($v);
        }
        return $voicedata;
    }

    /**
     * 截取电话号码  018763139626 为 18********    05388898056为 0538-8898056
     */
    private function subtel($tel) {
        $start = substr($tel, 1, 1);
        if (strlen($tel) == 12 && $start == '1') {
            $tel = substr($tel, 1);
        } else {
            $quhao = substr($tel, 0, 4);
            $tel = $quhao . '-' . substr($tel, 4);
        }
        return $tel;
    }

    /**
     * 格式化电话的时间信息
     */
    private function formatTimeString($time) {
        $year = substr($time, 0, 4);
        $month = substr($time, 4, 2);
        $day = substr($time, 6, 2);
        $hour = substr($time, 9, 2);
        $minute = substr($time, 11, 2);
        $second = substr($time, 13, 2);
        $date = $year . '-' . $month . '-' . $day . ' ' . $hour . ':' . $minute . ':' . $second;
        return strtotime($date);
    }

    /**
     * 获取用户分机号 分机号码=>user_id
     * 这个地方要改成geyuserinfo
     */
    private function getuserinfo() {
        $map['id'] = array('eq', 1);
        $m = M('ComData');
        $userinfo_data = $m->where($map)->getField('serialize_data');
        $userinfo = unserialize($userinfo_data);
        return $userinfo;
//        $m = M('UserAccount');
//        $data = $m->getField('user_id,telnum', true);
//        $info = array();
//        foreach ($data as $key => $value) {
//            $arr = explode('-', $value);
//            $info[$arr[2]] = $key;
//        }
//        print_r($info);
//        return $info;
    }

    /**
     * 获取mp3文件的时间长度
     */
    private function mp3_len($file) {
        $m = new \Org\MP3TIMECLASS\mp3file($file);
        $a = $m->get_metadata();
        $len = $a['Length'] ? $a['Length'] : 0;
        return $len;
    }

    /**
     * 更新今天的录音数据
     */
    public function getTodayData() {
        $deletestatus = $this->deleteTodayData();
        if (!$deletestatus) {
            #删除今日数据失败。 没有文件或者是删除失败
        }
        #今日文件夹名称
        $dir = date('Ymd', time());
        ini_set("max_execution_time", 7200); // s 60 分钟 
        $path = C('FILE_PATH') . DIRECTORY_SEPARATOR . $dir;
        $data = array();
        $this->traverse($path, $data);
        $returndata[] = $this->formatData($data);
        $checkResult = R('Admin/Datamanage/addvoicedata', $returndata);
        $adddata['status'] = 'suc';
        $adddata['msg'] = '更新今日数据成功。';
        exit(json_encode($adddata));
    }

    /**
     * 删除今天拨打的信息
     */
    private function deleteTodayData() {
        $m = M('UserVoice');
        $starttime = strtotime(date('Y-m-d 00:00:00', time()));
        $stoptime = strtotime(date('Y-m-d 23:59:59', time()));
        $condition = 'time< ' . $stoptime . ' and time > ' . $starttime;
        //$map['time']=array('gt',$starttime);
        $status = $m->where($condition)->delete();
        //这个地方可以把udp的文件删除掉  
        $where['path'] = array('like', '%udp');
        $m->where($where)->delete();
        if (!$status) {
            return true;
        }
        return false;
    }

}

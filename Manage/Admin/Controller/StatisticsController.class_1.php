<?php

namespace Admin\Controller;

use Think\Controller;

/**
 * StatisticsController.class.php      
 * @author timelesszhuang
 * @version voicemanage 1.0
 * @copyright 赵兴壮
 * @package  Controller
 * @todo 数据统计   这个类库由于修改次数太多   需要代码整理    冗余的代码比较多    ，比如 
 * 
 * 根据 用户的id 获取天周月的数据可以提出来统一获取
 * 还可以根据现在
 * 
 * 2014年8月12 12:25
 */
class StatisticsController extends BaseController {

    /**
     * 按天统计   需要根据主管部门来统计统计主管
     */
    public function day_count() {
        $data = $this->getuserdata('1');
        $data = $this->formatTable($data);
        $this->assign('data', $data);
        $this->display();
    }

    /**
     * 获取用户信息
     * @param string $type 分类信息
     * @return array  获取到的部门下面的数据 数量
     */
    private function getuserdata($type) {
        if (session('BOSS')) {
//获取部门数据
            $m = M('Department');
            $departmentdata = $m->where($condition)->getField('id,name');
            $data = array();
            foreach ($departmentdata as $key => $value) {
#每一个部门下面的用户
                $perdepartdata = array();
                $userdata = $this->getdata_bydepartmentid($key);
                if (!empty($userdata)) {
#部门下面的每一个职员的数据
                    foreach ($userdata as $k => $v) {
                        $peruserdata = array();
#用户名称
                        $peruserdata[] = $v;
                        if ($type == '1') {
                            $countarr = $this->getDayInAndOutPhoneCount($k);
                            $sizegt50 = $this->getDayOver50Count($k);
                            $sizegt30 = $this->getDayOver30Count($k);
//获取一天的电话的时间长度
                            $counttime = $this->getDayInAndOutPhoneTime($k);
                            $sizegt30time = $this->getDayOver30Time($k);
                            $sizegt50time = $this->getDayOver50Time($k);
                        } else if ($type == '2') {
                            $countarr = $this->getWeekInAndOutPhoneCount($k);
                            $sizegt50 = $this->getWeekOver50Count($k);
                            $sizegt30 = $this->getWeekOver30Count($k);
//获取一周电话的时间长度
                            $counttime = $this->getWeekInAndOutPhoneTime($k);
                            $sizegt30time = $this->getWeekOver30Time($k);
                            $sizegt50time = $this->getWeekOver50Time($k);
                        } else {
                            $countarr = $this->getMonthInAndOutPhoneCount($k);
                            $sizegt50 = $this->getMonthOver50Count($k);
                            $sizegt30 = $this->getMonthOver30Count($k);
//获取一个月电话的时间长度
                            $counttime = $this->getMonthInAndOutPhoneTime($k);
                            $sizegt30time = $this->getMonthOver30Time($k);
                            $sizegt50time = $this->getMonthOver50Time($k);
                        }
#0  打出   1打入
                        $peruserdata[] = $countarr[0];
                        $peruserdata[] = $countarr[1];
#0  打出   1打入
                        $peruserdata[] = $sizegt50[0];
                        $peruserdata[] = $sizegt50[1];
#0  打出   1打入
                        $peruserdata[] = $sizegt30[0];
                        $peruserdata[] = $sizegt30[1];
#0  打出   1打入  统计总的时间总的长度
                        $peruserdata[] = $counttime[0];
                        $peruserdata[] = $counttime[1];
#0  打出   1打入  统计大于50秒的时间总的长度
                        $peruserdata[] = $sizegt50time[0];
                        $peruserdata[] = $sizegt50time[1];
#0  打出   1打入 统计大于30秒的时间总的长度
                        $peruserdata[] = $sizegt30time[0];
                        $peruserdata[] = $sizegt30time[1];
#获取用户的 统计数据 比如用户打入电话 用户打出电话 电话 50秒的数量 
                        $perdepartdata[$k] = $peruserdata;
                        $peruserdata = array();
                    }
                }
                $arr[$key] = $perdepartdata;
                $arr['dep_name'] = $value;
                $perdepartdata = array();
                $data[] = $arr;
                $arr = array();
            }
#其他部门信息
            $department_id = 0;
            $otheruserdata = $this->getdata_bydepartmentid($department_id);
            if (!empty($otheruserdata)) {
                foreach ($otheruserdata as $k => $value) {
                    $peruserdata = array();
#用户名称
                    $peruserdata[] = $value;
                    if ($type == '1') {
                        $countarr = $this->getDayInAndOutPhoneCount($k);
                        $sizegt50 = $this->getDayOver50Count($k);
                        $sizegt30 = $this->getDayOver30Count($k);
//获取一天的电话的时间长度
                        $counttime = $this->getDayInAndOutPhoneTime($k);
                        $sizegt30time = $this->getDayOver30Time($k);
                        $sizegt50time = $this->getDayOver50Time($k);
                    } else if ($type == '2') {
                        $countarr = $this->getWeekInAndOutPhoneCount($k);
                        $sizegt50 = $this->getWeekOver50Count($k);
                        $sizegt30 = $this->getWeekOver30Count($k);
//获取一周电话的时间长度
                        $counttime = $this->getWeekInAndOutPhoneTime($k);
                        $sizegt30time = $this->getWeekOver30Time($k);
                        $sizegt50time = $this->getWeekOver50Time($k);
                    } else {
                        $countarr = $this->getMonthInAndOutPhoneCount($k);
                        $sizegt50 = $this->getMonthOver50Count($k);
                        $sizegt30 = $this->getMonthOver30Count($k);
                        $counttime = $this->getMonthInAndOutPhoneTime($k);
                        $sizegt30time = $this->getMonthOver30Time($k);
                        $sizegt50time = $this->getMonthOver50Time($k);
                    }
#0  打出   1打入
                    $peruserdata[] = $countarr[0];
                    $peruserdata[] = $countarr[1];
#0  打出   1打入
                    $peruserdata[] = $sizegt50[0];
                    $peruserdata[] = $sizegt50[1];
#0  打出   1打入
                    $peruserdata[] = $sizegt30[0];
                    $peruserdata[] = $sizegt30[1];
#0  打出   1打入  统计总的时间总的长度
                    $peruserdata[] = $counttime[0];
                    $peruserdata[] = $counttime[1];
#0  打出   1打入  统计大于50秒的时间总的长度
                    $peruserdata[] = $sizegt50time[0];
                    $peruserdata[] = $sizegt50time[1];
#0  打出   1打入 统计大于30秒的时间总的长度
                    $peruserdata[] = $sizegt30time[0];
                    $peruserdata[] = $sizegt30time[1];
#获取用户的 统计数据 比如用户打入电话 用户打出电话 电话 50秒的数量 
                    $perdepartdata[$k] = $peruserdata;
                    $peruserdata = array();
                }
                $arr[0] = $perdepartdata;
                $arr['dep_name'] = "其他部门";
                $perdepartdata = array();
                $data[] = $arr;
                $arr = array();
            }
        } else if (session('DEPARTMENT_ID')) {
            $department_id = session('DEPARTMENT_ID');
            $userdata = $this->getdata_bydepartmentid($department_id);
            $data = array();
            $perdepartdata = array();
//要判断是不是该分组为空值
            if (!empty($userdata)) {
                foreach ($userdata as $k => $v) {
#用户名称
                    $peruserdata[] = $v;
                    if ($type == '1') {
                        $countarr = $this->getDayInAndOutPhoneCount($k);
                        $sizegt50 = $this->getDayOver50Count($k);
                        $sizegt30 = $this->getDayOver30Count($k);
//获取一天的电话的时间长度
                        $counttime = $this->getDayInAndOutPhoneTime($k);
                        $sizegt30time = $this->getDayOver30Time($k);
                        $sizegt50time = $this->getDayOver50Time($k);
                    } else if ($type == '2') {
                        $countarr = $this->getWeekInAndOutPhoneCount($k);
                        $sizegt50 = $this->getWeekOver50Count($k);
                        $sizegt30 = $this->getWeekOver30Count($k);
//获取一周电话的时间长度
                        $counttime = $this->getWeekInAndOutPhoneTime($k);
                        $sizegt30time = $this->getWeekOver30Time($k);
                        $sizegt50time = $this->getWeekOver50Time($k);
                    } else {
                        $countarr = $this->getMonthInAndOutPhoneCount($k);
                        $sizegt50 = $this->getMonthOver50Count($k);
                        $sizegt30 = $this->getMonthOver30Count($k);
                        $counttime = $this->getMonthInAndOutPhoneTime($k);
                        $sizegt30time = $this->getMonthOver30Time($k);
                        $sizegt50time = $this->getMonthOver50Time($k);
                    }
#0  打出   1打入
                    $peruserdata[] = $countarr[0];
                    $peruserdata[] = $countarr[1];
#0  打出   1打入
                    $peruserdata[] = $sizegt50[0];
                    $peruserdata[] = $sizegt50[1];
#0  打出   1打入
                    $peruserdata[] = $sizegt30[0];
                    $peruserdata[] = $sizegt30[1];
#0  打出   1打入  统计总的时间总的长度
                    $peruserdata[] = $counttime[0];
                    $peruserdata[] = $counttime[1];
#0  打出   1打入  统计大于50秒的时间总的长度
                    $peruserdata[] = $sizegt50time[0];
                    $peruserdata[] = $sizegt50time[1];
#0  打出   1打入 统计大于30秒的时间总的长度
                    $peruserdata[] = $sizegt30time[0];
                    $peruserdata[] = $sizegt30time[1];
                    $perdepartdata[$k] = $peruserdata;
                    $peruserdata = array();
                }
            }
            $arr[0] = $perdepartdata;
            $arr['dep_name'] = '职员';
            $perdepartdata = array();
            $data[] = $arr;
            $arr = array();
        }
        return $data;
    }

    /**
     * 格式化字符串数据   按周按日查询  按天查询实现
     * $data 中的 一维数组表示每一个部门下面的数据，表示哪一个部门的信息     二维数组下面的信息表示哪一个人的信息    
     * 
     * 每一个索引代表
     * 0  职员姓名  
     * 电话数量
     *  打出数  1总数    3 30秒     5  50秒的   
     *  打入数  2 总数 6 50秒  4 30秒的       
     * 电话时长   
     * 打出 7总打出时间  9 50秒时间   11 30秒时间
     * 打入 8总打入时间  10 50秒时间  12 30秒时间
     */
    private function formatTable($data) {
        $string = '';
        foreach ($data as $key => $value) {
            //部门下的数据
            $arr = array_values($value);
            $depart_name = $arr[1];
            $perarr = $arr[0];
            $string .= "<table class='gridtable' style='margin:0 auto;'>"
                    . "<tr><td colspan='13'>部门：$depart_name</td></tr>"
                    . "<tr><th rowspan='2'>姓名</th><th colspan='6'>打出电话</th><th colspan='6'>打入电话</th></tr>"
                    . "<tr><th>总数</th><th>30秒以上</th><th>50秒以上</th><th>总时长</th><th>30秒以上时长</th><th>50秒以上时长</th><th>总数</th><th>30秒以上</th><th>50秒以上</th><th>总时长</th><th>30秒以上时长</th><th>50秒以上时长</th></tr>";
            foreach ($perarr as $k => $v) {
                $string.="<tr><td>$v[0]</td>   <td>$v[1]</td><td>$v[5]</td><td>$v[3]</td>   <td>$v[7]</td><td>$v[11]</td><td>$v[9]</td>        <td>$v[2]</td><td>$v[6]</td><td>$v[4]</td>    <td>$v[8]</td><td>$v[12]</td><td>$v[10]</td></tr>";
            }
            $string.= "</table><br><br>";
        }
        return $string;
    }

    /**
     * 根据传入的数组   循环计算出数据大小
     * @param Array $arr 求和  数据形式为  array(0=>时间长度，1=>时间长度);
     * @return int 计算出来的和
     */
    private function summaryTime($arr) {
        $count = 0;
        if (count($arr) > 0) {
            foreach ($arr as $key => $value) {
                $count = $count + $value;
            }
            return $count;
        } else {
            return 0;
        }
    }

    /**
     * 根据 user_id获取每一天的打入打出数据统 
     */
    private function getDayInAndOutPhoneCount($id) {
#显示的是今日的统计数据   需要先更新一下今日的数据
        $m = M('UserVoice');
        $starttime = strtotime(date('Y-m-d 00:00:00', time()));
        $stoptime = strtotime(date('Y-m-d 23:59:59', time()));
        $condition = "user_id = $id and time > $starttime and time < $stoptime";
        $outcondition = $condition . " and type = '10'";
        $incondition = $condition . " and type = '20'";
        $out = $m->where($outcondition)->count();
        $in = $m->where($incondition)->count();
#打入的   打出的电话数量
        return array(0 => $out, 1 => $in);
    }

    /**
     * 根据 user_id获取每一天的打入打出的时间长度
     * @param int $id  要查询的用户的id
     * @return Array 数组元素0表示打出的 1表示打入的  电话的时间总长度
     */
    private function getDayInAndOutPhoneTime($id) {
#显示的是今日的统计数据   需要先更新一下今日的数据
        $m = M('UserVoice');
        $starttime = strtotime(date('Y-m-d 00:00:00', time()));
        $stoptime = strtotime(date('Y-m-d 23:59:59', time()));
        $condition = "user_id = $id and time > $starttime and time < $stoptime";
        $outcondition = $condition . " and type = '10'";
        $incondition = $condition . " and type = '20'";
        $out = $m->where($outcondition)->getField('size', TRUE);
        $outtime = $this->summaryTime($out);
        $in = $m->where($incondition)->getField('size', TRUE);
        $intime = $this->summaryTime($in);
#打入的   打出的电话数量
        return array(0 => $outtime, 1 => $intime);
    }

    /**
     * 获取时间超过
     * @param int  $id 要查询的用户的id
     * @return Array 0表示打出的 1表示打入的   电话数量
     */
    private function getDayOver50Count($id) {
#显示的是今日的统计数据   需要先更新一下今日的数据
        $m = M('UserVoice');
        $starttime = strtotime(date('Y-m-d 00:00:00', time()));
        $stoptime = strtotime(date('Y-m-d 23:59:59', time()));
        $condition = "user_id = $id and time > $starttime and time < $stoptime";
        $outcondition = $condition . " and type = '10' and size > 50";
        $incondition = $condition . " and type = '20' and size > 50";
        $out = $m->where($outcondition)->count();
        $in = $m->where($incondition)->count();
#打入的   打出的电话数量
        return array(0 => $out, 1 => $in);
    }

    /**
     * 根据 user_id获取每一天的打入打出的时间长度
     * @param int $id  要查询的用户的id
     * @return Array 数组元素0表示打出的 1表示打入的  电话的时间总长度
     */
    private function getDayOver50Time($id) {
#显示的是今日的统计数据   需要先更新一下今日的数据
        $m = M('UserVoice');
        $starttime = strtotime(date('Y-m-d 00:00:00', time()));
        $stoptime = strtotime(date('Y-m-d 23:59:59', time()));
        $condition = "user_id = $id and time > $starttime and time < $stoptime";
        $outcondition = $condition . " and type = '10' and size > 50";
        $incondition = $condition . " and type = '20' and size > 50";
        $out = $m->where($outcondition)->getField('size', TRUE);
        $outtime = $this->summaryTime($out);
        $in = $m->where($incondition)->getField('size', TRUE);
        $intime = $this->summaryTime($in);
#打入的   打出的电话数量
        return array(0 => $outtime, 1 => $intime);
    }

    /**
     * 获取时间超过30秒的个数
     */
    private function getDayOver30Count($id) {
#显示的是今日的统计数据   需要先更新一下今日的数据
        $m = M('UserVoice');
        $starttime = strtotime(date('Y-m-d 00:00:00', time()));
        $stoptime = strtotime(date('Y-m-d 23:59:59', time()));
        $condition = "user_id = $id and time > $starttime and time < $stoptime";
        $outcondition = $condition . " and type = '10' and size > 30";
        $incondition = $condition . " and type = '20' and size > 30";
        $out = $m->where($outcondition)->count();
        $in = $m->where($incondition)->count();
#打入的   打出的电话数量
        return array(0 => $out, 1 => $in);
    }

    /**
     * 根据 user_id获取每一天的打入打出的每个长度大于30秒的时间长度
     * @param int $id  要查询的用户的id
     * @return Array 数组元素0表示打出的 1表示打入的  电话的时间总长度
     */
    private function getDayOver30Time($id) {
#显示的是今日的统计数据   需要先更新一下今日的数据
        $m = M('UserVoice');
        $starttime = strtotime(date('Y-m-d 00:00:00', time()));
        $stoptime = strtotime(date('Y-m-d 23:59:59', time()));
        $condition = "user_id = $id and time > $starttime and time < $stoptime";
        $outcondition = $condition . " and type = '10' and size > 30";
        $incondition = $condition . " and type = '20' and size > 30";
        $out = $m->where($outcondition)->getField('size', TRUE);
        $outtime = $this->summaryTime($out);
        $in = $m->where($incondition)->getField('size', TRUE);
        $intime = $this->summaryTime($in);
#打入的   打出的电话数量
        return array(0 => $outtime, 1 => $intime);
    }

###############################################################按周统计##############################################################

    /**
     * 按周统计
     */
    public function weekCount() {
#周一的时间戳 
#echo mktime(0, 0, 0, date("m"), date("d") - date("w") + 1, date("Y"));
#周末的时间戳 
#echo mktime(23, 59, 59, date("m"), date("d") - date("w") + 7, date("Y"));
        $data = $this->getuserdata('2');
        $data = $this->formatTable($data);
        $this->assign('data', $data);
        $this->display();
    }

    /**
     * 根据 user_id获取每一周的打入打出数据 
     */
    private function getWeekInAndOutPhoneCount($id) {
#显示的是今日的统计数据   需要先更新一下今日的数据
        $m = M('UserVoice');
        $starttime = mktime(0, 0, 0, date("m"), date("d") - date("w") + 1, date("Y"));
        $stoptime = mktime(23, 59, 59, date("m"), date("d") - date("w") + 7, date("Y"));
        $condition = "user_id = $id and time > $starttime and time < $stoptime";
        $outcondition = $condition . " and type = '10'";
        $incondition = $condition . " and type = '20'";
        $out = $m->where($outcondition)->count();
        $in = $m->where($incondition)->count();
#打入的   打出的电话数量
        return array(0 => $out, 1 => $in);
    }

    /**
     * 根据 user_id获取一周打出电话的总时长
     */
    private function getWeekInAndOutPhoneTime($id) {
#显示的是今日的统计数据   需要先更新一下今日的数据
        $m = M('UserVoice');
        $starttime = mktime(0, 0, 0, date("m"), date("d") - date("w") + 1, date("Y"));
        $stoptime = mktime(23, 59, 59, date("m"), date("d") - date("w") + 7, date("Y"));
        $condition = "user_id = $id and time > $starttime and time < $stoptime";
        $outcondition = $condition . " and type = '10'";
        $incondition = $condition . " and type = '20'";
        $out = $m->where($outcondition)->getField('size', TRUE);
        $outtime = $this->summaryTime($out);
        $in = $m->where($incondition)->getField('size', TRUE);
        $intime = $this->summaryTime($in);
#打入的   打出的电话数量
        return array(0 => $outtime, 1 => $intime);
    }

    /**
     * 获取时间超过
     */
    private function getWeekOver50Count($id) {
#显示的是今日的统计数据   需要先更新一下今日的数据
        $m = M('UserVoice');
        $starttime = mktime(0, 0, 0, date("m"), date("d") - date("w") + 1, date("Y"));
        $stoptime = mktime(23, 59, 59, date("m"), date("d") - date("w") + 7, date("Y"));
        $condition = "user_id = $id and time > $starttime and time < $stoptime";
        $outcondition = $condition . " and type = '10' and size > 50";
        $incondition = $condition . " and type = '20' and size > 50";
        $out = $m->where($outcondition)->count();
        $in = $m->where($incondition)->count();
#打入的   打出的电话数量
        return array(0 => $out, 1 => $in);
    }

    /**
     * 根据 user_id获取一周的打入打出的时间长度
     * @param int $id  要查询的用户的id
     * @return Array 数组元素0表示打出的 1表示打入的  电话的时间总长度
     */
    private function getWeekOver50Time($id) {
#显示的是今日的统计数据   需要先更新一下今日的数据
        $m = M('UserVoice');
        $starttime = mktime(0, 0, 0, date("m"), date("d") - date("w") + 1, date("Y"));
        $stoptime = mktime(23, 59, 59, date("m"), date("d") - date("w") + 7, date("Y"));
        $condition = "user_id = $id and time > $starttime and time < $stoptime";
        $outcondition = $condition . " and type = '10' and size > 50";
        $incondition = $condition . " and type = '20' and size > 50";
        $out = $m->where($outcondition)->getField('size', TRUE);
        $outtime = $this->summaryTime($out);
        $in = $m->where($incondition)->getField('size', TRUE);
        $intime = $this->summaryTime($in);
#打入的   打出的电话数量
        return array(0 => $outtime, 1 => $intime);
    }

    /**
     * 获取时间超过
     */
    private function getWeekOver30Count($id) {
#显示的是今日的统计数据   需要先更新一下今日的数据
        $m = M('UserVoice');
        $starttime = mktime(0, 0, 0, date("m"), date("d") - date("w") + 1, date("Y"));
        $stoptime = mktime(23, 59, 59, date("m"), date("d") - date("w") + 7, date("Y"));
        $condition = "user_id = $id and time > $starttime and time < $stoptime";
        $outcondition = $condition . " and type = '10' and size > 30";
        $incondition = $condition . " and type = '20' and size > 30";
        $out = $m->where($outcondition)->count();
        $in = $m->where($incondition)->count();
#打入的   打出的电话数量
        return array(0 => $out, 1 => $in);
    }

    /**
     * 根据 user_id获取每一天的打入打出的时间长度
     * @param int $id  要查询的用户的id
     * @return Array 数组元素0表示打出的 1表示打入的  电话的时间总长度
     */
    private function getWeekOver30Time($id) {
#显示的是今日的统计数据   需要先更新一下今日的数据
        $m = M('UserVoice');
        $starttime = mktime(0, 0, 0, date("m"), date("d") - date("w") + 1, date("Y"));
        $stoptime = mktime(23, 59, 59, date("m"), date("d") - date("w") + 7, date("Y"));
        $condition = "user_id = $id and time > $starttime and time < $stoptime";
        $outcondition = $condition . " and type = '10' and size > 30";
        $incondition = $condition . " and type = '20' and size > 30";
        $out = $m->where($outcondition)->getField('size', TRUE);
        $outtime = $this->summaryTime($out);
        $in = $m->where($incondition)->getField('size', TRUE);
        $intime = $this->summaryTime($in);
#打入的   打出的电话数量
        return array(0 => $outtime, 1 => $intime);
    }

###############################################################按月统计##############################################################
    /**
     * 按月统计
     */

    public function monthCount() {
#周一的时间戳 
#echo mktime(0, 0, 0, date("m"), date("d") - date("w") + 1, date("Y"));
#周末的时间戳 
#echo mktime(23, 59, 59, date("m"), date("d") - date("w") + 7, date("Y"));
        $data = $this->getuserdata('3');
        $data = $this->formatTable($data);
        $this->assign('data', $data);
        $this->display();
    }

    /**
     * 根据 user_id获取的打入打出数据统 
     */
    private function getMonthInAndOutPhoneCount($id) {
#显示的是今日的统计数据   需要先更新一下今日的数据
        $m = M('UserVoice');
#echo mktime(0, 0 , 0,date("m"),1,date("Y")); 
#echo mktime(23,59,59,date("m"),date("t"),date("Y")); 

        $starttime = mktime(0, 0, 0, date("m"), 1, date("Y"));
        $stoptime = mktime(23, 59, 59, date("m"), date("d") - date("w") + 7, date("Y"));
        $condition = "user_id = $id and time > $starttime and time < $stoptime";
        $outcondition = $condition . " and type = '10'";
        $incondition = $condition . " and type = '20'";
        $out = $m->where($outcondition)->count();
        $in = $m->where($incondition)->count();
#打入的   打出的电话数量
        return array(0 => $out, 1 => $in);
    }

    /**
     * 根据 user_id获取一个月的打入打出的时间长度
     * @param int $id  要查询的用户的id
     * @return Array 数组元素0表示打出的 1表示打入的  电话的时间总长度
     */
    private function getMonthInAndOutPhoneTime($id) {
#显示的是今日的统计数据   需要先更新一下今日的数据
        $m = M('UserVoice');
        $starttime = mktime(0, 0, 0, date("m"), 1, date("Y"));
        $stoptime = mktime(23, 59, 59, date("m"), date("d") - date("w") + 7, date("Y"));
        $condition = "user_id = $id and time > $starttime and time < $stoptime";
        $outcondition = $condition . " and type = '10'";
        $incondition = $condition . " and type = '20'";
        $out = $m->where($outcondition)->getField('size', TRUE);
        $outtime = $this->summaryTime($out);
        $in = $m->where($incondition)->getField('size', TRUE);
        $intime = $this->summaryTime($in);
#打入的   打出的电话数量
        return array(0 => $outtime, 1 => $intime);
    }

    /**
     * 获取时间超过
     */
    private function getMonthOver50Count($id) {
#显示的是今日的统计数据   需要先更新一下今日的数据
        $m = M('UserVoice');
        $starttime = mktime(0, 0, 0, date("m"), 1, date("Y"));
        $stoptime = mktime(23, 59, 59, date("m"), date("t"), date("Y"));
        $condition = "user_id = $id and time > $starttime and time < $stoptime";
        $outcondition = $condition . " and type = '10' and size > 50";
        $incondition = $condition . " and type = '20' and size > 50";
        $out = $m->where($outcondition)->count();
        $in = $m->where($incondition)->count();
#打入的   打出的电话数量
        return array(0 => $out, 1 => $in);
    }

    /**
     * 获取时间超过50秒的 时间长度
     */
    private function getMonthOver50Time($id) {
#显示的是今日的统计数据   需要先更新一下今日的数据
        $m = M('UserVoice');
        $starttime = mktime(0, 0, 0, date("m"), 1, date("Y"));
        $stoptime = mktime(23, 59, 59, date("m"), date("t"), date("Y"));
        $condition = "user_id = $id and time > $starttime and time < $stoptime";
        $outcondition = $condition . " and type = '10' and size > 50";
        $incondition = $condition . " and type = '20' and size > 50";
        $out = $m->where($outcondition)->getField('size', TRUE);
        $outtime = $this->summaryTime($out);
        $in = $m->where($incondition)->getField('size', TRUE);
        $intime = $this->summaryTime($in);
#打入的   打出的电话数量
        return array(0 => $outtime, 1 => $intime);
    }

    /**
     * 获取时间超过30秒的
     */
    private function getMonthOver30Count($id) {
#显示的是今日的统计数据   需要先更新一下今日的数据
        $m = M('UserVoice');
        $starttime = mktime(0, 0, 0, date("m"), 1, date("Y"));
        $stoptime = mktime(23, 59, 59, date("m"), date("t"), date("Y"));
        $condition = "user_id = $id and time > $starttime and time < $stoptime";
        $outcondition = $condition . " and type = '10' and size > 30";
        $incondition = $condition . " and type = '20' and size > 30";
        $out = $m->where($outcondition)->count();
        $in = $m->where($incondition)->count();
#打入的   打出的电话数量
        return array(0 => $out, 1 => $in);
    }

    /**
     * 获取时间超过50秒的 时间长度
     */
    private function getMonthOver30Time($id) {
#显示的是今日的统计数据   需要先更新一下今日的数据
        $m = M('UserVoice');
        $starttime = mktime(0, 0, 0, date("m"), 1, date("Y"));
        $stoptime = mktime(23, 59, 59, date("m"), date("t"), date("Y"));
        $condition = "user_id = $id and time > $starttime and time < $stoptime";
        $outcondition = $condition . " and type = '10' and size > 30";
        $incondition = $condition . " and type = '20' and size > 30";
        $out = $m->where($outcondition)->getField('size', TRUE);
        $outtime = $this->summaryTime($out);
        $in = $m->where($incondition)->getField('size', TRUE);
        $intime = $this->summaryTime($in);
#打入的   打出的电话数量
        return array(0 => $outtime, 1 => $intime);
    }

#############################################################条件查询####################################################################

    /**
     * 条件查询实现
     */
    public function condition_count() {
        $this->display();
    }

    /**
     * 部门或者个人信息查找
     */
    public function deporperson_count() {
        $this->display();
    }

    /**
     * combox 前台根据数据统计   根据部门 或者个人信息统计数据
     * @access public
     */
    public function json_dep_tree() {
        $qiuyun = new \Org\Util\Qiuyun;
        list($org_data, $parent_id) = R('Admin/Alllist/get_tree_data');
        $tree = $qiuyun->list_to_tree($org_data, 'id', 'parent_id', 'children', $parent_id);
        exit(json_encode($tree));
    }

    /**
     * combox 前台根据数据统计   根据部门 或者个人信息统计数据
     * @access public
     */
    public function json_condition_tree() {
        $alldata[] = array('id' => "count", 'text' => '按电话量从高到低排序');
        $alldata[] = array('id' => "percent", 'text' => '按百分比从高到低排序');
        $alldata[] = array('id' => "effective", 'text' => '按有效数量从高到低排序');
        $alldata[] = array('id' => "time", 'text' => '按有效时间长度从高到低排序');
        $data[] = array('id' => "all", 'text' => '全部职员数据', 'state' => 'open', 'children' => $alldata);
        exit(json_encode($data));
    }

    /**
     * 执行条件查询实现
     * @author timelesszhuang <834916321@qq.com>
     * @access public
     */
    public function exec_condition_count() {
        $id = I('post.id');
        $starttime = I('post.starttime');
        $starttime = $starttime . " 00:00:00";
        $stoptime = I('post.stoptime');
        $stoptime = $stoptime . " 00:00:00";
        #转换为时间戳
        $starttimestamp = strtotime($starttime);
        $stoptimestamp = strtotime($stoptime);
        #要查询的时间以上的数据
        $time = I('post.time');
        $condition = "time > $starttimestamp and time < $stoptimestamp and size > $time";
        $allcondition = "time > $starttimestamp and time < $stoptimestamp";
        if ($id == 'count') {
            #全部职员的数据    按照电话数量从高到底排序
            $data = $this->get_all_condition_data($condition, $allcondition);
            $data = $this->dealArr($data);
            #写个排序实现
            $returnData = $this->sortByCount($data);
            $Data['data'] = $returnData;
            $Data['status'] = 'suc';
            exit(json_encode($Data));
        } else if ($id == 'percent') {
            #全部职员的数据   按照职员完成数量百分比排序
            $data = $this->get_all_condition_data($condition, $allcondition);
            $data = $this->dealArr($data);
            #写个排序实现
            $returnData = $this->sortByPercent($data);
            $Data['data'] = $returnData;
            $Data['status'] = 'suc';
            exit(json_encode($Data));
        } else if ($id == 'time') {
            #全部职员的数据   按照职员完成数量百分比排序
            $data = $this->get_all_condition_data($condition, $allcondition);
            $data = $this->dealArr($data);
            #写个排序实现
            $returnData = $this->sortByEffectiveTime($data);
            $Data['data'] = $returnData;
            $Data['status'] = 'suc';
            exit(json_encode($Data));
        } else if ($id == 'effective') {
            #全部职员的数据    按照有效数量排序
            $data = $this->get_all_condition_data($condition, $allcondition);
            $data = $this->dealArr($data);
            #写个排序实现
            $returnData = $this->sortByEffective($data);
            $Data['data'] = $returnData;
            $Data['status'] = 'suc';
            exit(json_encode($Data));
        }
    }

    /**
     * 田间查询-----全部职员的数据 
     * @param  string $condition 查询条件  不带size条件的
     * @param string $allcondition  查询条件  带着size条件的
     */
    private function get_all_condition_data($condition, $allcondition) {
        list($org, $userinfo) = R('Admin/Alllist/getorg_user_info');
        foreach ($org as $key => $value) {
            #每一个部门下面的用户
            $perdepartdata = array();
            //传递的参数为部门的id
            $userdata = $this->get_user_data_bydep_id($value['id']);
            if (!empty($userdata)) {
                #部门下面的每一个职员的数据   $user_id=>$name
                foreach ($userdata as $k => $v) {
                    $peruserdata = array();
                    $perUserData = $this->get_peruser_voicedata($k, $condition, $allcondition);
                    #0  打出   1打入
                    $peruserdata = $perUserData;
                    #用户名称
                    $peruserdata[] = $v;
                    #获取用户的 统计数据 比如用户打入电话 用户打出电话 电话 50秒的数量 
                    $perdepartdata[$k] = $peruserdata;
                    $peruserdata = array();
                }
            }
            $arr[$key] = $perdepartdata;
            $arr['dep_name'] = $value;
            $perdepartdata = array();
            $data[] = $arr;
            $arr = array();
        }
        return $data;
    }

    /**
     * getdeuserdata
     * 根据departmentid获取部门下的人
     * @param int $dep_id 部门id
     * @param array $userinfo 不区分部门的用户数据
     * @return array  用户id=> 用户姓名
     */
    private function get_user_data_bydep_id($dep_id, $userinfo) {
        $data = array();
        foreach ($userinfo as $k => $v) {
            if ($v['parent_id'] == $dep_id) {
                $per_user_data = array('user_id' => $v['id'], 'name' => $v['text']);
                $data[] = $per_user_data;
            }
        }
        return $data;
    }

    /**
     * 获取个人的数据实现
     * @param int $id 用户id
     * @param String $condition
     * @param  $name Description
     */
    private function get_peruser_voicedata($id, $condition, $allcondition) {
        $countarr = $this->getCount($id, $allcondition);
        #0  打出   1打入
        $peruserdata[] = $countarr[0]; //打出 
        $peruserdata[] = $countarr[1];
        $conditioncountarr = $this->getConditionCount($id, $condition);
        if ($countarr[0]) {
            //$hh/$totalpj)*100
            $peruserdata[] = round($conditioncountarr[0] / $countarr[0] * 100) . "%";
        } else {
            $peruserdata[] = "0%";
        } if ($countarr[1]) {
            $peruserdata[] = round($conditioncountarr[1] / $countarr[1] * 100) . "%";
        } else {
            $peruserdata[] = "0%";
        }
        #0  打出   1打入
        $peruserdata[] = $conditioncountarr[0];
        $peruserdata[] = $conditioncountarr[1];

        //获取查询条件下时间长度
        $timearr = $this->getTime($id, $allcondition);
        $conditiontimearr = $this->getConditionTime($id, $condition);
        //0  打出   1打入
        $peruserdata[] = $timearr[0];
        $peruserdata[] = $timearr[1];
        if ($timearr[0]) {
            //$hh/$totalpj)*100
            $peruserdata[] = round($conditiontimearr[0] / $timearr[0] * 100) . "%";
        } else {
            $peruserdata[] = "0%";
        }
        if ($timearr[1]) {
            $peruserdata[] = round($conditiontimearr[1] / $timearr[1] * 100) . "%";
        } else {
            $peruserdata[] = "0%";
        }
        //0  打出   1打入
        $peruserdata[] = $conditiontimearr[0];
        $peruserdata[] = $conditiontimearr[1];
        //print_r($peruserdata);
        return $peruserdata;
    }
  
    /**
     * 条件查询 入口函数   ajax请求
     * @author timelesszhuang<834916321@qq.com>
     * @access public 
     */
    public function condition() {
        $id = I('post.id');
        $starttime = I('post.starttime');
        $starttime = $starttime . " 00:00:00";
        $stoptime = I('post.stoptime');
        $stoptime = $stoptime . " 00:00:00";
        #转换为时间戳
        $starttimestamp = strtotime($starttime);
        $stoptimestamp = strtotime($stoptime);
        #要查询的时间以上的数据
        $time = I('post.time');
        $condition = "time > $starttimestamp and time < $stoptimestamp and size > $time";
        $allcondition = "time > $starttimestamp and time < $stoptimestamp";
        #判断是部门的数据还是个人的数据
        $reg = '/^(de_id)d?/';
        if (preg_match($reg, $id)) {
            #部门的数据    每一个部门下的数据统计
            $dep_id = substr($id, 5);
            $data = $this->getDepData($dep_id, $condition, $allcondition);
            $returnData = $this->formatQueryData($data);
            $Data['data'] = $returnData;
            $Data['status'] = 'suc';
            exit(json_encode($Data));
        } else if ($id == 'all') {
            #全部职员的数据  
            $data = $this->getAllConditionData($condition, $allcondition);
            $returnData = $this->formatAllConditionTable($data);
            $Data['data'] = $returnData;
            $Data['status'] = 'suc';
            exit(json_encode($Data));
        } else if ($id == 'count') {
            #全部职员的数据    按照电话数量从高到底排序
            $data = $this->getAllConditionData($condition, $allcondition);
            $data = $this->dealArr($data);
            #写个排序实现
            $returnData = $this->sortByCount($data);
            $Data['data'] = $returnData;
            $Data['status'] = 'suc';
            exit(json_encode($Data));
        } else if ($id == 'percent') {
            #全部职员的数据   按照职员完成数量百分比排序
            $data = $this->getAllConditionData($condition, $allcondition);
            $data = $this->dealArr($data);
            #写个排序实现
            $returnData = $this->sortByPercent($data);
            $Data['data'] = $returnData;
            $Data['status'] = 'suc';
            exit(json_encode($Data));
        } else if ($id == 'time') {
            #全部职员的数据   按照职员完成数量百分比排序
            $data = $this->getAllConditionData($condition, $allcondition);
            $data = $this->dealArr($data);
            #写个排序实现
            $returnData = $this->sortByEffectiveTime($data);
            $Data['data'] = $returnData;
            $Data['status'] = 'suc';
            exit(json_encode($Data));
        } else if ($id == 'effective') {
            #全部职员的数据    按照有效数量排序
            $data = $this->getAllConditionData($condition, $allcondition);
            $data = $this->dealArr($data);
            #写个排序实现
            $returnData = $this->sortByEffective($data);
            $Data['data'] = $returnData;
            $Data['status'] = 'suc';
            exit(json_encode($Data));
        } else {
            #个人的数据
            $user_id = $id;
            $data = $this->getPerUserData($user_id, $condition, $allcondition);
            $returnData = $this->formatPerQueryData($data);
            $Data['data'] = $returnData;
            $Data['status'] = 'suc';
            exit(json_encode($Data));
        }
    }

    /**
     *  部门或者个人信息
     * @access public
     */
    public function deporpersoncondition() {
        //个人或者部门的信息
    }

    /**
     * 获取部门dep_id下的 职员的数据  
     * 
     */
    private function getDepData($id, $condition, $allcondition) {
        //产品分类获取  分配到前台
        $department_id = $id;
        $userdata = $this->getdata_bydepartmentid($department_id);
        $data = array();
        $perdepartdata = array();
        //要判断是不是该分组为空值
        if (!empty($userdata)) {
            #用户id=>值
            foreach ($userdata as $k => $v) {
                #用户名称
                $peruserdata[] = $v;
                $countarr = $this->getCount($k, $allcondition);
                $conditioncountarr = $this->getConditionCount($k, $condition);
                //0  打出   1打入
                $peruserdata[] = $countarr[0];
                $peruserdata[] = $countarr[1];
                if ($countarr[0]) {
                    //$hh/$totalpj)*100
                    $peruserdata[] = round($conditioncountarr[0] / $countarr[0] * 100) . "%";
                } else {
                    $peruserdata[] = "0%";
                }
                if ($countarr[1]) {
                    $peruserdata[] = round($conditioncountarr[1] / $countarr[1] * 100) . "%";
                } else {
                    $peruserdata[] = "0%";
                }
                //0  打出   1打入
                $peruserdata[] = $conditioncountarr[0];
                $peruserdata[] = $conditioncountarr[1];
                //获取查询条件下时间长度
                $timearr = $this->getTime($k, $allcondition);
                $conditiontimearr = $this->getConditionTime($k, $condition);
                //0  打出   1打入
                $peruserdata[] = $timearr[0];
                $peruserdata[] = $timearr[1];
                if ($timearr[0]) {
                    //$hh/$totalpj)*100
                    $peruserdata[] = round($conditiontimearr[0] / $timearr[0] * 100) . "%";
                } else {
                    $peruserdata[] = "0%";
                }
                if ($timearr[1]) {
                    $peruserdata[] = round($conditiontimearr[1] / $timearr[1] * 100) . "%";
                } else {
                    $peruserdata[] = "0%";
                }
                //0  打出   1打入
                $peruserdata[] = $conditiontimearr[0];
                $peruserdata[] = $conditiontimearr[1];
                $perdepartdata[$k] = $peruserdata;
                $peruserdata = array();
            }
        }
        $arr[0] = $perdepartdata;
        $arr['dep_name'] = '职员';
        $perdepartdata = array();
        $data[] = $arr;
        $arr = array();
        return $data;
    }

    /**
     * 获取 指定情况下的用户数据
     */
    private function getConditionCount($k, $condition) {
        #显示的是今日的统计数据   需要先更新一下今日的数据
        $m = M('UserVoice');
        $condition = $condition . " and user_id = $k ";
        $outcondition = $condition . " and type = '10'";
        $incondition = $condition . " and type = '20'";
        $out = $m->where($outcondition)->count();
        $in = $m->where($incondition)->count();
        #打入的   打出的电话数量
        return array(0 => $out, 1 => $in);
    }

    /**
     * getTime  获取条件下个人的电话时间长度
     * 获取条件下的条件
     */
    private function getConditionTime($k, $condition) {
        #显示的是今日的统计数据   需要先更新一下今日的数据
        $m = M('UserVoice');
        $condition = $condition . " and user_id = $k ";
        $outcondition = $condition . " and type = '10'";
        $incondition = $condition . " and type = '20'";
        $out = $m->where($outcondition)->getField('size', TRUE);
        $outtime = $this->summaryTime($out);
        $in = $m->where($incondition)->getField('size', TRUE);
        $intime = $this->summaryTime($in);
        #打入的   打出的电话数量
        return array(0 => $outtime, 1 => $intime);
    }

    /**
     * 根据时间查询个人的总数据
     */
    private function getCount($k, $condition) {
        #显示的是今日的统计数据   需要先更新一下今日的数据
        $m = M('UserVoice');
        $condition = $condition . " and user_id = $k ";
        $outcondition = $condition . " and type = '10'";
        $incondition = $condition . " and type = '20'";
        $out = $m->where($outcondition)->count();
        $in = $m->where($incondition)->count();
        #打入的   打出的电话数量
        return array(0 => $out, 1 => $in);
    }

    /**
     * getTime  获取条件下个人的数量
     * 
     */
    private function getTime($k, $condition) {
        #显示的是今日的统计数据   需要先更新一下今日的数据
        $m = M('UserVoice');
        $condition = $condition . " and user_id = $k ";
        $outcondition = $condition . " and type = '10'";
        $incondition = $condition . " and type = '20'";
        $out = $m->where($outcondition)->getField('size', TRUE);
        $outtime = $this->summaryTime($out);
        $in = $m->where($incondition)->getField('size', TRUE);
        $intime = $this->summaryTime($in);
        #打入的   打出的电话数量
        return array(0 => $outtime, 1 => $intime);
    }

    /**
     * 格式化部门数据查询数据实现
     */
    private function formatQueryData($data) {
        $string = '';
        foreach ($data as $key => $value) {
            #部门下的数据
            $arr = array_values($value);
            $depart_name = $arr[1];
            $perarr = $arr[0];
            $string .= "<table class='gridtable' style='margin:0 auto;
'>"
                    . "<tr><th rowspan='2'>姓名</th><th colspan='6'>打出电话</th><th colspan='6'>打入电话</th></tr>"
                    . "<tr><th>总数</th><th>符合查询条件数量</th><th>百分比</th><th>总时间</th><th>符合查询条件时间</th><th>百分比 </th><th>总数</th><th>符合查询条件数量</th><th>百分比</th><th>总时间</th><th>符合查询条件时间</th><th>百分比 </th></tr>";
            foreach ($perarr as $k => $v) {
                $string.="<tr><td>$v[0]</td>     <td>$v[1]</td><td>$v[5]</td><td>$v[3]</td>     <td>$v[7]</td><td>$v[11]</td><td>$v[9]</td>       <td>$v[2]</td><td>$v[6]</td><td>$v[4]</td>     <td>$v[8]</td><td>$v[12]</td><td>$v[10]</td> </tr>";
            }
            $string.= "</table><br><br>";
        }
        return $string;
    }

    /**
     * 格式化个人数据查询数据实现
     */
    private function formatPerQueryData($data) {
        $string = '';
        #部门下的数据
        $string .= "<table class='gridtable' style='margin:0 auto;
'>"
                . "<tr><th colspan='6'>打出电话</th><th colspan='6'>打入电话</th></tr>"
                . "<tr><th>总数</th><th>符合查询条件数量</th><th>百分比</th><th>总时间</th><th>符合查询条件时间</th><th>百分比 </th><th>总数</th><th>符合查询条件数量</th><th>百分比</th><th>总时间</th><th>符合查询条件时间</th><th>百分比 </th></tr>";
        $string.="<tr><td>$data[0]</td><td>$data[4]</td><td>$data[2]</td>       <td>$data[6]</td><td>$data[10]</td><td>$data[8]</td>        <td>$data[1]</td><td>$data[5]</td><td>$data[3]</td>    <td>$data[7]</td><td>$data[11]</td><td>$data[9]</td></tr>";
        $string.= "</table><br><br>";
        return $string;
    }

    /**
     * 格式化全部查询的数据实现
     */
    private function formatAllConditionTable($data) {
        $string = '';
        foreach ($data as $key => $value) {
            #部门下的数据
            $arr = array_values($value);
            $depart_name = $arr[1];
            $perarr = $arr[0];
            $string .= "<table class='gridtable' style='margin:0 auto;
'>"
                    . "<tr><td colspan='13'>部门：$depart_name</td></tr>"
                    . "<tr><th rowspan='2'>姓名</th><th colspan='6'>打出电话</th><th colspan='6'>打入电话</th></tr>"
                    . "<tr><th>总数</th><th>符合查询条件数量</th><th>百分比</th><th>总时间</th><th>符合查询条件时间</th><th>百分比 </th><th>总数</th><th>符合查询条件数量</th><th>百分比</th><th>总时间</th><th>符合查询条件时间</th><th>百分比 </th></tr>";
            foreach ($perarr as $k => $v) {
                $string.="<tr><td>$v[12]</td>    <td>$v[0]</td><td>$v[4]</td><td>$v[2]</td>     <td>$v[6]</td><td>$v[10]</td><td>$v[8]</td>       <td>$v[1]</td><td>$v[5]</td><td>$v[3]</td>      <td>$v[7]</td><td>$v[11]</td><td>$v[9]</td>   </tr>";
            }
            $string.= "</table><br><br>";
        }
        return $string;
    }

    #########################################数组排序实现###########################

    private function dealArr($data) {
        $finalArr = array();
        foreach ($data as $key => $value) {
            #部门下的数据
            $arr = array_values($value);
            $depart_name = $arr[1];
            $perarr = $arr[0];
            foreach ($perarr as $k => $v) {
                $finalArr[$k] = $v;
                $finalArr[$k][] = $depart_name;
            }
        }
        //处理数据实现
        return $finalArr;
    }

    /**
     * 按照百分比实现排序
     */
    private function sortByPercent($data) {
        $data = $this->sortByData($data, 2);
        $string = $this->formatCountAndPercentTable($data);
        return $string;
    }

    /**
     * 按照数量排序
     */
    private function sortByCount($data) {
        $data = $this->sortByData($data, 0);
        $string = $this->formatCountAndPercentTable($data);
        return $string;
    }

    /**
     * sortByEffectiveTime 按照有效时间统计
     */
    private function sortByEffectiveTime($data) {
        $data = $this->sortByData($data, 10);
        $string = $this->formatCountAndPercentTable($data);
        return $string;
    }

    /**
     * 按照有效数量排序
     */
    private function sortByEffective($data) {
        $data = $this->sortByData($data, 4);
        $string = $this->formatCountAndPercentTable($data);
        return $string;
    }

    /**
     * 根据字段排序
     */
    private function sortByData($array, $field) {
        $sort = $field;
        foreach ($array as $k => $v) {
            if ($sort == "2") {
                if (strlen($v[2]) == 3) {
                    $newArr[$k] = substr($v[2], 0, 2);
                } else {
                    $newArr[$k] = substr($v[2], 0, 3);
                }
            } else if ($sort == "0") {
                $newArr[$k] = $v[$sort];
            } else {
                $newArr[$k] = $v[$sort];
            }
        }
        //这个函数如果执行正确他会直接改变原数组键值的顺序 
        //如果执行失败，那么他会返回 bool(false)
        array_multisort($newArr, SORT_DESC, $array);
        return $array;
        exit;
    }

    /**
     * 格式化字段 字段信息
     */
    private function formatCountAndPercentTable($data) {
        $string = '';
        #部门下的数据
        $string .= "<table class='gridtable' style='margin:0 auto;
'>"
                . "<tr><th rowspan='2'>姓名</th><th rowspan='2'>部门</th><th colspan='6'>打出电话</th><th colspan='6'>打入电话</th></tr>"
                . "<tr><th>总数</th><th>符合查询条件数量</th><th>百分比</th>    <th>总时间</th><th>符合查询条件时间</th><th>百分比 </th>      <th>总数</th><th>符合查询条件数量</th><th>百分比</th>  <th>总时间</th><th>符合查询条件时间</th><th>百分比 </th> </tr>";
        foreach ($data as $k => $v) {
            $string.="<tr><td>$v[12]</td><td>$v[13]</td>      <td>$v[0]</td><td>$v[4]</td> <td>$v[2]</td>   <td>$v[6]</td><td>$v[10]</td><td>$v[8]</td>      <td>$v[1]</td><td>$v[5]</td><td>$v[3]</td>    <td>$v[7]</td><td>$v[11]</td><td>$v[9]</td> </tr>";
        }
        $string.= "</table><br><br>";
        return $string;
    }

}

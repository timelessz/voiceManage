<?php

namespace Admin\Controller;

use Think\Controller;

/**
 * StatisticsController.class.phpb      
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
     * 部门或者个人信息
     * @access public
     */
    public function exec_deporpersoncondition() {
        //个人或者部门的信息
        //如果是职员   前边带着  clerk+职员的id
        list($org, $userinfo ) = R('Admin/Alllist/getorg_user_info');
        $id = I('post.id');
        $starttime = I('post.starttime');
        $starttime = $starttime . " 00:00:00";
        $stoptime = I('post.stoptime');
        $stoptime = $stoptime . " 00:00:00";
        //转换为时间戳
        $starttimestamp = strtotime($starttime);
        $stoptimestamp = strtotime($stoptime);
        //要查询的时间以上的数据
        $time = I('post.time');
        $condition = "time > $starttimestamp and time < $stoptimestamp and size > $time";
        $allcondition = "time > $starttimestamp and time < $stoptimestamp";
        //判断是部门的数据还是个人的数据
        $mat = 'clerk';
        //返回位置  如果存在  不存在的话返回false
        $index = strpos($id, $mat);
        if ($index === false) {
            //部门的数据    每一个部门下的数据统计   $id表示部门
            $data = $this->get_dep_data_bydep_id($id, $condition, $allcondition, $org, $userinfo);
            $returnData = $this->formatQueryData($data);
            $Data['data'] = $returnData;
            $Data['status'] = 'suc';
            exit(json_encode($Data));
        } else {
            //个人的   数据
            $user_id = substr($id, 5);
            $data = $this->get_peruser_voicedata($user_id, $condition, $allcondition);
            $returnData = $this->formatPerQueryData($data);
            $Data['data'] = $returnData;
            $Data['status'] = 'suc';
            exit(json_encode($Data));
        }
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
     * 条件查询-----全部职员的数据 
     * @access private
     * @param  string $condition 查询条件  不带size条件的
     * @param string $allcondition  查询条件  带着size条件的
     * @return  
     */
    private function get_all_condition_data($condition, $allcondition) {
        list($org, $userinfo ) = R('Admin/Alllist/getorg_user_info');
        foreach ($org as $key => $value) {
            #每一个部门下面的用户
            $perdepartdata = array();
            //传递的参数为部门的id    求出现在的部门下面数据
            $dep_id = $value['id'];
            $userdata = $this->get_user_data_bydep_id($dep_id, $userinfo);
//         print_r($userdata);
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
            $arr['dep_name'] = $value['text'];
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
                $data[$v['clerk_id']] = $v['text'];
            }
        }
        return $data;
    }

    /**
     * getdeuserdata
     * 根据departmentid获取部门下所有的人
     * @param int $dep_id 部门id
     * @param array $userinfo 不区分部门的用户数据
     * @return array  用户id=> 用户姓名
     */
    private function get_sonuser_data_bydep_id($dep_id, $org, $userinfo) {
        //这个地方出现问题了
        $mat = "*,$dep_id,*";
        //首先回去当前下的分类的子孙目录
        $son_dep_id_arr = array();
        foreach ($org as $k => $v) {
//          $index = strpos($mat, $v['path']);    这个地方不能使用strpos 不知道为什么  这个函数还是有问题
            if (preg_match($mat, $v['path'])) {
                $son_dep_id_arr[] = $v['id'];
            }
        }
        //把父亲分类添加上
        $son_dep_id_arr[] = $dep_id;
        $userdata = array();
        foreach ($userinfo as $k => $v) {
            if (in_array($v['parent_id'], $son_dep_id_arr)) {
                $userdata[$v['clerk_id']] = $v['text'];
            }
        }
        return $userdata;
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
     * 获取部门dep_id下的 职员的数据
     */
    private function get_dep_data_bydep_id($dep_id, $condition, $allcondition, $org, $userinfo) {
        //产品分类获取  分配到前台
        $userdata = $this->get_sonuser_data_bydep_id($dep_id, $org, $userinfo);
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
     * 格式化字段 字段信息   根据条件查询实现
     */
    private function formatCountAndPercentTable($data) {
        $string = '';
        #部门下的数据
        $string .= "<table class='gridtable' style='margin:0 auto;'>"
                . "<tr><th rowspan='2'>姓名</th><th rowspan='2'>部门</th><th colspan='6'>打出电话</th><th colspan='6'>打入电话</th></tr>"
                . "<tr><th>总数</th><th>符合查询条件数量</th><th>百分比</th>    <th>总时间</th><th>符合查询条件时间</th><th>百分比 </th>      <th>总数</th><th>符合查询条件数量</th><th>百分比</th>  <th>总时间</th><th>符合查询条件时间</th><th>百分比 </th> </tr>";
        foreach ($data as $k => $v) {
            $string.="<tr><td>$v[12]</td><td>$v[13]</td>      <td>$v[0]</td><td>$v[4]</td> <td>$v[2]</td>   <td>$v[6]</td><td>$v[10]</td><td>$v[8]</td>      <td>$v[1]</td><td>$v[5]</td><td>$v[3]</td>    <td>$v[7]</td><td>$v[11]</td><td>$v[9]</td> </tr>";
        }
        $string.= "</table><br><br>";
        return $string;
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
            $string .= "<table class='gridtable' style='margin:0 auto;'>"
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
        $string .= "<table class='gridtable' style='margin:0 auto;'>"
                . "<tr><th colspan='6'>打出电话</th><th colspan='6'>打入电话</th></tr>"
                . "<tr><th>总数</th><th>符合查询条件数量</th><th>百分比</th><th>总时间</th><th>符合查询条件时间</th><th>百分比 </th><th>总数</th><th>符合查询条件数量</th><th>百分比</th><th>总时间</th><th>符合查询条件时间</th><th>百分比 </th></tr>";
        $string.="<tr><td>$data[0]</td><td>$data[4]</td><td>$data[2]</td>       <td>$data[6]</td><td>$data[10]</td><td>$data[8]</td>        <td>$data[1]</td><td>$data[5]</td><td>$data[3]</td>    <td>$data[7]</td><td>$data[11]</td><td>$data[9]</td></tr>";
        $string.= "</table><br><br>";
        return $string;
    }

}

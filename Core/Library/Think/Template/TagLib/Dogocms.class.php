<?php

namespace Think\Template\TagLib;
use Think\Template\TagLib;
defined('THINK_PATH') or exit();
//自定义标签
class Dogocms extends TagLib {

    protected $tags = array(
        // 标签定义： attr 属性列表 close 是否闭合（0 或者1 默认1） alias 标签别名 level 嵌套层次
        // 'test' => array('attr' => "attr1,attr2", level => 3),
        'nav' => array('attr' => 'id,limit,type,order,name,key,mod', level => 3), //网站导航 type:top,son,all;name:head,foot
        'article' => array('attr' => 'typeid,type,tid,limit,flag,order,keywords', 'level' => 3), //文章内容
        'sort' => array('attr' => 'typeid,order,key,mod', level => 3), //栏目分类
        'message' => array('attr' => 'attr1,attr2', level => 3), //咨询留言
        'comment' => array('attr' => 'attr1,attr2', level => 3), //评论
        'list' => array('attr' => 'attr1,attr2', level => 3), //列表页内容
        'pagelist' => array('attr' => 'attr1,attr2', level => 3), //分页
        'ads' => array('attr' => 'typeid,limit,order', level => 3), //广告（包含幻灯）
        'page' => array('attr' => 'attr1,attr2', level => 3), //广告（包含幻灯）
        'block' => array('attr' => 'typeid,limit,order', level => 3, 'close' => 1), //碎片
        'member' => array('attr' => 'attr1,attr2', level => 3), //会员信息(个人)
        'cfg' => array('attr' => 'name', level => 3, 'close' => 0), //系统参数
        'links' => array('attr' => 'typeid,limit,order', level => 3, 'close' => 1), //友情链接
    );

//文档分类
    public function _sort($tag, $content)
    {
        $typeid = $tag['typeid']; //分类ID，ID为0时调用全部分类
        $order = $tag['order']; //排序
        $tag['where'] = ' (`status`=\'20\') ';
        if ($typeid != '0') {//ID为0时调用全部分类
            $tag['where'] .= ' and (`path` like \'%' . $typeid . '%\') ';
        }
        $sql = "M('NewsSort')->";
        $sql .= ($order) ? "order(\"{$order}\")->" : '';
        $sql .= ($tag['where']) ? "where(\"{$tag['where']}\")->" : '';   //被重新处理过了
        $sql .= "select()";
        $result = 'sort'; //定义数据查询的结果存放变量
        $key = !empty($tag['key']) ? $tag['key'] : 'i';
        $mod = isset($tag['mod']) ? $tag['mod'] : '2';
        //下面拼接输出语句
        $parsestr = '<?php $qiuyun = new \Org\Util\Qiuyun; ';
        $parsestr .= '$_result=$qiuyun->list_to_tree(' . $sql . ',"id", "parent_id", "children"); if ($_result): $' . $key . '=0;';
        $parsestr .= 'foreach($_result as $key=>$' . $result . '):';
        $parsestr .= '++$' . $key . ';$mod = ($' . $key . ' % ' . $mod . ' );?>';
        $parsestr .= $content; //解析在article标签中的内容
        $parsestr .= '<?php endforeach; endif;?>';
        return $parsestr;
    }

//  ads广告标签 typeid,limit,order
    public function _ads($tag, $content)
    {
        $typeid = $tag['typeid'];
        $limit = $tag['limit'];
        $order = $tag['order']; //字符串加引号
        if (empty($limit)) {
            $tag['limit'] = '0,4';
        }
        if (empty($order)) {
            $order = 'myorder asc';
        }
        $tag['where'] = ' (`status`=\'20\') ';
        if ($typeid) {
            $tag['where'] .= ' and (`sort_id` =' . $typeid . ') ';
        }
        $sql = "M('Ads')->";
        $sql .= ($order) ? "order(\"{$order}\")->" : '';
        $sql .= ($tag['limit']) ? "limit({$tag['limit']})->" : '';
        $sql .= ($tag['where']) ? "where(\"{$tag['where']}\")->" : '';   //被重新处理过了
        $sql .= "select()";
        $result = 'ads'; //定义数据查询的结果存放变量
        $key = !empty($tag['key']) ? $tag['key'] : 'i';
        $mod = isset($tag['mod']) ? $tag['mod'] : '2';
        //下面拼接输出语句
        $parsestr = '<?php $_result=' . $sql . '; if ($_result): $' . $key . '=0;';
        $parsestr .= 'foreach($_result as $key=>$' . $result . '):';
        $parsestr .= '++$' . $key . ';$mod = ($' . $key . ' % ' . $mod . ' );?>';
        $parsestr .= $content; //解析在article标签中的内容
        $parsestr .= '<?php endforeach; endif;?>';
        return $parsestr;
    }

    //取得配置信息
    //之后存入缓存文件
    public function _cfg($tag)
    {
        //$tag = $this->parseXmlAttr($attr, 'cfg');
        $name = $tag['name'];
        $m = M('Setting');
        $condition['sys_name'] = array('eq', $name);
        $data = $m->where($condition)->find();
        $parseStr = '';
        if ($data) {
            $parseStr = htmlspecialchars_decode($data['sys_value']);
        }
        return $parseStr;
    }

//  头部和底部导航
    public function _nav($tag, $content)
    {
        $name = $tag['name'];
        $limit = $tag['limit'];
        $order = $tag['order']; //字符串加引号
        $type = $tag['type'];
        $id = $tag['id'];
        $tag['where'] = '(`status` = \'20\')';
        if (empty($limit)) {
            $tag['limit'] = '0,10';
        }
        $tag['name'] = ucfirst($tag['name']);
        $sql = "M('Nav{$tag['name']}')->";
        $sql .= ($order) ? "order(\"{$order}\")->" : '';
        $sql .= ($tag['limit']) ? "limit({$tag['limit']})->" : '';
        $sql .= ($tag['where']) ? "where(\"{$tag['where']}\")->" : '';   //被重新处理过了
        $sql .= "select()";
        $result = 'nav'; //定义数据查询的结果存放变量
        $key = !empty($tag['key']) ? $tag['key'] : 'i';
        $mod = isset($tag['mod']) ? $tag['mod'] : '2';
        //下面拼接输出语句
        $parsestr = '<?php';
        $parsestr .= '$_result=list_to_tree(' . $sql . ',"id", "parent_id", "children"); if ($_result): $' . $key . '=0;';
        $parsestr .= 'foreach($_result as $key=>$' . $result . '):';
        $parsestr .= '++$' . $key . ';$mod = ($' . $key . ' % ' . $mod . ' );?>';
        $parsestr .= $content; //解析在article标签中的内容
        $parsestr .= '<?php endforeach; endif;?>';
        return $parsestr;
    }

    public function _article($tag, $content)
    {
        $typeid = trim($tag['typeid']); //分类id
        $type = strtoupper($tag['type']); //分类类型type:all
        $tid = $tag['tid']; //指定文档id
        $limit = $tag['limit']; //显示信息数 默认10
        $flag = $tag['flag']; //信息属性
        $order = $tag['order']; //信息排序
        $keywords = $tag['keywords']; //包含关键词
        $tag['where'] = ' (t.`status`=\'12\') ';
        $tag['where'] .= ' and (t.`is_recycle`=\'10\') ';
        if (empty($limit)) {
            $tag['limit'] = '0,10';
        }
        if ($typeid) {
            if ($type == 'ALL') {//分类类型存在时，分类id一定存在，此处根据type获取所有子类id
                $ns = M('NewsSort');
                foreach (explode(',', $typeid) as $k => $v) {
                    $path .= ' (path like \'%,' . $v . ',%\') or';
                    $path .= ' (`id` = ' . $v . ') or';
                }
                $path = rtrim($path, 'or ');
                $rs = $ns->field('id')->where($path)->select();
                foreach ($rs as $v) {
                    $sort_id .= $v['id'] . ',';
                }
                $sort_id = rtrim($sort_id, ', ');
                $tag['where'] .= ' and (t.`sort_id` in(' . $sort_id . '))';
            } else {
                $tag['where'] .= ' and (t.`sort_id` in(' . $typeid . '))';
            }
        }//if
        if ($tid) {
            $tag['where'] .= ' and (t.`id` in(' . $tid . ')) ';
        }//if
        if ($flag) {
            foreach (explode(',', $flag) as $k => $v) {
                $flag_like .= ' (t.`flag` like \'%' . $v . '%\') or ';
            }
            $flag_like = rtrim($flag_like, 'or ');
            $tag['where'] .= ' and (' . $flag_like . ') ';
        }//if
        if ($keywords) {
            $tag['where'] .= ' and (t.`keywords` like \'%' . $keywords . '%\') ';
        }//if
        $tag['field'] = ' \'t.*,c.content,m.username,ns.text as sortname\' ';
        $table = '\'' . C('DB_PREFIX') . 'title t\'';
        $join = 'join(\' ' . C('DB_PREFIX') . 'news_sort ns on ns.id=t.sort_id \')->';
        $join .= 'join(\' ' . C('DB_PREFIX') . 'content c on c.title_id=t.id \')->';
        $join .= 'join(\' ' . C('DB_PREFIX') . 'members m on m.id=t.members_id \')->';
        $result = 'article'; //定义数据查询的结果存放变量
        $key = !empty($tag['key']) ? $tag['key'] : 'i';
        $mod = isset($tag['mod']) ? $tag['mod'] : '2';
        $sql = "M('Title')->";
        $sql .= "table({$table})->";
        $sql .= $join;
        $sql .= ($tag['field']) ? "field({$tag['field']})->" : '';
        $sql .= ($order) ? "order(\"{$order}\")->" : 'order(\'t.id desc\')->';
        $sql .= ($tag['where']) ? "where(\"{$tag['where']}\")->" : '';   //被重新处理过了
        $sql .= ($tag['limit']) ? "limit({$tag['limit']})->" : '';
        $sql .= "select()";
        //下面拼接输出语句
        $parsestr = '<?php $_result=' . $sql . '; if ($_result): $' . $key . '=0;';
        $parsestr .= 'foreach($_result as $key=>$' . $result . '):';
        $parsestr .= '++$' . $key . ';$mod = ($' . $key . ' % ' . $mod . ' );?>';
        $parsestr .= $content; //解析在article标签中的内容
        $parsestr .= '<?php endforeach; endif;?>';
        return $parsestr;
    }

//  头部和底部导航
    public function _links($tag, $content)
    {
        $typeid = $tag['typeid'];
        $limit = $tag['limit'];
        $order = $tag['order']; //字符串加引号
        $tag['where'] = ' (`status`=\'20\') '; //限制显示条件
        if (!empty($typeid)) {
            $tag['where'] .= ' and (`sort_id` in(' . $typeid . ')) ';
        }
        if (empty($limit)) {
            $tag['limit'] = '0,10';
        }
        $sql = "M('Links')->";
        $sql .= ($order) ? "order(\"{$order}\")->" : '';
        $sql .= ($tag['limit']) ? "limit({$tag['limit']})->" : '';
        $sql .= ($tag['where']) ? "where(\"{$tag['where']}\")->" : '';   //被重新处理过了
        $sql .= "select()";
        $result = 'links'; //定义数据查询的结果存放变量
        $key = !empty($tag['key']) ? $tag['key'] : 'i';
        $mod = isset($tag['mod']) ? $tag['mod'] : '2';
        //下面拼接输出语句
        $parsestr = '<?php $_result=' . $sql . '; if ($_result): $' . $key . '=0;';
        $parsestr .= 'foreach($_result as $key=>$' . $result . '):';
        $parsestr .= '++$' . $key . ';$mod = ($' . $key . ' % ' . $mod . ' );?>';
        $parsestr .= $content; //解析在article标签中的内容
        $parsestr .= '<?php endforeach; endif;?>';
        return $parsestr;
    }

    //  block碎片标签 typeid,limit,order
    public function _block($tag, $content)
    {
        $typeid = $tag['typeid'];
        $limit = $tag['limit'];
        $order = $tag['order']; //字符串加引号
        if (empty($limit)) {
            $tag['limit'] = '0,10';
        }
        if (empty($order)) {
            $order = 'myorder asc';
        }
        $tag['where'] = ' (`status`=\'20\') ';
        if ($typeid) {
            $tag['where'] .= ' and (`sort_id` =' . $typeid . ') ';
        }
        $sql = "M('BlockList')->";
        $sql .= ($order) ? "order(\"{$order}\")->" : '';
        $sql .= ($tag['limit']) ? "limit({$tag['limit']})->" : '';
        $sql .= ($tag['where']) ? "where(\"{$tag['where']}\")->" : '';   //被重新处理过了
        $sql .= "select()";
        $result = 'block'; //定义数据查询的结果存放变量
        $key = !empty($tag['key']) ? $tag['key'] : 'i';
        $mod = isset($tag['mod']) ? $tag['mod'] : '2';
        //下面拼接输出语句
        $parsestr = '<?php $_result=' . $sql . '; if ($_result): $' . $key . '=0;';
        $parsestr .= 'foreach($_result as $key=>$' . $result . '):';
        $parsestr .= '++$' . $key . ';$mod = ($' . $key . ' % ' . $mod . ' );?>';
        $parsestr .= $content; //解析在article标签中的内容
        $parsestr .= '<?php endforeach; endif;?>';
        return $parsestr;
    }

}

?>
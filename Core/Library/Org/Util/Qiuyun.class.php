<?php

/**
 * 扩展函数
 * @author 赵兴壮 <834916321@qq.com>
 * @package  Controller
 * @todo 内容模型各项操作
 */
namespace Org\Util;

class Qiuyun {

    /**
     * 把返回的数据集转换成Tree  本函数使用引用传递  修改  数组的索引架构 
     *  可能比较难理解     函数中   $reffer    $list[]  $parent
     *  等的信息实际上只是内存中地址的引用
     * @access public
     * @param array $list 要转换的数据集
     * @param string $pid parent标记字段
     * @param string $level level标记字段
     * @return array
     */
    function list_to_tree($list, $pk = 'id', $pid = 'pid', $child = '_child', $root = 0) {
        // 创建Tree
        $tree = array();
        if (is_array($list)) {
            //创建基于主键的数组引用
            $refer = array();
            foreach ($list as $key => $data) {
                $refer[$data[$pk]] = & $list[$key];
            }
            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parentId = $data[$pid];
                if ($root == $parentId) {
                    //根节点元素
                    $tree[] = & $list[$key];
                } else {
                    if (isset($refer[$parentId])) {
                        //当前正在遍历的父亲节点的数据
                        $parent = & $refer[$parentId];
                        //把当前正在便利的数据赋值给父亲类的  children
                        $parent[$child][] = & $list[$key];
                    }
                }
            }
        }
        return $tree;
    }

}

<?php if (!defined('THINK_PATH')) exit();?><table  id="datagrid_voice_list<?php echo ($id); ?>">

</table>
<script>
    $(function () {
        var classId = 'voice_list' + '<?php echo ($id); ?>';
        var urljson = '<?php echo U("Admin/Filelist/json",array("id"=>$id));?>';
        var hrefaddall = '<?php echo U("Admin/Filetraverse/getAllData");?>';
        var hrefadd = '<?php echo U("Admin/Filetraverse/getTodayData");?>';
        openDatagrid(classId, urljson);
        $('#datagrid_' + classId).datagrid({
            columns: [[
                    {field: 'id', title: 'ID', width: 20, align: 'center'},
                    {field: 'size', title: '长度', width: 30},
                    {field: 'type', title: '类型', width: 20},
                    {field: 'play', title: '播放', width: 100},
                    {field: 'tel', title: '客户电话', width: 40},
                    {field: 'time', title: '拨打时间', width: 40},
                ]],
            toolbar: [{
                    id: 'btnadd_' + classId,
                    text: '更新全部录音',
                    iconCls: 'icon-add',
                    handler: function () {
                        ajaxTraverse(hrefaddall, "2");
                    }
                }, '-', {
                    id: 'btnadd_' + classId,
                    text: '更新今日录音',
                    iconCls: 'icon-add',
                    handler: function () {
                        ajaxTraverse(hrefadd, "1");
                    }
                }
            ]//toolbar
        });
    });
</script>
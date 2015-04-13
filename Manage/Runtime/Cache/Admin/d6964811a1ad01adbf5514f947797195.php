<?php if (!defined('THINK_PATH')) exit();?><table  id="datagrid_voice_list<?php echo ($id); ?>">

</table>
<script>
    $(function () {
        var classId = 'voice_list' + '<?php echo ($id); ?>';
        var urljson = '<?php echo U("Admin/Alllist/json",array("id"=>$id));?>';
        var hrefplay = '<?php echo U("Admin/Filelist/play");?>';
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
            toolbar: ['-', {
                    id: 'btnsearch' + classId,
                    text: '电话:<input id=\"tel\" name=\"tel\"  class=\"easyui-textbox\" style=\"width:70px;\"/>'
                }, {
                    id: 'btnsubmit' + classId,
                    iconCls: 'icon-search',
                    handler: function () {
                        var tel = $('input[name="tel"]').val();
                        if (!tel) {
                            alert("请输入要查询的电话。");
                            return;
                        }
                        $('#datagrid_' + classId).datagrid('load', {
                            tel: tel,
                        });
                    }
                },
            ]
        });
    });
</script>
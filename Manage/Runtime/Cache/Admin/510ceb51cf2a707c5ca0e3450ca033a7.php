<?php if (!defined('THINK_PATH')) exit();?><div class="easyui-layout layout_filelist">
    <div data-options="region:'west',split:true" title="订单分类" style="width:150px;">
        <ul class="easyui-tree tree_filelist" data-options="url:'/voiceManage/index.php/Admin/Filelist/jsonTree'" style="padding: 10px 5px;">
        </ul>
    </div>
    <div data-options="region:'center'" class="smallindexcenter" >
        <div id="tabs_filelist" class="easyui-tabs"  fit="true" border="false">
        </div>
        <script>
            $(function() {
                var height = $('.indexcenter').height();
                var classId = 'filelist';
                $('.layout_' + classId).css('height', height - 50);
                $('.tree_' + classId).tree({
                    onClick: function() {
                        var node = $('.tree_' + classId).tree('getSelected');
                        var id = node.id;
                        if (id == 'no') {
                            return;
                        }
                        var url = '/voiceManage/index.php/Admin/Filelist/voicelist?id=' + id;
                        var subtitle = node.text;
                        if (!$('#tabs_' + classId).tabs('exists', subtitle)) {
                            $('#tabs_' + classId).tabs('add', {
                                title: subtitle,
                                content: subtitle,
                                closable: true,
                                href: url,
                                tools: [{
                                        iconCls: 'icon-mini-refresh',
                                        handler: function() {
                                            updateTab(classId, url, subtitle);
                                        }
                                    }]
                            });
                            return false;
                        } else {
                            $('#tabs_' + classId).tabs('select', subtitle);
                            return false;
                        }
                    }//onclick
                });
            })
        </script>
    </div>
</div>
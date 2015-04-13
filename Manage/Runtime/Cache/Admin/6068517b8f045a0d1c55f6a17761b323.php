<?php if (!defined('THINK_PATH')) exit();?><div style="padding-top:50px;">
    <div  style='width:80%;margin: 0 auto;clear: both;font-size:18px;color:#080'>
        <center>
            <input type="button" name="up_orgstructure" value="更新公司组织架构" style="width:250px;height:45px;font-size:18px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="button" name="up_telnum" value="更新职员手机分机号码" style="width:250px;height:45px;font-size:18px;">
        </center>
    </div>
</div>
<script>

    $('input[name="up_orgstructure"]').click(function () {
        ajax(0);
    });
    $('input[name="up_telnum"]').click(function () {
        ajax(1);
    });
    /**
     * 
     */
    function ajax(flag)
    {
        $.ajax({
            url: '/voiceManage/index.php/Admin/Statistics/condition',
            type: 'post',
            data: {
                flag: flag,
            },
            dataType: 'json',
            success: function (data) {
                if (data.status == 'suc') {
                    $('#data').html(data.data);
                } else {
                    alert('查询失败。');
                }
            }
        })
    }

</script>
<!--        var id = $('input[name="id"]').val();
        var starttime = $('input[name="starttime"]').val();
        var stoptime = $('input[name="stoptime"]').val();
        var time = $('input[name="time"]').val();
        if (id == "")
        {
            alert("选择部门，或者职员。");
            return false;
        }
        if (time == "")
        {
            alert("请填写要查询的时间长度。");
            return false;
        }
        $.ajax({
            url: '/voiceManage/index.php/Admin/Statistics/condition',
            type: 'post',
            data: {
                id: id,
                starttime: starttime,
                stoptime: stoptime,
                time: time,
            },
            dataType: 'json',
            success: function (data) {
                if (data.status == 'suc') {
                    $('#data').html(data.data);
                } else {
                    alert('查询失败。');
                }
            }
        })

-->
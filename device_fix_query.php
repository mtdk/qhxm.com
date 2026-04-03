<?php
include __DIR__ . '/user_session/user_session.php';
include __DIR__ . '/user_session/login_state.php';
include __DIR__ . '/db/db.php';
include __DIR__ . '/myHeader.php';
include __DIR__ . '/myMenu.php';

?>
    <main class="flex-shrink-0">
        <div class="container mt-lg-auto">
            <div class="row mt-lg-5">
                <div class="col-auto">
                    <label class="form-control-plaintext">查询开始时间</label>
                </div>
                <div class="col-auto">
                    <input type="date" class="form-control" id="start_time">
                </div>
                <div class="col-auto">
                    <label class="form-control-plaintext">查询结束时间</label>
                </div>
                <div class="col-auto">
                    <input type="date" class="form-control" id="stop_time">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary mb-3" onclick="LoadData()">查询</button>
                </div>
            </div>
            <table class="table caption-top table-hover table-success text-primary table-sm text-center">
                <caption>分散设备运行记录打印</caption>
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">维修单号</th>
                    <th scope="col">设备名称</th>
                    <th scope="col">维修人</th>
                    <th scope="col">操作</th>
                </tr>
                </thead>
                <tbody id="tbody">
                </tbody>
            </table>
            <script type="text/javascript">
                function LoadData() {
                    let start_time = $('#start_time').val();
                    let stop_time = $('#stop_time').val();
                    if (start_time == '') {
                        alert('请选择查询开始时间');
                    } else if (stop_time == '') {
                        alert('请选择查询结束时间');
                    } else {
                        $.ajax({
                            type: 'POST',
                            url: './device_fix_query_send.php',
                            data: {
                                start_time: start_time,
                                stop_time: stop_time
                            },
                            success: function (data) {
                                //console.log(data);
                                const a = data.split(' ');
                                //console.log(a);
                                let trStr = '';//动态拼接table
                                for (let i = 0; i < a.length - 1; i++) {
                                    trStr += '<tr>';
                                    trStr += '<td>' + (i + 1) + '</td>';
                                    trStr += '<td>' + JSON.parse(a[i]).repair_id + '</td>';
                                    trStr += '<td>' + JSON.parse(a[i]).device_name + '</td>';
                                    trStr += '<td>' + JSON.parse(a[i]).repairer_name + '</td>';
                                    trStr += '<td colspan=10><a href="device_fix_print.php?repair_id=' + JSON.parse(a[i]).repair_id + '"class="btn btn-outline-success btn-sm" target="_blank">打印</a>';
                                    trStr += '</tr>';
                                }
                                $("#tbody").html(trStr);
                            }
                        });
                    }
                }
            </script>
        </div>
    </main>
<?php include __DIR__ . '/myFooter.php'; ?>
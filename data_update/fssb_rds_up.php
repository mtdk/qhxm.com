<?php
include __DIR__ . '/../user_session/user_session.php';
include __DIR__ . '/../user_session/userinfo_check.php';
include __DIR__ . '/../myHeader.php';
include __DIR__ . '/../myMenu.php';
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
                    <th scope="col">设备编号</th>
                    <th scope="col">启动日期</th>
                    <th scope="col">启动时间</th>
                    <th scope="col">产品编号</th>
                    <th scope="col">产品批号</th>
                    <th scope="col">当前状态</th>
                    <th scope="col">操作员</th>
                    <th scope="col">#</th>
                </tr>
                </thead>
                <tbody id="tbody">
                </tbody>
            </table>
            <script type="text/javascript">
                function LoadData() {
                    let start_time = $('#start_time').val();
                    let stop_time = $('#stop_time').val();
                    let userid = $('#userid option:selected').val();
                    if (start_time == '') {
                        alert('请选择查询开始时间');
                    } else if (stop_time == '') {
                        alert('请选择查询结束时间');
                    } else {
                        $.ajax({
                            type: 'POST',
                            url: './fssb_rdsup_send.php',
                            data: {
                                start_time: start_time,
                                stop_time: stop_time
                            },
                            success: function (data) {
                                let dt = JSON.parse(data);
                                let i;
                                let trStr = '';
                                for (i = 0; i < dt.length; i++) {
                                    trStr += '<tr>';
                                    trStr += '<td>' + dt[i].id + '</td>';
                                    trStr += '<td>' + dt[i].machine_id + '</td>';
                                    trStr += '<td>' + dt[i].register_date + '</td>';
                                    trStr += '<td>' + dt[i].register_time.slice(0, 5) + '</td>';
                                    trStr += '<td>' + dt[i].pro_id + '</td>';
                                    trStr += '<td>' + dt[i].bath_number + '</td>';
                                    trStr += '<td>' + dt[i].machine_status + '</td>';
                                    trStr += '<td>' + dt[i].uname + '</td>';
                                    trStr += '<td><a href="#?id=' + dt[i].id + '" class="btn-link link-danger">修改</a>' + '</td>';
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
<?php
include __DIR__ . '/../myFooter.php';
<?php
include __DIR__ . '/../user_session/user_session.php';
include __DIR__ . '/../user_session/userinfo_check.php';
include __DIR__ . '/../db/db.php';
include __DIR__ . '/../myHeader.php';
include __DIR__ . '/../myMenu.php';
$stmt = $dbh->prepare("select uid,uname from usertb");
$stmt->execute();
$rows = $stmt->fetchAll();

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
                    <label class="form-control-plaintext">操作人员</label>
                </div>
                <div class="col-auto">
                    <select class="form-select" id="userid">
                        <option selected disabled value="">请选择您的姓名...</option>
                        <?php
                        foreach ($rows as $key => $value) {
                            echo "<option value=" . $value['uid'] . ">" . $value['uname'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary mb-3" onclick="LoadData()">查询</button>
                </div>
            </div>
            <table class="table caption-top table-hover table-success text-primary table-sm text-center">
                <caption>研磨设备运行记录打印</caption>
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">设备编号</th>
                    <th scope="col">启动日期</th>
                    <th scope="col">启动时间</th>
                    <th scope="col">关闭时间</th>
                    <th scope="col">总时长(分钟)</th>
                    <th scope="col">产品编号</th>
                    <th scope="col">产品批号</th>
                    <th scope="col">当前状态</th>
                    <th scope="col">操作员</th>
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
                    } else if (userid == '') {
                        alert('请选择操作人员姓名');
                    } else {
                        $.ajax({
                            type: 'POST',
                            url: './ymsb_rds_send.php',
                            data: {
                                start_time: start_time,
                                stop_time: stop_time,
                                userid: userid
                            },
                            success: function (data) {
                                //console.log(data);
                                const a = data.split(' ');
                                //console.log(a);
                                let trStr = '';//动态拼接table
                                for (let i = 0; i < a.length - 1; i++) {
                                    trStr += '<tr>';
                                    trStr += '<td>' + (i + 1) + '</td>';
                                    trStr += '<td>' + JSON.parse(a[i]).machine_id + '</td>';
                                    trStr += '<td>' + JSON.parse(a[i]).register_date + '</td>';
                                    if ((JSON.parse(a[i]).register_time) == null) {
                                        trStr += '<td></td>';
                                    } else {
                                        trStr += '<td>' + JSON.parse(a[i]).register_time.slice(0, 5) + '</td>';
                                    }
                                    if ((JSON.parse(a[i]).shutdown_time) == null) {
                                        trStr += '<td></td>';
                                    } else {
                                        trStr += '<td>' + JSON.parse(a[i]).shutdown_time.slice(0, 5) + '</td>';
                                    }
                                    if ((JSON.parse(a[i]).total_duration) == null) {
                                        trStr += '<td></td>';
                                    } else {
                                        trStr += '<td>' + JSON.parse(a[i]).total_duration + '</td>';
                                    }
                                    trStr += '<td>' + JSON.parse(a[i]).pro_id + '</td>';
                                    trStr += '<td>' + JSON.parse(a[i]).bath_number + '</td>';
                                    trStr += '<td>' + JSON.parse(a[i]).machine_status + '</td>';
                                    trStr += '<td>' + JSON.parse(a[i]).uname + '</td>';
                                    trStr += '</tr>';
                                }
                                trStr += '<tr>';
                                trStr += '<td colspan=10><a href="./ymsb_rds_print.php?start_time=' + start_time + '&stop_time=' + stop_time + '&userid=' + userid + '" class="btn btn-outline-success" target="_blank">打印</a>';
                                trStr += '</td>';
                                trStr += '</tr>';
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
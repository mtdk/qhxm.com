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
                    <label class="form-control-plaintext">产品编号</label>
                </div>
                <div class="col-auto">
                    <input type="text" class="form-control" id="pro_id">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary mb-3" onclick="LoadData()">查询</button>
                </div>
            </div>
            <table class="table caption-top table-hover table-success text-primary table-sm text-center">
                <caption>工单信息</caption>
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">工单编号</th>
                    <th scope="col">产品编号</th>
                    <th scope="col">批号</th>
                    <th scope="col">备注</th>
                    <th scope="col">生产工艺</th>
                </tr>
                </thead>
                <tbody id="tbody">
                </tbody>
            </table>
            <script type="text/javascript">
                function LoadData() {
                    let pro_id = $('#pro_id').val();
                    if (pro_id == '') {
                        alert('请输入查询产品编号！');
                    } else {
                        $.ajax({
                            type: 'POST',
                            url: './work_order_send.php',
                            data: {
                                pro_id: pro_id
                            },
                            success: function (data) {
                                //console.log(data);
                                const a = data.split(' ');
                                //console.log(a);
                                let trStr = '';//动态拼接table
                                for (let i = 0; i < a.length - 1; i++) {
                                    trStr += '<tr>';
                                    trStr += '<td>' + JSON.parse(a[i]).id + '</td>';
                                    trStr += '<td>' + JSON.parse(a[i]).pro_id + '</td>';
                                    trStr += '<td>' + JSON.parse(a[i]).bath_number + '</td>';
                                    trStr += '<td>' + JSON.parse(a[i]).remarks + '</td>';
                                    if ((JSON.parse(a[i]).technology_target) == 'FS') {
                                        trStr += '<td>分散</td>';
                                    } else {
                                        trStr += '<td>研磨</td>';
                                    }
                                    trStr += '</tr>';
                                }
                                trStr += '<tr>';
                                trStr += '<td colspan=10><a href="#?" class="btn btn-outline-success" target="_blank">打印</a>';
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
<?php include __DIR__ . '/myFooter.php'; ?>
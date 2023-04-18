<?php
include __DIR__ . '/../user_session/user_session.php';
include __DIR__ . '/../user_session/userinfo_check.php';
include __DIR__ . '/../myHeader.php';
include __DIR__ . '/../myMenu.php';
?>
    <main class="flex-shrink-0">
        <div class="container mt-lg-auto">
            <table class="table caption-top table-hover table-success text-primary table-sm text-center">
                <caption>未关停分散设备运行记录</caption>
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">操作序号</th>
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
                <?php
                $stmt = $dbh->prepare("SELECT id,machine_id,register_date,register_time,pro_id,bath_number,machine_status,uname FROM fssbjl_show where machine_status='开机' order by register_date,register_time");
                $stmt->execute();
                $result = $stmt->fetchAll();
                var_dump($result);
                ?>
                </tbody>
            </table>
        </div>
    </main>
<?php
include __DIR__ . '/../myFooter.php';
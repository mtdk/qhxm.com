<?php
include __DIR__ . '/user_session/user_session.php';
include __DIR__ . '/user_session/login_state.php';
include __DIR__ . '/db/db.php';
include __DIR__ . '/myHeader.php';
include __DIR__ . '/myMenu.php';
?>
    <main class="flex-shrink-0">
        <div class="container mt-lg-auto">
            <table class="table caption-top table-hover table-success text-primary table-sm text-center">
                <caption><h4><span style="color: black">未关停研磨设备</span></h4></caption>
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
                $stmt = $dbh->prepare("SELECT id,machine_id,register_date,register_time,pro_id,bath_number,machine_status,uname FROM ymsbjl_show where machine_status='开机' order by register_date,register_time");
                $stmt->execute();
                $rows = $stmt->fetchAll();
                $length = count($rows);
                for ($i = 0; $i < $length; $i++) { ?>
                    <tr>
                        <th scope="row"><?php echo $i + 1; ?></th>
                        <td><?php echo $rows[$i]['id']; ?></td>
                        <td><?php echo $rows[$i]['machine_id']; ?></td>
                        <td><?php echo $rows[$i]['register_date']; ?></td>
                        <td><?php echo substr($rows[$i]['register_time'], 0, 5); ?></td>
                        <td><?php echo $rows[$i]['pro_id']; ?></td>
                        <td><?php echo $rows[$i]['bath_number']; ?></td>
                        <td><?php echo $rows[$i]['machine_status']; ?></td>
                        <td><?php echo $rows[$i]['uname']; ?></td>
                        <td><a class="btn btn-outline-success btn-sm"
                               style="--bs-btn-padding-y: .2rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .7rem;"
                               href="ymsbjl_down_check.php?id=<?php echo $rows[$i]['id']; ?>&uid=<?php echo $uid; ?>">关机</a>
                        </td>
                    </tr>
                <?php }
                ?>
                </tbody>
            </table>
        </div>
    </main>
<?php
include __DIR__ . '/myFooter.php';
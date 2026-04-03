<?php
include __DIR__ . '/user_session/user_session.php';
include __DIR__ . '/user_session/login_state.php';
include __DIR__ . '/db/db.php';
include __DIR__ . '/myHeader.php';
include __DIR__ . '/myMenu.php';

?>
    <main class="flex-shrink-0">
        <table class="table table-hover text-primary">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">维修单号</th>
                <th scope="col">设备名称</th>
                <th scope="col">领单时间</th>
                <th scope="col">领单人</th>
                <th scope="col">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $stmt = $dbh->prepare("select repair_id,device_name,repairer_name,repair_start_time from tb_device_repair_order where repair_status = 2");
            $stmt->execute();
            $rows = $stmt->fetchAll();
            $length = count($rows);
            for ($i = 0; $i < $length; $i++) { ?>
                <tr>
                    <th scope="row"><?php echo $i + 1; ?></th>
                    <td><?php echo $rows[$i]['repair_id']; ?></td>
                    <td><?php echo $rows[$i]['device_name']; ?></td>
                    <td><?php echo $rows[$i]['repairer_name']; ?></td>
                    <td><?php echo $rows[$i]['repair_start_time']; ?></td>
                    <td><a href="device_fix_submit.php?repair_id=<?php echo base64_encode($rows[$i]['repair_id']); ?>"
                           class="btn btn-outline-success">完成</a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </main>

<?php include __DIR__ . '/myFooter.php'; ?>
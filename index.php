<?php
include __DIR__ . '/user_session/user_session.php';
include __DIR__ . '/user_session/login_state.php';
include __DIR__ . '/db/db.php';
include __DIR__ . '/myHeader.php';
include __DIR__ . '/myMenu.php';
?>
    <!-- Begin page content -->
    <main class="flex-shrink-0">
        <div class="container">
            <h1 class="mt-5">欢迎登录本系统</h1>
            <div class="m-4">
                <?php
                $stmt = $dbh->prepare("SELECT count(*) AS rs FROM fqpfsbjl_show where machine_status='开机'");
                $stmt->execute();
                $result = $stmt->fetchAll()[0]['rs'];
                if ($result > 0):?>
                    <span class="badge text-bg-warning fs-4">当前有 <?php echo $result; ?> 台废气排放设备正在运行。</span><br>
                <?php endif;
                $stmt = $dbh->prepare("SELECT count(*) AS rs FROM fssbjl_show where machine_status='开机'");
                $stmt->execute();
                $result = $stmt->fetchAll()[0]['rs'];
                if ($result > 0):?>
                    <span class="badge text-bg-warning fs-4">当前有 <?php echo $result; ?> 台分散设备正在运行。</span><br>
                <?php endif;
                $stmt = $dbh->prepare("SELECT count(*) AS rs FROM ymsbjl_show where machine_status='开机'");
                $stmt->execute();
                $result = $stmt->fetchAll()[0]['rs'];
                if ($result > 0):?>
                    <span class="badge text-bg-warning fs-4">当前有 <?php echo $result; ?> 台研磨设备正在运行。</span><br>
                <?php endif;
                $stmt = $dbh->prepare("SELECT count(*) AS rs FROM bsjjl_show where machine_status='开机'");
                $stmt->execute();
                $result = $stmt->fetchAll()[0]['rs'];
                if ($result > 0):?>
                    <span class="badge text-bg-warning fs-4">当前有 <?php echo $result; ?> 台冰水设备正在运行。</span><br>
                <?php endif;
                $stmt = $dbh->prepare("SELECT count(*) AS rs FROM kyjsb_show where machine_status='开机'");
                $stmt->execute();
                $result = $stmt->fetchAll()[0]['rs'];
                if ($result > 0):?>
                    <span class="badge text-bg-warning fs-4">当前有 <?php echo $result; ?> 空压机设备正在运行。</span><br>
                <?php endif; ?>
                <?php
                $stmt = $dbh->prepare("SELECT count(*) AS rs FROM work_order WHERE work_state=0 and technology_target='FS'");
                $stmt->execute();
                $result = $stmt->fetchAll()[0]['rs'];
                if ($result > 0):?>
                    <span class="badge text-bg-danger fs-4">当前有 <?php echo $result;?> 张分散工单未领取。</span><br>
                <?php endif; ?>
                <?php
                $stmt = $dbh->prepare("SELECT count(*) AS rs FROM work_order WHERE work_state=0 and technology_target='YM'");
                $stmt->execute();
                $result = $stmt->fetchAll()[0]['rs'];
                if ($result > 0):?>
                    <span class="badge text-bg-danger fs-4">当前有 <?php echo $result;?> 张开粉工单未领取。</span><br>
                <?php endif; ?>
            </div>
            <?php
            $sth = $dbh->prepare("select device_id,progress,repair_msg from tb_device_repair_order where repair_status <> 4");
            $sth->execute();
            $rows = $sth->fetchAll();
            $length = count($rows);
            for ($i = 0; $i < $length; $i++) {
                ?>
                <div class="m-4">
                    <label><?php echo $rows[$i]['device_id']; ?> 维修进度&nbsp;&rarr;&nbsp;<?php echo $rows[$i]['repair_msg']; ?></label>
                    <div class="progress">
                        <div class="progress-bar" style="width: <?php echo $rows[$i]['progress']; ?>%"><?php echo $rows[$i]['progress']; ?>%</div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </main>
<?php include __DIR__ . '/myFooter.php'; ?>
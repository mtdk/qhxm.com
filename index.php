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
            <?php
            $stmt = $dbh->prepare("SELECT count(*) AS rs FROM fqpfsbjl_show where machine_state='开机'");
            $stmt->execute();
            $result = $stmt->fetchAll()[0]['rs'];
            if ($result > 0):?>
                <span class="badge text-bg-warning">当前有 <?php echo $result; ?> 台废气排放设备正在运行。</span><br>
            <?php endif;
            $stmt = $dbh->prepare("SELECT count(*) AS rs FROM fssbjl_show where machine_status='开机'");
            $stmt->execute();
            $result = $stmt->fetchAll()[0]['rs'];
            if ($result > 0):?>
                <span class="badge text-bg-warning">当前有 <?php echo $result; ?> 台分散设备正在运行。</span><br>
            <?php endif;
            $stmt = $dbh->prepare("SELECT count(*) AS rs FROM ymsbjl_show where machine_status='开机'");
            $stmt->execute();
            $result = $stmt->fetchAll()[0]['rs'];
            if ($result > 0):?>
                <span class="badge text-bg-warning">当前有 <?php echo $result; ?> 台研磨设备正在运行。</span><br>
            <?php endif;
            $stmt = $dbh->prepare("SELECT count(*) AS rs FROM bsjjl_show where machine_state='开机'");
            $stmt->execute();
            $result = $stmt->fetchAll()[0]['rs'];
            if ($result > 0):?>
                <span class="badge text-bg-warning">当前有 <?php echo $result; ?> 台冰水设备正在运行。</span><br>
            <?php endif;
            $stmt = $dbh->prepare("SELECT count(*) AS rs FROM kyjsb_show where machine_state='开机'");
            $stmt->execute();
            $result = $stmt->fetchAll()[0]['rs'];
            if ($result > 0):?>
                <span class="badge text-bg-warning">当前有 <?php echo $result; ?> 空压机设备正在运行。</span><br>
            <?php endif; ?>
        </div>
    </main>
<?php include __DIR__ . '/myFooter.php'; ?>
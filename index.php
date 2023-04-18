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
      <h1 class="mt-5">莫罕达斯·卡拉姆昌德·甘地(Mohandas Karamchand Gandhi)</h1>
      <p class="lead">甘地说：没有人格的学识、没有人性的科学、没有原则的政治、没有辛劳的财富、没有道德的商业、没有良知的享乐、没有牺牲的敬拜足够毁灭人类。
        <code class="small text-primary">甘地 1869 ~ 1948，著名的政治家、革命家、思想家，印度国父、最伟大的政治领袖之一</code>。
      </p>
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
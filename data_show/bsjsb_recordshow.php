<?php
include __DIR__ . '/../user_session/user_session.php';
include __DIR__ . '/../user_session/userinfo_check.php';
include __DIR__ . '/../db/db.php';
include __DIR__ . '/../myHeader.php';
include __DIR__ . '/../myMenu.php';

$rd = date('Y-m-d');
$query_condition = $_GET['condition'];

$sql = "select count(*) as total from bsjjl_show where register_date=?";
$stmt = $dbh->prepare($sql);
$stmt->bindValue(1, $rd);
$stmt->execute();
$total = $stmt->fetchAll()[0]['total'];

$pageNum = 20;
//$pageMax = ceil($total / $pageNum);

$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $pageNum;
$sql = "SELECT machine_id,register_date,register_time,shutdown_time,total_duration,machine_state,machine_status,uname FROM bsjjl_show where register_date=? order by register_date desc LIMIT ?,?";
$stmt = $dbh->prepare($sql);
$stmt->bindValue(1, $rd);
$stmt->bindValue(2, $offset, PDO::PARAM_INT);
$stmt->bindValue(3, $pageNum, PDO::PARAM_INT);
$stmt->execute();
$rows = $stmt->fetchAll();
$length = count($rows);
?>
  <main class="flex-shrink-0">
    <div class="container mt-lg-auto">
      <table class="table caption-top table-hover table-success text-primary table-sm text-center">
        <caption>冰水机运行记录查看</caption>
        <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">设备编号</th>
          <th scope="col">启动日期</th>
          <th scope="col">启动时间</th>
          <th scope="col">关闭时间</th>
          <th scope="col">总时长(分钟)</th>
          <th scope="col">当前状态</th>
          <th scope="col">设备状态</th>
          <th scope="col">操作员</th>
        </tr>
        </thead>
        <tbody>
        <?php for ($i = 0; $i < $length; $i++) { ?>
          <tr>
            <th scope="row"><?php echo $i + 1; ?></th>
            <td><?php echo $rows[$i]['machine_id']; ?></td>
            <td><?php echo $rows[$i]['register_date']; ?></td>
            <td><?php echo substr($rows[$i]['register_time'], 0, 5); ?></td>
            <td><?php echo substr($rows[$i]['shutdown_time'], 0, 5); ?></td>
            <td><?php echo $rows[$i]['total_duration']; ?></td>
            <td><?php echo $rows[$i]['machine_state']; ?></td>
            <td><?php echo $rows[$i]['machine_status']; ?></td>
            <td><?php echo $rows[$i]['uname']; ?></td>
          </tr>
        <?php } ?>
        </tbody>
      </table>
      <div class="row">
        <nav aria-label="Page navigation example">
          <?php if (ceil($total / $pageNum) > 0): ?>
            <ul class="pagination justify-content-center">
              <?php if ($page > 1): ?>
                <li class="page-item">
                  <a class="page-link" href="bsjsb_recordshow.php?page=<?php echo $page - 1 ?>"><span
                      aria-hidden="true">&laquo;</span></a>
                </li>
              <?php endif; ?>

              <?php if ($page > 3): ?>
                <li class="page-item"><a class="page-link" href="bsjsb_recordshow.php?page=1">1</a>
                </li>
                <li class="page-item"><span aria-hidden="true">......</span></li>
              <?php endif; ?>

              <?php if ($page - 2 > 0): ?>
                <li class="page-item">
                <a class="page-link"
                   href="bsjsb_recordshow.php?page=<?php echo $page - 2 ?>"><?php echo $page - 2 ?></a>
                </li><?php endif; ?>
              <?php if ($page - 1 > 0): ?>
                <li class="page-item">
                <a class="page-link"
                   href="bsjsb_recordshow.php?page=<?php echo $page - 1 ?>"><?php echo $page - 1 ?></a>
                </li><?php endif; ?>

              <li class="page-item">
                <a class="page-link"
                   href="bsjsb_recordshow.php?page=<?php echo $page ?>"><?php echo $page ?></a>
              </li>

              <?php if ($page + 1 < ceil($total / $pageNum) + 1): ?>
                <li class="page-item">
                <a class="page-link"
                   href="bsjsb_recordshow.php?page=<?php echo $page + 1 ?>"><?php echo $page + 1 ?></a>
                </li><?php endif; ?>
              <?php if ($page + 2 < ceil($total / $pageNum) + 1): ?>
                <li class="page-item">
                <a class="page-link"
                   href="bsjsb_recordshow.php?page=<?php echo $page + 2 ?>"><?php echo $page + 2 ?></a>
                </li><?php endif; ?>

              <?php if ($page < ceil($total / $pageNum) - 2): ?>
                <li class="page-item"><span aria-hidden="true">......</span></li>
                <li class="page-item">
                  <a class="page-link"
                     href="bsjsb_recordshow.php?page=<?php echo ceil($total / $pageNum) ?>"><?php echo ceil($total / $pageNum) ?></a>
                </li>
              <?php endif; ?>

              <?php if ($page < ceil($total / $pageNum)): ?>
                <li class="page-item">
                  <a class="page-link" href="bsjsb_recordshow.php?page=<?php echo $page + 1 ?>"><span
                      aria-hidden="true">&raquo;</span></a>
                </li>
              <?php endif; ?>
            </ul>
          <?php endif; ?>
        </nav>
      </div>
    </div>
  </main>
<?php
include __DIR__ . '/../myFooter.php';
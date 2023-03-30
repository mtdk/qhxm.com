<?php
include __DIR__ . '/../user_session/user_session.php';
include __DIR__ . '/../user_session/login_state.php';
include __DIR__ . '/../db/db.php';
include __DIR__ . '/../myHeader.php';
include __DIR__ . '/../myMenu.php';

//$stmt = $dbh->prepare("select uid,uname from usertb where uid NOT IN (select usersorganization.uid from usersorganization where organi_id<>100)");
//$stmt->execute();
//$rows = $stmt->fetchAll();
?>
  <main class="flex-shrink-0 mt-lg-5">
    <div class="container mt-lg-4">
      <?php
      /*
       * $total 存放记录总数
       * $pageNum 设置每页显示多少条记录
       * $maxPage 计算总页数(记录总数/每页显示记录数)ceil向上取整
       * $page 当前页码
       * $offset 记录偏移量(从哪一条记录开始)
       */
      $sql = "SELECT count(*) as total from usersorganization_view";
      $stmt = $dbh->prepare($sql);
      $stmt->execute();
      $total = $stmt->fetchAll()[0]['total'];
      $pageNum = 10;
      $maxPages = ceil($total / $pageNum);
      $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
      $offset = ($page - 1) * $pageNum;
      $sql = "SELECT uid,uname,organi_name,organi_id FROM usersorganization_view LIMIT ?,?";
      $stmt = $dbh->prepare($sql);
      $stmt->bindValue(1, $offset, PDO::PARAM_INT);
      $stmt->bindValue(2, $pageNum, PDO::PARAM_INT);
      $stmt->execute();
      $rows = $stmt->fetchAll();
      $length = count($rows);
      ?>
      <table class="table table-primary table-hover caption-top text-center">
        <caption class="text-primary">用户组织表</caption>
        <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">用户ID</th>
          <th scope="col">用户姓名</th>
          <th scope="col">组织名称</th>
          <th scope="col">修改关系</th>
        </tr>
        </thead>
        <tbody class="table-group-divider">
        <?php for ($i = 0; $i < $length; $i++) { ?>
          <tr>
            <th scope="row"><?php echo $i + 1; ?></th>
            <td><?php echo $rows[$i]['uid']; ?></td>
            <td><?php echo $rows[$i]['uname']; ?></td>
            <td><?php echo $rows[$i]['organi_name']; ?></td>
            <td><a class="btn btn-outline-success btn-sm" href="user_org_up_check.php?uid=<?php echo $rows[$i]['uid']; ?>">修改</a></td>
          </tr>
        <?php } ?>
        </tbody>
      </table>
      <div class="row">
        <div class="col-12">
          <nav aria-label="Page navigation example">
            <ul class="pagination">
              <?php for ($y = 1; $y <= $maxPages; $y++) : ?>
                <li class="page-item"><a class="page-link" href="user_organization.php?page=<?php echo $y; ?>"><?php echo $y; ?></a></li>
              <?php endfor; ?>
            </ul>
          </nav>
        </div>
      </div>
      <script>
        // Example starter JavaScript for disabling form submissions if there are invalid fields
        (() => {
          'use strict'

          // Fetch all the forms we want to apply custom Bootstrap validation styles to
          const forms = document.querySelectorAll('.needs-validation')

          // Loop over them and prevent submission
          Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
              if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
              }

              form.classList.add('was-validated')
            }, false)
          })
        })()
      </script>
    </div>
  </main>

<?php include __DIR__ . '/../myFooter.php'; ?>
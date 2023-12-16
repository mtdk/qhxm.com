<?php
include_once __DIR__ . '/admin_sessions/admin_session.php';
include_once __DIR__ . '/admin_sessions/admin_login_state.php';
include __DIR__ . '/adminHeader.php';
include __DIR__ . '/adminMenu.php';
include_once __DIR__ . '/../db/db.php';


$sql = "select count(*) as total from usertb";
$stmt = $dbh->prepare($sql);
$stmt->execute();
$total = $stmt->fetchAll()[0]['total'];
$pageNum = 5;
$maxPages = ceil($total / $pageNum);
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $pageNum;
$sql = "select uid,uname from usertb limit ?,?";
$stmt = $dbh->prepare($sql);
$stmt->bindValue(1, $offset, PDO::PARAM_INT);
$stmt->bindValue(2, $pageNum, PDO::PARAM_INT);
$stmt->execute();
$rows = $stmt->fetchAll();
$length = count($rows);
?>
    <main class="flex-shrink-0">
        <div class="container mt-lg-4">
            <table class="table table-primary table-hover caption-top text-center">
                <caption class="text-primary">用户信息表</caption>
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">用户ID</th>
                    <th scope="col">用户姓名</th>
                    <th scope="col">用户修改</th>
                    <th scope="col">用户密码重置</th>
                </tr>
                </thead>
                <tbody class="table-group-divider">
                <?php for ($i = 0; $i < $length; $i++) { ?>
                    <tr>
                        <th scope="row"><?php echo $i + 1; ?></th>
                        <td><?php echo $rows[$i]['uid']; ?></td>
                        <td><?php echo $rows[$i]['uname']; ?></td>
                        <td><a class="btn btn-outline-success btn-sm"
                               style="--bs-btn-padding-y: .2rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .7rem;"
                               href="user_info_set.php?uid=<?php echo base64_encode($rows[$i]['uid']); ?>">修改</a>
                        </td>
                        <td><a class="btn btn-outline-success btn-sm"
                               style="--bs-btn-padding-y: .2rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .7rem;"
                               href="user_pwd_reset.php?uid=<?php echo base64_encode($rows[$i]['uid']); ?>">密码重置</a>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            <div class="row">
                <div class="col-12">
                    <nav aria-label="Page navigation example">
                        <ul class="pagination">
                            <?php for ($y = 1; $y <= $maxPages; $y++) : ?>
                                <li class="page-item">
                                    <a class="page-link"
                                       href="user_info_query.php?page=<?php echo $y; ?>"><?php echo $y; ?></a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </main>
<?php include __DIR__ . '/adminFooter.php'; ?>
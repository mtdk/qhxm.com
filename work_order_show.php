<?php
include __DIR__ . '/user_session/user_session.php';
include __DIR__ . '/user_session/login_state.php';
include __DIR__ . '/db/db.php';
include __DIR__ . '/myHeader.php';
include __DIR__ . '/myMenu.php';
?>
    <main class="flex-shrink-0">
        <div class="container mt-lg-4">
            <table class="table table-hover text-primary">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">编号</th>
                    <th scope="col">批号</th>
                    <th scope="col">备注</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $sth = $dbh->prepare("select * from work_order where work_state = 0");
                $sth->execute();
                $rows = $sth->fetchAll();
                $length = count($rows);
                for ($i = 0; $i < $length; $i++) { ?>
                    <tr>
                        <th scope="row"><?php echo $i + 1; ?></th>
                        <td>
                            <a href="fssbjl.php?id=<?php echo $rows[$i]['id']; ?>&pro_id=<?php echo $rows[$i]['pro_id']; ?>&bath_number=<?php echo $rows[$i]['bath_number']; ?>&remarks=<?php echo $rows[$i]['remarks']; ?>"><?php echo $rows[$i]['pro_id']; ?></a>
                        </td>
                        <td><?php echo $rows[$i]['bath_number']; ?></td>
                        <td><?php echo $rows[$i]['remarks']; ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </main>
<?php include __DIR__ . '/myFooter.php'; ?>
<?php
include __DIR__ . '/user_session/user_session.php';
include __DIR__ . '/user_session/login_state.php';
include __DIR__ . '/db/db.php';
include __DIR__ . '/myHeader.php';
include __DIR__ . '/myMenu.php';
?>
    <main class="flex-shrink-0">
        <!-- 分散设备 -->
        <div class="container mt-lg-4">
            <table class="table table-hover text-primary">
                <thead>
                <tr>
                    <th colspan="5" class="text-center text-bg-danger">分散设备</th>
                </tr>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">设备编号</th>
                    <th scope="col">启动时间</th>
                    <th scope="col">当前状态</th>
                    <th scope="col">操作</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $rd = date('Y-m-d');
                $stmt = $dbh->prepare("SELECT id,machine_id,register_time,machine_status from fssbrecords where register_date=:register_date and uid=:uid and machine_status='开机'");
                $stmt->bindParam(':register_date', $rd);
                $stmt->bindParam(':uid', $uid);
                $stmt->execute();
                $rows = $stmt->fetchAll();
                $length = count($rows);
                if ($length > 0) :
                    for ($i = 0; $i < $length; $i++) { ?>
                        <tr>
                            <th scope="row"><?php echo $i + 1; ?></th>
                            <td><?php echo $rows[$i]['machine_id']; ?></td>
                            <td><?php echo substr($rows[$i]['register_time'], 0, 5); ?></td>
                            <td><?php echo $rows[$i]['machine_status']; ?></td>
                            <td>
                                <a href="fssbjl_down_check.php?id=<?php echo $rows[$i]['id']; ?>&uid=<?php echo $uid; ?>"
                                   class="btn btn-outline-success">关机</a>
                            </td>
                        </tr>
                    <?php } ?>
                <?php else: ?>
                    <tr>
                        <th scope="row" colspan="5" class="text-center">无记录</th>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <!-- 研磨设备 -->
        <div class="container mt-lg-4">
            <table class="table table-hover text-primary">
                <thead>
                <tr>
                    <th colspan="5" class="text-center text-bg-danger">研磨设备</th>
                </tr>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">设备编号</th>
                    <th scope="col">启动时间</th>
                    <th scope="col">当前状态</th>
                    <th scope="col">操作</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $rd = date('Y-m-d');
                $stmt = $dbh->prepare("SELECT id,machine_id,register_time,machine_status from ymsbrecords where register_date=:register_date and uid=:uid and machine_status='开机'");
                $stmt->bindParam(':register_date', $rd);
                $stmt->bindParam(':uid', $uid);
                $stmt->execute();
                $rows = $stmt->fetchAll();
                $length = count($rows);
                if ($length > 0) :
                    for ($i = 0; $i < $length; $i++) { ?>
                        <tr>
                            <th scope="row"><?php echo $i + 1; ?></th>
                            <td><?php echo $rows[$i]['machine_id']; ?></td>
                            <td><?php echo substr($rows[$i]['register_time'], 0, 5); ?></td>
                            <td><?php echo $rows[$i]['machine_status']; ?></td>
                            <td>
                                <a href="ymsbjl_down_check.php?id=<?php echo $rows[$i]['id']; ?>&uid=<?php echo $uid; ?>"
                                   class="btn btn-outline-success">关机</a>
                            </td>
                        </tr>
                    <?php } ?>
                <?php else: ?>
                    <tr>
                        <th scope="row" colspan="5" class="text-center">无记录</th>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <!-- 废气设备 -->
        <div class="container mt-lg-4">
            <table class="table table-hover text-primary">
                <thead>
                <tr>
                    <th colspan="5" class="text-center text-bg-secondary">废气设备</th>
                </tr>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">设备编号</th>
                    <th scope="col">启动时间</th>
                    <th scope="col">当前状态</th>
                    <th scope="col">操作</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $rd = date('Y-m-d');
                $stmt = $dbh->prepare("SELECT id,machine_id,register_time,machine_state from fqssrecords where register_date=:register_date and machine_state='开机'");
                $stmt->bindParam(':register_date', $rd);
                $stmt->execute();
                $rows = $stmt->fetchAll();
                $length = count($rows);
                if ($length > 0) :
                    for ($i = 0; $i < $length; $i++) { ?>
                        <tr>
                            <th scope="row"><?php echo $i + 1; ?></th>
                            <td><?php echo $rows[$i]['machine_id']; ?></td>
                            <td><?php echo substr($rows[$i]['register_time'], 0, 5); ?></td>
                            <td><?php echo $rows[$i]['machine_state']; ?></td>
                            <td><a href="fqpfsbjl_down_check.php?id=<?php echo $rows[$i]['id']; ?>"
                                   class="btn btn-outline-success">关机</a>
                            </td>
                        </tr>
                    <?php } ?>
                <?php else: ?>
                    <tr>
                        <th scope="row" colspan="5" class="text-center">无记录</th>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <!-- 空压设备 -->
        <div class="container mt-lg-4">
            <table class="table table-hover text-primary">
                <thead>
                <tr>
                    <th colspan="5" class="text-center text-bg-secondary">空压设备</th>
                </tr>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">设备编号</th>
                    <th scope="col">启动时间</th>
                    <th scope="col">当前状态</th>
                    <th scope="col">操作</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $rd = date('Y-m-d');
                $stmt = $dbh->prepare("SELECT id,machine_id,register_time,machine_state from kyjrecords where register_date=:register_date and machine_state='开机'");
                $stmt->bindParam(':register_date', $rd);
                $stmt->execute();
                $rows = $stmt->fetchAll();
                $length = count($rows);
                if ($length > 0) :
                    for ($i = 0; $i < $length; $i++) { ?>
                        <tr>
                            <th scope="row"><?php echo $i + 1; ?></th>
                            <td><?php echo $rows[$i]['machine_id']; ?></td>
                            <td><?php echo substr($rows[$i]['register_time'], 0, 5); ?></td>
                            <td><?php echo $rows[$i]['machine_state']; ?></td>
                            <td><a href="kyjsbjl_down_check.php?id=<?php echo $rows[$i]['id']; ?>"
                                   class="btn btn-outline-success">关机</a>
                            </td>
                        </tr>
                    <?php } ?>
                <?php else: ?>
                    <tr>
                        <th scope="row" colspan="5" class="text-center">无记录</th>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <!-- 冰水设备 -->
        <div class="container mt-lg-4">
            <table class="table table-hover text-primary">
                <thead>
                <tr>
                    <th colspan="5" class="text-center text-bg-secondary">冰水设备</th>
                </tr>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">设备编号</th>
                    <th scope="col">启动时间</th>
                    <th scope="col">当前状态</th>
                    <th scope="col">操作</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $rd = date('Y-m-d');
                $stmt = $dbh->prepare("SELECT id,machine_id,register_time,machine_state from bsjrecords where register_date=:register_date and machine_state='开机'");
                $stmt->bindParam(':register_date', $rd);
                $stmt->execute();
                $rows = $stmt->fetchAll();
                $length = count($rows);
                if ($length > 0) :
                    for ($i = 0; $i < $length; $i++) { ?>
                        <tr>
                            <th scope="row"><?php echo $i + 1; ?></th>
                            <td><?php echo $rows[$i]['machine_id']; ?></td>
                            <td><?php echo substr($rows[$i]['register_time'], 0, 5); ?></td>
                            <td><?php echo $rows[$i]['machine_state']; ?></td>
                            <td><a href="bsjsbjl_down_check.php?id=<?php echo $rows[$i]['id']; ?>"
                                   class="btn btn-outline-success">关机</a>
                            </td>
                        </tr>
                    <?php } ?>
                <?php else: ?>
                    <tr>
                        <th scope="row" colspan="5" class="text-center">无记录</th>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

<?php include __DIR__ . '/myFooter.php'; ?>
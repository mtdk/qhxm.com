<?php
include __DIR__ . '/user_session/user_session.php';
include __DIR__ . '/user_session/login_state.php';
include __DIR__ . '/db/db.php';
include __DIR__ . '/myHeader.php';
include __DIR__ . '/myMenu.php';
?>
    <main class="flex-shrink-0">
        <div class="accordion" id="accordionExample">
            <!-- 分散工单 -->
            <div class="card">
                <?php
                $sth = $dbh->prepare("select * from work_order where work_state = 0");
                $sth->execute();
                $rows = $sth->fetchAll();
                $length = count($rows);
                ?>
                <div class="card-header" id="headingOne">
                    <h2 class="mb-0">
                        <button class="btn btn-block text-left" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                            分散工单&nbsp;<span class="badge text-bg-info"><?php echo $length ?: 0; ?></span>
                        </button>
                    </h2>
                </div>
                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                     data-parent="#accordionExample">
                    <div class="card-body">
                        <table class="table table-hover text-sm-center text-primary">
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
                            for ($i = 0; $i < $length; $i++) { ?>
                                <tr>
                                    <th scope="row"><?php echo $i + 1; ?></th>
                                    <td>
                                        <a href="fssbjl.php?id=<?php echo base64_encode($rows[$i]['id']); ?>"><?php echo $rows[$i]['pro_id']; ?></a>
                                    </td>
                                    <td><?php echo $rows[$i]['bath_number']; ?></td>
                                    <td><?php echo $rows[$i]['remarks']; ?></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- 研磨工单 -->
            <div class="card">
                <?php
                $sth = $dbh->prepare("select * from work_order where work_state = 2 and technology_target = 'YM'");
                $sth->execute();
                $rows = $sth->fetchAll();
                $length = count($rows);
                ?>
                <div class="card-header" id="headingTwo">
                    <h2 class="mb-0">
                        <button class="btn btn-block text-left collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            研磨工单&nbsp;<span class="badge text-bg-info"><?php echo $length ?: 0; ?></span>
                        </button>
                    </h2>
                </div>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                    <div class="card-body">
                        <table class="table table-hover text-sm-center text-primary">
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
                            for ($i = 0; $i < $length; $i++) { ?>
                                <tr>
                                    <th scope="row"><?php echo $i + 1; ?></th>
                                    <td>
                                        <a href="ymsbjl.php?id=<?php echo base64_encode($rows[$i]['id']); ?>"><?php echo $rows[$i]['pro_id']; ?></a>
                                    </td>
                                    <td><?php echo $rows[$i]['bath_number']; ?></td>
                                    <td><?php echo $rows[$i]['remarks']; ?></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
<?php include __DIR__ . '/myFooter.php'; ?>
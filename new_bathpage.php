<?php
//include __DIR__ . '/user_session/user_session.php';
//include __DIR__ . '/user_session/login_state.php';
include __DIR__ . '/db/db.php';
include __DIR__ . '/myHeader.php';
include __DIR__ . '/myMenu.php';
$uid = '039948SJBF441J28';
?>
    <main class="flex-shrink-0">
        <div class="container mt-lg-4">
            <div class="accordion accordion-flush" id="accordionFlushExample">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="flush-headingOne">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#flush-collapseOne" aria-expanded="false"
                                aria-controls="flush-collapseOne">
                            生产批号输入
                        </button>
                    </h2>
                    <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne"
                         data-bs-parent="#accordionFlushExample" style="">
                        <div class="accordion-body">
                            <form class="row g-3 needs-validation" novalidate=""
                                  action="/new_bathnumber/new_bathnb_save.php" method="post">
                                <div class="input-group">
                                    <?php $bath_num = date('Ymd'); ?>
                                    <input class="form-control" name="bath_num" type="text"
                                           value="<?php echo $bath_num ?>"
                                           readonly>
                                    <input type="text" class="form-control" placeholder="生产序号" name="number"
                                           aria-describedby="button-addon1" minlength="3" maxlength="3" required>
                                    <button class="btn btn-outline-success" type="submit" name="button-addon1">
                                        提交保存
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="flush-headingTwo">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#flush-collapseTwo" aria-expanded="false"
                                aria-controls="flush-collapseTwo">
                            已登记的批号
                        </button>
                    </h2>
                    <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo"
                         data-bs-parent="#accordionFlushExample" style="">
                        <div class="accordion-body">
                            <div class="container mt-lg-4">
                                <table class="table table-hover text-primary table-sm">
                                    <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">生产批号</th>
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
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapseThree" aria-expanded="false"
                                    aria-controls="flush-collapseThree">
                                已关闭的批号
                            </button>
                        </h2>
                        <div id="flush-collapseThree" class="accordion-collapse collapse"
                             aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample" style="">
                            <div class="accordion-body">
                                <div class="container mt-lg-4">
                                    <table class="table table-hover text-primary table-sm">
                                        <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">生产批号</th>
                                            <th scope="col">当前状态</th>
                                            <th scope="col">操作</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <th scope="row"></th>
                                            <td></td>
                                            <td></td>
                                            <td><a href="#"
                                                   class="btn btn-outline-success">关闭</a>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </main>
    <script>
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
<?php include __DIR__ . '/myFooter.php'; ?>
<?php
include __DIR__ . '/../user_session/user_session.php';
include __DIR__ . '/../user_session/login_state.php';
include __DIR__ . '/../db/db.php';
include __DIR__ . '/../myHeader.php';
include __DIR__ . '/../myMenu.php';

$stmt = $dbh->prepare("select uid,uname from usertb");
$stmt->execute();
$rows = $stmt->fetchAll();
?>
<main class="flex-shrink-0">
    <div class="container mt-lg-4">
        <div class="row">
            <div class="text-center mb-2">
                <h2>安全检查区域设置</h2>
            </div>
        </div>
        <form class="row g-3 needs-validation" novalidate="" action="user_area_save.php" method="post">
            <div class="col-sm-2">
                <label for="userid" class="form-label">人员选择</label>
                <select name="userid" class="form-select" id="userid" required>
                    <option selected disabled value="">请选择...</option>
                    <?php
                    foreach ($rows as $key => $value) {
                        echo "<option value=" . $value['uid'] . ">" . $value['uname'] . "</option>";
                    }
                    ?>
                </select>
                <div class="invalid-feedback">
                    请选择人员...
                </div>
            </div>
            <?php
            $stmt = $dbh->prepare("select areainsp_id,areainsp_name from area_inspection");
            $stmt->execute();
            $rows = $stmt->fetchAll();
            ?>
            <div class="col-sm-2">
                <label for="areainsp_id" class="form-label">请选择区域</label>
                <select name="areainsp_id" class="form-select" id="areainsp_id" required>
                    <option selected disabled value="">请选择...</option>
                    <?php
                    foreach ($rows as $key => $value) {
                        echo "<option value=" . $value['areainsp_id'] . ">" . $value['areainsp_name'] . "</option>";
                    }
                    ?>
                </select>
                <div class="invalid-feedback">
                    请选择区域...
                </div>
            </div>
            <div class="col-12">
                <button class="btn btn-primary" type="submit">提&nbsp;交&nbsp;保&nbsp;存</button>
            </div>
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
        </form>
        <div class="container mt-2">
            <?php
            $stmt = $dbh->prepare("SELECT uid,uname,areainsp_id,areainsp_name from users_areainspection_view");
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
                    <th scope="col">区域编号</th>
                    <th scope="col">区域名称</th>
                    <th scope="col">修改关系</th>
                </tr>
                </thead>
                <tbody class="table-group-divider">
                <?php for ($i = 0; $i < $length; $i++) { ?>
                    <tr>
                        <th scope="row"><?php echo $i + 1; ?></th>
                        <td><?php echo $rows[$i]['uid']; ?></td>
                        <td><?php echo $rows[$i]['uname']; ?></td>
                        <td><?php echo $rows[$i]['areainsp_id']; ?></td>
                        <td><?php echo $rows[$i]['areainsp_name']; ?></td>
                        <td><a class="btn btn-outline-success btn-sm" href="#?uid=<?php echo $rows[$i]['uid']; ?>">修改</a></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</main>
<?php include __DIR__ . '/../myFooter.php'; ?>

<?php
include __DIR__ . '/admin_sessions/admin_session.php';
include __DIR__ . '/admin_sessions/admin_login_state.php';
include __DIR__ . '/adminHeader.php';
include __DIR__ . '/adminMenu.php';
?>
    <main class="flex-shrink-0">
        <div class="container mt-lg-4">
            <div class="row">
                <div class="text-center text-primary mb-2">
                    <h2>管理员密码修改</h2>
                </div>
            </div>
            <form class="row g-3 needs-validation" novalidate action="admin_pwd_save.php" method="post">
                <div class="col-md-4">
                    <label for="admin_uid" class="form-label">用户ID</label>
                    <input type="text" id="admin_uid" name="admin_uid" class="form-control" value="<?php echo $admin_uid; ?>"
                           readonly required>
                    <div class="invalid-feedback">
                        用户ID不能为空
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="old_passwd" class="form-label">原密码</label>
                    <input type="password" id="old_passwd" name="old_passwd" class="form-control"
                           placeholder="请输入您的原始密码" required>
                    <div class="invalid-feedback">
                        原密码不能为空
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="new_passwd" class="form-label">新密码</label>
                    <input type="password" id="new_passwd" name="new_passwd" class="form-control"
                           placeholder="请输入您的6~10位新密码" minlength="6" required>
                    <div class="invalid-feedback">
                        1、新密码不能为空；
                        2、密码长度必须至少为6位。
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="replay_passwd" class="form-label">确认新密码</label>
                    <input type="password" id="replay_passwd" name="replay_passwd" class="form-control"
                           placeholder="请再次输入您的新密码" minlength="6" required>
                    <div class="invalid-feedback">
                        1、确认密码不能为空；
                        2、密码长度必须至少为6位。
                    </div>
                </div>
                <div class="col-12">
                    <button class="btn btn-primary" type="submit">保存</button>
                    <a class="btn btn-primary" href="./admin_index.php">取消</a>
                </div>
            </form>
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
<?php include __DIR__ . '/adminFooter.php'; ?>
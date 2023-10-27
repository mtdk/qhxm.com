<!doctype html>
<html lang="zh-CN">

<head>
    <?php
    include __DIR__ .'/myHeader.php';
    function getUserID()
    {
        $microtime = substr(microtime(true), strpos(microtime(true), ".") + 1);
        $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $userid = "";
        for ($i = 0; $i < 6; $i++) {
            $userid .= $chars[mt_rand(0, strlen($chars))];
        }

        return $microtime . strtoupper(base_convert(time() - 1420070400, 10, 36)) . $userid;
    }

    $userid = getUserID();
    ?>
</head>

<body>
<div class="container">
    <div class="row">
        <div class="col-6 offset-3">
            <div class="form-group text-primary text-center mt-5">
                <h3>新用户注册</h3>
            </div>
            <form action="register_save.php" method="post">
                <div class="form-group">
                    <label>用户名</label>
                    <input type="text" name="userid" class="form-control" value="<?php echo $userid; ?>" readonly
                           required>
                </div>
                <div class="form-group">
                    <label>用户姓名</label>
                    <input type="text" name="username" class="form-control" placeholder="请输入您的用户名" required>
                </div>
                <div class="form-group">
                    <label>密码</label>
                    <input type="password" name="userpwd" class="form-control" placeholder="请输入您的密码"
                           minlength="6" maxlength="16" required>
                </div>
                <div class="form-group">
                    <label>确认密码</label>
                    <input type="password" name="reuserpwd" class="form-control" minlength="6" maxlength="16"
                           placeholder="请再次输入您的密码"
                           required>
                </div>
                <button type="submit" class="btn btn-primary mt-1">注册</button>
            </form>
        </div>
    </div>
</div>
</body>

</html>
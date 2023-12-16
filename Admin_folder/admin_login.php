<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Signin Template · Bootstrap v5.2</title>

    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="../bootstrap/css/sign-in/sign-in.css" rel="stylesheet">
</head>
<body class="text-center">

<main class="form-signin w-100 m-auto">
    <form method="post" action="/Admin_folder/admin_login_check.php">
        <img class="mb-4" src="../brand/bootstrap-logo.svg" alt="" width="72" height="57">
        <h1 class="h3 mb-3 fw-normal">请&nbsp;登&nbsp;录&nbsp;系&nbsp;统</h1>

        <div class="form-floating">
            <input type="text" name="admin_uid" class="form-control" id="admin_uid" placeholder="用户名" required>
            <label for="admin_uid">用户名</label>
        </div>
        <div class="form-floating">
            <input type="password" name="admin_pwd" class="form-control" id="admin_pwd" placeholder="Password" required>
            <label for="admin_pwd">密码</label>
        </div>
        <div class="form-floating">
            <button class="w-100 btn btn-lg btn-primary" type="submit">登&nbsp;录</button>
        </div>
        <div class="form-floating mt-3">
            <a href="../login.php" class="w-100 btn btn-lg btn-primary">返&nbsp;回</a>
        </div>
        <p class="mt-5 mb-3 text-muted">&copy;Xmtdk 2017–2025</p>
    </form>
</main>
<script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html>
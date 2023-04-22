<?php include __DIR__ . '/db/db.php';
$stmt = $dbh->prepare("select uid,uname from usertb");
$stmt->execute();
$rows = $stmt->fetchAll();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Signin Template · Bootstrap v5.2</title>

    <link href="./css/bootstrap.min.css" rel="stylesheet">
    <script>
        function UsCheck(str) {
            var xmlhttp;
            if (str.length === 0) {
                document.getElementById("upassword").innerHTML = "";
                return;
            }
            if (window.XMLHttpRequest) {
                // IE7+, Firefox, Chrome, Opera, Safari 浏览器执行代码
                xmlhttp = new XMLHttpRequest();
            } else {
                // IE6, IE5 浏览器执行代码
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                    if (xmlhttp.responseText !== '') {
                        document.getElementById("upassword").value = xmlhttp.responseText;
                        document.getElementById("remember_me").checked = true;
                    } else {
                        document.getElementById("upassword").value = "";
                        document.getElementById("remember_me").checked = false;
                    }
                }
            }
            xmlhttp.open("POST", "/user_session/userinfo_check.php", true);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send("uid=" + str);
        }
    </script>
    <!-- Custom styles for this template -->
    <link href="./css/custom_styles/signin.css" rel="stylesheet">
</head>
<body class="text-center">

<main class="form-signin w-100 m-auto">
    <form method="post" action="login_check.php">
        <img class="mb-4" src="./brand/bootstrap-logo.svg" alt="" width="72" height="57">
        <h1 class="h3 mb-3 fw-normal">请&nbsp;登&nbsp;录&nbsp;系&nbsp;统</h1>

        <div class="form-floating">
            <select class="form-select" id="uid" name="uid"
                    onchange="UsCheck(this.options[this.options.selectedIndex].value)" required>
                <option selected disabled value="">请选择您的姓名...</option>
                <?php
                foreach ($rows as $key => $value) {
                    echo "<option value=" . $value['uid'] . ">" . $value['uname'] . "</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-floating">
            <input type="password" name="upassword" class="form-control" id="upassword"
                   placeholder="Password" required>
            <label for="upassword">密码</label>
        </div>
        <div class="checkbox mb-3">
            <label>
                <input type="checkbox" value="0" name="remember_me" id="remember_me"> 记住我
            </label>
        </div>
        <div class="form-floating">
            <button class="w-100 btn btn-lg btn-primary" type="submit">登&nbsp;录</button>
        </div>
        <div class="form-floating mt-3">
            <a href="usersinfo/register.php" class="w-100 btn btn-lg btn-primary">注&nbsp;册</a>
        </div>
        <p class="mt-5 mb-3 text-muted">&copy;Xmtdk 2017–2025</p>
    </form>
</main>
</body>
</html>
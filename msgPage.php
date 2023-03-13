<?php
include __DIR__ . '/user_session/user_session.php';
?>
<!doctype html>
<html lang="en" class="h-100">
<head>
    <?php include "myHeader.php" ?>
</head>
<body class="d-flex flex-column h-100">
<!-- Begin page content -->
<main class="flex-shrink-0">
    <div class="container text-primary text-center">
        <h1 class="mt-5">
            <?php
            header('refresh:3;url=' . $url);
            echo $msg;
            ?>
        </h1>
    </div>
</main>
</body>
</html>
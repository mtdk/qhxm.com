<?php
include __DIR__ . '/user_session/user_session.php';
include __DIR__ . '/myHeader.php';
?>
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
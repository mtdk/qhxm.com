<?php
include __DIR__ . '/admin_sessions/admin_session.php';
include __DIR__ . '/admin_sessions/admin_login_state.php';
include __DIR__ . '/adminHeader.php';
include __DIR__ . '/adminMenu.php';
// 登录成功后清除消息页中的$_SESSION['url']值
$_SESSION['url']=null;
?>
    <!-- Begin page content -->
    <h1 class="text-primary">Hello, world!</h1>
    <div class="p-3 text-primary-emphasis bg-primary-subtle border border-primary-subtle rounded-3">
        Example element with utilities
    </div>
    <main class="flex-shrink-0">
        <div class="container mt-lg-4">

        </div>
    </main>
<?php include __DIR__ . '/adminFooter.php'; ?>
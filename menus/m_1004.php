<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="../index.php">系&nbsp;统&nbsp;菜&nbsp;单</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse"
            aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
      <ul class="navbar-nav me-auto mb-2 mb-md-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="../index.php">系统首页</a>
        </li>
        <li class="nav-item dropdown">
          <button class="btn btn-dark dropdown-toggle" data-bs-toggle="dropdown"
                  aria-expanded="false">
            设备记录打印
          </button>
          <ul class="dropdown-menu dropdown-menu-dark">
            <li><a class="dropdown-item" href="/data_print/fssb_rds_query.php">分散设备记录打印</a></li>
            <li><a class="dropdown-item" href="/data_print/ymsb_rds_query.php">研磨设备记录打印</a></li>
            <li><a class="dropdown-item" href="/data_print/fqsb_rds_query.php">废气设备记录打印</a></li>
            <li><a class="dropdown-item" href="/data_print/bsjsb_rds_query.php">冰水机记录打印</a></li>
            <li><a class="dropdown-item" href="/data_print/kyjsb_rds_query.php">空压机记录打印</a></li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link text-light" href="/usersinfo/user_info_up.php">用户信息修改</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-light" href="/usersinfo/user_pwd_up.php">密码修改</a>
        </li>
      </ul>
      <a href="../logout.php" class="d-flex btn btn-outline-success">退出</a>
    </div>
  </div>
</nav>
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
          <button class="btn btn-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            设备运行登记
          </button>
          <ul class="dropdown-menu dropdown-menu-dark">
            <li><a class="dropdown-item" href="../fssbjl.php">分散设备登记</a></li>
            <li><a class="dropdown-item" href="../ymsbjl.php">研磨设备登记</a></li>
            <li><a class="dropdown-item" href="../fqpfsbjl.php">废气设备登记</a></li>
            <li><a class="dropdown-item" href="../bsjsbjl.php">冰水机登记</a></li>
            <li><a class="dropdown-item" href="../kyjsbjl.php">空压机登记</a></li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <button class="btn btn-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            设备记录查看
          </button>
          <ul class="dropdown-menu dropdown-menu-dark">
            <li><a class="dropdown-item" href="/data_show/fssb_rds_show_mob.php">分散设备记录查看(手机)</a></li>
            <li><a class="dropdown-item" href="/data_show/ymsb_rds_show_mob.php">研磨设备记录查看(手机)</a></li>
            <li><a class="dropdown-item" href="/data_show/fqpfsb_rds_show_mob.php">废气设备记录查看(手机)</a></li>
            <li><a class="dropdown-item" href="/data_show/bsjsb_rds_show_mob.php">冰水机记录查看(手机)</a></li>
            <li><a class="dropdown-item" href="/data_show/kyjsb_rds_show_mob.php">空压机记录查看(手机)</a></li>
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
<header>
  <!-- Fixed navbar -->
  <?php
  /**
   * uorg_id  用户组织部门编号
   * 一般用户（无） 100
   * 管理员  1000
   * 行政部  1001
   * 生产/技术部 1002
   * 仓储部  1003
   * 设备部  1004
   * 采购部  1005
   */
  if ($_SESSION['uorg_id'] == 100) {
    include __DIR__ . '/menus/m_100.php';
  } elseif ($_SESSION['uorg_id'] == 1000) {
    include __DIR__ . '/menus/m_1000.php';
  } elseif ($_SESSION['uorg_id'] == 1001) {
    include __DIR__ . '/menus/m_100.php';
  } elseif ($_SESSION['uorg_id'] == 1002) {
    include __DIR__ . '/menus/m_1002.php';
  } elseif ($_SESSION['uorg_id'] == 1003) {
    include __DIR__ . '/menus/m_100.php';
  } elseif ($_SESSION['uorg_id'] == 1004) {
    include __DIR__ . '/menus/m_1004.php';
  } elseif ($_SESSION['uorg_id'] == 1005) {
    include __DIR__ . '/menus/m_100.php';
  } else {
    include __DIR__ . '/menus/m_100.php';
  }
  ?>
</header>
<?php
include __DIR__ . '/../user_session/user_session.php';
include __DIR__ . '/../user_session/login_state.php';
//include __DIR__ . '/../db/db.php';
include __DIR__ . '/../myHeader.php';
include __DIR__ . '/../myMenu.php';
?>
<main class="flex-shrink-0">
  <div class="container mt-lg-4">
    <div class="jumbotron">
      <h1 class="display-4">冰水机日常检查表</h1>
      <p class="lead">冰水机检查表共6项检查内容，6项内容均正常时请选着“正常”选项；任意一项异常，请选择“异常”选项。</p>
      <hr class="my-4">
    </div>
    <div class="row">
      <div class="col-sm-2 badge text-dark text-start">
        <h5>1、外观</h5>
      </div>
      <div class="col-sm-2 badge text-dark text-start">
        <h5>2、设备显示屏</h5>
      </div>
      <div class="col-sm-2 badge text-dark text-start">
        <h5>3、电源开关</h5>
      </div>
      <div class="col-sm-2 badge text-dark text-start">
        <h5>4、进水阀</h5>
      </div>
      <div class="col-sm-2 badge text-dark text-start">
        <h5>5、出水阀</h5>
      </div>
      <div class="col-sm-2 badge text-dark text-start">
        <h5>6、压缩制冷系统</h5>
      </div>
    </div>
    <hr class="my-4">
    <form action="#">
      <div class="form-check">
        <input type="radio" class="form-check-input" id="validationFormCheck2" name="radio-stacked" onchange="closeTextarea()" required>
        <label class="form-check-label" for="validationFormCheck2">正常</label>
      </div>
      <div class="form-check mb-3">
        <input type="radio" class="form-check-input" id="validationFormCheck3" name="radio-stacked" onchange="showTextarea()" required>
        <label class="form-check-label" for="validationFormCheck3">异常</label>
        <div class="invalid-feedback">More example invalid feedback text</div>
      </div>
      <div class="mb-3">
        <textarea class="form-control" id="validationTextarea" placeholder="异常描述" required></textarea>
        <div class="invalid-feedback">
          异常描述
        </div>
      </div>
      <div class="mb-3">
        <button class="btn btn-primary" type="submit">Submit form</button>
      </div>
    </form>
  </div>
  <script>
    function closeTextarea() {
      $('#validationTextarea').disable().hide();
    }

    function showTextarea() {
      $('#validationTextarea').show();
    }
  </script>
</main>
<?php
include __DIR__ . '/../user_session/user_session.php';
include __DIR__ . '/../user_session/login_state.php';
include __DIR__ . '/../db/db.php';
include __DIR__ . '/../myHeader.php';
include __DIR__ . '/../myMenu.php';


?>
    <main class="flex-shrink-0">
        <div class="container mt-lg-4">
            <div class="row">
                <div class="text-center mb-2">
                    <h2>灭火器检查表</h2>
                </div>
            </div>
            <form class="row g-3 needs-validation" novalidate="" enctype="multipart/form-data"
                  action="mhq_check_save.php" method="post">
                <div class="col-sm-2">
                    <label for="validationTime" class="form-label">时间选择</label>
                    <input type="time" class="form-control" id="validationTime" name="register_time"
                           value="<?php echo date('H:i'); ?>" required>
                    <div class="invalid-feedback">
                        请选择时间...
                    </div>
                </div>
                <div class="col-sm-2">
                    <label for="validationDatetime" class="form-label">日期选择</label>
                    <input type="text" class="form-control" id="validationDatetime" value="<?php echo date('Y-m-d'); ?>"
                           name="register_date" readonly required>
                    <div class="invalid-feedback">
                        请选择日期...
                    </div>
                </div>
                <div class="col-sm-2">
                    <label for="validationProductid" class="form-label">产品编号</label>
                    <input type="text" class="form-control" id="validationProductid" maxlength="10" name="pro_id"
                           required>
                    <div class="invalid-feedback">
                        请输入产品编号...！
                    </div>
                </div>
                <div class="col-sm-2">
                    <label for="validationBathnumber" class="form-label">批号</label>
                    <input type="text" class="form-control" id="validationBathnumber" value="<?php echo date('Ymd'); ?>"
                           name="bath_number" minlength="11" maxlength="11" required>
                    <div class="invalid-feedback">
                        请输入批号...！
                    </div>
                </div>
                <div class="mb-3">
                    <input type="file" name="image_upload_a" accept="image/*" capture="environment" class="form-control"
                           aria-label="file example" required>
                    <div class="invalid-feedback">Example invalid form file feedback</div>
                </div>
                <div class="mb-3">
                    <input type="file" name="image_upload_b" accept="image/*" capture="environment" class="form-control"
                           aria-label="file example" required>
                    <div class="invalid-feedback">Example invalid form file feedback</div>
                </div>
                <div class="mb-3" id="app">
                    <div id="signatureparent">
                        <div id="signature" style="border: 1px solid cornflowerblue"></div>
                        <br>
                        <button type="button" class="btn btn-primary btn-block" id="save">提&nbsp;交&nbsp;保&nbsp;存</button>
                        <button type="button" class="btn btn-primary btn-block" id="clear">清&nbsp;除</button>
                    </div>
                </div>
                <script>
                    (() => {
                        'use strict'
                        // Fetch all the forms we want to apply custom Bootstrap validation styles to
                        const forms = document.querySelectorAll('.needs-validation')

                        // Loop over them and prevent submission
                        Array.from(forms).forEach(form => {
                            form.addEventListener('submit', event => {
                                if (!form.checkValidity()) {
                                    event.preventDefault()
                                    event.stopPropagation()
                                }

                                form.classList.add('was-validated')
                            }, false)
                        })
                    })()
                </script>
            </form>
            <script src="../js/3.6.6/jquery.js"></script>
            <script src="../js/jSignature.min.js"></script>
            <script type="text/javascript">
                let param = {
                    width: '100%',
                    height: '300px',
                    cssclass: 'zx11',
                    UndoButton: true,
                    signatureLine: false,
                    lineWidth: '3'
                };
                $('#signature').jSignature(param);

                $('#clear').click(function () {
                    $('#signature').jSignature('reset');
                });

                $('#save').click(function () {
                    if ($('#signature').jSignature('getData', 'native').length === 0) {
                        alert('请签名后再提交！');
                        return;
                    }
                    let con = confirm('提交后不可更改，确认提交签名？');
                    if (con === false) return;

                    let datapair = $('#signature').jSignature('getData', 'image');
                    let i = new Image();
                    i.src = 'data:' + datapair[0] + ',' + datapair[1];
                    console.log(datapair[0]);
                    i.image = datapair[1];
                    console.log(i.image);
                });

                // dataURLtoFile:function (dataurl){
                //     let arr = dataurl.split(','),
                //         mime=arr[0].match(/:(.*?);/)[1],
                //         bstr=atob(arr[1]),
                //         n=bstr.length,
                //         u8arr=new Uint8Array(n),
                //         while(n--){
                //         u8arr[n]=bstr.charCodeAt(n);
                //     }
                //     return new Blob([u8arr],{type:mime});
                // },
                // blobToFile:function(theBlob,fileName){
                //   theBlob.lastModifiedDate=new Date();
                //   theBlob.name=fileName;
                //   return theBlob;
                // },
                //
                // let blob=dataURLtoBlob(base64Data);
                // let file=blobToFile(blob,imgName);
            </script>
        </div>
    </main>

<?php include __DIR__ . '/../myFooter.php'; ?>
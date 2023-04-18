<?php
include __DIR__ . '/../myHeader.php';
include __DIR__ . '/../myMenu.php';
?>
    <main class="flex-shrink-0">
        <div class="container">
            <div id="signatureparent">
                <div id="signature"></div>
                <br>
                <button type="button" class="btn btn-primary btn-block" id="save">保存</button>
                <button type="button" class="btn btn-primary btn-block" id="clear">清除</button>
            </div>
        </div>
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
    </main>
<?php include __DIR__ . '/../myFooter.php'; ?>
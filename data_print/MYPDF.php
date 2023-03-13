<?php
require_once('../tcpdf.php');
class MYPDF extends TCPDF
{
    function Header()
    {
        $this->SetFont('cid0cs', 'B', 20);
        $this->Write(15, '厦门市庆和化工建材有限公司', '', false, 'C');
        $this->Ln();
    }
}
<?php
include __DIR__ . '/../db/db.php';
// Include the main TCPDF library (search for installation path).
include __DIR__ . '/MYPDF.php';

$start_time = $_GET['start_time'];
$stop_time = $_GET['stop_time'];
$userid = $_GET['userid'];

$sql = "SELECT machine_id,register_date,register_time,shutdown_time,total_duration,machine_state,machine_status,uname FROM fqpfsbjl_show where register_date>=? and register_date<=? and uid=? order by register_date,register_time";
$stmt = $dbh->prepare($sql);
$stmt->bindValue(1, $start_time);
$stmt->bindValue(2, $stop_time);
$stmt->bindValue(3, $userid);

$stmt->execute();
$rows = $stmt->fetchAll();
$length = count($rows);

// create new PDF document
//$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf = new MYPDF('P', 'mm', 'A4', true, 'UTF-8');
// set document information


// set header and footer fonts
$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);


// ---------------------------------------------------------

// set font
$pdf->SetFont('cid0cs', 'B', 20);

// add a page
$pdf->AddPage();

$txt = '废气设备运行记录';
$pdf->Write(15, $txt, '', 0, 'C');
$pdf->Ln();

$pdf->SetFont('cid0cs', '', 10);
$pdf->Cell(20, 5, '设备编号', 1, 0, 'C');
$pdf->Cell(30, 5, '启动日期', 1, 0, 'C');
$pdf->Cell(30, 5, '启动时间', 1, 0, 'C');
$pdf->Cell(30, 5, '关闭时间', 1, 0, 'C');
$pdf->Cell(25, 5, '总时长(分钟)', 1, 0, 'C');
$pdf->Cell(20, 5, '设备状态', 1, 0, 'C');
$pdf->Cell(25, 5, '操作员', 1, 0, 'C');
$pdf->Ln();
// set font

$tt = 0;
foreach ($rows as $row) {
//    $pdf->Write(0, $row[0], '', 0, 'L', true, 0, false, false, 0);
//    $pdf->Write(0, $row[1], '', 0, 'L', true, 0, false, false, 0);
  $pdf->Cell(20, 5, $row[0], 1, 0, 'C');
  $pdf->Cell(30, 5, $row[1], 1, 0, 'C');
  $pdf->Cell(30, 5, substr($row[2], 0, 5), 1, 0, 'C');
  $pdf->Cell(30, 5, substr($row[3], 0, 5), 1, 0, 'C');
  $pdf->Cell(25, 5, $row[4], 1, 0, 'C');
  $pdf->Cell(20, 5, $row[6], 1, 0, 'C');
  $pdf->Cell(25, 5, $row[7], 1, 0, 'C');
//    $pdf->Write(0, $row[2], '', 0, 'L', 0, 0, false, false, 0);
  $tt = $tt + $row[4];
  $pdf->Ln();
}
$pdf->Cell(180, 5, '共计：' . $tt . '分钟', 1, 0, 'C');
$pdf->Ln();
$pdf->Write(15, '操作人签名：', '', 0, 'L');
$pdf->Write(15, '年        月', '', 0, 'C');
// ---------------------------------------------------------
//Close and output PDF document
$pdf->Output('example_038.pdf', 'I');

//============================================================+
// END OF FILE
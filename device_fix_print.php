<?php
include __DIR__ . '/db/db.php';
// Include the main TCPDF library (search for installation path).
include __DIR__ . '/MYPDF.php';

$repair_id = $_GET['repair_id'];
$sqlstr = "select repair_id,device_id,device_name,use_name,use_department,apply_time,brief_content";
$sqlstr .= ",auditor_name,audit_time,repairer_department,repairer_name,repair_start_time";
$sqlstr .= ",repair_end_time,repair_content from tb_device_repair_order where repair_id = :repair_id";
$stmt = $dbh->prepare($sqlstr);
$stmt->bindParam(':repair_id', $repair_id);

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

$txt = '维修记录单';
$pdf->Write(20, $txt, '', 0, 'C');
$pdf->Ln();

$pdf->SetFont('cid0cs', '', 15);
$pdf->Cell(25, 8, '维修单号：', 0, 0, 'L');
$pdf->Cell(155, 8, $rows[0]['repair_id'], 0, 0, 'L');
$pdf->Cell(180, 10, '', 0, 0, 'C');
$pdf->Ln();
$pdf->Cell(30, 8, '设备编号', 1, 0, 'C');
$pdf->Cell(40, 8, $rows[0]['device_id'], 1, 0, 'C');
$pdf->Cell(30, 8, '设备名称', 1, 0, 'C');
$pdf->Cell(80, 8, $rows[0]['device_name'], 1, 0, 'C');
$pdf->Ln();
$pdf->Cell(30, 8, '报修部门', 1, 0, 'C');
$pdf->Cell(30, 8, $rows[0]['use_department'], 1, 0, 'C');
$pdf->Cell(30, 8, '报修人员', 1, 0, 'C');
$pdf->Cell(30, 8, $rows[0]['use_name'], 1, 0, 'C');
$pdf->Cell(30, 8, '报修时间', 1, 0, 'C');
$pdf->Cell(30, 8, $rows[0]['apply_time'], 1, 0, 'C');
$pdf->Ln();
$pdf->Cell(40, 50, '设备故障信息', 1, 0, 'C');
$pdf->Cell(140, 50, $rows[0]['brief_content'], 1, 0, 'L');
$pdf->Ln();
$pdf->Cell(40, 8, '审核人', 1, 0, 'C');
$pdf->Cell(40, 8, $rows[0]['auditor_name'], 1, 0, 'C');
$pdf->Cell(50, 8, '审核时间', 1, 0, 'C');
$pdf->Cell(50, 8, $rows[0]['audit_time'], 1, 0, 'C');
$pdf->Ln();
$pdf->Cell(40, 8, '检修部门', 1, 0, 'C');
$pdf->Cell(40, 8, $rows[0]['repairer_department'], 1, 0, 'C');
$pdf->Cell(50, 8, '检修人', 1, 0, 'C');
$pdf->Cell(50, 8, $rows[0]['repairer_name'], 1, 0, 'C');
$pdf->Ln();
$pdf->Cell(40, 8, '领单时间', 1, 0, 'C');
$pdf->Cell(40, 8, $rows[0]['repair_start_time'], 1, 0, 'C');
$pdf->Cell(50, 8, '完成时间', 1, 0, 'C');
$pdf->Cell(50, 8, $rows[0]['repair_end_time'], 1, 0, 'C');
$pdf->Ln();
$pdf->Cell(40, 80, '维修信息', 1, 0, 'C');
//$pdf->Cell(140,80,$rows[0]['repair_content'],1,0,'L');
$pdf->MultiCell(140, 80, $rows[0]['repair_content'], 1, 'L', 0, 0, '', '', true);
$pdf->Ln();


$pdf->Cell(180, 5, '', 0, 0, 'C');
$pdf->Cell(180, 5, '', 0, 0, 'C');
$pdf->Ln();
$pdf->SetFont('cid0cs', '', 12);
$pdf->Write(10, '报修人签名：______________     ', '', 0, 'L');
$pdf->Write(10, '检修人签名：______________     ', '', 0, 'L');
$pdf->Write(10, '审核人签名：______________', '', 0, 'L');

// ---------------------------------------------------------
//Close and output PDF document
$pdf->Output($rows[0]['repair_id'].'.pdf', 'I');
$dbh = null;
//============================================================+
// END OF FILE
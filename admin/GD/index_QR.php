<?php


include $_SERVER['DOCUMENT_ROOT']."/phonebook/admin/gd/phpqrcode/qrlib.php";
//require_once __DIR__ . '/phpqrcode-master/qrlib.php';
 
// create a QR Code with this text and display it
QRcode::png("http://www.ruseller.com", "test.png", "L", 4, 4);

//$backColor = 0xFFFF00;
//$foreColor = 0xFF00FF;
//QRcode::svg("//phpmaster.com", "test-me.svg", "L", 4, 4, false, $backColor, $foreColor);
header("Content-Type:text/html;charset='utf-8'");
?>
<img src="gd_badge2.php"/>

<?php
include 'settings.php';
require 'PhpWord/vendor/autoload.php';
//-------------------------------

if(isset($_POST)){
	$str_querry = '';
	$str_name_sprav = '';
	$num_name_sprav = 1;
	$array_name = array();
	if(isset($_POST['id_all'])){
		$str_querry = '';
		$array_name[$num_name_sprav++] = '. Управление Госэнергогазнадзора';
		$array_name[$num_name_sprav++] = '. Брестский филиал';
		$array_name[$num_name_sprav++] = '. Витебский филиал';
		$array_name[$num_name_sprav++] = '. Гомельский филиал';
		$array_name[$num_name_sprav++] = '. Гродненский филиал';
		$array_name[$num_name_sprav++] = '. Минский филиал';
		$array_name[$num_name_sprav++] = '. Могилевский филиал';
	}else{
		if(isset($_POST['id_upr'])){
			$str_querry = (empty($str_querry) ? '(spr_cod_branch = 7' : ' OR spr_cod_branch = 7');
		
			$array_name[$num_name_sprav++] = '. Управление Госэнергогазнадзора';
		};
		if(isset($_POST['id_brest'])){
			$str_querry .= (empty($str_querry) ? '(spr_cod_branch = 1' : ' OR spr_cod_branch = 1');
		
			$array_name[$num_name_sprav++] = '. Брестский филиал';
		};
		if(isset($_POST['id_vitebsk'])){
			$str_querry .= (empty($str_querry) ? '(spr_cod_branch = 2' : ' OR spr_cod_branch = 2');
		
			$array_name[$num_name_sprav++] = '. Витебский филиал';
		};
		if(isset($_POST['id_gomel'])){
			$str_querry .= (empty($str_querry) ? '(spr_cod_branch = 3' : ' OR spr_cod_branch = 3');
	
			$array_name[$num_name_sprav++] = '. Гомельский филиал';
		};
		if(isset($_POST['id_grodno'])){
			$str_querry .= (empty($str_querry) ? '(spr_cod_branch = 4' : ' OR spr_cod_branch = 4');
		
			$array_name[$num_name_sprav++] = '. Гродненский филиал';
		};
		if(isset($_POST['id_minsk'])){
			$str_querry .= (empty($str_querry) ? '(spr_cod_branch = 6' : ' OR spr_cod_branch = 6');
			
			$array_name[$num_name_sprav++] = '. Минский филиал';
		};
		if(isset($_POST['id_mogilev'])){
			$str_querry .= (empty($str_querry) ? '(spr_cod_branch = 5' : ' OR spr_cod_branch = 5');
		
			$array_name[$num_name_sprav++] = '. Могилевский филиал';
		};
		$str_querry .=') AND';
	}
	if(isset($_POST['id_personal'])){
			
		};

$phpWord = new  \PhpOffice\PhpWord\PhpWord(); 
$phpWord->setDefaultFontName('Sylfaen');
	
$sectionStyle = array(
 
 'orientation' => 'portrait',
 'marginTop' => 500,
 'marginLeft' => 700,
 'marginRight' => 700,
 'marginBottom' => 500,
 'colsNum' => 1,
 'pageNumberingStart' => 1,
 'borderColor' => 'black',
 'width' => 200,
 'height' => 40,

 );
$section = $phpWord->addSection($sectionStyle); 
	
$textPosition = "Должность";
$textFIO = "Фамилия Имя Отчество";
$textTEL = "Городской телефон:";
$textMTEL = "Мобильный телефон:";
$textIPTEL = "IP телефон:";
$textDOPTEL = "АТС ГПО БелЭнерго:";
$textVNTEL = "Внутренняя АТС:";
$textMAIL = "e-mail";

$fontStyle = array('size'=>10, 'color'=>'000000');
$fontStyle2 = array('size'=>12, 'color'=>'#782006');
$fontStyle3 = array('size'=>12, 'color'=>'#394e89');
$fontStyleHead = array('size'=>12, 'color'=>'#000000');
$fontStylePodrazd = array('size'=>12, 'color'=>'#ffffff');
$fontStyleBranch = array('size'=>12, 'color'=>'#ffffff');
$fontStyleZagolovok = array('size'=>14, 'color'=>'#782006');
$fontStyleZagolovokList1 = array('size'=>11, 'color'=>'#782006', 'bold' => true);
$fontStyleZagolovokList2 = array('size'=>11, 'color'=>'#782006');
$fontStyleNameBook = array('size'=>60, 'color'=>'#782006');
$fontStyleAdress = array('size'=>9, 'color'=>'#782006');

$paragraphStyle = array('spaceBefore' => 0, 'spaceAfter' => 3);
$paragraphStyleDolgnost = array('spaceBefore' =>0, 'spaceAfter' => 120, 'lineHeight' => 1);
$paragraphStyleHead = array('spaceBefore' =>0, 'spaceAfter' => 0, 'lineHeight' => 1, 'align' => 'center');
$paragraphStyleHeadList = array('spaceBefore' =>0, 'spaceAfter' => 0, 'lineHeight' => 1, 'align' => 'left', 'indent' => 8 );
$paragraphStyleHeadList1 = array('spaceBefore' =>0, 'spaceAfter' => 0, 'lineHeight' => 1, 'align' => 'left', 'indent' => 11);
$paragraphStyleFIO = array('spaceBefore' =>0, 'spaceAfter' => 0, 'lineHeight' => 1);
$paragraphStyleEMAIL = array('spaceBefore' =>0, 'spaceAfter' => 0, 'lineHeight' => 1, 'align' => 'right');
$parStyle = array('align'=>'left','spaceBefore'=>0);

$styleTableFirstPage = array('borderSize' => 6, 'borderColor' => '999999','cellMargin' => 50);
$styleTable = array('borderSize' => 6, 'borderColor' => '999999','cellMargin' => 50);
$cellRowSpan = array('vMerge' => 'restart', 'valign' => 'center', 'cellMargin' => 10, 'spaceBefore' =>0,
					'spaceAfter' => 0,);
$cellRow = array('vMerge' => 'restart', 'valign' => 'center', 'cellMargin' => 10);
$cellRowContinue = array('vMerge' => 'continue');
$cellColSpan = array('gridSpan' => 2, 'valign' => 'center');
$cellColSpanHead = array('gridSpan' => 4, 'valign' => 'center','bgColor' => 'ae8d51');
$cellColSpanPodrazd = array('gridSpan' => 4, 'valign' => 'center','bgColor' => '394e89');
$cellColSpanBranch = array('gridSpan' => 4, 'valign' => 'center','bgColor' => '782006');
$cellFirstPage = array('valign' => 'top','bgColor' => 'ffffff', 'align' => 'center', 'borderSize' => 6, 'borderColor' => '782006',);
$cellRowHead = array('bgColor' => 'ae8d51', 'color' => '000000');

$cellColSpan2 = array('borderRightSize' => 0, 'borderRightColor' => 'ffffff');
$cellColSpan3 = array('borderLeftSize' => 0, 'borderLeftColor' => 'ffffff');
$cellHCentered = array('align' => 'center');
$cellVCentered = array('valign' => 'center');
$phpWord->addTableStyle('Colspan Rowspan', $styleTable);

$res_users =  mysqli_query($connect, "SELECT  users.name as users_name, users.fam as users_fam, users.otch as users_otch, users.dolgnost as users_dolgnost, users.photo as users_photo, users.email as users_email, users.phone as users_phone, users.mobile_phone as users_mobile_phone, users.ip_phone as users_ip_phone, users.rup_phone as users_rup_phone, users.branch_phone as users_branch_phone, users.is_spr as users_is_spr, users.filter_spr as users_filter_spr, spr_branch.inner_order as spr_branch_inner_order, spr_branch.id as spr_branch_id, spr_branch.name as spr_branch_name, spr_branch.adress as spr_branch_adress, spr_branch.phone_cod as spr_branch_phone_cod, spr_branch.phone as spr_branch_phone, spr_branch.fax as spr_branch_fax, spr_branch.email as spr_branch_email, spr_otdel.inner_order as spr_otdel_inner_order,spr_otdel.id as spr_otdel_id, spr_otdel.name_otdel as spr_otdel_name_otdel, spr_otdel.adress as spr_otdel_adress, spr_podrazdelenie.id as spr_podrazdelenie_id, spr_podrazdelenie.inner_order as spr_podrazdelenie_inner_order, spr_podrazdelenie.name_podrazd as spr_podrazdelenie_name_podrazd, spr_podrazdelenie.adress as spr_podrazdelenie_adress, spr_podrazdelenie.phone_cod as spr_podrazdelenie_phone_cod, spr_podrazdelenie.phone as spr_podrazdelenie_phone, spr_podrazdelenie.fax as spr_podrazdelenie_fax, spr_podrazdelenie.email as spr_podrazdelenie_email 
FROM users 
INNER JOIN spr_branch on spr_branch.id = users.spr_cod_branch 
INNER JOIN spr_otdel on spr_otdel.id = users.spr_cod_otd 
INNER JOIN spr_podrazdelenie on spr_podrazdelenie.id = users.spr_cod_podrazd 
where ".$str_querry." is_spr = 1 
ORDER BY spr_branch_inner_order, spr_podrazdelenie_inner_order, spr_otdel_inner_order, users_filter_spr");
//echo $str_querry;
$current_otdel =0;
$current_podrazd =0;
$current_branch =0;
$kolLine = 0;
$kolOtd = 0;

/////////////////////////////////////////////////// ТИТУЛЬНЫЙ ЛИСТ (ОБЛОЖКА)  //////////////////////////////////////

$photoEmblem = $_SERVER['DOCUMENT_ROOT'].'/phonebook/images/emblem_gegn.png'; 

$table = $section->addTable('Colspan Rowspan');
$phpWord->addTableStyle('Colspan Rowspan', $styleTableFirstPage);
$table->addRow(15500);
$workCellFirstPage = $table->addCell(11000, $cellFirstPage);
$workCellFirstPage->addImage($photoEmblem, array('width' => 60, 'marginTop' =>20, 'marginLeft' =>240, 'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
    'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
    'posVertical' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,));
$workCellFirstPage ->addTextBreak(3);
$workCellFirstPage -> addText('Государственное учреждение', $fontStyleZagolovok,$paragraphStyleHead);	
$workCellFirstPage -> addText('"Государственный энергетический и газовый надзор"', $fontStyleZagolovok,$paragraphStyleHead);
$workCellFirstPage ->addTextBreak(4);
$workCellFirstPage -> addText('СПРАВОЧНИК', $fontStyleNameBook,$paragraphStyleHead);
$workCellFirstPage -> addText('от'.' '.date("d.m.Y"), $fontStyleZagolovokList2,$paragraphStyleHeadList1);
$workCellFirstPage ->addTextBreak(7);
$workCellFirstPage -> addText('Содержит:', $fontStyleZagolovokList1,$paragraphStyleHeadList);

for($i = 1; $i <= count($array_name); $i++){
	$workCellFirstPage -> addText($i.$array_name[$i], $fontStyleZagolovokList2,$paragraphStyleHeadList);
}

$workCellFirstPage ->addTextBreak(10 - $i);
$workCellFirstPage -> addText('Сайт в сети интернет: http://www.gosenergogaznadzor.by', $fontStyleAdress,$paragraphStyleHead);
$workCellFirstPage -> addText('Актуальная версия справочника находится по адресу: http://web.nadzor.net/phonebook/', $fontStyleAdress,$paragraphStyleHead);

////////////////////////////////////////////////////ЗАПОЛНЕНИЕ ДАННЫМИ///////////////////////////////////////
while($row_users = mysqli_fetch_object($res_users)){
	
			$table = $section->addTable('Colspan Rowspan');
			
			// вывод названия филиалов
			if($current_branch <> $row_users->spr_branch_inner_order){
				$section ->addPageBreak();
				$table = $section->addTable('Colspan Rowspan');
				$phpWord->addTableStyle('Colspan Rowspan', $styleTable);	
				
				$table->addRow(100);
				$workCellBranch = $table->addCell(9400, $cellColSpanBranch);
				$workCellBranch -> addText($row_users->spr_branch_name, $fontStyleBranch,$paragraphStyleHead);
				$workCellBranch -> addText($row_users->spr_branch_adress.'; '.'факс: +375('.$row_users->spr_branch_phone_cod.')'.$row_users->spr_branch_fax, $fontStyleBranch,$paragraphStyleHead);
				
						
				$current_branch = $row_users->spr_branch_inner_order;
				
				$table->addRow(100);
				$workCellOtdel = $table->addCell(9400, $cellColSpanPodrazd);
				$workCellOtdel -> addText($row_users->spr_podrazdelenie_name_podrazd, $fontStylePodrazd,$paragraphStyleHead);
				//$workCellOtdel -> addText($row_users->spr_podrazdelenie_adress, $fontStyleBranch,$paragraphStyleHead);
				$workCellOtdel -> addText($row_users->spr_podrazdelenie_adress.''.(empty($row_users->spr_podrazdelenie_fax) ? '' : '; факс: +375('.$row_users->spr_podrazdelenie_phone_cod.')').$row_users->spr_podrazdelenie_fax, $fontStyleBranch,$paragraphStyleHead);
				$headPodrazd = 0;
				$kolOtd = 0;
					
				$current_podrazd = $row_users->spr_podrazdelenie_id;
				
				$kolLine = 1;				
			}
			
			// вывод названия подразделений
			if($current_podrazd <> $row_users->spr_podrazdelenie_id){
				if($current_branch <> 1){
					$section ->addPageBreak();
					$kolLine = 0;	
				};
				$headPodrazd = $headPodrazd + 1;
				$table = $section->addTable('Colspan Rowspan');
				$phpWord->addTableStyle('Colspan Rowspan', $styleTable);	
				//$section->addText($current_podrazd );
				$table->addRow(100);
				$workCellOtdel = $table->addCell(9400, $cellColSpanPodrazd);
				$workCellOtdel -> addText($row_users->spr_podrazdelenie_name_podrazd, $fontStylePodrazd,$paragraphStyleHead);
				$workCellOtdel -> addText($row_users->spr_podrazdelenie_adress.''.(empty($row_users->spr_podrazdelenie_fax) ? '' : '; факс: +375('.$row_users->spr_podrazdelenie_phone_cod.')').$row_users->spr_podrazdelenie_fax, $fontStyleBranch,$paragraphStyleHead);
				
				
						
				$current_podrazd = $row_users->spr_podrazdelenie_id;
			
			}
			
			
			// вывод названия отделов
			if($current_otdel <> $row_users->spr_otdel_id){
				if($current_branch <> 1){
					if($kolOtd >= 2 && $kolLine == 6){
						$section ->addPageBreak();
						$table = $section->addTable('Colspan Rowspan');
						$phpWord->addTableStyle('Colspan Rowspan', $styleTable);
						$kolLine = 0;
						$kolOtd = 0;					
					}
				}
				
				
				$table->addRow(100, $cellRowHead );
				$workCellOtdel = $table->addCell(9400, $cellColSpanHead);
				$workCellOtdel -> addText($row_users->spr_otdel_name_otdel,$fontStyleHead,$paragraphStyleDolgnost);
				$kolOtd = $kolOtd + 1;
				$current_otdel = $row_users->spr_otdel_id;
				
				if($current_branch <> 1){
				
					if($kolOtd == 3 ){
						$kolLine = $kolLine + 1;
						$kolOtd = 0;
					}
				}
			}
			
			if($current_branch == 1){
				if($kolLine >= 6){
					$section = $phpWord->addSection($sectionStyle);
					$phpWord->addTableStyle('Colspan Rowspan', $styleTable);
					$kolLine = 0;
					$kolOtd = 0;
					$headPodrazd = 0;	
				}
				
			}else{
				if($kolLine >= 7 || ($headPodrazd == 1 && $kolOtd == 2 && $kolLine == 6) ){
					
					$section = $phpWord->addSection($sectionStyle);
					$phpWord->addTableStyle('Colspan Rowspan', $styleTable);
					$kolLine = 0;
					$kolOtd = 0;
					$headPodrazd = 0;
				
				}
			}
			
			
			$dolgnost = $row_users->users_dolgnost;
			$fio = $row_users->users_fam. PHP_EOL .$row_users->users_name. PHP_EOL .$row_users->users_otch;
			$phone = $row_users->users_phone;
			$mobile_phone = $row_users->users_mobile_phone;
			$ip_phone = $row_users->users_ip_phone;
			$rup_phone = $row_users->users_rup_phone;
			$branch_phone = $row_users->users_branch_phone;
			$email = $row_users->users_email;
			
			if(strlen($row_users->users_photo)>0){
				$photo = $_SERVER['DOCUMENT_ROOT'].'/phonebook/images/users_photo/'.trim($row_users->users_photo);
			}else{
				$photo = $_SERVER['DOCUMENT_ROOT'].'/phonebook/images/users_photo/no-photo.png';	
			};

			
			
			$table->addRow(100);
			$workCell1 = $table->addCell(3000, $cellRowSpan);
			$workCell1 ->addText($dolgnost, $fontStyle3,$paragraphStyleDolgnost);
			$workCell1 ->addText($row_users->users_fam, $fontStyle2,$paragraphStyleFIO);
			$workCell1 ->addText($row_users->users_name.' '.$row_users->users_otch, $fontStyle2,$paragraphStyleFIO);
			$workCell2 = $table->addCell(3500, $cellColSpan2);
			$workCell2 -> addText($textTEL, $fontStyle,$paragraphStyle);
			$workCell2 -> addText($textMTEL, $fontStyle,$paragraphStyle);
			$workCell2 -> addText($textIPTEL, $fontStyle,$paragraphStyle);
			$workCell2 -> addText($textDOPTEL, $fontStyle,$paragraphStyle);
			$workCell2 -> addText($textVNTEL, $fontStyle,$paragraphStyle);

			$workCell3 = $table->addCell(3000, $cellColSpan3);
			$workCell3 -> addText($phone, $fontStyle,$paragraphStyle);
			$workCell3 -> addText($mobile_phone, $fontStyle,$paragraphStyle);
			$workCell3 -> addText($ip_phone, $fontStyle,$paragraphStyle);
			$workCell3 -> addText($rup_phone, $fontStyle,$paragraphStyle);
			$workCell3 -> addText($branch_phone, $fontStyle,$paragraphStyle);
			$table->addCell(900, $cellRowSpan)->addImage($photo, array(
					'width' => 60,
										
			));
			$table->addRow(10);
			$table->addCell(null, $cellRowContinue);
			$table->addCell(null, $cellColSpan)->addText($email, $fontStyle2, $paragraphStyleEMAIL);
			$table->addCell(null, $cellRowContinue);
			$kolLine = $kolLine + 1;
			
			if($current_branch == 1){
				if($kolLine >= 6){
					$section = $phpWord->addSection($sectionStyle);
					$phpWord->addTableStyle('Colspan Rowspan', $styleTable);
					$kolLine = 0;
					$kolOtd = 0;
					$headPodrazd = 0;	
				}
				
			}else{
				if($kolLine >= 7 || ($headPodrazd == 1 && $kolOtd == 2 && $kolLine == 6) ){
				
				$section = $phpWord->addSection($sectionStyle);
				$phpWord->addTableStyle('Colspan Rowspan', $styleTable);
		
				$kolLine = 0;
				$kolOtd = 0;
				$headPodrazd = 0;
				}
			}
	
}
$LineStyle = array('weight' => 1, 'width' => 530, 'height' => 1, 'color' => '#cdc9c9');


$section ->addPageBreak();
$section->addText("Для заметок:", $fontStyle,$paragraphStyle );
$section ->addTextBreak(1);
for($l = 1; $l <= 30; $l++){
$section -> addLine($LineStyle);
}
///////////////////////////////
header("Content-Description: File Transfer");
header('Content-Disposition: attachment; filename="phone.docx"');
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Expires: 0');



$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord,'Word2007');
/*ob_clean();*/
$PathWord = "../phonebook/doc/sprav.docx";
$objWriter->save($PathWord);
//$objWriter->save("php://output");

/*$zip = new ZipArchive();
$zip->open('../phonebook/doc/sprav.zip', ZipArchive::CREATE);
$zip->addFile('../phonebook/doc/sprav.doc');
$zip->close();*/

echo $PathWord;
}
 ?>
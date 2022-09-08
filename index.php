<?php // счетчик статистики
	$first_url="phonebook_index.php";
	include $_SERVER['DOCUMENT_ROOT']."\statistics\stat_count.php";
?>

<?php 
	include 'header.php';
?>


<div class='main-body'>
<!--div class='sticky_body'-->	
<div id='search_request'>
	<input id="search" class='field_search' placeholder="Поиск по ФИО, должности, структуре, SIP..."><button class='clear_field'>×</button></input>
	<ul id='pre-result'></ul>
</div>
<!--/div-->
<div id='search_result'>



<div class='info_main_page'>
<?php
$res_branch =  mysqli_query($connect, "SELECT * FROM spr_branch ORDER BY inner_order");

$end_result = "";

while($row_branch = mysqli_fetch_object($res_branch)){ 
		
			$res_podrazdelenie = mysqli_query($connect, "SELECT * FROM spr_podrazdelenie WHERE cod_branch = $row_branch->id ORDER BY inner_order");
			$end_result     .= "<div  ".($row_branch->inner_order == 1 ? "class='upravlenie result_html'":"class='result_html'" ).">
			<div class='person_data'>
			<div class='block_link'><a href='branch-list.php?id=$row_branch->id' class='person_link'><p class='name'>$row_branch->sokr_name </p></a></div>
			<div class='data_block'>
			</div><div class='add_block_link'>";
			
			
			while($row_podrazd = mysqli_fetch_object($res_podrazdelenie)){
				$end_result     .= "<p><a href='department.php?id=$row_podrazd->id'>$row_podrazd->sokr_name</a></p>";
			}
			
			$end_result     .="</div><div class='link_structure'><a href='branch.php?id=$row_branch->id'>Структура</a></div></div></div>";
			
		}
		echo $end_result;
?>
</div>




</div>
</div>

<?php include 'footer.php';?>


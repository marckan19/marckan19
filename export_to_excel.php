<?php
ob_start();
session_start();
require_once('header.php');

if(isset($_SESSION['user_id']) || isset($_COOKIE['ciastko_user_id'])){
	require_once 'lib/excel/PHPExcel.php';
	
	$fileName = 'budowy.xlsx';
	$filePath = '/test/budowy.xlsx';
	
	$excel = new PHPExcel();
	/*$excel -> setActiveSheetIndex(0)
		-> setCellValue('A1','Hello')
		-> setCellValue('B1','World')
		-> setCellValue('C1','World2sssss3333sss22');*/
		
	$query = "SELECT id, zleceniodawca, realizacja_od, realizacja_do FROM panel_tabela_budowy ORDER BY realizacja_od;";
	$result = mysql_query($query) or die (mysql_error());
	
	$excel -> setActiveSheetIndex(0)
		-> setCellValue('A1','ID')
		-> setCellValue('B1','ZLECENIODAWCA')
		-> setCellValue('C1','REALIZACJA OD')
		-> setCellValue('D1','REALIZACJA DO');
	
	$lp = 2;
	while($row = mysql_fetch_array($result,MYSQL_ASSOC)){
		$excel -> setActiveSheetIndex(0)
			-> setCellValue('A'.$lp,$row['id'])
			-> setCellValue('B'.$lp,$row['zleceniodawca'])
			-> setCellValue('C'.$lp,$row['realizacja_od'])
			-> setCellValue('D'.$lp,$row['realizacja_do']);
		
		$lp++;
		
		//echo $row['id'] . ' ' . $row['zleceniodawca'] . ' ' . $row['realizacja_od'] . ' ' . $row['realizacja_do'] . '<br />';
	}
		
		//die();
	$file = PHPExcel_IOFactory::createWriter($excel,'Excel2007');
	$file->save($fileName);

	header("Location: ".$filePath,TRUE,301);


	
}else{
	require_once('logout.php');
}

require_once('footer.php');
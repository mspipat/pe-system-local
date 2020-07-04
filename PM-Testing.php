<?php
	include 'db/conn.php';
	require 'plugins/phpspreadsheet/vendor/autoload.php';
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

	$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
	$uploaded_sheet = $reader->load("process/uploads/PM Data/testPM.xls");

	// ARRAYS 
	$cc = 'starting';
	$offRow = 2;
	
	$ipAdd = $_SERVER['REMOTE_ADDR'];
	$tableName = 'pmTemp_'.$ipAdd;
	$sqlCreateTbl = "CREATE TABLE `$tableName` ( `listId` INT NOT NULL AUTO_INCREMENT , `connNo` INT(11) NOT NULL , `partNo` INT(11) NOT NULL , `partClass` VARCHAR(11) NOT NULL , `partsName` VARCHAR(255) NOT NULL , `wireLenght` INT(11) NOT NULL , `method` VARCHAR(255) NOT NULL , PRIMARY KEY (`listId`));";
	$query = $conn_db->query($sqlCreateTbl);

	do {
		$cc = $uploaded_sheet->getActiveSheet()->getCell('A'.$offRow)->getValue();

		echo 'Conn No: '.$connNo = $uploaded_sheet->getActiveSheet()->getCell('A'.$offRow)->getValue();
		echo ' Parts Class: '.$partsClass = $uploaded_sheet->getActiveSheet()->getCell('D'.$offRow)->getValue();
		echo ' Parts Name: '.$partsName = $uploaded_sheet->getActiveSheet()->getCell('F'.$offRow)->getValue();
		echo ' Parts Number: '.$partsNumber = $uploaded_sheet->getActiveSheet()->getCell('C'.$offRow)->getValue();
		echo ' Length: '.$length = $uploaded_sheet->getActiveSheet()->getCell('G'.$offRow)->getValue(); 
		echo ' Method: '.$method = $uploaded_sheet->getActiveSheet()->getCell('H'.$offRow)->getValue();
		if($length == ''){
			if((strstr($partsName, 'STU')) || (strstr($partsName, 'MARUBO'))|| (strstr($partsName, 'STG'))){
				$partsNameData = explode("X",$partsName);
				$lenghtData = $partsNameData[2];
				$length = preg_replace("/[^0-9]/", "", $lenghtData);
			}
		}
		echo "<br>";
		// $sqlInsert = "INSERT INTO `$tableName`(`connNo`, `partNo`, `partClass`, `partsName`, `wireLenght`, `method`) 
		// VALUES ('$connNo','$partsNumber','$partsClass','$partsName','$length','$method')";
		// $query = $conn_db->query($sqlInsert);
		$offRow++;

	} while ($cc != NULL);
	$sqlSelectSameCount = "SELECT COUNT(listId) AS num, connNo,  FROM `$tableName` GROUP BY connNo";
	$queryCount = $conn_db->query($sqlSelectSameCount);
	while ($data = $queryCount->fetch_assoc()) {
		$num = $data['num'];
		$connNo = $data['connNo'];
		if($num >= 2){
			$sqlGetData = "SELECT DISTINCT(partsName) FROM `tableName` WHERE connNo = '$connNo' AND partClass LIKE 'C%'";
			$queryData = $conn_db->query($sqlGetData);
			echo $count = mysqli_num_rows($queryData);
			// while ($data = $queryData->fetch_assoc()) {
				
			// }

		}
	}
	print_r($sameConnNo);
	// $sqlDeleteTbl = "DROP TABLE `$tableName`";
	// $query1 = $conn_db->query($sqlDeleteTbl);
	 
?>

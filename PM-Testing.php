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
	$sameConnSum = array();
	$samePartsSum = array();
	$vsMethodSum = array();
	
	$ipAdd = $_SERVER['REMOTE_ADDR'];
	$tableName = 'pmTemp_'.$ipAdd;
	// $sqlCreateTbl = "CREATE TABLE `$tableName` ( `listId` INT NOT NULL AUTO_INCREMENT , `connNo` INT(11) NOT NULL , `partNo` INT(11) NOT NULL , `partClass` VARCHAR(11) NOT NULL , `partsName` VARCHAR(255) NOT NULL , `wireLenght` INT(11) NOT NULL , `method` VARCHAR(255) NOT NULL , PRIMARY KEY (`listId`));";
	// $query = $conn_db->query($sqlCreateTbl);

	// do {
	// 	$cc = $uploaded_sheet->getActiveSheet()->getCell('A'.$offRow)->getValue();

	// 	$connNo = $uploaded_sheet->getActiveSheet()->getCell('A'.$offRow)->getValue();
	// 	$partsClass = $uploaded_sheet->getActiveSheet()->getCell('D'.$offRow)->getValue();
	// 	$partsName = $uploaded_sheet->getActiveSheet()->getCell('F'.$offRow)->getValue();
	// 	$partsNumber = $uploaded_sheet->getActiveSheet()->getCell('C'.$offRow)->getValue();
	// 	$length = $uploaded_sheet->getActiveSheet()->getCell('G'.$offRow)->getValue(); 
	// 	$method = $uploaded_sheet->getActiveSheet()->getCell('H'.$offRow)->getValue();
	// 	if($length == ''){
	// 		if((strstr($partsName, 'STU')) || (strstr($partsName, 'MARUBO'))|| (strstr($partsName, 'STG'))){
	// 			$partsNameData = explode("X",$partsName);
	// 			$lenghtData = $partsNameData[2];
	// 			$length = preg_replace("/[^0-9]/", "", $lenghtData);
	// 		}
	// 	}
	// 	$sqlInsert = "INSERT INTO `$tableName`(`connNo`, `partNo`, `partClass`, `partsName`, `wireLenght`, `method`) 
	// 	VALUES ('$connNo','$partsNumber','$partsClass','$partsName','$length','$method')";
	// 	$query = $conn_db->query($sqlInsert);
	// 	$offRow++;
	// } while ($cc != NULL);
	

	// CRITERIA 1 - SAME CONNECTOR NO
	$sqlSelectSameCount = "SELECT COUNT(listId) AS TOTAL, connNo  FROM `$tableName` GROUP BY connNo";
	$queryCount = $conn_db->query($sqlSelectSameCount);
	while ($data = $queryCount->fetch_assoc())
	{
		$num = $data['TOTAL'];
			if($num >= 2)
			{
				$connNo = $data['connNo'];
				$sqlGetData = "SELECT DISTINCT(partsName) as partsName, connNo FROM `$tableName` WHERE connNo = '$connNo' AND partClass LIKE 'C%'";
				$queryData = $conn_db->query($sqlGetData);
				$numCount = mysqli_num_rows($queryData);
				if($numCount >= 2){
					while ($connectors = $queryData->fetch_assoc()) {
						$partsName = $connectors['partsName'];
						$sameConnSum[] = $connNo .'-'.$partsName;
					}
				}
			}else{

			}
	}

	// CRITERIA 2 - SAME PARTS NO
	$sqlSelectSameCount1 = "SELECT COUNT(listId) AS TOTAL, partno  FROM `$tableName` GROUP BY partNo";
	$queryCount1 = $conn_db->query($sqlSelectSameCount1);
	while ($data1 = $queryCount1->fetch_assoc())
	{
		$num = $data1['TOTAL'];
		if($num >= 2)
			{
				$partno = $data1['partno'];
				$sqlGetData1 = "SELECT DISTINCT(CONCAT(partsName,' ',wireLenght)) AS partsName FROM `$tableName` WHERE partNo = '$partno'";
				$queryData1 = $conn_db->query($sqlGetData1);
				$numCount1 = mysqli_num_rows($queryData1);
				if($numCount1 >= 2)
				{
					while ($parts = $queryData1->fetch_assoc()) {
						$partsName = $parts['partsName'];
						$samePartsSum[] = $partno .' '.$partsName;
					}
				}
			}

	}
	// CRITERIA 3 - VS METHOD
	$sqlSelectVSData = "SELECT partNo, partsName, method FROM `pmtemp_172.25.112.223` WHERE partsName LIKE 'VS%'";
	$queryData3 = $conn_db->query($sqlSelectVSData);
	while ($data2 = $queryData3->fetch_assoc())
	{
		$vsPartName = $data2['partsName'];
		$vsPartNo = $data2['partNo'];
		$vsMethod = $data2['method'];
		$checkData = strstr($vsMethod, '(O)');
		if($checkData == true)
		{
			$vsMethodSum[] = $vsPartNo .' '. $vsPartName .' '. $vsMethod;
		}
	}
	// CRITERIA 4 - PARTS UNIFICATION
	


	// OUTPUT
	echo "Criteria 1 <br>";
	print_r($sameConnSum);
	echo "<br>";
	echo "Criteria 2 <br>";
	print_r($samePartsSum);
	echo "<br>";
	echo "Criteria 3 <br>";
	print_r($vsMethodSum);

	// $sqlDeleteTbl = "DROP TABLE `$tableName`";
	// $query1 = $conn_db->query($sqlDeleteTbl);
	 
?>

<?php
	include 'db/conn.php';
	require 'plugins/phpspreadsheet/vendor/autoload.php';
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

	$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
	$uploaded_sheet = $reader->load("process/uploads/SD Data/Dev.xls");

	$offRow = 2;
	$wires = array();
	$sameRecord = array();
	$wireRecord = array();
	$sameWireSummary = array();

	do 
	{
		// $cs++;
		$cc = $uploaded_sheet->getActiveSheet()->getCell('A'.$offRow)->getValue();
		$wireNo = $uploaded_sheet->getActiveSheet()->getCell('K'.$offRow)->getValue();
		$wireSize = $uploaded_sheet->getActiveSheet()->getCell('M'.$offRow)->getValue();
		$insL = $uploaded_sheet->getActiveSheet()->getCell('AA'.$offRow)->getValue();
		$insR = $uploaded_sheet->getActiveSheet()->getCell('AO'.$offRow)->getValue();
		$ter_L = $uploaded_sheet->getActiveSheet()->getCell('U'.$offRow)->getValue();
		$ter_R = $uploaded_sheet->getActiveSheet()->getCell('AI'.$offRow)->getValue();
		$wireType = $uploaded_sheet->getActiveSheet()->getCell('L'.$offRow)->getValue();
		$wireColor = $uploaded_sheet->getActiveSheet()->getCell('N'.$offRow)->getValue();
		$wireLen = $uploaded_sheet->getActiveSheet()->getCell('O'.$offRow)->getValue();
		$connName_L = $uploaded_sheet->getActiveSheet()->getCell('W'.$offRow)->getValue();
		$connName_R = $uploaded_sheet->getActiveSheet()->getCell('AK'.$offRow)->getValue();
		$connL = $uploaded_sheet->getActiveSheet()->getCell('X'.$offRow)->getValue();
		$connR = $uploaded_sheet->getActiveSheet()->getCell('AL'.$offRow)->getValue();
		$wireData = $wireNo.'-'. $wireType.'-'.$wireSize.'-'. $wireColor.'-'.$wireLen;
		$wires[] = array("wires" => $wireNo, "wireSpecs" => $wireData);
	
		$offRow++;
	}while($cc != NULL);
	$ipAdd = $_SERVER['REMOTE_ADDR'];
	$tableName = 'temp_'.$ipAdd;
	$sqlCreateTbl = "CREATE TABLE  `$tableName`(
		listId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		wire VARCHAR(30) NOT NULL,
		specs VARCHAR(30) NOT NULL);";
	$query = $conn_db->query($sqlCreateTbl);
	$temparr = array();
	foreach($wires as $key => $i)
	{
		$wires = $i['wires'];
		$specs = $i['wireSpecs'];
		echo 'Wires: '.$wires.' Specs: '.$specs.'<br>';
		$sql = "INSERT INTO `$tableName`(`wire`, `specs`) VALUES ('$wires','$specs')";
		$query = $conn_db->query($sql);
	}
	$sqlData = "SELECT COUNT(listId) AS number, wire FROM `$tableName` GROUP BY wire";
	$query1 = $conn_db->query($sqlData);
	while ($data = $query1->fetch_assoc())
	{
		$number = $data['number'];
		$wire = $data['wire'];
		if($number >= 2)
		{
			$sameWireList[] = $wire;
		}
	}
		foreach ($sameWireList as $key => $sameWire) {
			$sqlSelectDataWire = "SELECT specs FROM `$tableName` WHERE wire = '$sameWire' GROUP BY specs";
			$query2 = $conn_db->query($sqlSelectDataWire);
			$rowcount = mysqli_num_rows($query2);
			if($rowcount >=2){
				while($data = $query2->fetch_assoc()){
					$temparr[] = $data['specs'];
				}
			}
		}
		$sqlDelTemp = "DROP TABLE `$tableName`";
		$queryDel = $conn_db->query($sqlDelTemp);
		print_r($temparr);
?>
<?php
include 'db/conn.php';
$ipAdd = $_SERVER['REMOTE_ADDR'];
$tableName = 'pmTemp_'.$ipAdd;

$sameConnSum = array();

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
				echo "<br>";
			}else{

			}
	}
	

	print_r($sameConnSum);
	?>
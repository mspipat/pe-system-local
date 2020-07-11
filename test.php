<?php
include 'db/conn.php';
$holder = '';
$tableName = 'pmtemp_172.25.112.223';
$sqlSamePartsNameData = "SELECT COUNT(listId) AS TOTAL, partsName  FROM `$tableName` GROUP BY partsName";
	$queryData4 = $conn_db->query($sqlSamePartsNameData);
	while ($partsNameDat = $queryData4->fetch_assoc())
	{
		$num = $partsNameDat['TOTAL'];
		if($num >= 2)
			{
				$partsName = $partsNameDat['partsName'];
				$sqlGetData2 = "SELECT wireLength, partsName FROM `$tableName` WHERE partsName = '$partsName' ORDER BY wirelength DESC";
				$queryData5 = $conn_db->query($sqlGetData2);
				while ($lengthDat = $queryData5->fetch_assoc()) {
					if ($holder ==''){
						$holder = $lengthDat['wireLength'];
					}else{
						$holder = $holder - $lengthDat['wireLength'];
					}
				}
				if($holder >= 0 && $holder <= 20 ){
					$partsUnification[] = $partsName .'/'. $holder;
				}
				$holder = '';
			}
		}
		print_r($partsUnification);
		?>
		<!-- $result = $lenghtArr[0];
		for ($i = 1; $i < count($lenghtArr); $i++) {
					$result -= $lenghtArr[$i];
		} 
	echo $partsName .' '.$result; -->
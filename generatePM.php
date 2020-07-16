<!DOCTYPE html>
<html>
<head>
	<?php
		include 'src/link.php';
	?>
	<title> PM Data Wrangling Tool</title>
</head>
<body style="background-image: url('img/computer.jpg');background-size: cover;">
<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12">
				<h4 class="card-title text-center font-weight-bolder mt-1 text-uppercase"> Upload PM Tool</h4>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-8">
			<div class="card-body white">
				<form action="" method="post" enctype="multipart/form-data" id="fileUploadForm" class="">
	   		<div class="input-group text">
							<div class="input-group-prepend">
								<span class="input-group-text" id="inputGroupFileAddon01">Upload</span>
							</div>
							<div class="custom-file">
								<input type="file" class="custom-file-input" id="dataExcel" accept="application/vnd.ms-excel,.xlsx" name="file">
								<label class="custom-file-label" for="inputGroupFile01">Choose file</label>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12">
								<button type="submit" class="btn btn-md mt-2 btn-primary text-center" id="btnSubmit">Submit</button>
							</div>
						</div>
				</form>
				<div class="row text-center" id="waitSection" style="display:none;">
				<div class="col-lg-12">
							<img src="img/gif1.gif" width="250" height="200" style="margin-top: -50px;"><br>
							<div class="spinner-grow spinner-grow-sm text-primary" role="status">
  							<span class="sr-only">Loading...</span>
							</div>
							<div class="spinner-grow spinner-grow-sm text-primary" role="status">
  							<span class="sr-only">Loading...</span>
							</div>
							<div class="spinner-grow spinner-grow-sm text-primary" role="status">
  							<span class="sr-only">Loading...</span>
							</div>
					</div>
				</div>
				<div class="row text-center" id="reportSection" style="display:none;">
					<div class="col-lg-12">
							<h5>Successfully Generated PM Tool!</h5>
					</div>
					<div class="col-lg-4"></div>
					<div class="col-lg-4">
						<i class="fa fa-download fa-4x" aria-hidden="true"></i><br>
					<a id="linkReport">Download PM Report</a>
					<button class="btn btn-md btn-danger" id="btnNew">Upload New File</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
	include 'src/script.php';
?>
<?php
	include 'db/conn.php';
	require 'plugins/phpspreadsheet/vendor/autoload.php';
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
	if(isset($_FILES['file'])){
		// variables 
			$ipAdd = $_SERVER['REMOTE_ADDR'];
			$date = date('Y-m-d h i s');
			$uploadName = $_FILES['file']['name'];
			$fileType = $_FILES['file']['type'];
		// Check File Type
		if($fileType == 'application/vnd.ms-excel'){	
			$newFileName = 'PM-'.$date.' '.$ipAdd.'-'.$uploadName;
			// Uploaded File
			if( move_uploaded_file($_FILES["file"]["tmp_name"],"process/uploads/PM Data/".$newFileName)){
				// Record System Activity 
					$dateStart = date('Y-m-d h:i:s');
					$sqlInsertRec = "INSERT INTO `system_activity`(`ipAdd`, `activity`, `datetime`) VALUES ('$ipAdd','Upload PM Data','$dateStart')";
					$query = $conn_db->query($sqlInsertRec);
				// END Record System Activity
				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
				$uploaded_sheet = $reader->load("process/uploads/PM Data/".$newFileName);
				
				// ARRAYS 
					$cc = 'starting';
					$offRow = 2;
					$sameConnSum = array();
					$samePartsSum = array();
					$vsMethodSum = array();
					$partsUnification = array();	
					$ipAdd = $_SERVER['REMOTE_ADDR'];
					$tableName = 'pmTemp_'.$ipAdd;
					$sqlCreateTbl = "CREATE TABLE `$tableName` ( `listId` INT NOT NULL AUTO_INCREMENT , `connNo` INT(11) NOT NULL , `partNo` INT(11) NOT NULL , `partClass` VARCHAR(11) NOT NULL , `partsName` VARCHAR(255) NOT NULL , `wireLength` INT(11) NOT NULL , `method` VARCHAR(255) NOT NULL , PRIMARY KEY (`listId`));";
					$query = $conn_db->query($sqlCreateTbl);

					do {
						$cc = $uploaded_sheet->getActiveSheet()->getCell('F'.$offRow)->getValue();
						$connNo = $uploaded_sheet->getActiveSheet()->getCell('A'.$offRow)->getValue();
						$partsClass = $uploaded_sheet->getActiveSheet()->getCell('D'.$offRow)->getValue();
						$partsName = $uploaded_sheet->getActiveSheet()->getCell('F'.$offRow)->getValue();
						$partsNumber = $uploaded_sheet->getActiveSheet()->getCell('C'.$offRow)->getValue();
						$length = $uploaded_sheet->getActiveSheet()->getCell('G'.$offRow)->getValue(); 
						$method = $uploaded_sheet->getActiveSheet()->getCell('H'.$offRow)->getValue();
						if($length == ''){
							if((strstr($partsName, 'STU')) || (strstr($partsName, 'MARUBO'))|| (strstr($partsName, 'STG'))){
								$partsNameData = explode("X",$partsName);
								$lenghtData = $partsNameData[2];
								$length = preg_replace("/[^0-9]/", "", $lenghtData);
							}
						}
							$sqlInsert = "INSERT INTO `$tableName`(`connNo`, `partNo`, `partClass`, `partsName`, `wireLength`, `method`)  VALUES ('$connNo','$partsNumber','$partsClass','$partsName','$length','$method')";
							$query = $conn_db->query($sqlInsert);
						$offRow++;
					} while ($cc != NULL);

					// CRITERIA 1 - SAME CONNECTOR NO
						$sqlSelectSameCount = "SELECT COUNT(listId) AS TOTAL, connNo  FROM `$tableName` GROUP BY connNo";
						$queryCount = $conn_db->query($sqlSelectSameCount);
						while ($sameConnData = $queryCount->fetch_assoc())
						{
							$num = $sameConnData['TOTAL'];
							if($num >= 2)
							{
								$connNo = $sameConnData['connNo'];
								$sqlGetData = "SELECT DISTINCT(partsName) as partsName, connNo FROM `$tableName` WHERE connNo = '$connNo' AND partClass LIKE 'C%'";
								$queryData = $conn_db->query($sqlGetData);
								$numCount = mysqli_num_rows($queryData);
								if($numCount >= 2){
									while ($connectors = $queryData->fetch_assoc()) {
										$partsName = $connectors['partsName'];
										$sameConnSum[] = $connNo .'/'.$partsName;
									}
								}
							}else{
							}
						}
					// CRITERIA 2 - SAME PARTS NO
						$sqlSelectSameCount1 = "SELECT COUNT(listId) AS TOTAL, partno  FROM `$tableName` GROUP BY partNo";
						$queryCount1 = $conn_db->query($sqlSelectSameCount1);
						while ($samePartNoData = $queryCount1->fetch_assoc())
						{
							$num = $samePartNoData['TOTAL'];
							if($num >= 2)
							{
								$partno = $samePartNoData['partno'];
								$sqlGetData1 = "SELECT DISTINCT(CONCAT(partsName,'/',wireLength)) AS partsName FROM `$tableName` WHERE partNo = '$partno'";
								$queryData1 = $conn_db->query($sqlGetData1);
								$numCount1 = mysqli_num_rows($queryData1);
								if($numCount1 >= 2)
								{
									while ($parts = $queryData1->fetch_assoc()) {
										$partsName = $parts['partsName'];
										$samePartsSum[] = $partno .'/'.$partsName;
									}
								}
							}
						}
					// CRITERIA 3 - VS METHOD
						$sqlSelectVSData = "SELECT partNo, partsName, method FROM `$tableName` WHERE partsName LIKE 'VS%'";
						$queryData3 = $conn_db->query($sqlSelectVSData);
						while ($vsData = $queryData3->fetch_assoc())
						{
							$vsPartName = $vsData['partsName'];
							$vsPartNo = $vsData['partNo'];
							$vsMethod = $vsData['method'];
							$checkData = strstr($vsMethod, '(O)');
							if($checkData == true)
							{
								$vsMethodSum[] = $vsPartNo .'/'. $vsPartName .'/'. $vsMethod;
							}
						}
					// CRITERIA 4 - PARTS UNIFICATION
						$holder = '';
						$sqlSamePartsNameData = "SELECT COUNT(listId) AS TOTAL, partsName  FROM `$tableName` GROUP BY partsName";
						$queryData4 = $conn_db->query($sqlSamePartsNameData);
						while ($partsNameDat = $queryData4->fetch_assoc())
						{
							$num = $partsNameDat['TOTAL'];
							if($num >= 2){
								$partsName = $partsNameDat['partsName'];
								$sqlGetData2 = "SELECT partNo, wireLength, partsName FROM `$tableName` WHERE partsName = '$partsName' ORDER BY wirelength DESC";
								$queryData5 = $conn_db->query($sqlGetData2);
								while ($lengthDat = $queryData5->fetch_assoc()) {
									$partsNo = $lengthDat['partNo'];
									if ($holder ==''){
										$holder = $lengthDat['wireLength'];
									}else{
										$holder = $holder - $lengthDat['wireLength'];
									}
								}
								if($holder >= 0 && $holder <= 20 ){
									$partsUnification[] = $partsNo .'/'.$partsName .'/'. $holder;
								}
								$holder = '';
							}
						}
			
			
			
				// GENERATE EXCEL REPORT
					$PMExcelRep = new Spreadsheet();
					$report_sheet = $PMExcelRep->getActiveSheet();
					$writer = new Xlsx($PMExcelRep);
					$dateId = date("ymdHis");
					$rand = rand(000,100);
					$uploadName = 'test';
					$fileName = 'PM Report- '.$uploadName.' '.$dateId.''.$rand.'.xlsx';

					// Copy template to New Spreadsheet
					$temp_reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
					$temp_spreadsheet = $temp_reader->load("process/uploads/PM Data/template.xlsx");
					$temp_sheet = $temp_spreadsheet->getSheetByName('PM tool');
					$clonedWorksheet = clone $temp_spreadsheet->getActiveSheet();
					$PMExcelRep->addExternalSheet($clonedWorksheet);
					$PMExcelRep->removeSheetByIndex(0);
					$report_sheet = $PMExcelRep->getSheetByName('PM Tool');
	
					// Write the Report in the New Spreadsheet
					$offRow = 3;
					$countCri1 = ($sameConnSum);
					if($countCri1 >= 1){
						foreach($sameConnSum as $key => $cri1){
							$string = explode("/", $cri1);
							$report_sheet->getCell('A'.$offRow)->setValue($string[0]);
							$report_sheet->getCell('B'.$offRow)->setValue($string[1]);
							$offRow++;
						}
					}
					$offRow = 3;
					$countCri2 = ($samePartsSum);
					if($countCri2 >= 1){
						foreach($samePartsSum as $key => $cri2){
							$string = explode("/", $cri2);
							$report_sheet->getCell('C'.$offRow)->setValue($string[0]);
							$report_sheet->getCell('D'.$offRow)->setValue($string[1]);
							$report_sheet->getCell('E'.$offRow)->setValue($string[2]);
							$offRow++;
						}
					}
					$offRow = 3;
					$countCri3 = ($vsMethodSum);
					if($countCri3 >= 1){
						foreach($vsMethodSum as $key => $cri3){
							$string = explode("/", $cri3);
							$report_sheet->getCell('F'.$offRow)->setValue($string[0]);
							$report_sheet->getCell('G'.$offRow)->setValue($string[1]);
							$report_sheet->getCell('H'.$offRow)->setValue($string[2]);
							$offRow++;
						}
					}
					$offRow = 3;
					$countCri4 = ($partsUnification);
					if($countCri4 >= 1){
						foreach($partsUnification as $key => $cri4){
							$string = explode("/", $cri4);
							$report_sheet->getCell('I'.$offRow)->setValue($string[0]);
							$report_sheet->getCell('J'.$offRow)->setValue($string[1]);
							$report_sheet->getCell('K'.$offRow)->setValue($string[2]);
							$offRow++;
						}
					}
					// Save new Spreadsheet
					$writer->save('process/exports/PM Data/'.$fileName);
					$dateFinished = date('Y-m-d h:i:s');
					$sqlDeleteTbl = "DROP TABLE `$tableName`";
					$query1 = $conn_db->query($sqlDeleteTbl);	
					
					// Record System Activity
						$sqlInsertRec = $sqlInsertRec = "INSERT INTO `system_activity`(`ipAdd`, `activity`, `datetime`) VALUES ('$ipAdd','Generate PM Data - $fileName','$dateFinished')";
						$queryxv = $conn_db->query($sqlInsertRec);
					if($queryxv)
					{
						?>
							<script type="text/javascript">
										$('#reportSection').show();
										$('#waitSection').hide();
										$('#dataExcel').prop('disabled', true);
										$('#btnSubmit').prop('disabled', true);
	
										var report = '<a href="process/exports/PM Data/<?php echo $fileName;?>" class="btn btn-md btn-success text-center mt-2" id="linkReport">Download Report</a>';
										document.getElementById('linkReport').innerHTML = report;
									</script>
						<?php
					}
			}
		}else{
		?>
			<script type="text/javascript">
					Swal.fire({
					icon: 'error',
					title: 'Oops...',
					text: 'Invalid File Type, upload .xls only.'
				})
			</script>
		<?php
		}
	}
	?>
	<script type="text/javascript">
		$('#btnSubmit').click(function(){
			$('#waitSection').show();
			$('#btnSubmit').hide();
		});
		$('#btnNew').click(function(){
		$('#reportSection').hide();
			$('#dataExcel').prop('disabled', false);
			$('#btnSubmit').prop('disabled', false);
			$('$btnSubmit').show();
		
		});
	</script>
	</body>
	</html>
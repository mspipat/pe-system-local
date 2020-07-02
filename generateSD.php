<!DOCTYPE html>
<html>
<head>
	<?php
		include 'src/link.php';
	?>
	<title> SD Data Wrangling Tool</title>
</head>
<body style="background-image: url('img/computer.jpg');background-size: cover;">
<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12">
				<h4 class="card-title text-center font-weight-bolder mt-1 text-uppercase"> Upload SD Tool</h4>
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
							<h5>Successfully Generated SD Tool!</h5>
					</div>
					<div class="col-lg-4"></div>
					<div class="col-lg-4">
						<i class="fa fa-download fa-4x" aria-hidden="true"></i><br>
					<a id="linkReport">Download Report</a>
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
			if($fileType == 'application/vnd.ms-excel')
			{	
				$newFileName = 'SD-'.$date.' '.$ipAdd.'-'.$uploadName;
				// Uploaded File
				if( move_uploaded_file($_FILES["file"]["tmp_name"],"process/uploads/SD Data/".$newFileName))
				{
					// Record System Activity 
						$dateStart = date('Y-m-d h:i:s');
						$sqlInsertRec = "INSERT INTO `system_activity`(`ipAdd`, `activity`, `datetime`) VALUES ('$ipAdd','Upload SD Data','$dateStart')";
						$query = $conn_db->query($sqlInsertRec);
					// END Record System Activity 
					$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
					$uploaded_sheet = $reader->load("process/uploads/SD Data/".$newFileName);

					// ARRAYS 
						$wireSizeSummary = array();
						$floatingCircuitSummary = array();
						$aeeterminalSummary = array();
						$maleTerminalSummary = array();
						$terminalSummary = array();
						$wireSpotSummary = array();
						$gomusenSummary = array();
						$similarSummary = array();
						$cc = 'starting';
						$offRow = 2;
						$word = "AEE";
						$maleMaster = array();
						$terminalMaster = array();
						$wires = array();
						$sameWires = array();
						$wireRecord = array();
						$sameWireSummary = array();
						$L = array();
						$R = array();
						$sqlSelectMasterSimilar = "SELECT `left-right` AS L, `right-left` AS R FROM `master_similar`";
						$dataMasterSimilar = $conn_db->query($sqlSelectMasterSimilar);
						while ($similarItems = $dataMasterSimilar->fetch_array())
						{
							$L[] = $similarItems['L'];
							$R[] = $similarItems['R'];
						}

					// Query Databases
						$sqlSelectMasterMale = "SELECT `male_terminal` FROM `master_male`";
						$dataMasterMale = $conn_db->query($sqlSelectMasterMale);
						while ($maledata = $dataMasterMale->fetch_assoc()) 
						{
							$maleMaster[] = $maledata['male_terminal'];
						}
	
						$sqlSelectMasterTerminal = "SELECT `terminal` FROM `master_025terminal`";
						$dataMasterTerminal = $conn_db->query($sqlSelectMasterTerminal);
						while ($terminalData = $dataMasterTerminal->fetch_assoc()) 
						{
							$terminalMaster[] = $terminalData['terminal'];
						}

						$gomusenMaster = array();
						$sqlSelectGomusen = "SELECT `gomusen` FROM `master_gomusen`";
						$dataMasterGomusen = $conn_db->query($sqlSelectGomusen);
						while ($gomusen = $dataMasterGomusen->fetch_assoc()) 
						{
							$gomusenMaster[] = $gomusen['gomusen'];
						}
	
						$sqlSelectMasterSimilar = "SELECT `left-right` AS L, `right-left` AS R FROM `master_similar`";
						$dataMasterSimilar = $conn_db->query($sqlSelectMasterSimilar);
	
			//READ AND PROCESS DATA
						do 
						{
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
							// WIRE SIZE
								if($wireSize == '0.13' || $wireSize == '0.22')
								{
									if($insR == '2' || $insL == '2')
									{
										$wireSizeSummary[] = $wireNo;
									}
								}
							// FLOATING CIRCUIT
									if($insR == '2' && $insL == '2')
									{
										$floatingCircuitSummary[] = $wireNo;
									}
							// AEE TERMINAL
									if(strstr($ter_L,"AEE") == true || strstr($ter_R,"AEE") == true)
									{
										$aeeterminalSummary[] = $wireNo;
									}
							// MALE TERMINAL
									if((in_array($ter_L,$maleMaster) && ($insL == '2') == TRUE) || (in_array($ter_R, $maleMaster) && ($insR == '2') == TRUE))
									{
										$maleTerminalSummary[] = $wireNo;
									}
							// 025 Terminal	
									if((in_array($ter_L,$terminalMaster) && ($insL == '2') == TRUE) || (in_array($ter_R, $terminalMaster) && ($insR == '2') == TRUE))
									{
										$terminalSummary[] = $wireNo;
									}
							// With Gomusen
									if((in_array($connName_L,$gomusenMaster) && ($insL == '2') == TRUE) || (in_array($connName_R, $gomusenMaster)		 && ($insR == '2') 	== TRUE))
									{
										$gomusenSummary[] = $wireNo;
									}
							// Shikakari Number
									$wireData = $wireNo.'-'. $wireType.'-'.$wireSize.'-'. $wireColor.'-'.$wireLen;
									$wires[] = array("wires" => $wireNo, "wireSpecs" => $wireData);
							// Wire Spot Taping
									if($wireSize == '0.13')
									{
										$spot = $wireNo .' '. $connL .' '. $connR;
										$wireSpotSummary[] = $spot;
									}
							// Similar Terminal	
							$sameWire = $wireNo .' '. $ter_L .' '. $ter_R;
							if(in_array($ter_R, $R) && (in_array($ter_L,$L))){
								$similarSummary[] = $sameWire;
							}elseif (in_array($ter_L, $R) && (in_array($ter_R,$L))) {
								$similarSummary[] = $sameWire;
							}
								$offRow++;
			  } 
				while ($cc != NULL);
				$tableName = 'temp_'.$ipAdd;
				$sqlCreateTbl = "CREATE TABLE  `$tableName`(listId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, wire VARCHAR(30) NOT NULL, specs VARCHAR(30) NOT NULL);";
				$query = $conn_db->query($sqlCreateTbl);

				foreach($wires as $key => $i)
				{
					$wires = $i['wires'];
					$specs = $i['wireSpecs'];
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
				foreach ($sameWireList as $key => $sameWire) 
				{
						$sqlSelectDataWire = "SELECT specs FROM `$tableName` WHERE wire = '$sameWire' GROUP BY specs";
						$query2 = $conn_db->query($sqlSelectDataWire);
						$rowcount = mysqli_num_rows($query2);
						if($rowcount >=2)
						{
							while($data = $query2->fetch_assoc())
							{
								$sameWireFinalSummary[] = $data['specs'];
							}
						}
				}
		$sqlDelTemp = "DROP TABLE `$tableName`";
		$queryDel = $conn_db->query($sqlDelTemp);

			// END PROCESSING

		// GENERATE EXCEL REPORT
			// Create new Spreadsheet
			$SDExcelRep = new Spreadsheet();
			$report_sheet = $SDExcelRep->getActiveSheet();
			$writer = new Xlsx($SDExcelRep);
			$dateId = date("ymdHis");
    	$rand = rand(000,100);
			$fileName = 'SD Report- '.$uploadName.' '.$dateId.''.$rand.'.xlsx';

			// Copy template to New Spreadsheet
			$temp_reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
			$temp_spreadsheet = $temp_reader->load("process/uploads/SD Data/template.xlsx");
			$temp_sheet = $temp_spreadsheet->getSheetByName('SD tool');
			$clonedWorksheet = clone $temp_spreadsheet->getActiveSheet();
			$SDExcelRep->addExternalSheet($clonedWorksheet);
			$SDExcelRep->removeSheetByIndex(0);
			$report_sheet = $SDExcelRep->getSheetByName('SD Tool');

			// Write the Report in the New Spreadsheet
				// Wire Summary
					$offRow = 3;
					$countWS = count($wireSizeSummary);
					if($countWS >= 1)
					{
						for ($i=0; $i < $countWS; $i++) 
						{ 
							$report_sheet->getCell('A'.$offRow)->setValue($wireSizeSummary[$i]);
							$offRow++;
						}
					}
				// Floating Circuit
					$offRow = 3;
					$countFS = count($floatingCircuitSummary);
					if($countFS >= 1)
					{
						for ($i=0; $i < $countFS; $i++) 
						{ 
							$report_sheet->getCell('B'.$offRow)->setValue($floatingCircuitSummary[$i]);
							$offRow++;
						}
					}
				// AEE 
					$offRow = 3;
					$countAEE = count($aeeterminalSummary);
					if($countAEE >= 1)
					{
						for ($i=0; $i < $countAEE; $i++) 
						{ 
							$report_sheet->getCell('C'.$offRow)->setValue($aeeterminalSummary[$i]);
							$offRow++;
						}
					}
				// Male Terminal
					$offRow = 3;
					$countMS = count($maleTerminalSummary);
					if($countMS >= 1)
					{
						for ($i=0; $i < $countMS; $i++) 
						{ 
							$report_sheet->getCell('D'.$offRow)->setValue($maleTerminalSummary[$i]);
							$offRow++;
						}
					}
				// 025
					$offRow = 3;
					$countTS = count($terminalSummary);
					if($countTS >= 1)
					{
						for ($i=0; $i < $countTS; $i++) 
						{ 
							$report_sheet->getCell('E'.$offRow)->setValue($terminalSummary[$i]);
							$offRow++;
						}
					}
				// Gomusen
					$offRow = 3;
					$countGS = count($gomusenSummary);
					if($countGS >= 1)
					{
						for ($i=0; $i < $countGS; $i++) { 
							$report_sheet->getCell('F'.$offRow)->setValue($gomusenSummary[$i]);
							$offRow++;
						}
					}
				// Shikakari
					$offRow = 3;
					$countSS = count($sameWireFinalSummary);
					if($countSS >= 1)
					{
						foreach($sameWireFinalSummary as $key => $x)
						{
							// $shikakari = $X;
							$string = explode("-", $x);
							$report_sheet->getCell('G'.$offRow)->setValue($string[0]);
							$report_sheet->getCell('H'.$offRow)->setValue($string[1].' '.$string[2].' '.$string[3].' '.$string[4]);
							$offRow++;
						}
					}
				// Wire Spot Taping
					$offRow = 3;
					$countWST = count($wireSpotSummary);
					if($countWST >= 1)
					{
						for ($i=0; $i < $countWST; $i++) 
						{ 
							$wireSpot = $wireSpotSummary[$i];
							$wirex = explode(" ", $wireSpot);
			
							$report_sheet->getCell('I'.$offRow)->setValue($wirex[0]);
							$report_sheet->getCell('J'.$offRow)->setValue($wirex[1]);
							$report_sheet->getCell('K'.$offRow)->setValue($wirex[2]);
							$offRow++;
						}
					}
				// Wire Spot Taping
					$offRow = 3;
					$countSum = count($similarSummary);
					if($countSum >= 1)
					{
						for ($i=0; $i < $countSum; $i++) 
						{ 
							$similar = $similarSummary[$i];
							$wirey = explode(" ", $similar);
			
							$report_sheet->getCell('L'.$offRow)->setValue($wirey[0]);
							$report_sheet->getCell('M'.$offRow)->setValue($wirey[1]);
							$report_sheet->getCell('N'.$offRow)->setValue($wirey[2]);
							$offRow++;
						}
					}
				// Save new Spreadsheet

					$writer->save('process/exports/SD Data/'.$fileName);
					$dateFinished = date('Y-m-d h:i:s');

				// Record System Activity
					$sqlInsertRec = $sqlInsertRec = "INSERT INTO `system_activity`(`ipAdd`, `activity`, `datetime`) VALUES ('$ipAdd','Generate SD Data - $fileName','$dateFinished')";
					$queryxv = $conn_db->query($sqlInsertRec);
					if($queryxv)
					{
						?>
								<script type="text/javascript">
									$('#reportSection').show();
									$('#waitSection').hide();
									$('#dataExcel').prop('disabled', true);
									$('#btnSubmit').prop('disabled', true);

									var report = '<a href="process/exports/SD Data/<?php echo $fileName;?>" class="btn btn-md btn-success text-center mt-2" id="linkReport">Download Report</a>';
									document.getElementById('linkReport').innerHTML = report;
								</script>
						<?php
					}
					// echo $fileName;
		// END GENERATE EXCEL REPORT
			}
		}else
		{
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
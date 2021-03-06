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
  if(isset($_FILES['file']))
  {
    // variables 
			$ipAdd = $_SERVER['REMOTE_ADDR'];
			$date = date('Y-m-d h i s');
			$uploadName = $_FILES['file']['name'];
      $fileType = $_FILES['file']['type'];
    // Check File Type
			if($fileType == 'application/vnd.ms-excel')
			{
        
      }
  }
?>
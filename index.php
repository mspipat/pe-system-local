<!DOCTYPE html>
<html>
<head>
	<?php
		include 'src/link.php';
	?>
	<title>PE - MIS</title>
</head>
<body>
	<?php
		include 'modals/uploadFile.php';
		include 'modals/login.php';
	?>
	<div class="container-fluid">
		<div class="col-lg-12 mt-3">
			<div class="row">
				<div class="col-lg-4">
					<button class="btn btn-md btn-primary" id="btnAdmin"><i class="fas fa-user-lock"></i> Admin</button>
				</div>
			</div>
			<div class="row mt-2">
				<div class="col-lg-6">
				<div class="card" style="opacity: 0.9;">
						<h1 class="card-title text-center font-weight-bolder"> Production Engineering<br> System</h1>
					<div class="card-body">
							<div class="row">
								<div class="col-lg-12 text-center">
										<button class="btnMenu btn btn-lg btn-primary mt-3" id="btnSd" style="width: 100%;"> SD Tool</button>
										<br>
										<button class="btnMenu btn btn-lg btn-orange mt-3" id="btnPm" style="width: 100%;"> PM Tool</button>
										<br>

										<button class="btnMenu btn btn-lg btn-secondary mt-3" id="btnWt" style="width: 100%;"> Wire Tuning Calculator</button>
								</div>	
							</div>
					</div>
					</div>
					</div>

					<div class="col-lg-6" id="DispData" style="background-image: url('img/computer.jpg');background-size: cover;opacity: 0.9;">
					</div>
					<div class="col-lg-6" id="SDData" style="display: none;">
						<iframe src="generateSD.php" title="SD Data Wrangling Tool" id="SDData" style="width: 100%;height: 100%;opacity: 0.9;"></iframe>
					</div>
					<div class="col-lg-6" id="PMData" style="display: none;">
						<iframe src="generatePM.php" title="PM Data Tool" id="PMData" style="width: 100%;height: 100%;opacity: 0.9;"></iframe>
					</div>
			</div>
		</div>
	</div>
<?php
	include 'src/script.php';
?>
<script type="text/javascript">
	$(document).ready(function(){
		
	});

	$('#btnAdmin').click(function(){
		$('#adminLogin').modal();
	});

	$('#btnLoginAdmin').click(function(){
		let password = $('#password').val();
		$.ajax({
			url: 'process/ajax/processData.php?request=login&password='+password,
			type: 'get',
			success: function(data){
				if(data == 'true'){
					window.location.href = 'adminPage.php?login=1';
				}else{
					Swal.fire({
					  icon: 'error',
					  title: 'Oops...',
					  text: 'Wrong Admin Credentials'
					})
				}
			},error: function(data){

			}
		});
	});

	$('.btnMenu').click(function(){
		$('#SDData').hide();
		$('#PMData').hide();
		$('#DispData').hide();
	});

	$('#btnSd').click(function(){
		$('#SDData').show();
	});

	$('#btnPm').click(function(){
		$('#PMData').show();
	});
</script>
</body>
</html>
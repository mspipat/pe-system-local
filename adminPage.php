<?php
	if(isset($_GET['login']))
	{
		if(($_GET['login']) != '1'){
			header('Location: index.php');
		}
	}else{
		header('Location: index.php');
	}
?>
<!DOCTYPE html>
<html>
<head>
	<?php
		include 'src/link.php';
		include 'db/conn.php';
	?>
	<title>Admin</title>
</head>
<body>
		<?php
			include 'modals/updateMaster.php';
			include 'modals/updateMaster1.php';
			include 'modals/addMaster.php';
			include 'modals/addMaster1.php';
		?>
	<div class="container-fluid">
		<div class="row mt-3">
				<div class="col-lg-12">
					<div class="card">
						<a href="index.php"><i class="fa fa-arrow-left fa-2x ml-2 mt-2" aria-hidden="true"></i></a>
						<h3 class="text-center font-weight-bold mt-2">Master Data</h3>
							<div class="card-body">
								<div class="row">
									<div class="col-lg-4">
									</div>
										<div class="col-lg-4">
											<select class="browser-default custom-select" name="masterCateg" id="select">
												<option selected="" disabled="">Select Master Data</option>
												<option value="male"> Male Terminal</option>
												<option value="025"> 025 Terminal</option>
												<option value="gomusen"> Gomusen </option>
												<option value="similar"> Similar Terminal </option>
											</select>
									</div>
								</div>
								<div class="row">
									<button class="btn btn-sm btn-primary" id="btnAdd"><i class="fas fa-plus"></i> Add Item</button>
								</div>
								<div class="row m-4">
									<div class="col-lg-12">
										<table class="table text-center table-sm table-bordered" id="table">
										<thead class="blue darken-1 text-white text-uppercase font-weight-bold">
											<tr>
												<th>No.</th>
												<th>Item</th>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
									</div>
								</div>
							</div>
					</div>
				</div>
		</div>
		<div class="row mt-3">
			<div class="col-lg-12">
				<div class="card">
					<h3 class="text-center font-weight-bold mt-3">System Activity</h3>
					<div class="card-body">
						<div class="row">
							<div class="col-lg-12">
								<table class="table table-sm table-bordered table-stripes text-center" id="systemActivity">
									<thead class="blue-gradient text-white font-weight-bold text-uppercase">
										<tr>
											<th>Date/Time of Activity</th>
											<th>Acitivity</th>
											<th>IP Address</th>
										</tr>
									</thead>
									<tbody id="systemCol">
										<?php
											$sqlSelect = "SELECT * FROM `system_activity`";
											$querySelected = $conn_db->query($sqlSelect);
											while ($data = $querySelected->fetch_array()) {
												echo '<td>'.$data['datetime'].'</td>
				  									<td>'.$data['activity'].'</td>
				  									<td>'.$data['ipAdd'].'</td>
												</tr>';
											}
										?>
									</tbody>
								</table>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
		
	</div>

	<?php
		include 'src/script.php';
	?>
<script type="text/javascript">
$(document).ready(function(){
	$('#systemActivity').DataTable({});
});
	$('#select').change(function(){
		reloadTable();
	});

	function reloadTable(){
		$('#table').DataTable().destroy();
		var data = $('#select').val();
		$.ajax({
			url: 'process/ajax/displayData.php?data='+data,
			method: 'get',
			success: function(result){
				var res = result;
				$('#table').html(res);
				$('#table').DataTable();
				$('.dataTables_lenght').addClass('bs-select');
				// alert(result);
			}, error: function(result){

			}
		});
	}

	function loadActivity(){
		$('#systemActivity').DataTable().destroy();
		$.ajax({
			url: 'process/ajax/displayData.php?activity=true',
			method: 'get',
			success: function(result){
				var res = result;
				$('#systemActivity').html(res);
				$('#systemActivity').DataTable();
				$('.dataTables_lenght').addClass('bs-select');
				// alert(result);
			}, error: function(result){

			}
		});
	}

	function action(x){
		var master = $('#select').val();
		var data = x.split(",");
		var id = data[0];
		var item = data[1];
		$('#updateMaster').modal();
		$('#master').val(master);
		$('#updateMaster').modal();
		$('#id').val(id);	 
		$('#output').text(item);
		$('#newName').val(item);
	}

	function action1(x){
		var master = $('#select').val();
		var data = x.split(",");
		var id = data[0];
		var item1 = data[1];
		var item2 = data[2];
		$('#updateMaster1').modal();
		$('#master1').val(master);
		$('#updateMaster1').modal();
		$('#id1').val(id);	 
		$('#output1').text(item1 +' - '+ item2);
		$('#newItemName1').val(item1);
		$('#newItemName2').val(item2);
	}
	
	$('#btnAdd').click(function(){
		var master = $('#select').val();
		var text = '';
		if(master == 'male')
		{
				$('#addMaster').modal();
				$('#addMasterList').val('male');
				$('#addOutput').text('Male Terminal Master');
		}
		else if (master == '025')
		{
			 $('#addMaster').modal();
				$('#addMasterList').val('025');

				$('#addOutput').text('025 Terminal Master');
		}
		else if (master == 'gomusen')
		{
			$('#addMaster').modal();
				$('#addMasterList').val('gomusen');
				$('#addOutput').text('Gomusen Master');
		}else if(master == 'similar')
		{
			$('#addMaster1').modal();
			$('#addMasterList1').val('similar');
			$('#addOutput1').text('Similar Master');
		}
		$('#masterList1').val(master);
	});

	$('#btnAddItem').click(function(){
		var masterTable = $('#addMasterList').val();
		var addItem = $('#addItem').val();
		if(addItem != '')
		{
			var url = 'process/ajax/processData.php?process=add&master='+masterTable+'&newItem='+addItem;
			$.ajax({
				url: url,
				method: 'get',
				success: function(response)
				{
					if(response == 'success')
					{
						Swal.fire({
						  position: 'top-end',
						  icon: 'success',
						  title: 'Successfully Added',
						  showConfirmButton: false,
						  timer: 1500
						})
					   reloadTable();
					   loadActivity();
					   $('#addMaster').modal('toggle');
					}else{
						Swal.fire({
						position: 'top-end',
						icon: 'error',
						title: 'Fail to add. Please try again.',
						showConfirmButton: false,
						timer: 1500
						})
					}
				}, error: function(response)
				{
				}
			});
		}else{
			Swal.fire({
				position: 'center',
				icon: 'error',
				title: 'No Input Item',
				showConfirmButton: false,
				timer: 1500
			})
		}	
	});
	
	$('#btnAddItem1').click(function(){
		var masterTable = $('#addMasterList1').val();
		var addItem1 = $('#addItem1').val();
		var addItem2 = $('#addItem2').val();
		// alert(addItem1 +''+addItem2);
			if(addItem1 != '' && addItem2 != ''){
				var url = 'process/ajax/processData.php?process=add&master='+masterTable+'&newItem1='+addItem1+'&newItem2='+addItem2;
				// alert(url);
				$.ajax({
					url: url,
					method: 'get',
					success: function(response)
					{
						if(response == 'success')
		 				{
		 					Swal.fire({
							  position: 'top-end',
							  icon: 'success',
							  title: 'Successfully Added',
							  showConfirmButton: false,
							  timer: 1500
							})
	     					reloadTable();
	     					loadActivity();
	     					$('#addMaster1').modal('toggle');
		 				}
		 				else
		 				{
		 					Swal.fire({
							  position: 'top-end',
							  icon: 'error',
							  title: 'Fail to add. Please try again.',
							  showConfirmButton: false,
							  timer: 1500
							})
		 				}
					}, error: function(response)
					{
					}
				});
			}else{
				Swal.fire({
					position: 'center',
					icon: 'error',
					title: 'Please input Item 1 & 2',
					showConfirmButton: false,
					timer: 1500
				})
			}		
	});

	$('#btnUpdate').click(function(){
		var id = $('#id').val();
		var masterlist = $('#master').val();
		var newName = $('#newName').val();

		var url = 'process/ajax/processData.php?process=update&master='+masterlist+'&id='+id+'&newItemName='+newName;
		$.ajax({
			url: url,
			method: 'get',
			success: function(response)
			{
				if(response == 'success')
		 		{
		 			Swal.fire({
					  position: 'top-end',
					  icon: 'success',
					  title: 'Successfully Updated',
					  showConfirmButton: false,
					  timer: 1500
					})
	     		reloadTable();
	     		loadActivity();
					$('#updateMaster').modal('toggle');
		 		}
		 		else
		 		{
		 			Swal.fire({
					  position: 'top-end',
					  icon: 'error',
					  title: 'Update Failed. Please try again.',
					  showConfirmButton: false,
					  timer: 1500
					})
		 		}
			},error: function(response)
			{

			}
		});
	});

	$('#btnUpdate1').click(function(){
		var id = $('#id1').val();
		var masterlist = $('#master1').val();
		var newName1 = $('#newItemName1').val();
		var newName2 = $('#newItemName2').val();

		var url = 'process/ajax/processData.php?process=update&master='+masterlist+'&id='+id+'&newItemName1='+newName1+'&newItemName2='+newName2;
		$.ajax({
			url: url,
			method: 'get',
			success: function(response)
			{
				if(response == 'success')
		 		{
		 			Swal.fire({
					  position: 'top-end',
					  icon: 'success',
					  title: 'Successfully Updated',
					  showConfirmButton: false,
					  timer: 1500
					})
	     		reloadTable();
	     		loadActivity();
					$('#updateMaster1').modal('toggle');
		 		}
		 		else
		 		{
		 			Swal.fire({
					  position: 'top-end',
					  icon: 'error',
					  title: 'Update Failed. Please try again.',
					  showConfirmButton: false,
					  timer: 1500
					})
		 		}
			},error: function(response)
			{

			}
		});
	});

	$('#btnDelete').click(function(){
		var id = $('#id').val();
		var masterlist = $('#master').val();
		var newName = $('#newName').val();
			const swalWithBootstrapButtons = Swal.mixin({
			  customClass: {
			    confirmButton: 'btn btn-success',
			    cancelButton: 'btn btn-danger'
			  },
			  buttonsStyling: false
			})

			swalWithBootstrapButtons.fire({
			  title: 'Are you sure?',
			  text: "You won't be able to revert this!",
			  showCancelButton: true,
			  confirmButtonText: 'Yes, delete it!',
			  cancelButtonText: 'No, cancel!',
			  reverseButtons: true
			}).then((result) => {
			  if (result.value) {
  				var newItemName = $('#newName').val();
					var url = 'process/ajax/processData.php?process=delete&master='+masterlist+'&id='+id+'&newItemName='+newName;
			// alert(url);
		 			$.ajax({
		 				url: url,
		 				method: 'get',
		 				success: function(response){
					    swalWithBootstrapButtons.fire(
					      'Deleted!',
					      'Your file has been deleted.',
					      'success'
					    )
					   reloadTable();
					   loadActivity();
					    $('#updateMaster').modal('toggle');
			  		}
					});

			  } else if (
			    /* Read more about handling dismissals below */
			    result.dismiss === Swal.DismissReason.cancel
			  ) {
			    swalWithBootstrapButtons.fire(
			      'Cancelled',
			      'No changes in your masterlist.',
			      'error'
			    )
			  }
			})
	});

	$('#btnDelete1').click(function(){
			var id = $('#id1').val();
			var masterlist = $('#master1').val();
				const swalWithBootstrapButtons = Swal.mixin({
				  customClass: {
				    confirmButton: 'btn btn-success',
				    cancelButton: 'btn btn-danger'
				  },
				  buttonsStyling: false
				})

			swalWithBootstrapButtons.fire({
			  title: 'Are you sure?',
			  text: "You won't be able to revert this!",
			  showCancelButton: true,
			  confirmButtonText: 'Yes, delete it!',
			  cancelButtonText: 'No, cancel!',
			  reverseButtons: true
			}).then((result) => {
			  if (result.value) {
  				var newItemName = $('#newName').val();
					var url = 'process/ajax/processData.php?process=delete&master='+masterlist+'&id='+id;
			// alert(url);
		 			$.ajax({
		 				url: url,
		 				method: 'get',
		 				success: function(response){
					    swalWithBootstrapButtons.fire(
					      'Deleted!',
					      'Your file has been deleted.',
					      'success'
					    )
					    reloadTable();
					    loadActivity();
					    $('#updateMaster1').modal('toggle');
			  		}
					});

			  } else if (
			    /* Read more about handling dismissals below */
			    result.dismiss === Swal.DismissReason.cancel
			  ) {
			    swalWithBootstrapButtons.fire(
			      'Cancelled',
			      'No changes in your masterlist.',
			      'error'
			    )
			  }
			})
	});

</script>
</body>
</html>
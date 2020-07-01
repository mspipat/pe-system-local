<?php
	include '../../db/conn.php';
	if(isset($_GET['data']))
	{
		$data = $_GET['data'];
		if($data == 'male')
		{
			$noData = 1;
			$column = 'male_terminal';
			$table = 'master_male';
		}
		elseif($data == '025')
		{
			$noData = 1;
			$column = 'terminal';
			$table = 'master_025terminal';
		}
		elseif($data == 'gomusen')
		{
			$noData = 1;
			$column = 'gomusen';
			$table = 'master_gomusen';
		}
		elseif($data == 'similar')
		{
			$noData = 2;
			$column = '*';
			$table = 'master_similar';
		}
		$count = 1;
		if($noData == 1){
			echo '<table class="table text-center table-sm table-bordered table-hover" id="table">
			<thead class="text-white text-uppercase font-weight-bold blue darken-1">
			<tr>
				<th>No.</th>
				<th>Item</th>
			</tr>
				</thead>
				<tbody>
			';
			$sqlQuery = "SELECT listId, $column FROM $table";
			$query = $conn_db->query($sqlQuery);
				while ($data = $query->fetch_array()) {
					$x = $data[$column];
					$id = $data['listId'];
					echo '<tr onclick="action(&quot;'.$id.','.$x.'&quot;)">
						<td>'.$count.'</td>
						<td>'.$x.'</td>
						</tr>';
					$count++;
				}
		}elseif($noData == 2){
		echo '<table class="table text-center table-sm table-bordered table-hover" id="table">
			<thead class="text-white text-uppercase font-weight-bold blue darken-1">
			<tr>
				<th>No.</th>
				<th>Item 1</th>
				<th>Item 2</th>
			</tr>
				</thead>
				<tbody>
			';
			$sqlQuery = "SELECT * FROM $table";
			$query = $conn_db->query($sqlQuery);
				while ($data = $query->fetch_array()) {
					$x = $data[1];
					$y = $data[2];
					$id = $data[0];
					echo '<tr onclick="action1(&quot;'.$id.','.$x.','.$y.'&quot;)">
						<td>'.$id.'</td>
						<td>'.$x.'</td>
						<td>'.$y.'</td>
						</tr>';
					$count++;
				}
		}
		
		echo '</tbody>
		</table>';
	}
	elseif(isset($_GET['activity']))
	{
		echo '<table class="table table-sm table-bordered table-stripes text-center" id="systemActivity">
			<thead class="blue-gradient text-white font-weight-bold text-uppercase">
				<tr>
					<th>Date/Time of Activity</th>
					<th>Acitivity</th>
					<th>IP Address</th>
				</tr>
			</thead>
			<tbody>
			<tr>';
		$sqlSelect = "SELECT * FROM `system_activity`";
		$querySelected = $conn_db->query($sqlSelect);
		while ($data = $querySelected->fetch_assoc()) {
			echo '<td>'.$data['datetime'].'</td>
				  <td>'.$data['activity'].'</td>
				  <td>'.$data['ipAdd'].'</td>
			</tr>';
		}
		// echo 'yea';
		
	}



?>
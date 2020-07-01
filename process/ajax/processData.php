<?php
	include '../../db/conn.php';
	if(isset($_GET['process']))
	{
		$process = $_GET['process'];
		$master = $_GET['master'];
		$date = date('Y-m-d h:i:s');
		$ipAdd = $_SERVER['REMOTE_ADDR'];
			if($master == '025')
			{
				$table = 'master_025terminal';
				$column = 'terminal';
				$activity = '025 Terminal Master';
			}
			elseif ($master == 'male') 
			{
				$table = 'master_male';
				$column = 'male_terminal';
				$activity = 'Male Terminal Master';
			}
			elseif ($master == 'gomusen') 
			{
				$table = 'master_gomusen';
				$column = 'gomusen';
				$activity = 'Gomusen Master';
			}
			elseif ($master == 'similar') 
			{
				$table = 'master_similar';
				$column = '*';
				$activity = 'Similar Master';
			}
		if($process == 'update')
		{
			$id = $_GET['id'];
			if($master != 'similar')
			{
				$newName = $_GET['newItemName'];
				$sqlUpdate = "UPDATE $table SET $column = '$newName' WHERE listId = '$id'";
				$sqlInsertRec = "INSERT INTO `system_activity`(`ipAdd`, `activity`, `datetime`) VALUES ('$ipAdd','$activity -Updated Item: $newName','$date')";
				$query1 = $conn_db->query($sqlInsertRec);
				$query = $conn_db->query($sqlUpdate);
			}else{
				$newItemName1 = $_GET['newItemName1'];
				$newItemName2 = $_GET['newItemName2'];
				$sqlUpdate = "UPDATE $table SET  `left-right` = '$newItemName1', `right-left` = '$newItemName2' WHERE listId = '$id'";
				$sqlInsertRec = "INSERT INTO `system_activity`(`ipAdd`, `activity`, `datetime`) VALUES ('$ipAdd','$activity -Updated Item1: $newItemName1 $newItemName2','$date')";
				$query1 = $conn_db->query($sqlInsertRec);
				$query = $conn_db->query($sqlUpdate);
			}
				if($query)
				{
					echo 'success';
				}
				else
				{
					echo 'failed';
				}

		}
		elseif($process == 'delete')
		{
			$id = $_GET['id'];
			if($master != 'similar')
			{
				$newName = $_GET['newItemName'];
				$sqlDelete = "DELETE FROM $table WHERE listId = '$id'";
				$query = $conn_db->query($sqlDelete);
				$sqlInsertRec = "INSERT INTO `system_activity`(`ipAdd`, `activity`, `datetime`) VALUES ('$ipAdd','$activity -Deleted Item: $newName','$date')";
				$query1 = $conn_db->query($sqlInsertRec);
			}else
			{
				$sqlDelete = "DELETE FROM $table WHERE listId = '$id'";
				$query = $conn_db->query($sqlDelete);
				$sqlInsertRec = "INSERT INTO `system_activity`(`ipAdd`, `activity`, `datetime`) VALUES ('$ipAdd','$activity -Deleted Item','$date')";
				$query1 = $conn_db->query($sqlInsertRec);
			}
			
				if($query)
				{

					echo 'success';
				}
				else
				{
					echo 'failed';
				}
		}
		elseif($process == 'add')
		{
			if($master != 'similar')
			{
				$newItem = $_GET['newItem'];
				$sqlInsert = "INSERT INTO $table ($column) VALUES ('$newItem')";
				$query = $conn_db->query($sqlInsert);
				$sqlInsertRec = "INSERT INTO `system_activity`(`ipAdd`, `activity`, `datetime`) VALUES ('$ipAdd','$activity -Added New Item: 	$newItem','$date')";
				$query1 = $conn_db->query($sqlInsertRec);
			}else{
				$newItem1 = $_GET['newItem1'];
				$newItem2 = $_GET['newItem2'];
				$sqlInsert = "INSERT INTO $table (`left-right`,`right-left`) VALUES ('$newItem1','$newItem2')";
				$query = $conn_db->query($sqlInsert);
				$sqlInsertRec = "INSERT INTO `system_activity`(`ipAdd`, `activity`, `datetime`) VALUES ('$ipAdd','$activity -Added New Items: $newItem1 , $newItem2','$date')";
				$query1 = $conn_db->query($sqlInsertRec);
			}
				if($query)
				{

					echo 'success';
				}
				else
				{
					echo 'failed';
				}
		}
	}
	else if(isset($_GET['request']))
	{
		$process = $_GET['request'];
		if($process == 'login'){
			$inputPassword = $_GET['password'];
			$sqlGetPass = "SELECT password FROM `master_account` WHERE userName = 'admin'";
			$query = $conn_db->query($sqlGetPass);
			while ($pass = $query->fetch_assoc()) {
				$correctPassword = $pass['password'];
				$correctPassword;
			}
			if($inputPassword == $correctPassword){
				echo 'true';
			}else{
				echo 'false';
			}
		}
	}
?>
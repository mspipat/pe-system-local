<?php
date_default_timezone_set('Asia/Manila');
$conn_db =mysqli_connect('localhost', 'root', '', 'pe_mis');
	if ($conn_db->connect_error) {
		die("Connection failed: " . $conn_db->connect_error);
	}
?>
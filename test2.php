<?php
include 'db/conn.php';
echo $ipAdd = $_SERVER['REMOTE_ADDR'];
// $date = date('Y-m-d h i s');
$sqlCreateTbl = "CREATE TABLE  `temp_$ipAdd`(
  listId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  wire VARCHAR(30) NOT NULL,
  specs VARCHAR(30) NOT NULL);";
  $query = $conn_db->query($sqlCreateTbl);
?>
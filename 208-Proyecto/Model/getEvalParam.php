<?php

require_once( '../Resources/dbConnection.php' );

$dbCon = dbConnection::connect();
$evalId = $_POST['id'];

$query = "select * from HojaEvaluacion where idHojaEvaluacion = $evalId";
$result = $dbCon -> query( $query );
$row = $result -> fetch_assoc();

echo json_encode( $row );

?>

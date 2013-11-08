<?php

require_once( '../Resources/dbConnection.php' );

$dbCon = dbConnection::connect();
$code = $_POST['studentCode'];

$query = "select * from Alumno where codigo=\"$code\";";

$result = $dbCon -> query( $query );
$row = $result -> fetch_assoc();

echo json_encode( $row );

?>

<?php

require_once( '../Resources/dbConnection.php' );

$dbCon = dbConnection::connect();

$query = "select * from Ciclo;";
$result = $dbCon -> query( $query );

$rows = array();
while( $row = $result -> fetch_assoc() ){
    $rows[] = $row;
}

echo json_encode( $rows );

?>

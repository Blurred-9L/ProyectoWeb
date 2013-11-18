<?php

require_once( '../Resources/dbConnection.php' );

$dbCon = dbConnection::connect();

$query = 'select * from Curso';
$result = $dbCon -> query( $query );
while( $row = $result -> fetch_assoc() ){
    $rows[] = $row;
}

//var_dump( $rows );
echo json_encode( $rows );

?>

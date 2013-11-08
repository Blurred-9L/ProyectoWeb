<?php

require_once( '../Resources/dbConnection.php' );

$dbCon = dbConnection::connect();
$query = 'select * from Carrera;';
$result = $dbCon -> query( $query );
while( $row = $result -> fetch_assoc() ){
    $rows[] = $row;
}
echo json_encode( $rows );

?>

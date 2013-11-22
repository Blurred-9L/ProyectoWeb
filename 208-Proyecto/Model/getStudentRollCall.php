<?php

require_once( '../Resources/dbConnection.php' );

$dbCon = dbConnection::connect();

$code = $_POST['studentCode'];

$query = "select fecha, estado from Asistencia inner join AlumnoCurso on
          AlumnoCurso.idAlumnoCurso = Asistencia.idAlumnoCurso inner join
          Alumno on Alumno.idAlumno = AlumnoCurso.idAlumno where
          codigo = \"$code\";";
          
$result = $dbCon -> query( $query );
$rows = array();
while( $row = $result -> fetch_assoc() ){
    $rows[] = $row;
}

echo json_encode( $rows );

?>

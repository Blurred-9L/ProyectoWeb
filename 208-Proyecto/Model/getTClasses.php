<?php

require_once( '../Resources/dbConnection.php' );

$dbCon = dbConnection::connect();

$teacherId = '1'; // This will be taken from the SESSION... hopefully.
$query = "select clave, nombre, nombreAcademia, seccion, ciclo from Curso, Academia, CursoProfesor,
                  Ciclo where Curso.idAcademia = Academia.idAcademia and Curso.idCurso = CursoProfesor.idCurso
                  and CursoProfesor.idCiclo = Ciclo.idCiclo and CursoProfesor.idProfesor = $teacherId;";

$result = $dbCon -> query( $query );
$rows = array();
while( $row = $result -> fetch_assoc() ){
    $rows[] = $row;
}

echo json_encode( $rows );

?>

<?php

require_once( '../Resources/dbConnection.php' );

$dbCon = dbConnection::connect();

session_start();
$teacherId = $_SESSION['user_id']; // This will be taken from the SESSION... hopefully.
$query = "select clave, nombre, nombreAcademia, seccion, ciclo from Curso inner join Academia on
          Curso.idAcademia = Academia.idAcademia inner join CursoProfesor on 
          Curso.idCurso = CursoProfesor.idCurso inner join Ciclo on 
          CursoProfesor.idCiclo = Ciclo.idCiclo where CursoProfesor.idProfesor = 1;";

$result = $dbCon -> query( $query );
$rows = array();
while( $row = $result -> fetch_assoc() ){
    $rows[] = $row;
}

echo json_encode( $rows );

?>

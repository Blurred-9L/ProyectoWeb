<?php

require_once( '../Resources/dbConnection.php' );


$dbCon = dbConnection::connect();
session_start();

$cycleId = $_POST['cycle'];
$studentId = $_SESSION['user_id'];

$query = "select idAlumnoCurso, calificacion, porcentajeAsistencia, clave, Curso.nombre from
          AlumnoCurso inner join CursoProfesor on AlumnoCurso.idCursoProfesor = CursoProfesor.idCursoProfesor
          inner join Ciclo on CursoProfesor.idCiclo = Ciclo.idCiclo inner join Curso on
          CursoProfesor.idCurso = Curso.idCurso where AlumnoCurso.idAlumno = $studentId
          and Ciclo.idCiclo = $cycleId;";
          
$result = $dbCon -> query( $query );
$rows = array();
while( $row = $result -> fetch_assoc() ){
    $rows[] = $row;
}

echo json_encode( $rows );

?>

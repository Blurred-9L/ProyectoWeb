<?php

require_once( '../Resources/dbConnection.php' );

$dbCon = dbConnection::connect();
session_start();

$classInfo = $_POST['info'];
$classArray = explode( '-', $classInfo );
$classKey = $classArray[0];
$classSec = $classArray[1];
$cycleStr = $classArray[2];

$teacherId = $_SESSION['user_id'];

$classQuery = "select * from Curso where clave = \"$classKey\";";
$result = $dbCon -> query( $classQuery );
$classRow = $result -> fetch_assoc();
$classId = $classRow['idCurso'];

$cycleQuery = "select * from Ciclo where ciclo = \"$cycleStr\";";
$result = $dbCon -> query( $cycleQuery );
$cycleRow = $result -> fetch_assoc();
$cycleId = $cycleRow['idCiclo'];

$query = "select concat( nombre, \" \", apellidoP, \" \", apellidoM ) as nombre, codigo,
          nombreCarrera, email from Alumno inner join AlumnoCurso on
          Alumno.idAlumno = AlumnoCurso.idAlumno inner join Carrera on
          Alumno.idCarrera = Carrera.idCarrera inner join CursoProfesor on
          AlumnoCurso.idCursoProfesor = CursoProfesor.idCursoProfesor inner join Ciclo on
          CursoProfesor.idCiclo = Ciclo.idCiclo where CursoProfesor.idCurso = $classId and
          CursoProfesor.idProfesor = $teacherId and CursoProfesor.idCiclo = $cycleId and
          CursoProfesor.seccion = $classSec;";
  
$result = $dbCon -> query( $query );
$rows = array();
while( $row = $result -> fetch_assoc() ){
    $rows[] = $row;
}
echo json_encode( $rows );

?>

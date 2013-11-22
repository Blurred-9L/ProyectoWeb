<?php

require_once( '../Resources/dbConnection.php' );

$dbCon = dbConnection::connect();
session_start();

$classInfo = $_POST['classInfo'];

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

$teacherClassQuery = "select * from CursoProfesor where idCurso = $classId and
                      idProfesor = $teacherId and idCiclo = $cycleId and
                      seccion = $classSec;";
$result = $dbCon -> query( $teacherClassQuery );
$teacherClassRow = $result -> fetch_assoc();
$teacherClassId = $teacherClassRow['idCursoProfesor'];

$query = "select Alumno.codigo, concat( Alumno.nombre, \" \", Alumno.apellidoP, \" \", Alumno.apellidoM ) as
          nombre from Alumno inner join AlumnoCurso on Alumno.idAlumno = AlumnoCurso.idAlumno
          inner join CursoProfesor on AlumnoCurso.idCursoProfesor = CursoProfesor.idCursoProfesor where
          CursoProfesor.idCursoProfesor = $teacherClassId;";

$result = $dbCon -> query( $query );
$rows = array();
while( $row = $result -> fetch_assoc() ){
    $rows[] = $row;
}
echo json_encode( $rows );

?>

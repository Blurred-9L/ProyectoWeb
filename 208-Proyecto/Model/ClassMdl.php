<?php

class ClassMdl{
    private $dbCon;
    
    public function __construct(){
        require_once( 'Resources/dbConnection.php' );
        $this -> dbCon = dbConnection::connect();
    }
    
    public function insertId(){
        return $this -> dbCon -> connection -> insert_id;
    }
    
    public function getStudentByCode( $code ){
        $query = "select * from Alumno where codigo=\"$code\";";
        
        $result = $this -> dbCon -> query( $query );
        $row = $result -> fetch_assoc();
        
        return $row;
    }
    
    public function getStudentInClass( $studentId, $teacherClassId ){
        $query = "select * from AlumnoCurso where idAlumno = $studentId and idCursoProfesor = $teacherClassId;";
        
        $result = $this -> dbCon -> query( $query );
        $row = $result -> fetch_assoc();
        
        return $row;
    }
    
    public function getStudentAssistances( $studentClassId ){
        $query = "select * from Asistencia where idAlumnoCurso = $studentClassId;";
        
        $result = $this -> dbCon -> query( $query );
        $rows = array();
        while( $row = $result -> fetch_assoc() ){
            $rows[] = $row;
        }
        
        return $rows;
    }
    
    public function getClass( $classId ){
        $query = "select * from Curso where idCurso=$classId;";
        
        $result = $this -> dbCon -> query( $query );
        $row = $result -> fetch_assoc();
        
        return $row;
    }
    
    public function getClassByKey( $classKey ){
        $query = "select * from Curso where clave=\"$classKey\";";
        
        $result = $this -> dbCon -> query( $query );
        $row = $result -> fetch_assoc();
        
        return $row;
    }
    
    public function getCycle( $cycleId ){
        $query = "select * from Ciclo where idCiclo=$cycleId;";
        
        $result = $this -> dbCon -> query( $query );
        $row = $result -> fetch_assoc();
        
        return $row;
    }
    
    public function getCycleByStr( $cycleStr ){
        $query = "select * from Ciclo where ciclo=\"$cycleStr\";";
        
        $result = $this -> dbCon -> query( $query );
        $row = $result -> fetch_assoc();
        
        return $row;
    }
    
    public function getTeacherClass( $teacherId, $cycleId, $classId, $section ){
        $query = "select * from CursoProfesor where idCurso = $classId and idProfesor = $teacherId
                  and idCiclo = $cycleId and seccion = $section;";
                  
        $result = $this -> dbCon -> query( $query );
        $row = $result -> fetch_assoc();
        
        return $row;
    }
    
    public function getTeacherClassSchedules( $cycleId, $teacherId, $classId, $teacherClassId ){
        $query = "select Horario.idHorario, nombreDia, cantHoras, inicio from Horario inner join Dia on Horario.dia = Dia.idDia
                  inner join CursoProfesorHorario on Horario.idHorario = CursoProfesorHorario.idHorario
                  inner join CursoProfesor on CursoProfesorHorario.idCursoProfesor = CursoProfesor.idCursoProfesor
                  where CursoProfesor.idCiclo = $cycleId and CursoProfesor.idProfesor = $teacherId and
                  CursoProfesor.idCurso = $classId and CursoProfesor.idCursoProfesor = $teacherClassId;";
                  
        $result = $this -> dbCon -> query( $query );
        $rows = array();
        while( $row = $result -> fetch_assoc() ){
            $rows[] = $row;
        }
        
        return $rows;
    }
    
    public function getTeacherClassEvals( $teacherId, $cycleId, $classId, $teacherClassId ){
        $query = "select idHojaEvaluacion, descripcion, valor, nElems from HojaEvaluacion inner join CursoProfesor on
                  CursoProfesor.idCursoProfesor = HojaEvaluacion.idCursoProfesor where
                  CursoProfesor.idProfesor = $teacherId and CursoProfesor.idCiclo = $cycleId and
                  CursoProfesor.idCurso = $classId and CursoProfesor.idCursoProfesor = $teacherClassId;";
                  
        $result = $this -> dbCon -> query( $query );
        $rows = array();
        while( $row = $result -> fetch_assoc() ){
            $rows[] = $row;
        }
        
        return $rows;
    }
    
    public function getTeacherClasses( $teacherId ){
        $query = "select clave, nombre, nombreAcademia, seccion, ciclo from Curso, Academia, CursoProfesor,
                  Ciclo where Curso.idAcademia = Academia.idAcademia and Curso.idCurso = CursoProfesor.idCurso
                  and CursoProfesor.idCiclo = Ciclo.idCiclo and CursoProfesor.idProfesor = $teacherId;";
                  
        $result = $this -> dbCon -> query( $query );
        $rows = array();
        while( $row = $result -> fetch_assoc() ){
            $rows[] = $row;
        }
        
        return $rows;
    }
    
    public function getLatestCycle(){
        $query = 'select max(ciclo) as ciclo from Ciclo;';
        
        $result = $this -> dbCon -> query( $query );
        $row = $result -> fetch_assoc();
        $cycle = $row['ciclo'];

        $query = "select idCiclo from Ciclo where ciclo=\"$cycle\";";
        $result = $this -> dbCon -> query( $query );
        $row = $result -> fetch_assoc();
        
        return $row['idCiclo'];
    }
    
    public function getClassSectionValue( $key, $teacherId, $cycleId ){
        $query = "select max(seccion) as seccion from CursoProfesor where idCurso=$key and
                  idProfesor=$teacherId and idCiclo=$cycleId;";
                  
        $result = $this -> dbCon -> query( $query );
        $row = $result -> fetch_assoc();
        
        return $row;
    }
    
    public function registerNewClass( $key, $teacher, $cycle, $section ){
        $query = "insert into CursoProfesor( idCurso, idProfesor, idCiclo, seccion )
                  values( $key, $teacher, $cycle, $section );";
        
        $result = $this -> dbCon -> query( $query );
        
        return $result;
    }
    
    public function scheduleExists( $day, $start, $duration ){
        $query = "select * from Horario where dia=$day and inicio=$start and cantHoras=$duration;";
        
        $result = $this -> dbCon -> query( $query );
        $row = $result -> fetch_assoc();
        
        if( is_null( $row ) ){
            return FALSE;
        }
        else{
            return $row;
        }
    }
    
    public function registerSchedule( $day, $start, $duration ){
        $query = "insert into Horario( dia, cantHoras, inicio ) values( $day, $duration, $start );";
        
        $result = $this -> dbCon -> query( $query );
        
        return $result;
    }
    
    public function registerClassSchedule( $teacherClassId, $schedule ){
        $query = "insert into CursoProfesorHorario( idHorario, idCursoProfesor ) values( $schedule, $teacherClassId );";
        
        $result = $this -> dbCon -> query( $query );
        
        return $result;
    }
    
    public function registerEvaluation( $act, $val, $nElems, $teacherClassId ){
        $query = "insert into HojaEvaluacion( descripcion, valor, nElems, idCursoProfesor ) values
                  ( \"$act\", $val, $nElems, $teacherClassId );";
                  
        $result = $this -> dbCon -> query( $query );
        
        return $result;
    }
    
    public function registerAssistance( $studentClassId, $date ){
        $query = "update Asistencia set estado = TRUE where
                  idAlumnoCurso = $studentClassId and fecha = \"$date\";";
                  
        $result = $this -> dbCon -> query( $query );
        
        return $result;
    }
    
    public function updateStudentClassAssistances( $studentClassId, $percentage ){
        $query = "update AlumnoCurso set porcentajeAsistencia = $percentage where idAlumnoCurso = $studentClassId;";
        
        $result = $this -> dbCon -> query( $query );
        
        return $result;
    }
}

?>

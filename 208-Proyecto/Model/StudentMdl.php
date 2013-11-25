<?php

class StudentMdl{
    private $dbCon;
    
    public function __construct(){
        require_once( 'Resources/dbConnection.php' );
        $this -> dbCon = dbConnection::connect();
    }
    
    public function insertId(){
        return $this -> dbCon -> connection -> insert_id;
    }
    
    public function register( $code, $name, $last1, $last2, $mail, $major, $pass, $phone, $url, $github ){
        $newPass = sha1( $pass );
        $query = "insert into Alumno( codigo, nombre, apellidoP, apellidoM, email, idCarrera, password,
                  celular, paginaWeb, github ) values (\"$code\", \"$name\", \"$last1\", \"$last2\",
                  \"$mail\", $major, \"$newPass\", ";
                  
        $query .= ( isset( $phone ) )? "\"$phone\", " : 'NULL, ';
        $query .= ( isset( $url ) )? "\"$url\", " : 'NULL, ';
        $query .= ( isset( $github ) )? "\"$github\" );" : 'NULL );';
        
        $result = $this -> dbCon -> query( $query );
        
        return $result;
    }
    
    public function signUpToClass( $studentId, $teacherClassId ){
        $query = "insert into AlumnoCurso( idAlumno, idCursoProfesor ) values
                  ( $studentId, $teacherClassId );";
                  
        $result = $this -> dbCon -> query( $query );
        
        return $result;
    }
    
    public function createEvalElem( $studentClassId, $evalPageId, $numElems ){
        $correctQueries = 0;
        $query = "insert into ElemCalificacion( idHojaEvaluacion, idAlumnoCurso )
                  values( $evalPageId, $studentClassId );";
        
        for( $i = 0; $i < $numElems; $i += 1 ){
            $result = $this -> dbCon -> query( $query );
            if( $result === TRUE ){
                $correctQueries += 1;
            }
        }
        
        return $correctQueries;
    }
    
    public function registerClassDays( $classDays, $studentClassId ){
        $correctQueries = 0;
        
        foreach( $classDays as $class ){
            $dateStr = $class -> format( 'Y-m-d' );
            $query = "insert into Asistencia( idAlumnoCurso, fecha, estado )
                      values( $studentClassId, \"$dateStr\", FALSE );";
            $result = $this -> dbCon -> query( $query );
            if( $result === TRUE ){
                $correctQueries += 1;
            }
        }
        
        return $correctQueries;
    }
    
    public function update( $code, $mail, $phone, $url, $github ){
        $query = "update Alumno set email=\"$mail\", celular=\"$phone\", paginaWeb=\"$url\", github=\"$github\" 
                  where codigo=\"$code\";";
                  
        $result = $this -> dbCon -> query( $query );
        
        return $result;
    }
    
    public function updateEvalElem( $evalElemId, $grade ){
        $query = "update ElemCalificacion set calificacion = $grade where idElemCalificacion = $evalElemId;";
        
        $result = $this -> dbCon -> query( $query );
        
        return $result;
    }
    
    public function updatePassword( $code, $storedPass ){
        $query = "update Alumno set password = \"$storedPass\" where codigo = \"$code\";";
        
        $result = $this -> dbCon -> query( $query );
        
        return $result;
    }
    
    public function updateGrade( $studentClassId, $grade ){
        $query = "update AlumnoCurso set calificacion = $grade where idAlumnoCurso = $studentClassId;";
        
        $result = $this -> dbCon -> query( $query );
        
        return $result;
    }
    
    public function getMajorStr( $major ){
        $query = "select nombreCarrera from Carrera where idCarrera = $major;";
        
        $result = $this -> dbCon -> query( $query );
        $row = $result -> fetch_assoc();
        
        return $row;
    }
    
    public function getStudent( $code ){
        $query = "select * from Alumno join Carrera on Alumno.idCarrera=Carrera.idCarrera and codigo=\"$code\";";
        
        $result = $this -> dbCon -> query( $query );
        $row = $result -> fetch_assoc();
        
        return $row;
    }
    
    public function getClass( $classKey ){
        $query = "select * from Curso where clave=\"$classKey\";";
        
        $result = $this -> dbCon -> query( $query );
        $row = $result -> fetch_assoc();
        
        return $row;
    }
    
    public function getClassName( $studentClassId ){
        $query = "select nombre, clave from Curso inner join CursoProfesor on
                  CursoProfesor.idCurso = Curso.idCurso inner join AlumnoCurso on
                  AlumnoCurso.idCursoProfesor = CursoProfesor.idCursoProfesor
                  where idAlumnoCurso = $studentClassId;";
        $result = $this -> dbCon -> query( $query );
        $row = $result -> fetch_assoc();
        
        return $row['clave'] . ' ' . $row['nombre'];
    }
    
    public function getCycle( $cycleStr ){
        $query = "select * from Ciclo where ciclo=\"$cycleStr\";";
        
        $result = $this -> dbCon -> query( $query );
        $row = $result -> fetch_assoc();
        
        return $row;
    }
    
    public function getAll(){
        $query = 'select * from Alumno join Carrera on Alumno.idCarrera=Carrera.idCarrera;';
        
        $result = $this -> dbCon -> query( $query );
        
        $rows = NULL;
        while( $row = $result -> fetch_assoc() ){
            $rows[] = $row;
        }
        
        return $rows;
    }
    
    public function getTeacherClass( $classId, $teacherId, $cycleId, $classSec ){
        $query = "select * from CursoProfesor where idCurso = $classId and idProfesor = $teacherId
                  and idCiclo = $cycleId and seccion = $classSec;";
                  
        $result = $this -> dbCon -> query( $query );
        $row = $result -> fetch_assoc();
        
        return $row;
    }
    
    public function getEvalPages( $teacherClassId ){
        $query = "select * from HojaEvaluacion where idCursoProfesor = $teacherClassId;";
        
        $result = $this -> dbCon -> query( $query );
        $rows = array();
        while( $row = $result -> fetch_assoc() ){
            $rows[] = $row;
        }
        
        return $rows;
    }
    
    public function getCycleRange( $cycleId ){
        $query = "select fechaInicio, fechaFin from Ciclo where idCiclo = $cycleId;";
        
        $result = $this -> dbCon -> query( $query );
        $row = $result -> fetch_assoc();
        
        return $row;
    }
    
    public function getStartDayNum( $cycleId ){
        $query = "select date_format( fechaInicio, \"%w\" ) as nDia from Ciclo where idCiclo = $cycleId;";
        
        $result = $this -> dbCon -> query( $query );
        $row = $result -> fetch_assoc();
        
        return $row;
    }
    
    public function getCycleFreeDays( $cycleId ){
        $query = "select fecha from Asueto inner join Ciclo on Asueto.idCiclo = Ciclo.idCiclo and
                  Ciclo.idCiclo = $cycleId;";
                  
        $result = $this -> dbCon -> query( $query );
        $rows = array();
        while( $row = $result -> fetch_assoc() ){
            $rows[] = $row['fecha'];
        }
        
        return $rows;
    }
    
    public function getClassDayNums( $teacherClassId ){
        $query = "select dia from Horario inner join CursoProfesorHorario on
                  Horario.idHorario = CursoProfesorHorario.idHorario where
                  CursoProfesorHorario.idCursoProfesor = $teacherClassId";
                  
        $result = $this -> dbCon -> query( $query );
        $rows = array();
        while( $row = $result -> fetch_assoc() ){
            $rows[] = $row['dia'];
        }
        
        return $rows;
    }
    
    public function getStudentFromClass( $studentCode, $teacherClassId ){
        $query = "select idAlumnoCurso, calificacion, porcentajeAsistencia from AlumnoCurso
                  inner join Alumno on AlumnoCurso.idAlumno = Alumno.idAlumno inner join
                  CursoProfesor on CursoProfesor.idCursoProfesor = AlumnoCurso.idCursoProfesor
                  where CursoProfesor.idCursoProfesor = $teacherClassId and Alumno.codigo = \"$studentCode\";";
        
        $result = $this -> dbCon -> query( $query );
        $row = $result -> fetch_assoc();
        
        return $row;
    }
    
    public function getStudentClass( $studentClassId ){
        $query = "select * from AlumnoCurso where idAlumnoCurso = $studentClassId;";
        
        $result = $this -> dbCon -> query( $query );
        $row = $result -> fetch_assoc();
        
        return $row;
    }
    
    public function getStudentClassAssistances( $studentClassId ){
        $query = "select * from Asistencia where idAlumnoCurso = $studentClassId;";
        
        $result = $this -> dbCon -> query( $query );
        $rows = array();
        while( $row = $result -> fetch_assoc() ){
            $rows[] = $row;
        }
        
        return $rows;
    }
    
    public function getStudentEvalElems( $evalPageId, $studentClassId ){
        $query = "select * from ElemCalificacion where idHojaEvaluacion = $evalPageId and
                  idAlumnoCurso = $studentClassId;";
                  
        $result = $this -> dbCon -> query( $query );
        $rows = array();
        while( $row = $result -> fetch_assoc() ){
            $rows[] = $row;
        }
        
        return $rows;
    }
}

?>

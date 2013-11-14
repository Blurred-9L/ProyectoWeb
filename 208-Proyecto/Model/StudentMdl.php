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
    
    public function signUpToClass( $studentId, $classId, $teacherId, $cycleId, $classSec ){
        $query = "insert into AlumnoCurso( idAlumno, idCurso, idProfesor, idCiclo, seccion ) values
                  ( $studentId, $classId, $teacherId, $cycleId, $classSec );";
                  
        $result = $this -> dbCon -> query( $query );
        
        return $result;
    }
    
    public function update( $code, $mail, $phone, $url, $github ){
        $query = "update Alumno set email=\"$mail\", celular=\"$phone\", paginaWeb=\"$url\", github=\"$github\" 
                  where codigo=\"$code\";";
                  
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
}

?>

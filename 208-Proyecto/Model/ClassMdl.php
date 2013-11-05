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
        $query = "insert into CursoProfesor values( $key, $teacher, $section, $cycle );";
        
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
    
    public function registerClassSchedule( $key, $teacher, $schedule ){
        $query = "insert into CursoProfesorHorario values( $key, $teacher, $schedule );";
        
        $result = $this -> dbCon -> query( $query );
        
        return $result;
    }
    
    public function registerEvaluation( $act, $val, $nElems, $key, $teacher, $cycle ){
        $query = "insert into HojaEvaluacion( descripcion, valor, nElems, idCurso, idProfesor, idCiclo ) values
                  ( \"$act\", $val, $nElems, $key, $teacher, $cycle );";
                  
        $result = $this -> dbCon -> query( $query );
        
        return $result;
    }
}

?>

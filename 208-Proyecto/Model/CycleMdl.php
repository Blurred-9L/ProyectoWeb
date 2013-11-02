<?php

class CycleMdl{
    private $dbCon;
    
    public function __construct(){
        require_once( 'Resources/dbConnection.php' );
        $this -> dbCon = dbConnection::connect();
    }
    
    public function register( $year, $half, $start, $end ){
        $cycle = $year . $half;
        $query = "insert into Ciclo( ciclo, anio, calendario, fechaInicio, fechaFin )
                  values( \"$cycle\", \"$year\", \"$half\", \"$start\", \"$end\" );";
                  
        $result = $this -> dbCon -> query( $query );
        
        return $result;
    }
}

?>

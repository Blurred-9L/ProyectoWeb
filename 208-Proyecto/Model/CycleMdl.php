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
    
    public function registerFreeDays( $free_days ){
        $cycleId = $this -> dbCon -> connection -> insert_id;
        $count = 0;
        
        foreach( $free_days as $free_day ){
            $query = "insert into Asueto( fecha, idCiclo ) values( \"$free_day\", $cycleId );";
            if( $this -> dbCon -> query( $query ) === TRUE ){
                $count += 1;
            }
        }
        
        return $count;
    }
    
    public function addFreeDays( $free_days, $cycleId ){
        $count = 0;
        
        foreach( $free_days as $free_day ){
            $query = "insert into Asueto( fecha, idCiclo ) values( \"$free_day\", $cycleId );";
            if( $this -> dbCon -> query( $query ) === TRUE ){
                $count += 1;
            }
        }
        
        return $count;
    }
    
    public function getCycle( $year, $half ){
        $cycle = $year . $half;
        $query = "select * from Ciclo where ciclo=\"$cycle\";";
        
        $result = $this -> dbCon -> query( $query );
        $row = $result -> fetch_assoc();
        
        return $row;
    }
    
    public function getCycle2( $cycle ){
        $query = "select * from Ciclo where ciclo=\"$cycle\";";
        
        $result = $this -> dbCon -> query( $query );
        $row = $result -> fetch_assoc();
        
        return $row;
    }
    
    public function getFreeDays( $year, $half ){
        $cycleRow = $this -> getCycle( $year, $half );
        $cycleId = $cycleRow['idCiclo'];
        $query = "select fecha from Asueto where idCiclo=$cycleId;";
        
        $result = $this -> dbCon -> query( $query );
        $rows = array();
        while( $row = $result -> fetch_assoc() ){
            $rows[] = $row;
        }
        
        return $rows;
    }
    
    public function eraseFreeDays( $free_days, $cycleId ){
        $count = 0;
        
        foreach( $free_days as $freeDay ){
            $query = "delete from Asueto where idCiclo=$cycleId and fecha=\"$freeDay\";";
            $result = $this -> dbCon -> query( $query );
            if( $result === TRUE ){
                $count += 1;
            }
        }
        
        return $count;
    }
}

?>

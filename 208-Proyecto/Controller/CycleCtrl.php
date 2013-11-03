<?php

class CycleCtrl{
    private $model;
    
    public function __construct(){
        require_once( 'Model/CycleMdl.php' );
        $this -> model = new CycleMdl();
    }
    
    public function execute(){
        switch( $_GET['action'] ){
            case 'new':
                $this -> newCycle();
                break;
            case 'addfd':
                $this -> moreFreeDays();
                break;
            case 'erase':
                $this -> eraseFreeDays();
                break;
        }
    }
    
    public function showCycle( $year, $half, $start, $end ){
        $cycle = $year . $half;
        $view = file_get_contents( 'View/Admin/asuetosCiclo.html' );
        
        $dict = array( '*start*' => $start, '*end*' => $end );
        $view = strtr( $view, $dict );
        $view = str_replace( '<input type="text" id="cycle" name="cycle" />',
                             "<input type=\"text\" id=\"cycle\" name=\"cycle\" value=\"$cycle\" />",
                             $view );
        
        $start = strrpos( $view, '<tr>' );
        $end = strrpos( $view, '</tr>' ) + 5;
        $tableRow = substr( $view, $start, $end - $start );
        
        $freeDays = $this -> model -> getFreeDays( $year, $half );
        $rows = '';
        $count = 0;
        if( !empty( $freeDays ) ){
            foreach( $freeDays as $freeDay ){
                $newTableRow = $tableRow;
                $dict = array( '*count*' => $count, '*date*' => $freeDay['fecha'] );
                $newTableRow = strtr( $newTableRow, $dict );
                $rows .= $newTableRow;
                $count += 1;
            }
        } 
        
        $view = str_replace( $tableRow, $rows, $view );
        
        echo $view;
    }
    
    private function newCycle(){
        if( empty( $_POST ) ){
            require_once( 'View/Admin/nuevoCiclo.html' );
        }
        else{
            $year = $_POST['new-cycle-year'];
            $half = $_POST['new-cycle-half'];
            $start = $_POST['new-cycle-start'];
            $end = $_POST['new-cycle-end'];
            
            $result = $this -> model -> register( $year, $half, $start, $end );
            
            if( $result === TRUE ){
                $result2 = $this -> addFreeDays( $year, $half );
                if( $result2 === TRUE || $result2 === 0 ){
                    $this -> showCycle( $year, $half, $start, $end );
                }
                else{
                    echo "Error";
                }
            }
            else{
                echo "Error";
            }
        }
    }
    
    private function addFreeDays( $year, $half ){
        $cycle = $year . $half;
        $free_days = array();
        $count = 0;
        $key = 'new-free-day-' . $count;
        
        while( array_key_exists( $key, $_POST ) ){
            $free_days[] = $_POST[$key];
            $count += 1;
            $key = 'new-free-day-' . $count;
        }
        
        if( $count > 0 ){
            $result = $this -> model -> registerFreeDays( $free_days );
            
            if( $result == $count ){
                return TRUE;
            }
            else{
                return FALSE;
            }
        }
        else{
            return 0;
        }
    }
    
    private function moreFreeDays(){
        $count = 0;
        $key = 'new-free-day-' . $count;
        
        while( array_key_exists( $key, $_POST ) ){
            $free_days[] = $_POST[$key];
            $count += 1;
            $key = 'new-free-day-' . $count;
        }
        $cycle = $_POST['cycle'];
        $cycleRow = $this -> model -> getCycle2( $cycle );
        $cycleId = $cycleRow['idCiclo'];
        
        $result = $this -> model -> addFreeDays( $free_days, $cycleId );
        if( $result == $count ){
            $this -> showCycle( $cycleRow['anio'], $cycleRow['calendario'], $cycleRow['fechaInicio'], $cycleRow['fechaFin'] );
        }
        else{
            echo "Error";
        }
        // Count is always bigger than 0.
    }
    
    private function eraseFreeDays(){
        $count = 0;
        $key = 'date-' . $count;
        
        while( array_key_exists( $key, $_POST ) ){
            $free_days[] = $_POST[$key];
            $count += 1;
            $key = 'date-' . $count;
        }
        $cycle = $_POST['cycle'];
        $cycleRow = $this -> model -> getCycle2( $cycle );
        $cycleId = $cycleRow['idCiclo'];
        
        $result = $this -> model -> eraseFreeDays( $free_days, $cycleId );
        if( $result == $count ){
            $this -> showCycle( $cycleRow['anio'], $cycleRow['calendario'], $cycleRow['fechaInicio'], $cycleRow['fechaFin'] );
        }
        else{
            echo "Error";
        }
    }
}

?>

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
        }
    }
    
    public function showCycle( $year, $half, $start, $end ){
        $cycle = $year . $half;
        $view = file_get_contents( 'View/Admin/asuetosCiclo.html' );
        
        $dict = array( '*cycle*' => $cycle, '*start*' => $start, '*end*' => $end );
        $view = strtr( $view, $dict );
        
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
                $this -> showCycle( $year, $half, $start, $end );
            }
            else{
                echo "Error";
            }
        }
    }
}

?>

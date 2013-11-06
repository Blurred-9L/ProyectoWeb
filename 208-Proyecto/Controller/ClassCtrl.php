<?php

class ClassCtrl{
    private $model;
    
    public function __construct(){
        require_once( 'Model/ClassMdl.php' );
        $this -> model = new ClassMdl();
    }
    
    public function execute(){
        switch( $_GET['action'] ){
            case 'new':
                $this -> newClass();
                break;
        }
    }
    
    private function newClass(){
        if( empty( $_POST ) ){
            require_once( 'View/Profesores/nuevoCurso.html' );
        }
        else{
            $key = $_POST['new-class-key'];
            $prof = '1'; // This will change in the future to the prof's info.
            $cycle = $this -> model -> getLatestCycle();
            $row = $this -> model -> getClassSectionValue( $key, $prof, $cycle );
            if( isset( $row['seccion'] ) ){
                $section = $row['seccion'];
            }
            else{
                $section = "1";
            }
            
            $result = $this -> setUpNewClass( $key, $prof, $cycle, $section );
            if( $result === TRUE ){
                $this -> processShowClassView( $key, $prof, $cycle, $section );
            }
            else{
                echo "Not OK";
            }
        }
    }
    
    public function processShowClassView( $key, $prof, $cycle, $section ){
        $view = file_get_contents( 'View/Profesores/verCurso.html' );
        $cycleRow = $this -> model -> getCycle( $cycle );
        $classRow = $this -> model -> getClass( $key );
        
        $dict = array( '*class*' => $classRow['nombre'], '*cycle*' => $cycleRow['ciclo'], '*section*' => $section );
        $view = strtr( $view, $dict );
        
        $start = strpos( $view, '<tr class="sched-tr">' );
        $subView = substr( $view, $start );
        $trEnd = strpos( $subView, '</tr>' ) + 5;
        $tableRow = substr( $view, $start, $trEnd );
        
        $schedules = $this -> model -> getTeacherClassSchedules( $cycle, $prof, $key );
        $rows = '';
        if( !empty( $schedules ) ){
            foreach( $schedules as $sched ){
                $newTableRow = $tableRow;
                $dict = array( '*day*' => $sched['nombreDia'], '*start*' => $sched['inicio'], '*duration*' => $sched['cantHoras'] );
                $newTableRow = strtr( $newTableRow, $dict );
                $rows .= $newTableRow;
            }
        }
        $view = str_replace( $tableRow, $rows, $view );
        
        $start = strpos( $view, '<tr class="eval-tr">' );
        $subView = substr( $view, $start );
        $trEnd = strpos( $subView, '</tr>' ) + 5;
        $tableRow = substr( $view, $start, $trEnd );
        
        $evals = $this -> model -> getTeacherClassEvals( $prof, $cycle, $key );
        $rows = '';
        if( !empty( $evals ) ){
            foreach( $evals as $eval ){
                $newTableRow = $tableRow;
                $dict = array( '*evalname*' => $eval['descripcion'], '*value*' => $eval['valor'] );
                $newTableRow = strtr( $newTableRow, $dict );
                $rows .= $newTableRow;
            }
        }
        $view = str_replace( $tableRow, $rows, $view );
        
        echo $view;
    }
    
    private function setUpNewClass( $key, $teacher, $cycle, $section ){
        $result = $this -> model -> registerNewClass( $key, $teacher, $cycle, $section );
        
        if( $result === TRUE ){
            return $this -> setUpSchedules( $key, $teacher, $cycle );
        }
        else{
            echo "Error al registrar Curso.";
            return FALSE;
        }
    }
    
    private function setUpSchedules( $key, $teacher, $cycle ){
        $count = 0;
        $indexKey = 'new-class-day' . $count;
        $fail = FALSE;
        
        while( array_key_exists( $indexKey, $_POST ) ){
            $day = $_POST['new-class-day' . $count];
            $start = $_POST['new-class-start' . $count];
            $duration = $_POST['new-class-duration' . $count];
            $scheduleRow = $this -> model -> scheduleExists( $day, $start, $duration );
            if( $scheduleRow === FALSE ){
                $result = $this -> model -> registerSchedule( $day, $start, $duration );
                if( $result === TRUE ){
                    $schedule = $this -> model -> insertId();
                }
                else{
                    $fail = TRUE;
                    break;
                }
            }
            else{
                $schedule = $scheduleRow['idHorario'];
            }
            $result = $this -> model -> registerClassSchedule( $key, $teacher, $schedule );
            if( $result === FALSE ){
                $fail = TRUE;
                break;
            }
            $count += 1;
            $indexKey = 'new-class-day' . $count;
        }
        
        if( !$fail ){
            return $this -> setUpEvaluations( $key, $teacher, $cycle );
        }
        else{
            echo "Error al registrar Horarios.";
            return FALSE;
        }
    }
    
    private function setUpEvaluations( $key, $teacher, $cycle ){
        $count = 0;
        $indexKey = 'new-class-act' . $count;
        $fail = FALSE;
        
        while( array_key_exists( $indexKey, $_POST ) ){
            $act = $_POST['new-class-act' . $count];
            $val = $_POST['new-class-val' . $count];
            if( array_key_exists( 'page-columns' . $count, $_POST ) ){
                $nElems = $_POST['page-columns' . $count];
            }
            else{
                $nElems = '1';
            }
            $result = $this -> model -> registerEvaluation( $act, $val, $nElems, $key, $teacher, $cycle );
            if( $result === FALSE ){
                $fail = TRUE;
                break;
            }
            $count += 1;
            $indexKey = 'new-class-act' . $count;
        }
        
        if( !$fail ){
            return TRUE;
        }
        else{
            echo "Error al registrar Hojas de Evaluacion.";
            return FALSE;
        }
    }
}

?>

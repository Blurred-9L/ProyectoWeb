<?php

require_once( 'Controller/DefaultCtrl.php' );

class ClassCtrl extends DefaultCtrl{
    private $model;
    
    public function __construct(){
        parent::__construct();
        require_once( 'Model/ClassMdl.php' );
        $this -> model = new ClassMdl();
    }
    
    public function execute(){
        switch( $_GET['action'] ){
            case 'new':
                $this -> newClass();
                break;
            case 't-all':
                $this -> showTeacherAll();
                break;
            case 'show':
                $this -> showClass();
                break;
        }
    }
    
    private function newClass(){
        if( empty( $_POST ) ){
            require_once( 'View/Profesores/nuevoCurso.html' );
        }
        else{
            $key = $_POST['new-class-key'];
            $prof = $_SESSION['user_id'];
            $cycle = $this -> model -> getLatestCycle();
            $row = $this -> model -> getClassSectionValue( $key, $prof, $cycle );
            if( isset( $row['seccion'] ) ){
                $section = $row['seccion'] + 1;
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
        $teacherClassRow = $this -> model -> getTeacherClass( $prof, $cycle, $key, $section );
        
        $dict = array( '*class*' => $classRow['nombre'], '*cycle*' => $cycleRow['ciclo'], '*section*' => $section );
        $view = strtr( $view, $dict );
        
        $start = strpos( $view, '<tr class="sched-tr">' );
        $subView = substr( $view, $start );
        $trEnd = strpos( $subView, '</tr>' ) + 5;
        $tableRow = substr( $view, $start, $trEnd );
        
        $schedules = $this -> model -> getTeacherClassSchedules( $cycle, $prof, $key, $teacherClassRow['idCursoProfesor'] );
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
        
        $evals = $this -> model -> getTeacherClassEvals( $prof, $cycle, $key, $teacherClassRow['idCursoProfesor'] );
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
            return $this -> setUpSchedules();
        }
        else{
            echo "Error al registrar Curso.";
            return FALSE;
        }
    }
    
    private function setUpSchedules(){
        $count = 0;
        $indexKey = 'new-class-day' . $count;
        $fail = FALSE;
        $teacherClassId = $this -> model -> insertId();
        
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
            $result = $this -> model -> registerClassSchedule( $teacherClassId, $schedule );
            if( $result === FALSE ){
                $fail = TRUE;
                break;
            }
            $count += 1;
            $indexKey = 'new-class-day' . $count;
        }
        
        if( !$fail ){
            return $this -> setUpEvaluations( $teacherClassId );
        }
        else{
            echo "Error al registrar Horarios.";
            return FALSE;
        }
    }
    
    private function setUpEvaluations( $teacherClassId ){
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
            $result = $this -> model -> registerEvaluation( $act, $val, $nElems, $teacherClassId );
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
    
    private function showTeacherAll(){
        $view = file_get_contents( 'View/Profesores/verCursos.html' );
        
        $teacherId = $_SESSION['user_id'];
        $start = strrpos( $view, '<tr>' );
        $end = strrpos( $view, '</tr>' ) + 5;
        $tableRow = substr( $view, $start, $end - $start );
        
        $classes = $this -> model -> getTeacherClasses( $teacherId );
        $rows = '';
        if( !empty( $classes ) ){
            $count = 0;
            foreach( $classes as $class ){
                $newTableRow = $tableRow;
                $dict = array( '*id*' => $class['clave'], '*name*' => $class['nombre'], '*sec*' => $class['seccion'],
                               '*cycle*' => $class['ciclo'], '*academy*' => $class['nombreAcademia'], '*count*' => $count );
                $newTableRow = strtr( $newTableRow, $dict );
                $rows .= $newTableRow;
                $count += 1;
            }
        }
        
        $view = str_replace( $tableRow, $rows, $view );
        
        echo $view;
    }
    
    private function showClass(){
        $classKey = $_POST['classCode'];
        $section = $_POST['classSec'];
        $cycle = $_POST['classCycle'];
        $teacherId = $_SESSION['user_id'];
        
        $classRow = $this -> model -> getClassByKey( $classKey );
        $classId = $classRow['idCurso'];
        
        $cycleRow = $this -> model -> getCycleByStr( $cycle );
        $cycleId = $cycleRow['idCiclo'];
        
        $this -> processShowClassView( $classId, $teacherId, $cycleId, $section );
    }
}

?>

<?php

/** @file ClassCtrl.php
    @author Rodrigo Fuentes Hernandez <earthjenei@gmail.com>

    This file contains the Controller class for
    interaction with 'class' (as in school courses)
    operations.
*/

require_once( 'Controller/DefaultCtrl.php' );

/** @brief The ClassCtrl class is the Controller class
    for class operations.
    
    The ClassCtrl class is a direct child class of the
    DefaultCtrl class. The ClassCtrl class is in charge
    of functioning as the controller of class operations
    in an MVC design patter. Supported operations are:
    creating a new class, showing all of a teacher's
    classes, showing specific info about a teacher's
    class, seeing the class' roll and taking the class'
    roll.
*/
class ClassCtrl extends DefaultCtrl{
    private $model;             ///< Class model
    
    /** @brief ClassCtrl constructor.
    
        ClassCtrl constructor. Calls its parent constructor
        to initialize the session as well as initializing
        its own model.
    */
    public function __construct(){
        parent::__construct();
        require_once( 'Model/ClassMdl.php' );
        $this -> model = new ClassMdl();
    }
    
    /** @brief Selects the action to do depending on the
        received GET action.
        
        The execute() method is in charge of selecting the
        correct action according the value given during
        a GET request to this controller. Permissions are
        verified before executing the action, redirecting
        the user to the login view if the user does not have
        the required permissions to perform that action.
    */
    public function execute(){
        switch( $_GET['action'] ){
            case 'new':
                if( $this -> checkPermissions( 'brigadier' ) ){
                    $this -> newClass();
                }
                else{
                    header( 'Location: index.php?ctrl=login&action=login' );
                }
                break;
            case 't-all':
                if( $this -> checkPermissions( 'brigadier' ) ){
                    $this -> showTeacherAll();
                }
                else{
                    header( 'Location: index.php?ctrl=login&action=login' );
                }
                break;
            case 'show':
                if( $this -> checkPermissions( 'brigadier' ) ){
                    $this -> showClass();
                }
                else{
                    header( 'Location: index.php?ctrl=login&action=login' );
                }
                break;
            case 'see-roll':
                if( $this -> checkPermissions( 'brigadier' ) ){
                    $this -> getClassRollView();
                }
                else{
                    header( 'Location: index.php?ctrl=login&action=login' );
                }
                break;
            case 'take-roll':
                if( $this -> checkPermissions( 'brigadier' ) ){
                    $this -> takeClassRoll();
                }
                else{
                    header( 'Location: index.php?ctrl=login&action=login' );
                }
                break;
            case 'clone':
                if( $this -> checkPermissions( 'brigadier' ) ){
                    $this -> cloneClass();
                }
                else{
                    header( 'Location: index.php?ctrl=login&action=login' );
                }
                break;
        }
    }
    
    /** @brief Creates a new class for the current teacher.
    
        The newClass() method is called in order to insert a new
        class for the current teacher. Several things are processed
        during an insertion of a teacher's class, including the
        class' schedules and evaluation parameters. 
    */
    protected function newClass(){
        if( empty( $_POST ) ){
            /// Loads view if no data has been received.
            require_once( 'View/Profesores/nuevoCurso.html' );
        }
        else{
            $key = $_POST['new-class-key'];
            $prof = $_SESSION['user_id'];
            $cycle = $this -> model -> getLatestCycle();
            /// Gets latest section value.
            $row = $this -> model -> getClassSectionValue( $key, $prof, $cycle );
            if( isset( $row['seccion'] ) ){
                $section = $row['seccion'] + 1;
            }
            else{
                $section = "1";
            }
            
            $result = $this -> setUpNewClass( $key, $prof, $cycle, $section );
            if( $result === TRUE ){
                /// Loads view.
                $this -> processShowClassView( $key, $prof, $cycle, $section );
            }
            else{
                echo "Not OK";
            }
        }
    }
    
    /** @brief Processes view to show all classes related to a teacher.
    
        The processShowClassView() method replaces values on a template
        in order to show the main info from an specific class belonging
        to the current active teacher.
        @param key : The class respective key.
        @param prof : The id of the current active teacher.
        @param cycle : The current active cycle string.
        @param section : The inserted class' section.
    */
    public function processShowClassView( $key, $prof, $cycle, $section ){
        $view = file_get_contents( 'View/Profesores/verCurso.html' );
        /// Gets cycle information.
        $cycleRow = $this -> model -> getCycle( $cycle );
        /// Gets related class information.
        $classRow = $this -> model -> getClass( $key );
        /// Gets this class information.
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
    
    /** @brief Handles the insertion of the new class on the database.
    
        The setUpNewClass() method will be in charge of making the insertion
        of the new class on the database, and, if the insertion was successful,
        continue with the next required operations: inserting the class'
        schedules and evaluation parameters.
        @param key : The class respective key.
        @param prof : The id of the current active teacher.
        @param cycle : The current active cycle string.
        @param section : The inserted class' section.
        @return Returns TRUE if this and all succeeding operation are
        successful, otherwise, returns FALSE.
        @sa setUpSchedules()
        @sa setUpEvaluations()
    */
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
    
    /** @brief Handles the insertion of the new class' schedules on
        the database.
        
        The setUpSchedules() method takes care of receiving all the
        information given about the new class' schedules on the POST
        request, and tries to insert new schedule times on the database.
        If all insertions are successful, this method will call the
        setUpEvaluations() method
        @return Returns TRUE if this and all succeeding operation are
        successful, otherwise, returns FALSE.
        @sa setUpEvaluations()
    */
    private function setUpSchedules(){
        $count = 0;
        $indexKey = 'new-class-day' . $count;
        $fail = FALSE;
        $teacherClassId = $this -> model -> insertId();
        
        while( array_key_exists( $indexKey, $_POST ) ){
            /// Gets day, start time and class duration from POST.
            $day = $_POST['new-class-day' . $count];
            $start = $_POST['new-class-start' . $count];
            $duration = $_POST['new-class-duration' . $count];
            /// Checks for schedule existance.
            $scheduleRow = $this -> model -> scheduleExists( $day, $start, $duration );
            if( $scheduleRow === FALSE ){
                /// Inserts schedule if it does not exist.
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
    
    /** @brief Handles the insertion of class evaluation parameters
        to the database.
        
        The setUpEvaluations() method will take care of the last
        operation needed to create a new class for a teacher: the
        creationg of evaluation pages and sub-evaluation elements.
        Each class can have one or more evaluation pages, each one
        having up to n elements. The insertion of this information
        to the database is done on this method.
        @param teacherClassId : The ID of the newly inserted class.
        @return Returns TRUE if all insertions were sucessful,
        otherwise returns FALSE.
    */
    private function setUpEvaluations( $teacherClassId ){
        $count = 0;
        $indexKey = 'new-class-act' . $count;
        $fail = FALSE;
        
        while( array_key_exists( $indexKey, $_POST ) ){
            /// Gets evaluation page information from POST.
            $act = $_POST['new-class-act' . $count];
            $val = $_POST['new-class-val' . $count];
            if( array_key_exists( 'page-columns' . $count, $_POST ) ){
                /// Gets number of elements if given.
                $nElems = $_POST['page-columns' . $count];
            }
            else{
                /// Default number of elements.
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
    
    /** @brief Creates view from template to show all classes related
        to a teacher.
        
        The showTeacherAll method will create a table from a template
        in order to show the active teacher's classes and some of their
        information including their class key, name, section, school
        cycle to which they belong and academy.
    */
    protected function showTeacherAll(){
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
    
    /** @brief Collects data about a teacher's class in order to
        show it.
        
        The showClass() method receives from a POST requests the
        information about a class that the user wants to see.
        In order to do this, several queries to the database are
        made and then a call to process the data is done.
        @sa processShowClassView()
    */
    protected function showClass(){
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
    
    /** @brief Gets the view required to see a teacher's class
        roll.
        
        Gets the view required to see a teacher's class roll.
    */
    protected function getClassRollView(){
        $view = file_get_contents( 'View/Profesores/verAsistencias.html' );
        
        echo $view;
    }
    
    /** @brief Updates students signed up to a teacher's class
        assistance.
        
        The takeClassRoll() method receives by POST request a set of
        student codes, specifying the students that will have an
        assistance during a class on a specific date. An update is
        done to the database, modifying rows in the table related to
        a students assistances.
    */
    protected function takeClassRoll(){
        if( empty( $_POST ) ){
            require_once( 'View/Profesores/tomarAsistencias.html' );
        }
        else{
            /// Gets class info.
            $classInfo = $_POST['class-select'];
            $classArray = explode( '-', $classInfo );
            $classKey = $classArray[0];
            $classSec = $classArray[1];
            $cycleStr = $classArray[2];
            
            $date = $_POST['date-select'];
            $teacherId = $_SESSION['user_id'];
            
            $cycleRow = $this -> model -> getCycleByStr( $cycleStr );
            $cycleId = $cycleRow['idCiclo'];
            
            $classRow = $this -> model -> getClassByKey( $classKey );
            $classId = $classRow['idCurso'];
            
            $teacherClassRow = $this -> model -> getTeacherClass( $teacherId, $cycleId, $classId, $classSec );
            $teacherClassId = $teacherClassRow['idCursoProfesor'];
            
            $count = 0;
            $okUpdates = 0;
            $key = "code-$count";
            while( array_key_exists( $key, $_POST ) ){
                /// Gets student info by using the code as a key.
                $code = $_POST[$key];
                $studentRow = $this -> model -> getStudentByCode( $code );
                $studentId = $studentRow['idAlumno'];
                /// Gets signed up student's info.
                $studentClassRow = $this -> model -> getStudentInClass( $studentId, $teacherClassId );
                $studentClassId = $studentClassRow['idAlumnoCurso'];
                /// Registers the assistance.
                $result = $this -> model -> registerAssistance( $studentClassId, $date );
                if( $result === TRUE ){
                    if( $this -> updateTotalAssistance( $studentClassId ) === TRUE ){
                        $okUpdates += 1;
                    }
                }
                
                $count += 1;
                $key = "code-$count";
            }
            if( $okUpdates < $count ){
                echo "Error";
            }
            else{
                $this -> getClassRollView();
            }
        }
    }
    
    /** @brief Updates a student's class info to show an update over
        his or her class assistance.
        
        The updateTotalAssistance() method updates the field on the
        student's class table to show the current percentage of assistance
        acquired up to the latest update over the class assistance.
        @param studentClassId : the ID of the student signed up class.
        @return Returns TRUE if successful, otherwise returns FALSE.
    */
    public function updateTotalAssistance( $studentClassId ){
        $assistances = $this -> model -> getStudentAssistances( $studentClassId );
        $nAssistances = count( $assistances );
        $total = 0;
        foreach( $assistances as $assist ){
            if( $assist['estado'] == 1 ){
                $total += 1;
            }
        }
        $percentage = $total / $nAssistances * 100;
        $result = $this -> model -> updateStudentClassAssistances( $studentClassId, $percentage );
        
        return $result;
    }
    
    /** @brief Clones a class parameters into a new class.
    
        The cloneClass() method is used whenever a teacher is to lazy
        to actually set up evaluation parameters and schedules of a new
        class again. The operation is given as something convenient,
        allowing the teacher to clone existing parameters into a new
        class. All operations done when creating a new class are also
        done when cloning a class.
    */
    protected function cloneClass(){
        if( empty( $_POST ) ){
            require_once( 'View/Profesores/clonarCurso.html' );
        }
        else{
            $classInfo = $_POST['class-select'];
            $classArray = explode( '-', $classInfo );
            $classKey = $classArray[0];
            $classSec = $classArray[1];
            $cycleStr = $classArray[2];
            
            $cycleRow = $this -> model -> getCycleByStr( $cycleStr );
            $cycleId = $cycleRow['idCiclo'];
            
            $classRow = $this -> model -> getClassByKey( $classKey );
            $classId = $classRow['idCurso'];
            
            $teacherId = $_SESSION['user_id'];
            $teacherClassRow = $this -> model -> getTeacherClass( $teacherId, $cycleId, $classId, $classSec );
            $teacherClassId = $teacherClassRow['idCursoProfesor'];
            
            $curCycleId = $this -> model -> getLatestCycle();
            $maxSectionRow = $this -> model -> getClassSectionValue( $classId, $teacherId, $curCycleId );
            if( isset( $row['seccion'] ) ){
                $newSection = $row['seccion'] + 1;
            }
            else{
                $newSection = "1";
            }
            
            $classSchedules = $this -> model -> getTeacherClassSchedules( $cycleId, $teacherId, $classId, $teacherClassId );
            $classEvalPages = $this -> model -> getTeacherClassEvals( $teacherId, $cycleId, $classId, $teacherClassId );
            
            $result = $this -> model -> registerNewClass( $classId, $teacherId, $curCycleId, $newSection );
            if( $result === TRUE ){
                $ok = true;
                $newTeacherClassId = $this -> model -> insertId();
                foreach( $classSchedules as $schedule ){
                    if( $ok ){
                        $result = $this -> model -> registerClassSchedule( $newTeacherClassId, $schedule['idHorario'] );
                        if( $result === FALSE ){
                            $ok = false;
                        }
                    }
                }
                if( $ok ){
                    foreach( $classEvalPages as $evalPage ){
                        if( $ok ){
                            $result = $this -> model -> registerEvaluation( $evalPage['descripcion'], $evalPage['valor'],
                                                                            $evalPage['nElems'], $newTeacherClassId );
                            if( $result === FALSE ){
                                $ok = false;
                            }
                        }
                    }
                    if( $ok ){
                        $this -> showTeacherAll();
                    }
                    else{
                        echo "Error al clonar hojas de evaluacion.";
                    }
                }
                else{
                    echo "Error al clonar horarios.";
                }
            }
            else{
                echo "Error al clonar el curso.";
            }
        }
    }
}

?>

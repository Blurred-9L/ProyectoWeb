<?php

/** @file DefaultCtrl.php
    @author Rodrigo Fuentes Hernandez <earthjenei@gmail.com>
    
    The CycleCtrl file contains the CycleCtrl class, which
    acts as the controller of school cycle related operations.
*/

require_once( 'Controller/DefaultCtrl.php' );

/** @brief The CycleCtrl class intermediated between the views
    and the model related to school cycle operations.
    
    The CycleCtrl class is a direct child of the DefaultCtrl
    class, where the CycleCtrl is used to encapsulate behaviour
    related to school cycles operation, in this case, the
    supported operations are adding a new cycle, adding free
    days to a cycle, erasing free days from a cycle, showing all
    cycles and showing a specific cycle.
*/
class CycleCtrl extends DefaultCtrl{
    private $model;                             ///< This controller's model.
    
    /** @brief The CycleCtrl class' constructor.
    
        The CycleCtrl class' constructor initializes the session
        by calling its parent constructor, as well as instantiating
        its related model.
    */
    public function __construct(){
        parent::__construct();
        require_once( 'Model/CycleMdl.php' );
        $this -> model = new CycleMdl();
    }
    
    /** @brief Selects the action to do depending on the
        received GET action.
        
        The execute() method is in charge of selecting the
        correct action according the value given during
        a GET request to this controller. Action supported
        include adding cycles, free days, erasing free days
        or showing all or a specific cycles.
    */
    public function execute(){
        switch( $_GET['action'] ){
            case 'new':
                if( $this -> checkPermissions( 'ninja' ) ){
                    $this -> newCycle();
                }
                else{
                    header( 'Location: index.php?ctrl=login&action=login' );
                }
                break;
            case 'addfd':
                if( $this -> checkPermissions( 'ninja' ) ){
                    $this -> moreFreeDays();
                }
                else{
                    header( 'Location: index.php?ctrl=login&action=login' );
                }
                break;
            case 'erase':
                if( $this -> checkPermissions( 'ninja' ) ){
                    $this -> eraseFreeDays();
                }
                else{
                    header( 'Location: index.php?ctrl=login&action=login' );
                }
                break;
            case 'all':
                if( $this -> checkPermissions( 'ninja' ) ){
                    $this -> showAll();
                }
                else{
                    header( 'Location: index.php?ctrl=login&action=login' );
                }
                break;
            case 'show':
                if( $this -> checkPermissions( 'ninja' ) ){
                    $this -> getCycle();
                }
                else{
                    header( 'Location: index.php?ctrl=login&action=login' );
                }
                break;
        }
    }
    
    /** @brief Maniputales a template to show info about a cycle.
    
        The showCycle() method is in charge of modifying a template view
        in order to show information about a specific school cycle.
        @param year : The school cycle's year.
        @param half : The school cycle's calendar (A|B).
        @param start : The school cycle's start date string (YYYY-MM-DD).
        @param end : The school cycle's end data striing (YYYY-MM-DD).
    */
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
    
    /** @brief Adds data for a new school cycle.
    
        The newCycle() method receives the information from a POST
        request in order to insert a new school cycle to the database.
        The success of this operation depends on the success of the
        insertion of the cycle itself, as well as that of all of the
        free days related to the cycle.
    */
    protected function newCycle(){
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
    
    /** @brief Adds the free days related to a school cycle.
    
        The addFreeDays() method will perform an insertion for all
        given free day dates on the POST request. A free day is
        determined to be a day in which no classes are given, and
        are important for class assistance calculation, where days
        skipped are Sundays and dates marked as free days.
        @param year : The school cycle's year.
        @param half : The school cycle's calendar (A|B).
        @return Returns TRUE if all free days were properly inserted,
        FALSE if a failure occured during the insertion of free days
        or 0 if no free days where found.
    */
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
    
    /** @brief Add free days to a school cycle.
    
        The moreFreeDays() method will perform the second half of the
        insertion of a new cycle, by obtaining free day information from
        a POST request and delegating the model to insert it into the
        database.
    */
    protected function moreFreeDays(){
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
        /// Count is always bigger than 0.
    }
    
    /** @brief Erases free days from a school cycle.
    
        The eraseFreeDays() method will act as the opposite operation
        to the adding of free days, that is, the method will remove
        free days from a school cycle. The method will obtain the dates
        to be removed from a POST request.
    */
    protected function eraseFreeDays(){
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
    
    /** @brief Manipulates a template to show all existing cycles.
    
        The showAll() method manipulates the contents of a template
        in order to properly show information about all currently
        registered school cycles. The information shown includes the
        year, the calendar, the start date and the end date. This
        data is shown as rows in an HTML table.
    */
    private function showAll(){
        $view = file_get_contents( 'View/Admin/ciclosEscolares.html' );
        
        $start = strrpos( $view, '<tr>' );
        $end = strrpos( $view, '</tr>' ) + 5;
        $tableRow = substr( $view, $start, $end - $start );
        
        $cycles = $this -> model -> getAll();
        $rows = '';
        $count = 0;
        if( !empty( $cycles ) ){
            foreach( $cycles as $cycle ){
                $newTableRow = $tableRow;
                $dict = array( '*count*' => $count, '*year*' => $cycle['anio'], '*half*' => $cycle['calendario'],
                               '*start*' => $cycle['fechaInicio'], '*end*' => $cycle['fechaFin'] );
                $newTableRow = strtr( $newTableRow, $dict );
                $rows .= $newTableRow;
                $count += 1;
            }
        }
        
        $view = str_replace( $tableRow, $rows, $view );
        
        echo $view;
    }
    
    /** @brief Gets necessary information for showing a school cycle.
    
        The getCycle() method gets the necessary information about a
        school cycle in order to show it to the user by calling the
        showCycle() method. The only information obtained from a POST
        request is the cycle string used to identify the required
        cycle.
        @sa showCycle().
    */
    private function getCycle(){
        $cycle = $_POST['cycle'];
        $cycleRow = $this -> model -> getCycle2( $cycle );
        $this -> showCycle( $cycleRow['anio'], $cycleRow['calendario'], $cycleRow['fechaInicio'], $cycleRow['fechaFin'] );
    }
}

?>

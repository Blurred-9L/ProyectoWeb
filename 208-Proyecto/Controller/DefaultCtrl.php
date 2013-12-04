<?php

/** @file DefaultCtrl.php
    @author Rodrigo Fuentes Hernandez <earthjenei@gmail.com>

    This file contains the default Controller class
    from which all other Controllers used inherit from.
*/

/** @brief The DefaultCtrl class defines basic behaviour for
    all controllers used.
    
    The DefaultCtrl class defines basic behaviour for all
    controllers used inside the application. This basic behaviour
    includes session handling, checking for permissions,
    checking for the existance of a session and redirecting a
    user without the required permissions.
*/
class DefaultCtrl{

    /** @brief DefaultCtrl constructor.
    
        The DefaultCtrl constructor method. Initializes the
        session by calling session_start.
    */
    public function __construct(){
        session_start();
    }
    
    /** @brief Check the existance of a session.
    
        The checkSession() method will check for the existance
        of three values stored in the PHP $_SESSION array.
        These values are the user's id, the user's code and
        the type of user that holds the current session.
        @return Returns TRUE if a session exists, otherwise,
        returns FALSE.
    */
    public function checkSession(){
        // A session will store id, type of user and user code.
        $sessionExists = FALSE;
        
        if( isset( $_SESSION['user_code'] ) && isset( $_SESSION['user_type'] ) && isset( $_SESSION['user_id'] ) ){
            $sessionExists = TRUE;
        }
        
        return $sessionExists;
    }
    
    /** @brief Redirects user according to permission level.
    
        The redirectUser() method is in charge of loading the main
        page of each of the different permission levels.
    */
    public function redirectUser(){
        switch( $_SESSION['user_type'] ){
            case 'rookie': /// Student
                require_once( 'View/Alumnos/alumnosMain.html' );
                break;
            case 'brigadier': /// Teacher
                require_once( 'View/Profesores/profesoresMain.html' );
                break;
            case 'ninja': /// Admin
                require_once( 'View/Admin/adminMain.html' );
                break;
        }
    }
    
    /** @brief Kills the current session.
    
        Kills the current session by unsetting all its variables,
        destroying the session and ensuring that the cookie related
        to the session is eliminated.
    */
    public function killSession(){
        session_unset();                                                /// Unsets session
        session_destroy();                                              /// Destroys session
        setcookie( session_name(), '', time() - 3600 );                 /// Kills cookie
        header( 'Location: ./index.php?ctrl=login&action=login' );      /// Changes header location.
    }
    
    /** @brief Checks if a user's permissions level match up with
        the expected permissions level.
        
        Checks the user's permission level against a given string
        representing the expected permission level that the user
        should have in order to access a certain functionality or
        operation.
        @param userType : a string indicating the expected permission
        level. It can be: 'rookie', 'brigadier' or 'ninja'.
        @return Returns TRUE if the permission level matches up,
        otherwise, returns FALSE.
    */
    public function checkPermissions( $userType ){
        $ok = FALSE;
        
        if( isset( $_SESSION['user_type'] ) ){
            if( $_SESSION['user_type'] == $userType ){
                $ok = TRUE;
            }
        }
        
        return $ok;
    }
    
    /** @brief Selects the action to do depending on the
        received GET action.
        
        The execute() method is in charge of selecting the
        correct action according the value given during
        a GET request to this controller. Since this is the
        default controller, the only possible action is
        exiting the system or returning to the main page or
        log in page.
    */
    public function execute(){
        switch( $_GET['action'] ){
            case 'exit':
                $this -> killSession();
                break;
            case 'main':
                $this -> redirectUser();
                break;
        }
    }
}

?>

<?php

class DefaultCtrl{
    public function __construct(){
        session_start();
    }
    
    public function checkSession(){
        // A session will store... id and type.
        $sessionExists = FALSE;
        
        if( isset( $_SESSION['user_code'] ) && isset( $_SESSION['user_type'] ) && isset( $_SESSION['user_id'] ) ){
            $sessionExists = TRUE;
        }
        
        return $sessionExists;
    }
    
    public function redirectUser(){
        switch( $_SESSION['user_type'] ){
            case 'rookie': // Student
                require_once( 'View/Alumnos/alumnosMain.html' );
                break;
            case 'brigadier': // Teacher
                require_once( 'View/Profesores/profesoresMain.html' );
                break;
            case 'ninja': // Admin
                require_once( 'View/Admin/adminMain.html' );
                break;
        }
    }
    
    public function killSession(){
        session_unset();        // Unsets session
        session_destroy();      // Destroys session
        setcookie( session_name(), '', time() - 3600 );     // Kills cookie
        header( 'Location: ./index.php?ctrl=login&action=login' );
    }
    
    public function execute(){
        switch( $_GET['action'] ){
            case 'exit':
                $this -> killSession();
                break;
        }
    }
}

?>

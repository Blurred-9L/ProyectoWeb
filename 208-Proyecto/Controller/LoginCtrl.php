<?php

class LoginCtrl{
    private $model;
    
    public function __construct(){
        require_once( 'Model/LoginMdl.php' );
        $this -> model = new LoginMdl();
    }
    
    public function execute(){
        switch( $_GET['action'] ){
            case 'login':
                $this -> attemptLogin();
                break;
        }
    }
    
    private function attemptLogin(){
        if( empty( $_POST ) ){
            require_once( 'View/login.html' );
        }
        else{
            $code = $_POST['login-code'];
            $pass = $_POST['login-pass'];
            
            if( ( $account = $this -> searchAdmins( $code ) ) !== FALSE ){
                $view = file_get_contents( 'View/Admin/adminMain.html' );
            }
            else if( ( $account = $this -> searchTeachers( $code ) ) !== FALSE ){
                $view = file_get_contents( 'View/Alumnos/alumnosMain.html' );
            }
            else if( ( $account = $this -> searchStudents( $code ) ) !== FALSE ){
                $view = file_get_contents( 'View/Profesores/profesoresMain.html' );
            }
            else{
                $view = file_get_contents( 'View/login.html' );
                $view = str_replace( '<div class="message-div">',
                                     '<div class="message-div"> Codigo y contraseña incorrectos.',
                                     $view );
            }
            echo $view;
        }
    }
    
    private function searchAdmins( $code ){
        $admins = $this -> model -> getAllAdmins();
        $found = FALSE;
        $myAdmin = NULL;
        
        foreach( $admins as $admin ){
            if( !$found ){
                if( $admin['codigo'] == $code ){
                    $found = TRUE;
                    $myAdmin = $admin;
                }
            }
        }
        
        if( $found ){
            return $myAdmin;
        }
        else{
            return $found; // False
        }
    }
    
    private function searchTeachers( $code ){
        $teachers = $this -> model -> getAllTeachers();
        $found = FALSE;
        $myTeacher = NULL;
        
        foreach( $teachers as $teacher ){
            if( !$found ){
                if( $teacher['codigo'] == $code ){
                    $found = TRUE;
                    $myTeacher = $teacher;
                }
            }
        }
        
        if( $found ){
            return $myTeacher;
        }
        else{
            return $found;
        }
    }
    
    private function searchStudents( $code ){
        $students = $this ->  model -> getAllStudents();
        $found = FALSE;
        $myStudent = NULL;
        
        foreach( $students as $student ){
            if( !$found ){
                if( $student['codigo'] == $code ){
                    $found = TRUE;
                    $myStudent = $student;
                }
            }
        }
        
        if( $found ){
            return $myStudent;
        }
        else{
            return $found;
        }
    }
}

?>

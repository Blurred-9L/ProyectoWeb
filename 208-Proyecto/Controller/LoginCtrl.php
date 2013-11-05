<?php

class LoginCtrl{
    private $model;
    
    public function __construct(){
        require_once( 'Model/LoginMdl.php' );
        $this -> model = new LoginMdl();
        session_start();
    }
    
    public function checkSession(){
        // A session will store... id and type.
        $sessionExists = FALSE;
        
        if( isset( $_SESSION['user_id'] ) && isset( $_SESSION['user_type'] ) ){
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
    
    public function execute(){
        switch( $_GET['action'] ){
            case 'login':
                if( !$this -> checkSession() ){
                    $this -> attemptLogin();
                }
                else{
                    $this -> redirectUser();
                }
                break;
            case 'recover':
                $this -> recoverPass();
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
                $_SESSION['user_type'] = 'ninja';
                $_SESSION['user_id'] = $code;
            }
            else if( ( $account = $this -> searchTeachers( $code ) ) !== FALSE ){
                $view = file_get_contents( 'View/Profesores/profesoresMain.html' );
                $_SESSION['user_type'] = 'brigadier';
                $_SESSION['user_id'] = $code;
            }
            else if( ( $account = $this -> searchStudents( $code ) ) !== FALSE ){
                $view = file_get_contents( 'View/Alumnos/alumnosMain.html' );
                $_SESSION['user_type'] = 'rookie';
                $_SESSION['user_id'] = $code;
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
    
    private function recoverPass(){
        if( empty( $_POST ) ){
            require_once( 'View/lostPass.html' );
        }
        else{
            $code = $_POST['lost-pass-code'];
            if( ( $account = $this -> searchAdmins( $code ) ) !== FALSE ){
                $newPass = $this -> getAdminPassword();
                if( $this -> model -> updateAdminPass( $code, $newPass ) === TRUE ){
                    $view = file_get_contents( 'View/login.html' );
                    $view = str_replace( '<div class="message-div">',
                                         "<div class=\"message-div\"> Su nueva contraseña es $newPass.",
                                         $view );
                }
                else{
                    $view = 'Error';
                }
            }
            else if( ( $account = $this -> searchTeachers( $code ) ) !== FALSE ){
                $newPass = $this -> getTeacherPassword( $account['nombre'], $account['apellidoP'], $account['apellidoM'] );
                if( $this -> model -> updateTeacherPass( $code, $newPass ) === TRUE ){
                    $view = file_get_contents( 'View/login.html' );
                    $view = str_replace( '<div class="message-div">',
                                         "<div class=\"message-div\"> Su nueva contraseña es $newPass.",
                                         $view );
                }
                else{
                    $view = 'Error';
                }
            }
            else if( ( $account = $this -> searchStudents( $code ) ) !== FALSE ){
                $newPass = $this -> getStudentPassword( $account['nombre'], $account['apellidoP'], $account['apellidoM'] );
                if( $this -> model -> updateStudentPass( $code, $newPass ) === TRUE ){
                    $view = file_get_contents( 'View/login.html' );
                    $view = str_replace( '<div class="message-div">',
                                         "<div class=\"message-div\"> Su nueva contraseña es $newPass.",
                                         $view );
                }
                else{
                    $view = 'Error';
                }
            }
            else{
                $view = file_get_contents( 'View/login.html' );
                $view = str_replace( '<div class="message-div">',
                                     '<div class="message-div"> Intento de recuperar contraseña fallido.',
                                     $view );
            }
            
            echo $view;
        }
    }
    
    private function getStudentPassword( $name, $last1, $last2 ){
        $accents = array( 'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ñ' => 'n' );
        $pass = $name . $last1 . $last2;
        $pass = strtolower( $pass );
        $pass = strtr( $pass, $accents );
        $pass = str_shuffle( $pass );
        $pass = substr( $pass, 0, 8 );
        
        return $pass;
    }
    
    private function getTeacherPassword( $name, $last1, $last2 ){
        $accents = array( 'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ñ' => 'n' );
        $pass = $name . $last1 . $last2;
        $pass = strtolower( $pass );
        $pass = strtr( $pass, $accents );
        $pass = str_shuffle( $pass );
        $pass = substr( $pass, 0, 12 );
        
        return $pass;
    }
    
    private function getAdminPassword(){
        $letters = range( 'a', 'z' );
        
        $pass = '';
        for( $i = 0; $i < 8; $i += 1 ){
            $pass .= $letters[array_rand( $letters )]; 
        }
        
        return $pass;
    }
}

?>

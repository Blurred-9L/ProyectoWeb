<?php

require_once( 'Controller/DefaultCtrl.php' );

class LoginCtrl extends DefaultCtrl{
    private $model;
    
    public function __construct(){
        parent::__construct();
        require_once( 'Model/LoginMdl.php' );
        $this -> model = new LoginMdl();
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
            $pass = $_POST['password'];
            
            if( ( $account = $this -> searchAdmins( $code ) ) !== FALSE ){
                $view = file_get_contents( 'View/Admin/adminMain.html' );
                $_SESSION['user_id'] = $account['idAdmin'];
                $_SESSION['user_type'] = 'ninja';
                $_SESSION['user_code'] = $code;
            }
            else if( ( $account = $this -> searchTeachers( $code ) ) !== FALSE ){
                $view = file_get_contents( 'View/Profesores/profesoresMain.html' );
                $_SESSION['user_id'] = $account['idProfesor'];
                $_SESSION['user_type'] = 'brigadier';
                $_SESSION['user_code'] = $code;
            }
            else if( ( $account = $this -> searchStudents( $code ) ) !== FALSE ){
                $view = file_get_contents( 'View/Alumnos/alumnosMain.html' );
                $_SESSION['user_id'] = $account['idAlumno'];
                $_SESSION['user_type'] = 'rookie';
                $_SESSION['user_code'] = $code;
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
            $ok = TRUE;
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
                $ok = FALSE;
            }
            if( $ok ){
                $this -> sendPassChangeMail( $account, $newPass );
            }
            
            echo $view;
        }
    }
    
    private function sendPassChangeMail( $account, $newPass ){
        $mail = $account['email'];
        
        $to = $mail;
        $subject = 'Recuperacion de contraseña (Sistema de calificaciones)';
        
        $header = 'MIME-Version: 1.0' . "\r\n";
        $header .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $header .= 'From: user208@alanturing.cucei.udg.mx' . "\r\n";
        
        $content = '<em>Se ha reestablecido la contraseña de su cuenta. Su codigo y contraseña son los siguientes:</em><br />' . PHP_EOL;
        $content .= '<strong>Codigo</strong>: ' . $account['codigo'] . '<br />' . PHP_EOL;
        $content .= '<strong>Contraseña</strong>: ' . $newPass . '<br />' . PHP_EOL;
        
        $result = mail( $to, $subject, $content, $header );
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

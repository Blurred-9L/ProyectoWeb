<?php

require_once( 'Controller/DefaultCtrl.php' );

class TeacherCtrl extends DefaultCtrl{
    private $model;
    
    public function __construct(){
        parent::__construct();
        require_once( 'Model/TeacherMdl.php' );
        $this -> model = new TeacherMdl();
    }
    
    public function execute(){
        switch( $_GET['action'] ){
            case 'new':
                if( $this -> checkPermissions( 'ninja' ) ){
                    $this -> newTeacher();
                }
                else{
                    header( 'Location: index.php?ctrl=login&action=login' );
                }
                break;
            case 'edit':
                if( $this -> checkPermissions( 'ninja' ) ){
                    $this -> editTeacher();
                }
                else{
                    header( 'Location: index.php?ctrl=login&action=login' );
                }
                break;
            case 'all':
                if( $this -> checkPermissions( 'ninja' ) ){
                    $this -> showAllTeachers();
                }
                else{
                    header( 'Location: index.php?ctrl=login&action=login' );
                }
                break;
            case 'show':
                if( $this -> checkPermissions( 'ninja' ) ){
                    $this -> showTeacher();
                }
                else{
                    header( 'Location: index.php?ctrl=login&action=login' );
                }
                break;
        }
    }
    
    public function getPassword( $name, $last1, $last2 ){
        $accents = array( 'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ñ' => 'n' );
        $pass = $name . $last1 . $last2;
        $pass = strtolower( $pass );
        $pass = strtr( $pass, $accents );
        $pass = str_shuffle( $pass );
        $pass = substr( $pass, 0, 12 );
        
        return $pass;
    }
    
    public function showEditView( $code, $name, $last1, $last2, $mail, $phone ){
        $view = file_get_contents( 'View/Admin/editarProfesor.html' );
        
        $dict = array( '*name*' => $name, '*last1*' => $last1, '*last2*' => $last2 );
        $view = strtr( $view, $dict );
        $view = str_replace( '<input type="text" id="edit-teacher-code" name="edit-teacher-code" />',
                             "<input type=\"text\" id=\"edit-teacher-code\" name=\"edit-teacher-code\" value=\"$code\" />",
                             $view );
        $view = str_replace( '<input type="email" id="edit-teacher-email" name="edit-teacher-email" />',
                             "<input type=\"email\" id=\"edit-teacher-email\" name=\"edit-teacher-email\" value=\"$mail\" />",
                             $view );
        $view = str_replace( '<input type="tel" id="edit-teacher-phone" name="edit-teacher-phone" />',
                             "<input type=\"tel\" id=\"edit-teacher-phone\" name=\"edit-teacher-phone\" value=\"$phone\" />",
                             $view );
                             
        echo $view;
    }
    
    public function updateEditView( $code ){
        $row = $this -> model -> getTeacher( $code );
        $view = file_get_contents( 'View/Admin/editarProfesor.html' );
        
        $code = $row['codigo'];
        $mail = $row['email'];
        $phone = $row['celular'];
        $dict = array( '*name*' => $row['nombre'], '*last1*' => $row['apellidoP'], '*last2*' => $row['apellidoM'] );
        $view = strtr( $view, $dict );
        $view = str_replace( '<input type="text" id="edit-teacher-code" name="edit-teacher-code" />',
                             "<input type=\"text\" id=\"edit-teacher-code\" name=\"edit-teacher-code\" value=\"$code\" />",
                             $view );
        $view = str_replace( '<input type="email" id="edit-teacher-email" name="edit-teacher-email" />',
                             "<input type=\"email\" id=\"edit-teacher-email\" name=\"edit-teacher-email\" value=\"$mail\" />",
                             $view );
        $view = str_replace( '<input type="tel" id="edit-teacher-phone" name="edit-teacher-phone" />',
                             "<input type=\"tel\" id=\"edit-teacher-phone\" name=\"edit-teacher-phone\" value=\"$phone\" />",
                             $view );
                             
        echo $view;
    }
    
    private function newTeacher(){
        if( empty( $_POST ) ){
            require_once( 'View/Admin/profesorNuevo.html' );
        }
        else{
            $code = $_POST['reg-teacher-code'];
            $name = $_POST['reg-teacher-name'];
            $last1 = $_POST['reg-teacher-last1'];
            $last2 = $_POST['reg-teacher-last2'];
            $mail = $_POST['reg-teacher-email'];
            $phone = $_POST['reg-teacher-phone'];
            $pass = $this -> getPassword( $name, $last1, $last2 );
            
            $result = $this -> model -> register( $code, $name, $last1, $last2, $mail, $phone, $pass );
            
            if( $result === TRUE ){
                $this -> showEditView( $code, $name, $last1, $last2, $mail, $phone );
                $this -> sendMail( $code, $name . ' ' . $last1 . ' ' . $last2, $pass, $mail );
            }
            else{
                echo "Error";
            }
        }
    }
    
    private function sendMail( $code, $name, $pass, $mail ){
        $to = $mail;
        $subject = 'Alta en sistema de calificaciones';
        
        $header = 'MIME-Version: 1.0' . "\r\n";
        $header .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $header .= 'From: user208@alanturing.cucei.udg.mx' . "\r\n";
        
        $content = "<p><strong>$name</strong>, bienvenido al sistema de calificaciones universitario.<br />" . PHP_EOL;
        $content .= 'Ahora podra gestionar sus cursos escolares y evaluaciones.' . PHP_EOL;
        $content .= 'Usted puede acceder al sistema accediendo a la siguiente direccion:<br />' . PHP_EOL;
        $content .= '<a href="alanturing.cucei.udg.mx/cc409/user208/index.php?ctrl=login&action=login">Sistema de calificaciones</a>' . PHP_EOL;
        $content .= "Su cuenta es la siguiente:</p><ul><li><strong>Codigo</strong>: $code</li>" . PHP_EOL;
        $content .= "<li><strong>Contraseña</strong>: $pass</li></ul>" . PHP_EOL;
        
        $result = mail( $to, $subject, $content, $header );
    }
    
    private function editTeacher(){
        if( empty( $_POST ) ){
        }
        else{
            $code = $_POST['edit-teacher-code'];
            $mail = $_POST['edit-teacher-email'];
            $phone = $_POST['edit-teacher-phone'];
            
            $result = $this -> model -> update( $code, $mail, $phone );
            
            if( $result === TRUE ){
                $this -> updateEditView( $code );
            }
            else{
                echo "Error";
            }
        }
    }
    
    private function showAllTeachers(){
        $view = file_get_contents( 'View/Admin/verProfesores.html' );
        
        $start = strrpos( $view, '<tr>' );
        $end = strrpos( $view, '</tr>' ) + 5;
        $tableRow = substr( $view, $start, $end - $start );
        
        $teachers = $this -> model -> getAll();
        $rows = '';
        $count = 0;
        if( isset( $teachers ) ){
            foreach( $teachers as $row ){
                $newTableRow = $tableRow;
                $name = $row['nombre'] . ' ' . $row['apellidoP'] . ' ' . $row['apellidoM'];
                $dict = array( '*name*' => $name, '*code*' => $row['codigo'], '*phone*' => $row['celular'], '*mail*' => $row['email'],
                               '*count*' => $count );
                $newTableRow = strtr( $newTableRow, $dict );
                $rows .= $newTableRow;
                $count += 1;
            }
        }
            
        $view = str_replace( $tableRow, $rows, $view );
        
        echo $view;
    }
    
    private function showTeacher(){
        $code = $_POST['code'];
        
        $this -> updateEditView( $code );
    }
}

?>

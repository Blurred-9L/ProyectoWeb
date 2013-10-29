<?php

class TeacherCtrl{
    private $model;
    
    public function __construct(){
        require_once( 'Model/TeacherMdl.php' );
        $this -> model = new TeacherMdl();
    }
    
    public function execute(){
        switch( $_GET['action'] ){
            case 'new':
                $this -> newTeacher();
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
            }
            else{
                echo "Error";
            }
        }
    }
}

?>

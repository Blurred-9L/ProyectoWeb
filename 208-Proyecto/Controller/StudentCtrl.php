<?php

class StudentCtrl{
    private $model;
    
    public function __construct(){
        require_once( 'Model/StudentMdl.php' );
        $this -> model = new StudentMdl();
    }
    
    public function execute(){
        switch( $_GET['action'] ){
            case 'anew':
                $this -> adminNew();
                break;
        }
    }
    
    private function adminNew(){
        if( empty( $_POST ) ){
            require_once( 'View/Admin/adminAlumnosNuevo.html' );
        }
        else{
            $code = $_POST['reg-student-code'];
            $name = $_POST['reg-student-name'];
            $last1 = $_POST['reg-student-last1'];
            $last2 = $_POST['reg-student-last2'];
            $mail = $_POST['reg-student-email'];
            $major = $_POST['reg-student-major'];
            if( array_key_exists( 'reg-student-phone', $_POST ) ){
                $phone = $_POST['reg-student-phone'];
            }
            else{
                $phone = NULL;
            }
            if( array_key_exists( 'reg-student-url', $_POST ) ){
                $url = $_POST['reg-student-url'];
            }
            else{
                $url = NULL;
            }
            if( array_key_exists( 'reg-student-github', $_POST ) ){
                $github = $_POST['reg-student-github'];
            }
            else{
                $github = NULL;
            }
            $pass = $this -> getPassword( $name, $last1, $last2 );
            
            // doQuery
            $result = $this -> model -> register( $code, $name, $last1, $last2, $mail, $major, $pass, $phone, $url, $github );
            // showResult
            if( $result ){
                $this -> processViewEditStudent( $code, $name, $last1, $last2, $mail, $major, $pass, $phone, $url, $github );
                // sendMail
            }
            else{
                echo "Error";
            }
        }
    }
    
    public function getPassword( $name, $last1, $last2 ){
        $accents = array( 'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ñ' => 'n' );
        $pass = $name . $last1 . $last2;
        $pass = strtolower( $pass );
        $pass = strtr( $pass, $accents );
        $pass = str_shuffle( $pass );
        $pass = substr( $pass, 0, 8 );
        
        return $pass;
    }
    
    public function processViewEditStudent( $code, $name, $last1, $last2, $mail, $major, $pass, $phone, $url, $github ){
        $row = $this -> model -> getMajorStr( $major );
        $majorStr = $row['nombreCarrera'];
        $view = file_get_contents( 'View/Admin/editarAlumno.html' );
        
        $dict = array( '*code*' => $code, '*name*' => $name, '*last1*' => $last1, '*last2*' => $last2, '*major*' => $majorStr );
        $view = strtr( $view, $dict );
        $view = str_replace( '<input type="email" id="edit-student-email" name="edit-student-email" />',
                             "<input type=\"email\" id=\"edit-student-email\" name=\"edit-student-email\" value=\"$mail\" />",
                             $view );
        if( isset( $phone ) ){
            $view = str_replace( '<input type="tel" id="edit-student-phone" name="edit-student-phone" />',
                                 "<input type=\"tel\" id=\"edit-student-phone\" name=\"edit-student-phone\" value=\"$phone\" />",
                                 $view );
        }
        if( isset( $url ) ){
            $view = str_replace( '<input type="url" id="edit-student-url" name="edit-student-url" />',
                                 "<input type=\"url\" id=\"edit-student-url\" name=\"edit-student-url\" value=\"$url\" />",
                                 $view );
        }
        if( isset( $github ) ){
            $view = str_replace( '<input type="url" id="edit-student-github" name="edit-student-github" />',
                                 "<input type=\"url\" id=\"edit-student-github\" name=\"edit-student-github\" value=\"$github\" />",
                                 $view );
        }
        
        echo $view;
    }
}

?>

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
            case 'edit':
                $this -> edit();
                break;
            case 'all':
                $this -> showAll();
                break;
            case 'show':
                $this -> show();
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
        
        $dict = array( '*name*' => $name, '*last1*' => $last1, '*last2*' => $last2, '*major*' => $majorStr );
        $view = strtr( $view, $dict );
        $view = str_replace( '<input type="text" id="edit-student-code" name="edit-student-code" required />',
                             "<input type=\"text\" id=\"edit-student-code\" name=\"edit-student-code\" value=\"$code\" required />",
                             $view );
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
    
    public function updateViewEditStudent( $code ){
        $row = $this -> model -> getStudent( $code );
        $view = file_get_contents( 'View/Admin/editarAlumno.html' );
        $code = $row['codigo'];
        $mail = $row['email'];
        $phone = $row['celular'];
        $url = $row['paginaWeb'];
        $github = $row['github'];
        
        $dict = array( '*name*' => $row['nombre'], '*last1*' => $row['apellidoP'],
                       '*last2*' => $row['apellidoM'], '*major*' => $row['nombreCarrera'] );
        $view = strtr( $view, $dict );
        $view = str_replace( '<input type="text" id="edit-student-code" name="edit-student-code" required />',
                             "<input type=\"text\" id=\"edit-student-code\" name=\"edit-student-code\" value=\"$code\" required />",
                             $view );
        $view = str_replace( '<input type="email" id="edit-student-email" name="edit-student-email" />',
                             "<input type=\"email\" id=\"edit-student-email\" name=\"edit-student-email\" value=\"$mail\" />",
                             $view );
        $view = str_replace( '<input type="tel" id="edit-student-phone" name="edit-student-phone" />',
                                 "<input type=\"tel\" id=\"edit-student-phone\" name=\"edit-student-phone\" value=\"$phone\" />",
                                 $view );
        $view = str_replace( '<input type="url" id="edit-student-url" name="edit-student-url" />',
                                 "<input type=\"url\" id=\"edit-student-url\" name=\"edit-student-url\" value=\"$url\" />",
                                 $view );
        $view = str_replace( '<input type="url" id="edit-student-github" name="edit-student-github" />',
                                 "<input type=\"url\" id=\"edit-student-github\" name=\"edit-student-github\" value=\"$github\" />",
                                 $view );
        
        echo $view;                       
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
            
            $result = $this -> model -> register( $code, $name, $last1, $last2, $mail, $major, $pass, $phone, $url, $github );
            if( $result === TRUE ){
                $this -> processViewEditStudent( $code, $name, $last1, $last2, $mail, $major, $pass, $phone, $url, $github );
                // sendMail
            }
            else{
                echo "Error";
            }
        }
    }
    
    private function edit(){
        if( empty( $_POST ) ){
        }
        else{
            $code = $_POST['edit-student-code'];
            $mail = $_POST['edit-student-email'];
            $phone = $_POST['edit-student-phone'];
            $url = $_POST['edit-student-url'];
            $github = $_POST['edit-student-github'];
            
            $result = $this -> model -> update( $code, $mail, $phone, $url, $github );
            if( $result === TRUE ){
                $this -> updateViewEditStudent( $code );
            }
            else{
                echo "Error";
            }
        }
    }
    
    private function showAll(){
        $view = file_get_contents( 'View/Admin/adminVerAlumnos.html' );
        
        $start = strrpos( $view, '<tr>' );
        $end = strrpos( $view, '</tr>' ) + 5;
        $tableRow = substr( $view, $start, $end - $start );
        
        $students = $this -> model -> getAll();
        $rows = '';
        $count = 0;
        foreach( $students as $row ){
            $newTableRow = $tableRow;
            $name = $row['nombre'] . ' ' . $row['apellidoP'] . ' ' . $row['apellidoM'];
            $dict = array( '*name*' => $name, '*code*' => $row['codigo'], '*major*' => $row['nombreCarrera'], '*mail*' => $row['email'],
                           '*count*' => $count );
            $newTableRow = strtr( $newTableRow, $dict );
            $rows .= $newTableRow;
            $count += 1;
        }
        
        $view = str_replace( $tableRow, $rows, $view );
        
        echo $view;
    }
    
    private function show(){
        $code = $_POST['code'];
        
        $this -> updateViewEditStudent( $code );
    }
}

?>

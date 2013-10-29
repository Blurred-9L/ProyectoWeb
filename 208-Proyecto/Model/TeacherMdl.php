<?php

class TeacherMdl{
    private $dbCon;
    
    public function __construct(){
        require_once( 'Resources/dbConnection.php' );
        $this -> dbCon = dbConnection::connect();
    }
    
    public function register( $code, $name, $last1, $last2, $mail, $phone, $pass ){
        $storedPass = sha1( $pass );
        $query = "insert into Profesor( codigo, nombre, apellidoP, apellidoM, email, celular, password ) 
                  values( \"$code\", \"$name\", \"$last1\", \"$last2\", \"$mail\", \"$phone\", \"$storedPass\" );";
                  
        $result = $this -> dbCon -> query( $query );
        
        return $result;
    }
}

?>

<?php

class StudentMdl{
    private $dbCon;
    
    public function __construct(){
        require_once( 'Resources/dbConnection.php' );
        $this -> dbCon = dbConnection::connect();
    }
    
    public function register( $code, $name, $last1, $last2, $mail, $major, $pass, $phone, $url, $github ){
        $newPass = sha1( $pass );
        $query = "insert into Alumno( codigo, nombre, apellidoP, apellidoM, email, idCarrera, password,
                  celular, paginaWeb, github ) values (\"$code\", \"$name\", \"$last1\", \"$last2\",
                  \"$mail\", $major, \"$newPass\", ";
                  
        $query .= ( isset( $phone ) )? "\"$phone\", " : 'NULL, ';
        $query .= ( isset( $url ) )? "\"$url\", " : 'NULL, ';
        $query .= ( isset( $github ) )? "\"$github\" );" : 'NULL );';
        
        $result = $this -> dbCon -> query( $query );
        
        return $result;
    }
    
    public function getMajorStr( $major ){
        $query = "select nombreCarrera from Carrera where idCarrera = $major;";
        
        $result = $this -> dbCon -> query( $query );
        
        $row = $result -> fetch_assoc();
        
        return $row;
    }
}

?>

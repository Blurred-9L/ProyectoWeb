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
    
    public function update( $code, $mail, $phone ){
        $query = "update Profesor set email=\"$mail\", celular=\"$phone\" where codigo=\"$code\";";
        
        $result = $this -> dbCon -> query( $query );
        
        return $result;
    }
    
    public function getTeacher( $code ){
        $query = "select * from Profesor where codigo=\"$code\";";
        
        $result = $this -> dbCon -> query( $query );
        $row = $result -> fetch_assoc();
        
        return $row;
    }
    
    public function getAll(){
        $query = 'select * from Profesor;';
        
        $result = $this -> dbCon -> query( $query );
        
        $rows = NULL;
        while( $row = $result -> fetch_assoc() ){
            $rows[] = $row;
        }
        
        return $rows;
    }
}

?>

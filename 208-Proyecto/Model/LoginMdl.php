<?php

class LoginMdl{
    private $dbCon;
    
    public function __construct(){
        require_once( 'Resources/dbConnection.php' );
        $this -> dbCon = dbConnection::connect();
    }
    
    public function getAllStudents(){
        $query = "select * from Alumno;";
        
        $result = $this -> dbCon -> query( $query );
        $rows = array();
        while( $row = $result -> fetch_assoc() ){
            $rows[] = $row;
        }
        
        return $rows;
    }
    
    public function getAllTeachers(){
        $query = "select * from Profesor;";
        
        $result = $this -> dbCon -> query( $query );
        $rows = array();
        while( $row = $result -> fetch_assoc() ){
            $rows[] = $row;
        }
        
        return $rows;
    }
    
    public function getAllAdmins(){
        $query = "select codigo, password from Admin;";
        
        $result = $this -> dbCon -> query( $query );
        $rows = array();
        while( $row = $result -> fetch_assoc() ){
            $rows[] = $row;
        }
        
        return $rows;
    }
    
    public function updateAdminPass( $code, $pass ){
        $storedPass = sha1( $pass );
        $query = "update Admin set password=\"$storedPass\" where codigo=\"$code\";";
        
        $result = $this -> dbCon -> query( $query );
        
        return $result;
    }
    
    public function updateStudentPass( $code, $pass ){
        $storedPass = sha1( $pass );
        $query = "update Alumno set password=\"$storedPass\" where codigo=\"$code\";";
        
        $result = $this -> dbCon -> query( $query );
        
        return $result;
    }
    
    public function updateTeacherPass( $code, $pass ){
        $storedPass = sha1( $pass );
        $query = "update Profesor set password=\"$storedPass\" where codigo=\"$code\";";
        
        $result = $this -> dbCon -> query( $query );
        
        return $result;
    }
}

?>

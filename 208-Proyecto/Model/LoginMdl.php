<?php

class LoginMdl{
    private $dbCon;
    
    public function __construct(){
        require_once( 'Resources/dbConnection.php' );
        $this -> dbCon = dbConnection::connect();
    }
    
    public function getAllStudents(){
        $query = "select codigo, password from Alumno;";
        
        $result = $this -> dbCon -> query( $query );
        $rows = array();
        while( $row = $result -> fetch_assoc() ){
            $rows[] = $row;
        }
        
        return $rows;
    }
    
    public function getAllTeachers(){
        $query = "select codigo, password from Profesor;";
        
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
}

?>

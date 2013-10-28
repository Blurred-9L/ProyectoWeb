<?php

class dbConnection{
    private static $instance = NULL;
    public $connection;
    
    private function __construct( $server, $user, $pass, $dbName ){
        $this -> connection = new mysqli( $server, $user, $pass, $dbName );
        if( $this -> connection -> connect_errno ){
            die( 'Could not connect to the database.' . PHP_EOL );
        }
    }
    
    public function __destruct(){
        $this -> connection -> close();
    }
    
    public function query( $query ){
        $result = $this -> connection -> query( $query );
        
        return $result;
    }
    
    public static function connect(){
        if( !isset( self::$instance ) ){
            self::$instance = new dbConnection( 'localhost', 'blurred', 'blurred', 'Calificaciones' );
        }
        
        return self::$instance;
    }
}

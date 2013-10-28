<?php

switch( $_GET['ctrl'] ){
    case 'student':
        require_once( 'Controller/StudentCtrl.php' );
        $ctrl = new StudentCtrl();
        break;
    default:
}

$ctrl -> execute();

?>

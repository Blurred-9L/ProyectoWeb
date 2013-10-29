<?php

switch( $_GET['ctrl'] ){
    case 'student':
        require_once( 'Controller/StudentCtrl.php' );
        $ctrl = new StudentCtrl();
        break;
    case 'teacher':
        require_once( 'Controller/TeacherCtrl.php' );
        $ctrl = new TeacherCtrl();
    default:
        echo "Error";
        break;
}

$ctrl -> execute();

?>

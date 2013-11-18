<?php

if( array_key_exists( 'ctrl', $_GET ) ){
    switch( $_GET['ctrl'] ){
        case 'student':
            require_once( 'Controller/StudentCtrl.php' );
            $ctrl = new StudentCtrl();
            break;
        case 'teacher':
            require_once( 'Controller/TeacherCtrl.php' );
            $ctrl = new TeacherCtrl();
            break;
        case 'cycle':
            require_once( 'Controller/CycleCtrl.php' );
            $ctrl = new CycleCtrl();
            break;
        case 'login':
            require_once( 'Controller/LoginCtrl.php' );
            $ctrl = new LoginCtrl();
            break;
        case 'class':
            require_once( 'Controller/ClassCtrl.php' );
            $ctrl = new ClassCtrl();
            break;
        case 'default':
            require_once( 'Controller/DefaultCtrl.php' );
            $ctrl = new DefaultCtrl();
            break;
        default:
            echo "Error";
            break;
    }

    $ctrl -> execute();
}
else{
    require_once( 'Controller/LoginCtrl.php' );
    $ctrl = new LoginCtrl();
    $_GET['action'] = 'login';
    $ctrl -> execute();
}

?>

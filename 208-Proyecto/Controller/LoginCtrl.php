<?php

/** @file LoginCtrl.php
    @author Rodrigo Fuentes Hernandez <earthjenei@gmail.com>
    
    This file contains the LoginCtrl class used as a
    controller of the MVC design patter for login
    operations.
*/

require_once( 'Controller/DefaultCtrl.php' );

/** @brief The LoginCtrl class functions as the intermediator
    between log-in related views and operations.
    
    The LoginCtrl class mediates between the views needed to
    log in to the system or recover a password and their
    respective operations an authentication on the server side.
    The LoginCtrl class is a direct child of the DefaultCtrl
    class.
    Operation in the LoginCtrl class do not require any level
    of permission.
*/
class LoginCtrl extends DefaultCtrl{
    private $model;                             ///< This controller's model.
    
    /** @brief The LoginCtrl class' constructor.
    
        The LoginCtrl class constructor in charge of calling
        its parent constructor to initialize the session and
        instantiating its related module.
    */
    public function __construct(){
        parent::__construct();
        require_once( 'Model/LoginMdl.php' );
        $this -> model = new LoginMdl();
    }
    
    /** @brief Selects the action to do depending on the
        received GET action.
        
        The execute() method is in charge of selecting the
        correct action according the value given during
        a GET request to this controller. Supported operations
        include log-in authentication and password reset.
    */
    public function execute(){
        switch( $_GET['action'] ){
            case 'login':
                /// Checks if session exists.
                if( !$this -> checkSession() ){
                    $this -> attemptLogin();
                }
                else{
                    $this -> redirectUser();
                }
                break;
            case 'recover':
                /// This operation does not require permissions.
                $this -> recoverPass();
                break;
        }
    }
    
    /** @brief Attemps to authentica a user's code and password.
    
        The attemptLogin() method will take the values given in
        a POST request: the user's code and password, and will
        try to match them to any of the existing users in the
        database: either a student, a teacher of an admin.
        If the user is authenticated properly, session values are
        set to the user's id, the user's code and the user's
        level of permissions.
    */
    protected function attemptLogin(){
        if( empty( $_POST ) ){
            require_once( 'View/login.html' );
        }
        else{
            $code = $_POST['login-code'];
            $pass = $_POST['password'];
            
            /// If user is an admin...
            if( ( $account = $this -> searchAdmins( $code, $pass, TRUE ) ) !== FALSE ){
                $view = file_get_contents( 'View/Admin/adminMain.html' );
                $_SESSION['user_id'] = $account['idAdmin'];
                $_SESSION['user_type'] = 'ninja';
                $_SESSION['user_code'] = $code;
            }
            /// If user is a teacher...
            else if( ( $account = $this -> searchTeachers( $code, $pass, TRUE ) ) !== FALSE ){
                $view = file_get_contents( 'View/Profesores/profesoresMain.html' );
                $_SESSION['user_id'] = $account['idProfesor'];
                $_SESSION['user_type'] = 'brigadier';
                $_SESSION['user_code'] = $code;
            }
            /// If user is a student...
            else if( ( $account = $this -> searchStudents( $code, $pass, TRUE ) ) !== FALSE ){
                $view = file_get_contents( 'View/Alumnos/alumnosMain.html' );
                $_SESSION['user_id'] = $account['idAlumno'];
                $_SESSION['user_type'] = 'rookie';
                $_SESSION['user_code'] = $code;
            }
            /// No user/password match up.
            else{
                $view = file_get_contents( 'View/login.html' );
                $view = str_replace( '<div class="message-div">',
                                     '<div class="message-div"> Codigo y contraseña incorrectos.',
                                     $view );
            }
            echo $view;
        }
    }
    
    /** @brief Searches for code and password matching on
        registered admins.
        
        The searchAdmins() method is in charge of getting the information
        about all registered admins and searching for a matching code
        and password.
        @param code : The user's code.
        @param pass : The user's password.
        @param auth : If TRUE, matching is done against code and password,
        otherwise is done only over the code.
        @return Returns FALSE if no admin matched the code and password
        provided. If a match was found, returns the info row of the found
        admin.
    */
    private function searchAdmins( $code, $pass, $auth ){
        $admins = $this -> model -> getAllAdmins();
        $found = FALSE;
        $myAdmin = NULL;
        
        foreach( $admins as $admin ){
            if( !$found ){
                if( $auth === TRUE ){
                    $check = ( $admin['codigo'] == $code && $admin['password'] == $pass );
                }
                else{
                    $check = ( $admin['codigo'] == $code );
                }
                if( $check ){
                    $found = TRUE;
                    $myAdmin = $admin;
                }
            }
        }
        
        if( $found ){
            return $myAdmin;
        }
        else{
            return $found;
        }
    }
    
    /** @brief Searches for code and password matching on
        registered teachers.
        
        The searchTeachers() method is in charge of getting the information
        about all registered teachers and searching for a matching code
        and password.
        @param code : The user's code.
        @param pass : The user's password.
        @param auth : If TRUE, matching is done against code and password,
        otherwise is done only over the code.
        @return Returns FALSE if no teacher matched the code and password
        provided. If a match was found, returns the info row of the found
        teacher.
    */
    private function searchTeachers( $code, $pass, $auth ){
        $teachers = $this -> model -> getAllTeachers();
        $found = FALSE;
        $myTeacher = NULL;
        
        foreach( $teachers as $teacher ){
            if( !$found ){
                if( $auth === TRUE ){
                    $check = ( $teacher['codigo'] == $code && $teacher['password'] == $pass );
                }
                else{
                    $check = ( $teacher['codigo'] == $code );
                }
                if( $check ){
                    $found = TRUE;
                    $myTeacher = $teacher;
                }
            }
        }
        
        if( $found ){
            return $myTeacher;
        }
        else{
            return $found;
        }
    }
    
    /** @brief Searches for code and password matching on
        registered admins.
        
        The searchStudents() method is in charge of getting the information
        about all registered students and searching for a matching code
        and password.
        @param code : The user's code.
        @param pass : The user's password.
        @param auth : If TRUE, matching is done against code and password,
        otherwise is done only over the code.
        @return Returns FALSE if no student matched the code and password
        provided. If a match was found, returns the info row of the found
        student.
    */
    private function searchStudents( $code, $pass, $auth ){
        $students = $this ->  model -> getAllStudents();
        $found = FALSE;
        $myStudent = NULL;
        
        foreach( $students as $student ){
            if( !$found ){
                if( $auth === TRUE ){
                    $check = ( $student['codigo'] == $code && $student['password'] == $pass );
                }
                else{
                    $check = ( $student['codigo'] == $code );
                }
                if( $check ){
                    $found = TRUE;
                    $myStudent = $student;
                }
            }
        }
        
        if( $found ){
            return $myStudent;
        }
        else{
            return $found;
        }
    }
    
    /** @brief Resets the user password.
    
        The recoverPass() method will try to generate a new password
        for a user by identifying the user by its code. After making
        sure the code exists, an email will be sent to the registered
        user's email account with their newly reset password.
    */
    protected function recoverPass(){
        if( empty( $_POST ) ){
            require_once( 'View/lostPass.html' );
        }
        else{
            $ok = TRUE;
            $code = $_POST['lost-pass-code'];
            if( ( $account = $this -> searchAdmins( $code, '', FALSE ) ) !== FALSE ){
                $newPass = $this -> getAdminPassword();
                if( $this -> model -> updateAdminPass( $code, $newPass ) === TRUE ){
                    $view = file_get_contents( 'View/login.html' );
                    $view = str_replace( '<div class="message-div">',
                                         "<div class=\"message-div\"> Su nueva contraseña es $newPass.",
                                         $view );
                }
                else{
                    $view = 'Error';
                }
            }
            else if( ( $account = $this -> searchTeachers( $code, '', FALSE ) ) !== FALSE ){
                $newPass = $this -> getTeacherPassword( $account['nombre'], $account['apellidoP'], $account['apellidoM'] );
                if( $this -> model -> updateTeacherPass( $code, $newPass ) === TRUE ){
                    $view = file_get_contents( 'View/login.html' );
                    $view = str_replace( '<div class="message-div">',
                                         "<div class=\"message-div\"> Su nueva contraseña es $newPass.",
                                         $view );
                }
                else{
                    $view = 'Error';
                }
            }
            else if( ( $account = $this -> searchStudents( $code, '', FALSE ) ) !== FALSE ){
                $newPass = $this -> getStudentPassword( $account['nombre'], $account['apellidoP'], $account['apellidoM'] );
                if( $this -> model -> updateStudentPass( $code, $newPass ) === TRUE ){
                    $view = file_get_contents( 'View/login.html' );
                    $view = str_replace( '<div class="message-div">',
                                         "<div class=\"message-div\"> Su nueva contraseña es $newPass.",
                                         $view );
                }
                else{
                    $view = 'Error';
                }
            }
            else{
                $view = file_get_contents( 'View/login.html' );
                $view = str_replace( '<div class="message-div">',
                                     '<div class="message-div"> Intento de recuperar contraseña fallido.',
                                     $view );
                $ok = FALSE;
            }
            if( $ok ){
                $this -> sendPassChangeMail( $account, $newPass );
            }
            
            echo $view;
        }
    }
    
    /** @brief Sends a password reset notification to the user's email.
    
        The sendPassChangeMail() method sends an email notifying the user
        that his or her password has been reset as a request to the system.
        This email will contain the user's new password.
        @param account : The user's info row on the database.
        @param newPass : The user's new password string.
    */
    protected function sendPassChangeMail( $account, $newPass ){
        $mail = $account['email'];
        
        $to = $mail;
        $subject = 'Recuperacion de contraseña (Sistema de calificaciones)';
        
        $header = 'MIME-Version: 1.0' . "\r\n";
        $header .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $header .= 'From: user208@alanturing.cucei.udg.mx' . "\r\n";
        
        $content = '<em>Se ha reestablecido la contraseña de su cuenta. Su codigo y contraseña son los siguientes:</em><br />' . PHP_EOL;
        $content .= '<strong>Codigo</strong>: ' . $account['codigo'] . '<br />' . PHP_EOL;
        $content .= '<strong>Contraseña</strong>: ' . $newPass . '<br />' . PHP_EOL;
        
        $result = mail( $to, $subject, $content, $header );
    }
    
    /** @brief Creates a student's password.
    
        The getStudentPassword() method will create a new password for
        a student based on its name and last names. All strings are
        concatenated and stripped off of weird character. This string
        is shuffled and then the first 8 characters are selected as the
        user's password.
        @param name : The student's name.
        @param last1 : The student's first last name.
        @param last2 : The student's second last name.
        @return Returns the user's new password string.
    */
    private function getStudentPassword( $name, $last1, $last2 ){
        $accents = array( 'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ñ' => 'n' );
        $pass = $name . $last1 . $last2;
        $pass = strtolower( $pass );
        $pass = strtr( $pass, $accents );
        $pass = str_shuffle( $pass );
        $pass = substr( $pass, 0, 8 );
        
        return $pass;
    }
    
    /** @brief Creates a teacher's password.
    
        The getTeacherPassword() method will create a new password for
        a teacher based on its name and last names. All strings are
        concatenated and stripped off of weird character. This string
        is shuffled and then the first 12 characters are selected as the
        user's password.
        @param name : The teacher's name.
        @param last1 : The teacher's first last name.
        @param last2 : The teacher's second last name.
        @return Returns the user's new password string.
    */
    private function getTeacherPassword( $name, $last1, $last2 ){
        $accents = array( 'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ñ' => 'n' );
        $pass = $name . $last1 . $last2;
        $pass = strtolower( $pass );
        $pass = strtr( $pass, $accents );
        $pass = str_shuffle( $pass );
        $pass = substr( $pass, 0, 12 );
        
        return $pass;
    }
    
    /** @brief Creates an admin's password.
    
        The getAdminPassword() method will create a new password for
        an admin. A range of letters is created, and 8 random letters
        are selecte to be part of the new password.
        @return The new user's password string.
    */
    private function getAdminPassword(){
        $letters = range( 'a', 'z' );
        
        $pass = '';
        for( $i = 0; $i < 8; $i += 1 ){
            $pass .= $letters[array_rand( $letters )]; 
        }
        
        return $pass;
    }
}

?>

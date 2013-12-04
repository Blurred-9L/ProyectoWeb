<?php

/** @file TeacherCtrl.php
    @author Rodrigo Fuentes Hernandez <earthjenei@gmail.com>
    
    The TeacherCtrl file contains the implementation of the
    TeacherCtrl class, a controller for teacher related
    operations.
*/

require_once( 'Controller/DefaultCtrl.php' );

/** @brief The TeacherCtrl class serves as an mediator between
    user teacher related operations and views.
    
    The TeacherCtrl class is a direct child class of the
    DefaultCtrl class, inheriting basic controller functionality
    from it. The TeacherCtrl class is in charge of mediating all
    teacher related operation, including adding a new teacher,
    editing a teacher, changing password or showing data of a
    teacher.
*/
class TeacherCtrl extends DefaultCtrl{
    private $model;                     ///< This controller's model.
    
    /** @brief The TeacherCtrl class' constructor.
        
        The TeacherCtrl class constructor is in charge of initializing
        the session variables by calling its parent constructor as
        well as instantiating its related model.
    */
    public function __construct(){
        parent::__construct();
        require_once( 'Model/TeacherMdl.php' );
        $this -> model = new TeacherMdl();
    }
    
    /** @brief Selects the action to do depending on the
        received GET action.
        
        The execute() method is in charge of selecting the
        correct action according the value given during
        a GET request to this controller. Included operations
        are inserting teachers, modifying teachers, showing
        data about all of a specific teacher or changing the
        teacher's user password.
    */
    public function execute(){
        switch( $_GET['action'] ){
            case 'new':
                if( $this -> checkPermissions( 'ninja' ) ){
                    $this -> newTeacher();
                }
                else{
                    header( 'Location: index.php?ctrl=login&action=login' );
                }
                break;
            case 'edit':
                if( $this -> checkPermissions( 'ninja' ) ){
                    $this -> editTeacher();
                }
                else{
                    header( 'Location: index.php?ctrl=login&action=login' );
                }
                break;
            case 'all':
                if( $this -> checkPermissions( 'ninja' ) ){
                    $this -> showAllTeachers();
                }
                else{
                    header( 'Location: index.php?ctrl=login&action=login' );
                }
                break;
            case 'show':
                if( $this -> checkPermissions( 'ninja' ) ){
                    $this -> showTeacher();
                }
                else{
                    header( 'Location: index.php?ctrl=login&action=login' );
                }
                break;
            case 'data':
                if( $this -> checkPermissions( 'brigadier' ) ){
                    $this -> teacherData();
                }
                else{
                    header( 'Location: index.php?ctrl=login&action=login' );
                }
                break;
            case 'pass-ch':
                if( $this -> checkPermissions( 'brigadier' ) ){
                    $this -> changePassword();
                }
                else{
                    header( 'Location: index.php?ctrl=login&action=login' );
                }
                break;
        }
    }
    
    /** @brief Creates a teacher's password.
    
        The getPassword() method will create a new password for
        a teacher based on its name and last names. All strings are
        concatenated and stripped off of weird characters. This string
        is shuffled and then the first 12 characters are selected as the
        user's password.
        @param name : The teacher's name.
        @param last1 : The teacher's first last name.
        @param last2 : The teacher's second last name.
        @return Returns the user's new password string.
    */
    public function getPassword( $name, $last1, $last2 ){
        $accents = array( 'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ñ' => 'n' );
        $pass = $name . $last1 . $last2;
        $pass = strtolower( $pass );
        $pass = strtr( $pass, $accents );
        $pass = str_shuffle( $pass );
        $pass = substr( $pass, 0, 12 );
        
        return $pass;
    }
    
    /** @brief Manipulates the edit view and shows it to the user.
    
        The showEditView() method will manipulate a template HTML file
        in order to show editable and not-editable information about
        a teacher.
        @param code : The teacher's code.
        @param name : The teacher's names.
        @param last1 : The teacher's first last name.
        @param last2 : The teacher's second last name.
        @param mail : The teacher's email account.
        @oaram phone : The teacher's cell phone.
    */
    protected function showEditView( $code, $name, $last1, $last2, $mail, $phone ){
        $view = file_get_contents( 'View/Admin/editarProfesor.html' );
        
        $dict = array( '*code*' => $code, '*name*' => $name, '*last1*' => $last1, '*last2*' => $last2 );
        $view = strtr( $view, $dict );
        $view = str_replace( '<input type="email" id="edit-teacher-email" name="edit-teacher-email" />',
                             "<input type=\"email\" id=\"edit-teacher-email\" name=\"edit-teacher-email\" value=\"$mail\" />",
                             $view );
        $view = str_replace( '<input type="tel" id="edit-teacher-phone" name="edit-teacher-phone" />',
                             "<input type=\"tel\" id=\"edit-teacher-phone\" name=\"edit-teacher-phone\" value=\"$phone\" />",
                             $view );
                             
        echo $view;
    }
    
    /** @brief Manipulates the edit view and shows it to the user.
        
        The updateEditView() method will manipulate a template HTML file
        in order to show editable and not-editable information about
        a teacher. Information about the teacher is recovered by knowing
        the specific teacher's code.
        @param $code : The teacher's code.
    */
    protected function updateEditView( $code ){
        $row = $this -> model -> getTeacher( $code );
        $view = file_get_contents( 'View/Admin/editarProfesor.html' );
        
        $code = $row['codigo'];
        $mail = $row['email'];
        $phone = $row['celular'];
        $dict = array( '*code*' => $code, '*name*' => $row['nombre'], '*last1*' => $row['apellidoP'], '*last2*' => $row['apellidoM'] );
        $view = strtr( $view, $dict );
        $view = str_replace( '<input type="email" id="edit-teacher-email" name="edit-teacher-email" />',
                             "<input type=\"email\" id=\"edit-teacher-email\" name=\"edit-teacher-email\" value=\"$mail\" />",
                             $view );
        $view = str_replace( '<input type="tel" id="edit-teacher-phone" name="edit-teacher-phone" />',
                             "<input type=\"tel\" id=\"edit-teacher-phone\" name=\"edit-teacher-phone\" value=\"$phone\" />",
                             $view );
                             
        echo $view;
    }
    
    /** @brief Handles the insertion operation of a teacher.
    
        The newTeacher() method receives the information of the new teacher
        to insert via POST request and tries to insert it into the database.
        Once the teacher has been inserted, an email is sent to the teacher's
        email account notifying him or her that he or she is now part of
        the grading system.
    */
    protected function newTeacher(){
        if( empty( $_POST ) ){
            require_once( 'View/Admin/profesorNuevo.html' );
        }
        else{
            $code = $_POST['reg-teacher-code'];
            $name = $_POST['reg-teacher-name'];
            $last1 = $_POST['reg-teacher-last1'];
            $last2 = $_POST['reg-teacher-last2'];
            $mail = $_POST['reg-teacher-email'];
            $phone = $_POST['reg-teacher-phone'];
            $pass = $this -> getPassword( $name, $last1, $last2 );
            
            $result = $this -> model -> register( $code, $name, $last1, $last2, $mail, $phone, $pass );
            
            if( $result === TRUE ){
                $this -> showEditView( $code, $name, $last1, $last2, $mail, $phone );
                $this -> sendMail( $code, $name . ' ' . $last1 . ' ' . $last2, $pass, $mail );
            }
            else{
                echo "Error";
            }
        }
    }
    
    /** @brief Sends a notification email to a newly added teacher.
    
        The sendMail function notifies a teacher his inclusion on the
        grading system database, by sending him his code and password
        and a link to the system.
        @param code : The teacher's code.
        @param name : The teacher's full name.
        @param pass : The teacher's password.
        @param mail : The teacher's email account.
    */
    private function sendMail( $code, $name, $pass, $mail ){
        $to = $mail;
        $subject = 'Alta en sistema de calificaciones';
        
        $header = 'MIME-Version: 1.0' . "\r\n";
        $header .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $header .= 'From: user208@alanturing.cucei.udg.mx' . "\r\n";
        
        $content = "<p><strong>$name</strong>, bienvenido al sistema de calificaciones universitario.<br />" . PHP_EOL;
        $content .= 'Ahora podra gestionar sus cursos escolares y evaluaciones.' . PHP_EOL;
        $content .= 'Usted puede acceder al sistema accediendo a la siguiente direccion:<br />' . PHP_EOL;
        $content .= '<a href="alanturing.cucei.udg.mx/cc409/user208/index.php?ctrl=login&action=login">Sistema de calificaciones</a>' . PHP_EOL;
        $content .= "Su cuenta es la siguiente:</p><ul><li><strong>Codigo</strong>: $code</li>" . PHP_EOL;
        $content .= "<li><strong>Contraseña</strong>: $pass</li></ul>" . PHP_EOL;
        
        $result = mail( $to, $subject, $content, $header );
    }
    
    /** @brief Updates editable teacher info.
    
        The editTeacher() method receives the editable information of
        a teacher (email and cell phone) via POST request and tries to
        update the information on the database to match the provided
        information.
    */
    protected function editTeacher(){
        if( empty( $_POST ) ){
        }
        else{
            $code = $_POST['edit-teacher-code'];
            $mail = $_POST['edit-teacher-email'];
            $phone = $_POST['edit-teacher-phone'];
            
            $result = $this -> model -> update( $code, $mail, $phone );
            
            if( $result === TRUE ){
                $this -> updateEditView( $code );
            }
            else{
                echo "Error";
            }
        }
    }
    
    /** @brief Shows information about all teachers.
    
        The showAllTeachers() method manipulates a template HTML file
        in order to show basic information about registered teachers.
        This information includes: full name, code, cell phone and
        email account. The information is shown in an HTML table.
    */
    protected function showAllTeachers(){
        $view = file_get_contents( 'View/Admin/verProfesores.html' );
        
        $start = strrpos( $view, '<tr>' );
        $end = strrpos( $view, '</tr>' ) + 5;
        $tableRow = substr( $view, $start, $end - $start );
        
        $teachers = $this -> model -> getAll();
        $rows = '';
        $count = 0;
        if( isset( $teachers ) ){
            foreach( $teachers as $row ){
                $newTableRow = $tableRow;
                $name = $row['nombre'] . ' ' . $row['apellidoP'] . ' ' . $row['apellidoM'];
                $dict = array( '*name*' => $name, '*code*' => $row['codigo'], '*phone*' => $row['celular'], '*mail*' => $row['email'],
                               '*count*' => $count );
                $newTableRow = strtr( $newTableRow, $dict );
                $rows .= $newTableRow;
                $count += 1;
            }
        }
            
        $view = str_replace( $tableRow, $rows, $view );
        
        echo $view;
    }
    
    /** @brief Receives a teacher's code and delegates the display of
        its information.
        
        The showTeacher() method receives the teacher's code by
        checking the information on the POST request and calls the
        updateEditView() method in order to show the teacher's information.
        @sa updateEditView().
    */
    protected function showTeacher(){
        $code = $_POST['code'];
        
        $this -> updateEditView( $code );
    }
    
    /** @brief Gets template view to show a teacher user's information.
    
        The teacherDataGetView() method also manipulates a view, in order
        to show the teacher's respective information. The teacher's code
        is obtained from the SESSION variables.
    */
    protected function teacherDataGetView(){
        $view = file_get_contents( 'View/Profesores/datosProfesor.html' );
        $teacherCode = $_SESSION['user_code'];
        $row = $this -> model -> getTeacher( $teacherCode );
        
        $code = $row['codigo'];
        $mail = $row['email'];
        $phone = $row['celular'];
        $dict = array( '*code*' => $code, '*name*' => $row['nombre'], '*last1*' => $row['apellidoP'], '*last2*' => $row['apellidoM'] );
        $view = strtr( $view, $dict );
        $view = str_replace( '<input type="email" id="teacher-email" name="teacher-email" />',
                             "<input type=\"email\" id=\"teacher-email\" name=\"teacher-email\" value=\"$mail\" />",
                             $view );
        $view = str_replace( '<input type="tel" id="teacher-phone" name="teacher-phone" />',
                             "<input type=\"tel\" id=\"teacher-phone\" name=\"teacher-phone\" value=\"$phone\" />",
                             $view );
        
        echo $view;
    }
    
    /** @brief Updates data on teacher's request.
    
        The teacherData() method gets the necessary editable
        information about a teacher an updates it due to the own
        teacher's request.
    */
    protected function teacherData(){
        if( empty( $_POST ) ){
            $this -> teacherDataGetView();
        }
        else{
            $mail = $_POST['teacher-email'];
            $phone = $_POST['teacher-phone'];
            $teacherCode = $_SESSION['user_code'];
            
            $result = $this -> model -> update( $teacherCode, $mail, $phone );
            if( $result === TRUE ){
                $this -> teacherDataGetView();
            }
            else{
                echo "Error";
            }
        }
    }
    
    /** @brief Shows the change password view for a teacher.
    
        The teacherPassGetView() gets the change password template for
        a teacher and optionally adds a message to show the status
        regarding a password update. If this is the first time the
        view is being needed, an empty string should be given.
        @param msg : A string representing a message to append to the
        template.
    */
    private function teacherPassGetView( $msg ){
        $view = file_get_contents( 'View/Profesores/passProfesor.html' );
        
        $dict = array( '*msg*' => $msg );
        $view = strtr( $view, $dict );
        
        echo $view;
    }
    
    /** @brief Chagnes the teacher's password.
    
        The changePassword() method is in charge of receiving via
        POST request, the new password information, as well as
        trying to update the teacher's password, finally showing
        the teacher's change password view again, sending the
        status of the operation as the message.
        @sa teacherPassGetView().
    */
    private function changePassword(){
        if( empty( $_POST ) ){
            $this -> teacherPassGetView( '' );
        }
        else{
            $shaTeacherPass = $_POST['teacher-password'];
            $newPass = $_POST['new-password1'];
            $newPass2 = $_POST['new-password2'];
        
            $code = $_SESSION['user_code'];
            $teacherRow = $this -> model -> getTeacher( $code );
            $dbTeacherPass = $teacherRow['password'];
            
            if( $dbTeacherPass != $shaTeacherPass || $newPass != $newPass2 ){
                $this -> teacherPassGetView( 'Contraseña incorrecta.' );
            }
            else{
                $result = $this -> model -> updatePassword( $code, $newPass );
                if( $result === TRUE ){
                    $this -> teacherPassGetView( 'Contraseña actualizada.' );
                }
                else{
                    $this -> teacherPassGetView( 'Hubo un fallo al intentar actualizar la contraseña.' );
                }
            }
        }
    }
}

?>

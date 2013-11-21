<?php

require_once( 'Controller/DefaultCtrl.php' );

class StudentCtrl extends DefaultCtrl{
    private $model;
    
    public function __construct(){
        parent::__construct();
        require_once( 'Model/StudentMdl.php' );
        $this -> model = new StudentMdl();
    }
    
    public function execute(){
        switch( $_GET['action'] ){
            case 'anew':
                if( $this -> checkPermissions( 'ninja' ) ){
                    $this -> adminNewStudent();
                }
                else{
                    header( 'Location: index.php?ctrl=login&action=login' );
                }
                break;
            case 'edit':
                if( $this -> checkPermissions( 'ninja' ) ){
                    $this -> edit();
                }
                else{
                    header( 'Location: index.php?ctrl=login&action=login' );
                }
                break;
            case 'all':
                if( $this -> checkPermissions( 'ninja' ) ){
                    $this -> showAll();
                }
                else{
                    header( 'Location: index.php?ctrl=login&action=login' );
                }
                break;
            case 'show':
                if( $this -> checkPermissions( 'ninja' ) ){
                    $this -> show();
                }
                else{
                    header( 'Location: index.php?ctrl=login&action=login' );
                }
                break;
            case 'tnew':
                if( $this -> checkPermissions( 'brigadier' ) ){
                    $this -> teacherNewStudent();
                }
                else{
                    header( 'Location: index.php?ctrl=login&action=login' );
                }
                break;
            case 'list':
                if( $this -> checkPermissions( 'brigadier' ) ){
                    $this -> listStudents();
                }
                else{
                    header( 'Location: index.php?ctrl=login&action=login' );
                }
                break;
            case 'load':
                $this -> loadStudents();
                break;
        }
    }
    
    public function getPassword( $name, $last1, $last2 ){
        $accents = array( 'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ñ' => 'n' );
        $pass = $name . $last1 . $last2;
        $pass = strtolower( $pass );
        $pass = strtr( $pass, $accents );
        $pass = str_shuffle( $pass );
        $pass = substr( $pass, 0, 8 );
        
        return $pass;
    }
    
    public function processViewEditStudent( $code, $name, $last1, $last2, $mail, $major, $pass, $phone, $url, $github ){
        $row = $this -> model -> getMajorStr( $major );
        $majorStr = $row['nombreCarrera'];
        $view = file_get_contents( 'View/Admin/editarAlumno.html' );
        
        $dict = array( '*name*' => $name, '*last1*' => $last1, '*last2*' => $last2, '*major*' => $majorStr );
        $view = strtr( $view, $dict );
        $view = str_replace( '<input type="text" id="edit-student-code" name="edit-student-code" required />',
                             "<input type=\"text\" id=\"edit-student-code\" name=\"edit-student-code\" value=\"$code\" required />",
                             $view );
        $view = str_replace( '<input type="email" id="edit-student-email" name="edit-student-email" />',
                             "<input type=\"email\" id=\"edit-student-email\" name=\"edit-student-email\" value=\"$mail\" />",
                             $view );
        if( isset( $phone ) ){
            $view = str_replace( '<input type="tel" id="edit-student-phone" name="edit-student-phone" />',
                                 "<input type=\"tel\" id=\"edit-student-phone\" name=\"edit-student-phone\" value=\"$phone\" />",
                                 $view );
        }
        if( isset( $url ) ){
            $view = str_replace( '<input type="url" id="edit-student-url" name="edit-student-url" />',
                                 "<input type=\"url\" id=\"edit-student-url\" name=\"edit-student-url\" value=\"$url\" />",
                                 $view );
        }
        if( isset( $github ) ){
            $view = str_replace( '<input type="url" id="edit-student-github" name="edit-student-github" />',
                                 "<input type=\"url\" id=\"edit-student-github\" name=\"edit-student-github\" value=\"$github\" />",
                                 $view );
        }
        
        echo $view;
    }
    
    public function updateViewEditStudent( $code ){
        $row = $this -> model -> getStudent( $code );
        $view = file_get_contents( 'View/Admin/editarAlumno.html' );
        $code = $row['codigo'];
        $mail = $row['email'];
        $phone = $row['celular'];
        $url = $row['paginaWeb'];
        $github = $row['github'];
        
        $dict = array( '*name*' => $row['nombre'], '*last1*' => $row['apellidoP'],
                       '*last2*' => $row['apellidoM'], '*major*' => $row['nombreCarrera'] );
        $view = strtr( $view, $dict );
        $view = str_replace( '<input type="text" id="edit-student-code" name="edit-student-code" required />',
                             "<input type=\"text\" id=\"edit-student-code\" name=\"edit-student-code\" value=\"$code\" required />",
                             $view );
        $view = str_replace( '<input type="email" id="edit-student-email" name="edit-student-email" />',
                             "<input type=\"email\" id=\"edit-student-email\" name=\"edit-student-email\" value=\"$mail\" />",
                             $view );
        $view = str_replace( '<input type="tel" id="edit-student-phone" name="edit-student-phone" />',
                                 "<input type=\"tel\" id=\"edit-student-phone\" name=\"edit-student-phone\" value=\"$phone\" />",
                                 $view );
        $view = str_replace( '<input type="url" id="edit-student-url" name="edit-student-url" />',
                                 "<input type=\"url\" id=\"edit-student-url\" name=\"edit-student-url\" value=\"$url\" />",
                                 $view );
        $view = str_replace( '<input type="url" id="edit-student-github" name="edit-student-github" />',
                                 "<input type=\"url\" id=\"edit-student-github\" name=\"edit-student-github\" value=\"$github\" />",
                                 $view );
        
        echo $view;                       
    }
    
    private function adminNewStudent(){
        if( empty( $_POST ) ){
            require_once( 'View/Admin/adminAlumnosNuevo.html' );
        }
        else{
            $code = $_POST['reg-student-code'];
            $name = $_POST['reg-student-name'];
            $last1 = $_POST['reg-student-last1'];
            $last2 = $_POST['reg-student-last2'];
            $mail = $_POST['reg-student-email'];
            $major = $_POST['reg-student-major'];
            if( array_key_exists( 'reg-student-phone', $_POST ) ){
                $phone = $_POST['reg-student-phone'];
            }
            else{
                $phone = NULL;
            }
            if( array_key_exists( 'reg-student-url', $_POST ) ){
                $url = $_POST['reg-student-url'];
            }
            else{
                $url = NULL;
            }
            if( array_key_exists( 'reg-student-github', $_POST ) ){
                $github = $_POST['reg-student-github'];
            }
            else{
                $github = NULL;
            }
            $pass = $this -> getPassword( $name, $last1, $last2 );
            
            $result = $this -> model -> register( $code, $name, $last1, $last2, $mail, $major, $pass, $phone, $url, $github );
            if( $result === TRUE ){
                $this -> processViewEditStudent( $code, $name, $last1, $last2, $mail, $major, $pass, $phone, $url, $github );
                $this -> sendMail( $code, $name . ' ' . $last1 . ' ' . $last2, $pass, $mail );
            }
            else{
                echo "Error";
            }
        }
    }
    
    private function sendMail( $code, $name, $pass, $mail ){
        $to = $mail;
        $subject = 'Alta en sistema de calificaciones';
        
        $header = 'MIME-Version: 1.0' . "\r\n";
        $header .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $header .= 'From: user208@alanturing.cucei.udg.mx' . "\r\n";
        
        $content = "<p><strong>$name</strong>, bienvenido al sistema de calificaciones universitario.<br />" . PHP_EOL;
        $content .= 'Ahora podra revisar sus calificaciones para los cursos en los que sea registrado por ';
        $content .= 'sus profesores.<br />' . PHP_EOL;
        $content .= 'Usted puede acceder al sistema accediendo a la siguiente direccion:<br />' . PHP_EOL;
        $content .= '<a href="alanturing.cucei.udg.mx/cc409/user208/index.php?ctrl=login&action=login">Sistema de calificaciones</a>' . PHP_EOL;
        $content .= "Su cuenta es la siguiente:</p><ul><li><strong>Codigo</strong>: $code</li>" . PHP_EOL;
        $content .= "<li><strong>Contraseña</strong>: $pass</li></ul>" . PHP_EOL;
        
        $result = mail( $to, $subject, $content, $header );
    }
    
    private function sendClassMail( $name, $mail, $className, $classSec ){
        $to = $mail;
        $subject = "Inscripcion a $className seccion $classSec";
        
        $header = 'MIME-Version: 1.0' . "\r\n";
        $header .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $header .= 'From: user208@alanturing.cucei.udg.mx' . "\r\n";
        
        $content = "<p><strong>$name</strong>, bienvenido al curso <em>$className</em>" . PHP_EOL;
        $content .= 'Usted puede acceder al sistema accediendo a la siguiente direccion:<br />' . PHP_EOL;
        $content .= '<a href="alanturing.cucei.udg.mx/cc409/user208/index.php?ctrl=login&action=login">Sistema de calificaciones</a>' . PHP_EOL;
        
        $result = mail( $to, $subject, $content, $header );
    }
    
    private function teacherNewStudent(){
        if( empty( $_POST ) ){
            require_once( 'View/Profesores/alumnosNuevo.html' );
        }
        else{
            $code = $_POST['reg-student-code'];
            $name = $_POST['reg-student-name'];
            $last1 = $_POST['reg-student-last1'];
            $last2 = $_POST['reg-student-last2'];
            $mail = $_POST['reg-student-email'];
            $major = $_POST['reg-student-major'];
            if( array_key_exists( 'reg-student-phone', $_POST ) ){
                $phone = $_POST['reg-student-phone'];
            }
            else{
                $phone = NULL;
            }
            if( array_key_exists( 'reg-student-url', $_POST ) ){
                $url = $_POST['reg-student-url'];
            }
            else{
                $url = NULL;
            }
            if( array_key_exists( 'reg-student-github', $_POST ) ){
                $github = $_POST['reg-student-github'];
            }
            else{
                $github = NULL;
            }
            $classInfo = $_POST['reg-student-class'];
            $found = FALSE;
            
            if( !$this -> model -> getStudent( $code ) ){
                // Necesito registrar el estudiante.
                $pass = $this -> getPassword( $name, $last1, $last2 );
                $result = $this -> model -> register( $code, $name, $last1, $last2, $mail, $major, $pass, $phone, $url, $github );
                if( $result === FALSE ){
                    echo "Error";
                }
                else{
                    $this -> sendMail( $code, $name . ' ' . $last1 . ' ' . $last2, $pass, $mail );
                    $studentId = $this -> model -> insertId();
                }
            }
            else{
                // Ya tengo al estudiante.
                $found = TRUE;
                $student = $this -> model -> getStudent( $code );
                $studentId = $student['idAlumno'];
            }
            
            $classArray = explode( '-', $classInfo );
            $classKey = $classArray[0];
            $classSec = $classArray[1];
            $cycleStr = $classArray[2];
            
            $classRow = $this -> model -> getClass( $classKey );
            $classId = $classRow['idCurso'];
            
            $cycleRow = $this -> model -> getCycle( $cycleStr );
            $cycleId = $cycleRow['idCiclo'];
            
            $teacherId = $_SESSION['user_id'];
            $teacherClassRow = $this -> model -> getTeacherClass( $classId, $teacherId, $cycleId, $classSec );
            $teacherClassId = $teacherClassRow['idCursoProfesor'];
            
            $result = $this -> model -> signUpToClass( $studentId, $teacherClassId );
            if( $result === TRUE ){
                $this -> sendClassMail( $name, $mail, $classRow['nombre'], $classSec );
                $studentClassId = $this -> model -> insertId();
                // generarAsistencias
                $this -> createClassDays( $cycleId, $teacherClassId, $studentClassId );
                // generarElemCalificacion
                $this -> createEvalElems( $teacherClassId, $studentClassId );
                if( $found ){
                    $this -> listStudents();
                }
                else{
                    $this -> listStudents();
                }
            }
            else{
                echo "Error";
            }
            
        }
    }
    
    private function createClassDays( $cycleId, $teacherClassId, $studentClassId ){
        $cycle = $this -> model -> getCycleRange( $cycleId );

        $dayRow = $this -> model -> getStartDayNum( $cycleId );
        $startDay = $dayRow['nDia'];
        $interval = new DateInterval( "P1D" );

        $freeDays = $this -> model -> getCycleFreeDays( $cycleId );
        $classes = $this -> model -> getClassDayNums( $teacherClassId );

        $startDate = new DateTime( $cycle['fechaInicio'], new DateTimeZone( "America/Mexico_City" ) );
        $endDate = new DateTime( $cycle['fechaFin'], new DateTimeZone( "America/Mexico_City" ) );

        $curDate = $startDate;
        $day = $startDay;
        $classDays = array();
        $auxInterval = $endDate -> diff( $curDate );
        while( $auxInterval -> days > 0 ){
            $dateStr = $curDate -> format( 'Y-m-d' );
            // Si no es domingo y no es dia libre, pero es dia de clases...
            if( $day != 0 && ( array_search( $dateStr, $freeDays, TRUE ) === FALSE ) &&
                ( array_search( $day, $classes ) !== FALSE ) ){
                $addDate = new DateTime( $dateStr, new DateTimeZone( "America/Mexico_City" ) );
                $classDays[] = $addDate;
            }
            $day = ( $day + 1 ) % 7;
            $curDate -> add( $interval );
            $auxInterval = $endDate -> diff( $curDate );
        }
        
        $result = $this -> model -> registerClassDays( $classDays, $studentClassId );
        if( $result < count( $classDays ) ){
            echo "Error";
        } 
    }
    
    private function createEvalElems( $teacherClassId, $studentClassId ){
        $evalPages = $this -> model -> getEvalPages( $teacherClassId );
        foreach( $evalPages as $eval ){
            $times = $eval['nElems'];
            $evalPageId = $eval['idHojaEvaluacion'];
            $result = $this -> model -> createEvalElem( $studentClassId, $evalPageId, $times );
            if( $result < $times ){
                echo "Error";
            }
        }
    }
    
    private function edit(){
        if( empty( $_POST ) ){
        }
        else{
            $code = $_POST['edit-student-code'];
            $mail = $_POST['edit-student-email'];
            $phone = $_POST['edit-student-phone'];
            $url = $_POST['edit-student-url'];
            $github = $_POST['edit-student-github'];
            
            $result = $this -> model -> update( $code, $mail, $phone, $url, $github );
            if( $result === TRUE ){
                $this -> updateViewEditStudent( $code );
            }
            else{
                echo "Error";
            }
        }
    }
    
    private function showAll(){
        $view = file_get_contents( 'View/Admin/adminVerAlumnos.html' );
        
        $start = strrpos( $view, '<tr>' );
        $end = strrpos( $view, '</tr>' ) + 5;
        $tableRow = substr( $view, $start, $end - $start );
        
        $students = $this -> model -> getAll();
        $rows = '';
        $count = 0;
        if( isset( $students ) ){
            foreach( $students as $row ){
                $newTableRow = $tableRow;
                $name = $row['nombre'] . ' ' . $row['apellidoP'] . ' ' . $row['apellidoM'];
                $dict = array( '*name*' => $name, '*code*' => $row['codigo'], '*major*' => $row['nombreCarrera'], '*mail*' => $row['email'],
                               '*count*' => $count );
                $newTableRow = strtr( $newTableRow, $dict );
                $rows .= $newTableRow;
                $count += 1;
            }
        }
            
        $view = str_replace( $tableRow, $rows, $view );
        
        echo $view;
    }
    
    private function show(){
        $code = $_POST['code'];
        
        $this -> updateViewEditStudent( $code );
    }
    
    private function listStudents(){
        $view = file_get_contents( 'View/Profesores/listaAlumnos.html' );
        
        echo $view;
    }
    
    /*THIS FUNCTION IS INCOMPLETE*/
    private function loadStudents(){
        if( empty( $_POST ) ){
            require_once( 'View/Profesores/cargarAlumnos.html' );
        }
        else{
            $classInfo = $_POST['load-student-class'];
            $_SESSION['temp-class-info'] = $classInfo;
            
            $classArray = explode( '-', $classInfo );
            $classKey = $classArray[0];
            $classSec = $classArray[1];
            $cycleStr = $classArray[2];
            
            $classRow = $this -> model -> getClass( $classKey );
            $classId = $classRow['idCurso'];
            
            $cycleRow = $this -> model -> getCycle( $cycleStr );
            $cycleId = $cycleRow['idCiclo'];
            
            $teacherId = $_SESSION['user_id'];
        
            $filename = $_FILES['upload-file']['tmp_name'];
            $file = file( $filename );
            $students = array();
            $lineCount = 0;
            foreach( $file as $line ){
                if( $lineCount > 0 ){
                    $student = explode( ',', $line );
                    $students[] = $student;
                }
                $lineCount += 1;
            }
            
            $this -> showStudentsToLoad( $students );
        }
    }
    
    private function showStudentsToLoad( $loadedStudents ){
        $view = file_get_contents( 'View/Profesores/cargarAlumnos2.html' );
        
        $start = strrpos( $view, '<tr>' );
        $end = strrpos( $view, '</tr>' ) + 5;
        $tableRow = substr( $view, $start, $end - $start );
        
        $rows = '';
        $count = 0;
        foreach( $loadedStudents as $student ){
            $newTableRow = $tableRow;
            $name = $student[1] . ' ' . $student[2] . ' ' . $student[3];
            $major = $student[5];
            $majorRow = $this -> model -> getMajorStr( $major );
            $majorStr = $majorRow['nombreCarrera'];
            $dict = array( '*name*' => $name, '*code*' => $student[0], '*major*' => $majorStr, '*mail*' => $student[4],
                           '*count*' => $count );
            $newTableRow = strtr( $newTableRow, $dict );
            $rows .= $newTableRow;
            $count += 1;
        }
        
        $view = str_replace( $tableRow, $rows, $view );
        $_SESSION['temp-students'] = $loadedStudents;
        
        echo $view;
    }
}

?>

function checkRegStudent(){
    var form = document.regStudent;
    var boolArray = [];
    var ok;
    
    boolArray[0] = checkCode( form, document.getElementById( "reg-student-code" ) );
    boolArray[1] = checkName( form, document.getElementById( "reg-student-name" ) );
    boolArray[2] = checkLastName( form, document.getElementById( "reg-student-last1" ) );
    boolArray[3] = checkLastName( form, document.getElementById( "reg-student-last2" ) );
    boolArray[4] = checkMail( form, document.getElementById( "reg-student-email" ) );
    boolArray[5] = checkMajor( form, document.getElementById( "reg-student-major" ) );
    if( document.getElementById( "checkbox-tel" ).checked ){
        boolArray[6] = checkCellPhone( form, document.getElementById( "reg-student-phone" ) );
    }
    else{
        boolArray[6] = true;
    }
    if( document.getElementById( "checkbox-url" ).checked ){
        boolArray[7] = checkUrl( form, document.getElementById( "reg-student-url" ) );
    }
    else{
        boolArray[7] = true;
    }
    if( document.getElementById( "checkbox-github" ).checked ){
        boolArray[8] = checkUrl( form, document.getElementById( "reg-student-github" ) );
    }
    else{
        boolArray[8] = true;
    }
    boolArray[9] = checkClass( form, document.getElementById( "reg-student-class" ) );
    
    ok = true;
    for( var i = 0; i < boolArray.length && ok; i++ ){
        if( !boolArray[i] ){
            ok = false;
        }
    }
    
    if( ok ){
        form.submit();
    }
}

function checkRegStudent2(){
    var form = document.adminNewStudent;
    var boolArray = [];
    var ok;
    
    boolArray[0] = checkCode( form, document.getElementById( "reg-student-code" ) );
    boolArray[1] = checkName( form, document.getElementById( "reg-student-name" ) );
    boolArray[2] = checkLastName( form, document.getElementById( "reg-student-last1" ) );
    boolArray[3] = checkLastName( form, document.getElementById( "reg-student-last2" ) );
    boolArray[4] = checkMail( form, document.getElementById( "reg-student-email" ) );
    boolArray[5] = checkMajor( form, document.getElementById( "reg-student-major" ) );
    if( document.getElementById( "checkbox-tel" ).checked ){
        boolArray[6] = checkCellPhone( form, document.getElementById( "reg-student-phone" ) );
    }
    else{
        boolArray[6] = true;
    }
    if( document.getElementById( "checkbox-url" ).checked ){
        boolArray[7] = checkUrl( form, document.getElementById( "reg-student-url" ) );
    }
    else{
        boolArray[7] = true;
    }
    if( document.getElementById( "checkbox-github" ).checked ){
        boolArray[8] = checkUrl( form, document.getElementById( "reg-student-github" ) );
    }
    else{
        boolArray[8] = true;
    }
    
    ok = true;
    for( var i = 0; i < boolArray.length && ok; i++ ){
        if( !boolArray[i] ){
            ok = false;
        }
    }
    
    if( ok ){
        form.submit();
    }
}

function checkStudent(){
    var form = document.editStudent;
    var boolArray = [];
    var ok;
    var cell = document.getElementById( "edit-student-phone" );
    var url = document.getElementById( "edit-student-url" );
    var github = document.getElementById( "edit-student-github" );
    
    boolArray[0] = checkMail( form, document.getElementById( "edit-student-email" ) );
    if( cell.value != "" ){
        boolArray[1] = checkCellPhone( form, cell );
    }
    else{
        boolArray[1] = true;
        cell.parentNode.replaceChild( document.createTextNode( "" ), cell.parentNode.lastChild );
    }
    if( url.value != "" ){
        boolArray[2] = checkUrl( form, url );
    }
    else{
        boolArray[2] = true;
        url.parentNode.replaceChild( document.createTextNode( "" ), url.parentNode.lastChild );
    }
    if( github.value != "" ){
        boolArray[3] = checkUrl( form, github );
    }
    else{
        boolArray[3] = true;
        github.parentNode.replaceChild( document.createTextNode( "" ), github.parentNode.lastChild );
    }
    boolArray[4] = checkPass( form, document.getElementById( "edit-student-pass" ) );
    
    ok = true;
    for( var i = 0; i < boolArray.length; i++ ){
        if( !boolArray[i] ){
            ok = false;
        }
    }
    
    if( ok ){
        form.submit();
    }
}

function checkLoadFile(){
    var fileElem = document.getElementById( "upload-file" );
    var submitButton = document.getElementById( "submit-load-students" );
    var str = fileElem.value;
    var message;
    var ok = true;
    
    if( !str.endsWith( ".csv" ) ){
        message = document.createTextNode( " Elija un archivo .csv." );
        fileElem.parentNode.replaceChild( message, fileElem.parentNode.lastChild );
        ok = false;
    }
    else{
        message = document.createTextNode( "" );
        fileElem.parentNode.replaceChild( message, fileElem.parentNode.lastChild );
    }
    
    if( ok ){
        submitButton.disabled = false;
        //loadCSV;
    }
}

function checkCloneClass(){
    var form = document.cloneClassCourse;
    var boolArray = [];
    var ok;
    
    boolArray[0] = checkNrc( form, document.getElementById( "new-class-nrc" ) );
    boolArray[1] = checkYear( form, document.getElementById( "new-class-year" ) );
    boolArray[2] = checkHalfYear( form, document.getElementById( "new-class-half" ) );
    
    ok = true;
    for( var i = 0; i < boolArray.length && ok; i++ ){
        if( !boolArray[i] ){
            ok = false;
        }
    }
    
    if( ok ){
        form.submit();
    }
}

function checkNewClass(){
    var form = document.newClass;
    var boolArray = [];
    var ok;
    
    boolArray[0] = checkName( form, document.getElementById( "new-class-name" ) );
    boolArray[1] = checkNrc( form, document.getElementById( "new-class-nrc" ) );
    boolArray[2] = checkAcademy( form, document.getElementById( "new-class-academy" ) );
    boolArray[3] = checkSchedules( form );
    boolArray[4] = checkEvals( form );
    
    ok = true;
    for( var i = 0; i < boolArray.length && ok; i++ ){
        if( !boolArray[i] ){
            ok = false;
        }
    }
    
    if( ok ){
        form.submit();
    }
}

function checkFreeDays(){
    var form = document.freeDays;
    var boolArray = [];
    var freeDays;
    var ok;
    
    //2013-10-09
    //AAAA-MM-DD
    freeDays = document.getElementsByTagName( "input" );
    for( var i = 0; i < freeDays.length; i++ ){
        boolArray[i] = checkDate( form, freeDays[i] );
    }
    ok = true;
    for( var i = 0; i < freeDays.length; i++ ){
        if( !boolArray[i] ){
            ok = false;
        }
    }
    if( ok ){
        form.submit();
    }
}

function checkNewTeacher(){
    var form = document.newTeacher;
    var boolArray = [];
    var ok;
    
    boolArray[0] = checkCode( form, document.getElementById( "reg-teacher-code" ) );
    boolArray[1] = checkName( form, document.getElementById( "reg-teacher-name" ) );
    boolArray[2] = checkLastName( form, document.getElementById( "reg-teacher-last1" ) );
    boolArray[3] = checkLastName( form, document.getElementById( "reg-teacher-last2" ) );
    boolArray[4] = checkLastName( form, document.getElementById( "reg-teacher-pass" ) );
    boolArray[5] = checkMail( form, document.getElementById( "reg-teacher-email" ) );
    boolArray[6] = checkCellPhone( form, document.getElementById( "reg-teacher-phone" ) );
    
    ok = true;
    for( var i = 0; i < boolArray.length; i++ ){
        if( !boolArray[i] ){
            ok = false;
        }
    }
    
    if( ok ){
        form.submit();
    }
}

function checkTeacherData(){
    var form = document.teacherData;
    var boolArray = [];
    var ok;
    
    boolArray[0] = checkMail( form, document.getElementById( "teacher-email" ) );
    boolArray[1] = checkCellPhone( form, document.getElementById( "teacher-phone" ) );
    
    ok = true;
    for( var i = 0; i < boolArray.length && ok; i++ ){
        if( !boolArray[i] ){
            ok = false;
        }
    }
    
    if( ok ){
        form.submit();
    }
}

function checkEditTeacher(){
    var form = document.editTeacher;
    var boolArray = [];
    var ok;
    
    boolArray[0] = checkMail( form, document.getElementById( "edit-teacher-email" ) );
    boolArray[1] = checkCellPhone( form, document.getElementById( "edit-teacher-phone" ) );
    boolArray[2] = checkPass( form, document.getElementById( "edit-teacher-pass" ) );
    
    ok = true;
    for( var i = 0; i < boolArray.length && ok; i++ ){
        if( !boolArray[i] ){
            ok = false;
        }
    }
    
    if( ok ){
        form.submit();
    }
}

function checkStudentData(){
    var form = document.studentData;
    var boolArray = [];
    var ok;
    var cell = document.getElementById( "student-phone" );
    var url = document.getElementById( "student-url" );
    var github = document.getElementById( "student-github" );
    
    boolArray[0] = checkMail( form, document.getElementById( "student-email" ) );
    if( cell.value != "" ){
        boolArray[1] = checkCellPhone( form, cell );
    }
    else{
        boolArray[1] = true;
        cell.parentNode.replaceChild( document.createTextNode( "" ), cell.parentNode.lastChild );
    }
    if( url.value != "" ){
        boolArray[2] = checkUrl( form, url );
    }
    else{
        boolArray[2] = true;
        url.parentNode.replaceChild( document.createTextNode( "" ), url.parentNode.lastChild );
    }
    if( github.value != "" ){
        boolArray[3] = checkUrl( form, github );
    }
    else{
        boolArray[3] = true;
        github.parentNode.replaceChild( document.createTextNode( "" ), github.parentNode.lastChild );
    }
    
    ok = true;
    for( var i = 0; i < boolArray.length; i++ ){
        if( !boolArray[i] ){
            ok = false;
        }
    }
    
    if( ok ){
        form.submit();
    }
}

function checkTeacherPass(){
    var form = document.teacherPassCh;
    var teacherPass = document.getElementById( "teacher-old-pass" );
    var newPass = document.getElementById( "teacher-new-pass" );
    var newPass2 = document.getElementById( "teacher-new-pass2" );
    var boolArray = [];
    var ok;
    
    boolArray[0] = checkPass( form, teacherPass );
    boolArray[1] = checkPass( form, newPass );
    boolArray[2] = checkPass( form, newPass2 );
    
    if( boolArray[0] ){
        boolArray[3] = checkTeacherPassword( form, teacherPass );
    }
    else{
        boolArray[3] = false;
    }
    
    if( boolArray[1] && boolArray[2] ){
        boolArray[4] = checkNewPass( form, newPass, newPass2 );
    }
    else{
        boolArray[4] = false;
    }
    
    ok = true;
    for( var i = 0; i < boolArray.length; i++ ){
        if( !boolArray[i] ){
            ok = false;
        }
    }
    
    if( ok ){
        form.submit();
    }
}

function checkStudentPass(){
    var form = document.studentPassCh;
    var studentPass = document.getElementById( "student-old-pass" );
    var newPass1 = document.getElementById( "student-new-pass" );
    var newPass2 = document.getElementById( "student-new-pass2" );
    var boolArray = [];
    var ok;
    
    boolArray[0] = checkPass( form, studentPass );
    boolArray[1] = checkPass( form, newPass1 );
    boolArray[2] = checkPass( form, newPass2 );
    
    if( boolArray[0] ){
        boolArray[3] = checkStudentPassword( form, studentPass );
    }
    else{
        boolArray[3] = false;
    }
    
    if( boolArray[1] && boolArray[2] ){
        boolArray[4] = checkNewPass( form, newPass1, newPass2 );
    }
    else{
        boolArray[4] = false;
    }
    
    ok = true;
    for( var i = 0; i < boolArray.length; i++ ){
        if( !boolArray[i] ){
            ok = false;
        }
    }
    
    if( ok ){
        form.submit();
    }
}

function checkEvalParams(){
    var form = document.evalStudent;
    var boolArray = [];
    var ok;
    
    boolArray[0] = checkClass( form, document.getElementById( "select-class" ) );
    boolArray[1] = checkSelected( form, document.getElementsByTagName( "input" ) );
    boolArray[2] = checkEvalParam( form, document.getElementById( "select-eval" ) );
    boolArray[3] = checkEvalElem( form, document.getElementById( "select-sub-eval" ) );
    boolArray[4] = checkGrade( form, document.getElementById( "grade" ) );
    
    for( var i = 0; i < boolArray.length && ok; i++ ){
        if( !boolArray[i] ){
            ok = false;
        }
    }
    
    if( ok ){
        form.submit();
    }
}

function checkNewCycle(){
    var form = document.newCycle;
    var boolArray = [];
    var freeDays = [];
    var elems = [];
    var ok;
    
    boolArray[0] = checkYear( form, document.getElementById( "new-cycle-year" ) );
    boolArray[1] = checkHalfYear( form, document.getElementById( "new-cycle-half" ) );
    boolArray[2] = checkDate( form, document.getElementById( "new-cycle-start" ) );
    boolArray[3] = checkDate( form, document.getElementById( "new-cycle-end" ) );
    
    elems = document.getElementsByTagName( "input" );
    for( var i = 0; i < elems.length; i++ ){
        if( elems[i].name == "free-day" ){
            freeDays.push( elems[i] );
        }
    }
    for( var i = 0; i < freeDays.length; i++ ){
        boolArray.push( checkDate( form, freeDays[i] ) );
    }
    
    ok = true;
    for( var i = 0; i < boolArray.length; i++ ){
        if( !boolArray[i] ){
            ok = false;
        }
    }
    
    if( ok ){
        form.submit();
    }
}

function checkLogin(){
    var form = document.loginForm;
    var ok = false;
    var code = document.getElementById( "login-code" ).value;
    var pass = document.getElementById( "login-pass" ).value;
    var div = document.createElement( "div" );
    
    //ok = checkPair( code, pass );
    if( !ok ){
        div.appendChild( document.createTextNode( "Codigo y contraseña incorrectos." ) );
        form.replaceChild( div, form.lastChild );
    }
    else{
        div.appendChild( document.createTextNode( "" ) );
        form.replaceChild( div, form.lastChild );
        form.submit();
    }
}

function checkCode( form, code ){
    var ok = true;
    var regex = /^\w+$/gi;
    var str = code.value;
    var message;
    
    if( !regex.test( str ) ){
        message = document.createTextNode( " Escriba su codigo" );                  
        code.parentNode.replaceChild( message, code.parentNode.lastChild );
        ok = false;
    }
    else{
        message = document.createTextNode( "" );                  
        code.parentNode.replaceChild( message, code.parentNode.lastChild );
    }
    
    return ok;
}

function checkName( form, name ){
    var ok = true;
    var regex = /^([\w\u00d1\u00f1\u00c1\u00c9\u00cd\u00d3\u00da\u00e1\u00e9\u00ed\u00f3\u00fa]+\s*)+$/gi;
    var str = name.value;
    var message;
    
    if( !regex.test( str ) ){
        message = document.createTextNode( " Formato de nombre incorrecto." );                  
        name.parentNode.replaceChild( message, name.parentNode.lastChild );
        ok = false;
    }
    else{
        message = document.createTextNode( "" );                  
        name.parentNode.replaceChild( message, name.parentNode.lastChild );
    }
    
    return ok;
}

function checkLastName( form, lastName ){
    var ok = true;
    var regex = /^([\w\u00d1\u00f1\u00c1\u00c9\u00cd\u00d3\u00da\u00e1\u00e9\u00ed\u00f3\u00fa]+\s*)+$/gi
    var str = lastName.value;
    var message;
    
    if( !regex.test( str ) ){
        message = document.createTextNode( " Formato de apellido incorrecto." );
        lastName.parentNode.replaceChild( message, lastName.parentNode.lastChild );
        ok = false;
    }
    else{
        message = document.createTextNode( "" );
        lastName.parentNode.replaceChild( message, lastName.parentNode.lastChild );
    }
    
    return ok;
}

function checkMail( form, mail ){
    var ok = true;
    var regex = /^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/gi
    var str = mail.value;
    var message;
    
    if( !regex.test( str ) ){
        message = document.createTextNode( " Formato de correo incorrecto." );
        mail.parentNode.replaceChild( message, mail.parentNode.lastChild );
        ok = false;
    }
    else{
        message = document.createTextNode( "" );
        mail.parentNode.replaceChild( message, mail.parentNode.lastChild );
    }
    
    return ok;
}

function checkMajor( form, major ){
    var ok = true;
    var index = major.selectedIndex;
    var message;
    
    if( index == 0 ){
        message = document.createTextNode( " Seleccione una carrera." );
        major.parentNode.replaceChild( message, major.parentNode.lastChild );
        ok = false;
    }
    else{
        message = document.createTextNode( "" );
        major.parentNode.replaceChild( message, major.parentNode.lastChild );
    }
    
    return ok;
}

function checkClass( form, classCourse ){
    var ok = true;
    var index = classCourse.selectedIndex;
    var message;
    
    if( index == 0 ){
        message = document.createTextNode( " Seleccion un curso." );
        classCourse.parentNode.replaceChild( message, classCourse.parentNode.lastChild );
        ok = false;
    }
    else{
        message = document.createTextNode( "" );
        classCourse.parentNode.replaceChild( message, classCourse.parentNode.lastChild );
    }
    
    return ok;
}

function checkCellPhone( form, cellphone ){ 
    var ok = true;
    var regex = /^\d{10}$/gi
    var str = cellphone.value;
    var message;
    
    if( !regex.test( str ) ){
        message = document.createTextNode( " Formato de celular incorrecto." );                  
        cellphone.parentNode.replaceChild( message, cellphone.parentNode.lastChild );
        ok = false;
    }
    else{
        message = document.createTextNode( "" );                  
        cellphone.parentNode.replaceChild( message, cellphone.parentNode.lastChild );
    }
    
    return ok;
}

function checkUrl( form, url ){
    var ok = true;
    var regex = /^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/gi;
    var str = url.value;
    var message;
    
    if( !regex.test( str ) ){
        message = document.createTextNode( " Formato de url incorrecto." );
        url.parentNode.replaceChild( message, url.parentNode.lastChild );
        ok = false;
    }
    else{
        message = document.createTextNode( "" );
        url.parentNode.replaceChild( message, url.parentNode.lastChild );
    }
    
    return ok;
}

function checkNrc( form, nrc ){
    var ok = true;
    var regex = /^\d{5}$/gi;
    var str = nrc.value;
    var message;
    
    if( !regex.test( str ) ){
        message = document.createTextNode( " Formato de NRC incorrecto." );
        nrc.parentNode.replaceChild( message, nrc.parentNode.lastChild );
        ok = false;
    }
    else{
        message = document.createTextNode( "" );
        nrc.parentNode.replaceChild( message, nrc.parentNode.lastChild );
    }
    
    return ok;
}

function checkYear( form, year ){
    var ok = true;
    var regex = /^\d+$/gi;
    var str = year.value;
    var message;
    
    if( !regex.test( str ) ){
        message = document.createTextNode( " Formato de año incorrecto." );
        year.parentNode.replaceChild( message, year.parentNode.lastChild );
        ok = false;
    }
    else{
        message = document.createTextNode( "" );
        year.parentNode.replaceChild( message, year.parentNode.lastChild );
    }
    
    return ok;
}

function checkHalfYear( form, half ){
    var ok = true;
    var index = half.selectedIndex;
    var message;
    
    if( index == 0 ){
        message = document.createTextNode( " Seleccione un calendario." );
        half.parentNode.replaceChild( message, half.parentNode.lastChild );
        ok = false;
    }
    else{
        message = document.createTextNode( "" );
        half.parentNode.replaceChild( message, half.parentNode.lastChild );
    }
    
    return ok;
}

function checkAcademy( form, academy ){
    var ok = true;
    var index = academy.selectedIndex;
    var message;
    
    if( index == 0 ){
        message = document.createTextNode( " Seleccione una academia." );
        academy.parentNode.replaceChild( message, academy.parentNode.lastChild );
        ok = false;
    }
    else{
        message = document.createTextNode( "" );
        academy.parentNode.replaceChild( message, academy.parentNode.lastChild );
    }
    
    return ok;
}

function checkDay( form, day ){
    var ok = true;
    var index = day.selectedIndex;
    var message;
    
    if( index == 0 ){
        message = document.createTextNode( " Seleccione un dia." );
        day.parentNode.replaceChild( message, day.nextSibling );
        ok = false;
    }
    else{
        message = document.createTextNode( "" );
        day.parentNode.replaceChild( message, day.nextSibling );
    }
    
    return ok;
}

function checkStartTime( form, start ){
    var ok = true;
    var index = start.selectedIndex;
    var message;
    
    if( index == 0 ){
        message = document.createTextNode( " Seleccione un horario." );
        start.parentNode.replaceChild( message, start.nextSibling );
        ok = false;
    }
    else{
        message = document.createTextNode( "" );
        start.parentNode.replaceChild( message, start.nextSibling );
    }
    
    return ok;
}

function checkDuration( form, duration ){
    var ok = true;
    var regex = /^\d+$/gi
    var str = duration.value;
    var message;
    var val;
    
    if( !regex.test( str ) ){
        message = document.createTextNode( " Formato de duracion incorrecto." );
        duration.parentNode.replaceChild( message, duration.parentNode.lastChild );
        ok = false;
    }
    else{
        val = parseInt( str );
        if( val < 1 || val > 4 ){
            message = document.createTextNode( " Duracion debe estar entre 1 y 4." );
            duration.parentNode.replaceChild( message, duration.parentNode.lastChild );
            ok = false;
        }
        else{
            message = document.createTextNode( "" );
            duration.parentNode.replaceChild( message, duration.parentNode.lastChild );
        }
    }
    
    return ok;
}

function checkEvalParam( form, evalParam ){
    var ok = true;
    var index = evalParam.selectedIndex;
    var message;
    
    if( index == 0 ){
        message = document.createTextNode( " Seleccione un rubro de evaluacion." );
        evalParam.parentNode.replaceChild( message, evalParam.parentNode.lastChild );
        ok = false;
    }
    else{
        message = document.createTextNode( "" );
        evalParam.parentNode.replaceChild( message, evalParam.parentNode.lastChild );
    }
    
    return ok;
}

function checkEvalElem( form, evalNo ){
    var ok = true;
    var regex = /^\d+$/gi;
    var message;
    var str = evalNo.value;
    
    if( !regex.test( str ) ){
        message = document.createTextNode( " Escriba un numero." );
        evalNo.parentNode.replaceChild( message, evalNo.parentNode.lastChild );
        ok = false;
    }
    else{
        message = document.createTextNode( "" );
        evalNo.parentNode.replaceChild( message, evalNo.parentNode.lastChild );
    }
    
    return ok;
}

function checkActivity( form, act ){
    var ok = true;
    var regex = /^.+$/gi;
    var message;
    var str = act.value;
    
    if( !regex.test( str ) ){
        message = document.createTextNode( " Formato de descripcion incorrecto." );
        act.parentNode.replaceChild( message, act.nextSibling );
        ok = false;
    }
    else{
        message = document.createTextNode( "" );
        act.parentNode.replaceChild( message, act.nextSibling );
    }
    
    return ok;
}

function checkValue( form, val ){
    var ok = true;
    var regex = /^\d+$/gi;
    var message;
    var str = val.value;
    var num;
    
    if( !regex.test( str ) ){
        message = document.createTextNode( " Valor de evaluacion incorrecto." );
        val.parentNode.replaceChild( message, val.nextSibling );
        ok = false;
    }
    else{
        num = parseInt( str );
        if( num <= 0 || num > 100 ){
            message = document.createTextNode( " Valor de evaluacion entre 1 y 100." );
            val.parentNode.replaceChild( message, val.nextSibling );
            ok = false;
        }
        else{
            message = document.createTextNode( "" );
            val.parentNode.replaceChild( message, val.nextSibling );
        }
    }
    
    return ok;
}

function checkColumns( form, columns ){
    var ok = true;
    var regex = /^\d+$/gi;
    var message;
    var str = columns.value;
    
    if( !regex.test( str ) ){
        message = document.createTextNode( " Formato de columnas incorrecto." );
        columns.parentNode.replaceChild( message, columns.parentNode.lastChild.previousSibling.previousSibling );
        ok = false;
    }
    else{
        message = document.createTextNode( "" );
        columns.parentNode.replaceChild( message, columns.parentNode.lastChild.previousSibling.previousSibling );
    }
    
    return ok;
}

function checkGrade( form, grade ){
    var ok = true;
    var regex = /^(\d{1,2}|\d{1,2}[.]\d|NP|SD)$/gi;
    var message;
    var str = grade.value;
    var nums;
    
    if( !regex.test( str ) ){
        message = document.createTextNode( " Formato de calificacion incorrecto." );
        grade.parentNode.replaceChild( message, grade.parentNode.lastChild );
        ok = false;
    }
    else{
        if( str != "SD" && str != "NP" ){
            if( str > 10.0 ){
                message = document.createTextNode( " Rango valido [0.0-10.0]." );
                grade.parentNode.replaceChild( message, grade.parentNode.lastChild );
                ok = false;
            }
            else{
                message = document.createTextNode( "" );
                grade.parentNode.replaceChild( message, grade.parentNode.lastChild );
            }
        }
        else{
            message = document.createTextNode( "" );
            grade.parentNode.replaceChild( message, grade.parentNode.lastChild );
        }
    }
    
    return ok;
}

function checkDate( form, date ){
    var ok;
    var regex = /^\d{1,4}-[01]\d-[0-3]\d$/gi;
    var message;
    var str = date.value;
    var dateElems;
    var validDays = [0, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
    
    if( regex.test( str ) ){
        dateElems = str.split( "-" );
        if( dateElems[1] > 0 && dateElems[1] <= 12 ){
            if( dateElems[2] > 0 && dateElems[2] <= validDays[ parseInt( dateElems[1] ) ] ){
                ok = true;
            }
            else if( ( dateElems[0] % 4 == 0 ) && ( dateElems[0] % 100 != 0 ) || ( dateElems[0] % 400 == 0 ) ){
                if( dateElems[1] == 2 && dateElems[2] > 0 && dateElems[2] <= 29 ){
                        ok = true;
                }
                else{
                    ok = false;
                }
            }
            else{
                ok = false;
            }
        }
        else{
            ok = false;
        }
    }
    else{
        ok = false;
    }
    
    if( ok ){
        message = document.createTextNode( "" );
        date.parentNode.replaceChild( message, date.parentNode.lastChild );
    }
    else{
        message = document.createTextNode( " Formato de fecha es AAAA-MM-DD." );
        date.parentNode.replaceChild( message, date.parentNode.lastChild );
    }
    
    return ok;
}

function checkSchedules( form ){
    var ok;
    var inputs = document.getElementsByTagName( "input" );
    var selects = document.getElementsByTagName( "select" );
    var days = [];
    var starts = [];
    var durations = [];
    var boolArray = [];
    
    for( var i = 0; i < selects.length; i++ ){
        if( selects[i].name.substring( 0, 13 ) == "new-class-day" ){
            days.push( selects[i] );
        }
    }
    for( var i = 0; i < selects.length; i++ ){
        if( selects[i].name.substring( 0, 15 ) == "new-class-start" ){
            starts.push( selects[i] );
        }
    }
    for( var i = 0; i < inputs.length; i++ ){
        if( inputs[i].name.substring( 0, 18 ) == "new-class-duration" ){
            durations.push( inputs[i] );
        }
    }
    
    boolArray[0] = true;
    for( var i = 0; i < days.length; i++ ){
        boolArray[0] = checkDay( form, days[i] ) && boolArray[0];
    }
    
    boolArray[1] = true;
    for( var i = 0; i < starts.length; i++ ){
        boolArray[1] = checkStartTime( form, starts[i] ) && boolArray[1];
    }
    
    boolArray[2] = true;
    for( var i = 0; i < durations.length; i++ ){
        boolArray[2] = checkDuration( form, durations[i] ) && boolArray[2];
    }
    
    ok = true;
    for( var i = 0; i < boolArray.length && ok; i++ ){
        if( !boolArray[i] ){
            ok = false;
        }
    }
    
    return ok;
}

function checkEvals( form ){
    var ok;
    var inputs = document.getElementsByTagName( "input" );
    var activities = [];
    var values = [];
    var columns = [];
    var boolArray = [];
    
    for( var i = 0; i < inputs.length; i++ ){
        if( inputs[i].name.substring( 0, 13 ) == "new-class-act" ){
            activities.push( inputs[i] );
        }
    }
    for( var i = 0; i < inputs.length; i++ ){
        if( inputs[i].name.substring( 0, 13 ) == "new-class-val" ){
            values.push( inputs[i] );
        }
    }
    for( var i = 0; i < inputs.length; i++ ){
        if( inputs[i].name.substring( 0, 12 ) == "page-columns" ){
            columns.push( inputs[i] );
        }
    }
    
    boolArray[0] = true;
    for( var i = 0; i < activities.length; i++ ){
        boolArray[0] = checkActivity( form, activities[i] ) && boolArray[0];
    }
    
    boolArray[1] = true;
    for( var i = 0; i < values.length; i++ ){
        boolArray[1] = checkValue( form, values[i] ) && boolArray[1];
    }
    boolArray[2] = true;
    for( var i = 0; i < columns.length; i++ ){
        boolArray[2] = checkColumns( form, columns[i] ) && boolArray[2];
    }
    
    ok = true;
    for( var i = 0; i < boolArray.length && ok; i++ ){
        if( !boolArray[i] ){
            ok = false;
        }
    }
    
    
    return ok;
}

function checkSelected( form, inputArray ){
    var ok = false;
    var message;
    var i;
    var table = document.getElementById( "evalTable" );
    
    for( i = 0; i < inputArray.length && !ok; i++ ){
        if( inputArray[i].type == "radio" ){
            if( inputArray[i].checked ){
                ok = true;
            }
        }
    }
    if( !ok ){
        message = document.createTextNode( "Seleccione un alumno." );
        table.replaceChild( message, table.lastChild );
    }
    else{
        message = document.createTextNode( "" );
        table.replaceChild( message, table.lastChild );
    }
    
    return ok;
}

function checkSelected2(){
    var ok = false;
    var message;
    var table = document.getElementById( "student-table" );
    var form = document.allStudents;
    var inputArray = document.getElementsByTagName( "input" );
    
    for( var i = 0; i < inputArray.length && !ok; i++ ){
        if( inputArray[i].type == "radio" ){
            if( inputArray[i].checked ){
                ok = true;
            }
        }
    }
    if( !ok ){
        message = document.createTextNode( "Seleccione un alumno." );
        table.replaceChild( message, table.lastChild );
    }
    else{
        message = document.createTextNode( "" );
        table.replaceChild( message, table.lastChild );
        form.submit();
    }
}

function checkSelected3(){
    var ok = false;
    var message;
    var table = document.getElementById( "cycle-table-div" );
    var form = document.allCycles;
    var inputArray = document.getElementsByTagName( "input" );
    
    for( var i = 0; i < inputArray.length && !ok; i++ ){
        if( inputArray[i].type == "radio" ){
            if( inputArray[i].checked ){
                ok = true;
            }
        }
    }
    if( !ok ){
        message = document.createTextNode( "Seleccione un ciclo." );
        table.replaceChild( message, table.lastChild );
    }
    else{
        message = document.createTextNode( "" );
        table.replaceChild( message, table.lastChild );
        form.submit();
    }
}

function checkSelected4(){
    var ok = false;
    var message;
    var table = document.getElementById( "teacher-table-div" );
    var form = document.allTeachers;
    var inputArray = document.getElementsByTagName( "input" );
    
    for( var i = 0; i < inputArray.length && !ok; i++ ){
        if( inputArray[i].type == "radio" ){
            if( inputArray[i].checked ){
                ok = true;
            }
        }
    }
    if( !ok ){
        message = document.createTextNode( "Seleccione un profesor." );
        table.replaceChild( message, table.lastChild );
    }
    else{
        message = document.createTextNode( "" );
        table.replaceChild( message, table.lastChild );
        form.submit();
    }
}

function checkPass( form, pass ){
    var ok = true;
    var regex = /^.+$/gi;
    var message;
    var str = pass.value;
    
    if( !regex.test( str ) ){
        message = document.createTextNode( " Campo vacio." );
        pass.parentNode.replaceChild( message, pass.parentNode.lastChild );
        ok = false;
    }
    else{
        message = document.createTextNode( "" );
        pass.parentNode.replaceChild( message, pass.parentNode.lastChild );
    }
    
    return ok;
}

function checkTeacherPassword( form, teacherPass ){
    var ok = true;
    
    //Check password here... somehow.
    
    return ok;
}

function checkStudentPassword( form, studentPass ){
    var ok = true;
    
    //Check password here... queries...
    
    return ok;
}

function checkNewPass( form, newPass1, newPass2 ){
    var ok = true;
    var str1 = newPass1.value;
    var str2 = newPass2.value;
    var message;
    
    if( str1 != str2 ){
        message = document.createTextNode( " Las contraseñas no coinciden." );
        newPass2.parentNode.replaceChild( message, newPass2.parentNode.lastChild );
        ok = false;
    }
    else{
        message = document.createTextNode( "" );
        newPass2.parentNode.replaceChild( message, newPass2.parentNode.lastChild );
    }
    
    return ok;
}

function addTime(){
    var div = document.createElement( "div" );
    var dayLabel = document.createElement( "label" );
    var daySelect = document.createElement( "select" );
    var timeLabel = document.createElement( "label" );
    var timeSelect = document.createElement( "select" );
    var lenLabel = document.createElement( "label" );
    var lenInput = document.createElement( "input" );
    var otherDiv = document.getElementById( "eval0" );
    var button = document.getElementById( "add-time" );
    
    div.className = "form-div";
    div.id = "schedule" + nSchedules.toString();
    dayLabel.HTMLfor = "new-class-day" + nSchedules.toString();
    dayLabel.innerHTML = document.getElementById( "day-label0" ).innerHTML;
    div.appendChild( dayLabel );
    
    daySelect.name = "new-class-day" + nSchedules.toString();
    daySelect.innerHTML = document.getElementById( "new-class-day0" ).innerHTML;
    div.appendChild( daySelect );
    
    div.appendChild( document.createTextNode( "" ) );
    div.appendChild( document.createElement( "br" ) );
    div.appendChild( document.createElement( "br" ) );
    
    timeLabel.HTMLfor = "new-class-start" + nSchedules.toString();
    timeLabel.innerHTML = document.getElementById( "time-label0" ).innerHTML;
    div.appendChild( timeLabel );
    
    timeSelect.name = "new-class-start" + nSchedules.toString();
    timeSelect.innerHTML = document.getElementById( "new-class-start0" ).innerHTML;
    div.appendChild( timeSelect );
    
    div.appendChild( document.createTextNode( "" ) );
    div.appendChild( document.createElement( "br" ) );
    div.appendChild( document.createElement( "br" ) );
    
    lenLabel.HTMLfor = "new-class-duration" + nSchedules.toString();
    lenLabel.innerHTML = document.getElementById( "len-label0" ).innerHTML;
    div.appendChild( lenLabel );
    
    lenInput.name = "new-class-duration" + nSchedules.toString();
    lenInput.type = "number";
    lenInput.min = "1";
    lenInput.max = "4";
    div.appendChild( lenInput );
    
    div.appendChild( button );
    div.appendChild( document.createTextNode( "" ) );
    
    document.newClass.insertBefore( div, otherDiv );
    document.newClass.insertBefore( document.createElement( "br" ), otherDiv );
    
    nSchedules += 1;
}

function addEval(){
    var div = document.createElement( "div" );
    var actLabel = document.createElement( "label" );
    var actInput = document.createElement( "input" );
    var valLabel = document.createElement( "label" );
    var valInput = document.createElement( "input" );
    var pageLabel = document.createElement( "label" );
    var pageInput = document.createElement( "input" );
    var columnLabel = document.createElement( "label" );
    var columnInput = document.createElement( "input" );
    var otherDiv = document.getElementById( "add-eval-button-div" );
    var button = document.getElementById( "add-eval" );
    
    div.className = "form-div";
    div.id = "eval" + nEvals.toString();
    actLabel.HTMLfor = "new-class-act" + nEvals.toString();
    actLabel.innerHTML = document.getElementById( "class-act-label0" ).innerHTML;
    div.appendChild( actLabel );
    
    actInput.type = "text";
    actInput.name = "new-class-act" + nEvals.toString();
    div.appendChild( actInput );
    
    div.appendChild( document.createTextNode( "" ) );
    div.appendChild( document.createElement( "br" ) );
    div.appendChild( document.createElement( "br" ) );
    
    valLabel.HTMLfor = "new-class-val" + nEvals.toString();
    valLabel.innerHTML = document.getElementById( "class-val-label0" ).innerHTML;
    div.appendChild( valLabel );
    
    valInput.type = "number";
    valInput.name = "new-class-val" + nEvals.toString();
    div.appendChild( valInput );
    
    div.appendChild( document.createTextNode( "" ) );
    div.appendChild( document.createElement( "br" ) );
    div.appendChild( document.createElement( "br" ) );
    
    pageLabel.HTMLfor = "eval-page" + nEvals.toString();
    pageLabel.innerHTML = document.getElementById( "page-label0" ).innerHTML;
    div.appendChild( pageLabel );
    
    pageInput.type = "checkbox";
    pageInput.name = "eval-page" + nEvals.toString();
    div.appendChild( pageInput );
    
    div.appendChild( document.createTextNode( "" ) );
    div.appendChild( document.createElement( "br" ) );
    div.appendChild( document.createElement( "br" ) );
    
    document.newClass.insertBefore( div, otherDiv );
    
    pageInput.onclick = function(){
        if( pageInput.checked ){
            showColumnInput( div );
        }
        else{
            hideColumnInput( div );
        }
    }
    
    nEvals += 1;
}

function addFreeDay(){
    var div = document.createElement( "div" );
    var label = document.createElement( "label" );
    var input = document.createElement( "input" );
    var otherDiv = document.getElementById( "add-button-div" );
    var button = document.getElementById( "free-day-button" );
    
    div.className = "form-div";
    label.HTMLfor = "new-free-day";
    label.innerHTML = document.getElementById( "free-day-label1" ).innerHTML;
    div.appendChild( label );
    
    input.name = "new-free-day";
    input.type = "date";
    input.required = true;
    div.appendChild( input );
    
    div.appendChild( button );
    div.appendChild( document.createTextNode( "" ) );
    document.freeDays.insertBefore( div, otherDiv );
}

function addFreeDay2(){
    var div = document.createElement( "div" );
    var label = document.createElement( "label" );
    var input = document.createElement( "input" );
    var otherDiv = document.getElementById( "submit-div" );
    var button = document.getElementById( "add-free-day" );
    
    div.className = "form-div";
    label.HTMLfor = "free-day";
    label.innerHTML = document.getElementById( "free-day-label1" ).innerHTML;
    div.appendChild( label );
    
    input.name = "free-day";
    input.type = "date";
    div.appendChild( input );
    
    div.appendChild( button );
    div.appendChild( document.createTextNode( "" ) );
    document.newCycle.insertBefore( div, otherDiv );
}

function showStudentInput( checkbox, type, id, name ){
    var input = document.createElement( "input" );
    
    input.type = type;
    input.id = id;
    input.name = name;
    
    checkbox.parentNode.insertBefore( input, checkbox );
}

function hideStudentInput( id ){
    var input = document.getElementById( id );
    var message = document.createTextNode( "" );
    
    input.parentNode.replaceChild( message, input.parentNode.lastChild );
    input.parentNode.removeChild( input );
}

function showColumnInput( div ){
    var label = document.createElement( "label" );
    var input = document.createElement( "input" );
    var num = div.id.substring( 4 );
    
    label.id = "columns-label" + num;
    label.innerHTML = "Columnas:";
    label.HTMLfor = "page-columns" + num;
    
    input.type = "number";
    input.name = "page-columns" + num;
    input.id = "page-columns" + num;
    
    div.appendChild( label );
    div.appendChild( input );
    div.appendChild( document.createTextNode( "" ) );
    div.appendChild( document.createElement( "br" ) );
    div.appendChild( document.createElement( "br" ) );
}

function hideColumnInput( div ){
    var num = div.id.substring( 4 );
    
    div.removeChild( document.getElementById( "columns-label" + num ) );
    div.removeChild( document.getElementById( "page-columns" + num ) );
    for( var i = 0; i < 3; i++ ){
        div.removeChild( div.lastChild );
    }
}

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
    if( document.getElementById( "checkbox-url" ).checked ){
        boolArray[7] = checkUrl( form, document.getElementById( "reg-student-url" ) );
    }
    if( document.getElementById( "checkbox-github" ).checked ){
        boolArray[8] = checkUrl( form, document.getElementById( "reg-student-github" ) );
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

function checkCode( form, code ){
    var ok = true;
    var regex = /^.+$/gi;
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
    var regex = /^.+@.+$/gi
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
    var regex = /^.+$/gi
    var str = url.value;
    var message;
    
    if( !regex.test( str ) ){
        message = document.createTextNode( " Formato de url incorrecto." );
        url.parentNode.replaceChild( message, url.parentNode.lastChild );
        ok = false;
    }
    else{
        mesage = document.createTextNode( "" );
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

function addTime(){
    var div = document.createElement( "div" );
    var dayLabel = document.createElement( "label" );
    var daySelect = document.createElement( "select" );
    var timeLabel = document.createElement( "label" );
    var timeSelect = document.createElement( "select" );
    var lenLabel = document.createElement( "label" );
    var lenInput = document.createElement( "input" );
    var otherDiv = document.getElementById( "eval1" );
    var button = document.getElementById( "add-time" );
    
    div.className = "form-div";
    dayLabel.HTMLfor = "new-class-day";
    dayLabel.innerHTML = document.getElementById( "day-label1" ).innerHTML;
    div.appendChild( dayLabel );
    
    daySelect.name = "new-class-day";
    daySelect.innerHTML = document.getElementById( "new-class-day1" ).innerHTML;
    div.appendChild( daySelect );
    
    div.appendChild( document.createElement( "br" ) );
    div.appendChild( document.createElement( "br" ) );
    
    timeLabel.HTMLfor = "new-class-start";
    timeLabel.innerHTML = document.getElementById( "time-label1" ).innerHTML;
    div.appendChild( timeLabel );
    
    timeSelect.name = "new-class-start";
    timeSelect.innerHTML = document.getElementById( "new-class-start1" ).innerHTML;
    div.appendChild( timeSelect );
    
    div.appendChild( document.createElement( "br" ) );
    div.appendChild( document.createElement( "br" ) );
    
    lenLabel.HTMLfor = "new-class-duration";
    lenLabel.innerHTML = document.getElementById( "len-label1" ).innerHTML;
    div.appendChild( lenLabel );
    
    lenInput.name = "new-class-duration";
    lenInput.type = "number";
    lenInput.min = "1";
    lenInput.max = "4";
    div.appendChild( lenInput );
    
    div.appendChild( button );
    
    document.newClass.insertBefore( div, otherDiv );
    document.newClass.insertBefore( document.createElement( "br" ), otherDiv );
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
    var otherDiv = document.getElementById( "submit-div" );
    var button = document.getElementById( "add-eval" );
    
    div.className = "form-div";
    actLabel.HTMLfor = "new-class-act";
    actLabel.innerHTML = document.getElementById( "class-act-label1" ).innerHTML;
    div.appendChild( actLabel );
    
    actInput.type = "text";
    actInput.name = "new-class-act";
    div.appendChild( actInput );
    
    div.appendChild( document.createElement( "br" ) );
    div.appendChild( document.createElement( "br" ) );
    
    valLabel.HTMLfor = "new-class-val";
    valLabel.innerHTML = document.getElementById( "class-val-label1" ).innerHTML;
    div.appendChild( valLabel );
    
    valInput.type = "number";
    valInput.name = "new-class-val";
    div.appendChild( valInput );
    
    div.appendChild( document.createElement( "br" ) );
    div.appendChild( document.createElement( "br" ) );
    
    pageLabel.HTMLfor = "eval-page";
    pageLabel.innerHTML = document.getElementById( "page-label1" ).innerHTML;
    div.appendChild( pageLabel );
    
    pageInput.type = "checkbox";
    pageInput.name = "eval-page";
    div.appendChild( pageInput );
    
    div.appendChild( document.createElement( "br" ) );
    div.appendChild( document.createElement( "br" ) );
    
    columnLabel.HTMLfor = "page-columns";
    columnLabel.innerHTML = document.getElementById( "columns-label1" ).innerHTML;
    div.appendChild( columnLabel );
    
    columnInput.type = "number";
    columnInput.name = "page-columns";
    columnInput.disabled = true;
    div.appendChild( columnInput );
    
    div.appendChild( button );
    
    pageInput.onclick = function(){
        if( columnInput.disabled ){
            columnInput.disabled = false;
        }
        else{
            columnInput.disabled = true;
            columnInput.value = "";
            //elem.parentNode.replaceChild( document.createTextNode( "" ), elem.parentNode.lastChild );
        }
    }
    
    document.newClass.insertBefore( div, otherDiv );
    document.newClass.insertBefore( document.createElement( "br" ), otherDiv );
}

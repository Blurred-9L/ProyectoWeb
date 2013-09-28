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

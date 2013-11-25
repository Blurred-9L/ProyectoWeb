function fillDaySelect(){
    $.ajax({
        url: "../../Model/getDays.php",
        dataType: "json",
        success: function( json ){
            for( i in json ){
                var dayName = document.createTextNode( json[i].nombreDia );
                var option = document.createElement( "option" );
                option.id = "day-option-" + json[i].idDia.toString();
                option.value = json[i].idDia;
                option.appendChild( dayName );
                document.getElementById( "new-class-day0" ).appendChild( option );
            }
        }
    });
}

function fillAcademySelect(){
    $.ajax({
        url: "../../Model/getAcademies.php",
        dataType: "json",
        success: function( json ){
            for( i in json ){
                var academyName = document.createTextNode( json[i].nombreAcademia );
                var option = document.createElement( "option" );
                option.id = "academy-option-" + json[i].idAcademia.toString();
                option.value = json[i].idAcademia;
                option.appendChild( academyName );
                document.getElementById( "new-class-academy" ).appendChild( option );
            }
        }
    });
}

function fillClassSelect(){
    $.ajax({
        url: "../../Model/getClasses.php",
        dataType: "json",
        success: function( json ){
            for( i in json ){
                var className = document.createTextNode( json[i].clave + " " + json[i].nombre );
                var option = document.createElement( "option" );
                option.id = "class-key-option-" + json[i].idCurso.toString();
                option.value = json[i].idCurso;
                option.appendChild( className );
                document.getElementById( "new-class-key" ).appendChild( option );
            }
        }
    });
}

function fillMajorSelect( selectId ){
    $.ajax({
        url: "../../Model/getMajors.php",
        dataType: "json",
        success: function( json ){
            for( i in json ){
                var majorName = document.createTextNode( json[i].nombreCarrera );
                var option = document.createElement( "option" );
                option.id = "student-major-option-" + json[i].idCarrera.toString();
                option.value = json[i].idCarrera;
                option.appendChild( majorName );
                document.getElementById( selectId ).appendChild( option );
            }
        }
    });
}

function fillCycleSelect( selectId ){
    $.ajax({
        url: "../../Model/getCycles.php",
        dataType: "json",
        success: function( json ){
            for( i in json ){
                var cycle = document.createTextNode( json[i].ciclo );
                var option = document.createElement( "option" );
                option.id = "cycle-" + json[i].idCiclo;
                option.value = json[i].idCiclo;
                option.appendChild( cycle );
                document.getElementById( selectId ).appendChild( option );
            }
        }
    });
}

function fillTeacherClassSelect( selectId ){
    $.ajax({
        url: "../../Model/getTClasses.php",
        dataType: "json",
        success: function( json ){
            var count = 1;
            for( i in json ){
                var secString = ( json[i].seccion.length == 1 )? "0" + json[i].seccion : json[i].seccion;
                var classInfo = document.createTextNode( json[i].clave + " D" + secString + " " + json[i].ciclo );
                var option = document.createElement( "option" );
                option.id = "teacher-class-option-" + count.toString();
                option.value = json[i].clave + "-" + json[i].seccion + "-" + json[i].ciclo;
                option.appendChild( classInfo );
                document.getElementById( selectId ).appendChild( option );
                count += 1;
            }
        }
    });
}

function getTeacherClassList(){
    var select = document.getElementById( "teacher-classes" );
    var cycleInfo = select.value;
    
    if( select.selectedIndex != 0 ){
        $.ajax({
            type: "POST",
            data: {info: cycleInfo},
            url: "../../Model/getTeacherClassList.php",
            dataType: "json",
            success: function( json ){
                var tableBody = document.getElementById( "table-body" );
                while( tableBody.firstChild != null ){
                    tableBody.removeChild( tableBody.firstChild );
                }
                if( json != null ){
                    for( i in json ){
                        var newRow = document.createElement( "tr" );
                        var nameCell = document.createElement( "td" );
                        var codeCell = document.createElement( "td" );
                        var majorCell = document.createElement( "td" );
                        var mailCell = document.createElement( "td" );
                        
                        majorCell.className = "optional";
                        mailCell.className = "optional";
                        
                        nameCell.appendChild( document.createTextNode( json[i].nombre ) );
                        codeCell.appendChild( document.createTextNode( json[i].codigo ) );
                        majorCell.appendChild( document.createTextNode( json[i].nombreCarrera ) );
                        mailCell.appendChild( document.createTextNode( json[i].email ) );
                        
                        newRow.appendChild( nameCell );
                        newRow.appendChild( codeCell );
                        newRow.appendChild( majorCell );
                        newRow.appendChild( mailCell );
                        
                        tableBody.appendChild( newRow );
                    }
                }
            }
        });
    }
}

function getStudentData(){
    var code = document.getElementById( "reg-student-code" ).value;
    var checkbox;
    
    $.ajax({
        type: "POST",
        data: {studentCode: code},
        url: "../../Model/getStudentData.php",
        dataType: "json",
        success: function( json ){
            if( json != null ){
                document.getElementById( "reg-student-name" ).value = json.nombre;
                document.getElementById( "reg-student-last1" ).value = json.apellidoP;
                document.getElementById( "reg-student-last2" ).value = json.apellidoM;
                document.getElementById( "reg-student-email" ).value = json.email;
                document.getElementById( "reg-student-major" ).value = json.idCarrera;
                if( json.celular != null ){
                    checkbox = document.getElementById( "checkbox-tel" );
                    checkbox.checked = true;
                    checkbox.onclick();
                    document.getElementById( "reg-student-phone" ).value = json.celular;
                }
                if( json.paginaWeb != null ){
                    checkbox = document.getElementById( "checkbox-url" );
                    checkbox.checked = true;
                    checkbox.onclick();
                    document.getElementById( "reg-student-url" ).value = json.paginaWeb;
                }
                if( json.github != null ){
                    checkbox = document.getElementById( "checkbox-github" );
                    checkbox.checked = true;
                    checkbox.onclick();
                    document.getElementById( "reg-student-github" ).value = json.github;
                }
            }
            else{
                alert( "No se encontro el alumno." );
            }
        }
    });
}

function getTeacherClassStudents(){
    var studentSelect = document.getElementById( "student-select" );
    var classSelect = document.getElementById( "class-select" );
    var tableBody = document.getElementById( "student-roll-body" );
    var tableFoot = document.getElementById( "student-roll-foot" );
    var classInfoStr = classSelect.value;
    
    while( studentSelect.lastChild.value != 0 ){
        studentSelect.removeChild( studentSelect.lastChild );
    }
    while( tableBody.firstChild != null ){
        tableBody.removeChild( tableBody.firstChild );
    }
    while( tableFoot.firstChild != null ){
        tableFoot.removeChild( tableFoot.firstChild );
    }
    if( classSelect.selectedIndex != 0 ){
        $.ajax({
            type: "POST",
            data: {classInfo: classInfoStr},
            url: "../../Model/getTeacherClassStudents.php",
            dataType: "json",
            success: function( json ){
                if( json != null ){
                    var count = 0;
                    for( i in json ){
                        var option = document.createElement( "option" );
                        var text = document.createTextNode( json[i].nombre + "-" + json[i].codigo );
                        option.id = "student-" + count.toString();
                        option.value = json[i].codigo;
                        option.appendChild( text );
                        studentSelect.appendChild( option );
                        count += 1;
                    }
                }
            }
        });
    }
}

function seeStudentRollCall(){
    var studentSelect = document.getElementById( "student-select" );
    var classSelect = document.getElementById( "class-select" );
    var tableBody = document.getElementById( "student-roll-body" );
    var tableFoot = document.getElementById( "student-roll-foot" );
    var classInfoStr = classSelect.value;
    var code = studentSelect.value;
    
    while( tableBody.firstChild != null ){
        tableBody.removeChild( tableBody.firstChild );
    }
    while( tableFoot.firstChild != null ){
        tableFoot.removeChild( tableFoot.firstChild );
    }
    if( studentSelect.selectedIndex != 0 ){
        $.ajax({
            type: "POST",
            data: {studentCode: code, classInfo: classInfoStr},
            url: "../../Model/getStudentRollCall.php",
            dataType: "json",
            success: function( json ){
                var caption = document.getElementById( "student-roll-caption" );
                
                caption.replaceChild( document.createTextNode( "Asistencias de " + code ), caption.firstChild );
                if( json != null ){
                    var count = 0;
                    var total = 0;
                    for( i in json ){
                        var newRow = document.createElement( "tr" );
                        var dateCell = document.createElement( "td" );
                        var stateCell = document.createElement( "td" );
                        
                        dateCell.appendChild( document.createTextNode( json[i].fecha ) );
                        stateCell.appendChild( document.createTextNode( ( json[i].estado == 0 )? "Falta" : "Asistencia" ) );
                        
                        newRow.appendChild( dateCell );
                        newRow.appendChild( stateCell );
                        
                        tableBody.appendChild( newRow );
                        
                        count += ( json[i].estado == 0 )? 0 : 1;
                        total += 1;
                    }
                    
                    var footRow = document.createElement( "tr" );
                    var totalCell = document.createElement( "td" );
                    var percCell = document.createElement( "td" );
                    
                    totalCell.appendChild( document.createTextNode( count.toString() + " asistencias" ) );
                    percCell.appendChild( document.createTextNode( ( count / total * 100 ) + "%" ) );
                    
                    footRow.appendChild( totalCell );
                    footRow.appendChild( percCell );
                    tableFoot.appendChild( footRow );
                }
            }
        });
    }
}

function getTeacherClassDates(){
    var datesSelect = document.getElementById( "date-select" );
    var classSelect = document.getElementById( "class-select" );
    var classInfoStr = classSelect.value;
    
    if( classSelect.selectedIndex != 0 ){
        $.ajax({
            type: "POST",
            data: {classInfo: classInfoStr},
            url: "../../Model/getTeacherClassStudents.php",
            dataType: "json",
            success: function( json ){
                var tableBody = document.getElementById( "students-names-body" );
                while( tableBody.firstChild != null ){
                    tableBody.removeChild( tableBody.firstChild );
                }
                if( json != null ){
                    var count = 0;
                    for( i in json ){
                        var newRow = document.createElement( "tr" );
                        var checkboxCell = document.createElement( "td" );
                        var nameCell = document.createElement( "td" );
                        var codeCell = document.createElement( "td" );
                        var checkbox = document.createElement( "input" );
                        
                        checkbox.type = "checkbox";
                        checkbox.name = "roll-call-checkbox-" + count.toString();
                        checkbox.id = "roll-call-checkbox-" + count.toString();
                        checkboxCell.className = "checkbox-cell";
                        checkboxCell.appendChild( checkbox );
                        
                        nameCell.id = "student-name-" + count.toString();
                        nameCell.appendChild( document.createTextNode( json[i].nombre ) );
                        
                        codeCell.id = "student-code-" + count.toString();
                        codeCell.appendChild( document.createTextNode( json[i].codigo ) );
                        
                        newRow.appendChild( checkboxCell );
                        newRow.appendChild( nameCell );
                        newRow.appendChild( codeCell );
                        
                        tableBody.appendChild( newRow );
                        count += 1;
                    }
                }
            }
        });
    
        $.ajax({
            type: "POST",
            data: {classInfo: classInfoStr},
            url: "../../Model/getTeacherClassDates.php",
            dataType: "json",
            success: function( json ){
                while( datesSelect.lastChild.value != 0 ){
                    datesSelect.removeChild( datesSelect.lastChild );
                }
                var count = 0;
                if( json != null ){
                    for( i in json ){
                        var option = document.createElement( "option" );
                        var text = document.createTextNode( json[i].fecha );
                        option.id = "date-" + count.toString();
                        option.value = json[i].fecha;
                        option.appendChild( text );
                        datesSelect.appendChild( option );
                        count += 1;
                    }
                }
            }
        });
    }
}

function getTeacherClassEvalInfo(){
    var classSelect = document.getElementById( "select-class" );
    var evalSelect = document.getElementById( "select-eval" );
    var elemSelect = document.getElementById( "select-sub-eval" );
    var classInfoStr = classSelect.value;
    var tableBody = document.getElementById( "eval-table-body" );
    
    while( tableBody.firstChild != null ){
        tableBody.removeChild( tableBody.firstChild );
    }
    if( classSelect.selectedIndex != 0 ){
        // Gets all students.
        $.ajax({
            type: "POST",
            data: {classInfo: classInfoStr},
            url: "../../Model/getTeacherClassStudents.php",
            dataType: "json",
            success: function( json ){
                if( json != null ){
                    while( evalSelect.lastChild.value != 0 ){
                        evalSelect.removeChild( evalSelect.lastChild );
                    }
                
                    var count = 0;
                    for( i in json ){
                        var newRow = document.createElement( "tr" );
                        var checkboxCell = document.createElement( "td" );
                        var nameCell = document.createElement( "td" );
                        var codeCell = document.createElement( "td" );
                        var checkbox = document.createElement( "input" );
                        
                        checkbox.type = "checkbox";
                        checkbox.name = "eval-checkbox-" + count.toString();
                        checkbox.id = "eval-checkbox-" + count.toString();
                        checkboxCell.className = "checkbox-cell2";
                        checkboxCell.appendChild( checkbox );
                        
                        nameCell.id = "student-name-" + count.toString();
                        nameCell.appendChild( document.createTextNode( json[i].nombre ) );
                        
                        codeCell.id = "student-code-" + count.toString();
                        codeCell.appendChild( document.createTextNode( json[i].codigo ) );
                        
                        newRow.appendChild( checkboxCell );
                        newRow.appendChild( nameCell );
                        newRow.appendChild( codeCell );
                        
                        tableBody.appendChild( newRow );
                        count += 1;
                    }
                }
            }
        });
        
        // Gets all eval pages.
        $.ajax({
            type: "POST",
            data: {classInfo: classInfoStr},
            url: "../../Model/getTeacherClassEvalParams.php",
            dataType: "json",
            success: function( json ){
                if( json != null ){
                    var count = 0;
                    for( i in json ){
                        var option = document.createElement( "option" );
                        var text = document.createTextNode( json[i].descripcion );
                        
                        option.id = "eval-" + count.toString();
                        option.value = json[i].idHojaEvaluacion;
                        option.appendChild( text );
                        evalSelect.appendChild( option );
                        count += 1;
                    }
                }
            }
        });
        
        evalSelect.selectedIndex = 0;
        elemSelect.selectedIndex = 0;
    }
}

function getTeacherClassEvalElems(){
    var evalSelect = document.getElementById( "select-eval" );
    var elemSelect = document.getElementById( "select-sub-eval" );
    var evalId = evalSelect.value;
    
    if( evalSelect.selectedIndex != 0 ){
        $.ajax({
            type: "POST",
            data: {id: evalId},
            url: "../../Model/getEvalParam.php",
            dataType: "json",
            success: function( json ){
                while( elemSelect.lastChild.value != 0 ){
                    elemSelect.removeChild( elemSelect.lastChild );
                }
                if( json != null ){
                    var times = json.nElems;
                    var description = json.descripcion;
                    
                    for( var i = 0; i < times; i++ ){
                        var value = i + 1;
                        var option = document.createElement( "option" );
                        var text = document.createTextNode( description + " " + value.toString() );
                        
                        option.id = "eval-elem-" + i.toString();
                        option.value = value;
                        option.appendChild( text );
                        elemSelect.appendChild( option );
                    }
                }
            }
        });
        
        elemSelect.selectedIndex = 0;
    }
}

function getStudentClassReportCard(){
    var cycleSelect = document.getElementById( "cycle-select" );
    var cycleValue = cycleSelect.value;
    var tableBody = document.getElementById( "report-card-body" );
    
    while( tableBody.firstChild != null ){
        tableBody.removeChild( tableBody.firstChild );
    }
    if( cycleSelect.selectedIndex != 0 ){
        $.ajax({
            type: "POST",
            data: {cycle: cycleValue},
            url: "../../Model/getStudentClassReport.php",
            dataType: "json",
            success: function( json ){
                var count = 0;
                if( json != null ){
                    for( i in json ){
                        var newRow = document.createElement( "tr" );
                        var gradeCell = document.createElement( "td" );
                        var assistCell = document.createElement( "td" );
                        var classKeyCell = document.createElement( "td" );
                        var classNameCell = document.createElement( "td" );
                        var checkboxCell = document.createElement( "td" );
                        var checkbox = document.createElement( "input" );
                        
                        checkbox.type = "checkbox";
                        checkbox.id = "checkbox-" + count.toString();
                        checkbox.name = "checkbox-" + json[i].idAlumnoCurso;
                        checkboxCell.appendChild( checkbox );
                        checkboxCell.className = "checkbox-cell2";
                        
                        gradeCell.className = "grade-cell";
                        gradeCell.appendChild( document.createTextNode( json[i].calificacion ) );
                        
                        assistCell.className = "assist-cell";
                        assistCell.appendChild( document.createTextNode( json[i].porcentajeAsistencia ) );
                        
                        classKeyCell.className = "class-key-cell optional";
                        classKeyCell.id = "student-class-" + count.toString();
                        classKeyCell.appendChild( document.createTextNode( json[i].clave ) );
                        
                        classNameCell.className = "class-name-cell";
                        classNameCell.appendChild( document.createTextNode( json[i].nombre ) );
                        
                        newRow.appendChild( checkboxCell );
                        newRow.appendChild( gradeCell );
                        newRow.appendChild( assistCell );
                        newRow.appendChild( classKeyCell );
                        newRow.appendChild( classNameCell );
                        
                        tableBody.appendChild( newRow );
                        count += 1;
                    }
                }
            }
        });
    }
}

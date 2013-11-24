// ----------------------------------------------------------------------------
// Main onload functions:
// ----------------------------------------------------------------------------

function loginLoad(){
    var button = document.getElementById( "login" );
                
    button.onclick = checkLogin;
}

function lostPassLoad(){
    var button = document.getElementById( "recover" );
    
    recover.onclick = function(){
        var ok;
        ok = checkCode( document.recoverPass, document.getElementById( "lost-pass-code" ) );
        
        if( ok ){
            document.recoverPass.submit();
        }
    }
}

// ----------------------------------------------------------------------------
// Admin onload functions:
// ----------------------------------------------------------------------------

function newStudentLoad(){
    var button = document.getElementById( "submit-new-student" );
    var phone = document.getElementById( "checkbox-tel" );
    var url = document.getElementById( "checkbox-url" );
    var github = document.getElementById( "checkbox-github" );
    
    button.onclick = checkRegStudent2;
    fillMajorSelect( "reg-student-major" );
    
    phone.onclick = function(){
        if( phone.checked ){
            showStudentInput( phone, "tel", "reg-student-phone", "reg-student-phone" );
        }
        else{
            hideStudentInput( "reg-student-phone" );
        }
    }
    
    url.onclick = function(){
        if( url.checked ){
            showStudentInput( url, "url", "reg-student-url", "reg-student-url" );
        }
        else{
            hideStudentInput( "reg-student-url" );
        }
    }
    
    github.onclick = function(){
        if( github.checked ){
            showStudentInput( github, "url", "reg-student-github", "reg-student-github" );
        }
        else{
            hideStudentInput( "reg-student-github" );
        }
    }
}

function showAllStudentsLoad(){
    var button = document.getElementById( "see-student" );
                
    button.onclick = checkSelected2;
}

function editCycleLoad(){
    countFreeDays = 1;
    var addButton = document.getElementById( "free-day-button" );
    var button = document.getElementById( "add-free-days" );
    var selectAll = document.getElementById( "select-all" );
    var eraseButton = document.getElementById( "erase-free-days" );
    
    selectAll.onclick = selectAllFunc2;
    addButton.onclick = addFreeDay;
    eraseButton.onclick = eraseFreeDays;
    button.onclick = checkFreeDays;
}

function showAllCyclesLoad(){
    var button = document.getElementById( "free-day-edit" );
                
    button.onclick = checkSelected3;
}

function editStudentLoad(){
    var button = document.getElementById( "submit-student-ch" );
                
    button.onclick = checkStudent;
}

function editTeacherLoad(){
    var button = document.getElementById( "edit-teacher-button" );
                
    button.onclick = checkEditTeacher;
}

function newCycleLoad(){
    countFreeDays = 0;
    var addButton = document.getElementById( "add-free-day" );
    var button = document.getElementById( "new-cycle-button" );
    
    addButton.onclick = addFreeDay2;
    button.onclick = checkNewCycle;
}

function newTeacherLoad(){
    var button = document.getElementById( "submit-teacher" );
                
    button.onclick = checkNewTeacher;
}

function showAllTeachersLoad(){
    var button = document.getElementById( "to-edit-button" );
                
    button.onclick = checkSelected4;
}

// ----------------------------------------------------------------------------
// Teachers onload functions:
// ----------------------------------------------------------------------------

function showAllTeacherClassesLoad(){
    var button = document.getElementById( "see-class" );
    
    button.onclick = checkSelected5;
}

function newClassLoad(){
    nSchedules = 1;
    nEvals = 1;
    var addTimeButton = document.getElementById( "add-time" );
    var addEvalButton = document.getElementById( "add-eval" );
    var checkbox = document.getElementById( "eval-page0" );
    var button = document.getElementById( "submit-new-class" );
    
    button.onclick = checkNewClass;
    addTimeButton.onclick = addTime;
    addEvalButton.onclick = addEval;
    checkbox.checked = false;
    checkbox.onclick = function(){
        if( checkbox.checked ){
            showColumnInput( checkbox.parentNode );
        }
        else{
            hideColumnInput( checkbox.parentNode );
        }
    }
    
    fillDaySelect();
    //fillAcademySelect();
    fillClassSelect();
}

function teacherNewStudentLoad(){
    var button = document.getElementById( "submit-reg-student" );
    var searchButton = document.getElementById( "search-code-button" );
    var phone = document.getElementById( "checkbox-tel" );
    var url = document.getElementById( "checkbox-url" );
    var github = document.getElementById( "checkbox-github" );
    
    fillMajorSelect( "reg-student-major" );
    fillTeacherClassSelect( "reg-student-class" );
    
    button.onclick = checkRegStudent;
    searchButton.onclick = getStudentData;
    phone.onclick = function(){
        if( phone.checked ){
            showStudentInput( phone, "tel", "reg-student-phone", "reg-student-phone" );
        }
        else{
            hideStudentInput( "reg-student-phone" );
        }
    }
    
    url.onclick = function(){
        if( url.checked ){
            showStudentInput( url, "url", "reg-student-url", "reg-student-url" );
        }
        else{
            hideStudentInput( "reg-student-url" );
        }
    }
    
    github.onclick = function(){
        if( github.checked ){
            showStudentInput( github, "url", "reg-student-github", "reg-student-github" );
        }
        else{
            hideStudentInput( "reg-student-github" );
        }
    }
}

function teacherUploadFileLoad(){
    var loadButton = document.getElementById( "file-load-button" );
    
    fillTeacherClassSelect( "load-student-class" );
    loadButton.onclick = checkLoadFile;
}

function teacherUploadStudentsLoad(){
    var selectAll = document.getElementById( "select-all" );
    var button = document.getElementById( "submit-load-students" );
    
    selectAll.onclick = selectAllFunc;
    button.onclick = function(){
        var form = document.loadStudents2;
        
        form.submit();
    };
}

function teacherListClasses(){
    var select = document.getElementById( "teacher-classes" );
    
    fillTeacherClassSelect( "teacher-classes" );
    select.onchange = getTeacherClassList;
}

function teacherSeeClassRollLoad(){
    var classSelect = document.getElementById( "class-select" );
    var studentSelect = document.getElementById( "student-select" );
    
    fillTeacherClassSelect( "class-select" );
    classSelect.onchange = getTeacherClassStudents;
    studentSelect.onchange = seeStudentRollCall;
}

function teacherTakeClassRollLoad(){
    var selectAll = document.getElementById( "select-all" );
    var classSelect = document.getElementById( "class-select" );
    var button = document.getElementById( "class-assistance-button" );
    
    fillTeacherClassSelect( "class-select" );
    selectAll.onclick = selectAllFunc3;
    classSelect.onchange = getTeacherClassDates;
    button.onclick = checkClassAssistance;
}

function teacherDataLoad(){
    var button = document.getElementById( "submit-teacher-data" );
                
    button.onclick = checkTeacherData;
}

function teacherPassLoad(){
    var button = document.getElementById( "pass-submit" );
                
    button.onclick = checkTeacherPass;
}

function teacherCloneClassLoad(){
    var button = document.getElementById( "clone-submit" );
                
    fillTeacherClassSelect( "class-select" );
    button.onclick = checkCloneClass;
}

function teacherEvalStudentLoad(){
    var button = document.getElementById( "eval-button" );
    var classSelect = document.getElementById( "select-class" );
    var evalSelect = document.getElementById( "select-eval" );
    
    fillTeacherClassSelect( "select-class" );
    button.onclick = checkEvalParams;
    classSelect.onchange = getTeacherClassEvalInfo;
    evalSelect.onchange = getTeacherClassEvalElems;
}

// ----------------------------------------------------------------------------
// Students onload functions:
// ----------------------------------------------------------------------------

function studentDataLoad(){
    var button = document.getElementById( "change-button" );
                
    button.onclick = checkStudentData;
}

function studentPassLoad(){
    var button = document.getElementById( "pass-submit" );
                
    button.onclick = checkStudentPass;
}

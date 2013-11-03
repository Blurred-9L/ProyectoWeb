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
    fillAcademySelect();
}

function newStudentLoad(){
    var button = document.getElementById( "submit-new-student" );
    var phone = document.getElementById( "checkbox-tel" );
    var url = document.getElementById( "checkbox-url" );
    var github = document.getElementById( "checkbox-github" );
    
    button.onclick = checkRegStudent2;
    
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

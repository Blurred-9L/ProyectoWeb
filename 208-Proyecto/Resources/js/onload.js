function newClassLoad(){
    nSchedules = 1;
    nEvals = 1;
    addTimeButton = document.getElementById( "add-time" );
    addEvalButton = document.getElementById( "add-eval" );
    checkbox = document.getElementById( "eval-page0" );
    button = document.getElementById( "submit-new-class" );
    
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
    
    $.ajax({
        url: "../../Model/DayMdl.php",
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

function newCycleLoad(){
    countFreeDays = 0;
    addButton = document.getElementById( "add-free-day" );
    button = document.getElementById( "new-cycle-button" );
    
    addButton.onclick = addFreeDay2;
    button.onclick = checkNewCycle;
}

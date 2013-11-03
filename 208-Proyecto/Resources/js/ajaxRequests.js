function fillDaySelect(){
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

function fillAcademySelect(){
    $.ajax({
        url: "../../Model/AcademyMdl.php",
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

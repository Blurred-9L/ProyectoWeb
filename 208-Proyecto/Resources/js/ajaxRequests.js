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

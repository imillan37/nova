function hazclick(id_menu){

    if( jQuery("li[alt=alt"+id_menu+"]").length ) { 
        // if( jQuery("li[alt=alt'.$rsMENUS->fields["ID_Menu"].']").css("display") == "none" ) { 
        //         closeOpen = false;
        // }
        console.log('entra if');
    }else{
        console.log('entra else');
    }

}
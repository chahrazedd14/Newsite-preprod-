/**************************************************************************************************************************** */
// DOCUMENT READY 
/**************************************************************************************************************************** */
var fechasDatepicker = '';
var hostName = 'https://'+document.location.hostname;
var $body = jQuery('body');
var inputactive = false;

jQuery(document).ready(function () {

    //Pone a 0 todos los campos de persona
    jQuery('.annuler-link').on('click', function(){

        jQuery('.number').each(function(){
            jQuery(this).attr('value', 0);
        });

        //Para que me deje por defecto 2 adultos
        jQuery('.numberA').attr('value', 2);
        jQuery('.avec-qui-partez-vous-menu').text('participants');
        jQuery('.avec-qui-partez-vous').text('participants');
        
        jQuery('.numberPersonas-menu').text(2);
        jQuery('.numberPersonas').text(2);

        jQuery('.numberPersonas-menu').val(2);
        jQuery('.numberPersonas').val(2);

        // jQuery('.avec-qui-partez-vous-menu').text('Avec qui partez-vous ?');
        // jQuery('.avec-qui-partez-vous').text('Avec qui partez-vous ?');
        
        //Elimino la clase para que se vea el espacio del input
        // jQuery('.avec-qui-partez-vous-menu').removeClass('inputVisible');
        // jQuery('.avec-qui-partez-vous').removeClass('inputVisible');

        //jQuery('.selPersonas').trigger('click');

    });

    jQuery('.cparti-link').on('click', function(){

        jQuery('.selPersonas').trigger('click');

    });

    jQuery('.cparti-link-menu').on('click', function(){

        jQuery('.selPersonas-menu').trigger('click');

    });
    
    //Inicio el datepicker con las temporadas activas y fechas de primera y ultima disponibilidad
    initializeDatepicker();

    //Inicializo el autocompleteID (hidden value for autocomplete) en value de ALPES 
    

    // SIESTAMOS EN PARTENAIRES Y HEMOS SELECCIONADO MER DEBEREMOS CRAGAR PARTOUT
    var partenairesServiceId = getCookie('partner_service_id');
    var autocomplitValueInicial = '1__region__Alpes';
    if(partenairesServiceId == 4){
        autocomplitValueInicial = '0__region__Partout';
    }
    console.log(autocomplitValueInicial);

    jQuery(".autocompleteID").attr('value', autocomplitValueInicial);
    jQuery(".autocompleteID-menu").attr('value', autocomplitValueInicial);
    jQuery('.autocomplete-image').attr('data-region' , 1);

    //Inicializo el datepicker con las fechas de todas las temporadas activas
    if(jQuery('.autocomplete').length){
        initializeAutocomplete();
    }

    //Inicializo el datepicker del MENU con las fechas de todas las temporadas activas
    if(jQuery('.autocomplete-menu').length){
        initializeAutocompleteMenu();
    }

    //Array con la o las temporadas para buscar disponibilidad
    var datesWithDispo = getActiveSeasons();

    //Si no he seleccionado fechas, cargo todos los tipos de establecimiento con disponibilidad en las temporadas activas
    getTypeHebergements(datesWithDispo);

    getTypeHebergementsMenu(datesWithDispo);

    getAllHebergements('', '');

    //Si Selecciono una fecha en el datepicker, cargo los tipos de alojamiento con disponibilidades en las fechas seleccionadas    
    jQuery('.datefilter').on('apply.daterangepicker', function (ev, picker) {

        ////console.log('cambio fechas');  // CERRAMOS SOMBREADO AL CERRAR DATEPICKER
        jQuery('.datefilter').removeClass('active');
        jQuery('body').removeClass('body-layer');

        jQuery(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        var datesWithDispo = [{ 'dateFirstDispo': picker.startDate.format('YYYY-MM-DD'), 'dateLastDispo': picker.endDate.format('YYYY-MM-DD') }];
        fechasDatepicker = datesWithDispo;
        getTypeHebergements(datesWithDispo);
        //Cargo todos los establecimientos con fecha de apertura mayor o igual a la fecha de inicio
        //y fecha de cierre menor o igual a la fecha de fin
        getAllHebergements('', datesWithDispo);
        //En dependencia de las fechas seleccionadas cambio la web a invierno o verano.

        var temporada = getSeasonByDate(picker.startDate.format('YYYY-MM-DD'), picker.endDate.format('YYYY-MM-DD'));

        var date = fechasDatepicker[0];

        var beginD = date.dateFirstDispo.replace(new RegExp('-', 'g'),"/");
        var EndD = date.dateLastDispo.replace(new RegExp('-', 'g'),"/");
      
        var arrBegin = beginD.split('/');
        strBegin = arrBegin[2]+'/'+arrBegin[1]+'/'+arrBegin[0];
      
        var arrEnd = EndD.split('/');
        strEnd = arrEnd[2]+'/'+arrEnd[1]+'/'+arrEnd[0];
      
        // Si cambio la fecha en el buscador de resultados tambien lo cambio en el buscador del menu
        // dateBegin y dateEnd (MM/DD/YYYY)
        let startDate = moment(jQuery('.datefilter').data('daterangepicker').startDate).toDate();
        let endDate = moment(jQuery('.datefilter').data('daterangepicker').endDate).toDate();
      
      
        let startDateBuscador = moment(startDate).format('MM/DD/YYYY');
        let endDateBuscador = moment(endDate).format('MM/DD/YYYY');
      
        var drp = jQuery('.datefilter-menu').data('daterangepicker');
                    drp.startDate = moment(startDateBuscador);
                    
      
        let startDateBuscadorMenu = moment(startDate).format('DD/MM/YYYY');
        let endDateBuscadorMenu = moment(endDate).format('DD/MM/YYYY');          
      
        jQuery('.datefilter-menu').val(startDateBuscadorMenu+'-'+endDateBuscadorMenu);
        jQuery('.datefilter-menu').removeClass('active');

        if ( window.location.pathname == '/' ){
            // Index (home) page
            EteHiver(temporada);
        
        } else {
            // Other page
            updateCookie('saison', temporada);
        }

        //EteHiver(temporada);
        ////console.log('temporada: '+temporada);
        //updateCookie('saison', temporada);
        ////console.log('temporada: '+temporada);

    });

    //Si Selecciono una fecha en el datepicker del MENU, cargo los tipos de alojamiento con disponibilidades en las fechas seleccionadas    
    jQuery('.datefilter-menu').on('apply.daterangepicker', function (ev, picker) {

        //jQuery('.datefilter-menu').removeClass('active');
        //jQuery('body').removeClass('body-layer');

        jQuery(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        var datesWithDispo = [{ 'dateFirstDispo': picker.startDate.format('YYYY-MM-DD'), 'dateLastDispo': picker.endDate.format('YYYY-MM-DD') }];
        fechasDatepicker = datesWithDispo;
        getTypeHebergementsMenu(datesWithDispo);

        //Cargo todos los establecimientos con fecha de apertura mayor o igual a la fecha de inicio
        //y fecha de cierre menor o igual a la fecha de fin
        getAllHebergements('', datesWithDispo);

        //En dependencia de las fechas seleccionadas cambio la web a invierno o verano.
        var temporada = getSeasonByDate(picker.startDate.format('YYYY-MM-DD'), picker.endDate.format('YYYY-MM-DD'));

        var date = fechasDatepicker[0];

        var beginD = date.dateFirstDispo.replace(new RegExp('-', 'g'),"/");
        var EndD = date.dateLastDispo.replace(new RegExp('-', 'g'),"/");
      
        var arrBegin = beginD.split('/');
        strBegin = arrBegin[2]+'/'+arrBegin[1]+'/'+arrBegin[0];
      
        var arrEnd = EndD.split('/');
        strEnd = arrEnd[2]+'/'+arrEnd[1]+'/'+arrEnd[0];
      
        // Si cambio la fecha en el buscador del menu también la cambiare en el buscador de resultados
        // dateBegin y dateEnd (MM/DD/YYYY)
        let startDate = moment(jQuery('.datefilter-menu').data('daterangepicker').startDate).toDate();
        let endDate = moment(jQuery('.datefilter-menu').data('daterangepicker').endDate).toDate();
      
        let startDateBuscadorMenu = moment(startDate).format('MM/DD/YYYY');
        let endDateBuscadorMenu = moment(endDate).format('MM/DD/YYYY');
      
        var drp = jQuery('.datefilter').data('daterangepicker');
                    drp.startDate = moment(startDateBuscadorMenu);
                    drp.endDate = moment(endDateBuscadorMenu);
                    drp.updateView();
                    drp.updateCalendars();
      
        let startDateBuscador = moment(startDate).format('DD/MM/YYYY');
        let endDateBuscador = moment(endDate).format('DD/MM/YYYY');    

       
      
        jQuery('.datefilter').val(startDateBuscador+'-'+endDateBuscador);
        jQuery('.datefilter').removeClass('active');

        if ( window.location.pathname == '/' ){
            // Index (home) page
            EteHiver(temporada);
        
        } else {
            // Other page
            updateCookie('saison', temporada);
        }

       // //console.log('temporada');
       // //console.log(temporada);
        //EteHiver(temporada);
       // updateCookie('saison', temporada);

    });

    //
    /* ****************************************************
      Evento on click para mostrar o esconder el div para
      escoger la cantidad de personas
    **************************************************** */
    jQuery('.selPersonas-menu').on('click', function () {
        if (jQuery('.personasWindDiv-menu').hasClass('active')) {
            jQuery('.personasWindDiv-menu').hide();
            jQuery('.personasWindDiv-menu').removeClass('active');
            //jQuery('body').removeClass('body-layer');
            ////console.log('selPersonas menu');
            
            
        }
        else {
            jQuery('.personasWindDiv-menu').show();
            jQuery('.personasWindDiv-menu').addClass('active');
        }
    });
    /* ****************************************************
      Evento on click para mostrar o esconder el div para
      escoger la cantidad de personas
    **************************************************** */
    jQuery('.selPersonas').on('click', function () {
        if (jQuery('.personasWindDiv').hasClass('active')) {
            jQuery('.personasWindDiv').hide();
            jQuery('.personasWindDiv').removeClass('active');
            jQuery('.selPersonas').removeClass('active');
            jQuery('body').removeClass('body-layer');
            ////console.log('selPersonas');
            }
        else {
            jQuery('.personasWindDiv').show();
            jQuery('.personasWindDiv').addClass('active');
            jQuery('body').addClass('body-layer');
            jQuery('.selPersonas').addClass('active');
            ////console.log('abro el div');
            

        }
    });
    /* ****************************************************
      Cuando el mouse no está encima de la caja de personas,
      oculto el div
    **************************************************** */

    jQuery(document).mouseup(function (e){
    
        var container = jQuery(".personasWindDiv"); // YOUR CONTAINER SELECTOR
        var containerdatefilter = jQuery(".calendar-table td"); // YOUR CONTAINER DATEPICKER
        var containerdatepicker = jQuery(".calendar-table td"); // YOUR CONTAINER DATEFILTER
        var containerautocomplete  = jQuery(".blueBox"); // YOUR CONTAINER AUTOCOMPLIT
        var containermenu  = jQuery(".subreservez-menu.visible"); // OPEN MENU RESERVAS

        if (!container.is(e.target) // if the target of the click isn't the container...
            && container.has(e.target).length === 0) // ... nor a descendant of the container
        {
            container.hide();
            jQuery('.personasWindDiv').removeClass('active');
            jQuery('.selPersonas').removeClass('active');

            
            //NO REMOVEMOS BODY SI SELECCIONAMOS EN DATEPICKER
            if (!containerdatepicker.is(e.target) // if the target of the click isn't the containerdatefilter...
                && containerdatepicker.has(e.target).length === 0) // ... nor a descendant of the containerdatefilter
                
            {
                //console.log('entra mouseup personas y eliminamos body layer si no estamos en datepicker');
                jQuery('.datefilter').removeClass('active');
                
                if (!containerautocomplete.is(document.activeElement) // if the target of the click isn't the containerautocomplete...
                && containerautocomplete.has(document.activeElement).length === 0) // ... nor a descendant of the containerautocomplete
                
                { 
                    //console.log(containerautocomplete.has(e.target).length);
                    //console.log('entra mouseup personas y eliminamos body layer si no estamos en AUTOCOMPLIT');
                    //jQuery('body').removeClass('body-layer');

                    
                    // Si el reservas/menu no esta desplegado quitamos body-layer
                    // Para ponerlo en el menu/reservas lo hacemos desde script.js con un toogle class
                    if (containermenu.length === 0 ) 

                    { 
                        //console.log(containermenu.length);
                        //console.log('menu no esta active');
                        jQuery('body').removeClass('body-layer');
                    }
                }

            }
            
            //console.log(document.activeElement);
            //console.log(e.target);
            //console.log('mouseup personas');
        }
    });

    /* ****************************************************
      Cuando el mouse no está encima de la caja de personas,
      oculto el div
    **************************************************** */

    jQuery(document).mouseup(function (e){

        var container = jQuery(".personasWindDiv-menu"); // YOUR CONTAINER SELECTOR
        

        if (!container.is(e.target) // if the target of the click isn't the container...
            && container.has(e.target).length === 0) // ... nor a descendant of the container
        {
            container.hide();
            jQuery('.personasWindDiv-menu').removeClass('active');
            //console.log('mouseup personas menu');
        }
    });

    /* ****************************************************
      Click en el boton + para añadir personas (Buscador Menu)
    **************************************************** */
    jQuery('.btnDecrease-menu').on('click', function () {
        var element = jQuery(this).next('.number');
        decreaseValueMenu(element);
    });
    /* *******************************************************************
      Click en el boton - para añadir personas (Buscador Menu)
    ******************************************************************** */
    jQuery('.btnIncrease-menu').on('click', function () {
        var element = jQuery(this).prev('.number');
        increaseValueMenu(element);
    });
    /* ****************************************************
      Click en el boton + para añadir personas
    **************************************************** */
    jQuery('.btnDecrease').on('click', function () {
        var element = jQuery(this).next('.number');
        decreaseValue(element);
    });
    /* *******************************************************************
    Click en el boton - para añadir personas
    ******************************************************************** */
    jQuery('.btnIncrease').on('click', function () {
        var element = jQuery(this).prev('.number');
        increaseValue(element);
    });


    /* *******************************************************************
      Click en el boton - para saber la temporada en la que está la web
    ******************************************************************** */
   jQuery('.temporadas').on('click', function () {
        jQuery('.colEteHiver').find('.temporadas').removeClass('activa');
        jQuery(this).toggleClass('activa');
    });

    // jQuery(".autocomplete").on("change keyup paste", function(){
    //     if( jQuery(this).val() == '' ){
    //         //console.log('estoy vacio');
    //         jQuery('.autocomplete-image').css('background-image', 'none');
    //     }
    // });

    // jQuery('.autocomplete').on('input keyup paste', function () {
    //     var hasValue = $.trim(this.value).length;
    //     jQuery('#autocomplete-image').attr('src', function () {
    //         return hasValue ? 'http://placehold.it/20/00ff00' : 'http://placehold.it/20/ff0000';
    //     });
    // });

});



/* *******************************************************************
 Descripción: Función que construye el autocomplete
              de alojamientos
******************************************************************** */
var alias;
function initializeAutocomplete() {
    var autocompleteHome = jQuery(".autocomplete");
    var autocompleteMenu = jQuery(".autocomplete-menu");

    // SIESTAMOS EN PARTENAIRES Y HEMOS SELECCIONADO MER DEBEREMOS CRAGAR PARTOUT
         var partenairesServiceId = getCookie('partner_service_id');
         var autocomplitNameInicial = 'Alpes';
         if(partenairesServiceId == 4){
             autocomplitNameInicial = 'Partout';
         }
         console.log(autocomplitNameInicial);


    autocompleteHome.autocomplete({
        minLength: 0,
        source: function (request, response) {
            var search = request.term;
            var allHebergements = getAllHebergements(search, fechasDatepicker);
            var countRegionesPadre = 1;
            var regionesPadre = getAllRegionesPadre();
            ////console.log(hostName);

            if(  typeof regionesPadre !== 'undefined' && regionesPadre.length > 0){

                jQuery(".Rectangle").html('<button title="region" value="0" onclick="clickRegionPadrebtn.call(this, event)" class="btnRegionRecher"><img class="imgRegionRecher" src="'+hostName+'/images/icons/partout_b.png"><span>Partout</span></button>');

                jQuery.each(regionesPadre, function( index, value ) {
                    jQuery(".Rectangle").append('<button title="region" value="'+value.id+'" onclick="clickRegionPadrebtn.call(this, event)" class="btnRegionRecher"><img class="imgRegionRecher" src="'+value.icon+'"><span>'+value.nom+'</span></button>');
                    countRegionesPadre++;
                });
            } 

            response(allHebergements);
        },
        select: function (event, ui) {
            event.preventDefault();

            

            //Ponemos el valor seleccionado en el input de los resultados y del menu
            autocompleteHome.val(ui.item.label);
            autocompleteMenu.val(ui.item.label);

            var icon = ui.item.icon;
            if(icon == ''){
                icon = 'https://sitenew.preprod.mmv.resalys.com/images/icons/alpes_b.png';
            }

            if ( jQuery('.autocomplete-image').attr('data-region') == 1 ){

                var bg = jQuery('.autocomplete-image').html('Pour toute réservation supérieure à 7 adultes merci de nous contacter au 04 92 12 65 30').css('background-image');
                bg = bg.replace('url(','').replace(')','').replace(/\"/gi, "");

                image = bg.split('/icons/');
                var url = image[0];  
                image = image[1];
                image = image.split('.png');
                image = image[0];
                image = image.split('_');
                image = image[0];

                ////console.log('estoy en el select');
                ////console.log(url+'/icons/'+image+'_b.png');
                jQuery('.autocomplete-image').css('background-image', 'url('+url+'/icons/'+image+'_b.png)') .HT;
                jQuery('.autocomplete-image-menu').css('background-image', 'url('+url+'/icons/'+image+'_b.png)');

            }
            
            //Ponemos la imagen seleccionada en el input de los resultados y del menu
            //jQuery('.autocomplete-image').css('background-image', 'url('+icon+')');
            //jQuery('.autocomplete-image-menu').css('background-image', 'url('+icon+')');

            //AL SELECCIONAR ETABLESSIMENT PONDREMOS ICONO MAR O MONTAÑA YA QUE NO TENEMOS LOS ICONOS EN BLANCO
            
            if( ui.item.ItmExtTypeDestination== 'Montag'){
                jQuery('.autocomplete-image').css('background-image', 'url("images/icons/alpes.png")');  
                jQuery('.autocomplete-image-menu').css('background-image', 'url("images/icons/alpes.png")');  
            }else{
                jQuery('.autocomplete-image').css('background-image', 'url("images/icons/mer.png")');
                jQuery('.autocomplete-image-menu').css('background-image', 'url("images/icons/mer.png")');
            }

            jQuery(".autocompleteID").attr('value',ui.item.value+'__'+'camping');
            jQuery(".autocompleteID-menu").attr('value',ui.item.value+'__'+'camping');

            jQuery('.autocomplete-image').attr('data-region', 0);
            jQuery('.autocomplete-image-menu').attr('data-region', 0);

            //CGOS y MACIF
            var mmvLang = jQuery('html').attr('lang');
            switch (mmvLang) {
                case 'fr-ca':
                    mmvLang = 'macif/';
                    break;
                case 'bs-ba':
                    mmvLang = 'cgos/';
                    break;
                case 'en':
                    mmvLang = 'en/';
                    break;
                case 'ca-es':
                    mmvLang = 'partenaires/';
                    break;
                default:
                    mmvLang = '';
                    break;
            }
            

            alias = getAllHebergementAliasByID(ui.item.value);
            jQuery('.frmRechercher').attr('action',mmvLang+alias.replace(/['"]+/g, ''));
            jQuery('.frmRechercherMenu').attr('action',mmvLang+alias.replace(/['"]+/g, ''));

             //Cada vez q se seleccione un alojamiento hay que actualizar el datepicker y tener en cuenta que cuando se selecciona
            //una region tambien hay que actualizarlo
            //initializeDatepicker();
            updateDatepicker(ui.item.codeCRM);

                //Si Selecciono una fecha en el datepicker, cargo los tipos de alojamiento con disponibilidades en las fechas seleccionadas    
    jQuery('.datefilter').on('apply.daterangepicker', function (ev, picker) {

        //console.log('cambio fechas');

        jQuery(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        var datesWithDispo = [{ 'dateFirstDispo': picker.startDate.format('YYYY-MM-DD'), 'dateLastDispo': picker.endDate.format('YYYY-MM-DD') }];
        fechasDatepicker = datesWithDispo;
        getTypeHebergements(datesWithDispo);
        //Cargo todos los establecimientos con fecha de apertura mayor o igual a la fecha de inicio
        //y fecha de cierre menor o igual a la fecha de fin
        getAllHebergements('', datesWithDispo);
        //En dependencia de las fechas seleccionadas cambio la web a invierno o verano.

        var temporada = getSeasonByDate(picker.startDate.format('YYYY-MM-DD'), picker.endDate.format('YYYY-MM-DD'));

        var date = fechasDatepicker[0];

        var beginD = date.dateFirstDispo.replace(new RegExp('-', 'g'),"/");
        var EndD = date.dateLastDispo.replace(new RegExp('-', 'g'),"/");
      
        var arrBegin = beginD.split('/');
        strBegin = arrBegin[2]+'/'+arrBegin[1]+'/'+arrBegin[0];
      
        var arrEnd = EndD.split('/');
        strEnd = arrEnd[2]+'/'+arrEnd[1]+'/'+arrEnd[0];
      
        // Si cambio la fecha en el buscador de resultados tambien lo cambio en el buscador del menu
        // dateBegin y dateEnd (MM/DD/YYYY)
        let startDate = moment(jQuery('.datefilter').data('daterangepicker').startDate).toDate();
        let endDate = moment(jQuery('.datefilter').data('daterangepicker').endDate).toDate();
      
      
        let startDateBuscador = moment(startDate).format('MM/DD/YYYY');
        let endDateBuscador = moment(endDate).format('MM/DD/YYYY');
      
        var drp = jQuery('.datefilter-menu').data('daterangepicker');
                    drp.startDate = moment(startDateBuscador);
                    drp.endDate = moment(endDateBuscador);
                    drp.updateView();
                    drp.updateCalendars();
      
        let startDateBuscadorMenu = moment(startDate).format('DD/MM/YYYY');
        let endDateBuscadorMenu = moment(endDate).format('DD/MM/YYYY');          
      
        jQuery('.datefilter-menu').val(startDateBuscadorMenu+'-'+endDateBuscadorMenu);
        jQuery('.datefilter-menu').removeClass('active');

        if ( window.location.pathname == '/' ){
            // Index (home) page
            EteHiver(temporada);
        
        } else {
            // Other page
            updateCookie('saison', temporada);
        }

        //EteHiver(temporada);
        ////console.log('temporada: '+temporada);
        //updateCookie('saison', temporada);
        ////console.log('temporada: '+temporada);

    });

        },
        open: function (event, ui) {
            //console.log('open');
            jQuery('.Rectangle').css('display', 'block');
            jQuery('.Rectangle').css('opacity', '1');
            jQuery('body').addClass('body-layer');

            if ( jQuery('.autocomplete-image').attr('data-region') == 1 ){

                var bg = jQuery('.autocomplete-image').css('background-image');
                bg = bg.replace('url(','').replace(')','').replace(/\"/gi, "");

                image = bg.split('/icons/');
                var url = image[0];  
                image = image[1];
                image = image.split('.png');
                image = image[0];
                image = image.split('_');
                image = image[0];

                jQuery('.autocomplete-image').css('background-image', 'url('+url+'/icons/'+image+'_b.png)');   
                jQuery('.autocomplete-image-menu').css('background-image', 'url('+url+'/icons/'+image+'_b.png)');   
            }
        },
        close: function (event, ui) {
            //console.log('close');
            jQuery('.Rectangle').css('display', 'block');
            jQuery('.Rectangle').css('opacity', '0');
            jQuery('body').removeClass('body-layer');

            if ( jQuery('.autocomplete-image').attr('data-region') == 1 ){

                var bg = jQuery('.autocomplete-image').css('background-image');
                bg = bg.replace('url(','').replace(')','').replace(/\"/gi, "");

                image = bg.split('/icons/');
                var url = image[0];  
                image = image[1];
                image = image.split('.png');
                image = image[0];
                image = image.split('_');
                image = image[0];

                jQuery('.autocomplete-image').css('background-image', 'url('+url+'/icons/'+image+'.png)');   
            }


        }
    }).focus(function () {

        if ( jQuery('.autocomplete-image').attr('data-region') == 1 ){

            var bg = jQuery('.autocomplete-image').css('background-image');
            bg = bg.replace('url(','').replace(')','').replace(/\"/gi, "");

            image = bg.split('/icons/');
            var url = image[0];  
            image = image[1];
            image = image.split('.png');
            image = image[0];
            image = image.split('_');
            image = image[0];

            jQuery('.autocomplete-image').css('background-image', 'url('+url+'/icons/'+image+'_b.png)');            
        }

        jQuery(this).autocomplete("search", "");
        console.log(jQuery('.autocomplete-image').attr('data-region'));

        


    }).val(autocomplitNameInicial).data("ui-autocomplete")._renderItem = function (ul, item) {

        var icon = item.icon;
        if(icon == ''){
            icon = 'https://sitenew.preprod.mmv.resalys.com/images/icons/alpes_b.png';
        }

        var ville = item.villeNom;
        ////console.log(ville);
        if( typeof ville === null || typeof ville === 'object'){
            ville = '';
        }

        var classColor = '';

        switch (item.gamma) {
            case '2':  //Si es hotel
                classColor = 'bluetext-heberg';
                break;
            
            case '1': //Si es residence
                classColor = 'green-type-heberg';
                break;

            case '3': //Si es residence partenainers
                classColor = 'green-type-heberg';
                break;   

            default:
                classColor = 'bluetext-heberg';
                break;
        }

        var category = item.category;
        category = category.replace(/[*]/g,'');

        var commune = item.commune;
        commune = commune.replace(/[*]/g,'');

    

        //CALCULAMOS SI EL VALUE DEL HIDDEN ES DE UNA REGION PARA AÑADIR 
        //LA CLASE AL CREAR EL LISTADO PARA QUE SEA VISIBLE O NO COMPARANDOLO CON LA REGION PADRE DEL ETABLESIMENT
        
        
         if(jQuery('.autocompleteID').length>0){
            var cadenaReg = jQuery('.autocompleteID').attr('value');
        }
        //alert(jQuery('.autocompleteID-menu').length);
        if(jQuery('.autocompleteID-menu').length>0){
            var cadenaReg = jQuery('.autocompleteID-menu').attr('value');
            //console.log(cadenaReg);
        }
        
        var classNoVisible= '';
        if(cadenaReg.indexOf("region") > -1){
            var fstChar = cadenaReg.charAt(0);
            //console.log(fstChar);

            if(fstChar!=item.regionpadre && fstChar!=0 ){
                classNoVisible= 'novisible';
            }
            
        }else{

          //CUANDO ENTRAMOS EN RESULTS BUSQUEDA YA QUE NO TENEMOS STRING REGION EN EL VALUE DEL HIDDEN
          if(jQuery('body.etablessiments').length>0){
            var fstChar = cadenaReg.charAt(0);
            //console.log(fstChar);

                if(fstChar!=item.regionpadre && fstChar!=0 ){
                    classNoVisible= 'novisible';
                }
           }
        }



        
        
        //console.log(item.regionpadre);
        return jQuery('<li class="reg'+item.regionpadre+' '+classNoVisible+'"></li>')
        .data("item.autocomplete", item)
        .append("<div class='auto-box'><img class='imgEtabAutocomplete' src='../"+icon+"'/><div><span data-codecrm = '"+ item.codeCRM +"' data-value = '" + item.value + "' class='result-establec'>" + item.label + "</span><span class="+classColor+">" + category + "</span><br><span class='localidad-etab1'>" + commune + "</span><span class='localidad-etab'>" + ville + "</span></div></div>")
        .appendTo(ul); 
    };
}

/* *******************************************************************
 Descripción: Función que construye el autocomplete
              de alojamientos del menu
******************************************************************** */
var alias;
var iconEtab;
function initializeAutocompleteMenu() {

    // SIESTAMOS EN PARTENAIRES Y HEMOS SELECCIONADO MER DEBEREMOS CRAGAR PARTOUT
    var partenairesServiceId = getCookie('partner_service_id');
    var autocomplitNameInicial = 'Alpes';
    if(partenairesServiceId == 4){
        autocomplitNameInicial = 'Partout';
    }
    console.log(autocomplitNameInicial);

    
    var autocompleteHome = jQuery(".autocomplete-menu");
    var autocompleteMenu = jQuery(".autocomplete");
    var position = { my: "left top", at: "left bottom"};
    if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
        // Si es movil
        position = { my : "right bottom", at: "right top" };
    }
    autocompleteHome.autocomplete({
        minLength: 0,
        position: position,
        source: function (request, response) {
            var search = request.term;
            var allHebergements = getAllHebergements(search, fechasDatepicker);
            var countRegionesPadre = 1;
            var regionesPadre = getAllRegionesPadre();
           // //console.log(hostName);

            if(  typeof regionesPadre !== 'undefined' && regionesPadre.length > 0){

                jQuery(".Rectangle-menu").html('<button title="region" value="0" onclick="clickRegionPadrebtn.call(this, event)" class="btnRegionRecher"><img class="imgRegionRecher" src="'+hostName+'/images/icons/partout_b.png"><span>Partout</span></button>');

                jQuery.each(regionesPadre, function( index, value ) {
                    jQuery(".Rectangle-menu").append('<button title="region" value="'+value.id+'" onclick="clickRegionPadrebtn.call(this, event)" class="btnRegionRecher"><img class="imgRegionRecher" src="'+value.icon+'"><span>'+value.nom+'</span></button>');
                    countRegionesPadre++;
                });
            } 

            response(allHebergements);
        },
        select: function (event, ui) {
            event.preventDefault();
 
            console.log('selecciono un valor en el autocomplete');

            //Ponemos el valor seleccionado en el input de los resultados y del menu
            autocompleteHome.val(ui.item.label);
            autocompleteMenu.val(ui.item.label);

            var icon = ui.item.icon;
            if(icon == ''){
                icon = 'https://sitenew.preprod.mmv.resalys.com/images/icons/alpes.png';
            }
            iconEtab = icon;


            if ( jQuery('.autocomplete-image').attr('data-region') == 1 ){

                var bg = jQuery('.autocomplete-image-menu').css('background-image');
                bg = bg.replace('url(','').replace(')','').replace(/\"/gi, "");

                image = bg.split('/icons/');
                var url = image[0];  
                image = image[1];
                image = image.split('.png');
                image = image[0];
                image = image.split('_');
                image = image[0];

               //console.log('estoy en el select');
               // //console.log(url+'/icons/'+image+'_b.png');
                jQuery('.autocomplete-image-menu').css('background-image', 'url('+url+'/icons/'+image+'_b.png)');

            }

        
            //Ponemos la imagen seleccionada en el input de los resultados y del menu
            //jQuery('.autocomplete-image').css('background-image', 'url('+icon+')');
            //jQuery('.autocomplete-image-menu').css('background-image', 'url('+icon+')');

            //AL SELECCIONAR ETABLESSIMENT PONDREMOS ICONO MAR O MONTAÑA YA QUE NO TENEMOS LOS ICONOS EN BLANCO
            //console.log('entra aqui 761')
            if( ui.item.ItmExtTypeDestination== 'Montag'){
                jQuery('.autocomplete-image').css('background-image', 'url("images/icons/alpes.png")');  
                jQuery('.autocomplete-image-menu').css('background-image', 'url("images/icons/alpes.png")');  
            }else{
                jQuery('.autocomplete-image').css('background-image', 'url("images/icons/mer.png")');
                jQuery('.autocomplete-image-menu').css('background-image', 'url("images/icons/mer.png")');
            }

            jQuery('.autocomplete-image').attr('data-region', 0);

            jQuery(".autocompleteID-menu").attr('value',ui.item.value+'__'+'camping');
            jQuery(".autocompleteID").attr('value',ui.item.value+'__'+'camping');

            alias = getAllHebergementAliasByID(ui.item.value);

            //CGOS y MACIF
            var mmvLang = jQuery('html').attr('lang');
            switch (mmvLang) {
                case 'fr-ca':
                    mmvLang = 'macif/';
                    break;
                case 'bs-ba':
                    mmvLang = 'cgos/';
                    break;
                case 'en':
                    mmvLang = 'en/';
                    break;
                default:
                    mmvLang = '';
                    break;
            }

            jQuery('.frmRechercher').attr('action', mmvLang+alias.replace(/['"]+/g, ''));
            jQuery('.frmRechercherMenu').attr('action', mmvLang+alias.replace(/['"]+/g, ''));

            ////console.log('estoy seleccionando un etab');

            //jQuery('.reservez-menu').addClass('etab');

            updateDatepicker(ui.item.codeCRM);

            //Si Selecciono una fecha en el datepicker, cargo los tipos de alojamiento con disponibilidades en las fechas seleccionadas    
            jQuery('.datefilter').on('apply.daterangepicker', function (ev, picker) {

                console.log('cambio fechas');

                jQuery(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                var datesWithDispo = [{ 'dateFirstDispo': picker.startDate.format('YYYY-MM-DD'), 'dateLastDispo': picker.endDate.format('YYYY-MM-DD') }];
                fechasDatepicker = datesWithDispo;
                getTypeHebergements(datesWithDispo);
                //Cargo todos los establecimientos con fecha de apertura mayor o igual a la fecha de inicio
                //y fecha de cierre menor o igual a la fecha de fin
                getAllHebergements('', datesWithDispo);
                //En dependencia de las fechas seleccionadas cambio la web a invierno o verano.

                var temporada = getSeasonByDate(picker.startDate.format('YYYY-MM-DD'), picker.endDate.format('YYYY-MM-DD'));

                var date = fechasDatepicker[0];

                var beginD = date.dateFirstDispo.replace(new RegExp('-', 'g'),"/");
                var EndD = date.dateLastDispo.replace(new RegExp('-', 'g'),"/");
            
                var arrBegin = beginD.split('/');
                strBegin = arrBegin[2]+'/'+arrBegin[1]+'/'+arrBegin[0];
            
                var arrEnd = EndD.split('/');
                strEnd = arrEnd[2]+'/'+arrEnd[1]+'/'+arrEnd[0];
            
                // Si cambio la fecha en el buscador de resultados tambien lo cambio en el buscador del menu
                // dateBegin y dateEnd (MM/DD/YYYY)
                let startDate = moment(jQuery('.datefilter').data('daterangepicker').startDate).toDate();
                let endDate = moment(jQuery('.datefilter').data('daterangepicker').endDate).toDate();
            
            
                let startDateBuscador = moment(startDate).format('MM/DD/YYYY');
                let endDateBuscador = moment(endDate).format('MM/DD/YYYY');
            
                var drp = jQuery('.datefilter-menu').data('daterangepicker');
                            drp.startDate = moment(startDateBuscador);
                            drp.endDate = moment(endDateBuscador);
                            drp.updateView();
                            drp.updateCalendars();
            
                let startDateBuscadorMenu = moment(startDate).format('DD/MM/YYYY');
                let endDateBuscadorMenu = moment(endDate).format('DD/MM/YYYY');          
            
                jQuery('.datefilter-menu').val(startDateBuscadorMenu+'-'+endDateBuscadorMenu);
                jQuery('.datefilter-menu').removeClass('active');

                if ( window.location.pathname == '/' ){
                    // Index (home) page
                    EteHiver(temporada);
                
                } else {
                    // Other page
                    updateCookie('saison', temporada);
                }

                //EteHiver(temporada);
                ////console.log('temporada: '+temporada);
                //updateCookie('saison', temporada);
                ////console.log('temporada: '+temporada);

            });
           

        },
        open: function (event, ui) {
            console.log('se abre el autocomplete');

            //jQuery('.autocomplete-menu').blur();

            //console.log('open ficha');
            jQuery('.Rectangle-menu').css('display', 'block');
            jQuery('.Rectangle-menu').css('opacity', '1');
            
        },
        close: function (event, ui) {
            jQuery('.autocomplete-menu').css('z-index', '9999');
            ////console.log('close');
            jQuery('.Rectangle-menu').css('display', 'block');
            jQuery('.Rectangle-menu').css('opacity', '0');

        }
    }).focus(function () {
        console.log('estoy focus?');
        jQuery('.autocomplete-menu').blur();
        //AÑADIMOS BODY E INPUT autocomplete PARA QUE NO SE MUESTREN COMO MARCADOS

        //jQuery('body').removeClass('body-layer');

        if ( jQuery('.autocomplete-image').attr('data-region') == 1 ){

            var bg = jQuery('.autocomplete-image-menu').css('background-image');
            bg = bg.replace('url(','').replace(')','').replace(/\"/gi, "");

            image = bg.split('/icons/');
            var url = image[0];  
            image = image[1];
            image = image.split('.png');
            image = image[0];
            image = image.split('_');
            image = image[0];

            ////console.log('estoy en el select');
            ////console.log(url+'/icons/'+image+'_b.png');
            jQuery('.autocomplete-image-menu').css('background-image', 'url('+url+'/icons/'+image+'_b.png)');

        }

        jQuery(this).autocomplete("search", "");
    }).val(autocomplitNameInicial).data("ui-autocomplete")._renderItem = function (ul, item) {

        var icon = item.icon;
        if(icon == ''){
            icon = 'https://sitenew.preprod.mmv.resalys.com/images/icons/alpes.png';
        }

        var ville = item.villeNom;
        if( typeof ville === null || typeof ville === 'object'){
            ville = '';
        }

        var classColor = '';

        switch (item.gamma) {
            case '2':  //Si es hotel
                classColor = 'bluetext-heberg';
                break;
            
            case '1': //Si es residence
                classColor = 'green-type-heberg';
                break;

            case '3': //Si es residence partenainers
                classColor = 'green-type-heberg';
                break;   

            default:
                classColor = 'bluetext-heberg';
                break;
        }

        var category = item.category;
        category = category.replace(/[*]/g,'');

        var commune = item.commune;
        commune = commune.replace(/[*]/g,'');

        //CALCULAMOS SI EL VALUE DEL HIDDEN ES DE UNA REGION PARA AÑADIR 
        //LA CLASE AL CREAR EL LISTADO PARA QUE SEA VISIBLE O NO COMPARANDOLO CON LA REGION PADRE DEL ETABLESIMENT
        
        
         if(jQuery('.autocompleteID').length>0){
            var cadenaReg = jQuery('.autocompleteID').attr('value');
        }
        //alert(jQuery('.autocompleteID-menu').length);
        if(jQuery('.autocompleteID-menu').length>0){
            var cadenaReg = jQuery('.autocompleteID-menu').attr('value');
            //alert(cadenaReg);
        }
        
        var classNoVisible= '';
        if(cadenaReg.indexOf("region") > -1){
            var fstChar = cadenaReg.charAt(0);
            //console.log(fstChar);

            if(fstChar!=item.regionpadre && fstChar!=0 ){
                classNoVisible= 'novisible';
            }
            
        }else{

          //CUANDO ENTRAMOS EN RESULTS BUSQUEDA YA QUE NO TENEMOS STRING REGION EN EL VALUE DEL HIDDEN
          if(jQuery('body.etablessiments').length>0){
            var fstChar = cadenaReg.charAt(0);
            //console.log(fstChar);

                if(fstChar!=item.regionpadre && fstChar!=0 ){
                    classNoVisible= 'novisible';
                }
           }
        }


        /*return jQuery('<li class="reg'+item.regionpadre+' '+classNoVisible+'"></li>')
            .data("item.autocomplete", item)
            .append("<img style='width:25px; margin:8px' src='"+icon+"'/><span data-value = '" + item.value + "' class='result-establec'>" + item.label + "</span><span class="+classColor+">" + category + "</span><p class='localidad-etab'>" + ville + "</p>")
            .appendTo(ul);*/
         return jQuery('<li class="reg'+item.regionpadre+' '+classNoVisible+'"></li>')
            .data("item.autocomplete", item)
            .append("<img  class='iconmenures' src='../"+icon+"'/><p data-value = '" + item.value + "' class='result-establec'>" + item.label + "<span class="+classColor+">" + category + "</span></p><p class='localidad-etab112'>" + commune + "<span class='localidad-etab localidad-etab1125'>" + ville + "</span></p>")
            .appendTo(ul);
    };
}


/* *******************************************************************
 Descripción: Función que devuelve todos los alojamientos,
              según criterio de búsqueda y temporada activa.
 Parametros: Caractér buscado, si está vacío se buscan todos
 ******************************************************************* */
function getAllHebergementAliasByID(hebergementID){
    var custom_response;
    // Fetch data
    jQuery.ajax({
        type: 'POST',
        async: false,
        url: 'https://sitenew.preprod.mmv.resalys.com/modules/mod_campings/rechercher/function.php',
        dataType: "text",
        data: { custom_function: 'getAllHebergementAliasByID', id: hebergementID },
        success: function (data) {
            ////console.log(data);
            custom_response = data;
        },
        error: function () {
            //console.log("getAllHebergementAliasByID failed in rechercher.js");
        }
    });
    return custom_response;
}

/* *******************************************************************
    Click en el boton - para saber la region seleccionada
******************************************************************** */

function clickRegionPadrebtn(e){
    e.preventDefault();


    console.log('click en region padre');
    var regionSelected = jQuery(this).find('span').text();
    var regionSelectedID = jQuery(this).val();
    var iconSelected = jQuery(this).find('img').attr('src');
   
    console.log(regionSelected);
    console.log(regionSelectedID);
    console.log(iconSelected);

    jQuery('.autocomplete').val(regionSelected);
    jQuery('.autocomplete-menu').val(regionSelected);
    jQuery(".autocompleteID").attr('value',regionSelectedID+'__'+'region'+'__'+regionSelected);
    jQuery(".autocompleteID-menu").attr('value',regionSelectedID+'__'+'region'+'__'+regionSelected);
    jQuery('.frmRechercher').attr('action', 'results');
    jQuery('.frmRechercherMenu').attr('action', 'results');
    jQuery('.autocomplete-image').css('background-image', 'url('+iconSelected+')');
    jQuery('.autocomplete-image').attr('data-region', 1);
    //jQuery('.autocomplete-image-menu').css('background-image', 'url('+iconSelected+')');

    alias = '';  //pongo el alias vacio, pq las regiones no tienen alias

    var bg = jQuery('.autocomplete-image').css('background-image');
    bg = bg.replace('url(','').replace(')','').replace(/\"/gi, "");

    image = bg.split('/icons/');
    var url = image[0];  
    image = image[1];
    image = image.split('.png');
    image = image[0];
    image = image.split('_');
    image = image[0];
    jQuery('.autocomplete-image').css('background-image', 'url('+url+'/icons/'+image+'_w.png)');



    initializeDatepicker();

        //Si Selecciono una fecha en el datepicker, cargo los tipos de alojamiento con disponibilidades en las fechas seleccionadas    
        jQuery('.datefilter').on('apply.daterangepicker', function (ev, picker) {

            //console.log('cambio fechas');
    
            jQuery(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
            var datesWithDispo = [{ 'dateFirstDispo': picker.startDate.format('YYYY-MM-DD'), 'dateLastDispo': picker.endDate.format('YYYY-MM-DD') }];
            fechasDatepicker = datesWithDispo;
            getTypeHebergements(datesWithDispo);
            //Cargo todos los establecimientos con fecha de apertura mayor o igual a la fecha de inicio
            //y fecha de cierre menor o igual a la fecha de fin
            getAllHebergements('', datesWithDispo);
            //En dependencia de las fechas seleccionadas cambio la web a invierno o verano.
    
            var temporada = getSeasonByDate(picker.startDate.format('YYYY-MM-DD'), picker.endDate.format('YYYY-MM-DD'));
    
            var date = fechasDatepicker[0];
    
            var beginD = date.dateFirstDispo.replace(new RegExp('-', 'g'),"/");
            var EndD = date.dateLastDispo.replace(new RegExp('-', 'g'),"/");
          
            var arrBegin = beginD.split('/');
            strBegin = arrBegin[2]+'/'+arrBegin[1]+'/'+arrBegin[0];
          
            var arrEnd = EndD.split('/');
            strEnd = arrEnd[2]+'/'+arrEnd[1]+'/'+arrEnd[0];
          
            // Si cambio la fecha en el buscador de resultados tambien lo cambio en el buscador del menu
            // dateBegin y dateEnd (MM/DD/YYYY)
            let startDate = moment(jQuery('.datefilter').data('daterangepicker').startDate).toDate();
            let endDate = moment(jQuery('.datefilter').data('daterangepicker').endDate).toDate();
          
          
            let startDateBuscador = moment(startDate).format('MM/DD/YYYY');
            let endDateBuscador = moment(endDate).format('MM/DD/YYYY');
          
            var drp = jQuery('.datefilter-menu').data('daterangepicker');
                        drp.startDate = moment(startDateBuscador);
                        drp.endDate = moment(endDateBuscador);
                        drp.updateView();
                        drp.updateCalendars();
          
            let startDateBuscadorMenu = moment(startDate).format('DD/MM/YYYY');
            let endDateBuscadorMenu = moment(endDate).format('DD/MM/YYYY');          
          
            jQuery('.datefilter-menu').val(startDateBuscadorMenu+'-'+endDateBuscadorMenu);
            jQuery('.datefilter-menu').removeClass('active');
    
            if ( window.location.pathname == '/' ){
                // Index (home) page
                EteHiver(temporada);
            
            } else {
                // Other page
                updateCookie('saison', temporada);
            }
    
            //EteHiver(temporada);
            //console.log('temporada: '+temporada);
            //updateCookie('saison', temporada);
            ////console.log('temporada: '+temporada);
    
        });
}


/* *******************************************************************
 Descripción: Función que devuelve todos los alojamientos,
              según criterio de búsqueda y temporada activa.
 Parametros: Caractér buscado, si está vacío se buscan todos
 ******************************************************************* */
function getAllHebergements(valueToSearch, datefilter) {
    var custom_response;
    var activa = jQuery('.colEteHiver').find('.active');
    var temporada = activa.attr('value');
   // //console.log('O: '+temporada);

    var dateOverture = '';
    var dateFermeture = '';

    //[{ 'dateFirstDispo': picker.startDate.format('YYYY-MM-DD'), 'dateLastDispo': picker.endDate.format('YYYY-MM-DD') }]
    //Si hay fechas seleccionadas
    if(datefilter != ''){
        dateOverture = datefilter[0].dateFirstDispo;
        dateFermeture = datefilter[0].dateLastDispo;
    }

   // //console.log('O: '+dateOverture);

    // Fetch data
    jQuery.ajax({
        type: 'POST',
        async: false,
        url: 'https://sitenew.preprod.mmv.resalys.com/modules/mod_campings/rechercher/function.php',
        dataType: "text",
        data: { custom_function: 'getAllHebergements', search: valueToSearch, temporada: temporada, dateOverture: dateOverture, dateFermeture: dateFermeture  },
        success: function (data) {
            //console.log(data);
            custom_response = JSON.parse(data);
        },
        error: function () {
            //console.log("getAllHebergements failed");
        }
    });
    return custom_response;
}

/* *******************************************************************
 Descripción: Función que devuelve todos los alojamientos,
              según criterio de búsqueda y temporada activa.
 Parametros: Caractér buscado, si está vacío se buscan todos
 ******************************************************************* */
function getAllRegionesPadre() {
    var regionesPadre;
    var activa = jQuery('.colEteHiver').find('.activa');
    var temporada = activa.attr('value');
    // Fetch data
    jQuery.ajax({
        type: 'POST',
        async: false,
        url: 'https://sitenew.preprod.mmv.resalys.com/modules/mod_campings/rechercher/function.php',
        dataType: "text",
        data: { custom_function: 'getNameRegionPadre',  temporada: temporada },
        success: function (data) {
            regionesPadre = JSON.parse(data);
        },
        error: function () {
            //console.log("getAllRegionesPadre failed");
        }
    });
    return regionesPadre;
}



/* *******************************************************************
 Descripción: Función que devuelve todos los alojamientos,
              según tipo de alojamiento seleccionado
 Parametros: Caractér buscado, si está vacío se buscan todos
 ******************************************************************* */
function getAllHebergementsByType(typeEstablishmentID) {
    var custom_response;
    var temporada = getCookie('saison');

    ////console.log('temporada');
    ////console.log(temporada);
    
    // Fetch data
    jQuery.ajax({
        type: 'POST',
        async: false,
        url: 'https://sitenew.preprod.mmv.resalys.com/modules/mod_campings/rechercher/function.php',
        dataType: "text",
        data: { custom_function: 'getAllHebergementsByType', typeEstablishmentID: typeEstablishmentID, temporada: temporada },
        success: function (data) {
            ////console.log('getAllHebergementsByType');
            ////console.log(data);
            custom_response = JSON.parse(data);
        },
        error: function () {
            //console.log("getAllHebergementsByType failed in rechercher.js");
        }
    });
    return custom_response;
}
/* ******************************************************************* */
// Función que aumenta el numero de personas (Buscador Menu)
/* ******************************************************************* */
function increaseValueMenu(element) {

    //valor del campo que estoy incrementando
    var value = parseInt(element.attr('value'),10);
    value = isNaN(value) ? 0 : value;
    value++;

    if( ( (element.attr("data-id") == 'numberE1') || (element.attr("data-id") == 'numberE2') ) && (value > 4) ){

        return;

    }else{

        //Aumento tambien el elemento en el formulario de results o home
        var elementDataId = jQuery(element).attr("data-id");
        var elementResult = jQuery('.block-personas').find("[data-id="+elementDataId+"]");
        var numberElementResult = parseInt(elementResult.attr('value'),10);
        elementResult.attr('value', numberElementResult+1);

        //Numero de adultos
        var adults = jQuery(element).closest('.block-personas-menu').find("[data-id=numberA]");
        var numberAdults = parseInt(adults.attr('value'),10);

        var enfant1 = jQuery(element).closest('.block-personas-menu').find("[data-id=numberE1]");
        var numberEnfant1 = parseInt(enfant1.attr('value'),10);

        var enfant2 = jQuery(element).closest('.block-personas-menu').find("[data-id=numberE2]");
        var numberEnfant2 = parseInt(enfant2.attr('value'),10);

        var babys = jQuery(element).closest('.block-personas-menu').find("[data-id=numberB]");
        var numberBabys = parseInt(babys.attr('value'),10);

        var sumTotal = numberAdults + numberEnfant1 + numberEnfant2 + numberBabys;
    
        
        //No podemos selleccionar mas de 7 adultos
        if ((element.attr("data-id") == 'numberA') && (value >= 8)) {
	        jQuery('.maxPersPermitted-menu').show();
	        jQuery('.maxPersPermitted').show();
	        jQuery('.maxPersPermitted-menu').html('Pour toute réservation supérieure à 7 adultes merci de nous contacter au 04 92 12 65 30');
            jQuery('.maxPersPermitted').html('Pour toute réservation supérieure à 7 adultes merci de nous contacter au 04 92 12 65 30');
	        return;
	    }
	    //La suma de todos los campos no puede ser mayor de 14
        if (sumTotal >= 14) {
            jQuery('.maxPersPermitted-menu').show();
            jQuery('.maxPersPermitted').show();
            jQuery('.maxPersPermitted-menu').html('pour toute reservation superieure à 14 personens merci de nous contacter au 04 92 12 65 30');
            jQuery('.maxPersPermitted').html('pour toute reservation superieure à 14 personens merci de nous contacter au 04 92 12 65 30');

        }
        else {
            element.attr('value', value);
            var newNumPers = sumTotal + 1;
            jQuery('.numberPersonas-menu').text(newNumPers);
            jQuery('.numberPersonas').text(newNumPers);
            
            jQuery('.numberPersonas-menu').val(newNumPers);
            jQuery('.numberPersonas').val(newNumPers);

            jQuery('.avec-qui-partez-vous-menu').text('participants');
            jQuery('.avec-qui-partez-vous').text('participants');
            
            //Agrego la clase para que se vea el espacio del input
            jQuery('.avec-qui-partez-vous-menu').addClass('inputVisible');
            jQuery('.avec-qui-partez-vous').addClass('inputVisible');
        }
    }
}

/* ******************************************************************* */
// Función que aumenta el numero de personas 
/* ******************************************************************* */
function increaseValue(element) {

    //valor del campo que estoy incrementando
    var value = parseInt(element.attr('value'),10);
    value = isNaN(value) ? 0 : value;
    value++;

    if( ( (element.attr("data-id") == 'numberE1') || (element.attr("data-id") == 'numberE2') ) && (value > 4) ){

        return;

    }else{
            //Aumento tambien el elemento en el formulario del menu
            var elementDataId = jQuery(element).attr("data-id");
            var elementResult = jQuery('.block-personas-menu').find("[data-id="+elementDataId+"]");
            var numberElementResult = parseInt(elementResult.attr('value'),10);
            elementResult.attr('value', numberElementResult+1);

            //Numero de adultos
            var adults = jQuery(element).closest('.block-personas').find("[data-id=numberA]");
            var numberAdults = parseInt(adults.attr('value'),10);

            var enfant1 = jQuery(element).closest('.block-personas').find("[data-id=numberE1]");
            var numberEnfant1 = parseInt(enfant1.attr('value'),10);

            var enfant2 = jQuery(element).closest('.block-personas').find("[data-id=numberE2]");
            var numberEnfant2 = parseInt(enfant2.attr('value'),10);

            var babys = jQuery(element).closest('.block-personas').find("[data-id=numberB]");
            var numberBabys = parseInt(babys.attr('value'),10);

            var sumTotal = numberAdults + numberEnfant1 + numberEnfant2 + numberBabys;
        
        	//No podemos selleccionar mas de 7 adultos
        	if ((element.attr("data-id") == 'numberA') && (value >= 8)) {
	        jQuery('.maxPersPermitted-menu').show();
	        jQuery('.maxPersPermitted').show();
	        jQuery('.maxPersPermitted-menu').html('Pour toute réservation supérieure à 7 adultes merci de nous contacter au 04 92 12 65 30');
            jQuery('.maxPersPermitted').html('Pour toute réservation supérieure à 7 adultes merci de nous contacter au 04 92 12 65 30');
	        return;
		    }
		    //La suma de todos los campos no puede ser mayor de 14
	        if (sumTotal >= 14) {
	            jQuery('.maxPersPermitted-menu').show();
	            jQuery('.maxPersPermitted').show();
	            jQuery('.maxPersPermitted-menu').html('pour toute reservation superieure à 14 personens merci de nous contacter au 04 92 12 65 30');
	            jQuery('.maxPersPermitted').html('pour toute reservation superieure à 14 personens merci de nous contacter au 04 92 12 65 30');

	        }
            else {

                element.attr('value', value);
                var newNumPers = sumTotal + 1;

                jQuery('.numberPersonas').text(newNumPers);
                jQuery('.numberPersonas-menu').text(newNumPers);
                
                jQuery('.numberPersonas').val(newNumPers);
                jQuery('.numberPersonas-menu').val(newNumPers);
                
                jQuery('.avec-qui-partez-vous').text('participants');
                jQuery('.avec-qui-partez-vous-menu').text('participants');
                
                //Agrego la clase para que se vea el espacio del input
                jQuery('.avec-qui-partez-vous').addClass('inputVisible');
                jQuery('.avec-qui-partez-vous-menu').addClass('inputVisible');
            }
    }


}
/******************************************************************* */
// Función que disminuye el numero de personas (Buscador Menu)
/******************************************************************* */
function decreaseValueMenu(element) {
    //valor del campo que estoy decrementando
    var value = parseInt(element.attr('value'),10);
    value = isNaN(value) ? 0 : value;
    value--;

    
    if( ( (element.attr("data-id") == 'numberA') ) && (value < 1) ){

        return;

    }else{

    //Numero de adultos
    var adults = jQuery(element).closest('.block-personas-menu').find("[data-id=numberA]");
    var numberAdults = parseInt(adults.attr('value'),10);

    var enfant1 = jQuery(element).closest('.block-personas-menu').find("[data-id=numberE1]");
    var numberEnfant1 = parseInt(enfant1.attr('value'),10);

    var enfant2 = jQuery(element).closest('.block-personas-menu').find("[data-id=numberE2]");
    var numberEnfant2 = parseInt(enfant2.attr('value'),10);

    var babys = jQuery(element).closest('.block-personas-menu').find("[data-id=numberB]");
    var numberBabys = parseInt(babys.attr('value'),10);

    var sumTotal = (numberAdults + numberEnfant1 + numberEnfant2 + numberBabys) - 1;

    ////console.log(sumTotal);

     if (sumTotal >= 0) {

        jQuery('.maxPersPermitted-menu').hide();
        jQuery('.maxPersPermitted').hide();
        jQuery('.maxPersPermitted-menu').html('');
        jQuery('.maxPersPermitted').html('');

        if (value >= 0) {

            //Disminuyo tambien el elemento en el formulario del results o home
            var elementDataId = jQuery(element).attr("data-id");
            var elementResult = jQuery('.block-personas').find("[data-id="+elementDataId+"]");
            var numberElementResult = parseInt(elementResult.attr('value'),10);
            elementResult.attr('value', numberElementResult-1);

            element.attr('value', value); 

            jQuery('.numberPersonas-menu').text(sumTotal);
            jQuery('.numberPersonas').text(sumTotal);

            jQuery('.numberPersonas-menu').val(sumTotal);
            jQuery('.numberPersonas').val(sumTotal);

            jQuery('.avec-qui-partez-vous-menu').text('participants');
            jQuery('.avec-qui-partez-vous').text('participants');

            //Agrego la clase para que se vea el espacio del input
            jQuery('.avec-qui-partez-vous-menu').addClass('inputVisible');
            jQuery('.avec-qui-partez-vous').addClass('inputVisible');


            if ((sumTotal == 0) && (value == 0)) {
                jQuery('.numberPersonas-menu').text('');
                jQuery('.numberPersonas').text('');

                jQuery('.numberPersonas-menu').val('');
                jQuery('.numberPersonas').val('');

                jQuery('.avec-qui-partez-vous-menu').text('Avec qui partez-vous ?');
                jQuery('.avec-qui-partez-vous').text('Avec qui partez-vous ?');
                
                //Elimino la clase para que se vea el espacio del input
                jQuery('.avec-qui-partez-vous-menu').removeClass('inputVisible');
                jQuery('.avec-qui-partez-vous').removeClass('inputVisible');



            }

        }
    }
    }
}
/******************************************************************* */
// Función que disminuye el numero de personas 
/******************************************************************* */
function decreaseValue(element) {
    //valor del campo que estoy decrementando
    var value = parseInt(element.attr('value'),10);
    value = isNaN(value) ? 0 : value;
    value--;


    if( ( (element.attr("data-id") == 'numberA') ) && (value < 1) ){

        return;

    }else{
    //Numero de adultos
    var adults = jQuery(element).closest('.block-personas').find("[data-id=numberA]");
    var numberAdults = parseInt(adults.attr('value'),10);

    var enfant1 = jQuery(element).closest('.block-personas').find("[data-id=numberE1]");
    var numberEnfant1 = parseInt(enfant1.attr('value'),10);

    var enfant2 = jQuery(element).closest('.block-personas').find("[data-id=numberE2]");
    var numberEnfant2 = parseInt(enfant2.attr('value'),10);

    var babys = jQuery(element).closest('.block-personas').find("[data-id=numberB]");
    var numberBabys = parseInt(babys.attr('value'),10);

    var sumTotal = (numberAdults + numberEnfant1 + numberEnfant2 + numberBabys) - 1;

    ////console.log(sumTotal);

     if (sumTotal >= 0) {

        jQuery('.maxPersPermitted').hide();
        jQuery('.maxPersPermitted-menu').hide();
        jQuery('.maxPersPermitted-menu').html('');
        jQuery('.maxPersPermitted').html('');

        if (value >= 0) {

            element.attr('value', value); 

            //Disminuyo tambien el elemento en el formulario del results o home
            var elementDataId = jQuery(element).attr("data-id");
            var elementResult = jQuery('.block-personas-menu').find("[data-id="+elementDataId+"]");
            var numberElementResult = parseInt(elementResult.attr('value'),10);
            elementResult.attr('value', numberElementResult-1);

            jQuery('.numberPersonas').text(sumTotal);
            jQuery('.numberPersonas-menu').text(sumTotal);

            jQuery('.numberPersonas').val(sumTotal);
            jQuery('.numberPersonas-menu').val(sumTotal);
            
            jQuery('.avec-qui-partez-vous').text('participants');
            jQuery('.avec-qui-partez-vous-menu').text('participants');

            //Agrego la clase para que se vea el espacio del input
            jQuery('.avec-qui-partez-vous').addClass('inputVisible');
            jQuery('.avec-qui-partez-vous-menu').addClass('inputVisible');


            if ((sumTotal == 0) && (value == 0)) {
                jQuery('.numberPersonas').text('');
                jQuery('.numberPersonas-menu').text('');

                jQuery('.numberPersonas').val('');
                jQuery('.numberPersonas-menu').val('');

                jQuery('.avec-qui-partez-vous').text('Avec qui partez-vous ?');
                jQuery('.avec-qui-partez-vous-menu').text('Avec qui partez-vous ?');

                //Elimino la clase para que se vea el espacio del input
                jQuery('.avec-qui-partez-vous').removeClass('inputVisible');
                jQuery('.avec-qui-partez-vous-menu').removeClass('inputVisible');

                

            }

        }
    }
    }
}
/******************************************************************* */
// Funcion que obtiene las temporadas activas 
/******************************************************************* */
function getActiveSeasons() {
    var datesSeason = [];
    jQuery.ajax({
        type: 'POST',
        async: false,
        url: 'https://sitenew.preprod.mmv.resalys.com/modules/mod_campings/rechercher/function.php',
        dataType: "text",
        data: { custom_function: 'getGeneralSeason' },
        success: function (response) {
            datesSeason = JSON.parse(response);
        },
        error: function () {
            //console.log("getActiveSeasons failed in rechercher.js");
        }
    });
    return datesSeason;
}

/******************************************************************* */
// Funcion que obtiene las fechas de apertura y cierre de un hegerb
/******************************************************************* */
function getSeasonByHeberg(codeCRM) {
    var datesSeason = [];
    jQuery.ajax({
        type: 'POST',
        async: false,
        url: 'https://sitenew.preprod.mmv.resalys.com/modules/mod_campings/rechercher/function.php',
        dataType: "text",
        data: { custom_function: 'getSeasonByHeberg', 'codeCRM' : codeCRM },
        success: function (response) {
            datesSeason = JSON.parse(response);
        },
        error: function () {
            //console.log("getActiveSeasons failed in rechercher.js");
        }
    });
    return datesSeason;
}

/******************************************************************* */
// Funcion que obtiene las temporadas activas 
/******************************************************************* */
function getSeasonByDate(begin, end) {
    var season = '';
    jQuery.ajax({
        type: 'POST',
        async: false,
        url: 'https://sitenew.preprod.mmv.resalys.com/modules/mod_campings/rechercher/function.php',
        dataType: "text",
        data: { custom_function: 'getSeasonByDate', begin: begin, end: end },
        success: function (response) {

            season = JSON.parse(response);

        },
        error: function () {
            //console.log("getSeasonByDate failed in rechercher.js");
        }
    });
    return season;
}
/******************************************************************* */
// Funcion inicializa el datepicker de acuerdo a las temporadas activas
/******************************************************************* */
function initializeDatepicker() {

    var datesSeason = getActiveSeasons();
   // //console.log(datesSeason);

    //Calculamos el inicio de la temporada con disponibilidad
    var arrStartSeason = datesSeason.map(function (date) {
        return moment(date.dateFirstDispo, 'YYYY-MM-DD');
    });
    var dateFirstDispo = moment.min(arrStartSeason);
    ////console.log(dateFirstDispo);

    //Calculamos el final de la temporada con disponibilidad
    var arrEndSeason = datesSeason.map(function (date) {
        return moment(date.dateLastDispo, 'YYYY-MM-DD');
    });

    var dateLastDispo = moment.max(arrEndSeason);
   ////console.log(dateLastDispo);

    var fr_daterangepicker = {
        "direction": "ltr",
        "format": "DD/MM/YYYY",
        "separator": " - ",
        "applyLabel": "Appliquer",
        "cancelLabel": "Annuler",
        "fromLabel": "De",
        "toLabel": "A",
        "customRangeLabel": "Coutume",
        "daysOfWeek": [
            "dim",
            "lun",
            "mar",
            "mer",
            "jeu",
            "ven",
            "sam"
        ],
        "monthNames": [
            "Janvier",
            "Février",
            "Mars",
            "Avril",
            "Mai",
            "Juin",
            "Juillet",
            "Aout",
            "Septembre",
            "Octobre",
            "Novembre",
            "Décembre"
        ],
        "firstDay": 1
    };


    var today = new Date();
    var todayD = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate(); 
    var dateToday = moment(todayD, 'YYYY-MM-DD');
    
    var initDate = dateFirstDispo;

    //Comparo si la fecha de apertura de sesion es menor que la de hoy
    //Si es menor, el calendario se inicializa en la fecha de hoy
    var isAfter = moment(dateFirstDispo).isAfter(todayD);

    if(!isAfter){
        initDate = dateToday;
    }


    //Inicializo el datepicker
    jQuery('input[name="datefilter"]').daterangepicker({
        autoUpdateInput: false,
        startDate: initDate,
        endDate: dateLastDispo,
        minDate: initDate,
        maxDate: dateLastDispo,
        autoApply: true,
        maxSpan: {
            days: 21
        },
        locale: fr_daterangepicker,
        isInvalidDate: function (date) {

            //Se calculan los rangos de fecha, entre la fecha de inicio de cada temporada y la disponibilidad para la fecha de inicio de la temporada 
           
        }
    });

    //Inicializo el datepicker del MENU
    jQuery('input[name="datefilter-menu"]').daterangepicker({
        autoUpdateInput: false,
        startDate: initDate,
        endDate: dateLastDispo,
        minDate: initDate,
        maxDate: dateLastDispo,
        autoApply: true,
        maxSpan: {
            days: 21
        },
        locale: fr_daterangepicker,
        isInvalidDate: function (date) {
            //Se calculan los rangos de fecha, entre la fecha de inicio de cada temporada y la disponibilidad para la fecha de inicio de la temporada 
            var unavailableDates = new Array();
            for (var index = 0; index < datesSeason.length; index++) {
                //Fecha de inicio de temporada
                var dateBeginSeason = moment(datesSeason[index].dateBegin, 'YYYY-MM-DD');
                //Fecha de primera disponibilidad de la temporada
                var dateFirstDispo_1 = moment(datesSeason[index].dateFirstDispo, 'YYYY-MM-DD');
                //Fecha de fin de temporada
                var dateEndSeason = moment(datesSeason[index].dateEnd, 'YYYY-MM-DD');
                //Fecha de ultima disponibilidad de la temporada
                var dateLastDispo_1 = moment(datesSeason[index].dateLastDispo, 'YYYY-MM-DD');
                unavailableDates.push({ 'start': dateBeginSeason, 'end': dateFirstDispo_1 });
                unavailableDates.push({ 'start': dateEndSeason, 'end': dateLastDispo_1 });
            }
            return unavailableDates.reduce(function (bool, range) {
                return bool || (date >= range.start && date <= range.end);
            }, false);
        }
    });
}





function updateDatepicker(codeCRM){

    jQuery('.datefilter').data('daterangepicker').remove();
    

    var seasonbyH = getSeasonByHeberg(codeCRM);

    //Calculamos el inicio de la temporada con disponibilidad
    var arrStartSeason = seasonbyH.map(function (date) {
        return moment(date.ouverture, 'YYYY-MM-DD');
    });

    var ouverture = moment.min(arrStartSeason);
    ////console.log(dateFirstDispo);
    
    //Calculamos el final de la temporada con disponibilidad
    var arrEndSeason = seasonbyH.map(function (date) {
        return moment(date.fermeture, 'YYYY-MM-DD');
    });
    
    var fermeture = moment.max(arrEndSeason);
    ////console.log(dateLastDispo);
    
    var fr_daterangepicker = {
        "direction": "ltr",
        "format": "DD/MM/YYYY",
        "separator": " - ",
        "applyLabel": "Appliquer",
        "cancelLabel": "Annuler",
        "fromLabel": "De",
        "toLabel": "A",
        "customRangeLabel": "Coutume",
        "daysOfWeek": [
            "dim",
            "lun",
            "mar",
            "mer",
            "jeu",
            "ven",
            "sam"
        ],
        "monthNames": [
            "Janvier",
            "Février",
            "Mars",
            "Avril",
            "Mai",
            "Juin",
            "Juillet",
            "Aout",
            "Septembre",
            "Octobre",
            "Novembre",
            "Décembre"
        ],
        "firstDay": 1
    };
    
    
    var today = new Date();
    var todayD = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate(); 
    var dateToday = moment(todayD, 'YYYY-MM-DD');
        
    var initDate = ouverture;
    
    //Comparo si la fecha de apertura de sesion es menor que la de hoy
    //Si es menor, el calendario se inicializa en la fecha de hoy
    var isAfter = moment(ouverture).isAfter(todayD);

    if(!isAfter){
        initDate = dateToday;
    }
    
    
    //Inicializo el datepicker
    jQuery('.datefilter').daterangepicker({
        autoUpdateInput: false,
        startDate: initDate,
        endDate: fermeture,
        minDate: initDate,
        maxDate: fermeture,
        autoApply: true,
        maxSpan: {
            days: 21
        },
        locale: fr_daterangepicker,
        isInvalidDate: function (date) {

            //Se calculan los rangos de fecha, entre la fecha de inicio de cada temporada y la disponibilidad para la fecha de inicio de la temporada 
            var unavailableDates = new Array();
            for (var index = 1; index < seasonbyH.length; index++) {

                var dateBeginSeason = moment(seasonbyH[index-1].fermeture, 'YYYY-MM-DD');

                var dateEndSeason = moment(seasonbyH[index].ouverture, 'YYYY-MM-DD');

                unavailableDates.push({ 'start': dateBeginSeason, 'end': dateEndSeason });
            }

            return unavailableDates.reduce(function (bool, range) {
                return bool || (date >= range.start && date <= range.end);
            }, false);
        }
    });

    //Inicializo el datepicker
    jQuery('input[name="datefilter-menu"]').daterangepicker({
        autoUpdateInput: false,
        startDate: initDate,
        endDate: fermeture,
        minDate: initDate,
        maxDate: fermeture,
        autoApply: true,
        maxSpan: {
            days: 21
        },
        locale: fr_daterangepicker,
        isInvalidDate: function (date) {

            //Se calculan los rangos de fecha, entre la fecha de inicio de cada temporada y la disponibilidad para la fecha de inicio de la temporada 
            var unavailableDates = new Array();
            for (var index = 1; index < seasonbyH.length; index++) {

                var dateBeginSeason = moment(seasonbyH[index-1].fermeture, 'YYYY-MM-DD');

                var dateEndSeason = moment(seasonbyH[index].ouverture, 'YYYY-MM-DD');

                unavailableDates.push({ 'start': dateBeginSeason, 'end': dateEndSeason });

            }

            return unavailableDates.reduce(function (bool, range) {
                return bool || (date >= range.start && date <= range.end);
            }, false);
        }
    });

    //console.log(seasonbyH);
    
}





/******************************************************************* */
// Funcion que obtiene los tipos de alojamientos según 
// fechas de temporada (por defecto) o seleccionadas
/******************************************************************* */
function getTypeHebergements(datesWithDispo) {
    jQuery.ajax({
        type: 'POST',
        async: false,
        url: 'https://sitenew.preprod.mmv.resalys.com/modules/mod_campings/rechercher/function.php',
        dataType: "text",
        data: { custom_function: 'getTypeHebergement', datesWithDispo: datesWithDispo },
        success: function (response) {

            var typeHeberg = JSON.parse(response);
            //jQuery('.sel_typeEstablishment').html('<option selected="true" value="0"> Tout type d’hébergement </option>');
            jQuery('.sel_typeEstablishment').html('<option selected="true" value="0"> Hôtels & Résidences </option>');
            jQuery.each(typeHeberg, function (key, value) {
                jQuery('.sel_typeEstablishment').append('<option value="' + this.id + '"> ' + this.nom + ' </option>');
            });
        },
        error: function () {
            //console.log("getTypeHebergements failed in rechercher.js");
        }
    });

    constructLiHebergements();
}



function constructLiHebergements(){

    jQuery('.sel_typeEstablishment').each(function(){

        var $this = jQuery(this), numberOfOptions = jQuery(this).children('option').length;
      
        $this.addClass('select-hidden'); 

        if( $this.parent().is('.select') ){
            $this.remove();
        }

        $this.wrap('<div class="select"></div>');
        $this.after('<div class="select-styled blueBox"></div>');

        var $styledSelect = $this.next('div.select-styled');
        $styledSelect.text($this.children('option').eq(0).text());


        var $list = jQuery('<ul />', {
            'class': 'select-options'
        }).insertAfter($styledSelect);
        
        for (var i = 0; i < numberOfOptions; i++) {
            jQuery('<li />', {
                text: $this.children('option').eq(i).text(),
                rel: $this.children('option').eq(i).val()
            }).appendTo($list);
        }
    
        var $listItems = $list.children('li');

        var $inputEstablec = jQuery('input.typeEstablishmentID');

        

        $styledSelect.click(function(e) {
            
            e.stopPropagation();
            

            jQuery('div.select-styled.active').not(this).each(function(){
                jQuery(this).removeClass('active').next('ul.select-options').hide();
                jQuery('body').removeClass('body-layer');
             });

            jQuery(this).toggleClass('active').next('ul.select-options').toggle();

            //Añado la capa en todo el body
            //console.log('Añado la capa en todo el body');
            $body.addClass('body-layer');
            //$body.toggleClass('body-layer');

            var checkHeb = jQuery(this).text();

            // $listItems.each(function( index ) {

            //     var textLi = jQuery( this ).text();
            //     jQuery(this).css('padding-left','35px');

            //     if( (checkHeb ==  textLi) && (jQuery(this).find('i').length == 0)  ){
            //         jQuery( this ).prepend('<i class="fas fa-check" style="padding-left: 15px;margin-right: 5px;"></i>');
            //         //jQuery(this).css('padding-left','0px');
            //     }else if( (checkHeb !=  textLi) && (jQuery(this).find('i').length > 0))  {
            //         jQuery( this ).find('i').remove();
            //     }
                
            //   });


        });

        var $styledSelectMenu = document.getElementsByClassName("select-styled-menu");

     
        $listItems.click(function(e) {
            e.stopPropagation();
            $styledSelect.text(jQuery(this).text()).removeClass('active');
            $styledSelect.attr('value', jQuery(this).attr('rel') );

            jQuery($styledSelectMenu[0]).text( jQuery(this).text() );
            jQuery($styledSelectMenu[0]).attr( 'value', jQuery(this).attr('rel') );

            $inputEstablec.attr('value',jQuery(this).attr('rel')+'__'+jQuery(this).text());
            $this.val(jQuery(this).attr('rel'));
            $list.hide();
            
            //Elmino la capa en todo el body
            $body.removeClass('body-layer');  // este esta ok

            //ajax cambiar lista en el autocomplete cuando se selecciona un tipo de alojamiento
            var typeEstablishmentID = jQuery(this).attr('rel');
    
            //Cambiar Autocomplete de la home o results
            var autocompleteHome = jQuery(".autocomplete");
            autocompleteHome.autocomplete('option', 'source', function(request, response){
    
    
                //Lista de Regiones
                var countRegionesPadre = 1;
                var regionesPadre = getAllRegionesPadre();
                
    
                if(  typeof regionesPadre !== 'undefined' && regionesPadre.length > 0){
    
                    jQuery(".Rectangle").html('<button title="region" value="0" class="btnRegionRecher" onclick="clickRegionPadrebtn.call(this, event)"><img class="imgRegionRecher" src="'+hostName+'/images/icons/partout_b.png"><span>Partout</span></button>');
    
                    jQuery.each(regionesPadre, function( index, value ) {
                        jQuery(".Rectangle").append('<button title="region" value="'+value.id+'" onclick="clickRegionPadrebtn.call(this, event)" class="btnRegionRecher"><img class="imgRegionRecher" src="'+value.icon+'"><span>'+value.nom+'</span></button>');
                        countRegionesPadre++;
                    });
                } 
    
                //Lista de Establecimientos
                var search = '';
                if( typeEstablishmentID > 0 ){
                    var allHebergements = getAllHebergementsByType(typeEstablishmentID);
                }else{
                    var allHebergements = getAllHebergements('', '');
                } 
                response(allHebergements);
            });

            //Cambiar autocomplete del menu
            var autocompleteMenu = jQuery(".autocomplete-menu");
            autocompleteMenu.autocomplete('option', 'source', function(request, response){
    
                //Lista de Regiones
                var countRegionesPadre = 1;
                var regionesPadre = getAllRegionesPadre();
              

                if(  typeof regionesPadre !== 'undefined' && regionesPadre.length > 0){

                    jQuery(".Rectangle-menu").html('<button title="region" value="0" class="btnRegionRecher" onclick="clickRegionPadrebtn.call(this, event)"><img class="imgRegionRecher" src="'+hostName+'/images/icons/partout_b.png"><span>Partout</span></button>');

                    jQuery.each(regionesPadre, function( index, value ) {
                        jQuery(".Rectangle-menu").append('<button title="region" value="'+value.id+'" onclick="clickRegionPadrebtn.call(this, event)" class="btnRegionRecher"><img class="imgRegionRecher" src="'+value.icon+'"><span>'+value.nom+'</span></button>');
                        countRegionesPadre++;
                    });
                } 

                //Lista de Establecimientos
                var search = '';
                if( typeEstablishmentID > 0 ){
                    var allHebergements = getAllHebergementsByType(typeEstablishmentID);
                }else{
                    var allHebergements = getAllHebergements('', '');
                } 
                response(allHebergements);
            });

            
        });

      
        jQuery(document).click(function() {

         //alert(jQuery(this).attr('class'));

            if( $styledSelect.hasClass('active') ){
                $styledSelect.removeClass('active');
                $list.hide();
            }
         
            

        });
    
    });
    
}


/******************************************************************* */
// Funcion que obtiene los tipos de alojamientos según 
// fechas de temporada (por defecto) o seleccionadas
// SELECT DEL MENU
/******************************************************************* */
function getTypeHebergementsMenu(datesWithDispo) {
    jQuery.ajax({
        type: 'POST',
        async: false,
        url: 'https://sitenew.preprod.mmv.resalys.com/modules/mod_campings/rechercher/function.php',
        dataType: "text",
        data: { custom_function: 'getTypeHebergement', datesWithDispo: datesWithDispo },
        success: function (response) {
            var typeHeberg = JSON.parse(response);
            jQuery('.sel_typeEstablishment-menu').html('<option value="0"> Tout type d’hébergement </option>');
            jQuery('.sel_typeEstablishment-menu').html('<option value="0"> Hôtels & Résidences </option>');
            
             jQuery.each(typeHeberg, function (key, value) {
                 jQuery('.sel_typeEstablishment-menu').append('<option value="' + this.id + '"> ' + this.nom + ' </option>');
             });
            
             jQuery('.typeEtabissementContainerRadio').append('<input type="radio" class="typeEtabishemnt--radio" data-nom="Tous les Clubs"  name="typeEtablishment" value="0"> <span class="textcss">Tous les Clubs</span>');


             jQuery.each(typeHeberg, function (key, value) {
                 
                 jQuery('.typeEtabissementContainerRadio').append('<input type="radio" class="typeEtabishemnt--radio" data-nom="'+this.nom+'"  name="typeEtablishment" value="' + this.id + '"> <span class="textcss text-capitalize">' + this.nom +'</span>');
               
             });
        },
        error: function () {
            //console.log("getTypeHebergementsMenu failed in rechercher.js");
        }
    });

    constructLiHebergementsMenu();
}

function constructLiHebergementsMenu(){
    jQuery('.typeEstablishmentID').val('0__ Tous les Clubs');
jQuery('.typeEtabishemnt--radio').change(function(){
    jQuery('.typeEstablishmentID').val(jQuery(this).val()+'__ ' +jQuery(this).data('nom'));

});
    jQuery('.sel_typeEstablishment-menu').each(function(){

        var $this = jQuery(this), numberOfOptions = jQuery(this).children('option').length;
console.log($this);
        $this.addClass('select-hidden-menu'); 

        if( $this.parent().is('.select-menu') ){
            $this.remove();
        }
      
        $this.wrap('<div class="select-menu"></div>');
        $this.after('<div class="select-styled-menu blueBox"></div>');
    
        var $styledSelect = $this.next('div.select-styled-menu');
        $styledSelect.text($this.children('option').eq(0).text());


        var $list = jQuery('<ul />', {
            'class': 'select-options-menu'
        }).insertAfter($styledSelect);
      
        for (var i = 0; i < numberOfOptions; i++) {
            jQuery('<li />', {
                text: $this.children('option').eq(i).text(),
                rel: $this.children('option').eq(i).val()
            }).appendTo($list);
        }
      
        var $listItems = $list.children('li');

        //Input hidden q contendrá valor y texto del select
        var $inputEstablec = jQuery('input.typeEstablishmentID');
      
        $styledSelect.click(function(e) {
    
            e.stopPropagation();
            jQuery('div.select-styled-menu.active').not(this).each(function(){
                jQuery(this).removeClass('active').next('ul.select-options-menu').hide();
            });
            jQuery(this).toggleClass('active').next('ul.select-options-menu').toggle();

        });

        var $styledSelectResults = document.getElementsByClassName("select-styled");

      
        $listItems.click(function(e) {
            e.stopPropagation();
            //Pongo texto y value al div del select
            $styledSelect.text(jQuery(this).text()).removeClass('active');
            $styledSelect.attr('value', jQuery(this).attr('rel') );

            jQuery($styledSelectResults[0]).text( jQuery(this).text() );
            jQuery($styledSelectResults[0]).attr( 'value', jQuery(this).attr('rel') );

            $inputEstablec.attr('value',jQuery(this).attr('rel')+'__'+jQuery(this).text());
            $this.val(jQuery(this).attr('rel'));
            $list.hide();

            //ajax cambiar lista en el autocomplete cuando se selecciona un tipo de alojamiento
            var typeEstablishmentID = jQuery(this).attr('rel');
            console.log(typeEstablishmentID);

                
            //Cambiar Autocomplete de la home o results
            var autocompleteHome = jQuery(".autocomplete");
            autocompleteHome.autocomplete('option', 'source', function(request, response){
                
                
                //Lista de Regiones
                var countRegionesPadre = 1;
                var regionesPadre = getAllRegionesPadre();
              
    
                if(  typeof regionesPadre !== 'undefined' && regionesPadre.length > 0){
    
                    jQuery(".Rectangle").html('<button title="region" value="0" class="btnRegionRecher" onclick="clickRegionPadrebtn.call(this, event)"><img class="imgRegionRecher" src="'+hostName+'/images/icons/partout_b.png"><span>Partout</span></button>');
    
                    jQuery.each(regionesPadre, function( index, value ) {
                        jQuery(".Rectangle").append('<button title="region" value="'+value.id+'" onclick="clickRegionPadrebtn.call(this, event)" class="btnRegionRecher"><img class="imgRegionRecher" src="'+value.icon+'"><span>'+value.nom+'</span></button>');
                        countRegionesPadre++;
                    });
                } 
    
                //Lista de Establecimientos
                var search = '';
                if( typeEstablishmentID > 0 ){
                    var allHebergements = getAllHebergementsByType(typeEstablishmentID);
                }else{
                    var allHebergements = getAllHebergements('', '');
                } 
                response(allHebergements);
            });

            //Cambiar autocomplete del menu
            var autocompleteMenu = jQuery(".autocomplete-menu");
            autocompleteMenu.autocomplete('option', 'source', function(request, response){
    
                //Lista de Regiones
                var countRegionesPadre = 1;
                var regionesPadre = getAllRegionesPadre();
                

                if(  typeof regionesPadre !== 'undefined' && regionesPadre.length > 0){

                    jQuery(".Rectangle-menu").html('<button title="region" value="0" class="btnRegionRecher" onclick="clickRegionPadrebtn.call(this, event)"><img class="imgRegionRecher" src="'+hostName+'/images/icons/partout_b.png"><span>Partout</span></button>');

                    jQuery.each(regionesPadre, function( index, value ) {
                        jQuery(".Rectangle-menu").append('<button title="region" value="'+value.id+'" onclick="clickRegionPadrebtn.call(this, event)" class="btnRegionRecher"><img class="imgRegionRecher" src="'+value.icon+'"><span>'+value.nom+'</span></button>');
                        countRegionesPadre++;
                    });
                } 

                //Lista de Establecimientos
                var search = '';
                if( typeEstablishmentID > 0 ){
                    var allHebergements = getAllHebergementsByType(typeEstablishmentID);
                }else{
                    var allHebergements = getAllHebergements('', '');
                } 
                response(allHebergements);
            });
        });
      
        jQuery(document).click(function() {
            $styledSelect.removeClass('active');
            $list.hide();
        });


        // PARA SOMBREAR EL  BUSCADOR 
        
       /* jQuery('.autocomplete').click(function() {
            jQuery('.autocomplete').addClass('active');
            jQuery('body').addClass('body-layer');

            
        });*/

        jQuery('.selPersonas').click(function() {
            //jQuery('.selPersonas').addClass('active');
            //jQuery('body').addClass('body-layer');
            //inputactive == true;
        });

        jQuery('.datefilter').click(function(event){
            event.preventDefault();
            //console.log('je je je');
            $body.addClass('body-layer');
            jQuery(this).addClass('active');
            inputactive = true;
            
            //alert(inputactive);
        });

        jQuery(document).on('click','body.body-layer, *',function(){
        
           //jQuery('body').removeClass('body-layer');

        });

       

        // FIN PARA SOMBREAR EL  BUSCADOR 
    
    });
    
}



/**
 * Sombreara al hacer click en inputs de menu
 */
function addsomb(inpclick) {
  //alert('entra');
  //jQuery(inpclick).toggleClass('active');
  //jQuery('body').toggleClass('body-layer');
}


/**
 * En recjhercher etab si no hay nada seleccionado no dejamos hacer el envio
 */
function Entramoserach() {
    //alert('entra');
    //event.preventDefault();
}



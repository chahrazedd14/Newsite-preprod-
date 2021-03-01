<?php

/**
 * @version     CVS: 1.0.0
 * @package     com_campings
 * @subpackage  mod_campings
 * @author      unixdata <web@thelis.es>
 * @copyright   unixdata
 * @license     Licencia Pública General GNU versión 2 o posterior. Consulte LICENSE.txt
 */
defined('_JEXEC') or die;

$page = ModCampingsHelper::getpageResults();
$dispositivo = ModCampingsHelper::getMobile();
$lang = ModCampingsHelper::getLang();

//Incluyo mi fichero view del modulo resultsbusqueda
require_once JPATH_BASE . '/modules/mod_campings/resultsbusqueda/view.php';
?>

<div id="resultsrechercher">
    <p class="text-center">Aquí van los resultados de búqueda</p>
    <?php
    getHebergementByFormResults();
    ?>
</div>

<script>
/**********************************************
* Selecciona todas los alojamientos según 
  los campos seleccionados en el formulario
  por AJAX
***********************************************/
function getHebergementByFormResults() {

    var custom_response;

    var selectedEstab = jQuery('.sel_typeEstablishment');

    var typeEstablishmentID = jQuery('option:selected', selectedEstab).attr('value');

    var autocompleteID = jQuery(".autocompleteID").attr('value');

    jQuery.ajax({
        type: 'POST',
        async: false,
        url: '/mmv-web/modules/mod_campings/resultsbusqueda/function.php',
        dataType: "text",
        data: {
            custom_function: 'getHebergementByFormResults',
            typeEstablishment: typeEstablishmentID,
            autocompleteID: autocompleteID
        },
        success: function(data) {
            custom_response = JSON.parse(data);
        },
        error: function() {
            console.log("The request failed");
        }
    });

    return custom_response;
}


jQuery(function() {

    jQuery('.frmRechercher').on('submit', function(e) {

        //Si el alias esta vacio o no existe, es es que escogimos una region en el autocomplete
        if ((typeof alias == 'undefined') || (alias == '')) {

            //para q no se vaya a una nueva página
            e.preventDefault();

            var results = getHebergementByFormResults();
            var resultsHtml = "";

            jQuery.each(results, function(i, val) {
                resultsHtml += val.id + ' - ' + val.nom + '<br>';
            });

            console.log('results');
            console.log(results);

            jQuery('#resultsrechercher').html(resultsHtml);

        }


    });

});
</script>
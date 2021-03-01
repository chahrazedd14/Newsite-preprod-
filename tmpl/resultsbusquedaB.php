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


$lang = ModCampingsHelper::getLang();
$host = ModCampingsHelper::getHost();
$page = ModCampingsHelper::getpage();

$tipopage = $page['type'];
$itemid = $page['itemid'];

/*echo '<pre>';
print_r($page);
echo '</pre>';*/

?>
<script>
jQuery(document).ready(function() {

    var arrIdFilters = [];


    getHebergementByFilterCms(arrIdFilters, '<?php echo $tipopage; ?>', <?php echo $itemid; ?>);

    jQuery('.serviceslist  input[type="checkbox"]').change(function(event) {

        var arrIdFilters = [];
        var allChecked = jQuery('.moesrvicesclose').find('input:checkbox:checked');


        jQuery.each(allChecked, function(index, value) {
            arrIdFilters.push(jQuery(value).attr('data-id'));
        });

        console.log(arrIdFilters);

        //Hago la llamada a la función ajax que busca alojamientos segun los filtros seleccionados
        getHebergementByFilterCms(arrIdFilters, '<?php echo $tipopage; ?>', <?php echo $itemid; ?>);

    });


});
</script>
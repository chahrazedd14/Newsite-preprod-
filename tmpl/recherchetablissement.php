<?php

/**
 * @version     CVS: 1.0.0
 * @package     com_campings
 * @subpackage  mod_campings
 * @author      unixdata <web@thelis.es>
 * @copyright   unixdata
 * @license     Licencia Pública General GNU versión 2 o posterior. Consulte LICENSE.txt
 */

/************************************************************** */
//Modulo Joomla para la posición del buscador
/************************************************************** */

defined('_JEXEC') or die;

$lang = ModCampingsHelper::getLang();
//$mobile = ModCampingsHelper::getMobile();


//Incluyo mi fichero view rechercher home
require_once JPATH_BASE . '/modules/mod_campings/rechercher/view.php';

echo constructRechercherEstablessiment();


echo '<div class="rechercherfilter">';
echo '<div class="textfiltersmod d-flex contactezinfo align-items-center"><img class="float-left " src="images/icons/picto-expert.png" /><div class="consultezexpert">' . $params->get('descrfilters') . '</div></div>';
if ($mobile == 0) :

    echo '<div class="textfiltersmod d-flex packageinfo align-items-center"><img class="float-left " src="images/icons/picto-package-glisse.png"" /><div class="consultezexpert">' . $params->get('packageglisse');
    if ($params->get('packageglissemoreinfo') != '') :
        echo '<button type="button" class="btn  " data-toggle="modal" data-target="#glissemodal"><img src="images/icons/infofilters.png" alt="package-glisse" title="package-glisse" class=""></button>';

        //modal
        echo   '<div id="glissemodal" class="modal fade " role="dialog">';
        echo   '<div class="modal-dialog">';
        echo   '<div class="modal-content">';
        echo   '<div class="modal-body">';
        echo   $params->get('packageglissemoreinfo');
        echo   '</div>';
        echo   '<div class="modal-footer">';
        echo   ' <button type="button" class="btn btn-default" data-dismiss="modal">' . JText::_('MOD_CAMPINGS_FERMER') . '</button>';
        echo   '</div>';
        echo   '</div>';
        echo   '</div>';
        echo   '</div>';


    endif;
    echo '</div></div>';
    echo '<div class="textfiltersmod d-flex partezinfo align-items-center"><img class="float-left " src="images/icons/picto-le-saviez.png" /><div class="consultezexpert">' . $params->get('partezenfans');
    if ($params->get('partezenfansmoreinfo') != '') :
        echo '<button type="button" class="btn  " data-toggle="modal" data-target="#partezenfansmodal"><img src="images/icons/infofilters.png" alt="package-glisse" title="package-glisse" class=""></button>';
        //modal
        echo '<div id="partezenfansmodal" class="modal fade " role="dialog">';
        echo '<div class="modal-dialog">';
        echo '<div class="modal-content">';
        echo '<div class="modal-body">';
        echo  $params->get('partezenfansmoreinfo');
        echo '</div>';
        echo '<div class="modal-footer">';
        echo ' <button type="button" class="btn btn-default" data-dismiss="modal">' . JText::_('MOD_CAMPINGS_FERMER') . '</button>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';

    endif;
    echo '</div></div>';

endif;
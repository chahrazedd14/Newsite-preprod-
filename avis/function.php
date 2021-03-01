<?php

/**
 * @version     CVS: 1.0.0
 * @package     com_campings
 * @subpackage  mod_campings
 * @author      unixdata <web@thelis.es>
 * @copyright   unixdata
 * @license     Licencia Pública General GNU versión 2 o posterior. Consulte LICENSE.txt

 * avis

 */
//DAVID EDITAR
require_once  '../includes.php';

$temporada = (isset($_POST['temporada'])) ? $_POST['temporada'] :  $_COOKIE['saison'];
$avisCode = (isset($_POST['avisCode'])) ? $_POST['avisCode'] :  0;


//$elementsAvisOption = ModCampingsHelper::getAvisOptions($avisCode);
//
$elementsComments = ModCampingsHelper::getAvisCommentsPrestataires($avisCode);
$elementsAvis = ModCampingsHelper::getAvisPrestataires($avisCode);
$elementsCampingsAvis = ModCampingsHelper::getAvisCampingsPrestataires($avisCode);


// printmoduleAvisOptions($elementsAvisOption); //funcion buscador
//
printmoduleAvisCampingsPrestataires($elementsCampingsAvis);
printmoduleAvisCommentsPrestataires($elementsAvis);
printmoduleAvisPrestataires($elementsComments);
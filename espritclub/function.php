<?php

/**
 * @version     CVS: 1.0.0
 * @package     com_campings
 * @subpackage  mod_campings
 * @author      unixdata <web@thelis.es>
 * @copyright   unixdata
 * @license     Licencia Pública General GNU versión 2 o posterior. Consulte LICENSE.txt

 * Esprit Club 
 */

require_once  '../includes.php';

$temporada = (isset($_POST['temporada'])) ? $_POST['temporada'] : $_COOKIE['saison'];
$typeorder = ($params->get('clubsorder')) ? $params->get('clubsorder') : 0;  //Lo definimos en backoffice joomla module clubs
$elements = ModCampingsHelper::getLandingpages($typeorder, $codelang);

$directorio = ($temporada == 2) ? 'ete' : 'hiver';
printModuleLandingpage($elements, $directorio, $lang);
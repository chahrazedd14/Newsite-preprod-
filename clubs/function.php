<?php

/**
 * @version     CVS: 1.0.0
 * @package     com_campings
 * @subpackage  mod_campings
 * @author      unixdata <web@thelis.es>
 * @copyright   unixdata
 * @license     Licencia Pública General GNU versión 2 o posterior. Consulte LICENSE.txt

 * Clubs

 */

require_once  '../includes.php';

$saison = (isset($_POST['temporada'])) ? $_POST['temporada'] : $_COOKIE['saison'];
$elements = ModCampingsHelper::getClubs(0, $saison);

$directorio = ($saison == 2) ? 'ete' : 'hiver';

//PROMOTIONS

$categorie = (isset($_POST['categorie'])) ? $_POST['categorie'] : 0;
$mobile = ModCampingsHelper::getMobile();
$promotions = ModCampingsHelper::getPromotionsClub($categorie, $saison, $codelang);

//FIN PROMOTIONS

printmoduleclub($elements, $directorio, $lang, $saison, $promotions, $lienlang);
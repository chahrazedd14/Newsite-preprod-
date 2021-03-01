<?php

/**
 * @version     CVS: 1.0.0
 * @package     com_campings
 * @subpackage  mod_campings
 * @author      unixdata <web@thelis.es>
 * @copyright   unixdata
 * @license     Licencia Pública General GNU versión 2 o posterior. Consulte LICENSE.txt

 * Promotions
 */

require_once  '../includes.php';

$saison = (isset($_POST['temporada'])) ? $_POST['temporada'] : $_COOKIE['saison'];
$categorie = (isset($_POST['categorie'])) ? $_POST['categorie'] : 0;
$mobile = ModCampingsHelper::getMobile();
$categorieDerniereminutes = ModCampingsHelper::getCategoriesDernieresMinutes($codelang);
$categorieDerniereminutesAccueil = ModCampingsHelper::getCategoriesDernieresMinutesAccueil($codelang);

$elements = ModCampingsHelper::getPromotions($categorie, $saison, $codelang);
$elementsCategorie = ModCampingsHelper::getCategoriesPromotions($category, $codelang);

$directorio = ($saison == 2) ? 'ete' : 'hiver';

if ($mobile == 0) :
    getPromotions($elements, $elementsCategorie, $directorio, $saison, $categorie, $lang, $categorieDerniereminutesAccueil, $categorieDerniereminutes, $lienlang);

else :
    getPromotionsMobile($elements, $elementsCategorie, $directorio, $saison, $categorie, $lang, $categorieDerniereminutesAccueil, $categorieDerniereminutes, $lienlang);
endif;
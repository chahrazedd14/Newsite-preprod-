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
//$temporada = ModCampingsHelper::getTemporada();

//Al cargar crealos cookie de saison 
/*print_r($params);
echo $params->get('codesaison');*/
if (!isset($_COOKIE['saison'])) {
    setcookie("saison", $params->get('codesaison'), time() + 3600);  /* expira en una hora */
}

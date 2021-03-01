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
$mobile = ModCampingsHelper::getMobile();
echo $params->get('offretopdescription');
echo ' <a class="blanctext" href="' . $params->get('offretopurl') . '" target="_blank" />' . $params->get('offretopnomurl') . '</a>';
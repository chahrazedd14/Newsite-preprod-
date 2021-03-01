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
/*print_r($params->get('clients'));*/
//print_r($params);


?>

<div class="container" id="vosgarantiesexperience">
    <h3 class="blueexdtext"><strong>Vos garanties </strong>Avec mmv, réservez l’esprit libre !</h3>
    <div class="row">
        <ul class="nav nav-tabs col-lg-3 col-12" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#vosgaranties01" role="tab" aria-controls="home"><img
                        src="<?php echo $params->get('vosgarantiesimg01'); ?>"
                        alt="<?php echo $params->get('vosgarantiestitle01'); ?>"
                        title="<?php echo print_r($params->get('vosgarantiestitle01')); ?>" /></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#vosgaranties02" role="tab" aria-controls="profile"><img
                        src="<?php echo $params->get('vosgarantiesimg02'); ?>"
                        alt="<?php echo $params->get('vosgarantiestitle02'); ?>"
                        title="<?php echo print_r($params->get('vosgarantiestitle02')); ?>" /></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#vosgaranties03" role="tab" aria-controls="messages"><img
                        src="<?php echo $params->get('vosgarantiesimg03'); ?>"
                        alt="<?php echo $params->get('vosgarantiestitle03'); ?>"
                        title="<?php echo print_r($params->get('vosgarantiestitle03')); ?>" /></a>
            </li>

        </ul>

        <div class="tab-content col">
            <div class="tab-pane active" id="vosgaranties01" role="tabpanel">
                <div class="slider">
                    <div class="titlevosgaranties blueexdtext"><?php echo $params->get('vosgarantiestitle01'); ?></div>
                    <p><?php echo $params->get('vosgarantiestext01'); ?></p>
                </div>
            </div>
            <div class="tab-pane" id="vosgaranties02" role="tabpanel">
                <div class="slider">
                    <div class="titlevosgaranties blueexdtext"><?php echo $params->get('vosgarantiestitle02'); ?></div>
                    <p><?php echo $params->get('vosgarantiestext02'); ?></p>
                </div>
            </div>
            <div class="tab-pane" id="vosgaranties03" role="tabpanel">
                <div class="slider">
                    <div class="titlevosgaranties blueexdtext"><?php echo $params->get('vosgarantiestitle03'); ?></div>
                    <p><?php echo $params->get('vosgarantiestext03'); ?></p>
                </div>
            </div>

        </div>

    </div>
</div>
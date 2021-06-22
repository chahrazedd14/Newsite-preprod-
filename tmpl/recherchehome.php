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
$mobile = ModCampingsHelper::getMobile();
$temporada = ModCampingsHelper::getTemporada();
/*echo $temporada;*/

echo '<div class="descTitle">';
  // echo '<div class="Partez-tout-schuss">

  // '.JText::_(MOD_CAMPINGS_RECHERCHERHOME_PARTEZ_TOUT_SCHUSS).'

  // </div>';
  echo '<H1 class="blanctext">

 '.JText::_('MOD_CAMPINGS_RECHERCHERHOME_H1_LEXPERIECE_CLUB').'

  </H1>';
echo '</div>';

//Incluyo mi fichero view rechercher home
require_once JPATH_BASE . '/modules/mod_campings/rechercher/view.php';

if($mobile!=1):
	$view = constructRechercherHome();
	echo $view;
endif;

?>


<div class="container d-block d-sm-block d-md-block d-lg-none d-xl-none">
  <div class="row ">
  	<?php if($mobile==0): ?>
  		<div class="col-7 titleclubsmmv d-flex align-items-center">
  				<h2 class="float-left blanctext"><strong>  <?php echo JText::_('MOD_CAMPINGS_CLUBS_20_CLUBS'); ?>  </strong><span class="mmvfont"> <?php echo JText::_('MOD_CAMPINGS_MMV'); ?></span> <br></h2><span class="blanctext pl-3"><?php echo JText::_(MOD_CAMPINGS_CLUBS_LEQUEL_SERA); ?><br/><?php echo JText::_(MOD_CAMPINGS_CLUBS_VOTRE_PREFERE); ?></span>

  		</div>
  	<?php endif; ?>
    <div class="col-4 <?php if($mobile!=1): ?>justify-content-end<?php endif;?> colEteHiver">
    <!-- Voy a comentar el if que en dependencia de la temporada porque temporalmente forzaremos a la temporada 2 -->
      <button class="temporadas  <?php //if($temporada == 1): echo 'active'; endif; ?>" onclick="EteHiver(1)" value="1"><img src="images/icons/picto-hiver.png" alt="Hiver" title="Hiver" />

          <?php if($mobile==0): ?><?php echo JText::_('MOD_CAMPINGS_RECHERCHERHOME_HIVER'); ?><?php endif; ?>

      </button>
      <button class="temporadas  <?php echo "active";//if($temporada == 2): echo 'active'; endif; ?>" onclick="EteHiver(2)" value="2"><img src="images/icons/picto-ete.png" alt="Ete" title="Ete" />

          <?php if($mobile==0): ?><?php echo JText::_('MOD_CAMPINGS_RECHERCHERHOME_ETE'); ?><?php endif; ?>

      </button>
    </div>
  </div>
</div>


<div class="container d-none d-sm-none d-md-none d-lg-block d-xl-block">
  <div class="row " style="justify-content: flex-end;">
  	
    <div class="col-4  <?php if($mobile!=1): ?>justify-content-end<?php endif;?> colEteHiver" style="position: absolute;
    z-index: 2;
     top: 296px;px;">
    <!-- Voy a comentar el if que en dependencia de la temporada porque temporalmente forzaremos a la temporada 2 -->
      <button class="temporadas  <?php //if($temporada == 1): echo 'active'; endif; ?>" onclick="EteHiver(1)" value="1"><img src="images/icons/picto-hiver.png" alt="Hiver" title="Hiver" />

          <?php if($mobile==0): ?><?php echo JText::_('MOD_CAMPINGS_RECHERCHERHOME_HIVER'); ?><?php endif; ?>

      </button>
      <button class="temporadas  <?php echo "active";//if($temporada == 2): echo 'active'; endif; ?>" onclick="EteHiver(2)" value="2"><img src="images/icons/picto-ete.png" alt="Ete" title="Ete" />

          <?php if($mobile==0): ?><?php echo JText::_('MOD_CAMPINGS_RECHERCHERHOME_ETE'); ?><?php endif; ?>

      </button>
    </div>
  </div>
</div>

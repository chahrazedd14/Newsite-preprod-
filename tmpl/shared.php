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
$menus = $app->getMenu();
$elements = $menus->getItems('menutype', 'nos-autres-sites');


	echo '<div class="row">';
		echo '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 text-center">';
			/*echo '<a href="'.$params->get('facebook').' " target="_blank" class="iconfacebook" /><i class="fab fa-facebook-f"></i></a>';
			echo '<a href="'.$params->get('twitter').'" target="_blank" class="icontwitter" /><i class="fab fa-twitter"></i></a>';
			echo '<a href="'.$params->get('advisor').'" target="_blank" class="iconadvisor" /><i class="fab fa-tripadvisor"></i></a>';
			echo '<a href="'.$params->get('bloc').'" target="_blank" class="iconbloc" />blog</a>';*/
			echo '<a href="'.$params->get('facebook').' " target="_blank" class="iconfacebook" ><img src="images/icons/facebook.png" alt="facebook" title="facebook" /></a>';
			echo '<a href="https://www.instagram.com/mmv_lesvacancesclub/" target="_blank" class="icontwitter" ><img src="https://www.mmv.fr/images/icons/174855.jpg" alt="twitter" title="twitter" /></a>';
			echo '<a href="https://www.linkedin.com/company/mmv-clubs/mycompany/" target="_blank" class="iconfacebook" ><img src="https://www.mmv.fr/images/icons/Web-Linked-in-alt-Metro-icon.jpg" alt="facebook" title="linkedin" / style="border-radius: 5px;"></a>';
			echo '<a href="'.$params->get('advisor').'" target="_blank" class="iconadvisor" ><img src="images/icons/tripadvisor.png" alt="tripadvisor" title="tripadvisor" /></a>';
			echo '<a href="'.$params->get('blog').'" target="_blank" class="iconbloc" ><img src="images/icons/blog.png" alt="blog" title="blog" /></a>';
			
		echo '</div>';
		echo '<div class="col-lg-6 col-md-5 col-sm-12 col-xs-12">';
			if($lang == 'fr' || $lang =='en'):
			echo '<form class="autress"><select class="autressites">';
			echo '<option value="0">'.JText::_('MOD_CAMPINGS_AUTRES_SITES').'</option>';
				foreach($elements as $element):
						echo '<option value="'.$element->link.'">'.$element->title.'</option>';
				endforeach;

			echo '</select></form>';
			endif;
		
		echo '</div>';
	echo '</div>';

	?>


	<script>

	jQuery(function(){
      // bind change event to select
      jQuery('#linksfooter .autressites').on('change', function () {
          var url = jQuery(this).val(); // get selected value
          if (url!=0) { // require a URL
              window.open(url, '_blank');
          }
          return false;
      });
    });

	</script>
	



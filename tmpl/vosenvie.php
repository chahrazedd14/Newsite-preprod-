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
$vosenvies = ModCampingsHelper::getVosenvies();
$promotion = ModCampingsHelper::getPromotionMenu();
$lienlang = ModCampingsHelper::getLienLang($lang);


$mainframe = JFactory::getApplication();
$menu = $mainframe->getMenu();

if (!empty($vosenvies)) :
    echo '<div class="row m-0 p-0">';
    echo '<div class="col-12 col-lg-8 pl-4 pt-2">';
    echo '<div class="row">';
    foreach ($vosenvies as $envies) :

        $item = $menu->getItem($envies->id);
        $params = $item->params;
        $imagen = $params->get('menu_image');
        $img = ($imagen != '') ? $imagen : 'images/menu/default.jpg';

        echo '<div class="col-6 col-lg-4 p-2 text-left">';
        echo '<a href="' . $lienlang . $envies->link . '" class="linkvosenvies"><img src="' . $img . '" alt="' . $envies->title . '" title="' . $envies->title . '" class="p-2 float-left" /><p>' . $envies->title . '</p></a>';
        echo '</div>';
    endforeach;
    echo '</div>';
    echo '</div>';

    echo '<div class="col-4 pr-0">';

    if (!empty($promotion)) :
        echo '<a class="promotionhoverclub" href="' . $host . $lienlang . $promotion->alias . '" >';
        if ($promotion->imgpromotion != '') :
            $promotion->img = $promotion->imgpromotion;
        else :
            $promotion->img = $host . 'images/landingpages/vignettes/' . $directorio . '/default.jpg';
        endif;
        echo '<img src="' . $host . $promotion->img . '" alt="' . $promotion->nom . '" title="' . $promotion->nom . '" />';
        echo '<div class="dernieretext">';
        if ($promotion->nom != '') :
            echo '<p class="blanctext">' . $promotion->nom . '<br/>' . $promotion->subtitre . '</p>';
            echo '<span class="tag blanctext">' . $promotion->tag . ' ' . $promotion->minprix . '</span>';
            echo '<p class="bluebackground blanctext text-center promobutton">' . JText::_('MOD_CAMPINGS_OFFERS_PROFITEZ') . '</p>';


        endif;

        echo '</div>';


        echo '</a>';
    endif;


    echo '</div>'; //promo
    echo '</div>';
endif;
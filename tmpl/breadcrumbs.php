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


$app = JFactory::getApplication();
$menu = $app->getMenu();
$active = $menu->getActive();
$alias = $active->alias;
$lang = ModCampingsHelper::getLang();
//$alias ='residence-club-ski-les-menuires-le-coeur-des-loges';

// echo '<pre>';
// print_r($active);
// echo '</pre>';

$codelang = 1;

if ($active->query['view'] == 'camping') :


    $db    = JFactory::getDbo();
    $query = $db->getQuery(true);

    // $query = 'SELECT menu.title AS name , menu.path AS link , menu.parent_id AS parentid  FROM kdn_menu as menu
    // 		WHERE  menu.alias =  "'.$alias.'" AND menu.type = "component" AND menu.parent_id != 1 ';

    $query

        ->select(array('menu.title AS name', 'menu.path as link', 'falangtitle.value as nametranslate'))
        ->from($db->quoteName('kdn_menu', 'menu'))
        ->join('LEFT', $db->quoteName('#__falang_content', 'falangtitle') . ' ON (' . $db->quoteName('falangtitle.reference_table') . ' = "menu") AND ' . $db->quoteName('falangtitle.language_id') . ' = "' . $codelang . '" AND ' . $db->quoteName('falangtitle.reference_id') . ' =  ' . $db->quoteName('menu.id') . ' AND ' . $db->quoteName('falangtitle.reference_field') . ' =  "title"')
        ->where($db->quoteName('menu.alias') . ' = "' . $alias . '" AND menu.type = "component" AND parent_id != 1 ');

    $db->setQuery($query);
    $item = $db->loadObject();



    // echo '<pre>';
    // print_r($item);
    // echo '</pre>';
    $segmentos = explode("/", $item->link);
    //print_r($segmentos);

    printFildariadne($segmentos, $alias, $lang); // PRINTAMOS FILDARIADNE DE LA FICHA DEL ETAB



/*if($item->parent_id !=1):
				echo 'entra1';
				$item = getparent($item->parentid);
				echo '<pre>';
				print_r($item);
				echo '</pre>';
			endif;

			if($item->parent_id !=1):
				echo 'entra2';
				$item = getparent($item->parentid);
				echo '<pre>';
				print_r($item);
				echo '</pre>';
			endif;

			
			echo $item->parent_id;
			if($item->parent_id !=1):
				echo 'entra3';
				$item = getparent($item->parentid);
				echo '<pre>';
				print_r($item);
				echo '</pre>';
			endif;

			echo $item->parent_id;
			if($item->parent_id !='1'):
				echo 'entra4';
				$item = getparent($item->parentid);
				echo '<pre>';
				print_r($item);
				echo '</pre>';
			endif;*/



endif;


if ($active->query['view'] == 'cms') :

    $db    = JFactory::getDbo();
    $query = $db->getQuery(true);

    // $query = 'SELECT menu.title AS name , menu.path AS link , menu.parent_id AS parentid  FROM kdn_menu as menu
    // 		WHERE  menu.alias =  "'.$alias.'" AND menu.type = "component" AND menu.parent_id != 1 ';

    $query

        ->select(array('menu.title AS name', 'menu.path as link', 'falangtitle.value as nametranslate'))
        ->from($db->quoteName('kdn_menu', 'menu'))
        ->join('LEFT', $db->quoteName('#__falang_content', 'falangtitle') . ' ON (' . $db->quoteName('falangtitle.reference_table') . ' = "menu") AND ' . $db->quoteName('falangtitle.language_id') . ' = "' . $codelang . '" AND ' . $db->quoteName('falangtitle.reference_id') . ' =  ' . $db->quoteName('menu.id') . ' AND ' . $db->quoteName('falangtitle.reference_field') . ' =  "title"')
        ->where($db->quoteName('menu.alias') . ' = "' . $alias . '" AND menu.type = "component" AND parent_id != 1 ');



    $db->setQuery($query);
    $item = $db->loadObject();

    if (!empty($item)) :

        $db    = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query = 'SELECT menu.title AS name , menu.path AS link , menu.parent_id AS parentid  FROM kdn_menu as menu
						WHERE  menu.alias =  "' . $alias . '" AND menu.type = "component" AND menu.parent_id != 1 ';

        $db->setQuery($query);
        $item = $db->loadObject();
        $segmentos = explode("/", $item->link);
        printFildariadne($segmentos, $alias, $lang); // PRINTAMOS FILDARIADNE DE LA FICHA DEL ETAB

    else :
        $segmentos = explode("/", $alias);
        printFildariadne($segmentos, $alias, $lang); // PRINTAMOS FILDARIADNE DE LA FICHA DEL ETAB
    endif;


/*echo '<pre>';
				print_r($item);
				echo '</pre>';*/

endif;






function getparent($parentid)
{

    $db    = JFactory::getDbo();
    $query = $db->getQuery(true);

    $query = 'SELECT menu.title AS name , menu.path AS link , menu.parent_id AS parentid , menu.alias AS alias , menu.type AS type  FROM kdn_menu as menu
		WHERE  menu.id =  "' . $parentid . '"';

    $db->setQuery($query);
    $item = $db->loadObject();
    return $item;
}

function printFildariadne($segmentos, $alias, $lang)
{
    $codelang = ModCampingsHelper::getTranslateLang($lang);
    // echo $codelang;
    echo  '<ul class="row m-3" id="fildariadne">';
    $ultimo = end($segmentos);

    echo '<li class="p-2"><a href="/" class="text-decoration-underlined noirtext" >' . JText::_('MOD_CAMPINGS_ACCUEIL_MMV') . '</a></li>';
    foreach ($segmentos as $link) :

        $language = ($lang != 'fr') ? $lang . '/' : '';


        if ($link != $ultimo) :
            //echo $link;
            //echo 'cms';
            $name = ModCampingsHelper::getNameFildariadne($link, 'cms', $codelang);
            //print_r($name);

            $nom = (isset($name->nomtranslate)) ? $name->nomtranslate : $name->nom;
            $menu = (isset($name->menutranslate)) ? $name->menutranslate : $link;

            echo '<li class="p-2"><i class="fas fa-chevron-right bluetext pr-2"></i><a href="' . $language . $menu . '" class="text-decoration-underlined noirtext" >' . $nom . '</a></li>';
        else :
            if ($active->query['view'] == 'camping') :

                $name = ModCampingsHelper::getNameFildariadne($alias, 'camping', $codelang);
                $nom = (isset($name->nomtranslate)) ? $name->nomtranslate : $name->nom;
                $menu = (isset($name->menutranslate)) ? $name->menutranslate : $alias;

            else :
                //echo $alias;
                //echo 'cms';
                $name = ModCampingsHelper::getNameFildariadne($alias, 'cms', $codelang);
                $nom = (isset($name->nomtranslate)) ? $name->nomtranslate : $name->nom;
                $menu = (isset($name->menutranslate)) ? $name->menutranslate : $alias;
            endif;
            echo '<li class="p-2"><i class="fas fa-chevron-right bluetext pr-2"></i> ' . $nom . '</li>';
        endif;
    endforeach;
    echo  '</ul>';
}
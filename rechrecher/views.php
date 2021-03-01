<?php

/**
 * @version     CVS: 1.0.0
 * @package     com_campings
 * @subpackage  mod_campings
 * @author      unixdata <web@thelis.es>
 * @copyright   unixdata
 * @license     Licencia Pública General GNU versión 2 o posterior. Consulte LICENSE.txt

 * Rechercher
 */

require_once  'function.php';

/************************************************************************************************************************* */
//Funcion general que construye el html del rechercher de la home
/************************************************************************************************************************* */
function constructRechercherHome()
{

    $host = ModCampingsHelper::getHost();;

    $lang = ModCampingsHelper::getLang();
    $rechercherHome = '<form class="frmRechercher" name="frmRechercher" action="';
    if ($lang != 'fr') :
        $rechercherHome .= $host . '' . $lang . '/';
    endif;
    $rechercherHome .= 'results" method="post">';
    $rechercherHome .= '<div class="rechercherHome">';
    $rechercherHome .= '<div class="container">';
    $rechercherHome .= '<div class="row">';
    $rechercherHome .= '<div class="col-10">';
    $rechercherHome .= '<div class="row rechercherbuttons">';
    $rechercherHome .= '<div class="col-12 pb-2 slection">';
    $rechercherHome .= '<span class="autocomplete-image"></span>';
    $rechercherHome .= '<input type="text" class="autocomplete blueBox" name="autocomplete" >';
    $rechercherHome .= '<input type="hidden"  name="autocompleteID" class="autocompleteID blueBox">';
    // $rechercherHome .= '<div class="Rectangle">';
    // $rechercherHome .= '<button value="0" class="btnRegionRecher"><img class="imgRegionRecher" src="'.$host.'images/defaultCountry.png"><span>Partout</span></button>';
    // $rechercherHome .= '</div>';
    $rechercherHome .= '</div>';
    $rechercherHome .= '<div class="col-6">';
    $rechercherHome .= '<input type="text" name="datefilter" value="' . JText::_(MOD_CAMPINGS_RECHERCHER_DATEPICKER) . '" class="datefilter rangeDatepicker"  onfocus="blur();"/>';
    $rechercherHome .= '</div>';
    $rechercherHome .= '<div class="col-6 ">';
    $rechercherHome .= '<div class="blueBox selPersonas" onclick="addsomb(\'.selPersonas\');">';
    //$rechercherHome .= '<input class="numberPersonas text-center" name="numberPersonas"  /> <span class="avec-qui-partez-vous text-center ">  '.JText::_(MOD_CAMPINGS_RECHERCHER_AVEC_QUI_PARTEZ_VOUS).' </span>';
    $rechercherHome .= '<input class="numberPersonas text-center" name="numberPersonas"  /> <span class="avec-qui-partez-vous text-center "><strong>2 </strong>  ' . JText::_(' participants') . ' </span>';
    $rechercherHome .= '</div>';
    $rechercherHome .= '</div>';
    $rechercherHome .= '</div>';
    $rechercherHome .= '<div class="row">';
    $rechercherHome .= '<div class="col-8">';
    $rechercherHome .= '<div class="personasWindDiv" style="display:none">';
    $rechercherHome .= '<div class="container block-personas">';

    //Adultos
    $rechercherHome .= '<div class="row">';
    $rechercherHome .= '<div class="col-6 col-md-7 tipo-persona">';
    $rechercherHome .= '<span>

                                            ' . JText::_(MOD_CAMPINGS_RECHERCHER_ADULTE) . '

                                            </span>';
    $rechercherHome .= '</div>';

    $rechercherHome .= '<div class="col-6 col-md-5">';
    //$rechercherHome .= '<form class="formPersonas">';
    $rechercherHome .= '<div class="value-button btnDecrease" >-</div>';
    $rechercherHome .= '<input type="number" readonly class="number" data-id="numberA" name="numberA" value="2" />';
    $rechercherHome .= '<div class="value-button btnIncrease"  >+</div>';
    //$rechercherHome .= '</form>';
    $rechercherHome .= '</div>';
    $rechercherHome .= '</div>';

    //Enfant 6 à -12 ans
    $rechercherHome .= '<div class="row">';
    $rechercherHome .= '<div class="col-6 col-md-7 tipo-persona">';
    $rechercherHome .= '<span>

                                            ' . JText::_(MOD_CAMPINGS_RECHERCHER_ENFANT_6_12) . '

                                            </span>';
    $rechercherHome .= '</div>';
    $rechercherHome .= '<div class="col-6 col-md-5">';
    //$rechercherHome .= '<form class="formPersonas">';
    $rechercherHome .= '<div class="value-button btnDecrease" >-</div>';
    $rechercherHome .= '<input type="number" class="number" readonly data-id="numberE1" name="numberE1" value="0" />';
    $rechercherHome .= '<div class="value-button btnIncrease"  >+</div>';
    //$rechercherHome .= '</form>';
    $rechercherHome .= '</div>';
    $rechercherHome .= '</div>';

    //Enfant - de 6 ans
    $rechercherHome .= '<div class="row">';
    $rechercherHome .= '<div class="col-6 col-md-7 tipo-persona">';
    $rechercherHome .= '<span>

                                            ' . JText::_(MOD_CAMPINGS_RECHERCHER_ENFANT_6) . '

                                            </span>';
    $rechercherHome .= '</div>';
    $rechercherHome .= '<div class="col-6 col-md-5">';
    // $rechercherHome .= '<form class="formPersonas">';
    $rechercherHome .= '<div class="value-button btnDecrease"  >-</div>';
    $rechercherHome .= '<input type="number" class="number" readonly data-id="numberE2" name="numberE2" value="0" />';
    $rechercherHome .= '<div class="value-button btnIncrease"  >+</div>';
    // $rechercherHome .= '</form>';
    $rechercherHome .= '</div>';
    $rechercherHome .= '</div>';

    //Bébé -de 2 ans
    $rechercherHome .= '<div class="row">';
    $rechercherHome .= '<div class="col-6 col-md-7 tipo-persona">';
    $rechercherHome .= '<span>

                                            ' . JText::_(MOD_CAMPINGS_RECHERCHER_BEBE) . '

                                            </span>';
    $rechercherHome .= '</div>';
    $rechercherHome .= '<div class="col-6 col-md-5">';
    //$rechercherHome .= '<form class="formPersonas">';
    $rechercherHome .= '<div class="value-button btnDecrease"  >-</div>';
    $rechercherHome .= '<input type="number" class="number" readonly data-id="numberB" name="numberB" value="0" />';
    $rechercherHome .= '<div class="value-button btnIncrease"   >+</div>';
    //$rechercherHome .= '</form>';
    $rechercherHome .= '</div>';
    $rechercherHome .= '</div>';

    $rechercherHome .= '<div class="row">';
    $rechercherHome .= '<div class="col-12">';
    $rechercherHome .= '<span class="maxPersPermitted" style="display:none;">

                                            ' . JText::_('MOD_CAMPINGS_RECHERCHER_PLUS_6_PERSONENS') . '

                                            </span>';
    $rechercherHome .= '</div>';
    $rechercherHome .= '</div>';

    $rechercherHome .= '<div class="row annuler-row">';
    $rechercherHome .= '<div class="col-6">';
    $rechercherHome .= '<a class="annuler-link">Annuler</a>';
    $rechercherHome .= '</div>';
    $rechercherHome .= '<div class="col-6">';
    $rechercherHome .= '<p class="text-center"><a class="bluetext cparti-link ">C’est parti !</a></p>';
    $rechercherHome .= '</div>';
    $rechercherHome .= '</div>';

    $rechercherHome .= '</div>';
    $rechercherHome .= '</div>';

    $rechercherHome .= '</div>';
    $rechercherHome .= '</div>';


    $rechercherHome .= '</div>';


    $rechercherHome .= '<div class="col-2  text-center">';
    $rechercherHome .= '<button class="rechercherBtn"><i style="color:#1b438e;" class="fas fa-search text-white"></i></button>';
    $rechercherHome .= '</div>';
    $rechercherHome .= '</div>';
    $rechercherHome .= '<div class="row pt-2">';
    $rechercherHome .= '<div class="col-12 pl-0">';
    $rechercherHome .= '<div class="typeEtablissement ">';
    $rechercherHome .= '<div class="typeEtabissementContainer d-flex">';
    $rechercherHome .= '<p class="text-white">VOIR</p>';
    $rechercherHome .= '<div class="typeEtabissementContainerRadio form-check text-white pl-2"> ';
    $rechercherHome .= '<input type="hidden"  name="typeEstablishmentID" class="typeEstablishmentID blueBox "">';
    $rechercherHome .= '</div>';
    $rechercherHome .= '</div>';
    $rechercherHome .= '</div>';
    // $rechercherHome .= '<select name="typeEstablishment" class="sel_typeEstablishment blueBox">';
    // $rechercherHome .=    '</select>';
    // $rechercherHome .= '<input type="hidden"  name="typeEstablishmentID" class="typeEstablishmentID blueBox">';
    $rechercherHome .= '</div>';

    $rechercherHome .= '</div>';
    $rechercherHome .= '</div>';

    $rechercherHome .= '</div>';

    $rechercherHome .= '</form>';

    echo $rechercherHome;
}



/************************************************************************************************************************* */
//Funcion general que construye el html del rechercher en la página de resultados
/************************************************************************************************************************* */
function constructRechercherResults()
{

    $rechercherHome = '<form class="frmRechercher" name="frmRechercher" action="results" method="post">';

    $rechercherHome .= '<div class="rechercherHome">';
    $rechercherHome .= '<div class="container">';
    $rechercherHome .= '<div class="row">';
    $rechercherHome .= '<div class="col-12">';
    $rechercherHome .= '<div class="row">';

    $rechercherHome .= '<div class="col-12 col-lg">';
    $rechercherHome .= '<div class="blueBox selPersonas">';
    /*$rechercherHome .= '<input class="numberPersonas text-center" name="numberPersonas" /> <span class="avec-qui-partez-vous text-center">

                                    '.JText::_(MOD_CAMPINGS_RECHERCHER_AVEC_QUI_PARTEZ_VOUS).'

                                    </span>';*/
    $rechercherHome .= '<input class="numberPersonas text-center" name="numberPersonas"  /> <span class="avec-qui-partez-vous text-center "><strong>2 </strong>  ' . JText::_(' participants') . ' </span>';
    $rechercherHome .= '</div>';
    $rechercherHome .= '</div>';

    $rechercherHome .= '<div class="col-12 col-lg">';
    $rechercherHome .=  '<input type="text" name="datefilter" value="' . JText::_(MOD_CAMPINGS_RECHERCHER_DATEPICKER) . '" class="datefilter rangeDatepicker" onfocus="blur();"/>';
    $rechercherHome .= '</div>';

    $rechercherHome .= '<div class="col-12 col-lg">';
    $rechercherHome .= '<select name="typeEstablishment" class="sel_typeEstablishment blueBox">';
    $rechercherHome .= '</select>';
    $rechercherHome .= '<input type="hidden"  name="typeEstablishmentID" class="typeEstablishmentID blueBox">';
    $rechercherHome .= '</div>';

    $rechercherHome .= '<div class="col-12 col-lg">';
    $rechercherHome .= '<span class="autocomplete-image"></span>';
    $rechercherHome .= '<input type="text" class="autocomplete blueBox" name="autocomplete" >';
    $rechercherHome .= '<input type="hidden"  name="autocompleteID" class="autocompleteID blueBox">';
    // $rechercherHome .= '<div class="Rectangle">';
    // $rechercherHome .= '</div>';
    $rechercherHome .= '</div>';


    $rechercherHome .= '<div class="col-12 col-lg-1">';
    $rechercherHome .= '<button class="rechercherBtn rechercherBtn-results"><i class="fas fa-search"></i></button>';
    $rechercherHome .= '</div>';

    $rechercherHome .= '</div>';


    $rechercherHome .= '<div class="row">';
    $rechercherHome .= '<div class="col-8">';
    $rechercherHome .= '<div class="personasWindDiv" style="display:none">';
    $rechercherHome .= '<div class="container block-personas">';

    //Adultos
    $rechercherHome .= '<div class="row">';
    $rechercherHome .= '<div class="col-6">';
    $rechercherHome .= '<span>

                                            ' . JText::_(MOD_CAMPINGS_RECHERCHER_ADULTE) . '

                                            </span>';
    $rechercherHome .= '</div>';

    $rechercherHome .= '<div class="col-6 ">';
    // $rechercherHome .= '<form class="formPersonas">';
    $rechercherHome .= '<div class="value-button btnDecrease" >-</div>';
    $rechercherHome .= '<input type="number" class="number" readonly data-id="numberA" name="numberA"   value="2" />';
    $rechercherHome .= '<div class="value-button btnIncrease"  >+</div>';
    // $rechercherHome .= '</form>';
    $rechercherHome .= '</div>';
    $rechercherHome .= '</div>';

    //Enfant 6 à -12 ans
    $rechercherHome .= '<div class="row">';
    $rechercherHome .= '<div class="col-6">';
    $rechercherHome .= '<span>

                                            ' . JText::_(MOD_CAMPINGS_RECHERCHER_ENFANT_6_12) . '

                                            </span>';
    $rechercherHome .= '</div>';
    $rechercherHome .= '<div class="col-6 ">';
    // $rechercherHome .= '<form class="formPersonas">';
    $rechercherHome .= '<div class="value-button btnDecrease" >-</div>';
    $rechercherHome .= '<input type="number" class="number" readonly data-id="numberE1" name="numberE1"  value="0" />';
    $rechercherHome .= '<div class="value-button btnIncrease"  >+</div>';
    // $rechercherHome .= '</form>';
    $rechercherHome .= '</div>';
    $rechercherHome .= '</div>';

    //Enfant - de 6 ans
    $rechercherHome .= '<div class="row">';
    $rechercherHome .= '<div class="col-6">';
    $rechercherHome .= '<span>

                                            ' . JText::_(MOD_CAMPINGS_RECHERCHER_ENFANT_6) . '

                                            </span>';
    $rechercherHome .= '</div>';
    $rechercherHome .= '<div class="col-6 ">';
    // $rechercherHome .= '<form class="formPersonas">';
    $rechercherHome .= '<div class="value-button btnDecrease"  >-</div>';
    $rechercherHome .= '<input type="number" class="number" readonly data-id="numberE2" name="numberE2"  value="0" />';
    $rechercherHome .= '<div class="value-button btnIncrease"  >+</div>';
    // $rechercherHome .= '</form>';
    $rechercherHome .= '</div>';
    $rechercherHome .= '</div>';

    //Bébé -de 2 ans
    $rechercherHome .= '<div class="row">';
    $rechercherHome .= '<div class="col-6">';
    $rechercherHome .= '<span>

                                            ' . JText::_(MOD_CAMPINGS_RECHERCHER_BEBE) . '

                                            </span>';
    $rechercherHome .= '</div>';
    $rechercherHome .= '<div class="col-6 ">';
    //  $rechercherHome .= '<form class="formPersonas">';
    $rechercherHome .= '<div class="value-button btnDecrease"  >-</div>';
    $rechercherHome .= '<input type="number" class="number" readonly data-id="numberB" name="numberB"  value="0" />';
    $rechercherHome .= '<div class="value-button btnIncrease"   >+</div>';
    //  $rechercherHome .= '</form>';
    $rechercherHome .= '</div>';
    $rechercherHome .= '</div>';

    $rechercherHome .= '<div class="row">';
    $rechercherHome .= '<div class="col-12">';
    $rechercherHome .= '<span class="maxPersPermitted" style="display:none;">

                                            ' . JText::_(MOD_CAMPINGS_RECHERCHER_PLUS_14_PERSONENS) . '

                                            </span>';
    $rechercherHome .= '</div>';
    $rechercherHome .= '</div>';


    $rechercherHome .= '<div class="row annuler-row">';
    $rechercherHome .= '<div class="col-6">';
    $rechercherHome .= '<a class="annuler-link" >Annuler</a>';
    $rechercherHome .= '</div>';
    $rechercherHome .= '<div class="col-6">';
    $rechercherHome .= '<p class="text-center"><a class="bluetext cparti-link ">C’est parti !</a></p>';
    $rechercherHome .= '</div>';
    $rechercherHome .= '</div>';

    $rechercherHome .= '</div>';
    $rechercherHome .= '</div>';

    $rechercherHome .= '</div>';
    $rechercherHome .= '</div>';


    $rechercherHome .= '</div>';

    $rechercherHome .= '</div>';

    $rechercherHome .= '</div>';

    $rechercherHome .= '</div>';

    $rechercherHome .= '</form>';

    echo $rechercherHome;
}



/************************************************************************************************************************* */
//Funcion general que construye el html del rechercher en la página de resultados
/************************************************************************************************************************* */
function constructRechercherMenu()
{


    $rechercherHome = '<form class="frmRechercherMenu container greycbackground" name="frmRechercher" action="results" method="post">';

    $rechercherHome .= '<div class="rechercherHome">';
    $rechercherHome .= '<div class="container">';
    $rechercherHome .= '<div class="row">';
    $rechercherHome .= '<div class="col-12">';
    $rechercherHome .= '<div class="row">';
    $rechercherHome .= '<div class="col-12 col-lg">';
    $rechercherHome .=  '<input type="text" name="datefilter-menu" value="' . JText::_(MOD_CAMPINGS_RECHERCHER_DATEPICKER) . '" class="rangeDatepicker datefilter-menu bluedbackground" onfocus="blur();"/>';
    $rechercherHome .= '</div>';
    $rechercherHome .= '<div class="col-12 col-lg">';
    $rechercherHome .= '<div class="blueBox selPersonas-menu bluedbackground">';
    /* $rechercherHome .= '<input class="numberPersonas-menu text-center" name="numberPersonas" /> <span class="avec-qui-partez-vous-menu text-center">

                                    '.JText::_(MOD_CAMPINGS_RECHERCHER_AVEC_QUI_PARTEZ_VOUS).'

                                    </span>';*/
    $rechercherHome .= '<input class="numberPersonas text-center" name="numberPersonas"  /> <span class="avec-qui-partez-vous text-center "><strong>2 </strong>  ' . JText::_(' participants') . ' </span>';
    $rechercherHome .= '</div>';
    $rechercherHome .= '</div>';



    $rechercherHome .= '<div class="col-12 col-lg">';
    $rechercherHome .= '<select name="typeEstablishment-menu" class="sel_typeEstablishment-menu blueBox bluedbackground">';
    $rechercherHome .= '</select>';
    $rechercherHome .= '<input type="hidden"  name="typeEstablishmentID-menu" class="typeEstablishmentID blueBox">';
    $rechercherHome .= '</div>';

    $rechercherHome .= '<div class="col-12 col-lg">';
    $rechercherHome .= '<span class="autocomplete-image-menu"></span>';
    $rechercherHome .= '<input type="text" class="autocomplete-menu blueBox bluedbackground" name="autocomplete-menu" data-disable-touch-keyboard>';
    $rechercherHome .= '<input type="hidden"  name="autocompleteID-menu" class="autocompleteID-menu blueBox bluedbackground">';
    $rechercherHome .= '<div class="Rectangle-menu">';
    $rechercherHome .= '</div>';
    $rechercherHome .= '</div>';


    $rechercherHome .= '<div class="col-12 col-lg-1">';
    $rechercherHome .= '<button class="rechercherBtn rechercherBtn-results"><i class="fas fa-search"></i></button>';
    $rechercherHome .= '</div>';

    $rechercherHome .= '</div>';


    $rechercherHome .= '<div class="row">';
    $rechercherHome .= '<div class="col-8">';
    $rechercherHome .= '<div class="personasWindDiv-menu" style="display:none">';
    $rechercherHome .= '<div class="container block-personas-menu">';

    //Adultos
    $rechercherHome .= '<div class="row">';
    $rechercherHome .= '<div class="col-6">';
    $rechercherHome .= '<span>

                                            ' . JText::_(MOD_CAMPINGS_RECHERCHER_ADULTE) . '

                                            </span>';
    $rechercherHome .= '</div>';

    $rechercherHome .= '<div class="col-6 ">';
    //  $rechercherHome .= '<form class="formPersonas">';
    $rechercherHome .= '<div class="value-button btnDecrease-menu">-</div>';
    $rechercherHome .= '<input type="number" class="number numberA" data-id="numberA" name="numberA" value="2" />';
    $rechercherHome .= '<div class="value-button btnIncrease-menu"  >+</div>';
    //   $rechercherHome .= '</form>';
    $rechercherHome .= '</div>';
    $rechercherHome .= '</div>';

    //Enfant 6 à -12 ans
    $rechercherHome .= '<div class="row">';
    $rechercherHome .= '<div class="col-6">';
    $rechercherHome .= '<span>

                                            ' . JText::_(MOD_CAMPINGS_RECHERCHER_ENFANT_6_12) . '

                                            </span>';
    $rechercherHome .= '</div>';
    $rechercherHome .= '<div class="col-6 ">';
    //  $rechercherHome .= '<form class="formPersonas">';
    $rechercherHome .= '<div class="value-button btnDecrease-menu" >-</div>';
    $rechercherHome .= '<input type="number" class="number numberE1" data-id="numberE1" name="numberE1" value="0" />';
    $rechercherHome .= '<div class="value-button btnIncrease-menu"  >+</div>';
    //  $rechercherHome .= '</form>';
    $rechercherHome .= '</div>';
    $rechercherHome .= '</div>';

    //Enfant - de 6 ans
    $rechercherHome .= '<div class="row">';
    $rechercherHome .= '<div class="col-6">';
    $rechercherHome .= '<span>

                                            ' . JText::_(MOD_CAMPINGS_RECHERCHER_ENFANT_6) . '

                                            </span>';
    $rechercherHome .= '</div>';
    $rechercherHome .= '<div class="col-6 ">';
    // $rechercherHome .= '<form class="formPersonas">';
    $rechercherHome .= '<div class="value-button btnDecrease-menu"  >-</div>';
    $rechercherHome .= '<input type="number" class="number numberE2" data-id="numberE2" name="numberE2" value="0" />';
    $rechercherHome .= '<div class="value-button btnIncrease-menu"  >+</div>';
    // $rechercherHome .= '</form>';
    $rechercherHome .= '</div>';
    $rechercherHome .= '</div>';

    //Bébé -de 2 ans
    $rechercherHome .= '<div class="row">';
    $rechercherHome .= '<div class="col-6">';
    $rechercherHome .= '<span>

                                            ' . JText::_(MOD_CAMPINGS_RECHERCHER_BEBE) . '

                                            </span>';
    $rechercherHome .= '</div>';
    $rechercherHome .= '<div class="col-6 ">';
    // $rechercherHome .= '<form class="formPersonas">';
    $rechercherHome .= '<div class="value-button btnDecrease-menu"  >-</div>';
    $rechercherHome .= '<input type="number" class="number numberB" data-id="numberB" name="numberB" value="0" />';
    $rechercherHome .= '<div class="value-button btnIncrease-menu"   >+</div>';
    //  $rechercherHome .= '</form>';
    $rechercherHome .= '</div>';
    $rechercherHome .= '</div>';

    $rechercherHome .= '<div class="row">';
    $rechercherHome .= '<div class="col-12">';
    $rechercherHome .= '<span class="maxPersPermitted-menu" style="display:none;">

                                            ' . JText::_('MOD_CAMPINGS_RECHERCHER_PLUS_6_PERSONENS') . '

                                            </span>';
    $rechercherHome .= '</div>';
    $rechercherHome .= '</div>';


    $rechercherHome .= '<div class="row annuler-row">';
    $rechercherHome .= '<div class="col-6">';
    $rechercherHome .= '<a class="annuler-link" >Annuler</a>';
    $rechercherHome .= '</div>';
    $rechercherHome .= '<div class="col-6">';
    $rechercherHome .= '<p class="text-center"><a class="bluetext cparti-link-menu ">C’est parti !</a></p>';
    $rechercherHome .= '</div>';
    $rechercherHome .= '</div>';

    $rechercherHome .= '</div>';
    $rechercherHome .= '</div>';

    $rechercherHome .= '</div>';
    $rechercherHome .= '</div>';


    $rechercherHome .= '</div>';

    $rechercherHome .= '</div>';

    $rechercherHome .= '</div>';

    $rechercherHome .= '</div>';

    $rechercherHome .= '</form>';

    return $rechercherHome;
}


/************************************************************************************************************************* */
//Funcion general que construye el html del rechercher de la home
/************************************************************************************************************************* */
function constructRechercherEstablessiment()
{



    $saison = $page['saison'];
    $titlerechercheretab = ($_COOKIE['saison'] == 1) ? 'MOD_CAMPINGS_A_VOUS_LA_MONTAGNE' : 'MOD_CAMPINGS_A_VOUS_LA_SKI';

    //$rechercherHome = '<form class="frmRechercher" name="frmRechercher" action="results" method="post">';

    $rechercherHome .= '<div class="rechercherEtablissement bluedbackground m-3">';
    $rechercherHome .= '<p class="blanctext text-uppercase text-center p-3" >' . JText::_($titlerechercheretab) . '</p>';
    $rechercherHome .= '<div class="container">';
    $rechercherHome .= '<div class="row">';
    $rechercherHome .= '<div class="col-12">';
    $rechercherHome .= '<div class="row">';
    $rechercherHome .= '<div class="col-12 selPersonascol">';
    $rechercherHome .= '<div class="blueBox selPersonas">';
    /* $rechercherHome .= '<input class="numberPersonas text-center" name="numberPersonas"/> <span class="avec-qui-partez-vous text-center">


                                    '.JText::_(MOD_CAMPINGS_RECHERCHER_AVEC_QUI_PARTEZ_VOUS).'

                                    </span>';*/
    $rechercherHome .= '<input class="numberPersonas text-center" name="numberPersonas"  /> <span class="avec-qui-partez-vous text-center "><strong>2 </strong>  ' . JText::_(' fous de montagne') . ' </span>';
    $rechercherHome .= '</div>';
    $rechercherHome .= '<div class="personasWindDiv" style="display:none">';
    $rechercherHome .= '<div class="container block-personas">';

    //Adultos
    $rechercherHome .= '<div class="row">';
    $rechercherHome .= '<div class="col-6 tipo-persona">';
    $rechercherHome .= '<span>


                                            ' . JText::_(MOD_CAMPINGS_RECHERCHER_ADULTE) . '

                                            </span>';
    $rechercherHome .= '</div>';

    $rechercherHome .= '<div class="col-6 ">';
    $rechercherHome .= '<form class="formPersonas">';
    $rechercherHome .= '<div class="value-button btnDecrease">-</div>';
    $rechercherHome .= '<input type="number" class="number numberA" data-id="numberA" name="numberA" value="2" />';
    $rechercherHome .= '<div class="value-button btnIncrease" >+</div>';
    $rechercherHome .= '</form>';
    $rechercherHome .= '</div>';
    $rechercherHome .= '</div>';

    //Enfant 6 à -12 ans
    $rechercherHome .= '<div class="row">';
    $rechercherHome .= '<div class="col-6 tipo-persona">';
    $rechercherHome .= '<span>

                                            ' . JText::_(MOD_CAMPINGS_RECHERCHER_ENFANT_6_12) . '

                                            </span>';
    $rechercherHome .= '</div>';
    $rechercherHome .= '<div class="col-6 ">';
    $rechercherHome .= '<form class="formPersonas">';
    $rechercherHome .= '<div class="value-button btnDecrease">-</div>';
    $rechercherHome .= '<input type="number" class="number numberE1" data-id="numberE1" name="numberE1" value="0" />';
    $rechercherHome .= '<div class="value-button btnIncrease" >+</div>';
    $rechercherHome .= '</form>';
    $rechercherHome .= '</div>';
    $rechercherHome .= '</div>';

    //Enfant - de 6 ans
    $rechercherHome .= '<div class="row">';
    $rechercherHome .= '<div class="col-6 tipo-persona">';
    $rechercherHome .= '<span>

                                            ' . JText::_(MOD_CAMPINGS_RECHERCHER_ENFANT_6) . '

                                            </span>';
    $rechercherHome .= '</div>';
    $rechercherHome .= '<div class="col-6 ">';
    $rechercherHome .= '<form class="formPersonas">';
    $rechercherHome .= '<div class="value-button btnDecrease" >-</div>';
    $rechercherHome .= '<input type="number" class="number numberE2" data-id="numberE2" name="numberE2"  value="0" />';
    $rechercherHome .= '<div class="value-button btnIncrease" >+</div>';
    $rechercherHome .= '</form>';
    $rechercherHome .= '</div>';
    $rechercherHome .= '</div>';

    //Bébé -de 2 ans
    $rechercherHome .= '<div class="row">';
    $rechercherHome .= '<div class="col-6 tipo-persona">';
    $rechercherHome .= '<span>

                                            ' . JText::_(MOD_CAMPINGS_RECHERCHER_BEBE) . '

                                            </span>';
    $rechercherHome .= '</div>';
    $rechercherHome .= '<div class="col-6 ">';
    $rechercherHome .= '<form class="formPersonas">';
    $rechercherHome .= '<div class="value-button btnDecrease" >-</div>';
    $rechercherHome .= '<input type="number" class="number numberB" data-id="numberB" name="numberB"  value="0" />';
    $rechercherHome .= '<div class="value-button btnIncrease"  >+</div>';
    $rechercherHome .= '</form>';
    $rechercherHome .= '</div>';
    $rechercherHome .= '</div>';

    $rechercherHome .= '<div class="row">';
    $rechercherHome .= '<div class="col-12 maxPersPermittedcol">';
    $rechercherHome .= '<span class="maxPersPermitted" style="display:none;">

                                            ' . JText::_(MOD_CAMPINGS_RECHERCHER_PLUS_14_PERSONENS) . '

                                            </span>';
    $rechercherHome .= '</div>';
    $rechercherHome .= '</div>';


    $rechercherHome .= '<div class="row annuler-row">';
    $rechercherHome .= '<div class="col-6">';
    $rechercherHome .= '<a class="annuler-link">Annuler</a>';
    $rechercherHome .= '</div>';
    $rechercherHome .= '<div class="col-6">';
    $rechercherHome .= '<p class="text-center"><a class="bluetext cparti-link " >C’est parti !</a></p>';
    $rechercherHome .= '</div>';
    $rechercherHome .= '</div>';

    $rechercherHome .= '</div>';
    $rechercherHome .= '</div>';

    $rechercherHome .= '</div>';
    $rechercherHome .= '<div class="col-12 datefiltercol">';
    $rechercherHome .= '<input type="text" name="datefilter" value="' . JText::_(MOD_CAMPINGS_RECHERCHER_DATEPICKER) . '" class="datefilter rangeDatepicker" onfocus="blur();"/>';
    $rechercherHome .= '</div>';
    $rechercherHome .= '<div class="col-12 rechercherBtncol">';
    $rechercherHome .= '<button class="rechercherBtn blanctext" onclick="Entramoserach();"><i class="fas fa-search"></i>' . JText::_('MOD_CAMPINGS_TARIFS_AND_DISPOS') . '</button>';
    $rechercherHome .= '</div>';
    $rechercherHome .= '</div>';


    $rechercherHome .= '<div class="row">';
    $rechercherHome .= '<div class="col-12">';
    $rechercherHome .= '<span class="maxPersPermitted" style="display:none;">

                                ' . JText::_(MOD_CAMPINGS_RECHERCHER_PLUS_14_PERSONENS) . '

                                </span>';
    $rechercherHome .= '</div>';
    $rechercherHome .= '</div>';


    //     $rechercherHome .= '<div class="row annuler-row">';
    //     $rechercherHome .= '<div class="col-6">';
    //         $rechercherHome .= '<a class="annuler-link" href="#">Annuler</a>';
    //     $rechercherHome .= '</div>';
    //     $rechercherHome .= '<div class="col-6">';
    //         $rechercherHome .= '<p class="text-center"><a class="bluetext cparti-link ">C’est parti !</a></p>';
    //     $rechercherHome .= '</div>';
    // $rechercherHome .= '</div>';


    $rechercherHome .= '</div>';
    /*$rechercherHome .= '<div class="col-12">';
                    $rechercherHome .= '<button class="rechercherBtn blanctext"><i class="fas fa-search"></i>'.JText::_('MOD_CAMPINGS_TARIFS_AND_DISPOS').'</button>';
                    $rechercherHome .= '</div>';*/
    $rechercherHome .= '</div>';
    $rechercherHome .= '</div>';

    $rechercherHome .= '</div>';

    //$rechercherHome .= '</form>';

    echo $rechercherHome;
}


/************************************************************************************************************************* */
//Funcion general que construye el html del rechercher del menu ACCËDEZ A VOTRE RESERVATION
/************************************************************************************************************************* */
function constructRechercherMenuVotreR()
{

    $votreR = '';
    $votreR = '<div id="errorinputs" ></div>';

    $votreR .= '<form id="opensaisson" class="frmVotreR form-inline" name="frmVotreR" action="https://sitenew.preprod.mmv.resalys.com/rsl/clickbooking?display=customer_area&customer_area_sub_page=all_reservations&webuser=web2019" method="post">';
    //$votreR .= '<form id="opensaisson" class="frmVotreR form-inline" name="frmVotreR" action="http://new.mmv.fr/rsl/clickbooking?display=customer_area&customer_area_sub_page=all_reservations&webuser=web2019" method="post">';
    //$votreR .= '<form id="opensaisson" class="frmVotreR form-inline" name="frmVotreR" action="https://preproddev.mmv.resalys.com/rsl/clickbooking?display=customer_area&auth_sub_page=all_reservation" method="post">';

    $votreR .= '<div class="form-group mb-2">';
    $votreR .= '<input id="existing_customer_login" name="existing_customer_login" type="text" class="form-control" data-rule-required="true" placeholder="' . JText::_(MOD_CAMPINGS_RECHERCHER_MENU_USER) . '">';
    $votreR .= '</div>';

    $votreR .= '<div class="form-group mx-sm-3 mb-2">';
    $votreR .= '<input id="existing_customer_password" name="existing_customer_password" type="password" class="form-control" data-rule-required="true" placeholder="' . JText::_(MOD_CAMPINGS_RECHERCHER_MENU_PASS) . '">';
    $votreR .= '</div>';

    $votreR .= '<input type="hidden" name="template" value="">';
    $votreR .= '<input type="hidden" name="popup" value=""><input type="hidden" name="display" value="">';
    $votreR .= '<input type="hidden" name="tokens" value="MzM3MTU2NjgyMDI4MDU1OA">';
    $votreR .= '<input type="hidden" name="actions" value=""><input type="hidden" name="previous_display" value="authentication">';
    $votreR .= '<input type="hidden" name="page_after_auth" value="customer_area"><input type="hidden" name="webuser" value="web">';
    $votreR .= '<input type="hidden" name="session" value="';
    if (isset($_COOKIE['sessionResalys'])) :
        $votreR .= $_COOKIE['sessionResalys'];
    endif;
    $votreR .= '"><input type="hidden" name="formAction" value="">';
    $votreR .= '<input type="hidden" name="page_before_auth" value="customer_area"><input type="hidden" name="sub_page" value="">';
    $votreR .= '<input type="hidden" name="auth_sub_page" value="authentication_customer_area.htm">';

    //$votreR .= '<button type="submit" class="btn btn-primary mb-2">'.JText::_(MOD_CAMPINGS_RECHERCHER_MENU_CONECT).'</button>';
    $votreR .= '<div class="btn btn-primary mb-2" onclick="opensaisson();">' . JText::_(MOD_CAMPINGS_RECHERCHER_MENU_CONECT) . '</div>';



    $votreR .= '</form>';

    $votreR .=  '<a class="nav-link motdepasse" href="http://new.mmv.fr/rsl/clickbooking?page_after_auth=customer_area&webuser=web2019&display=forget_password&formAction=&tokens=MzM3MTU2NjgzNzkyNTIzNw&page_before_auth=customer_area&actions=displayForgetPasswordForm&" target="_blank">' . JText::_(MOD_CAMPINGS_RECHERCHER_MENU_PASSREQUIRE) . '</a>';

    $votreR .=  '<a class="nav-link active creercompte" href="http://new.mmv.fr/rsl/clickbooking?tokens=ignore_token&display=edit_customer&webuser=web2019&page_after_auth=authentication" tagret="_blank">' . JText::_(MOD_CAMPINGS_RECHERCHER_MENU_NEWACCOUNT) . '</a>';


    return $votreR;
}

function constructRechercherMenuVotreRLogin()
{

    $votreRLogin = '';
    $votreRLogin .=  '<p class="float-left p-3 blueexdtext"><strong>Bonjour ' . $_COOKIE['customer_firstname'] . ' ' . $_COOKIE['customer_lastname'] . '</strong></p>';
    $votreRLogin .=  '<p href="promotions" class="float-right blueexdbackground blanctext desconexion-button p-2 btn " onclick="delatesaissonresalys();">Desconexión</p>';
    return $votreRLogin;
}


/************************************************************************************************************************* */
//Funcion general que construye el html del rechercher del menu ACCËDEZ A VOTRE SEJOUR
/************************************************************************************************************************* */
function constructRechercherMenuVotreS()
{

    $etabsSejours = getAllEtabsSejours();
    //print_r($etabsSejours);

    $votreS = '';

    $votreS .= '<form class="frmVotreS form-inline" name="frmVotreS" >';

    $votreS .= '<div class="form-group mb-4">';
    $votreS .= '<select name="votreSejour-menu" id="votreSejour-menu">';

    $votreS .= '<option>Sélectionnez votre Club</option>';
    foreach ($etabsSejours as  $etab) :
        if ($etab->preparezvotresejour != '') :
            $votreS .= '<option value="https://portal.mmv.fr/' . $etab->preparezvotresejour . '">' . $etab->nom . '</option>';
        endif;
    endforeach;
    $votreS .= '</select>';
    $votreS .= '</div>';

    $votreS .= '<button type="submit" class="btn btn-primary mb-2">OK</button>';

    $votreS .= '</form>';

    return $votreS;
}


/************************************************************************************************************************* */
//Funcion general que construye el html del nav rechercher del menu 
/************************************************************************************************************************* */

function constructRechercherNavMenu()
{

    $htmlNavMenu =  '';

    $htmlNavMenu .= '<ul class="nav nav-tabs" role="tablist">';
    $htmlNavMenu .= '<li class="nav-item">';
    $htmlNavMenu .= '<a class="nav-link active" href="#rechercher" role="tab" data-toggle="tab">' . JText::_(MOD_CAMPINGS_RECHERCHER_MENU_TITLETAB1) . '</a>';
    $htmlNavMenu .= '</li>';
    $htmlNavMenu .= '<li class="nav-item">';
    $htmlNavMenu .= '<a class="nav-link" href="#votrereservation" role="tab" data-toggle="tab">' . JText::_(MOD_CAMPINGS_RECHERCHER_MENU_TITLETAB2) . '</a>';
    $htmlNavMenu .= '</li>';
    $htmlNavMenu .= '<li class="nav-item">';
    $htmlNavMenu .= '<a class="nav-link" href="#votresejour" role="tab" data-toggle="tab">' . JText::_(MOD_CAMPINGS_RECHERCHER_MENU_TITLETAB3) . '</a>';
    $htmlNavMenu .= '</li>';
    $htmlNavMenu .= '</ul>';
    $htmlNavMenu .= '<div class="closnavmenu">X</div>';

    $htmlNavMenu .= '<div class="tab-content">';
    $htmlNavMenu .= '<div role="tabpanel" class="tab-pane fade in active show" id="rechercher">';
    $htmlNavMenu .= constructRechercherMenu();
    $htmlNavMenu .= '</div>';
    $htmlNavMenu .= '<div role="tabpanel" class="tab-pane fade" id="votrereservation">';


    if (!isset($_COOKIE['sessionResalys'])) :
        $htmlNavMenu .= constructRechercherMenuVotreR();
    else :
        $htmlNavMenu .= constructRechercherMenuVotreRLogin();
    endif;
    $htmlNavMenu .= '</div>';
    $htmlNavMenu .= '<div role="tabpanel" class="tab-pane fade" id="votresejour">';
    $htmlNavMenu .= constructRechercherMenuVotreS();
    $htmlNavMenu .= '</div>';
    $htmlNavMenu .= '</div> ';

    echo $htmlNavMenu;
}
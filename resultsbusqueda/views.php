<?php

/**
 * @version     CVS: 1.0.0
 * @package     com_campings
 * @subpackage  mod_campings
 * @author      unixdata <web@thelis.es>
 * @copyright   unixdata
 * @license     Licencia Pública General GNU versión 2 o posterior. Consulte LICENSE.txt

 * Results busqueda
 */

require_once  'function.php';


$host = 'https://www.mmv.fr/';

/************************************************************************************************************************* */
//Ordenar disponibilidades de menora a mayor precio
/************************************************************************************************************************* */
function sort_by_orden($a, $b)
{
    return $a['price'] - $b['price'];
}

function order_by_score_endgame($a, $b)
{
    if ($a['base_product_code'] == $b['base_product_code']) {
        // score is the same, sort by endgame
        if ($a['price'] > $b['price']) return 1;
    }
    // sort the higher score first:
    return $a['base_product_code'] < $b['base_product_code'] ? 1 : -1;
}

/************************************************************************************************************************* */
//Funcion general que construye el html del rechercher en el menú
/************************************************************************************************************************* */
function constructHtmlResultsBusqueda($resultHeberg, $temporada, $lang)
{

    $htmlGeneral = '';

    foreach ($resultHeberg as $hebergement) {


        //$lang = ModCampingsHelper::getLang();
        $host = ModCampingsHelper::getHost();

        $colortext = ($hebergement['establecimiento']->gamme == 2) ? 'bluetext' : 'greentext';
        $decouvrez = ($hebergement['establecimiento']->gamme == 2) ? 'MOD_CAMPINGS_CEST_HOTEL' : 'MOD_CAMPINGS_CEST_RESIDENCE';

        $html = '';

        //Es la manera que tengo de diferenciar hotel de residencia
        if ($hebergement['establecimiento']->gamme == 2) {
            $gamma = 'hotel';
        } else {
            $gamma = 'résidence';
        }



        $html .=  '<div class="containerHebergement ">';
        $html .=  '<div class="row">';
        $html .=  '<div class="col-12 col-lg-7">';

        //$imgRut = '../../../images/etablessiments/vignettes/'.$hebergement['establecimiento']->alias.'.jpg';
        //$imgPath = $host.'images/etablessiments/vignettes/'.$hebergement['establecimiento']->alias.'.jpg';

        $imgRut = '../../../images/etablissements/' . $hebergement['establecimiento']->alias . '/slide';
        $imgPath = $host . 'images/etablissements/' . $hebergement['establecimiento']->alias . '/slide';

        if (file_exists($imgRut)) :

            $ficheros  = scandir($imgRut);
            foreach ($ficheros as $key => $value) {
                if (preg_match("/\.(png|gif|jpe?g|bmp)/", $value, $m)) {
                    $images[] = $value;
                }
            }

            $imgsansextension = substr($value, 0, -4); //lien sans extension
            $extension = substr_replace($value, '', 0, -4); //extension
            $thumb = '../images/etablissements/' . $hebergement['establecimiento']->alias . '/thumbs/' . $imgsansextension . '_thumb_380x225' . $extension;

            if (!file_exists('../../' . $thumb)) :
                $img = $host . 'images/etablissements/default.jpg';
                $thumb = $img;

            endif;
            $img = ($value == '..') ? $host . 'images/etablissements/default.jpg' : $imgPath . '/' . $value;
        //$img = $imgPath.'/'.$value;
        else :
            $img = $host . 'images/etablissements/default.jpg';
            $thumb = $img;
        endif;

        $html .= '<div class="contentimg">';
        //$html .= '<h3 class="blanctext">'.$hebergement['establecimiento']->ExtSkiDomNom.'</h3>';
        $html .= '<h3 class="blanctext">' . $hebergement['establecimiento']->ItmExtStationCommune . '</h3>';
        $html .= '<img src="' . $thumb . '" alt="' . $hebergement['establecimiento']->nom . '" title="' . $hebergement['establecimiento']->nom . '" />';
        $html .= '</div>';

        // BLOQUE GAMMA
        $html .= '<div class="float-left categorieicon">';
        /*if($hebergement['establecimiento']->gamme == 2):
                        $html .= '<div class=" "><img class="d-block" src="'.$host.'images/icons/hotel-club.png" alt="'.$hebergement['establecimiento']->nom. '" title="'.$hebergement['establecimiento']->nom. '" /></div>';
                    else:
                        $html .= '<div class=" rclub-logo "><img class="d-block" src="'.$host.'images/icons/residence-club.png" alt="'.$hebergement['establecimiento']->nom. '" title="'.$hebergement['establecimiento']->nom. '" /></div>';
                    endif;*/

        if ($hebergement['establecimiento']->categoryeicone != '0') :
            $html .= '<div class=" "><img class="d-block" src="../' . $hebergement['establecimiento']->categoryeicone . '" alt="' . $hebergement['establecimiento']->nom . '" title="' . $hebergement['establecimiento']->nom . '" /></div>';

        endif;

        /* if($hebergement['establecimiento']->etoiles!=''):
                            $html .= '<p class="text-center">';
                            for ($etoiles = 0; $etoiles < $hebergement['establecimiento']->etoiles; $etoiles++){$html .= '<span class="'.$colortext.'">*</span>'; }
                            $html .= '</p>';
                    endif; */
        $html .= '</div>';

        $dir = "../../../images/etablissements/" . $hebergement['establecimiento']->alias . "/galerie/";
        $ficheros1  = scandir($dir);
        $total_imagenes = count($ficheros1);

        $html .= '<div class="resultb-galleryicon">';
        $html .= '<a class="btn-gallery" data-alias="' . $hebergement['establecimiento']->alias . '" href="#"><img src="/images/icons/rb_more.png"><span class="blanctext">' . $total_imagenes . '</span></a>';
        $html .= '</div>';


        $html .= '<div class="resultb-mapicon">';
        $html .= '<button type="button" class="button-map-resultb btn" data-lat="' . $hebergement['establecimiento']->gpsLat . '" data-lon="' . $hebergement['establecimiento']->gpsLon . '" data-toggle="modal" data-target="#etab_' . $hebergement['establecimiento']->codeCRM . '">';
        $html .= '<img class="d-block" src="' . $host . 'images/icons/map.png" alt="' . $hebergement['establecimiento']->nom . '" title="' . $hebergement['establecimiento']->nom . '" />';
        $html .= '</button>';
        $html .= '</div>';

        $html .=  '<div id="etab_' . $hebergement['establecimiento']->codeCRM . '" class="modal fade modalmapa-resultb" role="dialog">';
        $html .=  '<div class="modal-dialog">';
        $html .=  '<div class="modal-content">';
        $html .=  '<div class="modal-body">';

        $html .=  '</div>';
        $html .=  '<div class="modal-footer">';
        $html .=  ' <button type="button" class="btn btn-default" data-dismiss="modal">' . JText::_('MOD_CAMPINGS_FERMER') . '</button>';
        $html .=  '</div>';
        $html .=  '</div>';
        $html .=  '</div>';
        $html .=  '</div>';

        $html .= '</div>';
        $html .=  '<div class="col-12 col-lg-5">';

        //Definir la url del link para cgos
        $urlMenuByLang = $host . $hebergement['establecimiento']->menu;
        if ($lang == 'bs-ba') {
            $urlMenuByLang = $host . 'cgos/' . $hebergement['establecimiento']->menu;
        } else if ($lang == 'fr-ca') {
            $urlMenuByLang = $host . 'macif/' . $hebergement['establecimiento']->menu;
        }


        $html .= '<form class="formByResult" action="' . $urlMenuByLang . '" method="post">';
        $html .= '<button class="btnTitleResultEtab"><h2 class="h2Resulthebergements">' . $hebergement['establecimiento']->nom . '</h2></button>';
        $html .= '<input name="datefilter" class="datefilterByResult" class="btn btn-primary" type="text" hidden value="">';
        $html .= '<input name="numberA" class="numberPersByResult numberA" class="btn btn-primary" type="text" hidden value="">';
        $html .= '<input name="numberE1" class="numberPersByResult numberE1" class="btn btn-primary" type="text" hidden value="">';
        $html .= '<input name="numberE2" class="numberPersByResult numberE2" class="btn btn-primary" type="text" hidden value="">';
        $html .= '<input name="numberB" class="numberPersByResult numberB" class="btn btn-primary" type="text" hidden value="">';
        $html .= '</form>';

        // $html .= '<a class="" href="'.$host.$hebergement['establecimiento']->menu.'"><h2 class="h2Resulthebergements">'.$hebergement['establecimiento']->nom.'</h2></a>' ;
        if ($hebergement['establecimiento']->category != '') :
            $html .= '<p class="typehebergement ';
            if ($hebergement['establecimiento']->gamme == 1) :
                $html .= 'greentext';
            else :
                $html .= 'bluetext';
            endif;
            if ($hebergement['establecimiento']->gamme == 3) :
                $html .= 'orangetext';
            endif;
            $regiongeographique = ModCampingsHelper::getRegionGeographique($temporada, $hebergement['establecimiento']->codeCRM);

            /* $html .= '"><strong>'.$hebergement['establecimiento']->ItmExtGamme.'</strong><br/>'.$hebergement['establecimiento']->ExtSkiDomNom.' ( '.ucwords(strtolower($hebergement['establecimiento']->ItmExtStationCommune)).' , '.$regiongeographique.' )</p>';*/
            $html .= '"><strong>' . $hebergement['establecimiento']->ItmExtGamme . '</strong><br/>' . $hebergement['establecimiento']->ExtSkiDomNom . ' ( ' . ucwords(strtolower($hebergement['establecimiento']->ItmExtStationCommune)) . ' , ' . $hebergement['establecimiento']->ItmExtDepartement . ' )</p>';
        endif;

        if ($hebergement['establecimiento']->villeimg != '') :
            $html .= '<p><img class="img1ResultHebergements" src="' . $host . $hebergement['establecimiento']->villeimg . '" alt="' . $hebergement['establecimiento']->nom . '" title="' . $hebergement['establecimiento']->nom . '"></p>';
        endif;

        $logosatation = ($hebergement['establecimiento']->logostationcomplet != '') ? $hebergement['establecimiento']->logostationcomplet : $hebergement['establecimiento']->icon;


        if ($logosatation != '') :
            $html .= '<p><img class="img1ResultHebergements" src="' . $host . $logosatation . '" alt="' . $hebergement['nom'] . '" title="' . $hebergement['nom'] . '"></p>';
        endif;


        if ($hebergement['establecimiento']->AnsweredSurveys != '') :
            $html .= '<p class="d-flex align-items-center"><img class="pictoravi" src="' . $host . 'images/icons/picto-client-ravi.png" alt="' . $hebergement['establecimiento']->nom . '" title="' . $hebergement['establecimiento']->nom . '"> ';
            $html .= '<span class="bluedtext textavi"> ' . $hebergement['establecimiento']->AnsweredSurveys . ' ' . JText::_('MOD_CAMPINGS_FICHA_CLIENTS_RAVIS') . '</span></p>';
        endif;
        $html .= '<div>';
        $html .= '<form class="formByResult" action="' . $urlMenuByLang . '" method="post" target="_blank">';
        $html .= '<button class="btn btnblueResults">' . JText::_($decouvrez) . '</button>';
        $html .= '<input name="datefilter" class="datefilterByResult" class="btn btn-primary" type="text" hidden value="">';
        $html .= '<input name="numberA" class="numberPersByResult numberA" class="btn btn-primary" type="text" hidden value="">';
        $html .= '<input name="numberE1" class="numberPersByResult numberE1" class="btn btn-primary" type="text" hidden value="">';
        $html .= '<input name="numberE2" class="numberPersByResult numberE2" class="btn btn-primary" type="text" hidden value="">';
        $html .= '<input name="numberB" class="numberPersByResult numberB" class="btn btn-primary" type="text" hidden value="">';
        $html .= '</form>';
        // $html .= '<a class="btn btnblueResults" href="'.$host.$hebergement['establecimiento']->menu.'">'.JText::_($decouvrez).'</a>';
        $html .= '</div>';

        $html .= '</div>';
        $html .= '</div>';
        $tags = ModCampingsHelper::getInformationtablessimentConcret($temporada, $hebergement['establecimiento']->codeCRM, 'C005', 'Tag');
        if (!empty($tags)) :
            $html .= '<div class="row descriptionheberg">';
            $html .= '<div class="votrecluboffretop col-12 mb-3">';
            foreach ($tags as $tag) :
                $html .= '<span class="text-uppercase text-white bluedbackground text-center p-1 m-1">' . $tag->nom . '</span>';
            endforeach;
            $html .= '</div>';
            $html .= '</div>';
        endif;
        $html .= '<div class="row ">';
        $html .= '<div class="col-12">';

        $introtext = ($lang == 'en' and $hebergement['establecimiento']->introtexten != '') ? $hebergement['establecimiento']->introtexten :  $hebergement['establecimiento']->introtext;
        $description = ($lang == 'en' and $hebergement['establecimiento']->descripen != '') ? $hebergement['establecimiento']->descripen :  $hebergement['establecimiento']->descrip;


        $description = strip_tags($introtext . '<br/>' . $description);
        $html .= '<p class="descriptiontext">' . substr($description, 0, 350) . '...</p>';

        if ($hebergement['establecimiento']->gamme == 2) {
            //Construyo disponibilidad html para hoteles
            $html .= constructDispoBlockHoteles($hebergement["disponibilidades"], $temporada,  $hebergement['establecimiento']->codeCRM, $host, $lang, $hebergement['establecimiento']->menu, $gamma, $hebergement['establecimiento']->metros, $hebergement['establecimiento']->alias);
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
        } else {
            //Construyo disponibilidad html para residencias
            $html .= constructDispoBlockResidence($hebergement["disponibilidades"], $temporada,  $hebergement['establecimiento']->codeCRM, $host, $lang, $hebergement['establecimiento']->menu, $gamma, $hebergement['establecimiento']->metros, $hebergement['establecimiento']->alias);
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
        }

        $html .= '</div>';
        $html .= '</div>';

        $htmlGeneral .= $html;

        //echo $html;
    }

    return $htmlGeneral;
}


/************************************************************************************************************************* */
//Funcion general que construye html para residencias
/************************************************************************************************************************* */

// Ordenamos el objeto de resultados con precio a partir de (menor a mayor)
function cmpResultsAscDispo1($a, $b)
{
    return $a->base_product_code - $b->base_product_code;
}


function constructDispoBlockResidence($disponibilidadesXhebergement, $temporada,  $codeCRM, $host, $lang, $menu, $gamma, $metros, $alias)
{

    $tabLocationTitle = '';
    $tabLocationContent = '';
    $html = '';

    // usort($disponibilidadesXhebergement, "cmpResultsAscDispo1");

    // return json_encode($disponibilidadesXhebergement);




    //Si hay disponibilidades busco todos los tipos de alojamientos y obtengo precios...
    if (count($disponibilidadesXhebergement) > 0) {

        $tabLocationContent .= '<script>datalayerpushEtab("' . $codeCRM . '"); </script>';
        $tabLocationTitle .= '<ul class="nav nav-tabs result-busqueda-ul">';
        $tabLocationContent .= '<div class="tab-content">';


        $arrTitlesLocation = [];
        $navActive = '';
        $activeTabContent = 'active';
        $navActiveCount = 0;

        $tabTitle = '';
        $contTabHeberg = 0;
        $activeAccordion = false;
        $tabpanel = false;

        $countRandom = 0;


        //Cantidad de alojamientos que se mostraran en el acordeon
        $countHeberg = count($disponibilidadesXhebergement) - 2;
        //Ordenar disponibilidades de menor a mayor precio
        //uasort($disponibilidadesXhebergement, 'sort_by_orden');


        usort($disponibilidadesXhebergement, "order_by_score_endgame");

        $arrCountDispoByFormule = [];

        /*
            esta solucion es temporal para el contador
        */

        foreach ($disponibilidadesXhebergement as $disponibilidades) {
            array_push($arrCountDispoByFormule, $disponibilidades['base_product_code']);
        }
        $countDispoByFormule = array_count_values($arrCountDispoByFormule);



        foreach ($disponibilidadesXhebergement as $disponibilidades) {

            if (!in_array($disponibilidades['base_product_code'], $arrTitlesLocation)) {

                if ($navActiveCount == 0) {
                    $navActive = 'active';
                    $navActiveCount++;
                } else {
                    $navActive = '';

                    //Cierro el acordion
                    if (($tabpanel == false) &&  ($activeAccordion == true)) {

                        $tabLocationContent .= '</div>';
                        $tabLocationContent .= '</div>';
                        //$tabLocationContent .= '</div>';

                        $activeAccordion = false;
                        $tabpanel = true;
                        $contTabHeberg = 0;
                    }

                    //Cierro el tabpanel
                    $tabLocationContent .= '</div>';
                    $tabpanel = false;
                }

                array_push($arrTitlesLocation, $disponibilidades['base_product_code']);

                //$tabTitle = getNamebyCategcode($disponibilidades['base_product_code'], $disponibilidades['etab_id'], $temporada);
                $tabTitle = ModCampingsHelper::getNomFormule($disponibilidades['base_product_code'],  $disponibilidades['etab_id'], 2/*$temporada*/);
                $tabIcon = ModCampingsHelper::getIconFormule($disponibilidades['base_product_code'],  $disponibilidades['etab_id'], $temporada);

                //$tabIcon = ModCampingsHelper::getIconFormule($disponibilidades['base_product_code'],  $disponibilidades['etab_id'], $temporada);

                $generateRandom = rand(1, 1000000);

                $tabLocationTitle .= '<li class="nav-item" data-order="' . $disponibilidades['ordering'] . '">';
                $tabLocationTitle .= '<a class="nav-link ' . $navActive . ' text-uppercase" href="#' . $disponibilidades['base_product_code'] . '_' . $generateRandom . '" role="tab" data-toggle="tab">';
                if ($tabIcon != '') :
                    $tabLocationTitle .= '<div class="text-center d-block iconeformule"><img src="' . $host . $tabIcon . '" alt="' . $tabTitle . '" title="' . $tabTitle . '" /></div>';
                endif;
                $tabLocationTitle .= strtoupper($tabTitle) . '</a>';
                $tabLocationTitle .= '</li>';

                $tabLocationContent .= '<div role="tabpanel" data-order="' . $disponibilidades['ordering'] . '" class="tab-pane in ' . $activeTabContent . '" id="' . $disponibilidades['base_product_code'] . '_' . $generateRandom . '">';
                $tabpanel = true;
            }

            $pers = explode('personnes', $disponibilidades['personas']);



            $tabLocationContent .= '<div class="container ' . $disponibilidades['base_product_code'] . '">';
            $tabLocationContent .= '<div class="row">';
            $tabLocationContent .= '<div class="col-12 col-lg-5 columns-roomtype">';


            $countMoreComb = 0;
            //Por cada room type
            foreach ($disponibilidades['room_types'] as $roomtype) {

                $appartements = ModCampingsHelper::getInformationAlojamientoProposals($codeCRM, $roomtype['room_type_code'], 'Typo', $temporada);

                // CREAMOS COLOR PARA LA GAMMA
                $backcolor = '';
                $textcolor = '';
                if ($appartements->OFfExtGamme != '') :
                    switch ($appartements->OFfExtGamme) {
                        case 'Premium':
                            $backcolor = 'yellowbackground';
                            $textcolor = 'blueexdtext';
                            break;
                        case 'Famille':
                            $backcolor = 'bluebackground';
                            $textcolor = 'blanctext';
                            break;
                        case 'Famille':
                            $backcolor = 'bluebackground';
                            $textcolor = 'blanctext';
                            break;
                        case 'Famille +':
                            $backcolor = 'bluebackground';
                            $textcolor = 'blanctext';
                            break;
                        case 'Standard':
                            $backcolor = 'greybackground';
                            $textcolor = 'blanctext';
                            break;

                        default:
                            $backcolor = 'blanctext';
                            $textcolor = 'bluedbackground';
                            break;
                    }

                endif;
                // FIN CREAMOS COLOR PARA LA OFfExtGamme


                $classSymbolPlus = '';

                if ($countMoreComb > 0) {
                    $classSymbolPlus = '<span class="plus-comb">+</span>';
                }


                $capacite = str_replace("personnes", "", $appartements->OFfExtCapacite);

                $tabLocationContent .= '<div class="row">';
                $tabLocationContent .= '<div class="col-3">';
                $tabLocationContent .= '<span class="tipoappartement ' . $backcolor . ' ' . $textcolor . '">' . $appartements->OFfExtGamme . '</span>';
                $tabLocationContent .= '<div class="row">';
                $tabLocationContent .= '<div class="col-3">';
                $tabLocationContent .= '<img title="mmv" src="' . $host . '/images/icons/picto-pi-ces.png">';
                $tabLocationContent .= '</div>';
                $tabLocationContent .= '<div class="col-7 pr-0">';
                $tabLocationContent .= '<p class="tipo-num">' . $appartements->OFfExtNbPiece . '</p><p class="tipo-text">

                                ' . JText::_(MOD_CAMPINGS_RES_BUSQ_PIECES) . '

                                </p>';
                $tabLocationContent .= '</div>';
                $tabLocationContent .= '</div>';
                $tabLocationContent .= '</div>';
                $tabLocationContent .= '<div class="col-3">';
                $tabLocationContent .= '<div class="row">';
                $tabLocationContent .= '<div class="col-3">';
                $tabLocationContent .= $classSymbolPlus . '<img title="mmv" src="' . $host . '/images/icons/picto-personnes-2.png">';
                $tabLocationContent .= '</div>';
                $tabLocationContent .= '<div class="col-7 pr-0">';
                $tabLocationContent .= '<p class="tipo-num tipo-personas ' . $roomtype['room_type_code'] . '">' . $capacite . '</p><p class="tipo-text">

                                ' . JText::_(MOD_CAMPINGS_RES_BUSQ_PERS) . '

                                </p>';
                $tabLocationContent .= '</div>';
                $tabLocationContent .= '</div>';
                $tabLocationContent .= '</div>';
                $tabLocationContent .= '<div class="col-4">';
                $tabLocationContent .= '<div class="row">';
                $tabLocationContent .= '<div class="col-3">';
                $tabLocationContent .= '<img title="mmv" src="' . $host . '/images/icons/picto-surface-2.png">';
                $tabLocationContent .= '</div>';
                $tabLocationContent .= '<div class="col-7 pr-0">';
                $tabLocationContent .= '<p class="tipo-num tipo-metros">' . $appartements->ExtSuperficie . '</p><p class="tipo-text">m²</p>';
                $tabLocationContent .= '</div>';
                $tabLocationContent .= '</div>';
                $tabLocationContent .= '</div>';
                $tabLocationContent .= '<div class="col-1">';
                $tabLocationContent .= '<button type="button" class="button-appartement btn" data-toggle="modal" data-target="#' . $codeCRM . '_' . $countRandom . '"><img title="mmv" src="' . $host . '/images/icons/info.png"></button>';
                $tabLocationContent .= '</div>';
                $tabLocationContent .= '</div>';

                //modal

                $tabLocationContent .=  '<div id="' . $codeCRM . '_' . $countRandom . '" class="modal fade" role="dialog">';
                $tabLocationContent .=  '<div class="modal-dialog">';

                $tabLocationContent .=  '<div class="modal-content">';
                $tabLocationContent .=  '<div class="modal-body">';
                $tabLocationContent .=  '<h3 class="greentext">' . $roomtype['room_type_label'] . '</H3>';
                //$tabLocationContent .=  '<p >'.$disponibilidades['descrip'].'</p>';
                $tabLocationContent .=  '<p >' . $appartements->ExtDescriptifCourt . '</p>';
                $tabLocationContent .=  '<p >' . $appartements->ExtDescriptifLong . '</p>';

                $adults = ($disponibilidades['nb_adults'] == 1) ? 'Adulte' : 'Adultes';
                $adults2 = ($disponibilidades['nb_first_room_pax_max'] == 1) ? 'Adulte' : 'Adultes';
                $enfant1 = ($disponibilidades['nb_children'] == 1) ? 'enfant' : 'enfants';
                $enfant2 = ($disponibilidades['nb_children2'] == 1) ? 'enfant' : 'enfants';
                $tabLocationContent .= '<p>';
                if ($disponibilidades['nb_first_room_pax_max'] != 0) :
                    $tabLocationContent .=  'Pour ' . $disponibilidades['nb_first_room_pax_max'] . ' ' . $adults2;
                endif;
                if ($disponibilidades['nb_children'] != 0) :
                    $tabLocationContent .=  ', ' . $disponibilidades['nb_children'] . ' ' . $enfant1 . ' de 6-12 ans';
                endif;
                if ($disponibilidades['nb_children2'] != 0) :
                    $tabLocationContent .=  ', ' . $disponibilidades['nb_children2'] . ' ' . $enfant2 . ' de 2-6 ans';
                endif;
                $tabLocationContent .= '</p>';
                $tabLocationContent .=  '<p ></p>';


                $tabLocationContent .=  '</div>';
                $tabLocationContent .=  '<div class="modal-footer">';
                $tabLocationContent .=  ' <button type="button" class="btn btn-default" data-dismiss="modal">' . JText::_('MOD_CAMPINGS_FERMER') . '</button>';
                $tabLocationContent .=  '</div>';
                $tabLocationContent .=  '</div>';
                $tabLocationContent .=  '</div>';
                $tabLocationContent .=  '</div>';

                //modal
                $countMoreComb++;
            }


            $tabLocationContent .= '</div>';
            /*$tabLocationContent .= '<div class="col-3 col-lg-2 public-price pr-2 pl-2">';
                        $tabLocationContent .= '<span>'.$disponibilidades['public_price'].' € </span>';
                    $tabLocationContent .= '</div>';*/
            $tabLocationContent .= '<div class="col-3 col-lg-2 public-price pr-2 pl-2"><div>';

            if ($disponibilidades['public_price'] > $disponibilidades['price']) :
                $porcentaje = ($disponibilidades['price'] / $disponibilidades['public_price']) * 100;
                $porcentaje = 100 - $porcentaje;
                $porcentaje = round($porcentaje, 0);

                $tabLocationContent .= '<span class="descountTarifs">- ' . $porcentaje . ' % </span>';
                $tabLocationContent .=  '<span class="publicpricesansdesc">' . $disponibilidades['public_price'] . ' €  </span>';
            endif;

            // info taxes
            $tabLocationContent .= '<div class="continfotaxes">';
            $tabLocationContent .= '<img src="https://www.mmv.fr/images/icons/info.png" class="bulletinfotaxes">';
            $tabLocationContent .= '<div class="divinfotaxes">' . JText::_('MOD_CAMPINGS_TAXES_INFO') . '</div>';
            $tabLocationContent .= '</div>';

            $tabLocationContent .=  '<span>' . $disponibilidades['price'] . ' €  <span class="prixapp">/ appt.</span></span>';

            $tabLocationContent .= '</div></div>';

            $tabLocationContent .= '<div class="col-9 col-lg-5 reserveztext">';

            //$tabLocationContent .= $disponibilidades['proposal_key'];

            //modificamos la url de las residencias para que indique el numero buscado de adultos siempre que sea menor a la capacidad maxima de la residencia
            //Esto se verá reflejado en resalys, donde indicará al usuario las personas buscadas en rechercher
            //hacer tambien en Grille


            $adultosProposalKey = '';
            //$achild1ProposalKey ='';
            //$achild2ProposalKey ='';


            //CALCULAREM LA DATA D'UN ADULT
            $tempStartDatelienAdulto = convertMongoTimestampTodatePhp($disponibilidades['start_date']);
            $tempStartDatelienAdulto =  date("d-m-Y", strtotime($tempStartDatelienAdulto . "- 30 year"));

            $tempStartDatelienAdulto = explode('-', $tempStartDatelienAdulto);
            $tempStartDatelienAdulto = $tempStartDatelienAdulto[2] . '-' . $tempStartDatelienAdulto[1] . '-' . $tempStartDatelienAdulto[0];
            //$tabLocationContent .= $tempStartDatelienAdulto;

            //CALCULAREM LA DATA D'UN ADULT

            //$tabLocationContent .= $_POST['numberA'];


            if (isset($_POST['numberA']) and $_POST['numberA'] != NULL) :
                //$tabLocationContent .= $disponibilidades['nb_first_room_pax_max'].'<br/>';
                //$tabLocationContent .= $_POST['numberA'].'<br/>';
                $adultosProposalKey = $_POST['numberA'] + $_POST['numberE1'] + $_POST['numberE2'];

                if ($adultosProposalKey <= $disponibilidades['nb_first_room_pax_max']) :
                    $disponibilidades['proposal_key'] = str_replace("1(" . $tempStartDatelienAdulto, $adultosProposalKey . "(" . $tempStartDatelienAdulto, $disponibilidades['proposal_key']);
                    $disponibilidades['proposal_key'] = str_replace($roomtype['room_type_code'] . "-1-" . $roomtype['room_type_code'], $roomtype['room_type_code'] . "-" . $adultosProposalKey . "-" . $roomtype['room_type_code'], $disponibilidades['proposal_key']);
                //$tabLocationContent .= $disponibilidades['proposal_key'] ;
                //$tabLocationContent .= $roomtype['room_type_code'] ;
                endif;

            endif;
            //$tabLocationContent .= $disponibilidades['proposal_key'].'<BR/>';

            if (isset($_POST['numberB']) and $_POST['numberB'] != 0) :  //CALCULAREM LA DATA D'UN BEBE


                //DATA D'UN BEBE
                $tempStartDatelienBebe = convertMongoTimestampTodatePhp($disponibilidades['start_date']);
                $tempStartDatelienBebe =  date("d-m-Y", strtotime($tempStartDatelienBebe . "- 1 year"));

                $tempStartDatelienBebe = explode('-', $tempStartDatelienBebe);
                $tempStartDatelienBebe = $tempStartDatelienBebe[2] . '-' . $tempStartDatelienBebe[1] . '-' . $tempStartDatelienBebe[0];

                //STRING DATA BEBE URL
                $bebeProposalKey = $_POST['numberB'];
                $proposalBebe = '!' . $bebeProposalKey . '(' . $tempStartDatelienBebe . ')';

                //POSITION PROPOSALKEY DONDE AÑADIR STRRING BEBE. bUSCAREMOS POSITION DONDE SE CARGA LA ULTIMA FECHA  
                $pos = strripos($disponibilidades['proposal_key'], ')');  //(EJ:40 ES POSITION)
                //$tabLocationContent .=  $pos.'<BR/>';


                //MODIFICAMOS LA PROPOSALKEYACTUAL
                $disponibilidades['proposal_key'] = substr_replace($disponibilidades['proposal_key'],  $proposalBebe, ($pos + 1), 0);
            //$tabLocationContent .= $disponibilidades['proposal_key'];

            endif;

            if (isset($_POST['numberE1']) and $_POST['numberE1'] != NULL) :
                //$achild1ProposalKey = $_POST['numberE1'].'<br/>';
                if ($achild1ProposalKey <= $disponibilidades['nb_first_room_pax_max']) :
                //$disponibilidades['proposal_key'] = str_replace("1(2015", $achild1ProposalKey."(1989", $disponibilidades['proposal_key']);   
                //$tabLocationContent .= $disponibilidades['proposal_key'] ;
                endif;

            endif;
            if (isset($_POST['numberE2']) and $_POST['numberE2'] != NULL) :
                $achild1ProposalKey = $_POST['numberE2'] . '<br/>';
                if ($achild1ProposalKey <= $disponibilidades['nb_first_room_pax_max']) :
                //$disponibilidades['proposal_key'] = str_replace("1(2011", $achild1ProposalKey."(2011", $disponibilidades['proposal_key']);   
                //$tabLocationContent .= $disponibilidades['proposal_key'] ;
                endif;

            endif;


            $webuser = ModCampingsHelper::getWebuser($lang);
            $linkReserv = 'https://sitenew.preprod.mmv.resalys.com/rsl/clickbooking?webuser=' . $webuser . '&tokens=ignore_token&display=reservation_content&reservation_content_sub_page=reservation_occupants&actions=cancelReservation%3BchooseProposalFromKey&proposal_key=' . $disponibilidades['proposal_key'] . '&backurl=' . $host . $alias;

            $tabLocationContent .= '<a href="' . $linkReserv . '" target="_blank" >';
            $tabLocationContent .= '<div class="float-left pr-2"><img src="../images/icons/cart.png" alt="' . JText::_(MOD_CAMPINGS_RES_BUSQ_RESERVEZ_MIN) . '" title="' . JText::_(MOD_CAMPINGS_RES_BUSQ_RESERVEZ_MIN) . '" /></div>';
            $tabLocationContent .= '<div class="float-left"><p class="reserveztext">' . JText::_(MOD_CAMPINGS_RES_BUSQ_RESERVEZ_MIN) . '</p>';

            $tempStartDate = convertMongoTimestampTodatePhp($disponibilidades['start_date']);
            $tempEndDate = convertMongoTimestampTodatePhp($disponibilidades['end_date']);

            //modificamos nom date
            $dayNameBegin = dayNameByDate($tempStartDate);
            $dayNameEnd   = dayNameByDate($tempEndDate);

            //Convierto el separador de la fecha de inicio - por un punto 
            $tempStartDate = explode('-', $tempStartDate);
            $tempStartDate = $tempStartDate[2] . '.' . $tempStartDate[1] . '.' . $tempStartDate[0];

            //Convierto el separador de la fecha de fin - por un punto 
            $tempEndDate = explode('-', $tempEndDate);
            $tempEndDate = $tempEndDate[2] . '.' . $tempEndDate[1] . '.' . $tempEndDate[0];


            $tabLocationContent .= '<p class="prixdisc">' . JText::_(MOD_CAMPINGS_RES_BUSQ_DU) . ' ' . $dayNameBegin . '<strong> ' . $tempStartDate . '</strong> ' . JText::_(MOD_CAMPINGS_RES_BUSQ_AU) . ' ' . $dayNameEnd . '<strong> ' . $tempEndDate . '</strong></p></div>';
            $tabLocationContent .= '</a>';

            $tabLocationContent .= '</div>';
            $tabLocationContent .= '</div>';
            $tabLocationContent .= '</div>';



            $activeTabContent = '';
            $countRandom++;

            $counter = $countDispoByFormule[$disponibilidades['base_product_code']] - 1;

            //Si el acordeon no ha sido creado y ya se ha pintado un elemento, lo creo.
            //if(  ($contTabHeberg > 0) && ($activeAccordion == false)){
            if (($activeAccordion == false) && ($tabpanel == true) && ($counter > 0)) {

                if ($gamma == 'hotel') {
                    $titleAcc = JText::_(MOD_CAMPINGS_RESULTS_PLUS_CHAM);
                } else {
                    $titleAcc = JText::_(MOD_CAMPINGS_RESULTS_PLUS_APP);
                }

                $tabLocationContent .= '<div class="accordion_container">';
                // $tabLocationContent .= '<div class="accordion_head"><i class="fa fa-angle-down caretDown"></i>'.$titleAcc.' <span class="countElements">('.$countHeberg.')</span></div>';
                $tabLocationContent .= '<div class="accordion_head ' . $countDispoByFormule[$disponibilidades['base_product_code']] . '"><i class="fa fa-angle-down caretDown"></i>' . $titleAcc . ' <span class="countElements">(' . $counter . ')</span></div>';
                $tabLocationContent .= '<div class="accordion_body" style="display: none;">';
                $activeAccordion = true;
                $tabpanel = false;
            }

            $tabLocationContent .= '<script>datalayerpush("' . $codeCRM . '" ,"' . $roomtype['room_type_code'] . '" , "' . $roomtype['room_type_label'] . '", "' . $appartements->OFfExtGamme . '","' . $disponibilidades['public_price'] . '","' . $tempStartDate . '", "' . $tempEndDate . '"); </script>';
            $contTabHeberg++;
        }



        $tabLocationTitle .= '</ul>';
        $tabLocationContent .= '</div>';
    } else {
        $prixhva = ModCampingsHelper::getInformationtablessiment($temporada, $codeCRM, 'C005', 'Prix');
        $tabLocationContent .= '<div class="prixminsansdispo">';
        $apporperson = ($gamma == 'hotel') ? 'MOD_CAMPINGS_PER_PERS' : 'MOD_CAMPINGS_PER_APP';
        if (!empty($prixhva)) :
            $tabLocationContent .= '<span>' . JText::_('COM_CAMPINGS_SEMANIER_A_PARTIR') . ' </span>';

            /* echo '<pre>';
                        print_r($prixhva);
                        echo '</pre>';*/
            /*if($temporada == 1):*/
            /*foreach($prixhva as $scolaires):
                            if( $scolaires->sousCatCode == 'Prix VAH'):
                                $tabLocationContent .= '<span class="tachado greytext">'.$scolaires->ExtTarif.' </span>';
                            endif;

                        endforeach;
                        foreach($prixhva as $scolaires):
                            if( $scolaires->sousCatCode == 'PrixHVA'):
                                $tabLocationContent .= '<span class="public-price"><span>'.$scolaires->ExtTarif.' €</span> /app.</span>';
                            endif;
                        endforeach;*/


            foreach ($prixhva as $scolaires) :
                if ($scolaires->sousCatCode == 'PrixHVA' and $temporada == 1) :
                    $tabLocationContent .= '<span class="public-price"><span>' . $scolaires->ExtTarif . ' €</span>  ' . JText::_($apporperson) . '</span>';
                endif;
                if ($scolaires->sousCatCode == 'Prix VAH' and $temporada == 2) :
                    $tabLocationContent .= '<span class="public-price"><span>' . $scolaires->ExtTarif . ' €</span>  ' . JText::_($apporperson) . '</span>';
                endif;
            endforeach;

            // info taxes
            $tabLocationContent .= '<div class="continfotaxes">';
            $tabLocationContent .= '<img src="https://www.mmv.fr/images/icons/info.png" class="bulletinfotaxes">';
            $tabLocationContent .= '<div class="divinfotaxes">' . JText::_('MOD_CAMPINGS_TAXES_INFO') . '</div>';
            $tabLocationContent .= '</div>';


            $tabLocationContent .= '<a class="bluetext ml-3" href="' . $host . $menu . '"> <img src="images/icons/lupa.png" alt="" title="" class=""> ' . JText::_('MOD_FICHA_DISPOS_TARIFS_MIN') . '</a>';
        endif;


        $tabLocationContent .= '</div>';
    }

    $html .= $tabLocationTitle;
    $html .= $tabLocationContent;


    return $html;
}



/************************************************************************************************************************* */
//Funcion general que construye html para hoteles
/************************************************************************************************************************* */

function constructDispoBlockHoteles($disponibilidadesXhebergement, $temporada,  $codeCRM, $host, $lang, $menu, $gamma, $metros, $alias)
{

    $tabLocationTitle = '';
    $tabLocationContent = '';
    $html = '';

    //echo 'entro a construir el bloque de dispo';

    //Si hay disponibilidades busco todos los tipos de alojamientos y obtengo precios...
    if (count($disponibilidadesXhebergement) > 0) {

        $tabLocationTitle .= '<ul class="nav nav-tabs">';
        $tabLocationContent .= '<script>datalayerpushEtab("' . $codeCRM . '"); </script>';
        $tabLocationContent .= '<div class="tab-content">';

        $arrTitlesLocation = [];
        $navActive = '';
        $activeTabContent = 'active';
        $navActiveCount = 0;

        $tabTitle = '';
        $contTabHeberg = 0;
        $activeAccordion = false;
        $tabpanel = false;

        $countRandom = 0;

        //ordenar disponibilidades de menora mayor precio
        //uasort($disponibilidadesXhebergement, 'sort_by_orden');

        //Cantidad de alojamientos que se mostraran en el acordeon
        $countHeberg = count($disponibilidadesXhebergement) - 2;

        usort($disponibilidadesXhebergement, "order_by_score_endgame");
        $arrCountDispoByFormule = [];

        foreach ($disponibilidadesXhebergement as $disponibilidades) {
            array_push($arrCountDispoByFormule, $disponibilidades['base_product_code']);
        }
        $countDispoByFormule = array_count_values($arrCountDispoByFormule);


        foreach ($disponibilidadesXhebergement as $disponibilidades) {



            if (!in_array($disponibilidades['base_product_code'], $arrTitlesLocation)) {

                if ($navActiveCount == 0) {
                    $navActive = 'active';
                    $navActiveCount++;
                } else {
                    $navActive = '';

                    //Cierro el acordion
                    if (($tabpanel == false) &&  ($activeAccordion == true)) {

                        $tabLocationContent .= '</div>';
                        $tabLocationContent .= '</div>';

                        $activeAccordion = false;
                        $tabpanel = true;
                        $contTabHeberg = 0;
                    }

                    //cierro el tabpanel
                    $tabLocationContent .= '</div>';

                    $tabpanel = false;
                }

                array_push($arrTitlesLocation, $disponibilidades['base_product_code']);

                /*$tabTitle = getNamebyCategcode($disponibilidades['base_product_code'], $disponibilidades['etab_id'], $temporada);

                $generateRandom = rand(1, 1000000);

                $tabLocationTitle .= '<li class="nav-item">';
                    $tabLocationTitle .= '<a class="nav-link '.$navActive.'" href="#'.$disponibilidades['base_product_code'].'_'.$generateRandom.'" role="tab" data-toggle="tab">'.strtoupper($tabTitle).'</a>';
                $tabLocationTitle .= '</li>';*/

                //$tabTitle = getNamebyCategcode($disponibilidades['base_product_code'], $disponibilidades['etab_id'], $temporada);
                $tabTitle = ModCampingsHelper::getNomFormule($disponibilidades['base_product_code'],  $disponibilidades['etab_id'], $temporada);
                $tabIcon = ModCampingsHelper::getIconFormule($disponibilidades['base_product_code'],  $disponibilidades['etab_id'], $temporada);

                $generateRandom = rand(1, 1000000);

                $tabLocationTitle .= '<li class="nav-item">';
                $tabLocationTitle .= '<a class="nav-link ' . $navActive . ' text-uppercase" href="#' . $disponibilidades['base_product_code'] . '_' . $generateRandom . '" role="tab" data-toggle="tab">';
                if ($tabIcon != '') :
                    $tabLocationTitle .= '<div class="text-center d-block iconeformule"><img src="' . $host . $tabIcon . '" alt="' . $tabTitle . '" title="' . $tabTitle . '" /></div>';
                endif;
                $tabLocationTitle .= strtoupper($tabTitle) . '</a>';
                $tabLocationTitle .= '</li>';

                $tabLocationContent .= '<div role="tabpanel" class="tab-pane in ' . $activeTabContent . '" id="' . $disponibilidades['base_product_code'] . '_' . $generateRandom . '">';
                $tabpanel = true;
            }

            $pers = $disponibilidades['nb_totalPers'];




            $tabLocationContent .= '<div class="container  ' . $disponibilidades['base_product_code'] . '">';
            $tabLocationContent .= '<div class="row">';

            $tabLocationContent .= '<div class="col-12 col-lg-5 columns-roomtype">';

            $countMoreComb = 0;
            //Por cada room type
            foreach ($disponibilidades['room_types'] as $roomtype) {

                $appartements = ModCampingsHelper::getInformationAlojamientoProposals($codeCRM, $roomtype['room_type_code'], 'Typo', $temporada);
                $capacite = str_replace("personnes", "", $appartements->OFfExtCapacite);
                // CREAMOS COLOR PARA LA GAMMA
                $backcolor = '';
                $textcolor = '';
                if ($appartements->OFfExtGamme != '') :
                    switch ($appartements->OFfExtGamme) {
                        case 'Premium':
                            $backcolor = 'yellowbackground';
                            $textcolor = 'blueexdtext';
                            break;
                        case 'Famille':
                            $backcolor = 'bluebackground';
                            $textcolor = 'blanctext';
                            break;
                        case 'Famille':
                            $backcolor = 'bluebackground';
                            $textcolor = 'blanctext';
                            break;
                        case 'Famille +':
                            $backcolor = 'bluebackground';
                            $textcolor = 'blanctext';
                            break;
                        case 'Standard':
                            $backcolor = 'greybackground';
                            $textcolor = 'blanctext';
                            break;

                        default:
                            $backcolor = 'blanctext';
                            $textcolor = 'bluedbackground';
                            break;
                    }

                endif;
                // FIN CREAMOS COLOR PARA LA OFfExtGamme

                $classSymbolPlus = '';

                if ($countMoreComb > 0) {
                    $classSymbolPlus = '<span class="plus-comb">+</span>';
                }


                $tabLocationContent .= '<span class="tipoappartement ' . $backcolor . ' ' . $textcolor . '">' . $appartements->OFfExtGamme . '</span>';


                $tabLocationContent .= '<div class="row">';
                $tabLocationContent .= '<div class="col-3">';
                $tabLocationContent .= '<div class="row">';
                $tabLocationContent .= '<div class="col-3">';

                $tabLocationContent .= $classSymbolPlus . '<img title="mmv" src="' . $host . '/images/icons/picto-personnes-2.png"></div>';
                $tabLocationContent .= '<div class="col-7">';
                $tabLocationContent .= '<p class="tipo-num tipo-personas ' . $roomtype['room_type_code'] . '">' . $capacite . '</p><p class="tipo-text">' . JText::_(MOD_CAMPINGS_RES_BUSQ_PERS) . ' </p></div>';
                $tabLocationContent .= '</div></div>';
                $tabLocationContent .= '<div class="col-4">';
                $tabLocationContent .= '<div class="row">';
                $tabLocationContent .= '<div class="col-3">';

                $tabLocationContent .= '<img title="mmv" src="' . $host . '/images/icons/picto-surface-2.png"></div>';
                $tabLocationContent .= '<div class="col-7">';
                $tabLocationContent .= '<p class="tipo-num tipo-metros">' . $appartements->ExtSuperficie . '</p><p class="tipo-text">m²</p></div></div>';

                $tabLocationContent .= '</div>';
                $tabLocationContent .= '<div class="col-1 pl-1">';
                $tabLocationContent .= '<button type="button" class="button-appartement btn" data-toggle="modal" data-target="#' . $codeCRM . '_' . $countRandom . '"><img title="mmv" src="' . $host . '/images/icons/info.png"></button>';
                $tabLocationContent .= '</div>';
                $tabLocationContent .= '<div class="col-3 resultchambres">';
                if ($roomtype['room_type_count'] == 1) :
                    $tabLocationContent .= '<p class="tipo-num tipo-habitaciones"> X ' . $roomtype['room_type_count'] . '</p><p class="tipo-text">' . JText::_(MOD_CAMPINGS_RES_BUSQ_CHAMBRE) . '</p>';
                else :
                    $tabLocationContent .= '<p class="tipo-num tipo-habitaciones"> X ' . $roomtype['room_type_count'] . '</p><p class="tipo-text">' . JText::_(MOD_CAMPINGS_RES_BUSQ_CHAMBRES) . '</p>';
                endif;
                $tabLocationContent .= '</div>';
                $tabLocationContent .= '</div>';

                // MODAL
                $tabLocationContent .=  '<div id="' . $codeCRM . '_' . $countRandom . '" class="modal fade" role="dialog">';
                $tabLocationContent .=  '<div class="modal-dialog">';

                $tabLocationContent .=  '<div class="modal-content">';
                $tabLocationContent .=  '<div class="modal-body">';
                $tabLocationContent .=  '<h3 class="greentext">' . $roomtype['room_type_count'] . ' ' . $roomtype['room_type_label'] . '</H3>';
                //$tabLocationContent .=  '<p >'.$disponibilidades['descrip'].'</p>';
                $tabLocationContent .=  '<p >' . $appartements->ExtDescriptifCourt . '</p>';
                $tabLocationContent .=  '<p >' . $appartements->ExtDescriptifLong . '</p>';
                $tabLocationContent .= '<p>';

                $adults = ($disponibilidades['nb_adults'] == 1) ? 'Adulte' : 'Adultes';
                $adults2 = ($disponibilidades['nb_first_room_pax_max'] == 1) ? 'Adulte' : 'Adultes';
                $enfant1 = ($disponibilidades['nb_children'] == 1) ? 'enfant' : 'enfants';
                $enfant2 = ($disponibilidades['nb_children2'] == 1) ? 'enfant' : 'enfants';


                if ($disponibilidades['nb_adults'] != 0) :
                    $tabLocationContent .=  'Pour ' . $disponibilidades['nb_adults'] . ' ' . $adults;
                endif;
                if ($disponibilidades['nb_children'] != 0) :
                    $tabLocationContent .=  ', ' . $disponibilidades['nb_children'] . ' ' . $enfant1 . ' de 6-12 ans';
                endif;
                if ($disponibilidades['nb_children2'] != 0) :
                    $tabLocationContent .=  ', ' . $disponibilidades['nb_children2'] . ' ' . $enfant2 . ' de 2-6 ans';
                endif;
                $tabLocationContent .= '</p>';
                $tabLocationContent .=  '<p ></p>';

                $tabLocationContent .=  '</div>';
                $tabLocationContent .=  '<div class="modal-footer">';
                $tabLocationContent .=  ' <button type="button" class="btn btn-default" data-dismiss="modal">' . JText::_('MOD_CAMPINGS_FERMER') . '</button>';
                $tabLocationContent .=  '</div>';
                $tabLocationContent .=  '</div>';
                $tabLocationContent .=  '</div>';
                $tabLocationContent .=  '</div>';
                // MODAL
                $countMoreComb++;
            }


            $tabLocationContent .= '</div>';



            $tabLocationContent .= '<div class="col-3 col-lg-2 public-price pr-2 pl-2"><div>';
            if ($disponibilidades['public_price'] > $disponibilidades['price']) :
                $porcentaje = ($disponibilidades['price'] / $disponibilidades['public_price']) * 100;
                $porcentaje = 100 - $porcentaje;
                $porcentaje = round($porcentaje, 0);

                $tabLocationContent .= '<span class="descountTarifs">- ' . $porcentaje . ' % </span>';
                $tabLocationContent .=  '<span class="publicpricesansdesc">' . $disponibilidades['public_price'] . ' €  </span>';
            endif;
            // info taxes
            $tabLocationContent .= '<div class="continfotaxes">';
            $tabLocationContent .= '<img src="https://www.mmv.fr/images/icons/info.png" class="bulletinfotaxes">';
            $tabLocationContent .= '<div class="divinfotaxes">' . JText::_('MOD_CAMPINGS_TAXES_INFO') . '</div>';
            $tabLocationContent .= '</div>';

            $tabLocationContent .=  '<span>' . $disponibilidades['price'] . ' €  </span>';
            //$prixperperson = ($disponibilidades['price'] / $capacite)/$roomtype['room_type_count'];
            $prixperperson = $disponibilidades['price'] / $disponibilidades['nb_totalPers'];
            $tabLocationContent .=  '<span class="prixper">(' . round($prixperperson, 2) . ' € / ' . JText::_(MOD_CAMPINGS_RES_BUSQ_PERS) . ')</span>';
            $tabLocationContent .= '</div></div>';
            $tabLocationContent .= '<div class="col-9 col-lg-5 reserveztext">';

            if (isset($_POST['numberB']) and $_POST['numberB'] != 0) :  //CALCULAREM LA DATA D'UN BEBE PARA AÑADIRLA A LA URL


                //DATA D'UN BEBE
                $tempStartDatelienBebe = convertMongoTimestampTodatePhp($disponibilidades['start_date']);
                $tempStartDatelienBebe =  date("d-m-Y", strtotime($tempStartDatelienBebe . "- 1 year"));

                $tempStartDatelienBebe = explode('-', $tempStartDatelienBebe);
                $tempStartDatelienBebe = $tempStartDatelienBebe[2] . '-' . $tempStartDatelienBebe[1] . '-' . $tempStartDatelienBebe[0];

                //STRING DATA BEBE URL
                $bebeProposalKey = $_POST['numberB'];
                $proposalBebe = '!' . $bebeProposalKey . '(' . $tempStartDatelienBebe . ')';

                //POSITION PROPOSALKEY DONDE AÑADIR STRRING BEBE. bUSCAREMOS POSITION DONDE SE CARGA LA ULTIMA FECHA  
                $pos = strripos($disponibilidades['proposal_key'], ')');  //(EJ:40 ES POSITION)
                //$tabLocationContent .=$pos;

                //MODIFICAMOS LA PROPOSALKEYACTUAL
                $disponibilidades['proposal_key'] = substr_replace($disponibilidades['proposal_key'],  $proposalBebe, $pos + 1, 0);

            endif;

            $webuser = ModCampingsHelper::getWebuser($lang);
            $linkReserv = 'https://sitenew.preprod.mmv.resalys.com/rsl/clickbooking?webuser=' . $webuser . '&tokens=ignore_token&display=reservation_content&reservation_content_sub_page=reservation_occupants&actions=cancelReservation%3BchooseProposalFromKey&proposal_key=' . $disponibilidades['proposal_key'] . '&backurl=' . $host . $alias;

            $tabLocationContent .= '<a href="' . $linkReserv . '" target="_blank">';
            $tabLocationContent .= '<div class="float-left pr-2"><img src="../images/icons/cart.png" alt="' . JText::_(MOD_CAMPINGS_RES_BUSQ_RESERVEZ_MIN) . '" title="' . JText::_(MOD_CAMPINGS_RES_BUSQ_RESERVEZ_MIN) . '" /></div>';
            $tabLocationContent .= '<div class="float-left"><p class="reserveztext">' . JText::_(MOD_CAMPINGS_RES_BUSQ_RESERVEZ_MIN) . '</p>';


            $tempStartDate = convertMongoTimestampTodatePhp($disponibilidades['start_date']);
            $tempEndDate = convertMongoTimestampTodatePhp($disponibilidades['end_date']);

            //modificamos nom date
            $dayNameBegin = dayNameByDate($tempStartDate);
            $dayNameEnd   = dayNameByDate($tempEndDate);

            //Convierto el separador de la fecha de inicio - por un punto 
            $tempStartDate = explode('-', $tempStartDate);
            $tempStartDate = $tempStartDate[2] . '.' . $tempStartDate[1] . '.' . $tempStartDate[0];

            //Convierto el separador de la fecha de fin - por un punto 
            $tempEndDate = explode('-', $tempEndDate);
            $tempEndDate = $tempEndDate[2] . '.' . $tempEndDate[1] . '.' . $tempEndDate[0];



            $tabLocationContent .= '<p class="prixdisc">' . JText::_(MOD_CAMPINGS_RES_BUSQ_DU) . ' ' . $dayNameBegin . '<strong> ' . $tempStartDate . '</strong> ' . JText::_(MOD_CAMPINGS_RES_BUSQ_AU) . ' ' . $dayNameEnd . '<strong> ' . $tempEndDate . '</strong></p></div>';
            $tabLocationContent .= '</a>';


            $tabLocationContent .= '</div>';
            $tabLocationContent .= '</div>';
            $tabLocationContent .= '</div>';


            $countRandom++;

            $activeTabContent = '';

            $counter = $countDispoByFormule[$disponibilidades['base_product_code']] - 1;


            //Si el acordeon no ha sido creado y ya se ha pintado un elemento, lo creo.
            if (($activeAccordion == false) && ($tabpanel == true) && ($counter > 0)) {

                if ($gamma == 'hotel') {
                    $titleAcc = JText::_(MOD_CAMPINGS_RESULTS_PLUS_CHAM);
                } else {
                    $titleAcc = JText::_(MOD_CAMPINGS_RESULTS_PLUS_APP);
                }



                $tabLocationContent .= '<div class="accordion_container">';
                //$tabLocationContent .= '<div class="accordion_head"><i class="fa fa-angle-down caretDown"></i>'.$titleAcc.''.'<span class="countElements"></span>'.'</div>';
                $tabLocationContent .= '<div class="accordion_head ' . $countDispoByFormule[$disponibilidades['base_product_code']] . '"><i class="fa fa-angle-down caretDown"></i>' . $titleAcc . '' . '<span class="countElements">(' . $counter . ')</span>' . '</div>';
                $tabLocationContent .= '<div class="accordion_body" style="display: none;">';
                $activeAccordion = true;
                $tabpanel = false;
            }
            $tabLocationContent .= '<script>datalayerpush("' . $codeCRM . '" ,"' . $roomtype['room_type_code'] . '" , "' . $roomtype['room_type_label'] . '", "' . $appartements->OFfExtGamme . '","' . $disponibilidades['public_price'] . '","' . $tempStartDate . '", "' . $tempEndDate . '"); </script>';
            $contTabHeberg++;
        }



        $tabLocationTitle .= '</ul>';
        $tabLocationContent .= '</div>';
    } else {
        $prixhva = ModCampingsHelper::getInformationtablessiment($temporada, $codeCRM, 'C005', 'Prix');
        $tabLocationContent .= '<div class="prixminsansdispo">';

        $apporperson = ($gamma == 'hotel') ? 'MOD_CAMPINGS_PER_PERS' : 'MOD_CAMPINGS_PER_APP';

        if (!empty($prixhva)) :
            $tabLocationContent .= '<span>' . JText::_('COM_CAMPINGS_SEMANIER_A_PARTIR') . ' </span>';

            foreach ($prixhva as $scolaires) :
                if ($scolaires->sousCatCode == 'PrixHVA' and $temporada == 1) :
                    $tabLocationContent .= '<span class="public-price"><span>' . $scolaires->ExtTarif . ' €</span>  ' . JText::_($apporperson) . '</span>';
                endif;
                if ($scolaires->sousCatCode == 'Prix VAH' and $temporada == 2) :
                    $tabLocationContent .= '<span class="public-price"><span>' . $scolaires->ExtTarif . ' €</span>  ' . JText::_($apporperson) . '</span>';
                endif;
            endforeach;

            // info taxes
            $tabLocationContent .= '<div class="continfotaxes">';
            $tabLocationContent .= '<img src="https://www.mmv.fr/images/icons/info.png" class="bulletinfotaxes">';
            $tabLocationContent .= '<div class="divinfotaxes">' . JText::_('MOD_CAMPINGS_TAXES_INFO') . '</div>';
            $tabLocationContent .= '</div>';


            $tabLocationContent .= '<a class="bluetext dispos ml-3" href="' . $host . $menu . '"> <img src="images/icons/lupa.png" alt="" title="" class=""> ' . JText::_('MOD_FICHA_DISPOS_TARIFS_MIN') . '</a>';
        endif;


        $tabLocationContent .= '</div>';
    }

    $html .= $tabLocationTitle;
    $html .= $tabLocationContent;


    return $html;
}

function dayNameByDate($stringDate)
{

    $dayNames = array('Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.');
    $fechats = strtotime($stringDate); //fecha en yyyy-mm-dd
    return $dayNames[date('w', $fechats)];
}
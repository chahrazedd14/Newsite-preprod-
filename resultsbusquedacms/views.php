<?php

/**
 * @version     CVS: 1.0.0
 * @package     com_campings
 * @subpackage  mod_campings
 * @author      unixdata <web@thelis.es>
 * @copyright   unixdata
 * @license     Licencia Pública General GNU versión 2 o posterior. Consulte LICENSE.txt

 *
 */

//require_once  'function.php';
//require_once  '../includes.php';

function sort_by_orden($a, $b)
{
    return $a['adult_price'] - $b['adult_price'];
}
function sort_by_orden_dispos($a, $b)
{
    return $a['order'] - $b['order'];
}

function constructHtmlDispos($etab_id, $dispos, $host, $saison, $lang, $alias)
{

    //  echo '<pre>';
    //      print_r($dispos);
    //  echo '</pre>';
    $lang  = (isset($_POST['mmvLang']) && !empty($_POST['mmvLang'])) ? $_POST['mmvLang'] : $lang;
    usort($dispos, 'sort_by_orden_dispos');

    $host = ModCampingsHelper::getHost();
    $hostimage = ModCampingsHelper::getHostImage();

    $html = '';
    $nomtab = '';
    $active = 'active';
    $tabanterior = 0;

    $html .= '<div  class="navformulecont" class="">';
    $html .= '<ul  class="nav nav-pills navformule">';


    foreach ($dispos as $key => $value) :

        $disposetab = $etab_id;
        $html .= '<li class=" p-0"><a class="p-3 ' . $active . '" href="#' . $value['etab'] . $key . $saison . '" data-toggle="tab">';
        if ($value['nom'] != '') :
            if ($value['iconformule'] != '') :
                $html .= '<div class="text-center d-block iconeformule"><img src="' . $hostimage . $value['iconformule'] . '" alt="' . $value['nom'] . '" title="' . $value['nom'] . '" /></div>';
            endif;
            $html .= $value['nom'];
        else :
            $html .= $value['id'];
        endif;
        $html .= '</a></li>';
        $active = '';
    endforeach;

    $html .= '</ul>';

    $html .= '<script>datalayerpushEtab("' . $disposetab . '"); </script>';


    $active = 'active show';
    $html .= '<div class="tab-content tab-content-formule clearfix blancbackground  ">';
    $i = 0;
    $iroom = 0;
    $tabanterior = '';

    /*echo '<pre>';
        print_r($dispos);
    echo '</pre>';*/
    foreach ($dispos as $key => $value) :


        if ($value['etab'] . $key . $saison != $tabanterior) :

            $iroom = 0;
        endif;
        $html .= '<div class="tab-pane  blancbackground ' . $active . ' " id="' . $value['etab'] . $key . $saison . '">';
        $idtab = $value['etab'] . $key . $saison;

        usort($value['rooms'], 'sort_by_orden');
        foreach ($value['rooms'] as $room) :

            if ($iroom == 1) :
            //QUITAMOS ACORDEON
            /*$html .= '<div class="accordion_container_cms"><div class="accordion_head"><i class="fa fa-angle-down caretDown"></i> '.JText::_('MOD_CAMPINGS_RES_BUSQ_CHAMBRES').' ('.(count($value['rooms'])-1).')<span class="countElements"></span></div><div class="accordion_body" style="display: none;"></div></div>';*/
            endif;

            if ($value['categoryetab'] == 2) : $resihot = htmlapartemanetshotel($room, $lang, $disposetab, $iroom, $alias);
            else : $resihot = htmlapartemanetsresidence($room, $lang,   $disposetab, $iroom, $alias);
            endif;
            //if($value['categoryetab'] != 2): htmlapartemanetshotel($room) ; endif;
            $html .= $resihot;
            $iroom++;


        endforeach;
        $html .= '</div>';
        $tabanterior = $value['etab'] . $key . $saison;
        $i++;
        $active = '';
    endforeach;
    $html .= '</div>';

    $html .= '</div>';


    echo $html;
}


function htmlapartemanetshotel($room, $lang, $disposetab, $iroom, $alias)
{
    //   echo '<pre>';
    //       print_r($room);
    //   echo '</pre>';



    $lang  = (isset($_POST['mmvLang']) && !empty($_POST['mmvLang'])) ? $_POST['mmvLang'] : $lang;
    $host = ModCampingsHelper::getHost();
    $hostimage = ModCampingsHelper::getHostImage();


    $htmlroom = '';




    $htmlroom .= '<div class="row appartement-caract';

    if ($iroom >= 1) :
    // COMO QUITAMOS ACORDEON DE + CHAMBRES QUITAMOS LA CLASE DE LAS DISPOS QUE HACIA TOOGLE CALSS OPEN
    //$htmlroom .= ' dispotogvisb ';
    endif;

    $htmlroom .= '">';



    $numrooms = count($room['room']);
    $combinada = ($numrooms == 1) ? 'individual' : 'combinada';

    $htmlroom .= '<div class="col-md-12 col-lg-5 ' . $combinada . '">';

    foreach ($room['room'] as $rommx) :


        $capacite = str_replace("personnes", "", $rommx['OFfExtCapacite']);
        $backcolor = '';
        $textcolor = '';

        if ($rommx['OFfExtGamme'] != '') :
            switch ($rommx['OFfExtGamme']) {
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

        //     $htmlroom .='1';
        if ($rommx['OFfExtGamme'] != '') :
            $htmlroom .= '<div class="appartement-type row"><span class="tipoappartement ' . $backcolor . ' ' . $textcolor . ' ">' . $rommx['OFfExtGamme'] . '</span></div>';
        endif;
        $htmlroom .= '<div class="row">';

        $htmlroom .= '<div class="col-4">';
        $htmlroom .= '<div class="row">';
        $htmlroom .= '<div class="col-4 pr-2"><img src="' . $hostimage . 'images/icons/picto-personnes-2.png" alt="' . $capacite . '" title="' . $capacite . '" /></div>';
        $htmlroom .= '<div class="col-8"><p class="tipo-num">' . $capacite . '</p><p class="tipo-text">pers.</p></div>';
        $htmlroom .= '</div>';
        $htmlroom .= '</div>';
        $htmlroom .= '<div class="col-3 pl-0 pr-0 lespace">';
        $htmlroom .= '<div class="row">';
        $htmlroom .= '<div class="col-4 pr-2 pl-3"><img src="' . $hostimage . 'images/icons/picto-surface-2.png" alt="' . $rommx['ExtSuperficie'] . '" title="' . $rommx['ExtSuperficie'] . '" /></div>';
        $htmlroom .= '<div class="col-8"><p class="tipo-num">' . $rommx['ExtSuperficie'] . '</p><p class="tipo-text">m²</p></div>';
        $htmlroom .= '</div>';
        $htmlroom .= '</div>';
        $htmlroom .= '<div class="col-2 pl-2 lebutton">';
        $htmlroom .= '<div>';
        if ($rommx['ExtDescriptifCourt'] != '' or $rommx['ExtDescriptifLong'] != '') :
            $htmlroom .= '<button type="button" class="btn  " data-toggle="modal" data-target="#' . $rommx['Reference'] . '"><img src="' . $hostimage . 'images/icons/info.png" alt="" title="" class="" /></button>';
        endif;
        $htmlroom .= '</div>';
        $htmlroom .= '</div>';
        $htmlroom .= '<div class="col-2 pr-0 pl-2">';
        $htmlroom .= '<div class="row">';
        $htmlroom .= '<div class="col-4"></div>';
        $htmlroom .= '<div class="col-8 pl-0"><p class="tipo-num">X ';
        if ($rommx['chambre'] == '' or $rommx['chambre'] == 1) :
            $htmlroom .= 1;
        else :
            $htmlroom .= $rommx['chambre'];
        endif;

        $htmlroom .= '</p><p class="tipo-text">';

        if ($rommx['chambre'] == '' or $rommx['chambre'] == 1) :
            $htmlroom .= JText::_('MOD_CAMPINGS_RES_BUSQ_CHAMBRE');
            $numchambre = 1;
        else :
            $htmlroom .= JText::_('MOD_CAMPINGS_RES_BUSQ_CHAMBRES');
            $numchambre = $rommx['chambre'];
        endif;

        //$htmlroom .= $room['OFfExtNbPiece'];

        $htmlroom .= '</p></div>';
        $htmlroom .= '</div>';
        $htmlroom .= '</div>';


        $htmlroom .= '</div>';

        // Modal alojamiento

        if ($rommx['ExtDescriptifCourt'] != '' or $rommx['ExtDescriptifLong'] != '') :

            $htmlroom .= '<div id="' . $rommx['Reference'] . '" class="modal fade" role="dialog">';
            $htmlroom .= '<div class="modal-dialog">';

            $htmlroom .= '<div class="modal-content">';
            $htmlroom .= '<div class="modal-body">';
            $htmlroom .= '<h3 class="greentext">' . $rommx['label'] . '</H3>';
            $htmlroom .= '<p >' . $rommx['ExtDescriptifCourt'] . '</p>';
            $htmlroom .= '<p >' . $rommx['ExtDescriptifLong'] . '</p>';

            $htmlroom .= '</div>';
            $htmlroom .= '<div class="modal-footer">';
            $htmlroom .= ' <button type="button" class="btn btn-default" data-dismiss="modal">' . JText::_('MOD_CAMPINGS_FERMER') . '</button>';
            $htmlroom .= '</div>';
            $htmlroom .= '</div>';
            $htmlroom .= '</div>';
            $htmlroom .= '</div>';

        endif;

    // Fin Modal alojamiento




    endforeach;


    $htmlroom .= '</div>';

    $htmlroom .= '<div class="col-md-12 col-lg-7 pr-0 reservezarea">';
    $htmlroom .= '<div class="row reserveztext">';
    $htmlroom .= '<div class="col-4 col-sm-5 reservezprix">';
    $htmlroom .= '<div>';
    if ($room['discont'] != '' and $room['discont'] > $room['adult_price']) :
        $htmlroom .= '<p class="prix">' . $room['discont'] . ' € </p>';
    endif;
    $htmlroom .= '<p class="prixdisc"><span>' . $room['adult_price'] . ' € </span>';
    $prixperperson = $room['adult_price'] / ($capacite * $numchambre);

    $htmlroom .= '<br/><span class="prixper">' . round($prixperperson, 2) . ' €' . JText::_('MOD_CAMPINGS_PER_PERS') . '</span>';

    $htmlroom .= '</p>';
    // info taxes
    $htmlroom .= '<div class="continfotaxes">';
    $htmlroom .= '<img src="' . $hostimage . 'images/icons/info.png" class="bulletinfotaxes">';
    $htmlroom .= '<div class="divinfotaxes">' . JText::_('MOD_CAMPINGS_TAXES_INFO') . '</div>';
    $htmlroom .= '</div>';
    $htmlroom .= '</div>';
    $htmlroom .= '</div>';

    $htmlroom .= '<div class="col-8 col-sm-7 pr-0 reservezbutton">';

    $htmlroom .= '<a href="https://www.mmv.fr' . $room['url'] . '&backurl=' . $host . $alias . '" target="_blank" rel="noopener nofollow ">';
    $htmlroom .= '<div class="float-left pr-1"><img src="' . $hostimage . 'images/icons/cart.png" alt="' . $room['label'] . '" title="' . $room['label'] . '" /></div>';
    $htmlroom .= '<div class="float-left"><p class="reserveztext">' . JText::_('MOD_CAMPINGS_RESERVEZ_MIN') . '</p>';

    //modificamos nom date
    $room['type_label'] = str_replace(' ', '', $room['type_label']);
    $daysnames = explode("/", $room['type_label']);
    $daysnames = explode("/", $room['type_label']);
    $dayNameBegin = $daysnames[0];
    $dayNameEnd   = $daysnames[1];
    //$dayNameBegin = dayNameByDate($room['begin']);
    //$dayNameEnd   = dayNameByDate($room['end']);

    //Convierto el separador - por un punto 
    $tempStartDate = explode('/', $room['begin']);
    $tempStartDate = $tempStartDate[0] . '.' . $tempStartDate[1] . '.' . $tempStartDate[2];

    $tempEndDate = explode('/', $room['end']);
    $tempEndDate = $tempEndDate[0] . '.' . $tempEndDate[1] . '.' . $tempEndDate[2];


    $htmlroom .= '<p class="reservezdates">' . JText::_('MOD_CAMPINGS_RES_BUSQ_DU') . ' ' . substr($dayNameBegin, 0, 3) . '<strong> ' . $tempStartDate . '</strong> ' . JText::_('MOD_CAMPINGS_RES_BUSQ_AU') . ' ' . substr($dayNameEnd, 0, 3) . '<strong> ' . $tempEndDate . '</strong></p></div>';
    $htmlroom .= '</a>';
    $htmlroom .= '</div>';
    $htmlroom .= '</div>';
    $htmlroom .= '</div>';



    $htmlroom .= '</div>';




    $htmlroom .= '<script>datalayerpush("' . $disposetab . '" ,"' . $room['code'] . '" , "' . $room['label'] . '", "' . $room['OFfExtGamme'] . '","' . $room['adult_price'] . '","' . $room['begin'] . '", "' . $room['end'] . '"); </script>';

    return $htmlroom;
}
function htmlapartemanetsresidence($room, $lang, $disposetab, $iroom, $alias)
{

    //    echo '<pre>';
    //       print_r($room);
    //   echo '</pre>';
    //   echo $alias.'<br/>';
    //   echo '<pre>';
    //   print_r($iroom);
    //     echo '</pre>';
    $lang  = (isset($_POST['mmvLang']) && !empty($_POST['mmvLang'])) ? $_POST['mmvLang'] : $lang;
    $host = ModCampingsHelper::getHost();
    $hostimage = ModCampingsHelper::getHostImage();

    $htmlroom = '';



    $htmlroom .= '<div class="row appartement-caract ';
    if ($iroom >= 1) :
    // COMO QUITAMOS ACORDEON DE + CHAMBRES QUITAMOS LA CLASE DE LAS DISPOS QUE HACIA TOOGLE CALSS OPEN
    //$htmlroom .= ' dispotogvisb ';
    endif;
    $htmlroom .= '">';
    //$htmlroom .= $html .= $iroom.'<br/>';;

    $numrooms = count($room['room']);
    $combinada = ($numrooms == 1) ? 'individual' : 'combinada';

    $htmlroom .= '<div class="col-md-12 col-lg-5 ' . $combinada . '">';
    foreach ($room['room'] as $rommx) :

        $capacite = str_replace("personnes", "", $rommx['OFfExtCapacite']);

        $backcolor = '';
        $textcolor = '';
        if ($rommx['OFfExtGamme'] != '') :
            switch ($rommx['OFfExtGamme']) {
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

        if ($rommx['OFfExtGamme'] != '') :
            $htmlroom .= '<div class="appartement-type row"><span class="tipoappartement ' . $backcolor . ' ' . $textcolor . ' ">' . $rommx['OFfExtGamme'] . '</span></div>';
        endif;


        $htmlroom .= '<div class="row">';
        $htmlroom .= '<div class="col-3">';
        $htmlroom .= '<div class="row">';
        $htmlroom .= '<div class="col-4 pr-0"><img src="' . $hostimage . 'images/icons/picto-pi-ces.png" alt="' . $rommx['OFfExtNbPiece'] . '" title="' . $rommx['OFfExtNbPiece'] . '" /></div>';
        $htmlroom .= '<div class="col-8"><p class="tipo-num">' . $rommx['OFfExtNbPiece'] . '</p><p class="tipo-text">

                                    ' . JText::_('MOD_CAMPINGS_RES_BUSQ_PIECES') . '

                                    </p></div>';

        $htmlroom .= '</div>';
        $htmlroom .= '</div>';
        $htmlroom .= '<div class="col-4">';
        $htmlroom .= '<div class="row">';
        $htmlroom .= '<div class="col-4 pr-2 pl-3"><img src="' . $hostimage . 'images/icons/picto-personnes-2.png" alt="' . $capacite . '" title="' . $capacite . '" /></div>';
        $htmlroom .= '<div class="col-8"><p class="tipo-num">' . $capacite . '</p><p class="tipo-text">

                                    ' . JText::_('MOD_CAMPINGS_RES_BUSQ_PERS') . '

                                    </p></div>';
        $htmlroom .= '</div>';
        $htmlroom .= '</div>';
        $htmlroom .= '<div class="col-4 pr-3 pl-0">';
        $htmlroom .= '<div class="row">';
        $htmlroom .= '<div class="col-4"><img src="' . $hostimage . 'images/icons/picto-surface-2.png" alt="' . $rommx['ExtSuperficie'] . '" title="' . $rommx['ExtSuperficie'] . '" /></div>';
        $htmlroom .= '<div class="col-8 pl-1"><p class="tipo-num">' . $rommx['ExtSuperficie'] . '</p><p class="tipo-text">m²</p></div>';
        $htmlroom .= '</div>';
        $htmlroom .= '</div>';

        $htmlroom .= '<div class="col-1 pl-0">';
        $htmlroom .= '<div>';
        if ($rommx['ExtDescriptifCourt'] != '' or $rommx['ExtDescriptifLong'] != '') :
            $htmlroom .= '<button type="button" class="btn pl-1 pr-1" data-toggle="modal" data-target="#' . $rommx['Reference'] . '"><img src="' . $hostimage . 'images/icons/info.png" alt="" title="" class="" /></button>';
        endif;
        $htmlroom .= '</div>';
        $htmlroom .= '</div>';
        $htmlroom .= '</div>';
        $htmlroom .= '</div>';


        // Modal alojamiento
        if ($rommx['ExtDescriptifCourt'] != '' or $rommx['ExtDescriptifLong'] != '') :

            $htmlroom .= '<div id="' . $rommx['Reference'] . '" class="modal fade" role="dialog">';
            $htmlroom .= '<div class="modal-dialog">';

            $htmlroom .= '<div class="modal-content">';
            $htmlroom .= '<div class="modal-body">';
            $htmlroom .= '<h3 class="greentext">' . $rommx['label'] . '</H3>';
            $htmlroom .= '<p >' . $rommx['ExtDescriptifCourt'] . '</p>';
            $htmlroom .= '<p >' . $rommx['ExtDescriptifLong'] . '</p>';

            $htmlroom .= '</div>';
            $htmlroom .= '<div class="modal-footer">';
            $htmlroom .= ' <button type="button" class="btn btn-default" data-dismiss="modal">' . JText::_('MOD_CAMPINGS_FERMER') . '</button>';
            $htmlroom .= '</div>';
            $htmlroom .= '</div>';
            $htmlroom .= '</div>';
            $htmlroom .= '</div>';
        endif;
    // Fin Modal alojamiento



    endforeach;

    $htmlroom .= '<div class="col-md-12 col-lg-7 pr-0 reservezarea">';
    $htmlroom .= '<div class="row reserveztext">';
    $htmlroom .= '<div class="col-4 col-sm-5 reservezprix">';
    $htmlroom .= '<div>';
    if ($room['discont'] != '' and $room['discont'] > $room['adult_price']) :
        $htmlroom .= '<p class="prix">' . $room['discont'] . ' € </p>';
    endif;
    $htmlroom .= '<p class="prixdisc"><span>' . $room['adult_price'] . ' € </span>' . JText::_('MOD_CAMPINGS_RES_BUSQ_APPT') . '</p>';
    // info taxes
    $htmlroom .= '<div class="continfotaxes">';
    $htmlroom .= '<img src="' . $hostimage . 'images/icons/info.png" class="bulletinfotaxes">';
    $htmlroom .= '<div class="divinfotaxes">' . JText::_('MOD_CAMPINGS_TAXES_INFO') . '</div>';
    $htmlroom .= '</div>';


    $htmlroom .= '</div>';
    $htmlroom .= '</div>';

    $htmlroom .= '<div class="col-8 col-sm-7 pr-0 reservezbutton">';
    $htmlroom .= '<a href="https://www.mmv.fr' . $room['url'] . '&backurl=' . $host . $alias . '" target="_blank" rel="noopener nofollow ">';
    $htmlroom .= '<div class="float-left pr-1"><img src="' . $hostimage . 'images/icons/cart.png" alt="' . $room['label'] . '" title="' . $room['label'] . '" /></div>';
    $htmlroom .= '<div class="float-left">';
    $htmlroom .= '<p class="reserveztext">' . JText::_('MOD_CAMPINGS_RES_BUSQ_RESERVEZ_MIN') . '</p>';

    //modificamos nom date
    $room['type_label'] = str_replace(' ', '', $room['type_label']);
    $daysnames = explode("/", $room['type_label']);
    $dayNameBegin = $daysnames[0];
    $dayNameEnd   = $daysnames[1];
    //$dayNameBegin = dayNameByDate($room['begin']);
    //$dayNameEnd   = dayNameByDate($room['end']);

    //Convierto el separador - por un punto 
    $tempStartDate = explode('/', $room['begin']);
    $tempStartDate = $tempStartDate[0] . '.' . $tempStartDate[1] . '.' . $tempStartDate[2];

    $tempEndDate = explode('/', $room['end']);
    $tempEndDate = $tempEndDate[0] . '.' . $tempEndDate[1] . '.' . $tempEndDate[2];


    $htmlroom .= '<p class="reservezdates">' . JText::_('MOD_CAMPINGS_RES_BUSQ_DU') . ' ' . substr($dayNameBegin, 0, 3) . '<strong> ' . $tempStartDate . '</strong> ' . JText::_('MOD_CAMPINGS_RES_BUSQ_AU') . ' ' . substr($dayNameEnd, 0, 3) . '<strong> ' . $tempEndDate . '</strong></p>';
    $htmlroom .= '</div>';
    $htmlroom .= '</a>';
    $htmlroom .= '</div>';
    $htmlroom .= '</div>';
    $htmlroom .= '</div>';



    $htmlroom .= '</div>';




    $htmlroom .= '<script>datalayerpush("' . $disposetab . '" ,"' . $room['code'] . '" , "' . $room['label'] . '", "' . $room['OFfExtGamme'] . '","' . $room['adult_price'] . '","' . $room['begin'] . '", "' . $room['end'] . '"); </script>';


    return $htmlroom;
}

function constructHtmlResultsBusqueda($hebergement, $host, $lang, $tags, $regiongeographique, $lienlang)
{


    //echo $hebergement->codeCRM;
    $lang  = (isset($_POST['mmvLang']) && !empty($_POST['mmvLang'])) ? $_POST['mmvLang'] : $lang;
    $datefilter  = (isset($_POST['datefilter']) && !empty($_POST['datefilter'])) ? $_POST['datefilter'] : '';
    $numberA  = (isset($_POST['numberA']) && !empty($_POST['numberA'])) ? $_POST['numberA'] : '';
    $numberE1  = (isset($_POST['numberE1']) && !empty($_POST['numberE1'])) ? $_POST['numberE1'] : '';
    $numberE2  = (isset($_POST['numberE2']) && !empty($_POST['numberE2'])) ? $_POST['numberE2'] : '';
    $numberB  = (isset($_POST['numberB']) && !empty($_POST['numberB'])) ? $_POST['numberB'] : '';

    $host = ModCampingsHelper::getHost();
    $hostimage = ModCampingsHelper::getHostImage();



    $colortext = ($hebergement->typeDestination == 2) ? 'bluetext' : 'greentext';
    $decouvrez = ($hebergement->typeDestination == 2) ? 'MOD_CAMPINGS_CEST_HOTEL' : 'MOD_CAMPINGS_CEST_RESIDENCE';

    $introtext = ($lang == 'en' and $hebergement->introtexten != '') ? $hebergement->introtexten :  $hebergement->introtext;
    $description = ($lang == 'en' and $hebergement->descriptionen != '') ? $hebergement->descriptionen :  $hebergement->description;

    $html = '';



    switch ($lang) {
        case 'partenaires':
            $lienlang = $lang . '/';
            break;
    }


    //Es la manera que tengo de diferenciar hotel de residencia
    if ($hebergement->gamma == 2) {
        $gamma = 'hotel';
    } else {
        $gamma = 'résidence';
    }

    $html .=  '<div class="containerHebergement">';
    $html .=  '<div class="row">';
    $html .=  '<div class="col-lg-7 col-12">';
    //img
    //$imgRut = '../../../images/etablessiments/vignettes/'.$hebergement->alias.'.jpg';
    //$imgPath = $host.'images/etablessiments/vignettes/'.$hebergement->alias.'.jpg';
    $imgRut = '../../../images/etablissements/' . $hebergement->alias . '/slide';
    $imgPath = $hostimage . 'images/etablissements/' . $hebergement->alias . '/slide';
    if (file_exists($imgRut)) :

        $ficheros  = scandir($imgRut);
        foreach ($ficheros as $key => $value) {
            if (preg_match("/\.(png|gif|jpe?g|bmp)/", $value, $m)) {
                $images[] = $value;
            }
        }

        $imgsansextension = substr($value, 0, -4); //lien sans extension
        $extension = substr_replace($value, '', 0, -4); //extension
        $thumb = '../images/etablissements/' . $hebergement->alias . '/thumbs/' . $imgsansextension . '_thumb_380x225' . $extension;

        if (!file_exists('../../' . $thumb)) :
            $img = $hostimage . 'images/etablissements/default.jpg';
            $thumb = $img;
        else :
            $thumb = $hostimage . 'images/etablissements/' . $hebergement->alias . '/thumbs/' . $imgsansextension . '_thumb_380x225' . $extension;
        endif;
        $img = ($value == '..') ? $hostimage . 'images/etablissements/default.jpg' : $imgPath . '/' . $value;
    //$img = $imgPath.'/'.$value;
    else :
        $img = $hostimage . 'images/etablissements/default.jpg';
        $thumb = $img;
    endif;

    $html .= '<div class="contentimg">';
    //$html .= '<h3 class="blanctext">'.$hebergement->ExtSkiDomNom.'</h3>';
    $html .= '<h3 class="blanctext">' . $hebergement->ItmExtStationCommune . '</h3>';
    $html .= '<img src="' . $thumb . '" alt="' . $hebergement->nom . '" title="' . $hebergement->nom . '" />';
    $html .= '</div>';

    // BLOQUE GAMMA
    $html .= '<div class="float-left categorieicon">';
    /*if($hebergement->typeDestination == 2):
                        $html .= '<div class=""><img class="d-block" src="'.$host.'images/icons/hotel-club.png" alt="'.$hebergement->nom. '" title="'.$hebergement->nom. '" /></div>';
                    else:
                        $html .= '<div class="rclub-logo "><img class="d-block" src="'.$host.'images/icons/residence-club.png" alt="'.$hebergement->nom. '" title="'.$hebergement->nom. '" /></div>';
                    endif;*/
    if ($hebergement->categoryeicone != '0') :
        $html .= '<div class=""><img class="d-block" src="' . $hostimage . $hebergement->categoryeicone . '" alt="' . $hebergement->nom . '" title="' . $hebergement->nom . '" /></div>';
    endif;

    /* if($hebergement->etoiles!=''):
                            $html .= '<p class="text-center">';
                            for ($etoiles = 0; $etoiles < $hebergement->etoiles; $etoiles++){$html .= '<span class="'.$colortext.'">*</span>'; }
                            $html .= '</p>';
                    endif; */
    $html .= '</div>';

    //Gallery
    $dir = "../../../images/etablissements/" . $hebergement->alias . "/galerie/";
    $ficheros1  = scandir($dir);
    $total_imagenes = count($ficheros1);

    $html .= '<div class="resultb-galleryicon">';
    $html .= '<a class="btn-gallery" data-alias="' . $hebergement->alias . '" ><img src="/images/icons/rb_more.png"><span class="blanctext">' . $total_imagenes . '</span></a>';
    $html .= '</div>';


    $html .= '<div class="resultb-mapicon">';
    $html .= '<button type="button" class="button-map-resultb btn" data-lat="' . $hebergement->gpsLat . '" data-lon="' . $hebergement->gpsLon . '" data-gamme="' . $hebergement->categoryeicone . '" data-toggle="modal" data-target="#etab_' . $hebergement->codeCRM . '">';
    $html .= '<img class="d-block" src="' . $host . 'images/icons/map.png" alt="' . $hebergement->nom . '" title="' . $hebergement->nom . '" />';
    $html .= '</button>';
    $html .= '</div>';

    $html .=  '<div id="etab_' . $hebergement->codeCRM . '" class="modal fade modalmapa-resultb" role="dialog">';
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
    //Gallery

    $html .= '</div>';
    $html .=  '<div class="col-lg-5 col-12">';
    //$html .= '<a class="" href="'.$host.$lienlang.$hebergement->menu.'" rel="noopener"><h2 class="h2Resulthebergements" target="_blank" >'.$hebergement->nom.'</h2></a>' ;

    $html .= '<form class="formByResult" action="' . $host . $lienlang . $hebergement->menu . '" method="post" target="_blank">';
    $html .= '<button class="btnTitleResultEtab"><h2 class="h2Resulthebergements" >' . $hebergement->nom . '</h2></button>';
    $html .= '<input name="datefilter" class="datefilterByResult" class="btn btn-primary" type="text" hidden value="' . $datefilter . '">';
    $html .= '<input name="numberA" class="numberPersByResult numberA" class="btn btn-primary" type="text" hidden value="' . $numberA . '">';
    $html .= '<input name="numberE1" class="numberPersByResult numberE1" class="btn btn-primary" type="text" hidden value="' . $numberE1 . '">';
    $html .= '<input name="numberE2" class="numberPersByResult numberE2" class="btn btn-primary" type="text" hidden value="' . $numberE2 . '">';
    $html .= '<input name="numberB" class="numberPersByResult numberB" class="btn btn-primary" type="text" hidden value="' . $numberB . '">';
    $html .= '</form>';



    if ($hebergement->category != '') :
        $html .= '<p class="typehebergement ';
        if ($hebergement->typeDestination == 1) :
            $html .= 'greentext';
        else :
            $html .= 'bluetext';
        endif;
        if ($hebergement->typeDestination == 3) :
            $html .= 'orangetext';
        endif;

        //$html .= '"><strong>'.$hebergement->ItmExtGamme.'</strong><br/>'.$hebergement->ExtSkiDomNom.' ( '.ucwords(strtolower($hebergement->ItmExtStationCommune)).' , '.$regiongeographique.' )</p>';
        $html .= '"><strong>' . $hebergement->ItmExtGamme . '</strong><br/>' . $hebergement->ExtSkiDomNom . ' ( ' . ucwords(strtolower($hebergement->ItmExtStationCommune)) . ' , ' . $hebergement->ItmExtDepartement . ' )</p>';
    endif;


    if ($hebergement->villeimg != '') :
        $html .= '<p><img class="img1ResultHebergements" src="' . $hostimage . $hebergement->villeimg . '" alt="' . $hebergement->nom . '" title="' . $hebergement->nom . '"></p>';
    endif;
    $logosatation = ($hebergement->logostationcomplet != '') ? $hebergement->logostationcomplet : $hebergement->icon;


    if ($logosatation != '') :
        $html .= '<p><img class="img1ResultHebergements" src="' . $hostimage . $logosatation . '" alt="' . $hebergement->nom . '" title="' . $hebergement->nom . '"></p>';
    endif;

    if ($hebergement->AnsweredSurveys != '') :
        $html .= '<p class="d-flex align-items-center"><img class="pictoravi" src="' . $hostimage . 'images/icons/picto-client-ravi.png" alt="' . $hebergement->nom . '" title="' . $hebergement->nom . '"> ';
        $html .= '<span class="bluedtext clientsravis"> ' . $hebergement->AnsweredSurveys . ' ' . JText::_('MOD_CAMPINGS_FICHA_CLIENTS_RAVIS') . '</span></p>';
    endif;
    // $html .= '<div>';
    //     $html .= '<a class="btn btnblueResults" href="'.$host.$lienlang.$hebergement->menu.'" target="_blank" rel="noopener">'.JText::_($decouvrez).'</a>';
    // $html .= '</div>';

    //Definir la url del link para cgos
    $urlMenuByLang = $host . $lienlang . $hebergement->menu;



    $html .= '<form class="formByResult" action="' . $urlMenuByLang . '" method="post" target="_blank">';
    $html .= '<button class="btn btnblueResults">' . JText::_($decouvrez) . '</button>';
    $html .= '<input name="datefilter" class="datefilterByResult" class="btn btn-primary" type="text" hidden value="' . $datefilter . '">';
    $html .= '<input name="numberA" class="numberPersByResult numberA" class="btn btn-primary" type="text" hidden value="' . $numberA . '">';
    $html .= '<input name="numberE1" class="numberPersByResult numberE1" class="btn btn-primary" type="text" hidden value="' . $numberE1 . '">';
    $html .= '<input name="numberE2" class="numberPersByResult numberE2" class="btn btn-primary" type="text" hidden value="' . $numberE2 . '">';
    $html .= '<input name="numberB" class="numberPersByResult numberB" class="btn btn-primary" type="text" hidden value="' . $numberB . '">';
    $html .= '</form>';


    $html .= '</div>';
    $html .= '</div>';
    $html .= '<div class="row mt-5">';
    if (!empty($tags)) :
        $html .= '<div class="votrecluboffretop col-12 mb-3">';
        foreach ($tags as $tag) :
            $tagnom = ($lang == 'en' and $tag->OFfExtUKLibelle != '') ? $tag->OFfExtUKLibelle : $tag->nom;
            $html .= '<span class="text-uppercase text-white bluedbackground text-center p-1 m-1">' . $tagnom . '</span>';
        endforeach;
        $html .= '</div>';
    endif;

    $html .= '<div class="col-12">';

    $description = strip_tags($introtext . '<br/>' . $description);
    //$html .= '<p>'.strip_tags ($hebergement->introtext).'...</p>';
    $html .= '<p class="descriptiontext">' . substr($description, 0, 350) . ' ...</p>';
    //$html .= '<p>'.strip_tags (substr($hebergement->description, 0, 350)).'</p>';


    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';

    echo $html;
}
function constructHtmlPrixMinium($prix, $host, $lang, $alias, $apporperson)
{

    $lang  = (isset($_POST['mmvLang']) && !empty($_POST['mmvLang'])) ? $_POST['mmvLang'] : $lang;
    $hostimage = ModCampingsHelper::getHostImage();
    $tabLocationContent .= '<div class="prixminsansdispo container pl-4">';


    $tabLocationContent .= '<span>' . JText::_('MOD_FICHA_VOTRE_SEMAINE_TOT') . ' </span>';
    /* foreach($prix as $scolaires):
                            if( $scolaires->sousCatCode == 'Prix VAH'):
                                //$tabLocationContent .= '<span class="tachado greytext">'.$scolaires->ExtTarif.' </span>';
                            endif;

                        endforeach;*/
    foreach ($prix as $scolaires) :
        //if( $scolaires->sousCatCode == 'PrixHVA'):
        $tabLocationContent .= '<span class="public-price"><span><strong>' . $scolaires->ExtTarif . ' € </strong></span><span class="prixes"> ' . JText::_($apporperson) . '</span></span>';
    //endif;
    endforeach;
    // info taxes
    $tabLocationContent .= '<div class="continfotaxes">';
    $tabLocationContent .= '<img src="' . $hostimage . 'images/icons/info.png" class="bulletinfotaxes">';
    $tabLocationContent .= '<div class="divinfotaxes">' . JText::_('MOD_CAMPINGS_TAXES_INFO') . '</div>';
    $tabLocationContent .= '</div>';
    $tabLocationContent .= '<a class="bluetext ml-3" href="' . $host . $alias . '"> <img src="' . $hostimage . 'images/icons/lupa.png" alt="" title="" class=""><span class="bluetext">' . JText::_('MOD_FICHA_DISPOS_TARIFS_MIN') . '</span> </a>';



    $tabLocationContent .= '</div>';
    echo $tabLocationContent;
}


function dayNameByDate($stringDate)
{

    $dayNames = array('Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.');
    $fechats = strtotime($stringDate); //fecha en yyyy-mm-dd
    return $dayNames[date('w', $fechats)];
}

?>

<!--  deshabilitamos porque si esta habilitado no funciona map partenaires , lo deberemos pasar a script.js-->
<!-- <script>

jQuery(document).ready( function($) {
    jQuery(".accordion_container_cms").click(function(){
        var elements = jQuery(this).siblings('.dispotogvisb');

        jQuery(elements).toggleClass("open");
    });

   
});


</script> -->
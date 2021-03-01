<?php

/**
 * @version     CVS: 1.0.0
 * @package     com_campings
 * @subpackage  mod_campings
 * @author      unixdata <web@thelis.es>
 * @copyright   unixdata
 * @license     Licencia Pública General GNU versión 2 o posterior. Consulte LICENSE.txt
 */

require_once  '../includes.php';




//Esperamos que nos envie algun filtro por POST
$filtros = (isset($_POST['arrIdFilters'])) ? $_POST['arrIdFilters'] : 0;
$tipopage = (isset($_POST['tipopage'])) ? $_POST['tipopage'] : 0;
$itemid = (isset($_POST['itemid'])) ? $_POST['itemid'] : 0;


// PARA QUE FUNCIONES LOS IDIOMAS
$langpost = (isset($_POST['customLangP'])) ? $_POST['customLangP'] : $lang;
switch ($langpost) {
    case 'fr-fr':
        $lang_code = 'fr-FR';
        break;
    case 'en':
        $lang_code = 'en-GB';
        break;
    case 'en-gb':
        $lang_code = 'en-GB';
        break;
    case 'en-GB':
        $lang_code = 'en-GB';
        break;
    case 'bs-ba':
        $lang_code = 'bs-BA';
        break;
    case 'bs-BA':
        $lang_code = 'bs-BA';
        break;
    case 'fr-ca':
        $lang_code = 'fr-CA';
        break;
    case 'fr-CA':
        $lang_code = 'fr-CA';
        break;
    case 'ca-es':
        $lang_code = 'ca-ES';
        break;
    case 'ca-ES':
        $lang_code = 'ca-ES';
        break;
    default:
        $lang_code = 'fr-FR';
        break;
}
// instantiate application.
$app = JFactory::getApplication('site');
$session = JFactory::getSession();
$lang_obj = $session->get('plg_system_languagefilter');
//$lang_code = $lang_obj->{'language'}; //and yes, here use curly brackets
//echo $lang_code; //return e.g. en-GB, de-DE, etc.

$langue = JFactory::getLanguage();
$langue->load('mod_campings', JPATH_SITE, $lang_code, true);

switch ($lang_code) {
    case 'fr-FR':
        $lang = 'fr';
        $codelang = 0;
        $lienlang = '';  // si language es != al fr en la url podremos codigo de idioma
        break;
    case 'en-GB':
        $lang = 'en';
        $codelang = 1;
        $lienlang = $lang . '/';
        break;
    case 'bs-BA':
        $lang = 'cgos';
        $codelang = 0;
        $lienlang = $lang . '/';
        break;
    case 'ca-es':
        $lang = 'partenaires';
        $codelang = 0;
        $lienlang = $lang . '/';
        break;
    case 'ca-ES':
        $lang = 'partenaires';
        $codelang = 0;
        $lienlang = $lang . '/';
        break;
    case 'fr-CA':
        $lang = 'macif';
        $codelang = 0;
        $lienlang = $lang . '/';
        break;
    default:
        $lang = 'fr';
        $codelang = 0;
        $lienlang = '';
}

$module = JModuleHelper::getModule('mod_campings');
// PARA QUE FUNCIONES LOS IDIOMAS


$element = ModCampingsHelper::getparamsPromotiosResalys($tipopage, $itemid);
$saison = $element->saison;
$arraysaisson = array();
/*echo '<pre>';
print_r($element);
echo '</pre>';*/


//VARS

$etabs1 = (!empty($element->etablessiments)) ? $element->etablessiments : '';
//echo $etabs1;

//echo implode(',' ,$codeetabs);
if ($etabs1 != '') :
    $codeetabs = ModCampingsHelper::getCodeCRMEtabs($etabs1); // convertimos id en codeCRM
    $etabs1 = implode(',', $codeetabs);
endif;


$begin = (!empty($element->begin)) ? $element->begin : '';
$end = (!empty($element->end)) ? $element->end : '';
$month = (!empty($element->month)) ? $element->month : '';
$nb_days = (!empty($element->nb_days)) ? $element->nb_days : 3;
$nb_adults = (!empty($element->nb_adults)) ? $element->nb_adults : 2;
$campaign_list = (!empty($element->campaign_list)) ? $element->campaign_list : '';
$partner_code = (!empty($element->partner_code)) ? $element->partner_code : '';
$sort_string = (!empty($element->sort_string)) ? $element->sort_string : '';
$room_features = (!empty($element->room_features)) ? $element->room_features : '';
$period_categories = (!empty($element->period_categories)) ? $element->period_categories : '';
$nb_children_1 = (!empty($element->nb_children_1)) ? $element->nb_children_1 : '';
$nb_children_2 = (!empty($element->nb_children_2)) ? $element->nb_children_2 : '';
$nb_babies = (!empty($element->nb_babies)) ? $element->nb_babies : '';
$max_results = (!empty($element->max_results)) ? $element->max_results : 10;
$product_code = (!empty($element->product_code)) ? $element->product_code : '';
$room_type = (!empty($element->room_type)) ? $element->room_type : '';
$room_typecatecory = (!empty($element->room_typecatecory)) ? $element->room_typecatecory : '';
$max_budget = (!empty($element->max_budget)) ? $element->max_budget : '';
$birth_dates = (!empty($element->birth_dates)) ? $element->birth_dates : '';
$yield_rule = (!empty($element->yield_rule)) ? $element->yield_rule : '';
$occupant_base_products = (!empty($element->occupant_base_products)) ? $element->occupant_base_products : '';
$no_on_request = (!empty($element->no_on_request)) ? $element->no_on_request : '';



$lienrechercher = (!empty($element->lienrechercher)) ? $element->lienrechercher : '';


if ($lienrechercher != '') :
    $lienrechercher = explode('&',  $lienrechercher);
    foreach ($lienrechercher as $lien) :
        if (strpos($lien, 'etabs') !== false) : $etabs1  = str_replace('etabs=', '', $lien);
        endif;
        if (strpos($lien, 'begin') !== false) : $begin  = str_replace('begin=', '', $lien);
        endif;
        if (strpos($lien, 'month') !== false) : $month  = str_replace('month=', '', $lien);
        endif;
        if (strpos($lien, 'nb_days') !== false) : $nb_days  = str_replace('nb_days=', '', $lien);
        endif;
        if (strpos($lien, 'nb_adults') !== false) : $nb_adults  = str_replace('nb_adults=', '', $lien);
        endif;
        if (strpos($lien, 'campaign_list') !== false) : $campaign_list  = str_replace('campaign_list=', '', $lien);
        endif;
        if (strpos($lien, 'partner_code') !== false) : $partner_code  = str_replace('partner_code=', '', $lien);
        endif;
        if (strpos($lien, 'sort_string') !== false) : $sort_string  = str_replace('sort_string=', '', $lien);
        endif;
        if (strpos($lien, 'room_features') !== false) : $room_features  = str_replace('room_features=', '', $lien);
        endif;
        if (strpos($lien, 'period_categories') !== false) : $period_categories  = str_replace("period_categories=", "", $lien);
        endif;
        if (strpos($lien, 'nb_children_1') !== false) : $nb_children_1  = str_replace('nb_children_1=', '', $lien);
        endif;
        if (strpos($lien, 'nb_children_2') !== false) : $nb_children_2  = str_replace('nb_children_2=', '', $lien);
        endif;
        if (strpos($lien, 'nb_babies') !== false) : $nb_babies  = str_replace('nb_babies=', '', $lien);
        endif;
        if (strpos($lien, 'max_results') !== false) : $max_results  = str_replace('max_results=', '', $lien);
        endif;
        if (strpos($lien, 'product_code') !== false) : $product_code  = str_replace('product_code=', '', $lien);
        endif;
        if (strpos($lien, 'room_type') !== false) : $room_type  = str_replace('room_type=', '', $lien);
        endif;
        if (strpos($lien, 'room_typecatecory') !== false) : $room_typecatecory  = str_replace('room_typecatecory=', '', $lien);
        endif;
        if (strpos($lien, 'max_budget') !== false) : $max_budget  = str_replace('max_budget=', '', $lien);
        endif;
        if (strpos($lien, 'birth_dates') !== false) : $birth_dates  = str_replace('birth_dates=', '', $lien);
        endif;
        if (strpos($lien, 'yield_rule') !== false) : $yield_rule  = str_replace('yield_rule=', '', $lien);
        endif;
        if (strpos($lien, 'occupant_base_products') !== false) : $occupant_base_products  = str_replace('occupant_base_products=', '', $lien);
        endif;
        if (strpos($lien, 'no_on_request') !== false) : $no_on_request  = str_replace('no_on_request=', '', $lien);
        endif;
    endforeach;

endif;



//FILTRAMOS ETABS

if ($filtros != 0 || $filtros != null) :

    $etabsRes = ModCampingsHelper::getetabsresultsCMS($filtros, $etabs1);

    //print_r($etabsRes);

    $etabs1 = $etabsRes;
    $countEtab = 1;
    $lastElement = count($etabs1) - 1;

    for ($i = 0; $i < count($etabs1); $i++) {

        if ($i != $lastElement) :
            $strEtab .= $etabs1[$i] . ',';
        else :
            $strEtab .= $etabs1[$i];
        endif;
        //(count($etabs1) == $countEtab) ? $strEtab .= $et : $strEtab .= $et.',';

    }
    $etabs1 = $strEtab;
endif;

/*print_r('ETAB STRING');
print_r($etabs1);*/

/*echo $etabs1;
echo $period_categories;
echo $nb_days;
echo $nb_adults;

echo '<br/>';
echo $etabs1;*/

//DESDE EL BACKOFFICE PODEMOS SELECCIONAR UNA SESSION O AMBAS PARA QUE APAREZCAN LOS ETABS DE AMBAS SESIONES

if ($saison == 0) :
    $arraysaisson = array(
        0 => 1,
        1 => 2,
    );
else :
    $arraysaisson = array(
        0 => $saison,
    );
endif;


//CUANDO NO HAY FECHAS
if ($begin == '' and $etabs1 != '' and $period_categories == '') :
    //echo 'sin fechas'; 
    $codes = explode(',', $etabs1);

    foreach ($codes as  $code) {

        foreach ($arraysaisson as $saison) {


            $resultHeberg = ModCampingsHelper::getEtabsProposals($code, $saison);  //RECUPERAMOS INFO HEBERGEMENT
            $tags = ModCampingsHelper::getInformationtablessimentConcret($saison, $code, 'C005', 'Tag');
            $regiongeographique = ModCampingsHelper::getRegionGeographique($saison, $code);

            if (!empty($resultHeberg)) :
                constructHtmlResultsBusqueda($resultHeberg, $host, $lang, $tags, $regiongeographique, $lienlang);
            endif;
            $apporperson = ($resultHeberg->typeDestination == 2) ? 'MOD_CAMPINGS_PER_PERS' : 'MOD_CAMPINGS_PER_APP';
            if ($saison == 1) :
                $prixhva = ModCampingsHelper::getInformationtablessiment($saison, $code, 'C005', 'PrixHVA');
            else :
                $prixhva = ModCampingsHelper::getInformationtablessiment($saison, $code, 'C005', 'Prix VAH');
            endif;

            if (!empty($prixhva)) :
                constructHtmlPrixMinium($prixhva, $host, $lang, $resultHeberg->alias, $apporperson);

            else :
            //echo '<p class="">'.JText::_('MOD_CAMPINGS_AUCUN_RESULTAT_TROUVE').'</p>';
            endif;
        }
    }
endif;

//CUANDO HAY FECHAS


if ($etabs1 != '' and ($period_categories != '' or $begin != '') and $nb_days != '' and $nb_adults != '') :




    $wsdlResalys = 'https://www.mmv.resalys.com/rsl/dwsdl?namespace=search_engine';

    $client = new SoapClient($wsdlResalys);
    $parametros = array(

        'base_id' => 'mmv',
        'username' => 'web2019',
        'password' => 'mmvpass',
        //'search_form_etab_list' =>'16,17,18,19,20,21,43,60,87,88,96,97,98,41,48,51,53,68,69,72,77,78,83,86,113,114,115,116,118,120,26,27,30,93,24,117,71',
        'search_form_etab_list' => $etabs1,
        'search_form_start_date' => $begin,
        'search_form_lookup_month' => $month,
        'search_form_nb_days' => $nb_days,
        'search_form_nb_adults' => $nb_adults,
        'search_form_campaign_list' => $campaign_list,
        'webuser' => 'web',
        'partner_code' => $partner_code,
        'service_id' => '',
        'search_form_sort_string' => $sort_string,
        'search_form_room_features' => $room_features,
        'search_form_period_categories' => $period_categories,
        'search_form_nb_children_1' => $nb_children_1,
        'search_form_nb_children_2' => $nb_children_2,
        'search_form_nb_babies' => $nb_babies,
        'search_form_max_results' => $max_results,
        'search_form_product_code' => $product_code,
        'search_form_room_type' => $room_type,
        'search_form_room_type_category' => $room_typecatecory,
        'search_form_min_budget' => '',
        'search_form_max_budget' => $max_budget,
        'search_form_birth_dates' => $birth_dates,
        'search_form_yield_rule' => $yield_rule,
        'search_form_occupant_base_products' => $occupant_base_products,
        'search_form_no_on_request' => $no_on_request,
        'search_form_no_counter_proposals' => '',
        'search_form_start_date_tolerance' => '',


    );



    $results = $client->__soapCall('getProposals75', $parametros);





    $results = json_decode(json_encode($results), true); //Object to array



    usort($results['proposal'], 'sort_by_orden_results');



    // if($_GET['test']=='1'):
    //echo '<pre>';
    //print_r($results);
    //echo '</pre>';
    // endif;


    $etabanterior = 0;
    //$i = 0;


    foreach ($results['proposal'] as $result) :




        //echo  $etabanterior;

        /*echo '<pre>';
                        print_r($resultHeberg);
                        echo '</pre>';*/


        //COMPROBAMOS SI EL ETAB SE HA PRINTADO YA

        if ($result['etab_id'] != $etabanterior or $result === end($results['proposal'])) :

            /*echo '<pre>';
                        print_r($resultHeberg);
                        echo '</pre>';*/
            if (!empty($dispos) and !empty($resultHeberg) and $resultHeberg != NULL) :


                //var_dump($resultHeberg);
                if (!empty($resultHeberg)) :
                    constructHtmlResultsBusqueda($resultHeberg, $host, $lang, $tags, $regiongeographique, $lienlang);
                    constructHtmlDispos($result['etab_id'], $dispos, $host, $resultHeberg->saison, $lang, $resultHeberg->alias);
                endif;

            /* echo '<pre>';
                                print_r($dispos);
                                echo '</pre>';*/


            endif;

            $regiongeographique = ModCampingsHelper::getRegionGeographique($saison, $result['etab_id']);
            $resultHeberg = ModCampingsHelper::getEtabsProposals($result['etab_id'], $saison);  //RECUPERAMOS INFO HEBERGEMENT
            $tags = ModCampingsHelper::getInformationtablessimentConcret($saison, $result['etab_id'], 'C005', 'Tag');

            $etabanterior = $result['etab_id'];
            $tabcontent = 0; // despues de printar el etab printaremos el tab formules
            $tabcategory = 1;
            $category = 1;
            $i = 0;
            $dispos = array();

        endif;

        //echo '<pre>';
        //print_r($result);
        //echo '</pre>'; 

        //BLOQUE TABS


        if ($result['etab_id'] == $etabanterior or $result === end($results['proposal'])) :  // solo printamos content tab al inicializarlo

            //echo '<pre>';
            //print_r($result);
            //echo '</pre>'; 

            $dispos[$result['base_product_code']]['etab'] =  $result['etab_id'];
            $dispos[$result['base_product_code']]['id'] =  $result['base_product_code'];

            //$nomtab = ModCampingsHelper::getNomFormule($result['base_product_code'],  $result['etab_id'], $saison);
            if ($lang == 'en') :
                $dispos[$result['base_product_code']]['nom'] = ModCampingsHelper::getNomFormuleEN($result['base_product_code'],  $result['etab_id'], $saison);
            else :
                $dispos[$result['base_product_code']]['nom'] = ModCampingsHelper::getNomFormule($result['base_product_code'],  $result['etab_id'], $saison);
            endif;
            $dispos[$result['base_product_code']]['iconformule'] = ModCampingsHelper::getIconFormule($result['base_product_code'],  $result['etab_id'], $saison);
            $dispos[$result['base_product_code']]['categoryetab'] =  ModCampingsHelper::getCategoryetab($result['etab_id'], $saison);

            if (isset($result['room_types']['room_type']['room_type_code'])) :

                //echo 'No es array';
                //var_dump($result['room_types']['room_type']);
                $dispos[$result['base_product_code']]['rooms'][$i]['room']['0']['code'] =  $result['room_types']['room_type']['room_type_code'];
                $dispos[$result['base_product_code']]['rooms'][$i]['room']['0']['label'] =  $result['room_types']['room_type']['room_type_label'];
                $inforoom = ModCampingsHelper::getInformationAlojamientoProposals($result['etab_id'], $dispos[$result['base_product_code']]['rooms'][$i]['room']['0']['code'], 'Typo', $saison);
                $dispos[$result['base_product_code']]['rooms'][$i]['room']['0']['ExtDescriptifCourt'] =  $inforoom->ExtDescriptifCourt;
                $dispos[$result['base_product_code']]['rooms'][$i]['room']['0']['ExtDescriptifLong'] =  $inforoom->ExtDescriptifLong;
                $dispos[$result['base_product_code']]['rooms'][$i]['room']['0']['ExtSuperficie'] =  $inforoom->ExtSuperficie;
                $dispos[$result['base_product_code']]['rooms'][$i]['room']['0']['OFfExtNbPiece'] =  $inforoom->OFfExtNbPiece;
                $dispos[$result['base_product_code']]['rooms'][$i]['room']['0']['OFfExtCapacite'] =  $inforoom->OFfExtCapacite;
                $dispos[$result['base_product_code']]['rooms'][$i]['room']['0']['OFfExtGamme'] =  $inforoom->OFfExtGamme;
                $dispos[$result['base_product_code']]['rooms'][$i]['room']['0']['Reference'] =  $inforoom->Reference;
                $dispos[$result['base_product_code']]['rooms'][$i]['room']['0']['chambre'] =  $result['room_types']['room_type']['room_type_count'];
            else :
                $a = 0;
                foreach ($result['room_types']['room_type'] as $room_type) :

                    $dispos[$result['base_product_code']]['rooms'][$i]['room'][$a]['code'] =  $room_type['room_type_code'];
                    $dispos[$result['base_product_code']]['rooms'][$i]['room'][$a]['label'] = $room_type['room_type_label'];
                    $inforoom = ModCampingsHelper::getInformationAlojamientoProposals($result['etab_id'], $dispos[$result['base_product_code']]['rooms'][$i]['room'][$a]['code'], 'Typo', $saison);
                    $dispos[$result['base_product_code']]['rooms'][$i]['room'][$a]['ExtDescriptifCourt'] =  $inforoom->ExtDescriptifCourt;
                    $dispos[$result['base_product_code']]['rooms'][$i]['room'][$a]['ExtDescriptifLong'] =  $inforoom->ExtDescriptifLong;
                    $dispos[$result['base_product_code']]['rooms'][$i]['room'][$a]['ExtSuperficie'] =  $inforoom->ExtSuperficie;
                    $dispos[$result['base_product_code']]['rooms'][$i]['room'][$a]['OFfExtNbPiece'] =  $inforoom->OFfExtNbPiece;
                    $dispos[$result['base_product_code']]['rooms'][$i]['room'][$a]['OFfExtCapacite'] =  $inforoom->OFfExtCapacite;
                    $dispos[$result['base_product_code']]['rooms'][$i]['room'][$a]['OFfExtGamme'] =  $inforoom->OFfExtGamme;
                    $dispos[$result['base_product_code']]['rooms'][$i]['room'][$a]['Reference'] =  $inforoom->Reference;
                    $dispos[$result['base_product_code']]['rooms'][$i]['room'][$a]['chambre'] =  $room_type['room_type_count'];
                    $a++;
                endforeach;

            endif;


            $dispos[$result['base_product_code']]['rooms'][$i]['begin'] =  $result['start_date'];
            $dispos[$result['base_product_code']]['rooms'][$i]['end'] =  $result['end_date'];
            $dispos[$result['base_product_code']]['rooms'][$i]['discont'] =  $result['adult_price_without_discounts'];
            $dispos[$result['base_product_code']]['rooms'][$i]['adult_price'] =  $result['adult_price'];
            $dispos[$result['base_product_code']]['rooms'][$i]['description'] =  $result['base_product_description'];
            $dispos[$result['base_product_code']]['rooms'][$i]['type_code'] =  $result['period_type_code'];
            $dispos[$result['base_product_code']]['rooms'][$i]['type_label'] =  $result['period_type_label'];
            $result['proposal_url'] = str_replace("base_id=mmv&", "",  $result['proposal_url']);
            $result['proposal_url'] = str_replace("reservation_content&", "reservation_content&reservation_content_sub_page=reservation_occupants&",  $result['proposal_url']);
            $dispos[$result['base_product_code']]['rooms'][$i]['url'] =  $result['proposal_url'];


            switch ($result['base_product_code']) {
                case 'DP':
                    $order = 1;
                    break;
                case 'PB':
                    $order = 0;
                    break;
                case 'PBALL':
                    $order = 2;
                    break;
                case 'PBRM':
                    $order = 3;
                    break;

                case 'LT':
                    $order = 0;
                    break;
                case 'TH':
                    $order = 1;
                    break;
                case 'LTPM':
                    $order = 2;
                    break;

                default:
                    $order = 0;
                    break;
            }
            $dispos[$result['base_product_code']]['order'] =  $order;

            $i++;

        /*echo $result['etab_id'];
                            echo $result['room_types']['room_type']['room_type_code'] ;
                            echo $saison;

                            echo '<pre>';
                            print_r($inforoom);
                            echo '</pre>';*/


        endif;

        if ($result === end($results['proposal'])) :
        //print_r($resultHeberg);
        //if(!empty($resultHeberg)):
        //constructHtmlResultsBusqueda($resultHeberg, $host, $lang, $tags, $regiongeographique);
        //constructHtmlDispos($result['etab_id'], $dispos, $host, $resultHeberg->saison, $lang, $resultHeberg->alias);
        //endif;
        endif;

    endforeach;

else :
//echo '<p class="">'.JText::_('MOD_CAMPINGS_AUCUN_RESULTAT_TROUVE').'</p>';
endif;

function sort_by_orden_results($a, $b)
{
    return $a['etab_id'] - $b['etab_id'];
}
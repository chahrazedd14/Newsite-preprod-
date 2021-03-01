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

ini_set('display_errors', 1);
error_reporting(1);

include  '../includes.php';
include JPATH_BASE . '/vendor/autoload.php';
require_once JPATH_BASE . '/Mongodb/class_mongoXML.php';
require_once JPATH_BASE . '/Mongo_cgos/class_mongoCGOS.php';
require_once JPATH_BASE . '/Mongo_macif/class_mongoMACIF.php';
// $mongo = new MmvMongo_Xml();
// var_dump($mongo);

//Verificar si estan puestas todas las colecciones
if ($_GET['test']) {

    $client = new MmvMongo_Xml();
    $collections = $client->db->listCollections();

    foreach ($collections as $collection) {
        echo '<pre>';
        var_dump($collection);
        echo '</pre>';
    }
}

//Verificar si estan puestas todas las colecciones
if ($_GET['lang']) {
    echo $lang;
}





//Variable que contiene el nombre de la funcion que se va a ejecutar
$c_function = $_POST['custom_function'];


//Siempre compruebo que exista o esté definida la variable y que no este vacía
if ((isset($c_function)) && (!empty($c_function))) {
    switch ($c_function) {
        case 'getHebergementByFormResults':
            getHebergementByFormResults();
            break;
        case 'getAjaxHebergementByFormResults':
            getAjaxHebergementByFormResults($lang);
            break;
        case 'getImagesFromDirectoryB':
            getImagesFromDirectoryB();
            break;
        default:
            # code...
            break;
    }
}



/**********************************************
 * Selecciona todas los alojamientos según
  los campos seleccionados en el formulario
 ***********************************************/
function getAjaxHebergementByFormResults($lang)
{

    $typeEstablishmentID = '';
    $autocomplete = '';
    $dateBegin = '';
    $dateEnd = '';
    $datefilter = '';
    $filter = [];
    $resultArr = [];
    $numberA  = 0;
    $numberB  = 0;
    $numberE1 = 0;
    $numberE2 = 0;
    $numberPers = 0;
    $mmvLang = 'fr';
    $orderby = 3;


    //me indica el tipo de establecimiento
    if (isset($_POST['typeEstablishment']) && !empty($_POST['typeEstablishment'])) {
        $typeEstablishmentID = $_POST['typeEstablishment'];
    } else {
        $typeEstablishmentID = '0';
    }

    //Si tengo definido un establecimiento o region
    if (isset($_POST['autocompleteID']) && !empty($_POST['autocompleteID'])) {
        $autocomplete = $_POST['autocompleteID'];
    }

    //Si tengo definido una fecha de incio
    if (isset($_POST['datefilter']) && !empty($_POST['datefilter'])) {
        $datefilter = explode('-', $_POST['datefilter']);
        $dateBegin = $datefilter[0];
        $dateEnd = $datefilter[1];
    }

    //Array de filtros seleccionados
    if (isset($_POST['filters']) && !empty($_POST['filters'])) {
        $filter = $_POST['filters'];
    }

    if (isset($_POST['temporada']) && !empty($_POST['temporada'])) {
        $temporada = $_POST['temporada'];
    } else {
        $temporada = $_COOKIE['saison'];
    }

    // Obtengo los valores que vienen en los campos de tipo de personas (adultos, niños mayores 6, niños menores de 6 y bebes) 
    if (isset($_POST['numberA']) && !empty($_POST['numberA'])) {
        $numberA =  $_POST['numberA'];
    }

    if (isset($_POST['numberE1']) && !empty($_POST['numberE1'])) {
        $numberE1 =  $_POST['numberE1'];
    }

    if (isset($_POST['numberE2']) && !empty($_POST['numberE2'])) {
        $numberE2 =  $_POST['numberE2'];
    }

    if (isset($_POST['numberB']) && !empty($_POST['numberB'])) {
        $numberB =  $_POST['numberB'];
    }

    if (isset($_POST['numberB']) && !empty($_POST['numberB'])) {
        $numberB =  $_POST['numberB'];
    }

    if (isset($_POST['numberPers']) && !empty($_POST['numberPers'])) {
        $numberPers =  $_POST['numberPers'];
    }
    if (isset($_POST['mmvLang']) && !empty($_POST['mmvLang'])) {
        $mmvLang =  $_POST['mmvLang'];
    }


    // ORDER BY maxprice = 1 AND minprice = 2 
    if (isset($_POST['priceOrder']) && !empty($_POST['priceOrder'])) {
        $orderby =  $_POST['priceOrder'];
    }
    // Hago la consulta a MySql y si las fechas estan definidas, buscare disponibilidad en Mongodb
    $resultHebergArr = getHebergementByFormResults($typeEstablishmentID, $autocomplete, $dateBegin, $dateEnd, $filter, $temporada, $numberA, $numberE1, $numberE2, $numberB, $numberPers, $mmvLang, $orderby);

    // echo json_encode($resultHebergArr);
    // die();

    $resultHeberg = $resultHebergArr['data'];

    //Si devuelve un array lleno
    if (count($resultHeberg) > 0) {

        $textDispos = '';
        $isFullArray = false;

        foreach ($resultHeberg as $key => $value) {

            $orderedDispo = sortMuldidimentionalArrayByKey($value['disponibilidades'], 'base_product_code');

            $resultHeberg[$key]['disponibilidades'] = $orderedDispo;

            $textDispos = $value['text'];

            $isFullArray = true;
        }

        $htmlHeberg = constructHtmlResultsBusqueda($resultHeberg, $temporada, $mmvLang);

        //Si hay o no establecimientos
        if (count($resultHeberg) > 0) {

            //Envio como respuesta un array con el html y el array de resultados
            $resultArr[] = array('data' =>  $resultHeberg, 'html' => $htmlHeberg, 'text' => $resultHebergArr['text']);
        } else {

            //Envio como respuesta un array con el texto sin dispo
            $resultArr[] = array('data' =>  '', 'html' => '', 'text' => 'Pas de résultats sur vos dates de séjours');
        }
    } else {

        //Envio como respuesta un array con el texto sin dispo
        $resultArr[] = array('data' =>  '', 'html' => '', 'text' => 'Pas de résultats sur vos dates de séjours');
    }

    // echo json_encode($resultHeberg);
    // die();


    //Si accedo a la funcion por ajax devuelvo un json 
    if (isset($_POST['ajax']) && !empty($_POST['ajax'])) {

        echo json_encode($resultArr);
        die();
    } else {

        //Si no accedo por ajax devuelvo el array normal
        return $resultArr;
    }
}



/**********************************************
 * Selecciona todas los alojamientos según
  los campos seleccionados en el formulario
 ***********************************************/
function getHebergementByFormResults($typeEstablishmentP, $autocompleteP, $dateBeginP, $dateEndP, $arrFiltersP, $temporada, $numberA, $numberE1, $numberE2, $numberB, $numberPers, $mmvLang, $orderby)
{

    if ($temporada == 1) {
        $strTemp = "HIVER";
    } else if ($temporada == 2) {
        $strTemp = "ETE";
    }


    $db    = JFactory::getDbo();
    $query = $db->getQuery(true);
    $typeEstablishmentID = 0;

    //me indica el tipo de establecimiento
    if (isset($typeEstablishmentID) && ($typeEstablishmentP != '')) {
        $typeEstablishmentID = $typeEstablishmentP;
    }

    //me indica si la fecha de inicio de la estancia es vacía o tiene valor
    ($dateBeginP != '') ?  $dateBegin = $dateBeginP : $dateBegin = '';


    //me indica si la fecha de fin de la estancia es vacía o tiene valor
    ($dateEndP != '') ? $dateEnd = $dateEndP : $dateEnd = '';


    $filterINNER = '';


    if (($dateBegin != '') && ($dateEnd != '')) {
        $whereFilter = '';
    } else {
        $whereFilter = 'GROUP BY camping.id';
    }


    //Si hay algún filtro seleccionado
    if (count($arrFiltersP) > 0) {

        $strFilters = implode("','", $arrFiltersP);
        $strFilters = "'" . $strFilters . "'";


        //Inner join para filtros (El HAVING se usa para usar el IN como AND y no como OR)
        $filterINNER = 'INNER JOIN `kdn_campings_FiltresAsociaciones` as filterrelation ON filterrelation.codeFiltre in (' . $strFilters . ') ';
        $whereFilter = 'AND filterrelation.codeCRM = camping.codeCRM GROUP BY camping.id HAVING COUNT(DISTINCT filterrelation.codeFiltre) = ' . count($arrFiltersP) . '';
    }


    //Si tengo definido una region ( PADRE O CUALQUIERA)
    if ($autocompleteP != '') {

        //me indica si es un establecimiento o region, y su id
        $autocomplete = explode('__', $_POST['autocompleteID']);


        $hebergementID = $autocomplete[0];
        $hebergementType = $autocomplete[1];


        //TENGO UN TIPO DE ESTABLECIMIENTO SELECCIONADO
        if ($typeEstablishmentID > 0) {

            //SI, EL id ES MAYOR QUE 0, SIGNIFICA QUE TENGO UNA REGION SELECCIONADA
            if ($hebergementID > 0) {

                //Si tengo un tipo de establecimiento definido, busco los establecimientos de esa region q pertenecen a este tipo de alojamiento
                $query =  ' SELECT camping.id, camping.saison, camping.nom as nom, camping.gamma as gamme, offre1.ExtTarif,  camping.codeCRM, camping.alias, camping.etoiles, pais.nom as pais, offre.ExtDescriptifCourt as introtext, offre.OFfExtUKDescriptifCourt as introtexten , offre.ExtDescriptifLong as descrip, offre.OFfExtUKDescriptifLong as descripen,  alojamientos.OFfExtNbPiece as pieces, alojamientos.OFfExtCapacite as personas, alojamientos.OFfExtGamme as gamma, alojamientos.OFfExtCodePresta as codepresta, alojamientos.ExtSuperficie as metros , alojamientos.nom as nomAloj,alojamientos.ExtDescriptifCourt as descripCourtAloj, alojamientos.ExtDescriptifLong as descripLongAloj ,camping.gamma as etabgamma, camping.adress, camping.gpsLat, camping.gpsLon, camping.category, camping.ItmExtGamme as ItmExtGamme, camping.codeAvis, camping.ville, camping.regionpadre, camping.departement , camping.ExtSkiDomNom,camping.ItmExtStationCommune, camping.ItmExtDepartement, ville.nom as ville, ville.logo as villeimg, menu.path as menu, avis.AnsweredSurveys , camping.icon, camping.logostationcomplet, camping.logostationcompletnegative , camping.categoryeicone
                            FROM `kdn_campings_camping` as camping
                            LEFT JOIN  `kdn_campings_pais` as pais ON pais.id = camping.pais
                            ' . $filterINNER . '
                            LEFT JOIN `kdn_campings_localidad` as ville ON ville.id = camping.ville 
                            LEFT JOIN `kdn_campings_avis` as avis ON avis.avisCode = camping.codeAvis 
                            INNER JOIN `kdn_menu` as menu ON menu.alias = camping.alias AND menu.type = "component" AND menu.parent_id = 1
                            LEFT JOIN `kdn_campings_offre` as offre ON offre.codeCRM = camping.codeCRM AND offre.sousCatCode = "DescrWebEtab1" AND offre.CodeSaison = ' . $temporada . '
                            LEFT JOIN `kdn_campings_offre` as alojamientos ON alojamientos.codeCRM = camping.codeCRM AND alojamientos.CategorieCode = "C003" AND alojamientos.ExtCategorieLabel = "stock"  AND ( alojamientos.ExtSaison LIKE "%' . $strTemp . '%" OR alojamientos.ExtSaison LIKE "%TOUTES%")
                            LEFT JOIN `kdn_campings_offre` AS offre1 ON  offre1.codeCRM = camping.codeCRM AND offre1.CodeSaison = camping.saison AND offre1.sousCatCode = "PrixHVA" 


                            WHERE camping.regionpadre = ' . $hebergementID . ' AND camping.state = 1 AND camping.saison = ' . $temporada . ' AND camping.gamma = ' . $typeEstablishmentID . ' ' . $whereFilter . ' ';
            } //SI LA REGION ES PARTOUT, O SEA TODAS LAS REGIONES
            else if ($hebergementID == 0) {

                //buscaré todos los alojamientos sin tener en cuenta la region
                $query =  ' SELECT camping.id, camping.saison, camping.nom as nom, camping.gamma as gamme, offre1.ExtTarif, camping.codeCRM, camping.alias, camping.etoiles, pais.nom as pais, offre.ExtDescriptifCourt as introtext, offre.OFfExtUKDescriptifCourt as introtexten , offre.ExtDescriptifLong as descrip, offre.OFfExtUKDescriptifLong as descripen, alojamientos.OFfExtNbPiece as pieces, alojamientos.OFfExtCapacite as personas, alojamientos.OFfExtGamme as gamma, alojamientos.OFfExtCodePresta as codepresta, alojamientos.ExtSuperficie as metros , alojamientos.nom as nomAloj,alojamientos.ExtDescriptifCourt as descripCourtAloj, alojamientos.ExtDescriptifLong as descripLongAloj , camping.gamma as etabgamma, camping.adress, camping.gpsLat, camping.gpsLon, camping.category, camping.ItmExtGamme as ItmExtGamme, camping.codeAvis, camping.ville, camping.regionpadre, camping.departement  , camping.ExtSkiDomNom,camping.ItmExtStationCommune, camping.ItmExtDepartement,  ville.nom as ville, ville.logo as villeimg, menu.path as menu, avis.AnsweredSurveys , camping.icon, camping.logostationcomplet, camping.logostationcompletnegative , camping.categoryeicone
                            FROM `kdn_campings_camping` as camping
                            LEFT JOIN  `kdn_campings_pais` as pais ON pais.id = camping.pais
                            ' . $filterINNER . '
                            LEFT JOIN `kdn_campings_localidad` as ville ON ville.id = camping.ville
                            LEFT JOIN `kdn_campings_avis` as avis ON avis.avisCode = camping.codeAvis  
                            INNER JOIN `kdn_menu` as menu ON menu.alias = camping.alias AND menu.type = "component" AND menu.parent_id = 1
                            LEFT JOIN `kdn_campings_offre` as offre ON offre.codeCRM = camping.codeCRM AND offre.sousCatCode = "DescrWebEtab1" AND offre.CodeSaison = ' . $temporada . '
                            LEFT JOIN `kdn_campings_offre` as alojamientos ON alojamientos.codeCRM = camping.codeCRM AND alojamientos.CategorieCode = "C003" AND alojamientos.ExtCategorieLabel = "stock"  AND ( alojamientos.ExtSaison LIKE "%' . $strTemp . '%" OR alojamientos.ExtSaison LIKE "%TOUTES%")
                            LEFT JOIN `kdn_campings_offre` AS offre1 ON  offre1.codeCRM = camping.codeCRM AND offre1.CodeSaison = camping.saison AND offre1.sousCatCode = "PrixHVA" 
                            WHERE camping.state = 1 AND camping.saison = ' . $temporada . '' . $whereFilter . ' ';
            }
        } //NO TENGO UN TIPO DE ESTABLECIMIENTO SELECCIONADO
        else if ($typeEstablishmentID == 0) {

            //SI, EL id ES MAYOR QUE 0, SIGNIFICA QUE TENGO UNA REGION SELECCIONADA
            if ($hebergementID > 0) {

                //Si no tengo tipo de establecimiento definido pero la region padre es distinta de 0, o sea no es PARTOUT, busco todos los alojamientos de la region padre
                $query =  ' SELECT camping.id, camping.saison, camping.nom as nom, camping.gamma as gamme, offre1.ExtTarif, camping.codeCRM, camping.alias, camping.etoiles, pais.nom as pais, offre.ExtDescriptifCourt as introtext, offre.OFfExtUKDescriptifCourt as introtexten , offre.ExtDescriptifLong as descrip, offre.OFfExtUKDescriptifLong as descripen, alojamientos.OFfExtNbPiece as pieces, alojamientos.OFfExtCapacite as personas, alojamientos.OFfExtGamme as gamma, alojamientos.OFfExtCodePresta as codepresta, alojamientos.ExtSuperficie as metros ,  alojamientos.nom as nomAloj,alojamientos.ExtDescriptifCourt as descripCourtAloj, alojamientos.ExtDescriptifLong as descripLongAloj ,camping.gamma as etabgamma, camping.adress, camping.gpsLat, camping.gpsLon, camping.category, camping.ItmExtGamme as ItmExtGamme, camping.codeAvis, camping.ville, camping.regionpadre, camping.departement , camping.ExtSkiDomNom, camping.ItmExtStationCommune, camping.ItmExtDepartement, ville.nom as ville, ville.logo as villeimg, menu.path as menu, avis.AnsweredSurveys , camping.icon, camping.logostationcomplet, camping.logostationcompletnegative , camping.categoryeicone
                            FROM `kdn_campings_camping` as camping
                            LEFT JOIN  `kdn_campings_pais` as pais ON pais.id = camping.pais
                            ' . $filterINNER . '
                            LEFT JOIN `kdn_campings_localidad` as ville ON ville.id = camping.ville
                            LEFT JOIN `kdn_campings_avis` as avis ON avis.avisCode = camping.codeAvis  
                            INNER JOIN `kdn_menu` as menu ON menu.alias = camping.alias AND menu.type = "component" AND menu.parent_id = 1
                            LEFT JOIN `kdn_campings_offre` as offre ON offre.codeCRM = camping.codeCRM AND offre.sousCatCode = "DescrWebEtab1" AND offre.CodeSaison = ' . $temporada . ' 
                            LEFT JOIN `kdn_campings_offre` as alojamientos ON alojamientos.codeCRM = camping.codeCRM AND alojamientos.CategorieCode = "C003" AND alojamientos.ExtCategorieLabel = "stock"  AND ( alojamientos.ExtSaison LIKE "%' . $strTemp . '%" OR alojamientos.ExtSaison LIKE "%TOUTES%")
                            LEFT JOIN `kdn_campings_offre` AS offre1 ON  offre1.codeCRM = camping.codeCRM AND offre1.CodeSaison = camping.saison AND offre1.sousCatCode = "PrixHVA" 
                            WHERE camping.regionpadre = ' . $hebergementID . ' AND camping.state = 1 AND camping.saison = ' . $temporada . ' ' . $whereFilter . ' ';
            } else if ($hebergementID == 0) {
                //Si la region padre es 0, significa que buscaré todos los alojamientos para la region padre (PARTOUT)
                $query =  ' SELECT camping.id, camping.saison, camping.nom as nom, camping.gamma as gamme, offre1.ExtTarif, camping.codeCRM, camping.alias, camping.etoiles, pais.nom as pais, offre.ExtDescriptifCourt as introtext, offre.OFfExtUKDescriptifCourt as introtexten , offre.ExtDescriptifLong as descrip, offre.OFfExtUKDescriptifLong as descripen, alojamientos.OFfExtNbPiece as pieces, alojamientos.OFfExtCapacite as personas, alojamientos.OFfExtGamme as gamma, alojamientos.OFfExtCodePresta as codepresta, alojamientos.ExtSuperficie as metros ,  alojamientos.nom as nomAloj,alojamientos.ExtDescriptifCourt as descripCourtAloj, alojamientos.ExtDescriptifLong as descripLongAloj ,camping.gamma as etabgamma, camping.adress, camping.gpsLat, camping.gpsLon, camping.category, camping.ItmExtGamme as ItmExtGamme, camping.codeAvis, camping.ville, camping.regionpadre, camping.departement  , camping.ExtSkiDomNom,camping.ItmExtStationCommune, camping.ItmExtDepartement,  ville.nom as ville, ville.logo as villeimg, menu.path as menu, avis.AnsweredSurveys , camping.icon, camping.logostationcomplet, camping.logostationcompletnegative , camping.categoryeicone
                            FROM `kdn_campings_camping` as camping
                            LEFT JOIN  `kdn_campings_pais` as pais ON pais.id = camping.pais
                            ' . $filterINNER . '
                            LEFT JOIN `kdn_campings_localidad` as ville ON ville.id = camping.ville
                            LEFT JOIN `kdn_campings_avis` as avis ON avis.avisCode = camping.codeAvis  
                            INNER JOIN `kdn_menu` as menu ON menu.alias = camping.alias AND menu.type = "component" AND menu.parent_id = 1
                            LEFT JOIN `kdn_campings_offre` as offre ON offre.codeCRM = camping.codeCRM AND offre.sousCatCode = "DescrWebEtab1" AND offre.CodeSaison = ' . $temporada . '
                            LEFT JOIN `kdn_campings_offre` as alojamientos ON alojamientos.codeCRM = camping.codeCRM AND alojamientos.CategorieCode = "C003" AND alojamientos.ExtCategorieLabel = "stock"  AND ( alojamientos.ExtSaison LIKE "%' . $strTemp . '%" OR alojamientos.ExtSaison LIKE "%TOUTES%")
                            LEFT JOIN `kdn_campings_offre` AS offre1 ON  offre1.codeCRM = camping.codeCRM AND offre1.CodeSaison = camping.saison AND offre1.sousCatCode = "PrixHVA" 
                            WHERE camping.state = 1 AND camping.saison = ' . $temporada . ' ' . $whereFilter . ' ';
            }
        }
    } //SI NO TENGO NADA EN EL AUTOCOMPLETE; TRAERE TODOS LOS RESULTADOS
    else {

        //TENGO UN TIPO DE ESTABLECIMIENTO SELECCIONADO
        if ($typeEstablishmentID > 0) {
            $query =  ' SELECT camping.id, camping.saison, camping.nom as nom, camping.gamma as gamme, camping.codeCRM, camping.alias, camping.etoiles, pais.nom as pais, offre.ExtDescriptifCourt as introtext, offre.OFfExtUKDescriptifCourt as introtexten , offre.ExtDescriptifLong as descrip, offre.OFfExtUKDescriptifLong as descripen, alojamientos.OFfExtNbPiece as pieces, alojamientos.OFfExtCapacite as personas, alojamientos.OFfExtGamme as gamma, alojamientos.OFfExtCodePresta as codepresta, alojamientos.ExtSuperficie as metros ,  alojamientos.nom as nomAloj, alojamientos.ExtDescriptifCourt as descripCourtAloj, alojamientos.ExtDescriptifLong as descripLongAloj ,camping.gamma as etabgamma, camping.adress, camping.gpsLat, camping.gpsLon, camping.category, camping.ItmExtGamme as ItmExtGamme, camping.codeAvis, camping.ville, camping.regionpadre, camping.departement  , camping.ExtSkiDomNom,camping.ItmExtStationCommune, camping.ItmExtDepartement, ville.nom as ville, ville.logo as villeimg, menu.path as menu, avis.AnsweredSurveys , camping.icon, camping.logostationcomplet, camping.logostationcompletnegative , camping.categoryeicone
            FROM `kdn_campings_camping` as camping
            LEFT JOIN  `kdn_campings_pais` as pais ON pais.id = camping.pais
            ' . $filterINNER . '
            LEFT JOIN `kdn_campings_localidad` as ville ON ville.id = camping.ville
            LEFT JOIN `kdn_campings_avis` as avis ON avis.avisCode = camping.codeAvis  
            INNER JOIN `kdn_menu` as menu ON menu.alias = camping.alias AND menu.type = "component" AND menu.parent_id = 1
            LEFT JOIN `kdn_campings_offre` as offre ON offre.codeCRM = camping.codeCRM AND offre.sousCatCode = "DescrWebEtab1" AND offre.CodeSaison = ' . $temporada . '
            LEFT JOIN `kdn_campings_offre` as alojamientos ON alojamientos.codeCRM = camping.codeCRM AND alojamientos.CategorieCode = "C003" AND alojamientos.ExtCategorieLabel = "stock"  AND ( alojamientos.ExtSaison LIKE "%' . $strTemp . '%" OR alojamientos.ExtSaison LIKE "%TOUTES%")
            WHERE camping.state = 1 AND camping.saison = ' . $temporada . ' AND camping.gamma = ' . $typeEstablishmentID . ' ' . $whereFilter . ' ';
        } else {
            $query =  ' SELECT camping.id, camping.saison, camping.nom as nom, camping.gamma as gamme, camping.codeCRM, camping.alias, camping.etoiles, pais.nom as pais, offre.ExtDescriptifCourt as introtext, offre.OFfExtUKDescriptifCourt as introtexten , offre.ExtDescriptifLong as descrip, offre.OFfExtUKDescriptifLong as descripen, alojamientos.OFfExtNbPiece as pieces, alojamientos.OFfExtCapacite as personas, alojamientos.OFfExtGamme as gamma, alojamientos.OFfExtCodePresta as codepresta, alojamientos.ExtSuperficie as metros ,  alojamientos.nom as nomAloj, alojamientos.ExtDescriptifCourt as descripCourtAloj, alojamientos.ExtDescriptifLong as descripLongAloj ,camping.gamma as etabgamma, camping.adress, camping.gpsLat, camping.gpsLon, camping.category, camping.ItmExtGamme as ItmExtGamme, camping.codeAvis, camping.ville, camping.regionpadre, camping.departement  , camping.ExtSkiDomNom,camping.ItmExtStationCommune, camping.ItmExtDepartement, ville.nom as ville, ville.logo as villeimg, menu.path as menu, avis.AnsweredSurveys , camping.icon, camping.logostationcomplet, camping.logostationcompletnegative , camping.categoryeicone
            FROM `kdn_campings_camping` as camping
            LEFT JOIN  `kdn_campings_pais` as pais ON pais.id = camping.pais
            ' . $filterINNER . '
            LEFT JOIN `kdn_campings_localidad` as ville ON ville.id = camping.ville
            LEFT JOIN `kdn_campings_avis` as avis ON avis.avisCode = camping.codeAvis  
            INNER JOIN `kdn_menu` as menu ON menu.alias = camping.alias AND menu.type = "component" AND menu.parent_id = 1
            LEFT JOIN `kdn_campings_offre` as offre ON offre.codeCRM = camping.codeCRM AND offre.sousCatCode = "DescrWebEtab1" AND offre.CodeSaison = ' . $temporada . '
            LEFT JOIN `kdn_campings_offre` as alojamientos ON alojamientos.codeCRM = camping.codeCRM AND alojamientos.CategorieCode = "C003" AND alojamientos.ExtCategorieLabel = "stock"  AND ( alojamientos.ExtSaison LIKE "%' . $strTemp . '%" OR alojamientos.ExtSaison LIKE "%TOUTES%")
            WHERE camping.state = 1 AND camping.saison = ' . $temporada . '  ' . $whereFilter . ' ';
        }
    }



    $db->setQuery($query);
    $hebergementResults = $db->loadObjectList();

    $hebergementsSinDispoData = [];

    // return $hebergementResults;

    //Eliminamos los alojamientos que vienen duplicados
    $hegergementsNoDuplicate = [];
    $hegergementsNoDuplicateIds = [];

    if (count($hebergementResults) > 0) { // PUESTO CRIS

        foreach ($hebergementResults as $results) {

            if (!in_array($results->id, $hegergementsNoDuplicateIds)) {
                array_push($hegergementsNoDuplicateIds, $results->id);
                array_push($hegergementsNoDuplicate, $results);
            }
        }

        // Si las fechas están definidas busco disponibilidades con fechas exactas en MONDODB
        if (($dateBegin != '') && ($dateEnd != '')) {

            //echo 'hay fechas definidas';

            $hebergementsWithDispo = busquedaDisponibilidades($hegergementsNoDuplicate, $dateBegin, $dateEnd, $numberA, $numberE1, $numberE2, $numberB, $numberPers, $mmvLang);
            //return $hebergementsWithDispo;

            //ORDER BY
            if ($orderby == 2) {

                usort($hebergementsWithDispo['data'], "cmpResultsAscDispo");
            } //Ordeno de mayor a menor
            elseif ($orderby == 1) {

                usort($hebergementsWithDispo['data'], "cmpResultsDescDispo");
            } else {
                shuffle($hebergementsWithDispo['data']);
            }
            return $hebergementsWithDispo;
        } else {

            // AGREGA PRECIOS DESDE AL ARRAY Y LOS ORDENA DE MENOR A MAYOR
            $hegergementsNoDuplicate = agregaPreciosDesde($hegergementsNoDuplicate, $temporada);

            // ordeno de menor a mayor
            if ($orderby == 2) {

                usort($hegergementsNoDuplicate, "cmpResultsAsc");
            } //Ordeno de mayor a menor
            elseif ($orderby == 1) {

                usort($hegergementsNoDuplicate, "cmpResultsDesc");
            }

            //Si no, no haré la busqueda de disponibilidades en MONGODB
            $hebergementsSinDispo = [];
            foreach ($hegergementsNoDuplicate as $hebergement) {
                array_push($hebergementsSinDispo,  array('establecimiento' => $hebergement, 'disponibilidades' => [], 'text' => ''));
            }

            $hebergementsSinDispoData['data'] = $hebergementsSinDispo;
            return $hebergementsSinDispoData;
        }
    } else {
        //Si no hay alojamientos de la consulta a MysQl devuelvo el array vacio
        $hebergementsSinDispoData['data'] = $hebergementResults;
        return $hebergementsSinDispoData;
    }
}

// Ordenamos el objeto de resultados con precio a partir de (menor a mayor)
function cmpResultsAsc($a, $b)
{
    return $a->precioDesde - $b->precioDesde;
}

// Ordenamos el objeto de resultados con precio a partir de (mayor a menor)
function cmpResultsDesc($a, $b)
{
    return $b->precioDesde - $a->precioDesde;
}

// Ordenamos el objeto de resultados con precio a partir de (menor a mayor)
function cmpResultsAscDispo($a, $b)
{
    return $a['establecimiento']->precioDesde - $b['establecimiento']->precioDesde;
}

// Ordenamos el objeto de resultados con precio max de (mayor a menor)
function cmpResultsDescDispo($a, $b)
{
    return $b['establecimiento']->precioMax - $a['establecimiento']->precioMax;
}

// ORDENA PRECIOS ORDER BY
function agregaPreciosDesde($hegergementsNoDuplicate, $temporada)
{
    $hebergementsSinDispoTemp = [];
    foreach ($hegergementsNoDuplicate as $hebergement) {

        $prixhva = ModCampingsHelper::getInformationtablessiment($temporada, $hebergement->codeCRM, 'C005', 'Prix');

        foreach ($prixhva as $scolaires) :
            if ($scolaires->sousCatCode == 'PrixHVA' and $temporada == 1) :
                $hebergement->precioDesde = $scolaires->ExtTarif;
            endif;
            if ($scolaires->sousCatCode == 'Prix VAH' and $temporada == 2) :
                $hebergement->precioDesde = $scolaires->ExtTarif;
            endif;
        endforeach;
    }
    return $hegergementsNoDuplicate;
}



/****************************************************************************************** 
    Función: Obtener todas las disponibilidades de un array de establecimientos
    Parámetros: Espera un array de alojamientos, string fecha de inicio, string fecha fin,
                instancia de mongo
 ****************************************************************************************** */

function busquedaDisponibilidades($hebergementsSinDispo, $dateBeginP = '', $dateEndP = '', $numberA, $numberE1, $numberE2, $numberB, $numberPers, $mmvLang)
{

    //echo 'entro a buscar dispo <br>';

    $hebergementsHotelIDs = [];
    $hebergementsResiIDs = [];

    $cursorEstablecHotels = '';
    $cursorEstablecResi = '';

    $dateBegin = explode('/', $dateBeginP);
    $dateEnd = explode('/', $dateEndP);


    //echo $mmvLang;
    //$mondongo =  new MmvMongo_Xml();

    //Si el lenguage es CGOS me conecto a la BD de CGOS
    switch ($mmvLang) {
        case 'bs-ba':
            $mondongo =  new MmvMongo_CGOS();
            break;
        case 'macif':
            $mondongo =  new MmvMongo_MACIF();
            break;
        default:
            $mondongo =  new MmvMongo_Xml();
            break;
    }



    $dateBegin = trim($dateBegin[2]) . '-' . trim($dateBegin[1]) . '-' . trim($dateBegin[0]);
    $dateEnd = trim($dateEnd[2]) . '-' . trim($dateEnd[1]) . '-' . trim($dateEnd[0]);

    //Convierto la fecha de inicio a formato Mongo para pasarle como parametro a la consulta
    $start_date_utc = new MongoDB\BSON\UTCDateTime((new DateTime($dateBegin))->getTimestamp() * 1000);

    //Convierto la fecha de fin a formato Mongo para pasarle como parametro a la consulta
    $end_date_utc = new MongoDB\BSON\UTCDateTime((new DateTime($dateEnd))->getTimestamp() * 1000);

    $hebergementSinDuplicados = [];


    //Llenar el array con los codigosCRM de los alojamientos a analizar
    foreach ($hebergementsSinDispo as $hebergement) {

        //Separo los ids por hotel o residencias
        if (($hebergement->gamme == '2') && !in_array($hebergement->codeCRM, $hebergementsHotelIDs)) {
            array_push($hebergementsHotelIDs, $hebergement->codeCRM);
        } else  if ((($hebergement->gamme == '1') || ($hebergement->gamme == '3')) && !in_array($hebergement->codeCRM, $hebergementsResiIDs)) {
            array_push($hebergementsResiIDs, $hebergement->codeCRM);
        }
    }


    //La cantidad de niños se cuenta como la suma de estos dos campos
    $numberChildrens = intval($numberE1) + intval($numberE2);

    //Al numero total le resto los bebes porque no cuentan para una cama disponible, será una cuna
    if (intval($numberB) > 0) {
        $numberPers = intval($numberPers) - intval($numberB);
    }

    //Si la cantidad de personas es 0 o menor, o no esta definida
    if ($numberPers <= 0 || $numberPers == null || $numberPers == 'undefined') {
        $numberPers = 1;
    }


    /*
    *
    * CAMBIO #350000!
    * Para los hoteles, ya no importa la cantidad total de personas.
    * A partir de ahora:
    * Si busco 2 adultos
    *   - coincidencia exacta nb_adults = 2
    * Si busco 2 adultos y un niño de (6-12 ans)
    *   - coincidencia exacta nb_adults = 2 AND nb_children = 1 ($numberE1) OR
    *   - nb_adults = 3
    * Si busco 2 adultos y un niño de (2-6 ans) 
    *   - coincidencia exacta nb_adults = 2 AND nb_children = 1  ($numberE1) OR
    *   - coincidencia exacta nb_adults = 2 AND nb_children2 = 1 ($numberE2) OR
    *   - nb_adults = 3
    *
     */

    // return $dateBegin;


    //CONSULTA A MONGODB PARA LOS HOTELES 
    if (!empty($hebergementsHotelIDs)) {

        $numberA  = intval($numberA);
        $numberE1 = intval($numberE1);
        $numberE2 = intval($numberE2);

        //El numero de adultos siempre tiene que ser mayor a 0 
        if ($numberA == 0) {
            $numberA = 2;
        }

        $mongoQuery =  [
            'etab_id' => ['$in' => $hebergementsHotelIDs],
            'start_date'    => $start_date_utc,
            'end_date'      => $end_date_utc,
            'nb_adults'     => $numberA,
            'nb_children'   => $numberE1,
            'nb_children2'  => $numberE2
        ];

        $cursorEstablecHotels = $mondongo->collection->find($mongoQuery);
    }


    //return $cursorEstablecHotels->toArray();


    //CONSULTA A MONGODB PARA LAS RESIDENCIAS 
    if (!empty($hebergementsResiIDs)) {

        $queryResi = [
            'etab_id'               => ['$in' => $hebergementsResiIDs],
            'start_date'            =>  $start_date_utc,
            'end_date'              =>  $end_date_utc,
            'nb_first_room_pax_max' => ['$gte' => intval($numberPers)]
        ];



        //Consulta Mongo que me busca en el array los alojamientos con disponibilidades en las fechas exactas
        $cursorEstablecResi = $mondongo->collection->find($queryResi);
    }

    //return $cursorEstablecResi->toArray();


    if ($cursorEstablecResi == null) {

        $disponibilidades = $cursorEstablecHotels->toArray();
    } else if ($cursorEstablecHotels == null) {

        $disponibilidades = $cursorEstablecResi->toArray();
    } else {
        //Array con todas las disponibilidades de hoteles y residencias
        $disponibilidades = array_merge($cursorEstablecHotels->toArray(), $cursorEstablecResi->toArray());
    }

    $finalDispos = [];
    $finalDisposOrdere = [];
    $finalDispoType = [];

    //Hay disponibilidades para las fechas seleccionadas
    if (count($disponibilidades) > 0) {

        $finalDispos        = combineMongoAndMysql($hebergementsSinDispo, $disponibilidades, $finalDispos, '');
        $finalDisposOrdered = orderMinMaxPrice($finalDispos);
        $finalDispoType     = array('data' => $finalDisposOrdered, 'text' => '');
    } else { //Si no hay disponibilidades, hare la contrapropuesta #1

        $disponibilidadesC1 = primeraContrapropuesta($mondongo, $hebergementsHotelIDs, $hebergementsResiIDs, $dateBegin, $dateEnd, $numberA, $numberE1, $numberE2, $numberPers);

        //Si hay disponibilidades en la contrapropuesta #1
        if (count($disponibilidadesC1) > 0) {
            $finalDispos        = combineMongoAndMysqlContras($hebergementsSinDispo, $disponibilidadesC1, $finalDispos, JText::_('MOD_CAMPINGS_RESULTS_CONTRA1'));
            $finalDisposOrdered = orderMinMaxPrice($finalDispos);
            $finalDispoType     = array('data' => $finalDisposOrdered, 'text' => JText::_('MOD_CAMPINGS_RESULTS_CONTRA1'));
        } else {

            //Si no hay disponibilidades, hare la contrapropuesta #2
            $disponibilidadesC2 = segundaContrapropuesta($mondongo, $hebergementsHotelIDs, $hebergementsResiIDs, $start_date_utc, $end_date_utc, $numberA, $numberE1, $numberE2, $numberPers);

            if (count($disponibilidadesC2) > 0) {

                $finalDispos        = combineMongoAndMysqlContras($hebergementsSinDispo, $disponibilidadesC2, $finalDispos, JText::_('MOD_CAMPINGS_RESULTS_CONTRA2'));
                $finalDisposOrdered = orderMinMaxPrice($finalDispos);
                $finalDispoType     = array('data' => $finalDisposOrdered, 'text' => JText::_('MOD_CAMPINGS_RESULTS_CONTRA2'));
            } else {

                $finalDispos = [];
                $finalDispoType = array('data' => $finalDispos, 'text' => '');
            }
        }
    }

    return $finalDispoType;
    //echo json_encode($finalDispos);
}

function orderMinMaxPrice($finalDispos)
{
    //ORDER BY MIN OR MAX PRICE
    foreach ($finalDispos as $hebergement) {
        $min = min(array_map(function ($a) {
            return $a->price;
        }, $hebergement['disponibilidades']));
        $max = max(array_map(function ($a) {
            return $a->price;
        }, $hebergement['disponibilidades']));
        $hebergement['establecimiento']->precioDesde = $min;
        $hebergement['establecimiento']->precioMax = $max;
    }
    return  $finalDispos;
}



function combineMongoAndMysql($hebergementsSinDispo, $disponibilidades, $finalDispos1, $text)
{

    $finalDisposByHebergement = [];
    $finalDispos = [];
    $arrayId = [];

    $countIndex = 0;

    foreach ($hebergementsSinDispo as $hebergement) {

        foreach ($disponibilidades as $disponibilidad) {

            //llamo a la function que me hace el ordering por tab
            //recorro el array de dispos y si el base_product_code(mongo) = base_product_code(mysql) lo añado al array 
            $tabOrdering = getTabsOrdering($disponibilidad->etab_id, $disponibilidad->base_product_code);
            $disponibilidad->ordering = $tabOrdering;

            if ($hebergement->codeCRM == $disponibilidad->etab_id) {

                //array_push($finalDisposByHebergement, array('hebergement' => $hebergement->codeCRM, 'disponibilidad' => $disponibilidad->etab_id));

                //COMBINO ARRAY DE ESTABLECIMIENTOS CON ARRAY DE DISPONIBILIDADES CON CODIGO DE CRM COMO KEY
                $finalDispos[$hebergement->codeCRM]['establecimiento']     =  $hebergement;
                $finalDispos[$hebergement->codeCRM]['disponibilidades'][]  =  $disponibilidad;
            }
        }
    }
    return $finalDispos;
}


function combineMongoAndMysqlContras($hebergementsSinDispo, $disponibilidades, $finalDispos1, $text)
{

    $finalDisposByHebergement = [];
    $finalDispos = [];
    $arrayId = [];

    $countIndex = 0;

    foreach ($hebergementsSinDispo as $hebergement) {

        foreach ($disponibilidades as $disponibilidad) {

            //llamo a la function que me hace el ordering por tab
            //recorro el array de dispos y si el base_product_code(mongo) = base_product_code(mysql) lo añado al array 
            $tabOrdering = getTabsOrdering($disponibilidad->etab_id, $disponibilidad->base_product_code);
            $disponibilidad->ordering = $tabOrdering;


            if ($hebergement->codeCRM == $disponibilidad['etab_id']) {

                //sarray_push($finalDisposByHebergement, array('hebergement' => $hebergement->codeCRM, 'disponibilidad' => $disponibilidad->etab_id));

                //COMBINO ARRAY DE ESTABLECIMIENTOS CON ARRAY DE DISPONIBILIDADES CON CODIGO DE CRM COMO KEY
                $finalDispos[$hebergement->codeCRM]['establecimiento']     =  $hebergement;
                $finalDispos[$hebergement->codeCRM]['disponibilidades'][]  =  $disponibilidad;
            }
        }
    }
    return $finalDispos;
    //return $finalDisposByHebergement;
}





/****************************************************************************************** 
    Función: Primera contrapropuesta, muestra los mismos alojamientos buscados en diferentes
             fechas, con un rango de + o - 7 días de la fecha de inicio que busca el cliente
             y la duración de la estancia esté entre 7 y 14 días
    Parámetros: Espera un array de strings de alojamientos, 
                string fecha de inicio, 
                string fecha fin, 
                instancia de mongo
 ****************************************************************************************** */

function primeraContrapropuesta($mondongo, $hebergementsHotelIDs, $hebergementsResiIDs, $dateBeginP, $dateEndP, $numberA, $numberE1, $numberE2, $numberPers)
{

    $disponibilidades = [];
    $cursorCount = 0;

    //Obtenemos la cantidad de noches de la estancia
    $datetimeAdd7 = new DateTime($dateBeginP);
    $datetimeRest7 = new DateTime($dateBeginP);

    $datetime2 = new DateTime($dateEndP);
    $interval = $datetimeAdd7->diff($datetime2);
    $days = $interval->days;

    //Fecha con 7 dias menor a la fecha de inicio
    $datetimeRest7->modify('-7 days');
    $sevenDatesBefore = $datetimeRest7->format('Y-m-d');

    //Fecha con 7 dias mayor a la fecha de inicio
    $datetimeAdd7->modify('+7 days');
    $sevenDatesAfter = $datetimeAdd7->format('Y-m-d');

    //$mondongo =  new MmvMongo_Xml();

    //Convierto la fecha de inicio a formato Mongo para pasarle como parametro a la consulta
    $start_date_ini = new MongoDB\BSON\UTCDateTime((new DateTime($sevenDatesBefore))->getTimestamp() * 1000);

    //Convierto la fecha de fin a formato Mongo para pasarle como parametro a la consulta
    $start_date_fin = new MongoDB\BSON\UTCDateTime((new DateTime($sevenDatesAfter))->getTimestamp() * 1000);



    //Busco los alojamientos en donde la fecha de inicio esté en el rango de 7 dias antes y 7 dias despues
    /*
        Ejemplo: Fechas de búsqueda inicial: 20/12 - 30/12
                -7 días = 13/12
                +7 días = 27/12
            La fecha de inicio (start_date) podrá ser cualquier día
            entre el 13/12 al 27/12    
    */


    //CONSULTA A MONGODB PARA LOS HOTELES 
    if (!empty($hebergementsHotelIDs)) {

        $numberA  = intval($numberA);
        $numberE1 = intval($numberE1);
        $numberE2 = intval($numberE2);

        //El numero de adultos siempre tiene que ser mayor a 0 
        if ($numberA == 0) {
            $numberA = 1;
        }

        $mongoQuery =  [
            'etab_id'       => ['$in' => $hebergementsHotelIDs],
            '$and' => [
                ['start_date' => ['$gte' =>  $start_date_ini]],
                ['start_date' => ['$lte' =>  $start_date_fin]]
            ],
            'nb_adults'     => $numberA,
            'nb_children'   => $numberE1,
            'nb_children2'  => $numberE2
        ];

        $cursorEstablecHotels = $mondongo->collection->find($mongoQuery);
    }

    //CONSULTA A MONGODB PARA LAS RESIDENCIAS 
    if (!empty($hebergementsResiIDs)) {

        $queryResi = [
            'etab_id'    => ['$in' => $hebergementsResiIDs],
            '$and' => [
                ['start_date' => ['$gte' =>  $start_date_ini]],
                ['start_date' => ['$lte' =>  $start_date_fin]]
            ],
            'nb_first_room_pax_max' => ['$gte' => intval($numberPers)]
        ];

        //Consulta Mongo que me busca en el array los alojamientos con disponibilidades en las fechas exactas
        $cursorEstablecResi = $mondongo->collection->find($queryResi);
    }


    $disponibilidadesContra1 = [];

    if ($cursorEstablecResi == null) {

        $disponibilidadesContra1 = $cursorEstablecHotels->toArray();
    } else if ($cursorEstablecHotels == null) {

        $disponibilidadesContra1 = $cursorEstablecResi->toArray();
    } else {
        //Array con todas las disponibilidades de hoteles y residencias
        $disponibilidadesContra1 = array_merge($cursorEstablecHotels->toArray(), $cursorEstablecResi->toArray());
    }

    //Cantidad de días de maxima duracion de la estancia
    $daysMaxDuration = $days + 7;

    //Cantidad de días de minima duracion de la estancia
    $daysMinDuration = $days - 7;

    $resultDispo = [];

    //AQUI VOY A HACER LA COMPROBACION DE LA DURACION MAXIMA Y MINIMA DE DIAS
    //lO HAREMOS POR PHP
    foreach ($disponibilidadesContra1 as $key => $row) {

        $minEndDate = convertMongoTimestampTodatePhp($row->start_date);
        $minEndDate = new DateTime($minEndDate);

        //Si es menor que cero, la estancia minima es de 1 noche
        if ($daysMinDuration > 0) {
            $minEndDate->modify('-' . $daysMinDuration . 'days');
            $minEndDate = $minEndDate->format('Y-m-d');
            // echo '<p>'.$minEndDate.'</p>';
        } else {
            $minEndDate->modify('+1 day');
            $minEndDate = $minEndDate->format('Y-m-d');
            //echo '<p>'.$minEndDate.'</p>';
        }


        $maxEndDate = convertMongoTimestampTodatePhp($row->start_date);
        $maxEndDate = new DateTime($maxEndDate);
        $maxEndDate->modify('+' . $daysMaxDuration . 'days');
        $maxEndDate = $maxEndDate->format('Y-m-d');
        //echo '<p>'.$maxEndDate.'</p>';

        //IMPRIMO LAS DISPO EN DONDE LA FECHA DE INICIO SEA:
        // - MENOR QUE LA DURACION MAXIMA DE DIAS 
        // - MAYOR QUE LA DURACION MINIMA DE DIAS 

        $myStartDate = convertMongoTimestampTodatePhp($row->start_date);
        $myEndDate = convertMongoTimestampTodatePhp($row->end_date);


        if ((strtotime($myEndDate) >= strtotime($minEndDate)) && (strtotime($myStartDate) <= strtotime($myEndDate))) {

            $disponibilidades[$cursorCount] = $row;

            $cursorCount++;
        }
    }

    return $disponibilidades;
}


/****************************************************************************************** 
    Función: Segunda contrapropuesta, muestra disponibilidad de otros alojamientos en las
             mismas fechas, si se ha establecido typo de establecimiento, debe buscarse 
             en establecimientos del mismo tipo
    Parámetros: Fecha de inicio convertida al formato Mongo BSON
                Fecha de fin convertida al formato Mongo BSON
                Instancia del obj mongo
 ****************************************************************************************** */

function segundaContrapropuesta($mondongo, $hebergementsHotelIDs, $hebergementsResiIDs, $dateBeginP, $dateEndP, $numberA, $numberE1, $numberE2, $numberPers)
{

    $disponibilidades = [];
    $cursorEstablecHotels  = [];
    $cursorEstablecResi    = [];

    //Convierto la fecha de inicio a formato Mongo para pasarle como parametro a la consulta
    $start_date_utc = new MongoDB\BSON\UTCDateTime((new DateTime($dateBeginP))->getTimestamp() * 1000);

    //Convierto la fecha de fin a formato Mongo para pasarle como parametro a la consulta
    $end_date_utc = new MongoDB\BSON\UTCDateTime((new DateTime($dateEndP))->getTimestamp() * 1000);


    //CONSULTA A MONGODB PARA LOS HOTELES 
    if (!empty($hebergementsHotelIDs)) {

        $numberA  = intval($numberA);
        $numberE1 = intval($numberE1);
        $numberE2 = intval($numberE2);

        //El numero de adultos siempre tiene que ser mayor a 0 
        if ($numberA == 0) {
            $numberA = 1;
        }

        $mongoQuery =  [
            'etab_id'       => ['$in' => $hebergementsHotelIDs],
            'start_date'    => $start_date_utc,
            'end_date'      => $end_date_utc,
            'nb_adults'     => $numberA,
            'nb_children'   => $numberE1,
            'nb_children2'  => $numberE2
        ];

        $cursorEstablecHotels = $mondongo->collection->find($mongoQuery);
    }

    //CONSULTA A MONGODB PARA LAS RESIDENCIAS 
    if (!empty($hebergementsResiIDs)) {

        $queryResi = [
            'etab_id'    => ['$in' => $hebergementsResiIDs],
            'start_date'    => $start_date_utc,
            'end_date'      => $end_date_utc,
            'nb_first_room_pax_max' => ['$gte' => intval($numberPers)]
        ];

        //Consulta Mongo que me busca en el array los alojamientos con disponibilidades en las fechas exactas
        $cursorEstablecResi = $mondongo->collection->find($queryResi);
    }

    //Consulta Mongo que me busca en el array los alojamientos con disponibilidades en las fechas exactas
    $cursorEstablec = $mondongo->collection->find([
        'start_date' =>  $start_date_utc,
        'end_date' =>   $end_date_utc
    ]);

    $disponibilidadesContra2 = [];

    if ($cursorEstablecResi == null) {

        $disponibilidadesContra2 = $cursorEstablecHotels->toArray();
    } else if ($cursorEstablecHotels == null) {

        $disponibilidadesContra2 = $cursorEstablecResi->toArray();
    } else {
        //Array con todas las disponibilidades de hoteles y residencias
        $disponibilidadesContra2 = array_merge($cursorEstablecHotels->toArray(), $cursorEstablecResi->toArray());
    }


    $disponibilidades = constructArrayDispo($disponibilidadesContra2);

    return $disponibilidades;
}


/****************************************************************************************** 
    Función: Construye array de disponibilidades a partir de la resp de la consulta mongo
    Parámetros: Cursor Mongo
 ****************************************************************************************** */
function constructArrayDispo($cursor)
{

    $cursorCount = 0;
    $disponibilidades = [];
    $alojamiento = [];

    foreach ($cursor as $key => $id) {

        $start_date = convertMongoTimestampTodatePhp($id->start_date);
        $end_date = convertMongoTimestampTodatePhp($id->end_date);

        $disponibilidades[$cursorCount] = $row;

        $cursorCount++;
    }

    return $disponibilidades;
}

/****************************************************************************************** 
    Ordena un array multidimencional pasandole la key o index por el cual se quiere ordenar
 ****************************************************************************************** */
function sortMuldidimentionalArrayByKey($array, $key)
{

    foreach ($array as $k => $v) {
        $b[] = strtolower($v[$key]);
    }

    asort($b);

    foreach ($b as $k => $v) {
        $c[] = $array[$k];
    }

    return $c;
}





/****************************************************************************************** 
    Convierte el tipo de dato (timestamp) que retorna mongodb bson a un date en php
 ****************************************************************************************** */
function convertMongoTimestampTodatePhp($timestampToConvert)
{

    $datetime = $timestampToConvert->toDateTime();
    $time = $datetime->format(DATE_RSS);
    $dateInUTC = $time;
    $time = strtotime($dateInUTC . ' UTC');
    $dateInLocal = date("Y-m-d", $time);
    return $dateInLocal;
}



/**********************************************
 * Selecciona el nombre completo segun 
  codigo de categoria
 ***********************************************/
function getNamebyCategcode($categorieCode, $codeCRM, $temporada)
{


    $db    = JFactory::getDbo();
    $query = $db->getQuery(true);

    //Si tengo un tipo de establecimiento definido, busco los establecimientos de esa region q pertenecen a este tipo de alojamiento
    $query =  ' SELECT tabName.ExtSousCategLabel 
                FROM `kdn_campings_offre` as tabName 
                WHERE tabName.codeCRM = ' . $codeCRM . ' AND tabName.CategorieCode = "C006" AND tabName.CodeSaison = ' . $temporada . ' AND tabName.OFfExtCodePresta = "' . $categorieCode . '" ';

    //return $query;

    $db->setQuery($query);
    $nomCat = $db->loadResult();

    return $nomCat;
}

/*---------------------------------------------------------------------------------------*/
/*
* Function to get all images from directory
*/
/*---------------------------------------------------------------------------------------*/

function getImagesFromDirectoryB()
{

    //echo 'get directory';

    /*$alias = $_POST['custom_alias'];

	$files = [];

    $ul = "";

    $custom_array = [];

   // echo 'imagenes de directorio';

	$images = preg_grep('/\.(jpg|jpeg|png|gif)(?:[\?\#].*)?$/i', $files);

	$directory = 'https://new.mmv.fr/images/etablissements/'.$alias.'/galerie/';

	if ($handle = opendir($directory)) {

		while (false !== ($entry = readdir($handle))) {
			$files[] = $entry;
		}

		$images = preg_grep('/\.jpg$/i', $files);

		

			foreach($images as $image)
			{
			   $custom_array[] = array('src' => 'https://new.mmv.fr/images/etablissements/'.$alias.'/galerie/'.$image.'');
			}
		

		closedir($handle);


    }	
    
    if( empty($custom_array) ){
        $custom_array[] = array('src' => 'https://new.mmv.fr/images/defaultmini.jpg');
    }

    echo json_encode($custom_array);
   die();
   */
    $alias = $_POST['custom_alias'];
    $host = ModCampingsHelper::getHost();

    $imgRut = '../../../images/etablissements/' . $alias . '/galerie';
    $imgPath = $host . 'images/etablissements/' . $alias . '/galerie';
    $images = [];

    if (file_exists($imgRut)) :

        $ficheros  = scandir($imgRut);

        foreach ($ficheros as $key => $value) {
            if (preg_match("/\.(png|gif|jpe?g|bmp)/", $value, $m)) {
                $images[] = $value;
                $custom_array[] = array('src' => $imgPath . '/' . $value, 'thumb' => $imgPath . '/' . $value);
            }
        }


    /*$imgsansextension = substr($value, 0, -4); //lien sans extension

        $extension = substr_replace($value,'',0,-4); //extension
        $thumb = 'images/etablissements/thumbs/clubs/'.$imgsansextension.'_thumb_380x225'.$extension;
        
        if (!file_exists('../../../'.$thumb)) :
            $img = $host.'images/etablissements/default.jpg';
            $thumb = $img;

        endif;
            $img = ($value=='..')? $host.'images/etablissements/default.jpg' : $imgPath.'/'.$value;
            //$img = $imgPath.'/'.$value;*/
    else :
        $img = $host . 'images/etablissements/default.jpg';
        $thumb = $img;
        $custom_array[] = array('src' => $thumb);

    endif;


    //$custom_array[] = array('src' => $thumb);

    echo json_encode($custom_array);
    die();
}


/*
    Trae el ordering de los tabs en MYSQL de un alojamiento
    Parámetros: 
            string: codeCRM
            string: baseProductCode (por ejemplo: PB )
    Devuelve: un número de tipo string con el valor del ordering        
*/
function getTabsOrdering($codeCRM, $baseproductcode)
{

    $db    = JFactory::getDbo();
    $query = $db->getQuery(true);

    $query = 'SELECT codeformule.ordering FROM `kdn_campings_formule_code` as codeformule WHERE codeformule.codeCRM = ' . $codeCRM . ' AND codeformule.formuleCode = "' . $baseproductcode . '" ';
    $db->setQuery($query);
    $ordering = $db->loadResult();

    return $ordering;
}

// Ordenamos el objeto de resultados por ordering de tabs
function cmpResultsTabs($a, $b)
{
    return $a->ordering - $b->ordering;
}
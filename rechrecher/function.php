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

include  '../includes.php';

//Variable que contiene el nombre de la funcion que se va a ejecutar
$c_function = $_POST['custom_function'];

//Siempre compruebo que exista o esté definida la variable y que no este vacía
if ((isset($c_function)) && (!empty($c_function))) {
    switch ($c_function) {
        case 'getAllHebergementAliasByID':
            getAllHebergementAliasByID();
            break;
        case 'getGeneralSeason':
            getGeneralSeason();
            break;
        case 'getSeasonByHeberg':
            getSeasonByHeberg();
            break;
        case 'getAllHebergements':
            getAllHebergements();
            break;
        case 'getAllHebergementsByType':
            getAllHebergementsByType();
            break;
        case 'getTypeHebergement':
            getTypeHebergement();
            break;
        case 'getSeasonByDate':
            getSeasonByDate();
            break;
        case 'getNameRegionPadre':
            getNameRegionPadre();
            break;
        case 'getAllTypeHebergement':
            getAllTypeHebergement();
            break;
        default:
            # code...
            break;
    }
}

/**********************************************
 * Selecciona todas las temporadas activas
 ***********************************************/
function getAllHebergementAliasByID()
{

    $id = $_POST['id'];

    $db    = JFactory::getDbo();
    $query = $db->getQuery(true);

    $query = 'SELECT `alias`  FROM `kdn_campings_camping` WHERE state = 1 AND id = ' . $id . ' ';

    $db->setQuery($query);
    $alias = $db->loadResult();

    echo json_encode($alias);
}


/**********************************************
 * Selecciona todas las temporadas activas
 ***********************************************/
function getGeneralSeason()
{

    $db    = JFactory::getDbo();
    $query = $db->getQuery(true);

    $query = 'SELECT `nom`, `codeSaison`, `dateFirstDispo`,`dateLastDispo`,`dateBegin`,`dateEnd`  FROM `kdn_campings_CodeSaison` WHERE state = 1 ';
    $db->setQuery($query);
    $seasons = $db->loadObjectList();

    echo json_encode($seasons);
}


/**********************************************
 * Selecciona todas las temporadas activas
 ***********************************************/
function getSeasonByHeberg()
{

    if (isset($_POST['codeCRM']))
        $codeCRM = $_POST['codeCRM'];


    $db    = JFactory::getDbo();
    $query = $db->getQuery(true);

    $query = 'SELECT `ouverture`, `fermeture`  FROM `kdn_campings_camping` WHERE state = 1 AND codeCRM = ' . $codeCRM . ' ';
    $db->setQuery($query);
    $seasons = $db->loadObjectList();

    echo json_encode($seasons);
}

/**********************************************
 * Selecciona todas los alojamientos según 
    temporada y criterio de búsqueda
 ***********************************************/
function getAllHebergements()
{

    $temporada = 1;
    $dateOverture = '';
    $dateFermeture = '';

    if (isset($_POST['search']))
        $search = $_POST['search'];

    if (isset($_POST['temporada']))
        $temporada = $_POST['temporada'];

    if (isset($_POST['dateOverture']))
        $dateOverture = $_POST['dateOverture'];

    if (isset($_POST['dateFermeture']))
        $dateFermeture = $_POST['dateFermeture'];



    $db    = JFactory::getDbo();
    $query = $db->getQuery(true);

    if ($search != '') {

        // if( ($dateOverture != '') || ($dateFermeture != '') ){

        //     //Hago la query de todos los alojamientos segun su fecha de inicio y cierre
        //     $query = "  SELECT camping.nom, camping.codeCRM, camping.id, camping.icon as icon, camping.category as category, camping.ExtSkiDomNom as domnom, camping.ItmExtStationCommune as commune, ville.nom as ville, ville.logo as villeimg, camping.gamma as gamma 
        //     FROM `kdn_campings_camping` as camping 
        //     LEFT JOIN `kdn_campings_localidad` as ville ON ville.id = camping.ville   
        //     WHERE camping.state = 1 AND camping.nom like'%".$search."%' AND camping.saison = ".$temporada." AND 
        //     ( DATE(camping.ouverture) >= ".$dateOverture." AND DATE(camping.fermeture) <= ".$dateFermeture." ) ";

        // }else{
        //Hago la query de todos los alojamientos sin tener en cuenta fechas
        $query = "  SELECT camping.nom, camping.codeCRM, camping.id, camping.icon as icon, camping.category as category, camping.ItmExtGamme as ItmExtGamme, camping.ExtSkiDomNom as domnom, camping.ItmExtStationCommune as commune, ville.nom as ville, ville.logo as villeimg, camping.gamma as gamma , camping.regionpadre as regionpadre , camping.ItmExtTypeDestination as ItmExtTypeDestination 
            FROM `kdn_campings_camping` as camping 
            LEFT JOIN `kdn_campings_localidad` as ville ON ville.id = camping.ville   
            WHERE camping.state = 1 AND camping.nom like'%" . $search . "%' AND camping.saison = " . $temporada . "";
        // }
    } else {

        // if( ($dateOverture != '') || ($dateFermeture != '') ){

        //     //Hago la query de todos los alojamientos segun su fecha de inicio y cierre
        //     $query = " SELECT camping.nom, camping.codeCRM, camping.id, camping.icon as icon, camping.category as category, ville.nom as ville, camping.ExtSkiDomNom as domnom, camping.ItmExtStationCommune as commune, ville.logo as villeimg, camping.gamma as gamma 
        //     FROM `kdn_campings_camping` as camping 
        //     LEFT JOIN `kdn_campings_localidad` as ville ON ville.id = camping.ville  
        //     WHERE camping.state = 1 AND camping.saison = ".$temporada." AND 
        //     ( DATE(camping.ouverture) >= ".$dateOverture." AND DATE(camping.fermeture) <= ".$dateFermeture." )";

        // }else{
        //Hago la query de todos los alojamientos sin tener en cuenta fechas
        $query = " SELECT camping.nom, camping.codeCRM, camping.id, camping.icon as icon, camping.category as category, camping.ItmExtGamme as ItmExtGamme, camping.ExtSkiDomNom as domnom, camping.ItmExtStationCommune as commune, ville.nom as ville, ville.logo as villeimg, camping.gamma as gamma , camping.regionpadre as regionpadre , camping.ItmExtTypeDestination as ItmExtTypeDestination 
            FROM `kdn_campings_camping` as camping 
            LEFT JOIN `kdn_campings_localidad` as ville ON ville.id = camping.ville  
            WHERE camping.state = 1 AND camping.saison = " . $temporada . "";
        // }

    }


    $db->setQuery($query);
    $hebergements = $db->loadObjectList();

    $response = array();

    foreach ($hebergements as $hebergement) {
        $response[] = array(
            "label" => $hebergement->nom,
            "value" => $hebergement->id,
            "icon" => $hebergement->icon,
            "villeNom" => $hebergement->domnom,
            "villeLogo" => $hebergement->villeimg,
            "category" => $hebergement->category,
            "gamma" => $hebergement->gamma,
            'commune' => $hebergement->commune,
            'regionpadre' => $hebergement->regionpadre,
            'ItmExtTypeDestination' => $hebergement->ItmExtTypeDestination,
            'codeCRM' => $hebergement->codeCRM
        );
    }

    echo json_encode($response);
}

/**********************************************
 * Selecciona todas los alojamientos segun
     tipo de establecimiento
 ***********************************************/
function getAllHebergementsByType()
{

    if (isset($_POST['typeEstablishmentID']))
        $typeEstablishmentID = $_POST['typeEstablishmentID'];

    if (isset($_POST['temporada']))
        $temporada = $_POST['temporada'];

    $db    = JFactory::getDbo();
    $query = $db->getQuery(true);


    //$query = "SELECT `nom`, `id` FROM `kdn_campings_camping` WHERE state = 1 AND saison = ".$temporada." AND gamma = ".$typeEstablishmentID."";

    $query = "  SELECT camping.nom, camping.id, camping.icon as icon, camping.category as category, camping.ItmExtGamme as ItmExtGamme, camping.icon, camping.ExtSkiDomNom as domnom, camping.ItmExtStationCommune as commune, ville.nom as ville, ville.logo as villeimg , camping.regionpadre as regionpadre , camping.ItmExtTypeDestination as ItmExtTypeDestination 
    FROM `kdn_campings_camping` as camping 
    LEFT JOIN `kdn_campings_localidad` as ville ON ville.id = camping.ville   
    WHERE camping.state = 1 AND camping.gamma = " . $typeEstablishmentID . " AND camping.saison = " . $temporada . "";

    // echo $query;

    $db->setQuery($query);
    $hebergements = $db->loadObjectList();

    $response = array();

    foreach ($hebergements as $hebergement) {
        $response[] = array("label" => $hebergement->nom, "value" => $hebergement->id, 'category' => $hebergement->category, "villeNom" => $hebergement->domnom, "icon" => $hebergement->icon, 'commune' => $hebergement->commune, 'regionpadre' => $hebergement->regionpadre);
    }

    echo json_encode($response);
}


/**********************************************
 * Selecciona todas los tipos de alojamientos 
  según disponibilidades.
Parámetros: Array de fechas para buscar dispo  
 ***********************************************/
function getTypeHebergement()
{

    if (isset($_POST['datesWithDispo']))
        $datesWithDispo = $_POST['datesWithDispo'];

    $db    = JFactory::getDbo();
    $query = $db->getQuery(true);

    $datesToCompare = 'AND';

    for ($i = 0; $i < count($datesWithDispo); $i++) {

        $lenght = count($datesWithDispo) - 1;

        if ($i == $lenght) {
            $datesToCompare .= ' ( DATE(dateFirstDispo) <= "' . $datesWithDispo[$i]['dateFirstDispo'] . '" AND DATE(dateLastDispo) >= "' . $datesWithDispo[$i]['dateLastDispo'] . '" ) ';
        } else {
            $datesToCompare .= ' ( DATE(dateFirstDispo) <= "' . $datesWithDispo[$i]['dateFirstDispo'] . '" AND DATE(dateLastDispo) >= "' . $datesWithDispo[$i]['dateLastDispo'] . '" ) OR ';
        }
    }

    $query = 'SELECT `id`,`nom`,`dateFirstDispo`,`dateLastDispo`  FROM `kdn_campings_gamma` WHERE state = 1 ' . $datesToCompare;

    $db->setQuery($query);
    $seasons = $db->loadObjectList();

    echo json_encode($seasons);
}


/**********************************************
 *   Selecciona todas los alojamientos 
    según su tipo.
    Parámetros: id del tipo de alojamiento  
 ***********************************************/
function getHebergementsByType()
{

    if (isset($_POST['idTypeHebergement']))
        $idTypeHebergement = $_POST['idTypeHebergement'];

    $db    = JFactory::getDbo();
    $query = $db->getQuery(true);

    $query = 'SELECT `id`,`nom`,`dateFirstDispo`,`dateLastDispo`  FROM `kdn_campings_camping` WHERE state = 1 AND gamma =' . $idTypeHebergement;

    $db->setQuery($query);
    $hebergementsByType = $db->loadObjectList();

    echo json_encode($hebergementsByType);
}

/**********************************************
 * Selecciona la temporada segun fechas 
 ***********************************************/
function getSeasonByDate()
{

    if (isset($_POST['begin']))
        $begin = $_POST['begin'];

    if (isset($_POST['end']))
        $end = $_POST['end'];

    $db    = JFactory::getDbo();
    $query = $db->getQuery(true);

    $query = 'SELECT `codeSaison`  FROM `kdn_campings_CodeSaison` WHERE state = 1 AND ( DATE(dateFirstDispo) <= "' . $begin . '" AND DATE(dateLastDispo) >= "' . $end . '" )';

    //echo $query;

    $db->setQuery($query);
    $season = $db->loadResult();

    echo json_encode($season);
}

/**********************************************
 * Selecciona todas las regiones padre
 ***********************************************/
function getNameRegionPadre()
{

    $db    = JFactory::getDbo();
    $query = $db->getQuery(true);

    $query = 'SELECT `id`,`nom`,`alias`, `icon`  FROM `kdn_campings_regionpadre` WHERE state = 1 ';

    $db->setQuery($query);
    $season = $db->loadObjectList();

    echo json_encode($season);
}



/**********************************************
 * Selecciona todas los tipos de alojamientos.
 Ej: hotel, residencia...
 ***********************************************/
function getAllTypeHebergement()
{

    $db    = JFactory::getDbo();
    $query = $db->getQuery(true);

    $query = 'SELECT `id`,`nom`  FROM `kdn_campings_gamma` WHERE state = 1 ';

    $db->setQuery($query);
    $seasons = $db->loadObjectList();

    echo json_encode($seasons);
}


/**********************************************
 * Selecciona todas los  alojamientos para menu votre sejour.
 Ej: hotel, residencia...
 ***********************************************/


function getAllEtabsSejours()
{
    if (isset($_COOKIE['saison'])) :
        $temporada = $_COOKIE['saison'];
    else :
        $temporada = 1;
    endif;
    $db    = JFactory::getDbo();
    $query = $db->getQuery(true);

    $query = 'SELECT `id`,`nom` ,`alias` ,`preparezvotresejour`  FROM `kdn_campings_camping` WHERE state = 1 AND saison = ' . $temporada;

    $db->setQuery($query);
    $seasons = $db->loadObjectList();

    return $seasons;
}
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

$mobile = (isset($_COOKIE['mobile'])) ? $_COOKIE['mobile'] : 0;
$lang = ModCampingsHelper::getlang()
$lienlang = ModCampingsHelper::getLienLang($lang);
$hostimage = ModCampingsHelper::getHostImage();
$page = ModCampingsHelper::getpage();
$iteminfo = ModCampingsHelper::getCampingCode($page['itemid']);
$saison = $iteminfo->saison;
$codeCRM = $iteminfo->codeCRM;


$format = JText::_('DATE_FORMAT_LC3');
$item = ModCampingsHelper::getEtablessimentCRM($saison, $codeCRM);
$ouverture = ModCampingsHelper::getInformationtablessimentConcret($saison, $codeCRM, 'C001', 'Date_O');
$fermeture = ModCampingsHelper::getInformationtablessimentConcret($saison, $codeCRM, 'C001', 'Date_F');


$DescrWebBienvenue = ModCampingsHelper::getInformationtablessimentConcret($saison, $codeCRM, 'C005', 'DescrWebBienvenue');
$descriptions = ModCampingsHelper::getInformationtablessimentEtab($saison, $codeCRM, 'C005', 'DescrWebEtab');

$webbienvenues = ModCampingsHelper::getInformationtablessiment($saison, $codeCRM, 'C002', 'Descr1');
$atouts = ModCampingsHelper::getInformationtablessiment($saison, $codeCRM, 'C005', 'Atouts');
$babys = ModCampingsHelper::getInformationtablessimentConcret($saison, $codeCRM, 'C008', 'Baby');
$minis = ModCampingsHelper::getInformationtablessimentConcret($saison, $codeCRM, 'C008', 'Mini');
$teens = ModCampingsHelper::getInformationtablessimentConcret($saison, $codeCRM, 'C008', 'Teens');
$juniors = ModCampingsHelper::getInformationtablessimentConcret($saison, $codeCRM, 'C008', 'Junior');
$ados = ModCampingsHelper::getInformationtablessimentConcret($saison, $codeCRM, 'C008', 'Club_A');
$Squats = ModCampingsHelper::getInformationtablessimentConcret($saison, $codeCRM, 'C008', 'Squat');
$ASKI = ModCampingsHelper::getInformationtablessimentConcret($saison, $codeCRM, 'C008', 'ASKI');
$tags = ModCampingsHelper::getInformationtablessimentConcret($saison, $codeCRM, 'C005', 'Tag');


$formules = ModCampingsHelper::getInformationformules($saison, $codeCRM, 'C006');
$servicesAs = ModCampingsHelper::getServicesinclus($saison, $codeCRM, 'C014');
$servicesSe = ModCampingsHelper::getServicesinclus($saison, $codeCRM, 'C015');
$servicesHe = ModCampingsHelper::getServicesinclus($saison, $codeCRM, 'C016');
$servicesDi = ModCampingsHelper::getServicesinclus($saison, $codeCRM, 'C017');
$dansappartements = ModCampingsHelper::getInformationtablessiment($saison, $codeCRM, 'C003', 'DescrA');
$appartementsinfo = ModCampingsHelper::getInformationtablessiment($saison, $codeCRM, 'C003', 'DescrC');


$appartements = ModCampingsHelper::getInformationAlojamiento($codeCRM, 'C003', 'Typo');

if ($item->gamma == 2) :
    $votreclubenfantscolor =  'bluebackground';
else :
    $votreclubenfantscolor =  'greenbackground';
endif;


//echo $codeCRM;
echo '<div id="votreclub';
if ($item->gamma == 2) :
    echo 'hotel';
else :
    echo 'residence';
endif;
echo '">';
// OFRES TOP
echo '<div class="votrecluboffretop text-center">';
/*foreach($item->offres as $offre):
			if( $offre->CategorieCode =='C005' AND ($offre->position == 4 OR $offre->position == 5 OR $offre->position == 6)):
			echo '<span class="text-uppercase bluedtext blueexclbackground text-center p-2 m-2">'.$offre->ExtLibelle.'</span>';
			endif;
		endforeach;*/
foreach ($tags as $tag) :
    $nom = ($lang == 'en' and $tag->OFfExtUKLibelle != '') ? $tag->OFfExtUKLibelle : $tag->nom;
    echo '<span class="text-uppercase text-white bluedbackground text-center p-1 m-1">' . $nom . '</span>';

endforeach;

echo '</div>';
echo '<h2 class="text-center">';
foreach ($DescrWebBienvenue  as $Bienvenue) :

    $nom = ($lang == 'en' and $Bienvenue->OFfExtUKLibelle != '') ? $Bienvenue->OFfExtUKLibelle : $Bienvenue->nom;

    if ($nom != '') :
        echo $nom;
    endif;

endforeach;
echo '</h2>';


if ($mobile == 1) :

    if ($item->urlsaison != '') : ?>
        <div class="col-5 colEteHiver">
            <a class="temporadas  <?php if ($saison == 1) : echo "active";
                                    endif; ?> " href="<?php if ($saison == 1) :
                                                            echo "#";
                                                        else :
                                                            echo $host . $lang . '/' . $item->urlsaison;
                                                        endif;
                                                        ?> ">Hiver</a>
            <a class="temporadas <?php if ($saison == 2) : echo "active";
                                    endif; ?> " href="<?php if ($saison == 1) :
                                                            echo $host . $lang . '/' . $item->urlsaison;
                                                        else :
                                                            echo "#";
                                                        endif;
                                                        ?> ">Été</a>
        </div>
<?php endif;

    // OVERTURE FERMATURE MOBILE
    $nomOverture = '';
    $nomFermature = '';

    if (!empty($ouverture)) :
        foreach ($ouverture as $ouvert) :
            $nomOverture = ($lang == 'en' and $ouvert->OFfExtUKLibelle != '') ? $ouvert->OFfExtUKLibelle : $ouvert->nom;

        endforeach;
    endif;

    if (!empty($fermeture)) :
        foreach ($fermeture as $fermer) :
            $nomFermature = ($lang == 'en' and $fermer->OFfExtUKLibelle != '') ? $fermer->OFfExtUKLibelle : $fermer->nom;

        endforeach;
    endif;

    echo '<div class="col saisondates">';
    echo '<p class="align-middle">' . $nomOverture . '</br>' . $nomFermature . '</p>';
    echo '</div>';

endif;


// BLOQUE GAMMA
if (!empty($DescrWebBienvenue)) :

    echo '<div class="votreclubgammacont row">';

    $imgsansextension = substr($item->categoryeicone, 0, -4); //lien sans extension
    $extension = substr_replace($item->categoryeicone, '', 0, -4); //extension


    if ($item->gamma == 2) :
        $bluelogo = ($item->etoiles == 4) ? 'blueFRlogo' : 'bluelogo';
        echo '<div class="' . $bluelogo . ' rclub-logo col-3 col-md-2"><img class="d-block" src="' . $hostimg . '/' . $imgsansextension . 'b' . $extension . '" alt="' . $item->nom . '" title="' . $item->nom . '" />';
    elseif ($item->gamma == 1) :
        echo '<div class="greenlogo rclub-logo col-3 col-md-2"><img class="d-block" src="' . $hostimg . '/' . $imgsansextension . 'b' . $extension . '" alt="' . $item->nom . '" title="' . $item->nom . '" />';
    else :
        echo '<div class="greylogo rclub-logo col-3 col-md-2"><img class="d-block" src="' . $hostimg . '/' . $imgsansextension . 'b' . $extension . '" alt="' . $item->nom . '" title="' . $item->nom . '" />';
    endif;

    /* if($item->etoiles!=''):
					echo '<p class="text-center">';
					for ($etoiles = 0; $etoiles < $item->etoiles; $etoiles++){echo '<span class="';if($item->gamma==2): echo 'bluetext'; else: echo 'greentext'; endif; echo '">*</span>'; }
					echo '</p>';
			endif; */
    echo '</div>';


    echo '<div class="col-12 col-md-10 votreclubgammalibere">';
    foreach ($DescrWebBienvenue as $webbienvenue) :

        $nom = ($lang == 'en' and $webbienvenue->OFfExtUKLibelle != '') ? $webbienvenue->OFfExtUKLibelle : $webbienvenue->nom;
        $ExtDescriptifCourt = ($lang == 'en' and $webbienvenue->OFfExtUKDescriptifCourt != '') ? $webbienvenue->OFfExtUKDescriptifCourt :  $webbienvenue->ExtDescriptifCourt;
        $ExtDescriptifLong = ($lang == 'en' and $webbienvenue->OFfExtUKDescriptifLong != '') ? $webbienvenue->OFfExtUKDescriptifLong :  $webbienvenue->ExtDescriptifLong;


        echo '<p class="';
        if ($item->gamma == 2) :
            $bluetext = ($item->etoiles == 4) ? 'blueFRtext' : 'bluetext';
            echo $bluetext . '">';
        elseif ($item->gamma == 1) :
            echo 'greentext">';
        else :
            echo ' ">';
        endif;

        echo $ExtDescriptifCourt;
        if ($ExtDescriptifLong != '') :
            echo '<br/>' . $ExtDescriptifLong;
        endif;
        echo '</p>';
    endforeach;
    /*foreach($webbienvenues as $webbienvenue):
					echo '<p class="';
					if($item->gamma==2):
						echo 'bluetext">';
					else:
						echo 'greentext">';
					endif;
					 echo $webbienvenue->ExtDescriptifCourt;
					echo '</p>';
			endforeach;*/
    echo '</div>';
    echo '</div>';

endif;

?>
<div id="galerieOngletPrincipal"></div>

<!-- <script>printGalerieNew(<?php echo $codeCRM; ?> , <?php echo $saison; ?>, 'club', '#galerieOngletPrincipal');</script> -->
<script>
    printGalerie(<?php echo $codeCRM; ?>, 'images/etablissements/<?php echo $item->alias; ?>/onglet_principal',
        '#galerieOngletPrincipal', '', '<?php echo $item->alias; ?>', 'onglet_principal');
</script>


<?php

// BLOQUE DESCRIPTION ETABLESSIMENT

/*echo '<pre>';
print_r($descriptions);
echo '</pre>';*/

$i = 0;
foreach ($descriptions as $description) :

    $nom = ($lang == 'en' and $description->OFfExtUKLibelle != '') ? $description->OFfExtUKLibelle : $description->nom;
    $ExtDescriptifCourt = ($lang == 'en' and $description->OFfExtUKDescriptifCourt != '') ? $description->OFfExtUKDescriptifCourt : $description->ExtDescriptifCourt;
    $ExtDescriptifLong = ($lang == 'en' and $description->OFfExtUKDescriptifLong != '') ? $description->OFfExtUKDescriptifLong : $description->ExtDescriptifLong;

    if ($i == 0) : echo '<div class="description1FichaVotreclub">';
    endif;
    if ($i == 1) : echo '<div class="descriptionFichaVotreclub">';
    endif; // info a voir plus
    if ($nom != '') : echo '<h2>' . $nom . '</h2>';
    endif;
    if ($ExtDescriptifCourt != '') : echo '<p>' . $ExtDescriptifCourt . '</p>';
    endif;
    if ($ExtDescriptifLong != '') : echo '<p>' . $ExtDescriptifLong . '</p>';
    endif;
    if ($i == 0) : echo '</div>';
    endif;






    $i++;
endforeach;

if ($i > 1) : echo '</div>';

    /*if(!empty($descriptions)):
		echo '<div class="descriptionFichaVotreclub">';
			foreach ($descriptions as $description):echo '<p>'.$description->ExtDescriptifLong.'</p>';endforeach;
		echo '</div>';
	endif;*/

    echo '<div id="lire" onclick="AddActive(\'.descriptionFichaVotreclub\'); myFunction();" class="bluetext lire-suite"><i class="fas fa-chevron-right"></i> ' . JText::_('COM_CAMPINGS_LIRELASUIT') . '</div>';

endif; // cerramos div si hay mas texto a mostrar

// BLOQUE OFFRES

if (!empty($atouts)) :
    echo '<div class="container p-0">';
    echo '<div class="row row-eq-height">';
    $atoutsnum = 1;
    /*echo '<pre>';
print_r($atouts);
echo '</pre>';*/
    foreach ($atouts  as $atout) :

        $nom = ($lang == 'en' and $atout->OFfExtUKLibelle != '') ? $atout->OFfExtUKLibelle : $atout->nom;
        $ExtDescriptifCourt = ($lang == 'en' and $atout->OFfExtUKDescriptifCourt != '') ? $atout->OFfExtUKDescriptifCourt : $atout->ExtDescriptifCourt;
        $ExtDescriptifLong = ($lang == 'en' and $atout->OFfExtUKDescriptifLong != '') ? $atout->OFfExtUKDescriptifLong : $atout->ExtDescriptifLong;
        $OFfExtImage = ($lang == 'en' and $atout->OFfExtUkImage != '') ? $atout->OFfExtUkImage : $atout->OFfExtImage;

        echo '<div class="col-12 col-xl-3 col-lg-4 col-sm-6 atoutsitems"><div class="greyc2background">';
        if ($OFfExtImage != '') :
            echo '<img src="' . $hostimg . $OFfExtImage . '" alt="' . $nom . '" title="' . $nom . '" />';
        endif;
        if ($ExtDescriptifCourt != '' or $ExtDescriptifLong != '') :
            echo '<p class="c-pointer bluetext" data-toggle="modal" data-target="#atout' . $atoutsnum . '">' . $nom . '</p>';

            // MODAL POR MAS INFO
            echo '<div id="atout' . $atoutsnum . '" class="modal fade" role="dialog">';
            echo '<div class="modal-dialog">';

            echo '<div class="modal-content">';
            echo '<div class="modal-body">';
            echo '<h3 class="bluedtext">' . $nom . '</h3>';
            echo '<p>' . $ExtDescriptifCourt . '</p>';
            echo '<p>' . $ExtDescriptifLong . '</p>';

            echo '</div>';
            echo '<div class="modal-footer">';
            echo ' <button type="button" class="btn btn-default" data-dismiss="modal">' . JText::_('COM_CAMPINGS_FERMER') . '</button>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';


        else :
            echo '<p>' . $nom . '</p>';
        endif;

        echo '</div></div>';
        $atoutsnum++;
    endforeach;
    echo '</div></div>';
endif;

// ENFANTS I ADOS

if (!empty($babys)  or !empty($minis)  or !empty($juniors)  or !empty($teens) or !empty($ados)) :


    echo '<div class="votreclubenfants row m-0">';
    echo '<h2 class="bluedtext">' . JText::_('COM_CAMPINGS_ENFANTSADOS') . '</h2>';
    echo '<a href="#pills-tab" onclick="triggerClick(\'.tabclubenfants a\');" class="bluetext ancla"><i class="fas fa-chevron-right"></i> ' . JText::_('COM_CAMPINGS_TOUTSURENFANTS') . '</a>';
    echo '</div>';


endif;

echo '<div class="row col-12 votreclubenfants-info">';

if (!empty($babys)) : printbullet($babys, $votreclubenfantscolor, $lang, $hostimg);
endif;
if (!empty($minis)) : printbullet($minis, $votreclubenfantscolor, $lang, $hostimg);
endif;
if (!empty($juniors)) : printbullet($juniors, $votreclubenfantscolor, $lang, $hostimg);
endif;
if (!empty($teens)) : printbullet($teens, $votreclubenfantscolor, $lang, $hostimg);
endif;
if (!empty($ados)) : printbullet($ados, $votreclubenfantscolor, $lang, $hostimg);
endif;



function printbullet($elements, $votreclubenfantscolor, $lang, $hostimg)
{
    foreach ($elements as $element) :
        if ($element->OFfExtPastilleWeb != '') :

            $OFfExtPastilleWeb = ($lang == 'en' and $element->OFfExtUKLibelle != '') ? $element->OFfExtUKLibelle : $element->OFfExtPastilleWeb;

            echo '<div class="' . $votreclubenfantscolor . ' col-2 blanctext text-center p-3  rounded-circle">';

            //if($element->OFfExtUKsouscategorie!=''): echo $element->OFfExtUKsouscategorie.'<br/>'; endif;
            //if($element->ExtSousCategLabel!=''): echo $element->ExtSousCategLabel; endif;
            echo $OFfExtPastilleWeb;
            echo '</div>';
        endif;
    endforeach;
}

if (!empty($Squats) or !empty($ASKI)) : echo '<div class="col-12 col-lg-3 icon-enfants">';
endif;
if (!empty($Squats)) : printbulletIcon($Squats, 'squat-ado.png', $host, $lang, $hostimg);
endif;
if (!empty($ASKI)) : printbulletIcon($ASKI, 'cours-ski.png', $host, $lang, $hostimg);
endif;

function printbulletIcon($elements, $img, $host, $lang, $hostimg)
{
    foreach ($elements as $element) :

        $nom = ($lang == 'en' and $element->OFfExtUKLibelle != '') ? $element->OFfExtUKLibelle : $element->nom;
        //$ExtDescriptifCourt = ($lang == 'en' AND $element->OFfExtUKDescriptifCourt!='') ? $element->OFfExtUKDescriptifCourt : $element->ExtDescriptifCourt;
        //$ExtDescriptifLong = ($lang == 'en' AND $element->OFfExtUKDescriptifLong!='') ? $element->OFfExtUKDescriptifLong : $element->ExtDescriptifLong;
        $OFfExtImage = ($lang == 'en' and $element->OFfExtUkImage != '') ? $element->OFfExtUkImage : $element->OFfExtImage;



        if ($OFfExtImage != '') :
            echo '<img src="' . $hostimg . $OFfExtImage . '" alt="' . $nom . '" title="' . $nom . '" />';
        else :
            echo '<img src="' . $hostimg . '/images/icons/' . $img . '" alt="' . $nom . '" title="' . $nom . '" />';
        endif;
        echo '<p class="blueexdtext">';
        echo $nom;
        echo '</p>';
    endforeach;
}
if (!empty($Squats) or !empty($ASKI)) : echo '</div>';
endif;
echo '</div>';



// VOTRE FORMULE À LA CARTE


/*
echo '<pre>';
print_r($formules);
echo '</pre>';*/

if (!empty($formules)) :

    echo '<h2 class="bluedtext">' . JText::_('COM_CAMPINGS_VOTREFORMULEALACARTE') . '</h2>';

    echo '<div  id="navformule" class="">';
    echo '<ul  class="nav nav-pills navformule">';
    $active = 'active';
    foreach ($formules as $formule) :

        $nom = ($lang == 'en' and $formule->OFfExtUKLibelle != '') ? $formule->OFfExtUKLibelle : $formule->nom;

        echo '<li class="col p-0 iconefor"><a class="p-2 ' . $active . '" href="#' . $formule->OFfExtCodePresta . $formule->sousCatCode . $formule->Reference . '" data-toggle="tab">';
        if ($formule->icon != '') :
            echo '<div class="text-center d-block iconeformule"><img src="' . $hostimg . '/' . $formule->icon . '" alt="' . $nom . '" title="' . $nom . '" class="" /></div>';
        endif;
        echo $nom . '</a></li>';
        $active = '';
    endforeach;
    echo '</ul>';

    $active = 'active show';
    echo '<div class="tab-content tab-content-formule clearfix">';

    foreach ($formules as $formule) :

        $nom = ($lang == 'en' and $formule->OFfExtUKLibelle != '') ? $formule->OFfExtUKLibelle : $formule->nom;
        $ExtDescriptifCourt = ($lang == 'en' and $formule->OFfExtUKDescriptifCourt != '') ? $formule->OFfExtUKDescriptifCourt : $formule->ExtDescriptifCourt;
        $ExtDescriptifLong = ($lang == 'en' and $formule->OFfExtUKDescriptifLong != '') ? $formule->OFfExtUKDescriptifLong : $formule->ExtDescriptifLong;


        echo '<div class="tab-pane ' . $active . ' " id="' . $formule->OFfExtCodePresta . $formule->sousCatCode . $formule->Reference . '">';
        echo '<h3>' . $nom . '</h3>';
        echo '<div>' . $ExtDescriptifCourt . '</div>';
        //Contenido de lista de los tabs de ficha esta aqui (Mantis 075) (no esta bien necesita mirar donde viene este texto)
        $str = str_replace("-", "&bull;", $ExtDescriptifLong);
        echo '<div>' . nl2br($str) . '</div>';
        // echo '<div>'.nl2br($formule->ExtDescriptifLong).'</div>';
        //
        echo '</div>';
        $active = '';
    endforeach;

    echo '</div>';
    echo '</div>';
endif;

/* BUTTONS SERVICES */

echo '<button type="button" class="btn btnservice col-12 col-lg-5 greyc2background p-3 mr-1 mt-4 greytext" data-toggle="modal" data-target="#servicesinclus">
<p><img src="' . $hostimg . '/images/icons/services-not-inclus.png" alt="" title="" class="pr-2" />
' . JText::_('COM_CAMPINGS_VOTRE_CLUB_VOTRES_SERVICES_INCLUS') . '
</p></button>';
echo '<button type="button" class="btn btnservice col-12 col-lg-6 greyc2background p-3 mr-3 mt-4 greytext" data-toggle="modal" data-target="#servicesNotinclus"><p><img src="' . $hostimg . '/images/icons/services-inclus.png" alt="" title="" class="pr-2" />
' . JText::_('COM_CAMPINGS_VOTRE_CLUB_VOTRES_SERVICES_CARTE') . '
</p></button>';


echo '<div id="servicesinclus" class="modal fade" role="dialog">';
echo '<div class="modal-dialog">';

echo '<div class="modal-content">';
echo '<div class="modal-body">';
echo '<h3 class="bluedtext">' . JText::_('COM_CAMPINGS_SERVICES_INCLUS') . '</h3>';


if (!empty($servicesAs)) : htmlservicesInclus($servicesAs, JText::_(COM_CAMPINGS_VOTRE_CLUB_ASSURANCES), $lang);
endif;
if (!empty($servicesSe)) : htmlservicesInclus($servicesSe, JText::_(COM_CAMPINGS_VOTRE_CLUB_SERVICES), $lang);
endif;
if (!empty($servicesHe)) : htmlservicesInclus($servicesHe, JText::_(COM_CAMPINGS_VOTRE_CLUB_HEBERGEMENT), $lang);
endif;
if (!empty($servicesDi)) : htmlservicesInclus($servicesDi, JText::_(COM_CAMPINGS_VOTRE_CLUB_DIVERS), $lang);
endif;


function htmlservicesInclus($elements, $type, $lang)
{
    $title = false;
    foreach ($elements as $service) :
        if ($service->OFfExtInclus == 'Oui') :

            $nom = ($lang == 'en' and $service->OFfExtUKLibelle != '') ? $service->OFfExtUKLibelle : $service->nom;
            $ExtDescriptifCourt = ($lang == 'en' and $service->OFfExtUKDescriptifCourt != '') ? $service->OFfExtUKDescriptifCourt : $service->ExtDescriptifCourt;

            if ($title == false) : echo '<p>' . $type . ':</p>';
            endif;
            echo '<li>' . $nom . '</br>' . $ExtDescriptifCourt . '</li><br/>';
            $title = true;
        endif;
    endforeach;
}

echo '</div>';
echo '<div class="modal-footer">';
echo ' <button type="button" class="btn btn-default" data-dismiss="modal">' . JText::_('COM_CAMPINGS_FERMER') . '</button>';
echo '</div>';
echo '</div>';
echo '</div>';
echo '</div>';


echo '<div id="servicesNotinclus" class="modal fade" role="dialog">';
echo '<div class="modal-dialog">';

echo '<div class="modal-content">';
echo '<div class="modal-body">';
echo '<h3 class="bluedtext">' . JText::_('COM_CAMPINGS_SERVICES_PAYANT') . '</H3>';
if (!empty($servicesAs)) : htmlservicesPayant($servicesAs, JText::_(COM_CAMPINGS_VOTRE_CLUB_ASSURANCES), $lang);
endif;
if (!empty($servicesSe)) : htmlservicesPayant($servicesSe, JText::_(COM_CAMPINGS_VOTRE_CLUB_SERVICES), $lang);
endif;
if (!empty($servicesHe)) : htmlservicesPayant($servicesHe, JText::_(COM_CAMPINGS_VOTRE_CLUB_HEBERGEMENT), $lang);
endif;
if (!empty($servicesDi)) : htmlservicesPayant($servicesDi, JText::_(COM_CAMPINGS_VOTRE_CLUB_DIVERS), $lang);
endif;


function htmlservicesPayant($elements, $type, $lang)
{
    $title = false;
    foreach ($elements as $service) :
        if ($service->OFfExtInclus == 'Non') :

            $nom = ($lang == 'en' and $service->OFfExtUKLibelle != '') ? $service->OFfExtUKLibelle : $service->nom;
            $ExtDescriptifCourt = ($lang == 'en' and $service->OFfExtUKDescriptifCourt != '') ? $service->OFfExtUKDescriptifCourt : $service->ExtDescriptifCourt;
            $ExtTarif = ($lang == 'en' and $service->OFfExtUKTarif != '') ? $service->OFfExtUKTarif : $service->ExtTarif;

            if ($title == false) : echo '<p>' . $type . ':</p>';
            endif;
            echo '<li>' . $nom . '</br>' . $ExtDescriptifCourt;
            if ($ExtTarif != '' and $ExtTarif != 0) :
                echo '<br/><strong>' . $ExtTarif . '</strong>';
            endif;
            echo '</li><br/>';
            $title = true;
        endif;
    endforeach;
}

echo '</div>';
echo '<div class="modal-footer">';
echo ' <button type="button" class="btn btn-default" data-dismiss="modal">' . JText::_('COM_CAMPINGS_FERMER') . '</button>';
echo '</div>';
echo '</div>';
echo '</div>';
echo '</div>';


/* Votre appartement : à vous de choisir ! */


echo '<div class="" id="appartementsvotreclub">';

if (!empty($dansappartements) or !empty($appartements)) :

    // Mantis 091 (HOTEL = CHAMBRE & APPARTEMENT = APPARTEMENT)
    if ($item->gamma == 1) {
        echo '<h2 class="bluedtext">' . JText::_('COM_CAMPINGS_APPARTEMENT_CHOISIR') . '</h2>';
    }
    if ($item->gamma == 2) {
        echo '<h2 class="bluedtext">' . JText::_('COM_CAMPINGS_HOTEL_CHOISIR') . '</h2>';
    }


endif;

/*echo '<pre>';
	print_r($appartementsinfo);
	echo '</pre>';*/
echo '<p>';
foreach ($appartementsinfo as $dansappartement) {

    $nom = ($lang == 'en' and $dansappartement->OFfExtUKLibelle != '') ? $dansappartement->OFfExtUKLibelle : $dansappartement->nom;
    $ExtDescriptifCourt = ($lang == 'en' and $dansappartement->OFfExtUKDescriptifCourt != '') ? $dansappartement->OFfExtUKDescriptifCourt : $dansappartement->ExtDescriptifCourt;
    $ExtDescriptifLong = ($lang == 'en' and $dansappartement->OFfExtUKDescriptifLong != '') ? $dansappartement->OFfExtUKDescriptifLong : $dansappartement->ExtDescriptifLong;

    echo $nom . '<br/>';
    echo $ExtDescriptifCourt . '<br/>';
    echo $ExtDescriptifLong;
}
echo '</p>';
echo '<div class="row appartementsvotreclub m-0">';

$stringSaison = ($saison == 1) ? 'HIVER' : 'ETE';

/*echo '<pre>';
	print_r($appartements);
	echo '</pre>';*/
if (!empty($appartements) and $item->gamma == 1) : htmlapartemanetsresidence($appartements, $stringSaison, $host, $lang, $hostimg);
endif;
if (!empty($appartements) and $item->gamma == 3) : htmlapartemanetsresidence($appartements, $stringSaison, $host, $lang, $hostimg);
endif;
if (!empty($appartements) and $item->gamma == 2) : htmlapartemanetshotel($appartements, $stringSaison, $host, $lang, $hostimg);
endif;






function htmlapartemanetsresidence($appartements, $stringSaison, $host, $lang, $hostimg)
{

    $totalelemnt = count($appartements) / 2;
    $totalelemnt = round($totalelemnt);
    $con = 1;

    foreach ($appartements as $appartement) :
        $pos = strpos($appartement->ExtSaison, $stringSaison);
        if ($pos != false or $appartement->ExtSaison == 'TOUTES') :

            $capacite = str_replace("personnes", "", $appartement->OFfExtCapacite);


            echo '<div class="col-12 col-sm-12';

            //CALCULAMOS SI VA A LA COLUMNA DE LA DERECHA O A LA DE LA IZQUIERDA
            if ($con > $totalelemnt) :
                echo ' second-half ';
            endif;

            echo '">';

            echo '<div class="row mr-1">';

            echo '<div class="col-12 col-lg-12">';

            echo '<div class="appartement-type">';
            if ($appartement->OFfExtGamme != '') :
                switch ($appartement->OFfExtGamme) {
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
                echo '<span class="tipoappartement ' . $textcolor . ' ' . $backcolor . ' ">' . $appartement->OFfExtGamme . '</span>';
            endif;
            echo '<button style="background-color: transparent;" type="button" class="button-appartement btn" data-toggle="modal" data-target="#' . $appartement->Reference . '"><img style="
    margin-bottom: 20px;" src="' . $hostimg . '/images/icons/info.png" alt="" title="" class="" /></button>';
            echo '</div>';
            echo '<div class="row appartement-caract">';
            echo '<div class="col-4">';
            echo '<div class="row">';
            echo '<div class="col-4"><img src="' . $hostimg . '/images/icons/picto-pi-ces.png" alt="' . $appartement->OFfExtNbPiece . '" title="' . $appartement->OFfExtNbPiece . '" /></div>';
            echo '<div class="col-8 pl-2 pr-0"><p class="tipo-num">' . $appartement->OFfExtNbPiece . '</p><p class="tipo-text">
								' . JText::_(COM_CAMPINGS_VOTRE_CLUB_PIECES) . '
								</p></div>';
            echo '</div>';
            echo '</div>';
            echo '<div class="col-4">';
            echo '<div class="row">';
            echo '<div class="col-4"><img src="' . $hostimg . '/images/icons/picto-personnes-2.png" alt="' . $capacite . '" title="' . $capacite . '" /></div>';
            echo '<div class="col-8 pl-3 pr-0"><p class="tipo-num">' . $capacite . '</p><p class="tipo-text">
								' . JText::_(COM_CAMPINGS_VOTRE_CLUB_PERS) . '
								</p></div>';
            echo '</div>';
            echo '</div>';
            echo '<div class="col-4">';
            echo '<div class="row">';
            echo '<div class="col-4"><img src="' . $hostimg . '/images/icons/picto-surface-2.png" alt="' . $appartement->ExtSuperficie . '" title="' . $appartement->ExtSuperficie . '" /></div>';
            echo '<div class="col-8 pl-2 pr-0"><p class="tipo-num">' . $appartement->ExtSuperficie . '</p><p class="tipo-text">m²</p></div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '<div class="col-12 col-lg-12  p-0" style="text-align: end;">';

            echo '<button style="padding: 2px 5px 0px;font-size: 14px;" type="button" class=" btn yellowbackground" onclick="triggerClick(\'.tabTarifs a\');" ><span>TARIFS ET DISPO</span></button>';
            echo '</div>';
            echo '</div>';


            echo '</div>';


            // Modal alojamiento

            echo '<div id="' . $appartement->Reference . '" class="modal fade" role="dialog">';
            echo '<div class="modal-dialog">';

            echo '<div class="modal-content">';
            echo '<div class="modal-body">';

            $nom = ($lang == 'en' and $appartement->OFfExtUKLibelle != '') ? $appartement->OFfExtUKLibelle : $appartement->nom;
            $ExtDescriptifCourt = ($lang == 'en' and $appartement->OFfExtUKDescriptifCourt != '') ? $appartement->OFfExtUKDescriptifCourt : $appartement->ExtDescriptifCourt;
            $ExtDescriptifLong = ($lang == 'en' and $appartement->OFfExtUKDescriptifLong != '') ? $appartement->OFfExtUKDescriptifLong : $appartement->ExtDescriptifLong;


            echo '<h3 class="greentext">' . $nom . '</H3>';
            echo '<p >' . $ExtDescriptifCourt . '</p>';
            echo '<p >' . $ExtDescriptifLong . '</p>';

            echo '</div>';
            echo '<div class="modal-footer">';
            echo ' <button type="button" class="btn btn-default" data-dismiss="modal">' . JText::_('COM_CAMPINGS_FERMER') . '</button>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';

            // Fin Modal alojamiento
            $con++;
        endif;
    endforeach;
}

function htmlapartemanetshotel($appartements, $stringSaison, $host, $lang, $hostimg)
{

    $totalelemnt = count($appartements) / 2;
    $totalelemnt = round($totalelemnt);
    $con = 1;

    /*print_r($appartements);*/
    foreach ($appartements as $appartement) :
        $pos = strpos($appartement->ExtSaison, $stringSaison);
        //echo $pos;
        //echo $appartement->ExtSaison;
        if ($pos != false or $appartement->ExtSaison == 'TOUTES') :

            $capacite = str_replace("personnes", "", $appartement->OFfExtCapacite);


            echo '<div class="col-12 col-sm-12';
            //CALCULAMOS SI VA A LA COLUMNA DE LA DERECHA O A LA DE LA IZQUIERDA
            if ($con > $totalelemnt) :
                echo ' second-half ';
            endif;
            echo '">';
            echo '<div class="row mr-1">';

            echo '<div class="col-12 col-lg-10">';
            echo '<div class="appartement-type">';
            if ($appartement->OFfExtGamme != '') :
                echo '<span class="tipoappartement blanctext bluedbackground ">' . $appartement->OFfExtGamme . '</span>';
            endif;
            echo '</div>';
            echo '<div class="row appartement-caract">';
            echo '<div class="col-4">';
            echo '<div class="row">';
            echo '<div class="col-4"><img  src="' . $hostimg . '/images/icons/chambres.png" alt="' . $appartement->OFfExtNbPiece . '" title="' . $appartement->OFfExtNbPiece . '" /></div>';
            echo '<div class="col-8 pl-2 pr-0"><p class="tipo-num">';
            if ($appartement->OFfExtNbPiece == '') :
                echo '1';
            else :
                echo $appartement->OFfExtNbPiece;
            endif;

            echo '</p><p class="tipo-text">';

            if ($appartement->OFfExtNbPiece == '' or $appartement->OFfExtNbPiece == 1) :
                echo JText::_(COM_CAMPINGS_VOTRE_CLUB_CHAMBRE);
            else :
                echo JText::_(COM_CAMPINGS_VOTRE_CLUB_CHAMBRES);
            endif;

            echo '</p></div>';
            echo '</div>';
            echo '</div>';
            echo '<div class="col-4">';
            echo '<div class="row">';
            echo '<div class="col-4"><img src="' . $hostimg . '/images/icons/picto-personnes-2.png" alt="' . $capacite . '" title="' . $capacite . '" /></div>';
            echo '<div class="col-8 pl-3 pr-0"><p class="tipo-num">' . $capacite . '</p><p class="tipo-text">
							' . JText::_(COM_CAMPINGS_VOTRE_CLUB_PERS) . '
							</p></div>';
            echo '</div>';
            echo '</div>';
            echo '<div class="col-4">';
            echo '<div class="row">';
            echo '<div class="col-4"><img src="' . $hostimg . '/images/icons/picto-surface-2.png" alt="' . $appartement->ExtSuperficie . '" title="' . $appartement->ExtSuperficie . '" /></div>';
            echo '<div class="col-8 pl-2 pr-0"><p class="tipo-num">' . $appartement->ExtSuperficie . '</p><p class="tipo-text">m²</p></div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '<div class="col-12 col-lg-2 bluecbackground p-0">';
            echo '<button type="button" class="button-appartement btn" data-toggle="modal" data-target="#' . $appartement->Reference . '"><img src="' . $hostimg . '/images/icons/info.png" alt="" title="" class="" /></button>';
            echo '<button type="button" class="button-appartement btn" onclick="triggerClick(\'.tabTarifs a\');"><img src="' . $hostimg . '/images/icons/lupa.png" alt="" title="" class="" /></button>';
            echo '</div>';
            echo '</div>';

            echo '</div>';


            // Modal alojamiento

            echo '<div id="' . $appartement->Reference . '" class="modal fade" role="dialog">';
            echo '<div class="modal-dialog">';

            echo '<div class="modal-content">';
            echo '<div class="modal-body">';
            echo '<h3 class="greentext">' . $appartement->nom . '</H3>';
            echo '<p >' . $appartement->ExtDescriptifCourt . '</p>';
            echo '<p >' . $appartement->ExtDescriptifLong . '</p>';

            echo '</div>';
            echo '<div class="modal-footer">';
            echo ' <button type="button" class="btn btn-default" data-dismiss="modal">' . JText::_('COM_CAMPINGS_FERMER') . '</button>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';

            // Fin Modal alojamiento
            $con++;
        endif;
    endforeach;
}




echo '</div>';
echo '</div><p></p>';


echo '<img src="' . $hostimg . '/images/icons/picto-handicap.png" alt="" title="" class="" />&nbsp
' . JText::_('MOD_VOTRECLUB_MOBILITE_REDUITE') . '
<p></p>';

if (!empty($appartements)) :

    echo '<a href="#pills-tab" onclick="triggerClick(\'.tabTarifs a\');" class="bluetext ancla">' . JText::_('COM_CAMPINGS_DISPOS_TARIFS') . '</a>';

endif;



echo '</div>';



?>
<!-- Cambio de Lire la suite para Voir Moins en las fichas de estabelecimentos -->
<script>
    function myFunction() {
        var x = document.getElementById("lire");
        if (x.innerHTML == '<i class="fas fa-chevron-right"></i> <?php echo JText::_('COM_CAMPINGS_LIRELASUIT'); ?>') {
            x.innerHTML = '<i class="fas fa-chevron-right"></i> <?php echo JText::_('COM_CAMPINGS_VOIRMOINS'); ?>';
        } else {
            x.innerHTML = '<i class="fas fa-chevron-right"></i> <?php echo JText::_('COM_CAMPINGS_LIRELASUIT'); ?>';
        }
    };
</script>
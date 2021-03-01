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
$temporada = ModCampingsHelper::getTemporada();
$mobile = (isset($_COOKIE['mobile'])) ? $_COOKIE['mobile'] : 0;
$saison = (!isset($_COOKIE['saison'])) ? $_COOKIE['saison'] : $temporada;
$lang = ModCampingsHelper::getLang();
//$dispositivo = ModCampingsHelper::getMobile();
$page = ModCampingsHelper::getpageResults();
//$equipements = ModCampingsHelper::getEquipements('equipement');
//$services = ModCampingsHelper::getEquipements('service');
//$formules = ModCampingsHelper::getEquipements('formules');
//$clubenfants = ModCampingsHelper::getEquipements('clubenfants');

//$localisations = ModCampingsHelper::getEquipements('localisation');
echo $lang;

$localisations = ModCampingsHelper::getEquipementsWS('SITUATION', $lang);
$services = ModCampingsHelper::getEquipementsWS('SERVICES', $lang);
$servicescarte = ModCampingsHelper::getEquipementsWS('SERVCARTE', $lang);
$animloisirs = ModCampingsHelper::getEquipementsWS('ANIMLOISIR', $lang);
$typeetablesiments = ModCampingsHelper::getEquipementsWS('TH', $lang);
$products = ModCampingsHelper::getEquipementsWS('PRODUCTS', $lang);
$themes = ModCampingsHelper::getEquipementsWS('THEMES', $lang);
$stations = ModCampingsHelper::getEquipementsWS('STATIONS', $lang);
$destinations = ModCampingsHelper::getEquipementsWS('TYPE_DESTI', $lang);

$FWLOC = ModCampingsHelper::getEquipementsWS('FWLOC', $lang);
$FWFOR = ModCampingsHelper::getEquipementsWS('FWFOR', $lang);
$FWEQU = ModCampingsHelper::getEquipementsWS('FWEQU', $lang);
$DESTIMMV = ModCampingsHelper::getEquipementsWS('DESTIMMV', $lang);
$DESTIMMV2 = ModCampingsHelper::getEquipementsWS('DESTIMMV2', $lang);
$FWSER = ModCampingsHelper::getEquipementsWS('FWSER', $lang);
$FWLOG = ModCampingsHelper::getEquipementsWS('FWLOG', $lang);

/*echo '<pre>';
print_r($FWLOC);
echo '</pre>';*/

/*$temporada = ModCampingsHelper::getTemporada();*/
//echo $temporada;


?>

<div>


    <?php
    if ($mobile == 0) :
        echo '<div class="textfiltersmod d-flex contactezinfo align-items-center"><img class="float-left " src="images/icons/picto-expert.png" /><div class="consultezexpert">' . $params->get('descrfilters') . '</div></div>';
    endif;

    if ($mobile == 1) :
        echo '<div class="closfiltersmobile text-center bluedtext text-uppercase" ><strong>' . JText::_('MOD_CAMPINGS_AFFINER') . '</strong> X</div>';
    endif; ?>



</div>



<!-- ORDER BY -->
<!--<div class="classement serviceslist flex-grow-2 orderbyfilters">
	<div class="servicesCheck ratingcontent"> 
	<select name="nameorderby" class="select-orderby">
		<option value="3">TRIER PAR</option>
		<option value="2">Prix croissant</option>
		<option value="1">Prix décroissant</option>
	</select>
	<input type="hidden"  name="nameorderbyID" class="nameorderbyID blueBox" value="3__min price">
	</div>
</div>-->


<!-- FORMULES -->

<!-- FORMULES -->

<div class="classement serviceslist flex-grow-2">
    <div class="servicesCheck ratingcontent">

        <?php


        echo '<p class="titlefilter"><strong>' . JText::_('MOD_CAMPINGS_FORMULES') . '</strong><i class="fas fa-chevron-up bluetext"></i></p>';
        echo '<div class="moesrvicesclose productcont">';

        echo '<div>';
        $max = 0;
        foreach ($FWFOR as $formule) :

            if ($product->saison == 0 or $product->saison == $saison) :
                echo '<div class=" pl-3 ';
                if ($max > 5) : echo 'itemclose ';
                endif;
                echo '">';
                echo '<input name="formule" id="formule' . $formule->code . '" class="formuleSel" type="checkbox"  data-id="' . $formule->code . '" ';

                echo ' value="' . $formule->code . '"/><span class="checkmark"></span>';
                echo '<label for="formule' . $formule->code . '" class="container-checkbox pl-2">  ' . $formule->nom . '</label>';
                echo '</div>';

                $max++;
            endif;

        endforeach;




        echo '</div>';



        ?>

    </div>
</div>

</div>

<!-- formule -->


<div class="classement serviceslist flex-grow-2">
    <div class="servicesCheck ratingcontent">

        <?php


        echo '<p class="titlefilter"><strong>' . JText::_('MOD_CAMPINGS_LOCALISATION') . '</strong><i class="fas fa-chevron-up bluetext"></i></p>';
        echo '<div class="moesrvicesclose productcont">';

        echo '<div>';
        $max = 0;
        foreach ($FWLOC as $localisation) :

            if ($product->saison == 0 or $product->saison == $saison) :
                echo '<div class=" pl-3 ';
                if ($max > 5) : echo 'itemclose ';
                endif;
                echo '">';
                echo '<input name="localisation" id="localisation' . $localisation->code . '" class="localisationSel" type="checkbox"  data-id="' . $localisation->code . '" ';

                echo ' value="' . $localisation->code . '"/><span class="checkmark"></span>';
                echo '<label for="localisation' . $localisation->code . '" class="container-checkbox pl-2">  ' . $localisation->nom . '</label>';
                echo '</div>';

                $max++;
            endif;

        endforeach;




        echo '</div>';



        ?>

    </div>
</div>

</div>

<!-- SERVICES -->

<?php


?>
<div class="classement serviceslist flex-grow-2">
    <div class="servicesCheck ratingcontent">

        <?php
        echo '<p class="titlefilter"><strong>' . JText::_('MOD_CAMPINGS_SERVICES') . '</strong> <i class="fas fa-chevron-up bluetext"></i></p>';
        echo '<div class="moesrvicesclose servicecont">';


        echo '<div>';
        $max = 0;
        /*foreach($services as $service):
								if($services->saison==0 OR $services->saison==$saison):

								echo '<div class=" pl-3 ';
								if($max>5): echo 'itemclose '; endif;
								echo '">';
								echo '<input name="service" id="service' . $service->code . '" class="serviceSel" type="checkbox"  data-id="'.$service->code.'" ';

								echo ' value="' . $service->code . '"/><span class="checkmark"></span>';
								echo '<label for="service' . $service->code . '" class="container-checkbox pl-2">  ' .$service->nom .'</label>';
								echo '</div>';

						$max++;
						endif;
						endforeach;
						foreach($servicescarte as $service):
								if($services->saison==0 OR $services->saison==$saison):

								echo '<div class=" pl-3 ';
								if($max>5): echo 'itemclose '; endif;
								echo '">';
								echo '<input name="service" id="service' . $service->code . '" class="serviceSel" type="checkbox"  data-id="'.$service->code.'" ';

								echo ' value="' . $service->code . '"/><span class="checkmark"></span>';
								echo '<label for="service' . $service->code . '" class="container-checkbox pl-2">  ' .$service->nom .'</label>';
								echo '</div>';
						$max++;
						endif;
						endforeach;*/
        foreach ($FWSER as $service) :
            if ($services->saison == 0 or $services->saison == $saison) :

                echo '<div class=" pl-3 ';
                if ($max > 5) : echo 'itemclose ';
                endif;
                echo '">';
                echo '<input name="service" id="service' . $service->code . '" class="serviceSel" type="checkbox"  data-id="' . $service->code . '" ';

                echo ' value="' . $service->code . '"/><span class="checkmark"></span>';
                echo '<label for="service' . $service->code . '" class="container-checkbox pl-2">  ' . $service->nom . '</label>';
                echo '</div>';
                $max++;
            endif;
        endforeach;

        if ($max > 5) : ?><p class="bluetext" onclick="filtervisible('.servicecont');">
            <?php echo '<span></span>' . JText::_('MOD_CAMPINGS_FILTRES__PLUS_CRITERES'); ?>
        </p> <?php endif;


                echo '</div>';



                    ?>

    </div>
</div>

</div>
<!-- EQUIPEMENTS -->

<?php


?>
<div class="classement serviceslist flex-grow-2">
    <div class="servicesCheck ratingcontent">

        <?php
        echo '<p class="titlefilter"><strong>' . JText::_('MOD_CAMPINGS_EQUIPEMENTS_ETABS') . '</strong> <i class="fas fa-chevron-up bluetext"></i></p>';
        echo '<div class="moesrvicesclose equipementcont">';


        echo '<div>';
        $max = 0;
        foreach ($FWEQU as $equipement) :
            if ($equipements->saison == 0 or $equipements->saison == $saison) :

                echo '<div class=" pl-3 ';
                if ($max > 5) : echo 'itemclose ';
                endif;
                echo '">';
                echo '<input name="equipement" id="equipement' . $equipement->code . '" class="equipementSel" type="checkbox"  data-id="' . $equipement->code . '" ';

                echo ' value="' . $equipement->code . '"/><span class="checkmark"></span>';
                echo '<label for="equipement' . $equipement->code . '" class="container-checkbox pl-2">  ' . $equipement->nom . '</label>';
                echo '</div>';

                $max++;
            endif;
        endforeach;
        /*foreach($equipementscarte as $equipement):
									if($equipements->saison==0 OR $equipements->saison==$saison):

									echo '<div class=" pl-3 ';
									if($max>5): echo 'itemclose '; endif;
									echo '">';
									echo '<input name="equipement" id="equipement' . $equipement->code . '" class="equipementSel" type="checkbox"  data-id="'.$equipement->code.'" ';

									echo ' value="' . $equipement->code . '"/><span class="checkmark"></span>';
									echo '<label for="equipement' . $equipement->code . '" class="container-checkbox pl-2">  ' .$equipement->nom .'</label>';
									echo '</div>';
							$max++;
							endif;
							endforeach;*/

        if ($max > 5) : ?><p class="bluetext" onclick="filtervisible('.equipementcont');">
            <?php echo '<span></span>' . JText::_('MOD_CAMPINGS_FILTRES__PLUS_CRITERES'); ?>
        </p> <?php endif;


                echo '</div>';



                    ?>

    </div>
</div>

</div>

<!-- PRODUCTS -->

<!-- <div class="classement serviceslist flex-grow-2">
		<div class="servicesCheck ratingcontent">
	
			<?php
            echo '<p class="titlefilter"><strong>' . JText::_('MOD_CAMPINGS_TYPEETAB') . '</strong>  <i class="fas fa-chevron-up bluetext"></i></p>';
            echo '<div class="moesrvicesclose productcont">';


            echo '<div>';
            $max = 0;
            foreach ($products as $product) :
                if ($product->saison == 0 or $product->saison == $saison) :
                    echo '<div class=" pl-3 ';
                    if ($max > 5) : echo 'itemclose ';
                    endif;
                    echo '">';
                    echo '<input name="product" id="product' . $product->code . '" class="productSel" type="checkbox"  data-id="' . $product->code . '" ';

                    echo ' value="' . $product->code . '"/><span class="checkmark"></span>';
                    echo '<label for="product' . $product->code . '" class="container-checkbox pl-2">  ' . $product->nom . '</label>';
                    echo '</div>';

                    $max++;
                endif;

            endforeach;
            if ($max > 5) : echo '<p class="bluetext ">
							' . JText::_('MOD_CAMPINGS_FILTRES__PLUS_CRITERES') . '
							</p> ';
            endif;




            echo '</div>';



            ?>
	
		</div>
	</div>
	
	</div> -->
<!-- ANIMATIONS ET LOISIRS -->

<!-- <div class="classement serviceslist flex-grow-2">
		<div class="servicesCheck ratingcontent">
	
			<?php
            echo '<p class="titlefilter"><strong>' . JText::_('MOD_CAMPINGS_ANIMATIONETLOISIRS') . '</strong>  <i class="fas fa-chevron-up bluetext"></i></p>';
            echo '<div class="moesrvicesclose animloisircont">';
            echo '<div>';

            foreach ($animloisirs as $animloisir) :
                if ($animloisir->saison == 0 or $animloisir->saison == $saison) :
                    echo '<div class=" pl-3">';
                    echo '<input name="animloisir" id="animloisir' . $animloisir->code . '" class="animloisirSel" type="checkbox"  data-id="' . $animloisir->code . '" ';

                    echo ' value="' . $animloisir->code . '"/><span class="checkmark"></span>';
                    echo '<label for="animloisir' . $animloisir->code . '" class="container-checkbox pl-2">  ' . $animloisir->nom . '</label>';
                    echo '</div>';
                endif;
            endforeach;




            echo '</div>';



            ?>
	
		</div>
	</div>
	
	</div> -->

<!-- TYPE ETABLESIMENT -->

<!-- <div class="classement serviceslist flex-grow-2">
		<div class="servicesCheck ratingcontent">
	
			<?php
            echo '<p class="titlefilter"><strong>' . JText::_('MOD_CAMPINGS_TYPEETABLESIMENT') . '</strong> <i class="fas fa-chevron-up bluetext"></i></p>';
            echo '<div class="moesrvicesclose typeetablesimentcont">';


            echo '<div>';
            $max = 0;
            foreach ($typeetablesiments as $typeetablesiment) :
                if ($typeetablesiment->saison == 0 or $typeetablesiment->saison == $saison) :

                    echo '<div class=" pl-3 ';
                    if ($max > 5) : echo 'itemclose ';
                    endif;
                    echo '">';
                    echo '<input name="typeetablesiment" id="typeetablesiment' . $typeetablesiment->code . '" class="typeetablesimentSel" type="checkbox"  data-id="' . $typeetablesiment->code . '" ';

                    echo ' value="' . $typeetablesiment->code . '"/><span class="checkmark"></span>';
                    echo '<label for="typeetablesiment' . $typeetablesiment->code . '" class="container-checkbox pl-2">  ' . $typeetablesiment->nom . '</label>';
                    echo '</div>';

                    $max++;
                endif;

            endforeach;

            if ($max > 5) : ?><p class="bluetext" onclick="filtervisible('.typeetablesimentcont');">
	
							<?php echo '<span></span>' . JText::_('MOD_CAMPINGS_FILTRES__PLUS_CRITERES'); ?>
	
							</p> <?php endif;


                                echo '</div>';



                                    ?>
	
		</div>
	</div>
	
	</div> -->

<!-- CLUB ENFANTS -->

<!-- <div class="classement serviceslist flex-grow-2">
		<div class="servicesCheck ratingcontent">
	 -->
<?php

/*echo '<div class="moesrvicesclose">';
					echo '<p><strong>'.JText::_('MOD_CAMPINGS_CLUBENFANTS').'</strong></p>';

						echo '<div>';

							foreach($clubenfants as $clubenfant):

									echo '<div class=" pl-3">';
									echo '<input name="clubenfant" id="clubenfant' . $clubenfant->id . '" class="clubenfantSel" type="checkbox"  data-id="'.$clubenfant->id.'" ';

									echo ' value="' . $clubenfant->filterclass . '"/><span class="checkmark"></span>';
									echo '<label for="clubenfant' . $clubenfant->id . '" class="container-checkbox pl-2">  ' .$clubenfant->nom .'</label>';
									echo '</div>';

							endforeach;




				 echo '</div>';
*/


?>

<!-- 	</div>
		</div>

	</div> -->




<!-- EQUIPEMENTS -->

<!-- <div class="classement serviceslist flex-grow-2">
		<div class="servicesCheck ratingcontent"> -->

<?php

/*echo '<div class="moesrvicesclose">';
					echo '<p><strong>'.JText::_('MOD_CAMPINGS_EQUIPEMENTS').'</strong></p>';

						echo '<div>';

							foreach($equipements as $equipement):

									echo '<div class=" pl-3">';
									echo '<input name="equipement" id="equipement' . $equipement->id . '" class="equipementSel" type="checkbox" data-id="'.$equipement->id.'"  ';

									echo ' value="' . $equipement->filterclass . '"/><span class="checkmark"></span>';
									echo '<label for="equipement' . $equipement->id . '" class="container-checkbox pl-2">  ' .$equipement->nom .'</label>';
									echo '</div>';

							endforeach;




				 echo '</div>';*/



?>

<!-- </div>
			</div>

		</div> -->





<!-- LOGEMENT -->

<div class="classement serviceslist flex-grow-2">
    <div class="servicesCheck ratingcontent">

        <?php
        echo '<p class="titlefilter"><strong>' . JText::_('MOD_CAMPINGS_LOGEMENTS') . '</strong> <i class="fas fa-chevron-up bluetext"></i></p>';
        echo '<div class="moesrvicesclose logementcont">';


        echo '<div>';
        $max = 0;
        foreach ($FWLOG as $logement) :
            if ($logement->saison == 0 or $logement->saison == $saison) :

                echo '<div class=" pl-3 ';
                if ($max > 5) : echo 'itemclose ';
                endif;
                echo '">';
                echo '<input name="logement" id="logement' . $logement->code . '" class="logementSel" type="checkbox"  data-id="' . $logement->code . '" ';

                echo ' value="' . $logement->code . '"/><span class="checkmark"></span>';
                echo '<label for="logement' . $logement->code . '" class="container-checkbox pl-2">  ' . $logement->nom . '</label>';
                echo '</div>';

                $max++;
            endif;

        endforeach;

        if ($max > 5) : ?><p class="bluetext" onclick="filtervisible('.logementcont');">

            <?php echo '<span></span>' . JText::_('MOD_CAMPINGS_FILTRES__PLUS_CRITERES'); ?>

        </p> <?php endif;


                echo '</div>';



                    ?>

    </div>
</div>

</div>


<!-- THEMES -->

<?php


?>
<!-- <div class="classement serviceslist flex-grow-2">
		<div class="servicesCheck ratingcontent">
	
			<?php
            echo '<p class="titlefilter"><strong>' . JText::_('MOD_CAMPINGS_THEMES') . '</strong> <i class="fas fa-chevron-up bluetext"></i></p>';
            echo '<div class="moesrvicesclose themecont">';


            echo '<div>';
            $max = 0;
            foreach ($themes as $theme) :

                if ($theme->saison == 0 or $theme->saison == $saison) :
                    echo '<div class=" pl-3 ';
                    if ($max > 5) : echo 'itemclose ';
                    endif;
                    echo '">';
                    echo '<input name="theme" id="theme' . $theme->code . '" class="themeSel" type="checkbox"  data-id="' . $theme->code . '" ';

                    echo ' value="' . $theme->code . '"/><span class="checkmark"></span>';
                    echo '<label for="theme' . $theme->code . '" class="container-checkbox pl-2">  ' . $theme->nom . '</label>';
                    echo '</div>';
                    $max++;
                endif;
            endforeach;

            if ($max > 5) : ?><p class="bluetext" onclick="filtervisible('.servicecont');">
	
								<?php echo '<span></span>' . JText::_('MOD_CAMPINGS_FILTRES__PLUS_CRITERES'); ?>
	
							</p>
	
							<?php endif;



                        echo '</div>';



                            ?>
	
		</div>
	</div>
	
	</div> -->
<!-- DESTINATIONS -->

<?php


?>
<!-- <div class="classement serviceslist flex-grow-2">
		<div class="servicesCheck ratingcontent">
	
			<?php
            echo '<p class="titlefilter"><strong>' . JText::_('MOD_CAMPINGS_DESTINATIONS') . '</strong> <i class="fas fa-chevron-up bluetext"></i></p>';
            echo '<div class="moesrvicesclose destinationcont">';


            echo '<div>';
            $max = 0;
            foreach ($destinations as $destination) :

                if ($destination->saison == 0 or $destination->saison == $saison) :
                    echo '<div class=" pl-3 ';
                    if ($max > 5) : echo 'itemclose ';
                    endif;
                    echo '">';
                    echo '<input name="destination" id="destination' . $destination->code . '" class="destinationSel" type="checkbox"  data-id="' . $destination->code . '" ';

                    echo ' value="' . $destination->code . '"/><span class="checkmark"></span>';
                    echo '<label for="destination' . $destination->code . '" class="container-checkbox pl-2">  ' . $destination->nom . '</label>';
                    echo '</div>';

                    $max++;
                endif;
            endforeach;


            if ($max > 5) : ?><p class="bluetext" onclick="filtervisible('.destinationcont');">
	
								<?php echo '<span></span>' . JText::_('MOD_CAMPINGS_FILTRES__PLUS_CRITERES'); ?>
	
							</p> <?php endif;




                                echo '</div>';



                                    ?>
	
		</div>
	</div>
	
	</div> -->
<!-- DESTINATIONS MMV-->

<?php


?>
<!-- <div class="classement serviceslist flex-grow-2">
		<div class="servicesCheck ratingcontent">
	
			<?php
            echo '<p class="titlefilter"><strong>' . JText::_('MOD_CAMPINGS_DESTINATIONS') . ' MMV</strong> <i class="fas fa-chevron-up bluetext"></i></p>';
            echo '<div class="moesrvicesclose destinationmmvcont">';


            echo '<div>';
            $max = 1;
            foreach ($DESTIMMV as $destinationmmv) :

                if ($destinationmmv->saison == 0 or $destinationmmv->saison == $saison) :
                    echo '<div class=" pl-3 ';
                    if ($max > 5) : echo 'itemclose ';
                    endif;
                    echo '">';
                    echo '<input name="destinationmmv" id="destinationmmv' . $destinationmmv->code . '" class="destinationmmvSel" type="checkbox"  data-id="' . $destinationmmv->code . '" ';

                    echo ' value="' . $destinationmmv->code . '"/><span class="checkmark"></span>';
                    echo '<label for="destinationmmv' . $destinationmmv->code . '" class="container-checkbox pl-2">  ' . $destinationmmv->nom . '</label>';
                    echo '</div>';

                    $max++;
                endif;
            endforeach;
            foreach ($DESTIMMV2 as $destinationmmv) :

                if ($destinationmmv->saison == 0 or $destinationmmv->saison == $saison) :
                    echo '<div class=" pl-3 ';
                    if ($max > 5) : echo 'itemclose ';
                    endif;
                    echo '">';
                    echo '<input name="destinationmmv" id="destinationmmv' . $destinationmmv->code . '" class="destinationmmvSel" type="checkbox"  data-id="' . $destinationmmv->code . '" ';

                    echo ' value="' . $destinationmmv->code . '"/><span class="checkmark"></span>';
                    echo '<label for="destinationmmv' . $destinationmmv->code . '" class="container-checkbox pl-2">  ' . $destinationmmv->nom . '</label>';
                    echo '</div>';

                    $max++;
                endif;
            endforeach;




            if ($max > 5) : ?><p class="bluetext" onclick="filtervisible('.destinationmmvcont');">
	
								<?php echo '<span></span>' . JText::_('MOD_CAMPINGS_FILTRES__PLUS_CRITERES'); ?>
	
							</p> <?php endif;




                                echo '</div>';



                                    ?>
	
		</div>
	</div>
	
	</div> -->
<!-- STATIONS -->

<?php

if ($saison == 1) :
?>
<!-- <div class="classement serviceslist flex-grow-2">
		<div class="servicesCheck ratingcontent">
	
			<?php
            echo '<p class="titlefilter"><strong>' . JText::_('MOD_CAMPINGS_STATIONS') . '</strong><i class="fas fa-chevron-up bluetext"></i></p>';
            echo '<div class="moesrvicesclose stationcont">';


            echo '<div>';
            $max = 0;
            foreach ($stations as $station) :

                if ($station->saison == 0 or $station->saison == $saison) :
                    echo '<div class=" pl-3 ';
                    if ($max > 5) : echo 'itemclose ';
                    endif;
                    echo '">';
                    echo '<input name="station" id="station' . $station->code . '" class="stationSel" type="checkbox"  data-id="' . $station->code . '" ';

                    echo ' value="' . $station->code . '"/><span class="checkmark"></span>';
                    echo '<label for="station' . $station->code . '" class="container-checkbox pl-2">  ' . $station->nom . '</label>';
                    echo '</div>';
                    $max++;
                endif;
            endforeach;

            if ($max > 5) : ?><p class="bluetext" onclick="filtervisible('.stationcont');">
	
								<?php echo '<span></span>' . JText::_('MOD_CAMPINGS_FILTRES__PLUS_CRITERES'); ?>
	
							</p> <?php endif;




                                echo '</div>';



                                    ?>
	
		</div>
	</div>
	
	</div> -->

<?php endif; ?>

<!-- LOCALISATION -->

<?php


?>
<!-- <div class="classement serviceslist flex-grow-2">
		<div class="servicesCheck ratingcontent">
	
			<?php
            echo '<p class="titlefilter"><strong>' . JText::_('MOD_CAMPINGS_LOCALISATION') . '</strong><i class="fas fa-chevron-up bluetext"></i></p>';
            echo '<div class="moesrvicesclose localisationcont">';
            echo '<div>';
            $max = 0;
            foreach ($localisations as $localisation) :
                if ($localisation->saison == 0 or $localisation->saison == $saison) :

                    echo '<div class=" pl-3 ';
                    if ($max > 5) : echo 'itemclose ';
                    endif;
                    echo '">';
                    echo '<input name="localisation" id="localisation' . $localisation->code . '" class="localisationSel" type="checkbox"   ';

                    echo ' value="' . $localisation->code . '"/><span class="checkmark"></span>';
                    echo '<label for="localisation' . $localisation->code . '" class="container-checkbox pl-2">  ' . $localisation->nom . '</label>';
                    echo '</div>';
                    $max++;
                endif;
            endforeach;
            if ($max > 5) : ?><p class="bluetext" onclick="filtervisible('.localisationcont');">
	
								<?php echo '<span></span>' . JText::_('MOD_CAMPINGS_FILTRES__PLUS_CRITERES'); ?>
	
							</p> <?php endif;



                                echo '</div>';



                                    ?>
	
		</div>
	</div>
	
	
	</div> -->

<?php
if ($mobile == 0) :

    echo '<div class="textfiltersmod d-flex packageinfo align-items-center"><img class="float-left " src="images/icons/picto-package-glisse.png"" /><div class="consultezexpert">' . $params->get('packageglisse');
    if ($params->get('packageglissemoreinfo') != '') :
        echo '<button type="button" class="btn  " data-toggle="modal" data-target="#glissemodal"><img src="images/icons/infofilters.png" alt="package-glisse" title="package-glisse" class=""></button>';

        //modal
        echo   '<div id="glissemodal" class="modal fade " role="dialog">';
        echo   '<div class="modal-dialog">';
        echo   '<div class="modal-content">';
        echo   '<div class="modal-body">';
        echo   $params->get('packageglissemoreinfo');
        echo   '</div>';
        echo   '<div class="modal-footer">';
        echo   ' <button type="button" class="btn btn-default" data-dismiss="modal">' . JText::_('MOD_CAMPINGS_FERMER') . '</button>';
        echo   '</div>';
        echo   '</div>';
        echo   '</div>';
        echo   '</div>';


    endif;
    echo '</div></div>';
    echo '<div class="textfiltersmod d-flex partezinfo align-items-center"><img class="float-left " src="images/icons/picto-le-saviez.png" /><div class="consultezexpert">' . $params->get('partezenfans');
    if ($params->get('partezenfansmoreinfo') != '') :
        echo '<button type="button" class="btn  " data-toggle="modal" data-target="#partezenfansmodal"><img src="images/icons/infofilters.png" alt="package-glisse" title="package-glisse" class=""></button>';
        //modal
        echo '<div id="partezenfansmodal" class="modal fade " role="dialog">';
        echo '<div class="modal-dialog">';
        echo '<div class="modal-content">';
        echo '<div class="modal-body">';
        echo  $params->get('partezenfansmoreinfo');
        echo '</div>';
        echo '<div class="modal-footer">';
        echo ' <button type="button" class="btn btn-default" data-dismiss="modal">' . JText::_('MOD_CAMPINGS_FERMER') . '</button>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';

    endif;
    echo '</div></div>';

endif;

if ($mobile == 1) :
    echo '<button class="closfiltersmobile bluedbackground blanctext text-center p-2 mb-3" >' . JText::_('MOD_CAMPINGS_GAMMES_APPLIER') . '</button>';
    echo '<div class="textfiltersmod d-flex contactezinfo align-items-center"><img class="float-left " src="images/icons/picto-expert.png" /><div class="consultezexpert">' . $params->get('descrfilters') . '</div></div>';


endif; ?>


<script>
jQuery(document).ready(function() {

    <?php if ($mobile == 1) : ?>
    var altura = jQuery(window).height();
    jQuery(".recherchercms .rechercherfilter").css("height", altura);

    <?php endif; ?>
    jQuery(".closfiltersmobile").click(function() {
        jQuery(".rechercherfilter").toggleClass("open");
    });
    jQuery(".rechercherBtn , .closrecherchersmobile").click(function() {
        jQuery(".recherchermobile").toggleClass("open");
    });


});
</script>
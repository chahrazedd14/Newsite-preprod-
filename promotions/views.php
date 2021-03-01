<?php

/**
 * @version     CVS: 1.0.0
 * @package     com_campings
 * @subpackage  mod_campings
 * @author      unixdata <web@thelis.es>
 * @copyright   unixdata
 * @license     Licencia Pública General GNU versión 2 o posterior. Consulte LICENSE.txt

 * Promotions

 */

require_once  'function.php';

function getPromotions($elements, $elementsCategorie,  $directorio, $temporada, $categoria, $lang, $categorieDerniereminutesAccueil, $categorieDerniereminutes, $lienlang)
{


    $host = ModCampingsHelper::getHost();

    echo '<div class="container blancbackground p-0" id="promotionsresults">';
    //echo $temporada;

    echo '<div class="row"  id="promotionscontent">';
    echo '<div class="col pl-2 pr-0">';

    echo '<div class="offresSlick">';
    echo $lang;

    foreach ($elements as $element) :
        $nom = ($element->nomtranslate != '') ? $element->nomtranslate : $element->nom;
        $subtitre = ($element->subtitretranslate != '') ? $element->subtitretranslate : $element->subtitre;
        $tag = ($element->tagtranslate != '') ? $element->tagtranslate : $element->tag;
        $alias = ($element->aliastranslate != '') ? $element->aliastranslate : $element->alias;


        if ($element->derniereminute == 0) :
            if ($element->brochure == 0) :
                if (isset($nom)) :


                    echo '<div class="slickContent">';
                    echo '<a href="' . $host . $lienlang . $alias;



                    echo '" >';
                    if ($element->imgpromotion != '') :
                        $element->img = $element->imgpromotion;
                    else :
                        $element->img = $host . '../images/landingpages/vignettes/' . $directorio . '/default.jpg';
                    endif;
                    echo '<img src="' . $host . $element->img . '" alt="' . $nom . '" title="' . $nom . '" />';
                    echo '<span class="tag blueexdbackground blanctext">' . $tag . '</span>';
                    echo '<p class="discont">' . $element->minprix;
                    if ($element->minprix != '') :
                    //echo '<span>%</span>';
                    endif;
                    echo '</p>';
                    echo '<div class="dernieretext">';
                    if ($nom != '') :
                        echo '<p class="blanctext">' . $nom . '</p>';
                        echo '<h3 class="blanctext">' . $subtitre . '</h3>';
                    endif;

                    echo '</div>';


                    echo '</a>';
                    //DIV HOVER PROMOTION


                    echo '<a class="promotionhoverclub" href="' . $host . $lienlang . $alias;


                    echo '" >';

                    echo '<div class="dernieretexthover">';
                    if ($nom != '') :
                        echo '<p class="blanctext promotion-nom">' . $nom . '<span>' . $subtitre . '</span></p>';
                        echo '<span class="tag blanctext">' . $tag . ' ' . $element->minprix . '</span>';
                        echo '<p class="bluebackground blanctext text-center promobutton">' . JText::_('MOD_CAMPINGS_OFFERS_PROFITEZ') . '</p>';


                    endif;

                    echo '</div>';


                    echo '</a>';

                    //DIV HOVER PROMOTION
                    echo '</div>';
                endif;
            else :
                // brochure
                echo '<div class="slickContent">';
                echo '<a href="' . $element->lienbrochure . '" target="_blank">';

                if ($element->imgpromotion != '') :
                    $element->img = $element->imgpromotion;
                else :
                    $element->img = $host . 'images/landingpages/vignettes/' . $directorio . '/default.jpg';
                endif;
                echo '<img src="' . $host . $element->img . '" alt="' . $nom . '" title="' . $nom . '" />';
                echo '</a>';



                echo '</div>';

            endif;


        endif;
    endforeach;


    echo '</div>';


    echo '</div>';
    echo '</div>';

    /*echo '<div id="promotionsbuttons"><div id="promotionsbuttons"><button onclick="PromotionsPorCategories('.$temporada.' , 0) " value="0">'.JText::_('MOD_CAMPINGS_TIUTES_PROMOTIONS').'</button>';
    echo '<div><img src="'.$host.'images/icons/derniere-minute.png" alt="'.JText::_('MOD_CAMPINGS_DERNIERES_MINUTE').'" title="'.JText::_('MOD_CAMPINGS_DERNIERES_MINUTE').'" />'.JText::_('MOD_CAMPINGS_DERNIERES_MINUTE').'</div>';
	foreach ($elementsCategorie as $categorie):

 				 	if($categorie->temporada == $temporada):

 				  	echo '<button onclick="PromotionsPorCategories('.$temporada.' , '.$categorie->id.') " value="'.$categorie->id.'"' ;
 				  	if($categoria ==  $categorie->id):
 				  			echo ' class="active" ';
 				  	endif;

 				  	echo '>'.$categorie->nom.'</button>';
 				 	endif;
	endforeach;
	echo '</div></div>';*/
    echo '<div class="row" id="dernieresminutescontent">';
    echo '<div class="col-12 col-lg-4 col-xl-3"><a href="promotions" class="blueexdbackground blanctext promotions-button ">' . JText::_(MOD_CAMPINGS_PROMOTIONS_TOUTES_LES_PROMOS) . ' <span class="mmvfont">mmv</span></a></div>';

    // BLOQUE DERNIERES MINUTES

    echo '<div class="float-left col-12 col-lg-8 col-xl-9">';
    if ($categorieDerniereminutesAccueil->nom != '') :
        $nom = ($categorieDerniereminutesAccueil->nomtranslate != '') ? $categorieDerniereminutesAccueil->nomtranslate : $categorieDerniereminutesAccueil->nom;
        echo '<div class="bluedtext text-uppercase pr-4 titleaccuilderniere"><img class="derniereminuteicon p-1" width="48px" src="' . $host . $categorieDerniereminutesAccueil->icone . '" alt="' . $nom . '"  title="' . $nom . '" /><strong>' . $nom . '</strong></div>';

        if (!empty($categorieDerniereminutes)) :
            echo '<div class="offresSlick p-1">';
            foreach ($categorieDerniereminutes as $categories) :
                $nom = ($categories->nomtranslate != '') ? $categories->nomtranslate : $categories->nom;
                echo '<div class="slickContent"><a class=" bluedtext btn  text-center mr-2" href="https://sitenew.preprod.mmv.resalys.com/' . $lienlang . $categories->alias . '" >' . $nom . '</a></div>';
            endforeach;
            echo '</div>';

        endif;
    endif;
    echo '</div>';
    echo '</div>';
}
function getPromotionsMobile($elements, $elementsCategorie,  $directorio, $temporada, $categoria, $lang, $categorieDerniereminutesAccueil, $categorieDerniereminutes, $lienlang)
{

    $host = ModCampingsHelper::getHost();

    echo '<div class=" blancbackground " id="promotionsresults">';
    //echo $temporada;



    foreach ($elements as $element) :

        $nom = ($element->nomtranslate != '') ? $element->nomtranslate : $element->nom;
        $subtitre = ($element->subtitretranslate != '') ? $element->subtitretranslate : $element->subtitre;
        $tag = ($element->tagtranslate != '') ? $element->tagtranslate : $element->tag;
        $alias = ($element->aliastranslate != '') ? $element->aliastranslate : $element->alias;

        if ($element->derniereminute == 0) :
            if ($element->brochure == 0) :
                if (isset($nom)) :


                    echo '<div class="slickContent slickContentPromos d-flex align-items-center mt-3 mb-3">';
                    echo '<a href="javascript:; ';


                    echo '" class="w-100" >';
                    if ($element->imgpromotion != '') :
                        $element->img = $element->imgpromotion;
                    else :
                        $element->img = $host . 'images/landingpages/vignettes/' . $directorio . '/default.jpg';
                    endif;
                    echo '<img src="' . $host . $element->img . '" alt="' . $nom . '" title="' . $nom . '" class="w-100" />';
                    echo '<span class="tag blueexdbackground blanctext">' . $tag . '</span>';
                    echo '<p class="discont">' . $element->minprix . '</p>';
                    echo '<div class="dernieretext">';
                    if ($nom != '') :
                        echo '<p class="blanctext">' . $nom . '</p>';
                        echo '<h3 class="blanctext">' . $subtitre . '</h3>';
                    endif;

                    echo '</div>';


                    echo '</a>';
                    echo '</div>';


                endif;
            else :
            // brochure
            /*echo '<div class="slickContent slickContentPromos slickContentBrochure d-flex align-items-center mt-3 mb-3">';
								echo '<a href="'.$element->lienbrochure.'" target="_blank" >';

									if($element->imgpromotion !=''):
										$element->img = $element->imgpromotion;
									else:
										$element->img = $host.'images/landingpages/vignettes/'.$directorio.'/default.jpg';
									endif;
								echo '<img src="'.$host.$element->img.'" alt="'.$element->nom. '" title="'.$element->nom. '" />';
								echo '</a>';
							echo '</div>';*/

            endif;
        endif;
    endforeach;


    echo '<div class="row" id="dernieresminutescontent">';
    echo '<div class="col-12 col-lg-4 col-xl-3"><a href="promotions" class="blueexdbackground d-block blanctext promotions-button ">' . JText::_(MOD_CAMPINGS_PROMOTIONS_TOUTES_LES_PROMOS) . ' <span class="mmvfont">mmv</span></a></div>';

    // BLOQUE DERNIERES MINUTES

    echo '<div class="float-left col-12 col-lg-8 col-xl-9">';
    if ($categorieDerniereminutesAccueil->nom != '') :
        $nom = ($categorieDerniereminutesAccueil->nomtranslate != '') ? $categorieDerniereminutesAccueil->nomtranslate : $categorieDerniereminutesAccueil->nom;
        echo '<div class="bluedtext text-uppercase pr-4 titleaccuilderniere"><img class="derniereminuteicon p-1" width="48px" src="' . $host . $categorieDerniereminutesAccueil->icone . '" alt="' . $nom . '"  title="' . $nom . '" /><strong>' . $nom . '</strong></div>';

        if (!empty($categorieDerniereminutes)) :

            echo '<div class="offresSlick p-1">';
            foreach ($categorieDerniereminutes as $categories) :
                $nom = ($categories->nomtranslate != '') ? $categories->nomtranslate : $categories->nom;
                echo '<div class="slickContent"><a class=" bluedtext btn  text-center mr-2" href="https://sitenew.preprod.mmv.resalys.com/' . $lienlang . $categories->alias . '" >' . $nom . '</a></div>';
            endforeach;
            echo '</div>';

        endif;
    endif;
    echo '</div>';
    echo '</div>';
}

?>

<script>
jQuery(document).ready(function() {

    jQuery('#promotionscontent .offresSlick').slick({

        infinite: true,
        speed: 300,
        slidesToShow: 4,
        variableWidth: true,
        responsive: [{
                breakpoint: 1024,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1,
                    infinite: true,

                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 2,

                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                }
            }
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
        ]

    });
    jQuery('#dernieresminutescontent .offresSlick').slick({

        infinite: false,
        speed: 300,
        slidesToShow: 5,
        slidesToScroll: 1,
        centerMode: false,
        variableWidth: true,
        responsive: [{
                breakpoint: 1024,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    infinite: true,

                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1
                }
            }
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
        ]

    });
});
</script>
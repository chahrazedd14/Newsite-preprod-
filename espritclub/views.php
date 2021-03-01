<?php

/**
 * @version     CVS: 1.0.0
 * @package     com_campings
 * @subpackage  mod_campings
 * @author      unixdata <web@thelis.es>
 * @copyright   unixdata
 * @license     Licencia Pública General GNU versión 2 o posterior. Consulte LICENSE.txt
 * Esprit Club
 */

require_once  'function.php';

function printModuleLandingpage($elements, $directorio, $lang)
{

    $host = ModCampingsHelper::getHost();
    $mobile = ModCampingsHelper::getMobile();

    echo '<div  class="container blancbackground ';
    if ($mobile == 0) : echo 'p-0';
    endif;
    echo '">';

    echo '<div class="row p-0">';
    echo '<div class="col-lg p-0 slickespirit">';

    echo '<div class="offresSlick">';

    foreach ($elements as $element) :

        $nom = ($element->nomtranslate != '') ? $element->nomtranslate : $element->nom;
        $alias = ($element->aliastranslate != '') ? $element->aliastranslate : $element->alias;
        $slogan = ($element->slogantranslate != '') ? $element->slogantranslate : $element->slogan;
        $introtext = ($element->introtexttranslate != '') ? $element->introtexttranslate : $element->introtext;
        $linktext = ($element->linktexttranslate != '') ? $element->linktexttranslate : $element->linktext;


        if (isset($element->nom)) :

            echo '<div class="slickContent">';
            echo '<a href="' . $element->ruta;


            echo '" >';
            echo '<div class="contentimg">';
            if ($element->imgdialog != '') :
                $element->imgdialog = $element->imgdialog;
            else :
                $element->imgdialog = $host . 'images/landingpages/vignettes/' . $directorio . '/default.jpg';
            endif;
            echo '<img src="' . $host . $element->imgdialog . '" alt="' . $nom . '" title="' . $nom . '" />';
            if ($nom != '') :
                echo '<h3>' . $nom . '</h3>';
            endif;
            echo '<div class="contbuttonoffremom"><button class="align-middle d-inline-flex button01 medianeligne">' . $linktext . '</button></div>';

            echo '</div>';
            echo '<div class="dernieretext ">';

            if ($element->introtext != '') :
                echo '<div class="textocult noirtext">';
                $introtext = strip_tags($introtext);
                echo '<p>' . substr($introtext, 0, 150) . ' </p><div class=" d-flex text-center"></div>';

                echo '</div>';
            endif;

            echo '</div>';


            echo '</a>';
            echo '</div>';


        endif;
    endforeach;

    echo '</div>';


    echo '</div>';
    echo '</div>';
    if ($mobile == 1) :
        echo '<a href="l-experience-mmv" class="blueexdbackground d-block text-center blanctext promotions-button">

		 <span class="mmvfont">mmv</span>

		</a>';
    endif;
}

?>
<script>
jQuery(document).ready(function() {

    jQuery('#espritclub .offresSlick').slick({

        infinite: true,
        speed: 300,
        slidesToShow: 5,
        slidesToScroll: 1,
        responsive: [{
                breakpoint: 1124,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    infinite: true,
                    centerMode: true,

                }
            },
            {
                breakpoint: 800,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1,
                    infinite: true,
                    centerMode: true,

                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1,
                    infinite: false,

                }
            },
            {
                breakpoint: 550,
                settings: {
                    slidesToShow: 2,
                    arrows: false,
                    slidesToScroll: 1,
                    variableWidth: true

                }
            }
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
        ]

    });
});
</script>
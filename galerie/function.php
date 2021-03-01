<?php

/**
 * @version     CVS: 1.0.0
 * @package     com_campings
 * @subpackage  mod_campings
 * @author      unixdata <web@thelis.es>
 * @copyright   unixdata
 * @license     Licencia Pública General GNU versión 2 o posterior. Consulte LICENSE.txt

 * Galerie LightSlide
 */

require_once  '../includes.php';
$host = ModCampingsHelper::getHost();
$directorio = (isset($_POST['directorio'])) ? $_POST['directorio'] : 0;
$galerie = (isset($_POST['galerie'])) ? $_POST['galerie'] : '';
$slogan = (isset($_POST['slogan'])) ? $_POST['slogan'] : 0;
$codeCRM = (isset($_POST['codeCRM'])) ? $_POST['codeCRM'] : 0;
$saison = (isset($_POST['saison'])) ? $_POST['saison'] : 0;
$view = (isset($_POST['view'])) ? $_POST['view'] : '';
$contenedor = (isset($_POST['contenedor'])) ? $_POST['contenedor'] : '';
$etab = (isset($_POST['etab'])) ? $_POST['etab'] : '';
$alias = (isset($_POST['alias'])) ? $_POST['alias'] : '';
$subdirectorio = (isset($_POST['subdirectorio'])) ? $_POST['subdirectorio'] : '';


if ($view == 'galerie') : funcgalerie($directorio, $host, $alias, $subdirectorio);
endif;

if ($view == 'galeriecaption') :
    $imagenes = ModCampingsHelper::getImagesGalerie($codeCRM, $saison, $galerie);
    funcgaleriecaption($imagenes, $host, $saison);
endif;
if ($view == 'galerieexperience') :
    $imagenes = ModCampingsHelper::getImagesGalerieexperience($galerie, $etab);
    //print_r($imagenes);
    funcgaleriecaption($imagenes, $host, $saison);
endif;



function funcgalerie($directorio, $host, $alias, $subdirectorio)
{

    $directorioruta = '../../../' . $directorio . '/';

    // ESTA GALERIA SE CARGA EN LAS PAGINAS ETAB

    if (file_exists($directorioruta)) {
?>
<div style="display:none;" id="video1">
    <video id="video_player" class="lg-video-object lg-html5" playsinline muted loop autoplay
        poster="https://sitenew.preprod.mmv.resalys.com/images/defaultmini.jpg">
        <source
            src="https://sitenew.preprod.mmv.resalys.com/images/Le-Coeur-des-Loges-Les-Menuires-3-Vallees-Terresens.mp4"
            type="video/mp4">
        Your browser does not support HTML5 video.
    </video>
</div>




<?php
        echo '<ul class="lightSliderContent">';

        ?>
<li data-thumb="https://sitenew.preprod.mmv.resalys.com/images/defaultmini.jpg" data-html="#video1">
    <video id="video1" class="lg-video-object lg-html5" playsinline muted loop autoplay
        style="width:100%!important;height: auto!important; border-radius: 4px;">
        <source
            src="https://sitenew.preprod.mmv.resalys.com/images/Le-Coeur-des-Loges-Les-Menuires-3-Vallees-Terresens.mp4"
            type="video/mp4">
    </video>
</li>





<?php
        $ficheros  = scandir($directorioruta);
        $i = 0;
        foreach ($ficheros as $key => $value) {

            if (preg_match("/\.(png|gif|jpe?g|bmp)/", $value, $m)) {
                $images[] = $value;

                $imgsansextension = substr($value, 0, -4); //lien sans extension
                $extension = substr_replace($value, '', 0, -4); //extension
                $thumb = $host . 'images/etablissements/' . $alias . '/thumbs/' . $subdirectorio . '/' . $imgsansextension . '_thumb_103x69' . $extension;

                $img = $host . 'images/etablissements/' . $alias . '/thumbs/' . $subdirectorio . '/' . $imgsansextension . '_thumb_750x500' . $extension;
                $imgecran = $host . 'images/etablissements/' . $alias . '/thumbs/' . $subdirectorio . '/' . $imgsansextension . '_thumb_1350x900' . $extension;
                //$img = $host.$directorio .'/'. $value;
                //crearThumbnail($img, $thumb, '103', '69');
        ?>

<li data-thumb="<?php echo $thumb; ?>" data-src="<?php echo $imgecran; ?>">
    <img itemprop="photo" src="<?php echo $img; ?>" alt="" title="" />
    <!-- <div class="caption"><p>Desc1</p> </div> -->

</li>


<?php
                $i++;
            }
        }

        echo ' </ul>';
        if ($i > 1) :
            //echo ' <div class="blanctext numimages"><p><img src="../images/icons/imgicon.jpg" /> +'.$i.'</p></div>';
            ?>

<!-- show all is not part of thumnail list, but next to it -->
<div class="numimages">
    <p><img src="../images/icons/imgicon.jpg" /> +<?php echo $i; ?></p>
</div>
<?php
        endif;
    }
}

function funcgaleriecaption($imagenes, $host, $saison)
{

    // ESTA GALERIA SE CARGA EN LA PAGINA EXPERIENCE
    if (!empty($imagenes)) {
        echo '<ul class="lightSliderContent ' . $saison . '">';
        $i = 0;
        foreach ($imagenes as $imagen) :
            if ($imagen->saison == $saison || $imagen->saison == 0) :
            ?>

<li data-thumb="../<?php echo $imagen->url; ?>" data-src="<?php echo $host; ?><?php echo $imagen->url; ?>">
    <img itemprop="photo" src="<?php echo $host ?><?php echo $imagen->url; ?>" alt="<?php echo $imagen->caption; ?>"
        title="<?php echo $imagen->caption; ?>" />
    <div class="caption">
        <p><?php echo $imagen->caption; ?></p>
    </div>

</li>


<?php
                $i++;
            endif;
        endforeach;
        echo ' </ul>';
        if ($i > 1) :
            echo ' <div class="blanctext numimages numimagesexperienc"><p><img src="../images/icons/imgicon.jpg" /> +' . $i . '</p></div>';
        endif;
    }
}



?>

<script>
jQuery(document).ready(function() {

    //lightGallery(document.getElementsByClassName('lightSliderContent'));

    jQuery('<?php echo $contenedor; ?> .lightSliderContent').lightSlider({
        gallery: true,
        item: 1,
        loop: true,
        thumbItem: 7,
        slideMargin: 0,
        enableDrag: true,
        currentPagerPosition: 'left',

        onSliderLoad: function(el) {
            el.lightGallery({
                selector: '.lightSlider .lslide',
                share: false,
                download: false,
                zoom: false,
                autoplayControls: false,
                autoplay: false

                // loadYoutubeThumbnail: true,
                // youtubeThumbSize: 'default',
                // loadVimeoThumbnail: true,
                // vimeoThumbSize: 'thumbnail_medium',
                // youtubePlayerParams: {
                // 	modestbranding: 1,
                // 	showinfo: 0,
                // 	rel: 0,
                // 	controls: 0
                // },
                // vimeoPlayerParams: {
                // 	byline : 0,
                // 	portrait : 0,
                // 	color : 'A90707'     
                // }


            });
        }


    });
    jQuery('.numimages').click(function() {

        jQuery('li.lslide.active').trigger('click');

    });
});
</script>
<style>
.numimages {
    position: absolute;
    right: 0;
    bottom: 0;
    width: 104px;
    height: 70px;
    text-align: center;
    align-items: center;
    display: grid;
    background-color: #0d2146;
    cursor: pointer;
}

.numimages p {
    color: #fff;
    margin: 0;
}

.numimagesexperienc {
    width: 118px;
}

.numimagesexperienc img {
    width: initial !important;
}

.show-all {
    position: absolute;
    right: 0;
    bottom: 0;
    width: 104px;
    height: 70px;
    color: #fff;

    background-color: #0d2146;
}

.show-all p {
    color: #fff;
    margin: 0;
}
</style>
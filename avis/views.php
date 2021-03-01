<meta charset="utf-8" />
<?php

/**
 * @version     CVS: 1.0.0
 * @package     com_campings
 * @subpackage  mod_campings
 * @author      unixdata <web@thelis.es>
 * @copyright   unixdata
 * @license     Licencia Pública General GNU versión 2 o posterior. Consulte LICENSE.txt

 * Clubs
 */
//DAVID EDITAR
require_once  'function.php';

?>



<!-- ************************************************************  *
	*
	* **************** VALORACION GENERAL ESTANCIA ******************  *
	*
	 ****************************************************************-->
<div id="avisclientsFicha">
    <?php function printmoduleAvisCampingsPrestataires($elementsCampingsAvis)
    { ?>
    <?php } ?>


    <!-- *********************************************************** -->
    <?php function printmoduleAvisCommentsPrestataires($elementsAvis)
    {
        // Cambiamos lo title si no tiene valoraciones y ocultamos todo que no es necesario exibir
        $valores = $elementsAvis[0]->satisfactionAverage; ?>

    <script>
    document.getElementById('porcentage').style.display = 'inline';
    var z = "<?php echo $valores; ?>";
    title = "<?php echo JText::_(MOD_CAMPINGS_AVIS_VIEW_NOUS_NAVONS_AUCUNE_NOTE); ?>";
    if (z != '') {
        document.getElementById('porcentage').style.display = 'inline';
        document.getElementById('titlePrincipal').style.display = 'inline';
        document.getElementById('titles').style.display = 'inline';
    } else {
        document.getElementById("titles").innerText = title;
        document.getElementById('porcentage').style.display = 'none';
        document.getElementById('titlePrincipal').style.display = 'none';
    };
    </script>

    <!-- fin cambia title -->
    <!-- *********************************************************** -->
    <div class="row">
        <div id="porcentage" class="col-6 col-md-4 col-xl-3 avis-percentage">
            <img class="rounded d-block happyFace" src="https://new.mmv.fr/images/icons/hiverhappyface.jpg" alt="">
            <p id="satisfaction" class="totalSatisfaction bluedtext">
                <?php echo round($elementsAvis[0]->satisfactionAverage) . '<span>%</span>'; ?></p>
            <br>
            <span class="textsatisfaction">

                <?php echo JText::_(MOD_CAMPINGS_AVIS_VIEW_DE_SATISFACTION); ?>

            </span>
            <?php //echo $elementsAvis[0]->avisCode; 
                ?>
        </div>
    </div>
</div>
<!-- *********************************************************** -->
<?php } ?>
<!-- ************************************************************ -->



<!-- ************************************************************  *
	*
	* **************** VALORACIONES DE CLIENTES *********************  *
	*
	 ****************************************************************-->
<!-- *********************************************************** -->
<?php function printmoduleAvisPrestataires($elementsComments)
{ ?>
<!-- *********************************************************** -->
<?php
    //TENEMOS TODOS LOS COMMENTS DE LOS CLIENTES PARA LO AVIS SELECCIONADO
    echo '<div class="container avis-opinions">';
    foreach ($elementsComments as $elementsCommentsIN) {
        echo  '<div class="commentBox">';
        echo  '<div class="row">';
        echo  '<div class="col-4">';
        echo  '<div class="nameNote">';
        if ($elementsCommentsIN->firstName == '') {
            $elementsCommentsIN->firstName = 'Anonyme';
        }
        echo  '<p class="avisClientName blueTitleAvis">' . strtoupper($elementsCommentsIN->firstName) . ' ' . strtoupper($elementsCommentsIN->lastName) . '</p>';
        echo  '<span class="avisDate blueDescAvis">
                                ' . JText::_(MOD_CAMPINGS_AVIS_VIEW_DU) . '
                                ' . $elementsCommentsIN->stayStart . '
                                ' . JText::_(MOD_CAMPINGS_AVIS_VIEW_AU) . '
                                ' . $elementsCommentsIN->stayEnd . '</span>';
        /* echo  '<p class="avisNote bluedtext">'. $average .'</p>'; */
        echo  '<div class="col-6 box-container"><div class="box-wrapper"><div class="box-circle"><div class="circle-border red-background" data-color1="#dfdfdf" data-color2="#0f8fd1"><div class="circle-percentage">
									<span  id="note" class="percentage bluedtext" data-percentage="' . $elementsCommentsIN->note . '">' . $elementsCommentsIN->note . '</span>
									</div></div></div></div></div>';
        echo  '</div>';
        echo  '</div>';
        echo  '<div class="col-8">';
        // VRIFICANDO POR HORA
        echo  '<p class=" textComment"> ' . $elementsCommentsIN->commentTitle . ' </p>';
        echo  '<blockquote class="avisblockquote textComment"> ' . $elementsCommentsIN->comment . ' </blockquote>';
        //
        echo  '</div>';
        echo  '</div>';
        echo  '</div>';
    }
    echo '</div>';
    //SE ACABA EL FOREACH DE LOS COMMENTS DE CLIENTES
    ?>

<!-- ************************************************************ -->
<?php } ?>
<!-- ************************************************************ -->



<!-- ************************************************************  *
	*
	* **************** SCRIPTS LOAD BLUE PORCENTAGE *****************  *
	*
	 ****************************************************************-->
<script>
/* CIRCULOS AVIS*/
jQuery(".box-circle").each(function() {
    let i = 0,
        that = jQuery(this),
        circleBorder = that.find(".circle-border"),
        borderColor = circleBorder.data("color1"),
        animationColor = circleBorder.data("color2"),
        percentageText = that.find(".percentage"),
        percentage = percentageText.data("percentage"),
        degrees = percentage * 3.6;
    circleBorder.css({
        "background-color": animationColor
    });
    setTimeout(function() {
        loopIt();
    }, 1);

    function loopIt() {
        i = i + 1
        if (i < 0) {
            i = 0;
        }
        if (i > degrees) {
            i = degrees;
        }
        percentage = i / 3.6;
        percentageText.text(percentage.toFixed(0));
        if (i <= 180) {
            circleBorder.css('background-image', 'linear-gradient(' + (90 + i) + 'deg, transparent 50%,' +
                borderColor + ' 50%),linear-gradient(90deg, ' + borderColor + ' 50%, transparent 50%)');
        } else {
            circleBorder.css('background-image', 'linear-gradient(' + (i - 90) + 'deg, transparent 50%,' +
                animationColor + ' 50%),linear-gradient(90deg, ' + borderColor + ' 50%, transparent 50%)');
        }
        setTimeout(function() {
            loopIt();
        }, 1);
    }
});
/* END CIRCULOS AVIS */
</script>



<!-- ************************************************************  *
	*
	* **************** PARA TESTEAR O COGER CAMPOS ******************  *
	*
	 ****************************************************************-->
<?php
//USADO PARA TESTEAR LOS CAMPOS A CARGAR----BORRAR DESPUÉS DE DEFINITIVO

// Printamos los dados de las Estancias
// function printmoduleAvisCommentsPrestataires($elementsAvis){
// 	$host = ModCampingsHelper::getHost();
// 	echo "<br><br><h3> Query de Estancia </h3><br>";
//     // print_r($elementsAvis);
// 	foreach ($elementsAvis as $avisElements){
// 		echo "<br>";
// 		echo $avisElements->avisCode;
// 		echo "<br>";
// 		echo $avisElements->AnsweredSurveys;
// 		echo "<br>";
// 		echo $avisElements->satisfactionAverage;
// 		echo "<br>";
// 	}
// }
// Printamos los dados de los clientes de estancia
// function printmoduleAvisPrestataires($elementsComments){
// 	$host = ModCampingsHelper::getHost();
//     echo "<br><br><h3> Query de Clientes </h3><br>";
//     // print_r($elementsComments);
// foreach ($elementsComments as $commentsElements){
// 		echo "<br>";
// 		echo "AvisCode: ".$commentsElements->avisCode;
// 		echo "<br>";
// 		echo "idSejour: ".$commentsElements->idSejour;
// 		echo "<br>";
// 		echo "Nom: ".$commentsElements->firstName;
// 		echo "";
// 		echo ", ".$commentsElements->lastName;
// 		echo "<br>";
// 		echo "du: ".$commentsElements->stayStart;
// 		echo "<br>";
// echo "au: ".$commentsElements->stayEnd;
// 		echo "<br>";
// echo "note: ".$commentsElements->note;
// 		echo "<br>";
// 		echo "comment: ".$commentsElements->comment;
// 		echo "<br>";
// 		echo $commentsElements->pinned;
// 		echo "<br>";
// 		echo $commentsElements->profile1;
// 		echo "<br>";
// 		echo $commentsElements->profile2;
// 		echo "<br>";
// 		echo $commentsElements->cycleId;
// 		echo "<br>";
// 		echo $commentsElements->surveyId;
// 		echo "<hr>";
//
// }
// }
?>
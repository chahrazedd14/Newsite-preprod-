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

require_once  'function.php';


function printmoduleclub($elements , $directorio, $lang, $saison ,$promotions, $lienlang){
/*	echo '<pre>';
	print_r($promotions);
	echo '</pre>';*/
	$i=0;
	$ipromo=0;
	$countpromotions =  count($promotions);
	$host = ModCampingsHelper::getHost();
	$hostimg = ModCampingsHelper::getHostImage();
	$mobile = ModCampingsHelper::getMobile();
	//$lang = ModCampingsHelper::getLang();
	// echo $lang;
	

	if($mobile==1):
		echo ' <h2 class="blanctext text-center"><strong>

		'.JText::_(MOD_CAMPINGS_CLUBS_20_CLUBS).'

		</strong>

		<span class="mmvfont">mmv</span>

		</h2>';
		echo ' <p class="blanctext text-center m-0">

		'.JText::_('MOD_CAMPINGS_CLUBS_LEQUEL_SERA_VOTRE_PREFERE').'

		</p>';
	endif;

	echo '<div class="container ';
 	if($mobile==0): echo ' blancbackground'; endif;
	echo '">';


	echo '<div class="row ml-2 d-none d-sm-none d-md-none d-lg-block d-xl-block">';
	echo'<div class="col-7 titleclubsmmv d-flex align-items-center pl-3 pt-2">';
	  echo' <h2 class="float-left blueexdtext"><strong>  20 Clubs  </strong><span class="mmvfont"> mmv</span> <br></h2><span class="blueexdtext pl-3">Lequel sera <br>votre préféré ?</span>';

echo '</div>';
echo '</div>';




	echo '<div class="row ">';
	
			echo '<div class="col-lg slickclubs">';
				echo '<div class="offresSlick">';

					foreach($elements as $element ):

						/*$regiongeographique = ModCampingsHelper::getRegionGeographique($saison , $element->codeCRM);	
						echo $regiongeographique;*/
						

						//ORIGINAL CRIS
						//$colortext = ($element->typeDestination == 2) ? 'bluetext' : 'greentext';
						
						/*$colortext = ($element->gamma == 2) ? 'bluetext' : 'greentext';*/
						$colortext = '';
						if($element->gamma==1):
							$colortext = 'greentext';
						endif;
						if($element->gamma==2):
							$colortext = 'bluetext';
						endif;
						if($element->gamma==3):
							$colortext = 'orangetext';
						endif;
						$pictopax = ($element->typeDestination == 2) ? 'picto-personnes-2.png' : 'picto-user.png';
						$person = ($element->typeDestination == 2) ? 'MOD_CAMPINGS_PER_PERS' : 'MOD_CAMPINGS_PER_APP';

					
						if($saison == 1):
							$prixhva = ModCampingsHelper::getInformationtablessiment($saison , $element->codeCRM, 'C005' , 'PrixHVA');
						else:
							$prixhva = ModCampingsHelper::getInformationtablessiment($saison , $element->codeCRM, 'C005' , 'Prix VAH');
						endif;

							

						if(isset($element->nom)):

							//PROMOTION
							/*echo '<pre>';
							print_r($promotions[$ipromo]);
							echo '</pre>';*/

							$printpromo =  ($i % 2 ? 0 : 1);
							if($printpromo ==1 AND $i!=0 AND $ipromo<$countpromotions):
									
									echo '<div class="slickContent PromotionClub '.$lang.'" >';
									if($promotions[$ipromo]->brochure == 0):

										//Miraremos el lang para cargar las variables de traducción o no. Estas se generan en la query(Helper) con un  join a Falang
										$nom = ($promotions[$ipromo]->nomtranslate!='') ? $promotions[$ipromo]->nomtranslate : $promotions[$ipromo]->nom;
										$subtitre = ($promotions[$ipromo]->subtitretranslate!='') ? $promotions[$ipromo]->subtitretranslate : $promotions[$ipromo]->subtitre;
										$tag = ($promotions[$ipromo]->tagtranslate!='') ? $promotions[$ipromo]->tagtranslate : $promotions[$ipromo]->tag;
										$alias = ($promotions[$ipromo]->aliastranslate!='') ? $promotions[$ipromo]->aliastranslate : $promotions[$ipromo]->alias;

										if(isset($nom)):
											if($mobile==0): 
												echo '<a href="'. $host .$lienlang.$alias;
											else:
												echo '<a  onclick="return false;" href=" ';
											endif;
							


											echo '" >';
												if($promotions[$ipromo]->imgpromotion !=''):
													$promotions[$ipromo]->img = $promotions[$ipromo]->imgpromotion;
												else:
													$promotions[$ipromo]->img = $hostimg.'images/landingpages/vignettes/'.$directorio.'/default.jpg';
												endif;
											echo '<img src="'.$hostimg.$promotions[$ipromo]->img.'" alt="'.$nom. '" title="'.$nom. '" />';
											echo '<span class="tag blueexdbackground blanctext">'.$tag.'</span>';
											echo '<p class="discont">'.$promotions[$ipromo]->minprix;
											if($promotions[$ipromo]->minprix!=''):
												//echo '<span>%</span>';
											endif;
											echo '</p>';
											echo '<div class="dernieretext">';
											if($nom!=''):
												echo '<p class="blanctext">'.$nom.'</p>';
												//echo '<h3 class="blanctext">'.$subtitre.'</h3>';
											endif;

											echo '</div>';

											//DIV HOVER PROMOTION

													
													echo '<div class="promotionhoverclub" >';


												
														
													echo '<div class="dernieretexthover">';
													if($nom!=''):
														echo '<p class="blanctext promotion-nom">'.$nom.'<span>'.$subtitre.'</span></p>';
														echo '<span class="tag blanctext">'.$tag.' '.$promotions[$ipromo]->minprix.'</span>';
														echo '<a href="'. $host .$lienlang.$alias.'" class="bluebackground blanctext text-center promobutton">'.JText::_('MOD_CAMPINGS_OFFERS_PROFITEZ').'</a>';
														

													endif;

													echo '</div>';


													echo '</div>';

											//DIV HOVER PROMOTION


											echo '</a>';
											
								
									    endif;
									else:
										// brochure
										echo '<div class="slickContent">';
											echo '<a href="'.$promotions[$ipromo]->lienbrochure.'" target="_blank" rel="noopener">';

												if($promotions[$ipromo]->imgpromotion !=''):
													$promotions[$ipromo]->img = $promotions[$ipromo]->imgpromotion;
												else:
													$promotions[$ipromo]->img = $hostimg.'images/landingpages/vignettes/'.$directorio.'/default.jpg';
												endif;
											echo '<img src="'.$hostimg.$promotions[$ipromo]->img.'" alt="'.$nom. '" title="'.$nom. '" />';
											echo '</a>';

									endif;
									echo '</div>';
									$ipromo++;

									
							endif;
							// FIN PROMOTION

							echo '<div class="slickContent" >';
								echo '<a href="'. $host .$lienlang. $element->alias;
								echo '" ';
								if($mobile==1): echo ' class=" blancbackground " '; endif;
								echo ' target="_blank" rel="noopener">';
								echo '<h3 class="blanctext">'.$element->ItmExtStationCommune.'</h3>';

									//$imgRut = '../../../images/etablessiments/vignettes/'.$element->alias.'.jpg';
									//$imgPath = $hostimg.'images/etablessiments/vignettes/'.$element->alias.'.jpg';

									$imgRut = '../../../images/etablissements/'.$element->alias.'/slide';
									$imgPath = $hostimg.'images/etablissements/'.$element->alias.'/slide';
									if (file_exists($imgRut)) :

													$ficheros  = scandir($imgRut);
															foreach ($ficheros as $key => $value)
															   {
																if( preg_match("/\.(png|gif|jpe?g|bmp)/",$value,$m)) {
																	if($value != 'index.html'):
																		$images[] = $value;
																		$imgsansextension = substr($value, 0, -4); //lien sans extension
																		$extension = substr_replace($value,'',0,-4); //extension
																	endif;

																}

															}


													
													$thumb = '/images/etablissements/'.$element->alias.'/thumbs/'.$imgsansextension.'_thumb_380x225'.$extension;
													
													if (!file_exists('../../../'.$thumb)) :
														$img = $hostimg.'images/etablissements/default.jpg';
														$thumb = $img;
													else: 
														$thumb = $hostimg.'images/etablissements/'.$element->alias.'/thumbs/'.$imgsansextension.'_thumb_380x225'.$extension;
													endif;
													$img = ($value=='..')? $hostimg.'images/etablissements/default.jpg' : $imgPath.'/'.$value;
													//$img = $imgPath.'/'.$value;
												else:
													$img = $hostimg.'images/etablissements/default.jpg';
													$thumb = $img;
												endif;

									

								echo '<div class="contentimg d-flex align-items-center">';
									echo '<img src="'.$thumb.'" alt="'.$element->nom. '" title="'.$element->nom. '" />';


								echo '</div>';


								// BLOQUE GAMMA ORIGINAL CRIS
								// echo '<div class="float-left categorieicon">';
								// 	if($element->typeDestination==2):

								// 		echo '<div class="bluelogo "><img class="d-block" src="'.$hostimg.'images/icons/hotel.png" alt="'.$element->nom. '" title="'.$element->nom. '" /></div>';
								// 	else:
								// 		echo '<div class="greenlogo rclub-logo "><img class="d-block" src="'.$hostimg.'images/icons/residenc.png" alt="'.$element->nom. '" title="'.$element->nom. '" /></div>';
								// 	endif;

								// 	if($element->etoiles!=''):
								// 			echo '<p class="text-center">';
								// 			for ($etoiles = 0; $etoiles < $element->etoiles; $etoiles++){echo '<span class="'.$colortext.'">*</span>'; }
								// 			echo '</p>';
								// 	endif;
								// echo '</div>';

									// BLOQUE GAMMA  || CREAR ICON, OLD. AHORA SERÁ IMAGEN
									/*echo '<div class="float-left categorieicon">';
										if($element->gamma==2):

											echo '<div><img class="d-block" src="'.$hostimg.'images/icons/hotel-club.png" alt="'.$element->nom. '" title="'.$element->nom. '" /></div>';
										elseif($element->gamma==3):
											echo '<div class="orangelogo rclub-logo "><img class="d-block" src="'.$hostimg.'images/icons/residenc.png" alt="'.$element->nom. '" title="'.$element->nom. '" /></div>';
										else:
											echo '<div class="rclub-logo "><img class="d-block" src="'.$hostimg.'images/icons/residence-club.png" alt="'.$element->nom. '" title="'.$element->nom. '" /></div>';
										endif;

										 if($element->etoiles!=''):
												echo '<p class="text-center">';
												for ($etoiles = 0; $etoiles < $element->etoiles; $etoiles++){echo '<span class="'.$colortext.'">*</span>'; }
												echo '</p>';
										endif; 
									echo '</div>';*/
									
									echo '<div class="float-left categorieicon">';
										if($element->categoryeicone!='0'):

											echo '<div><img class="d-block" src="'.$hostimg.$element->categoryeicone.'" alt="'.$element->nom. '" title="'.$element->nom. '" /></div>';
										
										endif;

										 
									echo '</div>';




								/*if($element->typeDestination==1):
											echo '<img class="categorieicon" src="'.$hostimg.'images/icons/residence-club.png" alt="'.$element->nom. '" title="'.$element->nom. '" />';
										endif;
										if($element->typeDestination==2):
											echo '<img class="categorieicon" src="'.$hostimg.'images/icons/hotel-club.png" alt="'.$element->nom. '" title="'.$element->nom. '" />';
										endif;
										if($element->typeDestination==3):
											echo '<img class="categorieicon" src="'.$hostimg.'images/icons/residence-club.png" alt="'.$element->nom. '" title="'.$element->nom. '" />';
										endif;	*/
								echo '<div class="dernieretext bluedtext ';
								if($mobile==1): echo ' pl-3 pr-3' ; endif;
								echo '">';
								if($element->nom!=''):
									echo '<h4 class="blueexdtext">'.$element->nom.'</h4>';
								endif;
								if($element->gamma!=''):
									echo '<p class="';
										//ORIGINAL CRIS
										// if($element->typeDestination==1):
										// 	echo 'greentext';
										// endif;
										// if($element->typeDestination==2):
										// 	echo 'bluetext';
										// endif;
										// if($element->typeDestination==3):
										// 	echo 'orangetext';
										// endif;

										if($element->gamma==1):
											echo 'greentext';
										endif;
										if($element->gamma==2):
											echo 'bluetext';
										endif;
										if($element->gamma==3):
											echo 'orangetext';
										endif;

										//echo '">'.$element->category.'</p>';
										echo '">'.$element->ItmExtGamme.'</p>';
										if($element->ExtSkiDomNom!=''):
											echo '<p class="blueexdtext">'.$element->ExtSkiDomNom.'<br/></p>';
										endif;
										/*$imgRut = '../../../images/logos/'.$element->alias.'.png';
										$imgPath = $hostimg.'images/logos/'.$element->alias.'.png';
										if (file_exists($imgRut)) :
											echo '<img src="'.$imgPath.'" alt="'.$element->nom. '" title="'.$element->nom. '" />';
										endif;*/


								endif;


								echo '</div>';
								echo '<div class="row blueexdtext ';
									if($mobile==1): echo ' pl-3 pr-3 pb-3' ; endif;
								echo '">';
											$logostation = ($element->logostationcomplet !='')? $element->logostationcomplet : $element->icon ;	
											if($logostation !=''):
												echo '<div class="col-3">';
												echo '<img class="logoheberg" src="'.$hostimg.$logostation.'" alt="'.$element->nom. '" title="'.$element->nom. '"  />';
												echo '</div>';
											endif;
											echo '<div class="col-5 d-flex align-items-center">';
												if(!empty($prixhva)):
												//echo '<img class="float-left p-2 " src="'.$hostimg.'images/icons/'.$pictopax.'" alt="'.$element->nom. '" title="'.$element->nom. '" />';
												echo '<img class="float-left picto-person p-2 " src="'.$hostimg.'images/icons/'.$pictopax.'" alt="'.$element->nom. '" title="'.$element->nom. '" />';
												echo '<div class="float-left clubprice"><p class="blueexdtext prix_font">'.JText::_('MOD_CAMPINGS_A_PARTIR_DE').'</p>';
												echo '<p class="blueexdtext"><span style="font-weight:900;">'.$prixhva[0]->ExtTarif.' €</span> '.JText::_($person).'</p></div>';
												endif;
												echo '<div class="continfotaxes">';
													echo '<img src="'.$hostimg.'images/icons/info.png" class="bulletinfotaxes">';
													echo '<div class="divinfotaxes">'.JText::_('MOD_CAMPINGS_TAXES_INFO').'</div>';
												echo '</div>';

											echo '</div>';
											//decouvrir

											echo '<div class="col-4 decouvrir_btn">';
												echo '<button type="button" class="blanctext blueexdbackground  btn mr-3">'.JText::_('MOD_CAMPINGS_DECOUVRIR').'</button>';
											echo '</div>';
									echo '</div>';



								echo '</a>';

								//DIV HOVER PROMOTION

									/*if($element->promotion!=0):

										$promotion = ModCampingsHelper::getPromotion($element->promotion);
										
										
										echo '<a class="promotionhoverclub" href="'. $host .$promotion->alias;


										echo '" >';
											if($promotion->imgpromotion !=''):
												$promotion->img = $promotion->imgpromotion;
											else:
												$promotion->img = $hostimg.'images/landingpages/vignettes/'.$directorio.'/default.jpg';
											endif;
										echo '<img src="'.$host.$promotion->img.'" alt="'.$promotion->nom. '" title="'.$promotion->nom. '" />';
										echo '<div class="dernieretext">';
										if($promotion->nom!=''):
											echo '<p class="blanctext">'.$promotion->nom.'<br/>'.$promotion->subtitre.'</p>';
											echo '<span class="tag blanctext">'.$promotion->tag.' '.$promotion->minprix.'</span>';
											echo '<p class="bluebackground blanctext text-center promobutton">'.JText::_('MOD_CAMPINGS_OFFERS_PROFITEZ').'</p>';
											

										endif;

										echo '</div>';


										echo '</a>';
									endif;*/
								//DIV HOVER PROMOTION
							echo '</div>';

							$i++;
							endif;
						endforeach;

				echo '</div>';


			echo '</div>';
		echo '</div>';



}

?>
<script>

	jQuery( document ).ready(function() {



		   jQuery('#clubs .offresSlick').slick({
	
			  infinite: true,
			  speed: 300,
			  slidesToShow: 3,
			  slidesToScroll: 1,
			  autoplay:true,
  			  autoplaySpeed:1500,
			  
			  variableWidth: true,
			  responsive: [
			    {
			      breakpoint: 1024,
			      settings: {
			        slidesToShow: 3,
			        slidesToScroll: 1,
			        infinite: true,
	
			      }
			    },
			    {
			      breakpoint: 990,
			      settings: {
			        slidesToShow: 2,
			        slidesToScroll:1
			      }
			    },
			    {
			      breakpoint: 640,
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
<style>
#clubs .offresSlick .slick-next, #clubs .offresSlick .slick-prev {
    display: block!important;
}
#clubs .offresSlick button:hover{
	background-color:#0f8fd1!important;
}
</style>
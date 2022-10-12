<?php 


// C'est le tableau de résultats
$result = array();
// Ces sont les informations sur les destinations standards
$zones = array('GDN','GDL','GDE','GDA','GSL','GDM','ORL','ROS');
$paris = array('GDN','GDL','GDE','GDA','GSL','GDM');

if(isset($_POST['depart']) && isset($_POST['arrivee']) && isset($_POST['datepk']) && isset($_POST['horaire'])){
					if(($_POST['depart'] != '') && ($_POST['arrivee'] != '') && ($_POST['datepk'] != '') && ($_POST['horaire'] != '')){
						$reponse = 'ok';

					$depart = $_POST['depart'];
					$arrivee = $_POST['arrivee'];
					$date = $_POST['datepk'];
					$horaire = $_POST['horaire'];

					$data = NULL;
					$is_zones = false;
					// On regarde dans les départements
					$dept_depart = substr($depart,0,2);
					$dept_arrivee = substr($arrivee,0,2);

						// Traitement
						
								// Access database using PDO.

								try {
										

										$conn = new PDO('mysql:host=db5003736621.hosting-data.io;dbname=dbs3048394','dbu1610506','bbACa9m9');
										$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

										// On choisit suivant les valeurs de seldepart et selarrivee
										// 
										if((!in_array($depart,$zones) && !in_array($arrivee, $zones)) || (in_array($depart,$zones) && !in_array($arrivee, $zones))){

											if(in_array($depart,$zones)){
													if(in_array($depart,$paris)){
														$dept_depart = '75';
													} else if($depart == 'ROS') {
														$dept_depart = '95';
													} else if ($depart == 'ORL') {
														$dept_depart = '94';
													}
											}

											$data = $conn->query('SELECT * FROM departements WHERE DEPT = '. $dept_depart );

											// Determiner le département d'arrivée	

										} else {
											// On regarde dans les villes
											$is_zones = true;
											$data = $conn->query('SELECT * FROM villes WHERE DEPT = '. $depart );
										}



										foreach ($data as $row) {

											$result = $row;
										}


										// Calcul du prix de base

										$result['depart'] = $depart;
										$result['arrivee'] = $arrivee;

										if($is_zones){
											$result['type'] = 'ville';

											switch ($arrivee) {
												case 'GDN':
													$result['prix'] = $result['GDN'];
													break;
												case 'GDL':
													$result['prix'] = $result['GDL'];
													break;
												case 'GDE':
													$result['prix'] = $result['GDE'];
													break;
												case 'GDA':
													$result['prix'] = $result['GDA'];
													break;
												case 'GSL':
													$result['prix'] = $result['GSL'];
													break;
												case 'GDM':
													$result['prix'] = $result['GDM'];
													break;
												case 'ORL':
													$result['prix'] = $result['ORL'];
													break;
												case 'ROS':
													$result['prix'] = $result['ROS'];
													break;
											}

										} else {
											$result['type'] = 'dept';
											$result['prix'] = $result['DEPT' . $dept_arrivee ];

										}
										
									// Les majorations
									
									// Si jour férié ou week end (sam/dim) + 50%
									// On ne peut réserver qu'entre 7h00 et 20h00
									// On récupère la date, on la compare aux jours féries 
									// 

									
									$splitHoraire = explode("-", $horaire);
									$majorations = $splitHoraire[1];

									switch ($majorations) {
								
									// Si réservation entre 6h00 et 8h00 : + 30%
									
									// SI réservation entre 22h00 et 6h00: + 50%
										case '30':
												$result['prix_final'] = $result['prix'] * 1.3;
											break;
											
										case '50':
												$result['prix_final'] = $result['prix'] * 1.5;
											break;

										default:
											$result['prix_final'] = $result['prix'];
											break;
									}
									
									
							
									
								} catch (PDOException  $e) {

									echo 'ERROR: ' . $e->getMessage();
									
								}

					} else {
						$reponse = 'Les champs sont vides';
						}
					} else {
					$reponse = 'Tous les champs ne sont pas parvenus';
				}

				echo json_encode($result);


 ?>
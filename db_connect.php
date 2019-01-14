<?php 
	/*
	* Connection à base de donnée
	*/
	/**
	 * 
	 */
	class Db_connect 
	{
		Private $hote = '192.168.23.128';
		Private $port = "3306";
		Private $nom_bdd = 'bdcaisseweb';
		// Private $nom_bdd = 'api_test';
		Private $utilisateur = 'root';
		Private $mot_de_passe ='djegDEV15';

		public function connect()
		{
			try {
					//header('Content-Type: application/json');
					//On test la connexion à la base de donnée
				    $pdo = new PDO('mysql:host='.$this->hote.';port='.$this->port.';dbname='.$this->nom_bdd, $this->utilisateur, $this->mot_de_passe);
				    $retour['success'] = true; 
				    $retour['message'] = "connexion à la base de la caisse web réussie";
				    // echo json_encode($retour);
				    return $pdo;

				} catch(Exception $e) {
					header('Content-Type: application/json');
					//Si la connexion n'est pas établie, on stop le chargement de la page.
					$retour['success'] = false; 
				    $retour['message'] = "Echec de la connexion à la base de données";
				    	echo json_encode($retour);
				    	exit();

				}

		}
	}

	// $db = new Db_connect;
	// $db->connect();
 ?>
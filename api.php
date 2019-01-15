<?php 
	

	class Api extends Rest{
		public $dbConn;
		public $hote = '192.168.23.128';
		public function __construct(){
			parent::__construct();
			$db = new Db_connect;
			$this->dbConn = $db->connect();
		}

		public function Secure_Key_Token(){
			
			$USR_LOGIN = $this->validateParameter('USR_LOGIN',$this->param['USR_LOGIN'],STRING);
			$USR_PASS = $this->validateParameter('USR_PASS',$this->param['USR_PASS'],STRING);
			
		try{
			$stmt = $this->dbConn->prepare("SELECT * FROM cw_utilisateur WHERE USR_LOGIN = :USR_LOGIN and USR_PASS = :USR_PASS");
		    $stmt->bindParam(':USR_LOGIN', $USR_LOGIN);
		    $stmt->bindParam(':USR_PASS', $USR_PASS);
		    $stmt->execute();
		    $user = $stmt->fetch(PDO::FETCH_ASSOC);
		    // $user = $stmt->fetchAll();
		    if (!is_array($user)) {
		    	$this->ReturnResponse(INVALID_USER_PASS,"Les identifiants renseignés ne sont pas correctes. ");
		    }
		    // print_r($user);

		    $payload = [
					    'iss' => 'localhost',
						'iat' => time(),
						'exp' => time() + (2000*60),
						'userId'=> $user['USR_ID']
						];
		     $token = JWT::encode($payload,SECRETE_KEY);
		     $data = ["token" => $token];

		    $this->ReturnResponse(SUCCESS_RESPONSE,$data);

		} catch (Exception $e) {
			$this->throwError(JWT_ERROR_PROCESSING, $e->getMessage());
		
		}

	}


	public function ListAllFactures(){

		try{
			 $token = $this->getBearerToken();
			 $payload = JWT::decode($token,SECRETE_KEY,['HS256']);

			$stmts = $this->dbConn->prepare("SELECT * FROM cw_sync_facture LIMIT 20");
		    $stmts->execute();
		    $Facture = $stmts->fetchAll(PDO::FETCH_ASSOC);
		    // var_dump($Facture);exit();
		    if (!is_array($Facture)) {
		    	$Message = "Aucunes factures";
		    	$define = REQUEST_NOT_VALID;
		    	$this->ReturnResponse($define,$Message);
		    }
		        // return $Facture;
		     if(count($Facture) > 0){

			    $Status['status'] = SUCCESS_RESPONSE; 
			    $Status['message'] = "Voici toutes les factures ";
			    $Status['success'] = true; 
			    $Status['resultat']['Nombre_fact'] = count($Facture);
			   

			    // echo json_encode($retour);

			}
		    $this->ReturnResponse($Status,$Facture);


		}catch (Exception $e) {
			$this->throwError(ACCESS_TOKEN_ERRORS, 'Le token a Expiré');
			
		}
	}


	public function FacturesClient(){
			
			$DET_REFDETTE = $this->validateParameter('DET_REFDETTE',$this->param['DET_REFDETTE'],STRING);
			
		try{
			$token = $this->getBearerToken();
			$payload = JWT::decode($token,SECRETE_KEY,['HS256']);

			// Requête (1)
			$requete1 = $this->dbConn->prepare("SELECT DET_REFABN FROM cw_sync_facture WHERE DET_REFDETTE = :DET_REFDETTE ");
		    $requete1->bindParam(':DET_REFDETTE', $DET_REFDETTE);
		    $requete1->execute();
		    $resultats1 = $requete1->fetch(PDO::FETCH_ASSOC);
		    // Requête (1)
		    $res1 = $resultats1["DET_REFABN"];

		    $requete2 = $this->dbConn->prepare("SELECT * FROM cw_sync_facture where DET_REFABN = $res1");
		    $requete2->execute();
		    $resultats2 = $requete2->fetchAll(PDO::FETCH_ASSOC);
		    // Requête (1)
		    if(count($resultats2) > 0){

			    $Status['status'] = SUCCESS_RESPONSE; 
			    $Status['message'] = "Voici les factures du client ".$resultats2[0]['DET_NOMPAYEUR'];
			    $Status['success'] = true; 
			    $Status['resultat']['Nombre_fact'] = count($resultats2);
			   

			    // echo json_encode($retour);

			}
		    	
		    if (!is_array($resultats2)) {
		    	$Message = "Aucunes factures clients trouvées";
		    	$define = REQUEST_NOT_VALID;
		    	$this->ReturnResponse($define,$Message);
		    }
		    
		    $this->ReturnResponse($Status,$resultats2);

		} catch (Exception $e) {
			$this->throwError(ACCESS_TOKEN_ERRORS, ' Le token a Expiré ');
			
		}

	}

	public function OneFacturesClient(){
			
			$DET_REFDETTE = $this->validateParameter('DET_REFDETTE',$this->param['DET_REFDETTE'],STRING);
			
		try{
			$token = $this->getBearerToken();
			$payload = JWT::decode($token,SECRETE_KEY,['HS256']);

			// Requête (1)
			$requete1 = $this->dbConn->prepare("SELECT * FROM cw_sync_facture WHERE DET_REFDETTE = :DET_REFDETTE ");
		    $requete1->bindParam(':DET_REFDETTE', $DET_REFDETTE);
		    $requete1->execute();
		    $resultats = $requete1->fetch(PDO::FETCH_ASSOC);
		    // Requête (1)
		    
		    // Requête (1)
		    if(count($resultats) > 0){

			    $Status['status'] = SUCCESS_RESPONSE; 
			    $Status['success'] = true; 
			    $Status['message'] = "Voici la facture du client ".$resultats['DET_NOMPAYEUR'];
			    
			   

			    // echo json_encode($retour);

			}
		    	
		    if (!is_array($resultats)) {
		    	$Message = "Aucunes factures clients trouvées";
		    	$define = REQUEST_NOT_VALID;
		    	$this->ReturnResponse($define,$Message);
		    }
		    
		    $this->ReturnResponse($Status,$resultats);

		} catch (Exception $e) {
			$this->throwError(ACCESS_TOKEN_ERRORS, ' Le token a Expiré ');
			
		}

	}


	public function SoldeClient(){
			
			$DET_REFDETTE = $this->validateParameter('DET_REFDETTE',$this->param['DET_REFDETTE'],STRING);
			
		try{
			$token = $this->getBearerToken();
			$payload = JWT::decode($token,SECRETE_KEY,['HS256']);

			// Requête (1)
			$requete1 = $this->dbConn->prepare("SELECT DET_REFABN FROM cw_sync_facture WHERE DET_REFDETTE = :DET_REFDETTE ");
		    $requete1->bindParam(':DET_REFDETTE', $DET_REFDETTE);
		    $requete1->execute();
		    $resultats1 = $requete1->fetch(PDO::FETCH_ASSOC);
		    // Requête (1)
		    $res1 = $resultats1["DET_REFABN"];

		    $requete2 = $this->dbConn->prepare("SELECT SUM(DET_RESTANTDU) as SoldeClient, DET_NOMPAYEUR as NomClient,DET_REFABN as NumeroAbonne FROM cw_sync_facture where DET_REFABN = $res1");
		    $requete2->execute();
		    $resultats2 = $requete2->fetch(PDO::FETCH_ASSOC);
		    // Requête (1)
		    if(count($resultats2) > 0){

			    $Status['status'] = SUCCESS_RESPONSE; 
			    $Status['success'] = true; 
			    $Status['message'] = "Voici le solde facture du client ".$resultats2['NomClient'];

			   

			    // echo json_encode($retour);

			}
		    	
		    if (!is_array($resultats2)) {
		    	$Message = "Aucunes solde client trouvées";
		    	$define = REQUEST_NOT_VALID;
		    	$this->ReturnResponse($define,$Message);
		    }
		    
		    $this->ReturnResponse($Status,$resultats2);

		} catch (Exception $e) {
			$this->throwError(ACCESS_TOKEN_ERRORS, ' Le token a Expiré ');
			
		}

	}



	/**************************** Section Encaissement ***************************/
	public function EncaissementFacturesClient(){
		try{
			/*
			* Debut de transaction
			*/
			$this->dbConn->beginTransaction();
			$token = $this->getBearerToken();
			$payload = JWT::decode($token,SECRETE_KEY,['HS256']);
			
			// $zzz =$this->dbConn->rollBack();
			// var_dump($zz);exit();
			/*
			* Debut de transaction
			*/
			//Initialisation des Propriétés
			$DET_REFDETTE = $this->validateParameter('DET_REFDETTE',$this->param['DET_REFDETTE'],STRING);
			$ENC_MONTANT = $this->validateParameter('ENC_MONTANT',$this->param['ENC_MONTANT'],INTEGER);



/*
* Création ou Ouverture de session
 */

// var_dump($payload->userId);exit();
$curl_session = curl_init();

curl_setopt_array($curl_session, array(
  CURLOPT_URL => $this->hote."/Cw_Application/Layers/Bibliotheques/PROCESS_CW/index.php?r=Session/Session/OuvrirSessionCaisse&USR_ID=".$payload->userId,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_POSTFIELDS => "",

));

$response_session = curl_exec($curl_session);
$err_session = curl_error($curl_session);

curl_close($curl_session);

if ($err_session) {
  echo "cURL Error #:" . $err_session;
} else {
	header('Content-Type: application/json');
 	$response_sessions = json_decode($response_session); 
    $Caisse_Id = $response_sessions->CAIS_ID;

      $Status['Session'] = "Ouverture de session pour cette opération";
	  $Status['nombre_success'] += 1;

}

//var_dump($Caisse_Id);exit();

/*
* Création de session
 */



/*
* Récupération du montant du timbre
 */


$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $this->hote."/Cw_Application/Layers/Bibliotheques/PROCESS_CW/index.php?r=Encaissement/Timbre/Calcul&MONTANT=".$ENC_MONTANT,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_POSTFIELDS => "",

));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
	header('Content-Type: application/json');
 	$reponses = json_decode($response); 
    $Timbre = $reponses->VAL_TBRE;
}
// exit();
/*
* Récupération du montant du timbre
 */





	// $chemin_access = $this->hote."/Cw_Application/Layers/Bibliotheques/PROCESS_CW/index.php?r=Encaissement/Timbre/Calcul&MONTANT=".$ENC_MONTANT;
	// 	var_dump($chemin_access);exit();
			// Par défauts -Automatique 
			$ENC_ID = NULL ;
			$CAIS_ID = $Caisse_Id ;
			$ENC_FRAIS = $Timbre ;
			$ENC_NUMERO_CHEQUE = 0 ;
			$ENC_MONTANT_SUP = 0 ;
			$ENC_ECART = NULL ;
			$ENC_TAUX = NULL ;
			$ENC_KEY_EGEE = 0;
			$ENC_COMMENTAIRE = 0;
			$ENC_DATE = date("Y-m-d H:i");
			$ENC_MONNAIE = 0 ;
			$MODEREG_ID = 14 ;
			$TYPENC_ID = 1038 ;
			$ENC_SYNCHRO = 0 ;
			$ENC_ID_EGEE = 0 ;
			$ENC_ID_ORIGINE = 1;
			$ENC_RDU_APR = 0;
			// Par défauts

			
			
			
			$token = $this->getBearerToken();
			$payload = JWT::decode($token,SECRETE_KEY,['HS256']);
			// var_dump($payload->userId);exit();

			// Requête (1)
			$requete1 = $this->dbConn->prepare("SELECT * FROM cw_sync_facture WHERE DET_REFDETTE = :DET_REFDETTE ");
		    $requete1->bindParam(':DET_REFDETTE', $DET_REFDETTE);
		    $requete1->execute();
		    $resultats1 = $requete1->fetch(PDO::FETCH_ASSOC);

		       // Status requête
		    if(count($resultats1) > 0){
			    $Status['nombre_success'] = 1;
			    $Status['Facture'] = "Facture trouvée";
			    // $this->ReturnResponse($Status,$resultats1);
			}else{
				 // $Status['status'] = REQUEST_NOT_VALID; 
			     $Status['Nombre_erreur'] += 1;
			     $Status['Facture'] = "Facture non trouvée";
			}
		    	
		    // Requête (1)
		     $DET_RESTANTDU =  floatval($resultats1['DET_RESTANTDU']);
		     // var_dump($DET_RESTANTDU);exit();
		     /*
		     * Conditions pour eviter les montants supérieures au restantdu
		      */
		    if($ENC_MONTANT > $DET_RESTANTDU){
			    $Status['Erreur'] = 802;
			    $msg = "Le montant à régler est supérieure au restantdu";
			    $this->dbConn->rollBack();
			    $this->ReturnResponse($Status,$msg);
			}
			if ($ENC_MONTANT<=0) {
				$Status['Erreur'] = 805;
			    $msg = "Le montant à régler est trop peu";
			    $this->dbConn->rollBack();
			    $this->ReturnResponse($Status,$msg);
			}
		     /*
		     * Conditions pour eviter les montants supérieures au restantdu
		      */
		    
		     // Provenant d'une facture
			     // Calcul de l'encaissement
				$Montant_regler = $DET_RESTANTDU-$ENC_MONTANT;
			     // Calcul de l'encaissement
				$CLI_ID = $resultats1['CLI_ID'] ;
				$ENC_RDU_AVT = $ENC_MONTANT;
			// Provenant d'une facture

		// cw_encaissement
				 $requete_cw_encaissement = $this->dbConn->prepare("INSERT INTO  cw_encaissement (ENC_ID,CAIS_ID,ENC_DATE,ENC_MONTANT,ENC_NUMERO_CHEQUE,ENC_MONNAIE,ENC_MONTANT_SUP,ENC_ECART,ENC_TAUX,MODEREG_ID,TYPENC_ID,ENC_FRAIS,ENC_SYNCHRO,ENC_ID_EGEE,ENC_KEY_EGEE,ENC_COMMENTAIRE,ENC_ID_ORIGINE,ENC_RDU_APR,ENC_RDU_AVT)
			 VALUES (NULL,:CAIS_ID,:ENC_DATE,:ENC_MONTANT,:ENC_NUMERO_CHEQUE,:ENC_MONNAIE,:ENC_MONTANT_SUP,:ENC_ECART,:ENC_TAUX,:MODEREG_ID,:TYPENC_ID,:ENC_FRAIS,:ENC_SYNCHRO,:ENC_ID_EGEE,:ENC_KEY_EGEE,:ENC_COMMENTAIRE,:ENC_ID_ORIGINE,:ENC_RDU_APR,:ENC_RDU_AVT)");

			// $requete_cw_encaissement->bindParam(':ENC_ID',$ENC_ID);
			$requete_cw_encaissement->bindParam(':CAIS_ID',$CAIS_ID);
			$requete_cw_encaissement->bindParam(':ENC_DATE',$ENC_DATE);
			// var_dump($ENC_DATE);exit();
			$requete_cw_encaissement->bindParam(':ENC_FRAIS',$ENC_FRAIS);
			$requete_cw_encaissement->bindParam(':ENC_NUMERO_CHEQUE',$ENC_NUMERO_CHEQUE);
			$requete_cw_encaissement->bindParam(':ENC_MONTANT_SUP',$ENC_MONTANT_SUP);
			$requete_cw_encaissement->bindParam(':ENC_ECART',$ENC_ECART);
			$requete_cw_encaissement->bindParam(':ENC_TAUX',$ENC_TAUX);
			$requete_cw_encaissement->bindParam(':ENC_KEY_EGEE',$ENC_KEY_EGEE);
			$requete_cw_encaissement->bindParam(':ENC_COMMENTAIRE',$ENC_COMMENTAIRE);
			$requete_cw_encaissement->bindParam(':ENC_MONNAIE',$ENC_MONNAIE);
			$requete_cw_encaissement->bindParam(':MODEREG_ID',$MODEREG_ID);
			$requete_cw_encaissement->bindParam(':TYPENC_ID',$TYPENC_ID);
			$requete_cw_encaissement->bindParam(':ENC_SYNCHRO',$ENC_SYNCHRO);
			$requete_cw_encaissement->bindParam(':ENC_ID_EGEE',$ENC_ID_EGEE);
			$requete_cw_encaissement->bindParam(':ENC_ID_ORIGINE',$ENC_ID_ORIGINE);
			$requete_cw_encaissement->bindParam(':ENC_RDU_APR',$ENC_RDU_APR);
			// Provenant d'une facture
			$requete_cw_encaissement->bindParam(':ENC_MONTANT',$ENC_MONTANT);
			// $requete2->bindParam(':CLI_ID',$CLI_ID);
			$requete_cw_encaissement->bindParam(':ENC_RDU_AVT',$ENC_RDU_AVT);
			// Provenant d'une facture
			
		   $resultat_encaissement1= $requete_cw_encaissement->execute();
		   // var_dump($resultat_encaissement1);exit();


		    if($resultat_encaissement1){

			    // $Status['status'] = SUCCESS_RESPONSE; 
			    $Status['cw_encaissement'] = "cw_encaissement remplie ";
			    $Status['nombre_success'] += 1;
			    // $this->ReturnResponse($Status,$resultats1);
			}else{
				 // $Status['status'] = REQUEST_NOT_VALID; 
			     $Status['cw_encaissement'] = "cw_encaissement non remplie";
			     $Status['Nombre_erreur'] += 1;
			}
		    // $ENC_ID = $this->ENC_ID;/**/
		    $ENC_ID = $this->dbConn->lastInsertId();
		   //var_dump($ENC_ID);
		   //exit();
		// cw_encaissement
		

				
		// cw_sync_encaissement
		    $requete2 = $this->dbConn->prepare("INSERT INTO  cw_sync_encaissement (ENC_ID,CAIS_ID,ENC_DATE,ENC_MONTANT,ENC_NUMERO_CHEQUE,ENC_MONNAIE,ENC_MONTANT_SUP,ENC_ECART,ENC_TAUX,MODEREG_ID,TYPENC_ID,ENC_FRAIS,ENC_SYNCHRO,ENC_ID_EGEE,ENC_KEY_EGEE,ENC_COMMENTAIRE,ENC_ID_ORIGINE,ENC_RDU_APR,CLI_ID,ENC_RDU_AVT)
			 VALUES (NULL,:CAIS_ID,:ENC_DATE,:ENC_MONTANT,:ENC_NUMERO_CHEQUE,:ENC_MONNAIE,:ENC_MONTANT_SUP,:ENC_ECART,:ENC_TAUX,:MODEREG_ID,:TYPENC_ID,:ENC_FRAIS,:ENC_SYNCHRO,:ENC_ID_EGEE,:ENC_KEY_EGEE,:ENC_COMMENTAIRE,:ENC_ID_ORIGINE,:ENC_RDU_APR,:CLI_ID,:ENC_RDU_AVT)");

			$requete2->bindParam(':CAIS_ID',$CAIS_ID);
			$requete2->bindParam(':CAIS_ID',$CAIS_ID);
			$requete2->bindParam(':ENC_DATE',$ENC_DATE);
			// var_dump($ENC_DATE);exit();
			$requete2->bindParam(':ENC_FRAIS',$ENC_FRAIS);
			$requete2->bindParam(':ENC_NUMERO_CHEQUE',$ENC_NUMERO_CHEQUE);
			$requete2->bindParam(':ENC_MONTANT_SUP',$ENC_MONTANT_SUP);
			$requete2->bindParam(':ENC_ECART',$ENC_ECART);
			$requete2->bindParam(':ENC_TAUX',$ENC_TAUX);
			$requete2->bindParam(':ENC_KEY_EGEE',$ENC_KEY_EGEE);
			$requete2->bindParam(':ENC_COMMENTAIRE',$ENC_COMMENTAIRE);
			$requete2->bindParam(':ENC_MONNAIE',$ENC_MONNAIE);
			$requete2->bindParam(':MODEREG_ID',$MODEREG_ID);
			$requete2->bindParam(':TYPENC_ID',$TYPENC_ID);
			$requete2->bindParam(':ENC_SYNCHRO',$ENC_SYNCHRO);
			$requete2->bindParam(':ENC_ID_EGEE',$ENC_ID_EGEE);
			$requete2->bindParam(':ENC_ID_ORIGINE',$ENC_ID_ORIGINE);
			$requete2->bindParam(':ENC_RDU_APR',$ENC_RDU_APR);
			// Provenant d'une facture
			$requete2->bindParam(':ENC_MONTANT',$ENC_MONTANT);
			$requete2->bindParam(':CLI_ID',$CLI_ID);
			$requete2->bindParam(':ENC_RDU_AVT',$ENC_RDU_AVT);
			// Provenant d'une facture
			
		  $resultat_cw_sync_encaissement = $requete2->execute();

		     if($resultat_cw_sync_encaissement){

			    // $Status['status'] = SUCCESS_RESPONSE; 
			    $Status['nombre_success'] += 1;
			    $Status['cw_sync_encaissement'] = "cw_sync_encaissement remplie ";
			    // $this->ReturnResponse($Status,$resultat_cw_sync_encaissement);
			}else{
				 // $Status['status'] = REQUEST_NOT_VALID; 
			     $Status['Nombre_erreur'] += 1;
			     $Status['cw_sync_encaissement'] = "cw_sync_encaissement non remplie";
			}
		    // var_dump($req2);exit();
			$REG_ID = NULL;
	
			//var_dump($ENC_ID);exit();
			$IRO_EXERCICE = $resultats1['IRO_EXERCICE'];
			$IRO_NUMERO = $resultats1['IRO_NUMERO'];
			$IRO_NUMERODANSROLE = $resultats1['IRO_NUMERODANSROLE'];
			$REG_MONTANT = $ENC_MONTANT;
			$REG_MULTI = 0;
			$REG_BORDEREAU = 0;
			$REG_COMMENTAIRE = NULL;
			// cw_sync_encaissement

			// Verfication avant insertion 
			$requeteVerif = $this->dbConn->prepare("SELECT * FROM cw_identificationdette WHERE DET_REFDETTE = :DET_REFDETTE ");
		    $requeteVerif->bindParam(':DET_REFDETTE', $DET_REFDETTE);
		   $Verif_identificationdette = $requeteVerif->execute();
		    $resultatsVerif = $requeteVerif->fetch(PDO::FETCH_ASSOC);
		    // var_dump(count($resultatsVerif) );exit();

		    // Requête (1)
		     $verifIRO_EXERCICE =  $resultatsVerif['IRO_EXERCICE'];
		     $verifIRO_NUMERO=  $resultatsVerif['IRO_NUMERO'];
		     $verifIRO_NUMERODANSROLE=  $resultatsVerif['IRO_NUMERODANSROLE'];
		      // var_dump($verifIRO_NUMERODANSROLE);exit();
		     $montant_mis_a_jour = floatval($resultatsVerif['DET_RESTANTDU'])-$ENC_MONTANT;


		     $countFact = $this->dbConn->prepare("SELECT count(*) as Num_fact FROM cw_identificationdette WHERE DET_REFDETTE = :DET_REFDETTE ");
		    $countFact->bindParam(':DET_REFDETTE', $DET_REFDETTE);
		   $Verif_identificationdette = $countFact->execute();
		    $resultatscountFact = $countFact->fetch(PDO::FETCH_ASSOC);
		    // var_dump($resultatscountFact['Num_fact']);exit();
		     $nombre_facture_enregistrer_dans_cw_identificationdette =floatval($resultatscountFact['Num_fact']);
		     // // Code de verif 
		    // echo $nombre_facture_enregistrer_dans_cw_identificationdette;
		     // var_dump($nombre_facture_enregistrer_dans_cw_identificationdette);exit();
		     if ($nombre_facture_enregistrer_dans_cw_identificationdette == 0) {
		     	# code...
		    
		     // Code de verif 
			// Verfication avant insertion 
			// Cw_identificationdette
			 $requete6 = $this->dbConn->prepare("INSERT INTO  cw_identificationdette (IRO_EXERCICE,IRO_NUMERO,IRO_NUMERODANSROLE,DET_REFDETTE,DET_DATEEMISSION,DET_RESTANTDU,DET_CODEMONNAIE,DET_DATEMISEAJOUR,DET_REFBORD,DET_REFABN,DET_MNTTC,DET_REFPAYEUR,DET_NOMPAYEUR,DET_NUMCPT,DET_INSID,DET_ADRESSE)
			 VALUES (:IRO_EXERCICE,:IRO_NUMERO,:IRO_NUMERODANSROLE,:DET_REFDETTE,:DET_DATEEMISSION,:DET_RESTANTDU,:DET_CODEMONNAIE,:DET_DATEMISEAJOUR,:DET_REFBORD,:DET_REFABN,:DET_MNTTC,:DET_REFPAYEUR,:DET_NOMPAYEUR,:DET_NUMCPT,:DET_INSID,:DET_ADRESSE)");

			$requete6->bindParam(':IRO_EXERCICE',$resultats1['IRO_EXERCICE']);
			$requete6->bindParam(':IRO_NUMERO',$resultats1['IRO_NUMERO']);
			$requete6->bindParam(':IRO_NUMERODANSROLE',$resultats1['IRO_NUMERODANSROLE']);
			$requete6->bindParam(':DET_REFDETTE',$resultats1['DET_REFDETTE']);
			$requete6->bindParam(':DET_DATEEMISSION',$ENC_DATE);
			$requete6->bindParam(':DET_RESTANTDU',$Montant_regler);
			$requete6->bindParam(':DET_CODEMONNAIE',$resultats1['DET_CODEMONNAIE']);
			$requete6->bindParam(':DET_DATEMISEAJOUR',$ENC_DATE);
			$requete6->bindParam(':DET_REFBORD',$resultats1['DET_REFBORD']);
			$requete6->bindParam(':DET_REFABN',$resultats1['DET_REFABN']);
			$requete6->bindParam(':DET_MNTTC',$ENC_MONTANT);
			$requete6->bindParam(':DET_REFPAYEUR',$resultats1['DET_REFPAYEUR']);
			$requete6->bindParam(':DET_NOMPAYEUR',$resultats1['DET_NOMPAYEUR']);
			$requete6->bindParam(':DET_NUMCPT',$resultats1['DET_NUMCPT']);
			$requete6->bindParam(':DET_INSID',$resultats1['DET_INSID']);
			$requete6->bindParam(':DET_ADRESSE',$resultats1['DET_ADRESSE']);
			
			// Provenant d'une facture
			
		    $Req6 = $requete6->execute();
		    if($Req6){

			    // $Status['status'] = SUCCESS_RESPONSE; 
			    // $Status['message'] = "cw_identificationdette remplie ";
			    $Status['nombre_success'] += 1;
			     $Status['insert_cw_identificationdette'] = 'cw_identificationdette remplie ';
			    // $this->ReturnResponse($Status,$Req6);
			}else{
				 // $Status['status'] = REQUEST_NOT_VALID; 
			     $Status['Nombre_erreur'] += 1;
			     $Status['insert_cw_identificationdette'] = "cw_identificationdette non remplie";
			}
			// var_dump($Req6);exit();

		    // Suite du code de verif 
		     }else{
		     	$requete_ = $this->dbConn->prepare("update cw_identificationdette set DET_RESTANTDU=$montant_mis_a_jour,DET_DATEMISEAJOUR='$ENC_DATE',DET_MNTTC=$ENC_MONTANT where 
		     		IRO_EXERCICE = $verifIRO_EXERCICE AND 
		     		IRO_NUMERO = $verifIRO_NUMERO AND 
		     		IRO_NUMERODANSROLE = $verifIRO_NUMERODANSROLE ");

		     		$Req_ = $requete_->execute();
		     		if($Req_){

			    // $Status['status'] = SUCCESS_RESPONSE; 
			    // $Status['message'] = "cw_identificationdette remplie ";
			    $Status['nombre_success'] += 1;
			    $Status['update_cw_identificationdette'] = 'Mise à jour effectuée';
			    // $this->ReturnResponse($Status,$Req_);
			}else{
				 // $Status['status'] = REQUEST_NOT_VALID; 
			     $Status['Nombre_erreur'] += 1;
			     $Status['update_cw_identificationdette'] = "Mise à jour non effectuée";
			}
		     		// var_dump($Req_);exit();
		     }
		    // Suite du code de verif 

			
     // Cw_syn_facture
		     		$requete_Facture = $this->dbConn->prepare("update cw_sync_facture set DET_RESTANTDU=$Montant_regler,DET_DATEMISEAJOUR='$ENC_DATE',DET_MNTTC=$ENC_MONTANT where 
		     		IRO_EXERCICE = $IRO_EXERCICE AND 
		     		IRO_NUMERO = $IRO_NUMERO AND 
		     		IRO_NUMERODANSROLE = $IRO_NUMERODANSROLE ");

		     		$Req_Facture = $requete_Facture->execute();
		     			if($Req_Facture){

			    // $Status['status'] = SUCCESS_RESPONSE; 
			    // $Status['message'] = "cw_identificationdette remplie ";
			    $Status['nombre_success'] += 1;
			    $Status['cw_sync_facture'] = 'Mise à jour effectuée';
			    // $this->ReturnResponse($Status,$Req_);
			}else{
			 
			     $Status['Nombre_erreur'] += 1;
			      $Status['cw_sync_facture'] = 'Mise à jour non effectuée';
			}
		     		//var_dump($Req_Facture);exit();

			// Cw_syn_facture


		
			// Cw_identificationdette


		     	// Cw_sync_reglement
		       $requete3 = $this->dbConn->prepare("
		       	INSERT INTO  cw_reglement (REG_ID,ENC_ID,IRO_EXERCICE,IRO_NUMERO,IRO_NUMERODANSROLE,REG_MONTANT,REG_MULTI,REG_BORDEREAU)
			 VALUES (NULL,$ENC_ID,$IRO_EXERCICE,$IRO_NUMERO,$IRO_NUMERODANSROLE,$REG_MONTANT,$REG_MULTI,$REG_BORDEREAU)");

			// var_dump($requete3);exit();
			// $requete3->bindParam(':REG_ID',$REG_ID);
			// $requete3->bindParam(':ENC_ID',$ENC_ID);
			// $requete3->bindParam(':IRO_EXERCICE',$verifIRO_EXERCICE);
			// $requete3->bindParam(':IRO_NUMERO',$verifIRO_NUMERO);
			// $requete3->bindParam(':IRO_NUMERODANSROLE',$verifIRO_NUMERODANSROLE);
			// $requete3->bindParam(':REG_MONTANT',$REG_MONTANT);
			// $requete3->bindParam(':REG_MULTI',$REG_MULTI);
			// $requete3->bindParam(':REG_BORDEREAU',$REG_BORDEREAU);
			
			// Provenant d'une facture
			
		    $Req3 = $requete3->execute();
		    // var_dump($Req3);
		    	if($Req3){
			    $Status['nombre_success'] += 1;
			     $Status['cw_reglement'] = 'remplie';
				}else{		 
				     $Status['Nombre_erreur'] += 1;
				      $Status['cw_reglement'] = 'non remplie';
				}	
			     		

		     	// Cw_reglement


		     	// Cw_sync_reglement
		    $requete5 = $this->dbConn->prepare("INSERT INTO  cw_sync_reglement (REG_ID,ENC_ID,IRO_EXERCICE,IRO_NUMERO,IRO_NUMERODANSROLE,REG_MONTANT,REG_MULTI,REG_BORDEREAU,REG_COMMENTAIRE)
			 VALUES (:REG_ID,:ENC_ID,:IRO_EXERCICE,:IRO_NUMERO,:IRO_NUMERODANSROLE,:REG_MONTANT,:REG_MULTI,:REG_BORDEREAU,:REG_COMMENTAIRE)");

			$requete5->bindParam(':REG_ID',$ENC_ID);
			// var_dump($ENC_ID);exit();
			$requete5->bindParam(':ENC_ID',$ENC_ID);
			$requete5->bindParam(':IRO_EXERCICE',$IRO_EXERCICE);
			$requete5->bindParam(':IRO_NUMERO',$IRO_NUMERO);
			$requete5->bindParam(':IRO_NUMERODANSROLE',$IRO_NUMERODANSROLE);
			$requete5->bindParam(':REG_MONTANT',$REG_MONTANT);
			$requete5->bindParam(':REG_MULTI',$REG_MULTI);
			$requete5->bindParam(':REG_BORDEREAU',$REG_BORDEREAU);
			$requete5->bindParam(':REG_COMMENTAIRE',$REG_COMMENTAIRE);
			
			// Provenant d'une facture
			
		    $Req5 = $requete5->execute();
				if($Req5){
			    $Status['nombre_success'] += 1;
			     $Status['cw_sync_reglement'] = 'remplie';
				}else{		 
				     $Status['Nombre_erreur'] += 1;
				     $Status['cw_sync_reglement'] = 'non remplie';
				}
		        // var_dump($Req5);
		     	// Cw_sync_reglement

		     $rec_contenu = "Encaissement:".$ENC_MONTANT."Facture:".$DET_REFDETTE."timbre:".$ENC_FRAIS;
		     $REC_NUMERO = date("Y").date('m').date('d').date('s').date('s');
		     // var_dump($rec_contenu);exit();
		     // Cw_recu
		    $requete_recu = $this->dbConn->prepare("INSERT INTO  cw_recu (REC_ID,rec_contenu,REC_NUMERO,ENC_ID)
			 VALUES (NULL,:rec_contenu,:REC_NUMERO,:ENC_ID)");

			// var_dump($ENC_ID);exit();
			$requete_recu->bindParam(':ENC_ID',$ENC_ID);
			$requete_recu->bindParam(':rec_contenu',$rec_contenu);
			$requete_recu->bindParam(':REC_NUMERO',$REC_NUMERO);
	
		
			// Provenant d'une facture
			
		    $Req_recu = $requete_recu->execute();
		    if($Req_recu){
			    $Status['nombre_success'] += 1;
			       $Status['message_success'] = 'Recu générer';
				}else{		 
				     $Status['Recu non générer'] += 1;
				     $Status['message_erreur'] = 'Recu non générer';
				    //  $this->dbConn->rollBack();
				    // $this->ReturnResponse($Status,"--- Processus intérrompu ---"); 
				}


/*
*   Fermeture de session
 */


$curl_Femerture_session = curl_init();

curl_setopt_array($curl_Femerture_session, array(
  CURLOPT_URL => $this->hote."/Cw_Application/Layers/Bibliotheques/PROCESS_CW/index.php?r=Session/Session/FermerSessionCaisse&CAIS_ID=".$Caisse_Id,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_POSTFIELDS => "",
  CURLOPT_HTTPHEADER => array(
    "Postman-Token: 56d0beab-4123-4632-b388-b63694a9fff6",
    "cache-control: no-cache"
  ),
));

$response_Femerture_session = curl_exec($curl_Femerture_session);
$err_Femerture_session = curl_error($curl_Femerture_session);

curl_close($curl_Femerture_session);

if ($err_Femerture_session) {
  echo "cURL Error #:" . $err_Femerture_session;
} else {
  // echo $response_Femerture_session;
  // 
}
/*
*   Fermeture de session
 */
				if ($Status['nombre_success'] == 8) {
					$this->dbConn->commit();
					 $Status['Commit'] = 'Sauvegarde effectuer';
				}
				else if ($Status['nombre_success'] <= 7) {
					$this->dbConn->rollBack();
					$Status['rollBack'] = 'Sauvegarde non effectuer';
				}
				else{
					$this->dbConn->rollBack();
					$Status['rollBack'] = 'Sauvegarde non effectuer';
				}
				$this->ReturnResponse($Status,"--- Fin du traitements ---");
		
  

		} catch (Exception $e) {
			$this->throwError(ACCESS_TOKEN_ERRORS, ' Le token a Expiré ');
			$this->dbConn->rollBack();
			
		}


	}
	/**************************** Section Encaissement ***************************/




}

 ?>
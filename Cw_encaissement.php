<?php
/* **********************************************************
Cette classe est construite à l'image de la table Cw_encaissement
********************************************************** */
class Cw_encaissement {
	private $ENC_ID ;
	private $CAIS_ID ;
	private $ENC_DATE ;
	private $ENC_MONTANT ;
	private $ENC_NUMERO_CHEQUE ;
	private $ENC_MONNAIE ;
	private $ENC_MONTANT_SUP ;
	private $ENC_ECART ;
	private $ENC_TAUX ;
	private $MODEREG_ID ;
	private $TYPENC_ID ;
	private $ENC_FRAIS ;
	private $ENC_SYNCHRO ;
	private $ENC_ID_EGEE ;
	private $ENC_KEY_EGEE ;
	private $ENC_COMMENTAIRE ;
	private $ENC_ID_ORIGINE ;
	private $ENC_RDU_APR ;
//	private $CLI_ID ;
	private $ENC_RDU_AVT ;
	// Rajout
	private $dbConn;
	// Rajout


	function __construct  ($ENC_ID,$CAIS_ID,$ENC_DATE,$ENC_MONTANT,$ENC_NUMERO_CHEQUE,$ENC_MONNAIE,$ENC_MONTANT_SUP,$ENC_ECART,$ENC_TAUX,$MODEREG_ID,$TYPENC_ID,$ENC_FRAIS,$ENC_SYNCHRO,$ENC_ID_EGEE,$ENC_KEY_EGEE,$ENC_COMMENTAIRE,$ENC_ID_ORIGINE,$ENC_RDU_APR,$ENC_RDU_AVT) {
		$this->ENC_ID = $ENC_ID ;
		$this->CAIS_ID = $CAIS_ID ;
		$this->ENC_DATE = $ENC_DATE ;
		$this->ENC_MONTANT = $ENC_MONTANT ;
		$this->ENC_NUMERO_CHEQUE = $ENC_NUMERO_CHEQUE ;
		$this->ENC_MONNAIE = $ENC_MONNAIE ;
		$this->ENC_MONTANT_SUP = $ENC_MONTANT_SUP ;
		$this->ENC_ECART = $ENC_ECART ;
		$this->ENC_TAUX = $ENC_TAUX ;
		$this->MODEREG_ID = $MODEREG_ID ;
		$this->TYPENC_ID = $TYPENC_ID ;
		$this->ENC_FRAIS = $ENC_FRAIS ;
		$this->ENC_SYNCHRO = $ENC_SYNCHRO ;
		$this->ENC_ID_EGEE = $ENC_ID_EGEE ;
		$this->ENC_KEY_EGEE = $ENC_KEY_EGEE ;
		$this->ENC_COMMENTAIRE = $ENC_COMMENTAIRE ;
		$this->ENC_ID_ORIGINE = $ENC_ID_ORIGINE ;
		$this->ENC_RDU_APR = $ENC_RDU_APR ;
		//$this->CLI_ID = $CLI_ID ;
		$this->ENC_RDU_AVT = $ENC_RDU_AVT ;
	}
	function getENC_ID() { return $this->ENC_ID ; }
	function getCAIS_ID() { return $this->CAIS_ID ; }
	function getENC_DATE() { return $this->ENC_DATE ; }
	function getENC_MONTANT() { return $this->ENC_MONTANT ; }
	function getENC_NUMERO_CHEQUE() { return $this->ENC_NUMERO_CHEQUE ; }
	function getENC_MONNAIE() { return $this->ENC_MONNAIE ; }
	function getENC_MONTANT_SUP() { return $this->ENC_MONTANT_SUP ; }
	function getENC_ECART() { return $this->ENC_ECART ; }
	function getENC_TAUX() { return $this->ENC_TAUX ; }
	function getMODEREG_ID() { return $this->MODEREG_ID ; }
	function getTYPENC_ID() { return $this->TYPENC_ID ; }
	function getENC_FRAIS() { return $this->ENC_FRAIS ; }
	function getENC_SYNCHRO() { return $this->ENC_SYNCHRO ; }
	function getENC_ID_EGEE() { return $this->ENC_ID_EGEE ; }
	function getENC_KEY_EGEE() { return $this->ENC_KEY_EGEE ; }
	function getENC_COMMENTAIRE() { return $this->ENC_COMMENTAIRE ; }
	function getENC_ID_ORIGINE() { return $this->ENC_ID_ORIGINE ; }
	function getENC_RDU_APR() { return $this->ENC_RDU_APR ; }
	//function getCLI_ID() { return $this->CLI_ID ; }
	function getENC_RDU_AVT() { return $this->ENC_RDU_AVT ; }

	function setENC_ID($value) { $this->ENC_ID = $value ; }
	function setCAIS_ID($value) { $this->CAIS_ID = $value ; }
	function setENC_DATE($value) { $this->ENC_DATE = $value ; }
	function setENC_MONTANT($value) { $this->ENC_MONTANT = $value ; }
	function setENC_NUMERO_CHEQUE($value) { $this->ENC_NUMERO_CHEQUE = $value ; }
	function setENC_MONNAIE($value) { $this->ENC_MONNAIE = $value ; }
	function setENC_MONTANT_SUP($value) { $this->ENC_MONTANT_SUP = $value ; }
	function setENC_ECART($value) { $this->ENC_ECART = $value ; }
	function setENC_TAUX($value) { $this->ENC_TAUX = $value ; }
	function setMODEREG_ID($value) { $this->MODEREG_ID = $value ; }
	function setTYPENC_ID($value) { $this->TYPENC_ID = $value ; }
	function setENC_FRAIS($value) { $this->ENC_FRAIS = $value ; }
	function setENC_SYNCHRO($value) { $this->ENC_SYNCHRO = $value ; }
	function setENC_ID_EGEE($value) { $this->ENC_ID_EGEE = $value ; }
	function setENC_KEY_EGEE($value) { $this->ENC_KEY_EGEE = $value ; }
	function setENC_COMMENTAIRE($value) { $this->ENC_COMMENTAIRE = $value ; }
	function setENC_ID_ORIGINE($value) { $this->ENC_ID_ORIGINE = $value ; }
	function setENC_RDU_APR($value) { $this->ENC_RDU_APR = $value ; }
	//function setCLI_ID($value) { $this->CLI_ID = $value ; }
	function setENC_RDU_AVT($value) { $this->ENC_RDU_AVT = $value ; }


		// public function __construct(){
		// 	$db = new Db_connect;
		// 	$this->dbConn = $db->connect();
		// }

		public function insert(){

//Initialisation des Propriétés
$CAIS_ID = 1 ;
$ENC_FRAIS = 120 ;
$ENC_NUMERO_CHEQUE = NULL ;
$ENC_MONTANT_SUP = NULL ;
$ENC_ECART = NULL ;
$ENC_TAUX = NULL ;
$ENC_KEY_EGEE = NULL;
$ENC_COMMENTAIRE = NULL;
$ENC_DATE = date("d-m-Y H:i") ;
$ENC_MONNAIE = 0 ;
$MODEREG_ID = 14 ;
$TYPENC_ID = 1038 ;
$ENC_SYNCHRO = 0 ;
$ENC_ID_EGEE = 0 ;
$ENC_ID_ORIGINE = 1;
$ENC_RDU_APR = 0;
// Par défauts

// Provenant d'une facture
//$ENC_MONTANT = NULL ;
$CLI_ID = NULL ;
$ENC_RDU_AVT = NULL;
// Provenant d'une facture

// Par défauts -Automatique 


	 $requete4 = $this->dbConn->prepare("INSERT INTO  cw_encaissement (ENC_ID,CAIS_ID,ENC_DATE,ENC_MONTANT,ENC_NUMERO_CHEQUE,ENC_MONNAIE,ENC_MONTANT_SUP,ENC_ECART,ENC_TAUX,MODEREG_ID,TYPENC_ID,ENC_FRAIS,ENC_SYNCHRO,ENC_ID_EGEE,ENC_KEY_EGEE,ENC_COMMENTAIRE,ENC_ID_ORIGINE,ENC_RDU_APR,ENC_RDU_AVT)
			 VALUES (:ENC_ID,:CAIS_ID,:ENC_DATE,:ENC_MONTANT,:ENC_NUMERO_CHEQUE,:ENC_MONNAIE,:ENC_MONTANT_SUP,:ENC_ECART,:ENC_TAUX,:MODEREG_ID,:TYPENC_ID,:ENC_FRAIS,:ENC_SYNCHRO,:ENC_ID_EGEE,:ENC_KEY_EGEE,:ENC_COMMENTAIRE,:ENC_ID_ORIGINE,:ENC_RDU_APR,:ENC_RDU_AVT)");

			$requete4->bindParam(':ENC_ID', $ENC_ID);
			$requete4->bindParam(':CAIS_ID', $CAIS_ID);
			$requete4->bindParam(':ENC_DATE', $ENC_DATE);
			// var_dump($ENC_DATE);exit();
			$requete4->bindParam(':ENC_FRAIS', $ENC_FRAIS);
			$requete4->bindParam(':ENC_NUMERO_CHEQUE', $ENC_NUMERO_CHEQUE);
			$requete4->bindParam(':ENC_MONTANT_SUP', $ENC_MONTANT_SUP);
			$requete4->bindParam(':ENC_ECART', $ENC_ECART);
			$requete4->bindParam(':ENC_TAUX', $ENC_TAUX);
			$requete4->bindParam(':ENC_KEY_EGEE', $ENC_KEY_EGEE);
			$requete4->bindParam(':ENC_COMMENTAIRE', $ENC_COMMENTAIRE);
			$requete4->bindParam(':ENC_MONNAIE', $ENC_MONNAIE);
			$requete4->bindParam(':MODEREG_ID', $MODEREG_ID);
			$requete4->bindParam(':TYPENC_ID', $TYPENC_ID);
			$requete4->bindParam(':ENC_SYNCHRO', $ENC_SYNCHRO);
			$requete4->bindParam(':ENC_ID_EGEE', $ENC_ID_EGEE);
			$requete4->bindParam(':ENC_ID_ORIGINE', $ENC_ID_ORIGINE);
			$requete4->bindParam(':ENC_RDU_APR', $ENC_RDU_APR);
			// Provenant d'une facture
			$requete4->bindParam(':ENC_MONTANT', $ENC_MONTANT);
			// $requete2->bindParam(':CLI_ID', $this->CLI_ID);
			$requete4->bindParam(':ENC_RDU_AVT', $ENC_RDU_AVT);
			// Provenant d'une facture
			
		   $req4 = $requete4->execute();
		   // var_dump($req4);

			if ($req4->execute()) {
				return true;
			}else{
				return false;
			}
		}



}

?>
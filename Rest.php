<?php 
	require_once('constante.php');
	Class Rest{
			
			protected $request;
			protected $serviceName;
			protected $param;

		
		Public function __construct(){
			// if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			// 	$this->throwError(REQUEST_METHOD_NOT_VALID,"Cette méthode n'est pas une méthode POST");
			
			// }
			$handler = fopen('php://input', 'r');
		    $this->request = stream_get_contents($handler);
		 
		    $this->validateRequest();

		}


		Public function validateRequest(){
			// echo $_SERVER['CONTENT_TYPE'];exit();
			if ($_SERVER['CONTENT_TYPE'] !== 'application/json') {
				$this->throwError(REQUEST_CONTENTTYPE_NOT_VALID,"Le contenu de ces données n'est pas valide ");
				// Format json requis
			}

			$data = json_decode($this->request, true);
				// Premiere verif (1)
			
			if (!isset($data['name']) || $data['name'] == "") {
				$this->throwError(API_NAME_REQUIRED,"Le Nom de l'API est requis : (name)  ");
				// cette donnée est de type string mettre les doubles côtes "" .
			}
			$this->serviceName = $data['name'];

			// Premiere verif (1)
						
				// if (!is_array($data['param']) ) {
				if (empty($data['param']) ) {
				// $this->throwError(API_PARAM_REQUIRED,"Le contenu de ces données est incomplet (param) ");

				}else{

			$this->param = $data['param'];
				}

			// Premiere verif (2)
			
			// Premiere verif (2)
			// print_r($data);
		}
		

		Public function ProcessApi(){
			$api = new Api;
			$rMethod = new reflectionMethod('API', $this->serviceName);
			if (!method_exists($api, $this->serviceName)) {
				$this->throwError(API_DOES_NOT_EXIST,"L'api n'existe pas.");
			}
			$rMethod->invoke($api);
		}


		Public function validateParameter($fieldName,$value, $dataType,$required=true){
			if ($required == true && empty($value) == true) {
				$this->throwError(VALIDATE_PARAMETER_REQUIRED,"Veuillez renseigner les champs vides : ".$fieldName);
			}

			switch ($dataType) {
				case BOOLEAN:
					if (!is_bool($value)) {
						$this->throwError(VALIDATE_PARAMETER_DATATYPE,"Ces données ne sont pas valides pour ces champs ".$fieldName." ce pourrait-il qu'il soit de type : Boolean ? ");
					}
				break;

				case INTEGER:
					if (!is_numeric($value)) {
						$this->throwError(VALIDATE_PARAMETER_DATATYPE,"Ces données ne sont pas valides pour ces champs ".$fieldName." ce pourrait-il qu'il soit de type : Numeric ? ");
					}
				break;

				case STRING:
					if (!is_string($value)) {
						$this->throwError(VALIDATE_PARAMETER_DATATYPE,"Ces données ne sont pas valides pour ces champs ".$fieldName." ce pourrait-il qu'il soient de type : String ? ");
					}
				break;

				default:
						$this->throwError(VALIDATE_PARAMETER_DATATYPE,"Ces données ne sont pas valides pour ces champs ".$fieldName);
				break;
				
				
			}
			return $value;
		}


		Public function throwError($code,$message){
			header("content-type: application/json");
			$Erreur = json_encode(['Erreur'=>['status'=>$code, 'message'=>$message]]);
			echo $Erreur; exit();
		}


		Public function ReturnResponse($code, $data){
			header("content-type: application/json");
			$response = json_encode(['Response'=>['status'=>$code,'resultat'=>$data]]);
			echo $response; exit();
		}


		public function getAuthorizationHeader(){
			$headers = null;
			if (isset($_SERVER['Authorization'])) {
				$headers = trim ($_SERVER['Authorization']);
			}else if (isset($_SERVER['HTTP_AUTHORIZATION'])){
				$headers = trim ($_SERVER['HTTP_AUTHORIZATION']);
			}elseif (function_exists('apache_request_headers')) {
				$requestHeaders = apache_request_headers();
				$requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
				if (isset($requestHeaders['Authorization'])) {
					$headers = trim ($requestHeaders['Authorization']);
				}
			}
			return $headers;
		}

		/*
		* Recup le token par l'header ( l'entête )
		*/

		public function getBearerToken(){
			$headers = $this->getAuthorizationHeader();
			if (!empty($headers)) {
				if (preg_match('/Bearer\s(\S+)/', $headers,$matches)){
					return $matches[1];
				}
			}
			$this->throwError(AUTHORIZATION_HEADER_NOT_FOUND,"L'entête Authorisation n'a pas été trouvé. ");
		}
	}

 ?>
<?php 

		spl_autoload_register(function($className){
			$path = strtolower($className).".php";
			if(file_exists($path)){
				require_once($path);
			}else{
				echo "Le fichier $path est introuvable";
			}
		})
 ?>
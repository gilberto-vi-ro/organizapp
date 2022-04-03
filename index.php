<?php
	
	
	
 	/*=============================================
	PAGINAS DEL SITIO
	=============================================*/
	include_once "config.php";
	include_once ROOT_PATH."library/tool.php";
	if(isset($_GET["pagina"])){

		$pagina = ROOT_PATH."mvc/view/".$_GET["pagina"].".php";

		if (file_exists($pagina)) {
			include_once $pagina;
		}
		else{
			include_once ROOT_PATH."mvc/view/general/error/error404.php";
		}

	}else{
		include_once ROOT_PATH."mvc/view/welcome.php";
	}
	
	/*=============================================
	ICONS FONTAWESOME FREE
	=============================================*/

	#https://fontawesome.com/v5/search


?>
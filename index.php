<?php
	
	
	
 	/*=============================================
	SITE PAGES
	=============================================*/
	include_once "config.php";
	include_once ROOT_PATH."library/tool.php";
	if(isset($_GET["page"])){

		$page = ROOT_PATH."mvc/view/".$_GET["page"].".php";
		if (file_exists($page))
			include_once $page;
		else
			include_once ROOT_PATH."mvc/view/general/error/error404.php";
	}else
		include_once ROOT_PATH."mvc/view/welcome.php";
	
	/*=============================================
	ICONS FONTAWESOME FREE
	=============================================*/
	#https://fontawesome.com/v5/search
	
	/*=============================================
	ICONS BOXICON FREE
	=============================================*/
	#https://boxicons.com/



?>
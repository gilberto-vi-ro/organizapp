<?php
   http_response_code(404);
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Error 404</title>
		<meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
	    <meta name="viewport" content="width-device-width, user-scalable=no, initial-scale=1.0, maiximum-scale1.0, minimum-scale=1.0">
		<link rel="icon"  href="<?= BASE_URL ?>public/img/icon/warning.jpg">

		<link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>public/css/general/error.css">
	</head>
	<body class="body">
			<div  class="container-error">
				<div class="cont-error">
					
					<div class="cont-left"><a href="?"><label  class="p-left js-redir"> Error</label>
						<img class="img-left js-redir" src="<?= BASE_URL ?>public/img/icon/sitio_web.png"></a>
					</div>
				    
					<div class="cont-right"><a href="?"><label  class="p-right">Contactanos</label>
						<img class="img-right"  src="<?= BASE_URL ?>public/img/icon/WhatsApp.png"></a>
					</div>
				</div>
			</div>
			<div  class="container2">
				<center><h1>Error 404 BY GL</h1></center>
			</div>
            <center>
			    <p class="p-err">  Página no encontrada...No se ha localizado la URL solicitada en este servidor. Si usted ha introducido la URL manualmente, por favor revise su ortografía e inténtelo de nuevo. <br><br> Si usted cree que esto es un error del servidor, por favor comuníqueselo al administrador del portal.</p>
            </center>
		<footer>
		</footer>
	</body>
</html>
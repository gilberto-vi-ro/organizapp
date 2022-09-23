<?php 
	/**
	* 
	*/
	class LoginController	{
		
		private $LoginModel;

		function __construct()
		{
			$this->LoginModel = new LoginModel();
		}
		
		/** login */
		public function login()
		{
			$myData = $this->LoginModel->userExist($_POST);
	        if ($myData && $myData['email'] === $_POST['email']){
	           
	            if ( !password_verify(  $_POST['pwd'], $myData['pwd']) )
	            href(BASE_URL."login?notpwd");

	            # if user and paswword is true(init  session) 
	            session_start();


	            session('data_user', $myData);
	            session('id_usuario', $myData["id_usuario"]);

	            $token = md5( uniqid( mt_rand(),true ) );
	            session( 'token', $token);
				
				$this->LoginModel->setLastTime($myData["id_usuario"]);
	            if ($myData["tipo"]==0)
	            	href(BASE_URL."admin");
	            else{
					if($this->LoginModel->isExpiredLicense())
					{
						include_once ROOT_PATH."mvc/view/general/expiredLicense.php";
						exit();
					}
					else
						href(BASE_URL."home");
				}
	            	
	        }
	        else{
	            href(BASE_URL."login?notuser");
	        }
		}

		public function registerUser () 
		{
			$_POST["pwd"] = password_hash( $_POST["pwd"], PASSWORD_DEFAULT );
			$response = $this->LoginModel->register($_POST);
		    if ($response == 1){
		    	$this->createFolder();
		   		href(BASE_URL."login?user_registered");
		    }
		   	else if( $response ==2 )
		   		href(BASE_URL."login?user_exist&sign_up");
		   	else
		   		href(BASE_URL."login?user_not_register&sign_up");
		}

		public  function createFolder() 
	    {   
	    	$FileManager = new FileManager();
			$FileManager->hideExtension(['php','trash']);
	   		$FileManager->setPath("drive/");
			$FileManager->createDir($this->LoginModel->getMaxUser());
	    }
		
		public function recoverEmail(){
			if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
				echo "El correo electrónico no tiene un formato de correo electrónico válido.";
				exit();
			}

			if (!$this->LoginModel->emailExists($_POST["email"])){
				echo "El correo electrónico no existe en la base de datos.";
				exit();
			}
			
			$newPwd = generatePassword(6);
			
			$to = $_POST["email"]; 
			$subject = "OrganizApp"; 
			$body = ' 
				<html> 
				<head> 
				<title>Recuperar Contraseña</title> 
				</head> 
				<body> 
				<h1>Tu nueva Contraseña es: '.$newPwd.'</h1> 
				<p> 
					<b>Puedes acceder a la app OrganizApp desde el siguiente link:</b>
					<a href="https://myproyecto.com/organizapp/login?" >https://myproyecto.com/organizapp/login</a>
				</p> 
				</body> 
				</html> 
			'; 
			// Para enviar un correo HTML, debe establecerse la cabecera Content-type
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			if(mail($to,$subject,$body,$headers)){
				if ($this->LoginModel->updatePwd($_POST["email"], password_hash( $newPwd, PASSWORD_DEFAULT ))){
					echo 'Te hemos enviado un mensaje con asunto "OrganizApp"';
				}else{
					echo "Ocurrió un error al recuperar la contraseña.";
				}
			}
			else 
				echo 'Se produjo un error al enviar el correo electrónico';
			exit();
		}

	}


 ?>
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
	            else
	            	href(BASE_URL."home");
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

	}


 ?>